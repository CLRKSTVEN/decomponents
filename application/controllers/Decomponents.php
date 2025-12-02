<?php
defined('BASEPATH') or exit('No direct script access allowed');

use League\OAuth2\Client\Provider\Google;

class Decomponents extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url', 'form', 'directory']);
        $this->load->library(['session', 'upload']);
        $this->load->database();
        $this->load->model('Decomponents_model');
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Settings_model');
        $this->load->model('Contact_model');
        $this->load->model('Auth_visual_model');
        $this->load->model('Testimonials_model');


        // Share site settings with all views.
        $settings = $this->Settings_model->get_settings();
        $this->load->vars('siteSettings', $settings);

        if (!$this->session->userdata('ez_cart')) {
            $this->session->set_userdata('ez_cart', []);
        }
    }


    /**
     * Require admin session.
     */
    private function require_admin()
    {
        $level = $this->session->userdata('level');
        // Accept both legacy capitalized and lowercase roles.
        if (!in_array(strtolower((string)$level), ['admin'], true)) {
            $this->session->set_flashdata('error', 'Admin access required.');
            redirect('home_page.php');
            exit;
        }
    }

    /**
     * Redirect admins away from public shopping pages.
     */
    private function redirect_admin_to_dashboard()
    {
        $level = strtolower((string)$this->session->userdata('level'));
        if ($level === 'admin') {
            redirect('Decomponents/admin');
            exit;
        }
    }

    /**
     * Seed default categories if missing and return all categories.
     */
    private function ensure_default_categories()
    {
        if (!$this->db->table_exists('categories')) {
            return [];
        }

        $defaults = [
            ['name' => 'Central Processor Unit (CPU)', 'slug' => 'cpu'],
            ['name' => 'Power Supply', 'slug' => 'power-supply'],
            ['name' => 'GPU', 'slug' => 'gpu'],
        ];

        $slugs = array_map(function ($row) {
            return strtolower((string)$row['slug']);
        }, $this->db->select('slug')->from('categories')->where_in('slug', array_column($defaults, 'slug'))->get()->result_array());

        foreach ($defaults as $cat) {
            if (!in_array(strtolower($cat['slug']), $slugs, true)) {
                if ($this->db->field_exists('created_at', 'categories')) {
                    $cat['created_at'] = date('Y-m-d H:i:s');
                }
                $this->db->insert('categories', $cat);
            }
        }

        return $this->Category_model->all();
    }

    /**
     * Ensure an upload directory exists and is writable.
     */
    private function ensure_upload_dir($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        if (!is_writable($path)) {
            @chmod($path, 0775);
        }
        return is_dir($path) && is_writable($path);
    }

    /**
     * Display name helper: "Last, First Middle" fallback to available parts.
     */
    private function format_display_name($user)
    {
        if (!$user) {
            return 'Guest';
        }

        $first  = trim((string)($user->FirstName ?? ''));
        $middle = trim((string)($user->MiddleName ?? ''));
        $last   = trim((string)($user->LastName ?? ''));

        $front = trim($first . ($middle !== '' ? ' ' . $middle : ''));

        if ($last !== '' && $front !== '') {
            return $last . ', ' . $front;
        }
        if ($last !== '') {
            return $last;
        }
        if ($front !== '') {
            return $front;
        }

        // Fallback if names are missing (e.g., OAuth without name fields)
        if (!empty($user->email)) {
            return $user->email;
        }

        return 'Guest';
    }

    /**
     * Return the active user id from any of the known session keys.
     */
    private function current_user_id()
    {
        $candidates = ['ez_user_id', 'userID', 'id'];
        foreach ($candidates as $key) {
            $val = $this->session->userdata($key);
            if (!empty($val)) {
                return (int)$val;
            }
        }
        return null;
    }

    /**
     * Persist the requested post-login destination so we can reuse it after OAuth redirects.
     */
    private function remember_next_from_request()
    {
        $next = trim((string)$this->input->post('next', true));
        if ($next === '') {
            $next = trim((string)$this->input->get('next', true));
        }
        if ($next !== '') {
            $this->session->set_userdata('login_next_redirect', $next);
        }
        return $next;
    }

    /**
     * Return a safe, same-site redirect target falling back when missing or external.
     */
    private function get_safe_next_url($fallback = 'Decomponents/shop')
    {
        $fallbackUrl = preg_match('#^https?://#i', $fallback) ? $fallback : site_url($fallback);

        $candidate = trim((string)$this->input->post('next', true));
        if ($candidate === '') {
            $candidate = trim((string)$this->input->get('next', true));
        }

        $usedSession = false;
        if ($candidate === '') {
            $candidate = (string)$this->session->userdata('login_next_redirect');
            $usedSession = $candidate !== '';
        }

        if ($candidate === '') {
            return $fallbackUrl;
        }

        $parts = @parse_url($candidate);
        if ($parts === false) {
            if ($usedSession) {
                $this->session->unset_userdata('login_next_redirect');
            }
            return $fallbackUrl;
        }

        $host = $_SERVER['HTTP_HOST'] ?? '';
        $isRelative = !isset($parts['host']) && !isset($parts['scheme']);
        $isSameHost = isset($parts['host']) && $host !== '' && strcasecmp($parts['host'], $host) === 0;

        if (!$isRelative && !$isSameHost) {
            if ($usedSession) {
                $this->session->unset_userdata('login_next_redirect');
            }
            return $fallbackUrl;
        }

        if ($usedSession) {
            $this->session->unset_userdata('login_next_redirect');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        if ($isRelative) {
            if ($host === '') {
                return $fallbackUrl;
            }
            if ($path === '') {
                $path = $candidate;
            }
            if (strpos($path, '/') !== 0) {
                $path = '/' . ltrim($path, '/');
            }
            return $scheme . '://' . $host . $path . $query . $fragment;
        }

        // Same host but protocol-relative or missing scheme
        if (empty($parts['scheme'])) {
            return $scheme . '://' . $parts['host'] . $port . $path . $query . $fragment;
        }

        return $candidate;
    }
    private function build_google_provider()
    {
        $this->load->config('google_oauth');

        $clientId     = $this->config->item('google_client_id');
        $clientSecret = $this->config->item('google_client_secret');
        $redirectUriRaw  = $this->config->item('google_redirect_uri');

        // Normalize redirect URI to the current host/port to avoid invalid_grant when the app
        // runs under a different hostname than what was hard-coded in config.
        $redirectUri = site_url('google_callback');
        if (!empty($redirectUriRaw)) {
            $parsed = parse_url($redirectUriRaw);
            if (!empty($parsed['path'])) {
                $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                $host   = $_SERVER['HTTP_HOST'] ?? ($parsed['host'] ?? '');
                // Rebuild with the current host to avoid mismatch while keeping the intended path.
                $redirectUri = $scheme . '://' . $host . $parsed['path'];
            }
        }

        if (empty($clientId) || empty($clientSecret) || empty($redirectUri)) {
            log_message('error', 'Google OAuth configuration is incomplete in google_oauth.php.');
            show_error('Google sign-in is not configured. Please contact support.', 500);
        }

        if (!class_exists(\League\OAuth2\Client\Provider\Google::class)) {
            log_message('error', 'Google OAuth provider class not found. Check composer autoload and league/oauth2-google installation.');
            show_error('Google sign-in is temporarily unavailable. Please contact support.', 500);
        }

        return new Google([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $redirectUri,
            // Force OIDC userinfo endpoint; avoids Legacy People API
            'scope'        => ['openid', 'profile', 'email'],
        ]);
    }

    public function login_google()
    {
        // Remember where to return after Google completes auth.
        $this->remember_next_from_request();

        // Build provider
        $provider = $this->build_google_provider();

        // Get auth URL
        $authUrl = $provider->getAuthorizationUrl([
            'scope'       => ['openid', 'profile', 'email'],
            'access_type' => 'offline',
            // ❌ REMOVE prompt, because the library already sends approval_prompt
            // 'prompt'      => 'select_account',
            // optional: you can add this if you really want force approval in this old lib:
            // 'approval_prompt' => 'force',
        ]);

        log_message('debug', 'GOOGLE AUTH URL: ' . $authUrl);

        $this->session->set_userdata('google_oauth_state', $provider->getState());
        redirect($authUrl);
    }


    public function google_callback()
    {
        $provider = $this->build_google_provider();

        $state = $this->input->get('state', TRUE);
        $code  = $this->input->get('code', TRUE);
        $error = $this->input->get('error', TRUE);

        // User cancelled or Google returned an error
        if (!empty($error)) {
            $this->session->set_flashdata('error', 'Google sign-in was cancelled or failed: ' . $error);
            redirect('home_page.php');
            return;
        }

        // CSRF / state mismatch
        $savedState = $this->session->userdata('google_oauth_state');
        if (!$state || !$savedState || $state !== $savedState) {
            $this->session->unset_userdata('google_oauth_state');
            $this->session->set_flashdata('error', 'Invalid Google login session. Please try again.');
            redirect('home_page.php');
            return;
        }

        try {
            // Exchange code for access token
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code,
            ]);

            $tokenValues = method_exists($token, 'getValues') ? $token->getValues() : [];
            $idToken     = $tokenValues['id_token'] ?? null;

            // Fetch profile via the OpenID userinfo endpoint (avoids the Legacy People API dependency)
            $googleUser = $this->fetch_google_profile($token->getToken(), $idToken);
            if (!$googleUser || empty($googleUser['email'])) {
                throw new \RuntimeException('Missing profile data from Google userinfo endpoint.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Google OAuth error: ' . $e->getMessage());
            // Mirror to PHP error log in case CI logging is disabled.
            error_log('Google OAuth error: ' . $e->getMessage());
            $errorMsg = 'Unable to sign in with Google right now. Please try again.';
            // In non-production, surface the underlying reason to help debug redirect_uri mismatches, etc.
            if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production') {
                $errorMsg .= ' (' . $e->getMessage() . ')';
            }
            $this->session->set_flashdata('error', $errorMsg);
            redirect('home_page.php');
            return;
        }

        // Clear state once used
        $this->session->unset_userdata('google_oauth_state');

        // Extract profile fields
        $email    = $googleUser['email'] ?? null;
        $fullName = $googleUser['name'] ?? trim(($googleUser['given_name'] ?? '') . ' ' . ($googleUser['family_name'] ?? ''));
        $avatar   = $googleUser['picture'] ?? null;

        if (empty($email)) {
            $this->session->set_flashdata('error', 'Google did not provide an email address.');
            redirect('home_page.php');
            return;
        }

        // Check if user already exists
        $user = $this->Decomponents_model->get_user_by_email($email);

        // If no user yet, auto-create a customer account
        if (!$user) {
            $first = '';
            $middle = '';
            $last = '';

            if (!empty($fullName)) {
                $parts  = preg_split('/\s+/', trim($fullName));
                $first  = $parts[0] ?? '';
                $last   = $parts[count($parts) - 1] ?? '';
                if (count($parts) > 2) {
                    $middle = implode(' ', array_slice($parts, 1, -1));
                }
            }

            // Fallbacks if Google name is weird
            if ($first === '' && $last === '') {
                $first = 'Google';
                $last  = 'User';
            }

            // Random local password (not used for Google login)
            $randomPass = bin2hex(random_bytes(8));

            $userData = [
                'FirstName'       => $first,
                'MiddleName'      => $middle,
                'LastName'        => $last,
                'email'           => $email,
                'password'        => password_hash($randomPass, PASSWORD_DEFAULT),
                'profile_picture' => $avatar,
                // Use lowercase enum to match the DB column definition
                'role'            => 'customer',
                'created_at'      => date('Y-m-d H:i:s'),
            ];

            $newId = $this->Decomponents_model->create_user($userData);
            if (!$newId) {
                $this->session->set_flashdata('error', 'Unable to create an account from your Google profile.');
                redirect('home_page.php');
                return;
            }

            $user = $this->Decomponents_model->get_user_by_id($newId);
        }

        // Log in the user (same session keys as normal login)
        $displayName = $this->format_display_name($user);

        $this->session->set_userdata([
            'ez_user_id'     => $user->id,
            'level'          => $user->role ?: 'customer',
            'ez_user_name'   => $displayName,
            'ez_user_avatar' => $user->profile_picture ?: 'upload/profile/avatar.png',
        ]);

        // Send admins to dashboard, customers to shop, unless a safe "next" URL was provided.
        $defaultRedirect = strtolower((string)$user->role) === 'admin' ? 'Decomponents/admin' : 'Decomponents/shop';
        redirect($this->get_safe_next_url($defaultRedirect));
    }

    /**
     * Fetch Google profile using the OpenID Connect userinfo endpoint, avoiding the Legacy People API.
     */
    private function fetch_google_profile($accessToken, $idToken = null)
    {
        $profile = null;

        // Try the userinfo endpoint first.
        if (!empty($accessToken)) {
            $url = 'https://openidconnect.googleapis.com/v1/userinfo';

            if (function_exists('curl_init')) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                $resp = curl_exec($ch);
                curl_close($ch);
            } else {
                $context = stream_context_create([
                    'http' => [
                        'method'  => 'GET',
                        'header'  => "Authorization: Bearer {$accessToken}\r\n",
                        'timeout' => 5,
                    ]
                ]);
                $resp = @file_get_contents($url, false, $context);
            }

            if ($resp !== false) {
                $decoded = json_decode($resp, true);
                if (is_array($decoded) && !empty($decoded['email'])) {
                    $profile = $decoded;
                }
            }
        }

        // Fallback: decode ID token to at least get email/name/picture (best-effort; not signature-verified here).
        if (!$profile && !empty($idToken)) {
            $parts = explode('.', $idToken);
            if (count($parts) === 3) {
                $payloadJson = $this->base64_url_decode($parts[1]);
                $payload     = json_decode($payloadJson, true);
                if (is_array($payload)) {
                    $profile = [
                        'email'       => $payload['email'] ?? null,
                        'name'        => $payload['name'] ?? trim(($payload['given_name'] ?? '') . ' ' . ($payload['family_name'] ?? '')),
                        'given_name'  => $payload['given_name'] ?? null,
                        'family_name' => $payload['family_name'] ?? null,
                        'picture'     => $payload['picture'] ?? null,
                    ];
                }
            }
        }

        return $profile;
    }

    private function base64_url_decode($data)
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $data .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * Visitor landing page (from VisitorPage.php)
     * Accessible even if not logged-in.
     */
    public function visitor()
    {
        $this->redirect_admin_to_dashboard();

        $data = [
            'page_title' => 'Welcome to DeComponents',
        ];
        $this->load->view('decomponents/visitor', $data);
    }
    // 1) LANDING PAGE: http://localhost/decomponents/
    public function index()
    {
        $this->redirect_admin_to_dashboard();

        // Get all products with their categories
        $featured = $this->Product_model->get_all_with_categories();

        // Randomize a bit so the landing page feels fresh each time (optional)
        if (is_array($featured) && count($featured) > 1) {
            shuffle($featured);
        }

        // Show only 8 items on the landing featured grid.
        if (is_array($featured) && count($featured) > 8) {
            $featured = array_slice($featured, 0, 8);
        }

        $data = [
            'page_title'       => 'Welcome to DeComponents',
            'featuredProducts' => $featured,
        ];

        $this->load->view('decomponents/landing', $data);
    }
    // 2) SHOP PAGE (PRODUCT GRID)
    public function shop($category = 'all')
    {
        // Admins should manage via dashboard, not browse shop UI.
        $this->redirect_admin_to_dashboard();

        $category = strtolower((string)$category);

        // Allowed category slugs from DB (you can add more here later)
        $allowedCategories = [
            'all'               => 'All Components',
            'imported-components' => 'Imported Components',
            'cpu'               => 'Central Processor Unit (CPU)',
            'gpu'               => 'Graphics Cards (GPU)',
            'power-supply'      => 'Power Supplies',
        ];

        // If invalid segment is passed, default to "all"
        if (!array_key_exists($category, $allowedCategories)) {
            $category = 'all';
        }

        // Pull ALL products with categories once
        $allProducts = $this->Product_model->get_all_with_categories();
        if (!is_array($allProducts)) {
            $allProducts = [];
        }

        // Filter by category slug (if not "all")
        $filtered = [];
        if ($category === 'all') {
            $filtered = $allProducts;
        } else {
            foreach ($allProducts as $row) {
                // Expecting 'category_slug' from get_all_with_categories join
                $rowSlug = isset($row['category_slug']) ? strtolower($row['category_slug']) : '';
                if ($rowSlug === $category) {
                    $filtered[] = $row;
                }
            }
        }

        // Basic pagination
        $page    = max(1, (int)$this->input->get('page'));
        $perPage = 12;
        $total   = count($filtered);

        $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 1;
        if ($totalPages < 1) {
            $totalPages = 1;
        }
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $offset       = ($page - 1) * $perPage;
        $pagedProducts = array_slice($filtered, $offset, $perPage);

        // Normalize for the view (id, name, slug, price, image, description)
        $products = [];
        $normalizeImg = function ($path) {
            if (!$path) {
                return base_url('Pictures/DeComponents.jpeg');
            }
            if (preg_match('#^https?://#i', $path) || strpos($path, '//') === 0) {
                return $path;
            }
            return base_url(ltrim($path, '/'));
        };
        foreach ($pagedProducts as $row) {
            $products[] = [
                'id'          => $row['id'] ?? null,
                'name'        => $row['name'] ?: ($row['slug'] ?? 'Product'),
                'slug'        => $row['slug'] ?? '',
                'price'       => isset($row['price']) ? (float)$row['price'] : 0,
                'image'       => $normalizeImg(!empty($row['image']) ? $row['image'] : 'Pictures/DeComponents.jpeg'),
                'description' => $row['description'] ?? '',
            ];
        }

        // Cart counter from session
        $cart = $this->session->userdata('ez_cart') ?? [];
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['qty'] ?? 1);
        }

        // Human-friendly category title
        $categoryTitle = $allowedCategories[$category] ?? 'All Components';

        $data = [
            'page_title'    => 'DeComponents - ' . $categoryTitle,
            'products'      => $products,
            'cartCount'     => $cartCount,
            'category'      => $category,
            'categoryTitle' => $categoryTitle,
            // no more clothing folders / emptyPath — but keep the key if your view expects it
            'emptyPath'     => '',
            'currentPage'   => $page,
            'perPage'       => $perPage,
            'totalItems'    => $total,
            'baseUrl'       => site_url('Decomponents/shop/' . $category),
        ];

        // If logged in, surface the user details for greetings
        $userId = $this->session->userdata('ez_user_id');
        if ($userId) {
            $data['user'] = $this->Decomponents_model->get_user_by_id($userId);
        }

        // Use the public products view so customers can browse and checkout.
        $this->load->view('pages/Products', $data);
    }



    /**
     * Admin dashboard.
     */
    public function admin()
    {
        $this->require_admin();

        $stats = [
            'products' => $this->Decomponents_model->count_products(),
            'orders'   => $this->Decomponents_model->count_orders(),
            'pending'  => $this->Decomponents_model->count_orders('pending'),
            'customers' => $this->Decomponents_model->count_users('customer'),
        ];

        $recentOrders = $this->Decomponents_model->recent_orders(5);

        $data = [
            'page_title'   => 'Admin Dashboard',
            'stats'        => $stats,
            'recentOrders' => $recentOrders,
        ];

        $this->load->view('dashboard_admin', $data);
    }

    /**
     * Admin: orders list.
     */
    public function orders()
    {
        $this->require_admin();
        $data = [
            'page_title' => 'Orders',
            'orders'     => $this->Decomponents_model->get_all_orders(100),
        ];
        $this->load->view('decomponents/admin_orders', $data);
    }

    /**
     * Admin: update order status.
     */
    public function update_order_status()
    {
        $this->require_admin();
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/orders');
        }

        $orderId = (int)$this->input->post('order_id');
        $status  = strtolower(trim($this->input->post('status')));
        $allowed = ['pending', 'paid', 'fulfilled', 'cancelled'];

        if (!$orderId || !in_array($status, $allowed, true)) {
            $this->session->set_flashdata('error', 'Invalid status update.');
            return redirect('Decomponents/orders');
        }

        $ok = $this->Decomponents_model->update_order_status($orderId, $status);
        $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Order status updated.' : 'Unable to update order.');
        redirect('Decomponents/orders');
    }

    /**
     * Admin: customers list.
     */
    public function customers()
    {
        $this->require_admin();
        $data = [
            'page_title' => 'Customers',
            'customers'  => $this->Decomponents_model->get_customers(100),
        ];
        $this->load->view('decomponents/admin_customers', $data);
    }

    /**
     * Admin: manage support staff for incoming messages.
     */
    public function staff()
    {
        $this->require_admin();
        $data = [
            'page_title' => 'Support Staff',
            'staff'      => $this->Contact_model->get_staff(100),
        ];
        $this->load->view('decomponents/admin_staff', $data);
    }

    /**
     * Admin: add a staff member.
     */
    public function save_staff()
    {
        $this->require_admin();
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/staff');
        }

        $name  = trim($this->input->post('name', true));
        $email = trim($this->input->post('email', true));
        $phone = trim($this->input->post('phone', true));
        $role  = trim($this->input->post('role', true));

        if (!$name || !$email) {
            $this->session->set_flashdata('error', 'Name and email are required.');
            return redirect('Decomponents/staff');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Enter a valid email address.');
            return redirect('Decomponents/staff');
        }

        $payload = [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone ?: null,
            'role'       => $role ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $ok = $this->Contact_model->add_staff($payload);
        if (!$ok && $this->db->error()['code'] === 1062) {
            $this->session->set_flashdata('error', 'A staff entry with that email already exists.');
        } elseif ($ok) {
            $this->session->set_flashdata('success', 'Staff member saved.');
        } else {
            $this->session->set_flashdata('error', 'Unable to save staff member.');
        }

        redirect('Decomponents/staff');
    }

    /**
     * Admin: delete staff record.
     */
    public function delete_staff($id)
    {
        $this->require_admin();
        $id = (int)$id;
        if ($id) {
            $this->Contact_model->delete_staff($id);
            $this->session->set_flashdata('success', 'Staff member removed.');
        } else {
            $this->session->set_flashdata('error', 'Invalid staff id.');
        }
        redirect('Decomponents/staff');
    }

    /**
     * Admin: quick auto-login and seed admin user.
     * Note: protect this route in production; intended for local setup convenience.
     */
    public function admin_autologin()
    {
        $admin = $this->Decomponents_model->ensure_admin_user('admin@decomponents.local', 'admin123');
        if ($admin) {
            $avatar = $admin->profile_picture ?: 'upload/profile/avatar.png';
            $displayName = $this->format_display_name($admin);
            $this->session->set_userdata([
                'ez_user_id'   => $admin->id,
                'level'        => $admin->role ?: 'admin',
                'ez_user_name' => $displayName,
                'ez_user_avatar' => $avatar,
            ]);
        }
        redirect('Decomponents/admin');
    }

    /**
     * Admin: list/manage products.
     */
    public function products()
    {
        $this->require_admin();

        $products   = $this->Product_model->get_all_with_categories();
        $categories = $this->ensure_default_categories();
        $editId     = (int)$this->input->get('edit');
        $editProduct = $editId ? $this->Product_model->get_product($editId) : null;

        $data = [
            'page_title' => 'Manage Products',
            'products'   => $products,
            'categories' => $categories,
            'editProduct' => $editProduct,
        ];

        $this->load->view('decomponents/admin_products', $data);
    }

    /**
     * Admin: view/edit site settings.
     */
    public function settings()
    {
        $this->require_admin();
        $data = [
            'page_title' => 'Shop Settings',
            'settings'   => $this->Settings_model->get_settings(),
        ];
        $this->load->view('decomponents/admin_settings', $data);
    }

    /**
     * Admin: manage testimonials for homepage.
     */
    public function testimonials($id = null)
    {
        $this->require_admin();
        $editId = $id ? (int)$id : null;
        $edit   = $editId ? $this->Testimonials_model->find($editId) : null;

        $data = [
            'page_title'  => 'Testimonials',
            'testimonials' => $this->Testimonials_model->all(),
            'edit'        => $edit,
        ];

        $this->load->view('decomponents/admin_testimonials', $data);
    }

    /**
     * Admin: save testimonial.
     */
    public function save_testimonial($id = null)
    {
        $this->require_admin();
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/testimonials');
        }

        $payload = [
            'name'       => trim($this->input->post('name', true)),
            'role'       => trim($this->input->post('role', true)),
            'quote'      => trim($this->input->post('quote', true)),
            'is_active'  => $this->input->post('is_active') ? 1 : 0,
        ];

        $manualImage = trim($this->input->post('image_manual', true));
        if ($manualImage !== '') {
            $payload['image'] = $manualImage;
        }

        if (!empty($_FILES['image']['name'])) {
            $uploadPath = FCPATH . 'upload/testimonials/';
            if (!$this->ensure_upload_dir($uploadPath)) {
                $this->session->set_flashdata('error', 'Upload folder is not writable: ' . $uploadPath);
                return redirect('Decomponents/testimonials' . ($id ? '/' . $id : ''));
            }
            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048,
                'file_name'     => 'testimonial_' . time(),
            ];
            $this->upload->initialize($config);
            if ($this->upload->do_upload('image')) {
                $fileData = $this->upload->data();
                $payload['image'] = 'upload/testimonials/' . $fileData['file_name'];
            } else {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
                return redirect('Decomponents/testimonials' . ($id ? '/' . $id : ''));
            }
        } elseif ($id) {
            $existing = $this->Testimonials_model->find($id);
            if (!empty($existing['image'])) {
                $payload['image'] = $existing['image'];
            }
        }

        if (!$payload['name'] || !$payload['quote']) {
            $this->session->set_flashdata('error', 'Name and quote are required.');
            return redirect('Decomponents/testimonials' . ($id ? '/' . $id : ''));
        }

        $ok = $id
            ? $this->Testimonials_model->update($id, $payload)
            : $this->Testimonials_model->create($payload);

        $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Saved testimonial.' : 'Unable to save testimonial.');
        return redirect('Decomponents/testimonials');
    }

    /**
     * Admin: delete testimonial.
     */
    public function delete_testimonial($id)
    {
        $this->require_admin();
        $id = (int)$id;
        if ($id) {
            $this->Testimonials_model->delete($id);
            $this->session->set_flashdata('success', 'Testimonial deleted.');
        } else {
            $this->session->set_flashdata('error', 'Invalid testimonial id.');
        }
        redirect('Decomponents/testimonials');
    }

    /**
     * Admin: save site settings.
     */
    public function save_settings()
    {
        $this->require_admin();
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/settings');
        }

        $payload = [
            'site_name'     => trim($this->input->post('site_name', true)),
            'tagline'       => trim($this->input->post('tagline', true)),
            'support_email' => trim($this->input->post('support_email', true)),
            'support_phone' => trim($this->input->post('support_phone', true)),
            'address'       => trim($this->input->post('address', true)),
            'logo_path'     => trim($this->input->post('logo_path', true)),
            'facebook_url'  => trim($this->input->post('facebook_url', true)),
            'instagram_url' => trim($this->input->post('instagram_url', true)),
            'youtube_url'   => trim($this->input->post('youtube_url', true)),
            'hero_title'    => trim($this->input->post('hero_title', true)),
            'hero_subtitle' => trim($this->input->post('hero_subtitle', true)),
            'hero_cta_label' => trim($this->input->post('hero_cta_label', true)),
            'hero_cta_link' => trim($this->input->post('hero_cta_link', true)),
        ];

        $this->Settings_model->upsert_settings($payload);
        $this->session->set_flashdata('success', 'Settings updated.');
        redirect('Decomponents/settings');
    }

    /**
     * Admin: manage login/signup hero visuals.
     */
    public function auth_visuals($id = null)
    {
        $this->require_admin();

        $editId = $id ? (int)$id : null;
        $editVisual = $editId ? $this->Auth_visual_model->find($editId) : null;

        $data = [
            'page_title'   => 'Auth Visuals',
            'visuals'      => $this->Auth_visual_model->all(),
            'edit_visual'  => $editVisual,
            'active_login' => $this->Auth_visual_model->get_active_by_page('login'),
            'active_signup' => $this->Auth_visual_model->get_active_by_page('signup'),
        ];

        $this->load->view('decomponents/admin_auth_visuals', $data);
    }

    /**
     * Admin: save a login/signup visual.
     */
    public function save_auth_visual()
    {
        $this->require_admin();
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/auth_visuals');
        }

        $id   = (int)$this->input->post('id');
        $page = strtolower(trim($this->input->post('page', true)));
        $allowedPages = ['login', 'signup'];

        if (!in_array($page, $allowedPages, true)) {
            $this->session->set_flashdata('error', 'Choose whether this visual is for Login or Signup.');
            return redirect('Decomponents/auth_visuals');
        }

        $payload = [
            'page'          => $page,
            'badge'         => trim($this->input->post('badge', true)),
            'headline'      => trim($this->input->post('headline', true)),
            'subheadline'   => trim($this->input->post('subheadline', true)),
            'feature_one'   => trim($this->input->post('feature_one', true)),
            'feature_two'   => trim($this->input->post('feature_two', true)),
            'feature_three' => trim($this->input->post('feature_three', true)),
            'feature_four'  => trim($this->input->post('feature_four', true)),
            'is_active'     => $this->input->post('is_active') ? 1 : 0,
        ];

        $manualImagePath = trim($this->input->post('image_path_manual', true));
        if ($manualImagePath !== '') {
            $payload['image_path'] = $manualImagePath;
        }

        if (empty($payload['headline'])) {
            $this->session->set_flashdata('error', 'Headline is required.');
            return redirect('Decomponents/auth_visuals' . ($id ? '/' . $id : ''));
        }

        // Handle optional image upload.
        if (!empty($_FILES['image_path']['name'])) {
            $uploadPath = FCPATH . 'upload/auth/';
            if (!$this->ensure_upload_dir($uploadPath)) {
                $this->session->set_flashdata('error', 'Upload folder is not writable: ' . $uploadPath);
                return redirect('Decomponents/auth_visuals' . ($id ? '/' . $id : ''));
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 4096,
                'file_name'     => 'auth_visual_' . $page . '_' . time(),
            ];

            $this->upload->initialize($config);
            if ($this->upload->do_upload('image_path')) {
                $fileData = $this->upload->data();
                $payload['image_path'] = 'upload/auth/' . $fileData['file_name'];
            } else {
                $this->session->set_flashdata('error', strip_tags($this->upload->display_errors('', '')));
                return redirect('Decomponents/auth_visuals' . ($id ? '/' . $id : ''));
            }
        } else {
            // Preserve existing image when updating without a new file.
            if ($id) {
                $existing = $this->Auth_visual_model->find($id);
                if (!empty($existing['image_path'])) {
                    $payload['image_path'] = $existing['image_path'];
                }
            }
        }

        $ok = $id
            ? $this->Auth_visual_model->update($id, $payload)
            : $this->Auth_visual_model->create($payload);

        if ($ok) {
            $this->session->set_flashdata('success', $id ? 'Visual updated.' : 'Visual created.');
        } else {
            $this->session->set_flashdata('error', 'Unable to save the visual.');
        }

        redirect('Decomponents/auth_visuals');
    }

    /**
     * Admin: delete a visual.
     */
    public function delete_auth_visual($id)
    {
        $this->require_admin();
        $id = (int)$id;
        if ($id) {
            $this->Auth_visual_model->delete($id);
            $this->session->set_flashdata('success', 'Visual deleted.');
        } else {
            $this->session->set_flashdata('error', 'Invalid visual id.');
        }
        redirect('Decomponents/auth_visuals');
    }

    /**
     * Admin: create or update a product.
     */
    public function save_product($id = null)
    {
        $this->require_admin();

        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/products');
        }

        $name        = trim($this->input->post('name', true));
        $category_id = (int)$this->input->post('category_id');
        $price       = (float)$this->input->post('price');
        $inventory   = (int)$this->input->post('inventory');
        $description = trim($this->input->post('description'));
        $slug        = strtolower(url_title($name));
        $is_featured = $this->input->post('is_featured') ? 1 : 0;

        if (!$name || !$category_id || $price <= 0) {
            $this->session->set_flashdata('error', 'Name, category, and price are required.');
            return redirect('Decomponents/products');
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $uploadPath = FCPATH . 'upload/products/';
            if (!$this->ensure_upload_dir($uploadPath)) {
                $this->session->set_flashdata('error', 'Upload folder is not writable: ' . $uploadPath);
                return redirect('Decomponents/products');
            }

            $config = [
                'upload_path'   => $uploadPath,
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true,
            ];
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('image')) {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                return redirect('Decomponents/products');
            }

            $fileData = $this->upload->data();
            $imagePath = 'upload/products/' . $fileData['file_name'];
        }

        $payload = [
            'category_id' => $category_id,
            'name'        => $name,
            'slug'        => $slug,
            'description' => $description,
            'price'       => $price,
            'is_featured' => $is_featured,
            'inventory'   => $inventory,
        ];

        if ($imagePath) {
            $payload['image'] = $imagePath;
        }

        if ($id) {
            $this->Product_model->update_product($id, $payload);
            $this->session->set_flashdata('success', 'Product updated.');
        } else {
            $payload['created_at'] = date('Y-m-d H:i:s');
            $this->Product_model->create_product($payload);
            $this->session->set_flashdata('success', 'Product created.');
        }

        redirect('Decomponents/products');
    }

    /**
     * Admin: delete product.
     */
    public function delete_product($id)
    {
        $this->require_admin();
        if ($id) {
            $this->Product_model->delete_product($id);
            $this->session->set_flashdata('success', 'Product deleted.');
        }
        redirect('Decomponents/products');
    }

    // 3) LOGIN PAGE
    public function login()
    {
        $data = [
            'page_title'         => 'Login',
            'error'              => null,
            'old'                => [],
            'auth_visual'        => $this->Auth_visual_model->get_active_by_page('login'),
            'next'               => $this->remember_next_from_request(),
        ];

        // Surface OAuth/other flash errors (e.g., Google login failures)
        $flashError = $this->session->flashdata('error');
        if (!empty($flashError)) {
            $data['error'] = $flashError;
        }

        if ($this->input->post()) {
            $email    = trim($this->input->post('email', true));
            $password = (string)$this->input->post('password');

            $data['old'] = ['email' => $email];

            if (empty($email) || empty($password)) {
                $data['error'] = 'Email and password are required.';
            } else {
                // Master admin fallback: ensures the admin exists and logs in with a known credential.
                $masterEmail = 'admin@decomponents.local';
                $masterPass  = 'admin123';
                if (strcasecmp($email, $masterEmail) === 0 && $password === $masterPass) {
                    $admin = $this->Decomponents_model->ensure_admin_user($masterEmail, $masterPass);
                    if ($admin) {
                        $avatar = $admin->profile_picture ?: 'upload/profile/avatar.png';
                        $displayName = $this->format_display_name($admin);
                        $this->session->set_userdata([
                            'ez_user_id'     => $admin->id,
                            'level'          => $admin->role ?: 'admin',
                            'ez_user_name'   => $displayName,
                            'ez_user_avatar' => $avatar,
                        ]);
                        return redirect($this->get_safe_next_url('Decomponents/shop'));
                    }
                }

                $user = $this->Decomponents_model->get_user_by_email($email);

                if (!$user) {
                    $data['error'] = 'Account not found.';
                } else {
                    $stored = $user->password ?? '';
                    $valid  = password_verify($password, $stored);

                    // Gracefully handle legacy plain-text passwords by rehashing them.
                    if (!$valid && $stored === $password) {
                        $valid = true;
                        $this->db->where('id', $user->id)->update('users', [
                            'password' => password_hash($password, PASSWORD_DEFAULT)
                        ]);
                    }

                    if ($valid) {
                        $avatar = $user->profile_picture ?: 'upload/profile/avatar.png';
                        $this->session->set_userdata('ez_user_avatar', $avatar);
                        $this->session->set_userdata('ez_user_address', $user->address ?? '');
                        $displayName = $this->format_display_name($user);
                        $this->session->set_userdata([
                            'ez_user_id'   => $user->id,
                            'level'        => $user->role ?: 'customer',
                            'ez_user_name' => $displayName,
                        ]);

                        // Send admins to dashboard, customers to shop, unless a safe "next" was provided.
                        $defaultRedirect = strtolower((string)$user->role) === 'admin' ? 'Decomponents/admin' : 'Decomponents/shop';
                        return redirect($this->get_safe_next_url($defaultRedirect));
                    }

                    $data['error'] = 'Incorrect password.';
                }
            }
        }

        // Bubble any validation/auth error to the legacy login view.
        if (!empty($data['error'])) {
            $this->session->set_flashdata('msg', $data['error']);
        }

        // home_page.php expects a $data array with login form imagery.
        $loginImage = '';
        if (!empty($data['auth_visual']['image_path'])) {
            $loginImage = (string)$data['auth_visual']['image_path'];
        }
        if ($loginImage === '') {
            $loginImage = 'assets/images/login/2.jpg';
        }

        $viewPayload = [
            'data'          => [(object)['login_form_image' => $loginImage, 'loginFormImage' => $loginImage]],
            'login_visual'  => $data['auth_visual'],
            'allow_signup'  => 'Yes',
            'active_sy'     => '',
            'active_sem'    => '',
        ];

        return $this->load->view('decomponents/login', $viewPayload);
    }

    // 4) SIGNUP PAGE
    public function signup()
    {
        $this->load->config('recaptcha');
        $data = [
            'page_title'         => 'Create Account',
            'error'              => null,
            'old'                => [],
            'recaptcha_site_key' => $this->config->item('recaptcha_site_key'),
            'auth_visual'        => $this->Auth_visual_model->get_active_by_page('signup'),
            'next'               => $this->remember_next_from_request(),
        ];

        if ($this->input->post()) {
            $first    = trim($this->input->post('first_name', true));
            $middle   = trim($this->input->post('middle_name', true));
            $last     = trim($this->input->post('last_name', true));
            $email    = trim($this->input->post('email', true));
            $password = (string)$this->input->post('password');
            $confirm  = (string)$this->input->post('confirm_password');
            $acceptPrivacy = (bool)$this->input->post('accept_privacy');
            $recaptchaToken = (string)$this->input->post('g-recaptcha-response');
            $uploadedAvatar = '';

            $data['old'] = [
                'first_name'      => $first,
                'middle_name'     => $middle,
                'last_name'       => $last,
                'email'           => $email,
                'accept_privacy'  => $acceptPrivacy,
            ];

            if (!$first || !$last || !$email || !$password || !$confirm) {
                $data['error'] = 'Please complete all required fields.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address.';
            } elseif (strlen($password) < 6) {
                $data['error'] = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $data['error'] = 'Passwords do not match.';
            } elseif ($this->Decomponents_model->get_user_by_email($email)) {
                $data['error'] = 'An account with this email already exists.';
            } elseif (!$acceptPrivacy) {
                $data['error'] = 'Please review and accept the privacy policy to continue.';
            } elseif (!$this->validate_recaptcha_token($recaptchaToken)) {
                $data['error'] = 'Please complete the reCAPTCHA check to continue.';
            } else {
                // Handle optional profile picture upload
                if (!empty($_FILES['profile_picture']['name'])) {
                    $uploadPath = FCPATH . 'upload/profile/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }
                    $config['upload_path']   = $uploadPath;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
                    $config['max_size']      = 2048;
                    $config['file_name']     = 'avatar_signup_' . time();

                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('profile_picture')) {
                        $fileData = $this->upload->data();
                        $uploadedAvatar = 'upload/profile/' . $fileData['file_name'];
                    } else {
                        $data['error'] = strip_tags($this->upload->display_errors('', ''));
                    }
                }

                if (!empty($data['error'])) {
                    return $this->load->view('decomponents/signup', $data);
                }

                $userData = [
                    'FirstName'       => $first,
                    'MiddleName'      => $middle,
                    'LastName'        => $last,
                    'email'           => $email,
                    'password'        => password_hash($password, PASSWORD_DEFAULT),
                    'profile_picture' => $uploadedAvatar,
                    // enum is lowercase in DB
                    'role'            => 'customer',
                    'created_at'      => date('Y-m-d H:i:s'),
                ];

                $newId = $this->Decomponents_model->create_user($userData);
                if ($newId) {
                    $user = $this->Decomponents_model->get_user_by_id($newId);
                    $displayName = $this->format_display_name($user);
                    $this->session->set_userdata([
                        'ez_user_id'   => $newId,
                        'level'        => $user->role ?: 'customer',
                        'ez_user_name' => $displayName,
                        'ez_user_avatar' => $user->profile_picture ?: 'upload/profile/avatar.png',
                    ]);
                    return redirect($this->get_safe_next_url('Decomponents/shop'));
                }

                $data['error'] = 'Unable to create your account right now. Please try again.';
            }
        }

        $this->load->view('decomponents/signup', $data);
    }

    private function validate_recaptcha_token($token)
    {
        $secret = $this->config->item('recaptcha_secret_key');
        if (empty($secret) || empty($token)) {
            return false;
        }

        $payload = http_build_query([
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => $this->input->ip_address(),
        ]);

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $responseBody = null;

        if (function_exists('curl_init')) {
            $ch = curl_init($verifyUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $responseBody = curl_exec($ch);
            curl_close($ch);
        }

        if ($responseBody === null) {
            $context = stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 5,
                ]
            ]);
            $responseBody = @file_get_contents($verifyUrl, false, $context);
        }

        if ($responseBody === false || $responseBody === null) {
            return false;
        }

        $decoded = json_decode($responseBody, true);
        return is_array($decoded) && !empty($decoded['success']);
    }

    // 5) LOGOUT
    public function logout()
    {
        $this->session->unset_userdata(['ez_user_id', 'level']);
        $this->session->sess_destroy();
        redirect('Decomponents');
    }

    /**
     * Checkout review page (requires login).
     */
    public function checkout_review()
    {
        $userId = $this->current_user_id();
        if (!$userId) {
            $nextUrl = site_url('Decomponents/checkout_review');
            $loginUrl = site_url('home_page.php') . '?next=' . rawurlencode($nextUrl) . '&checkout=1';
            return redirect($loginUrl);
        }

        $cart = $this->session->userdata('ez_cart') ?? [];
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            return redirect('products');
        }

        $total = 0;
        foreach ($cart as $item) {
            $qty = (int)($item['qty'] ?? $item['quantity'] ?? 1);
            $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
            $total += $qty * $price;
        }

        $data = [
            'page_title' => 'Checkout Review',
            'cart'       => $cart,
            'total'      => $total,
        ];

        $this->load->view('decomponents/checkout_review', $data);
    }

    // Profile view
    public function profile()
    {
        $userId = $this->session->userdata('ez_user_id');
        if (!$userId) {
            $this->session->set_flashdata('error', 'Please log in to view your profile.');
            return redirect('home_page.php');
        }

        $user = $this->Decomponents_model->get_user_by_id($userId);

        $cart = $this->session->userdata('ez_cart') ?? [];
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['qty'] ?? 1);
        }

        $data = [
            'page_title' => 'My Profile',
            'user'       => $user,
            'address'    => $this->session->userdata('ez_user_address') ?? '',
            'success'    => $this->session->flashdata('success'),
            'error'      => $this->session->flashdata('error'),
            'cartCount'  => $cartCount,
        ];

        $this->load->view('account_profile', $data);
    }

    // Profile update (avatar + address)
    public function profile_update()
    {
        $userId = $this->session->userdata('ez_user_id');
        if (!$userId) {
            $this->session->set_flashdata('error', 'Please log in to update your profile.');
            return redirect('home_page.php');
        }

        $address = trim($this->input->post('address', true));
        $update  = [];
        $first   = trim($this->input->post('first_name', true));
        $middle  = trim($this->input->post('middle_name', true));
        $last    = trim($this->input->post('last_name', true));
        $email   = trim($this->input->post('email', true));

        if ($first !== '') {
            $update['FirstName'] = $first;
        }
        if ($middle !== '') {
            $update['MiddleName'] = $middle;
        }
        if ($last !== '') {
            $update['LastName'] = $last;
        }
        if ($email !== '') {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->session->set_flashdata('error', 'Please provide a valid email address.');
                return redirect('Decomponents/profile');
            }
            $update['email'] = $email;
        }

        // Handle avatar upload
        if (!empty($_FILES['profile_picture']['name'])) {
            $config['upload_path']   = FCPATH . 'upload/profile/';
            if (!$this->ensure_upload_dir($config['upload_path'])) {
                $this->session->set_flashdata('error', 'Upload folder is not writable: ' . $config['upload_path']);
                return redirect('Decomponents/profile');
            }
            $config['allowed_types'] = 'jpg|jpeg|png|gif|webp';
            $config['max_size']      = 2048;
            $config['file_name']     = 'avatar_' . $userId . '_' . time();

            $this->upload->initialize($config);
            if ($this->upload->do_upload('profile_picture')) {
                $fileData = $this->upload->data();
                $relativePath = 'upload/profile/' . $fileData['file_name'];
                $update['profile_picture'] = $relativePath;
                $this->session->set_userdata('ez_user_avatar', $relativePath);
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                return redirect('Decomponents/profile');
            }
        }

        // Address stored in session for prefill
        $this->session->set_userdata('ez_user_address', $address);

        if (!empty($update)) {
            $this->Decomponents_model->update_user($userId, $update);
            // Refresh display name in session if we updated name/email
            $sessionName = trim(($update['FirstName'] ?? $first ?? '') . ' ' . ($update['LastName'] ?? $last ?? ''));
            if ($sessionName !== '') {
                $this->session->set_userdata('ez_user_name', $sessionName);
            } elseif (!empty($update['email'])) {
                $this->session->set_userdata('ez_user_name', $update['email']);
            }
        }

        $this->session->set_flashdata('success', 'Profile updated.');
        redirect('Decomponents/profile');
    }
    public function add_to_cart()
    {
        // Allow non-AJAX clients; always respond JSON.
        header('Content-Type: application/json');

        // Capture user if available, but allow guests.
        $userId = $this->current_user_id();
        $user   = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;

        // Get posted data
        $id    = $this->input->post('product_id');
        $name  = $this->input->post('product_name');
        $price = (float)$this->input->post('product_price');
        $image = $this->input->post('product_image');
        $qty   = (int)$this->input->post('qty');
        if ($qty < 1) {
            $qty = 1;
        }

        // Prefer canonical product data from DB and REQUIRE it
        if ($id) {
            $dbProduct = $this->Product_model->get_product($id);
            if ($dbProduct) {
                $name  = $dbProduct['name'] ?? $name;
                $price = (float)($dbProduct['price'] ?? $price);
                $image = $dbProduct['image'] ?? $image;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
                return;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product id.']);
            return;
        }


        // Get existing cart from session, or empty array
        $cart = $this->session->userdata('ez_cart');
        if (!is_array($cart)) {
            $cart = [];
        }

        // If same ID already in cart, just increase qty
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                'id'    => $id,
                'name'  => $name,
                'price' => $price,
                'image' => $image,
                'qty'   => $qty
            ];
        }

        // Save back to session
        $this->session->set_userdata('ez_cart', $cart);

        // Count total items
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)$item['qty'];
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status'    => 'ok',
            'cartCount' => $count
        ]);
    }


    /**
     * Cart page (from cart.php).
     */
    public function cart()
    {
        $cart = $this->session->userdata('ez_cart') ?? [];

        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['qty'] ?? 1);
        }

        $userId = $this->session->userdata('ez_user_id');
        $user   = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;

        $data = [
            'page_title' => 'Your Cart',
            'cartItems'  => $cart,
            'cartCount'  => $cartCount,
            'user'       => $user,
        ];

        $this->load->view('decomponents/cart', $data);
    }

    /**
     * Remove a single cart item by index.
     */
    public function remove_cart_item($index = null)
    {
        $cart = $this->session->userdata('ez_cart') ?? [];
        $idx  = is_numeric($index) ? (int)$index : -1;

        if ($idx >= 0 && isset($cart[$idx])) {
            unset($cart[$idx]);
            $cart = array_values($cart); // reindex to keep order stable
            $this->session->set_userdata('ez_cart', $cart);
            $this->session->set_flashdata('success', 'Item removed from cart.');
        } else {
            $this->session->set_flashdata('error', 'Unable to remove that item.');
        }

        redirect('Decomponents/cart');
    }

    /**
     * Checkout single item by index (similar to checkout.php).
     * /Decomponents/checkout/0
     */
    public function checkout($itemIndex = null)
    {
        // Require login to proceed to checkout.
        $userId = $this->current_user_id();
        if (!$userId) {
            $this->session->set_flashdata('error', 'Please log in to continue checkout.');
            $nextUrl = site_url('Decomponents/cart');
            $loginUrl = site_url('home_page.php') . '?next=' . rawurlencode($nextUrl);
            return redirect($loginUrl);
        }

        $cart = $this->session->userdata('ez_cart') ?? [];

        if ($itemIndex === null || !isset($cart[$itemIndex])) {
            return redirect('Decomponents/cart');
        }

        $item = $cart[$itemIndex];
        $user   = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;

        $data = [
            'page_title' => 'Checkout',
            'itemIndex'  => $itemIndex,
            'item'       => $item,
            'user'       => $user,
        ];

        $this->load->view('decomponents/checkout', $data);
    }

    /**
     * Payment form page (from payment_form.php).
     * In raw: it uses full cart; here we pass full cart too.
     */
    public function payment_form()
    {
        $user_id = $this->current_user_id();
        if (!$user_id) {
            $this->session->set_flashdata('error', 'Please log in to continue checkout.');
            $nextUrl = site_url('Decomponents/cart');
            $loginUrl = site_url('home_page.php') . '?next=' . rawurlencode($nextUrl);
            return redirect($loginUrl);
        }

        $user = $this->Decomponents_model->get_user_by_id($user_id);
        $cart = $this->session->userdata('ez_cart') ?? [];

        $data = [
            'page_title' => 'Payment',
            'user'       => $user,
            'cartItems'  => $cart,
        ];

        $this->load->view('decomponents/payment_form', $data);
    }

    /**
     * Process payment (from process_payment.php).
     * Here we simply remove the product from cart and redirect to thank you page.
     */
    public function process_payment()
    {
        if ($this->input->method() !== 'post') {
            return redirect('Decomponents/cart');
        }

        $user_id = $this->current_user_id();
        if (!$user_id) {
            $this->session->set_flashdata('error', 'Please log in to continue checkout.');
            $nextUrl = site_url('Decomponents/cart');
            $loginUrl = site_url('home_page.php') . '?next=' . rawurlencode($nextUrl);
            return redirect($loginUrl);
        }

        $full_name  = trim($this->input->post('full_name'));
        $email      = trim($this->input->post('email'));
        $address    = trim($this->input->post('address'));
        $pay_method_raw = strtolower(trim($this->input->post('payment_method')));
        $allowedPayMethods = ['credit_card', 'paypal', 'bank_transfer', 'cod', 'card', 'gcash'];
        $pay_method = in_array($pay_method_raw, $allowedPayMethods, true) ? $pay_method_raw : 'cod';

        if (!$full_name || !$email || !$address) {
            $this->session->set_flashdata('error', 'Please complete all required fields.');
            return redirect('Decomponents/payment_form');
        }

        $cart = $this->session->userdata('ez_cart') ?? [];
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Your cart is empty.');
            return redirect('Decomponents/cart');
        }

        $userId = $user_id;
        $total = 0;
        foreach ($cart as $item) {
            $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
            $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
            $total += $price * $qty;
        }

        $orderData = [
            'user_id'          => $userId ?: null,
            'full_name'        => $full_name,
            'email'            => $email,
            'shipping_address' => $address,
            'payment_method'   => $pay_method,
            'status'           => 'pending',
            'quantity'         => array_sum(array_map(function ($i) {
                return (int)($i['qty'] ?? $i['quantity'] ?? 0);
            }, $cart)),
            'total_amount'     => $total,
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        $newOrderId = $this->Decomponents_model->create_order($orderData);

        if ($newOrderId && $this->db->table_exists('order_items')) {
            foreach ($cart as $item) {
                $pid   = $item['product_id'] ?? $item['id'] ?? 0;
                $price = (float)($item['product_price'] ?? $item['price'] ?? 0);
                $qty   = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                $this->Decomponents_model->add_order_item($newOrderId, $pid, $qty, $price);
            }
        }

        // Clear cart after order
        $this->session->set_userdata('ez_cart', []);

        redirect('Decomponents/thank_you');
    }

    /**
     * Thank you page (from thank_you.php).
     */
    public function thank_you()
    {
        $data = [
            'page_title' => 'Thank You',
        ];
        $this->load->view('decomponents/thank_you', $data);
    }

    /**
     * Static pages – about, contact, feature, etc.
     * (from aboutus.php, contactus.php, feature.php)
     */
    public function about()
    {
        $cart = $this->session->userdata('ez_cart') ?? [];
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['qty'] ?? 1);
        }
        $userId = $this->session->userdata('ez_user_id');

        $data['page_title'] = 'About Us';
        $data['cartCount']  = $cartCount;
        $data['user']       = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;
        $data['category']   = 'info';
        $this->load->view('decomponents/about', $data);
    }

    public function contact()
    {
        $cart = $this->session->userdata('ez_cart') ?? [];
        $cartCount = 0;
        foreach ($cart as $item) {
            $cartCount += (int)($item['qty'] ?? 1);
        }
        $userId = $this->session->userdata('ez_user_id');

        $data['page_title'] = 'Contact Us';
        $data['cartCount']  = $cartCount;
        $data['user']       = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;
        $data['category']   = 'info';
        $this->load->view('decomponents/contact', $data);
    }

    /**
     * Handle contact form submissions (landing + contact page).
     */
    public function send_contact()
    {
        if ($this->input->method() !== 'post') {
            show_404();
            return;
        }

        $name    = trim($this->input->post('name', true));
        $email   = trim($this->input->post('email', true));
        $subject = trim($this->input->post('subject', true));
        $message = trim($this->input->post('message', true));

        $error = null;
        if (!$name || !$email || !$subject || !$message) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        }

        if ($error) {
            return $this->respond_contact(false, $error);
        }

        $payload = [
            'name'       => $name,
            'email'      => $email,
            'subject'    => $subject,
            'message'    => $message,
            'status'     => 'new',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $ok  = $this->Contact_model->save_message($payload);
        $msg = $ok ? 'Thanks! Your message has been received.' : 'Unable to save your message right now.';

        return $this->respond_contact($ok, $msg);
    }

    /**
     * Unified response for contact submissions.
     */
    private function respond_contact($ok, $message)
    {
        if ($this->input->is_ajax_request()) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => $ok ? 'ok' : 'error',
                    'message' => $message,
                ]));
            return;
        }

        $this->session->set_flashdata($ok ? 'success' : 'error', $message);

        $referer = $this->input->server('HTTP_REFERER');
        $base    = rtrim(base_url(), '/');
        if (!$referer || stripos($referer, $base) !== 0) {
            $referer = site_url('Decomponents/contact');
        }
        redirect($referer);
    }

    /**
     * Order tracker page for logged-in users.
     */
    public function track_order()
    {
        $userId = $this->session->userdata('ez_user_id');
        if (!$userId) {
            $this->session->set_flashdata('error', 'Please log in to view your orders.');
            return redirect('home_page.php');
        }

        $orders = $this->Decomponents_model->get_orders_by_user($userId);
        $user   = $this->Decomponents_model->get_user_by_id($userId);
        $cartCleared = (bool)$this->session->flashdata('cart_cleared');
        $showHistory = $this->input->get('history') === '1' || !$cartCleared;

        $data = [
            'page_title'  => 'Track Your Orders',
            'orders'      => $orders,
            'user'        => $user,
            'cartCleared' => $cartCleared,
            'showHistory' => $showHistory,
        ];

        $this->load->view('decomponents/track_order', $data);
    }

    public function feature()
    {
        $data['page_title'] = 'Feature';
        $this->load->view('decomponents/feature', $data);
    }

    /**
     * Simple JSON endpoint for client to verify server-side login status.
     */
    public function api_auth_status()
    {
        $userId = $this->current_user_id();
        $user = $userId ? $this->Decomponents_model->get_user_by_id($userId) : null;

        $payload = [
            'logged_in' => $userId ? true : false,
            'user'      => $user ? [
                'id'   => $user->id ?? null,
                'email' => $user->email ?? null,
                'role' => $user->role ?? null,
            ] : null,
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
    public function clear_cart()
    {
        // Clear server cart state
        $this->session->set_userdata('ez_cart', []);
        $this->session->unset_userdata('ez_cart');
        $this->session->set_flashdata('success', 'Cart cleared. You can view your order history in My Orders.');
        $this->session->set_flashdata('cart_cleared', true);

        $referer = $this->input->server('HTTP_REFERER');
        $base    = rtrim(base_url(), '/');
        if (!$referer || stripos($referer, $base) !== 0) {
            $referer = site_url('Decomponents/track_order');
        }
        redirect($referer);
    }
}
