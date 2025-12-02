<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Product_model');
        $this->load->model('Settings_model');
        $this->load->model('Testimonials_model');

        // Share site settings with all public views.
        $settings = $this->Settings_model->get_settings();
        $this->load->vars('siteSettings', $settings);

        // Keep admins inside the dashboard instead of the public site.
        $role = strtolower((string)$this->session->userdata('level'));
        if ($role === 'admin') {
            redirect('Decomponents/admin');
            exit;
        }
    }

    public function home()
    {
        // Pull featured products for homepage grid.
        $featured = $this->Product_model->get_featured_products(6);
        if (empty($featured)) {
            $featured = $this->Product_model->get_all_with_categories();
            if (count($featured) > 6) {
                $featured = array_slice($featured, 0, 6);
            }
        }

        $data = [
            'featuredProducts' => $featured,
            'testimonials'     => $this->Testimonials_model->get_active(4),
        ];
        $this->load->view('pages/Home', $data);
    }

    public function products()
    {
        // Prefer database-backed products so admin CRUD changes appear here.
        $dbProducts = $this->Product_model->get_all_with_categories();
        $products   = [];

        if (!empty($dbProducts)) {
            foreach ($dbProducts as $row) {
                $imgPath = $row['image'] ?: 'Pictures/DeComponents.jpeg';
                if (preg_match('#^https?://#i', $imgPath) || strpos($imgPath, '//') === 0) {
                    $imgUrl = $imgPath;
                } else {
                    $imgUrl = base_url($imgPath);
                }
                $products[] = [
                    'id'          => $row['id'],
                    'name'        => $row['name'],
                    'price'       => (float)($row['price'] ?? 0),
                    'image'       => $imgUrl,
                    'category'    => $row['category_name'] ?: 'General',
                    'description' => $row['description'] ?? '',
                ];
            }
        } else {
            // Fallback: scan products/ directory for static items (legacy).
            $productRoot = FCPATH . 'products';
            $categories = [
                'gpu'   => ['label' => 'GPU', 'base_price' => 450],
                'cpui'  => ['label' => 'CPU', 'base_price' => 320],
                'mobo'  => ['label' => 'Motherboard', 'base_price' => 280],
                'powsu' => ['label' => 'Power Supply', 'base_price' => 150],
                'image' => ['label' => 'GPU', 'base_price' => 400],
            ];
            $descriptionSeeds = [
                'gpu' => [
                    'Tuned for high FPS gaming and smooth ray tracing.',
                    'Great for 1440p play and creator workloads.',
                    'Dual-fan cooling keeps temps stable under load.',
                ],
                'cpui' => [
                    'Balanced cores for gaming and streaming together.',
                    'Efficient silicon for cooler, quieter builds.',
                    'Ready for overclocking with plenty of headroom.',
                ],
                'mobo' => [
                    'Robust VRMs and solid PCB for stable power delivery.',
                    'Plenty of M.2 slots and headers for clean builds.',
                    'Updated BIOS support for the latest chips.',
                ],
                'powsu' => [
                    '80+ certified efficiency with quiet operation.',
                    'Fully-protected rails for reliable uptime.',
                    'Built to handle modern GPUs without coil whine.',
                ],
                'image' => [
                    'Built for crisp visuals and buttery frame times.',
                    'Compact profile with impressive power efficiency.',
                    'Great value pick for esports and indie titles.',
                ],
            ];

            $id = 1;

            $makeDescription = function ($dir, $prettyName, $id) use ($descriptionSeeds) {
                $pool = isset($descriptionSeeds[$dir]) ? $descriptionSeeds[$dir] : ['Reliable component for your next build.'];
                $phrase = $pool[$id % count($pool)];
                return $prettyName . ' - ' . $phrase;
            };

            foreach ($categories as $dir => $meta) {
                $fullPath = $productRoot . DIRECTORY_SEPARATOR . $dir;
                if (!is_dir($fullPath)) {
                    continue;
                }

                $files = array_filter(scandir($fullPath), function ($file) use ($fullPath) {
                    return is_file($fullPath . DIRECTORY_SEPARATOR . $file)
                        && preg_match('/\.(png|jpe?g|webp)$/i', $file);
                });

                natsort($files);

                foreach ($files as $file) {
                    $name = pathinfo($file, PATHINFO_FILENAME);
                    $prettyName = $meta['label'] . ' ' . strtoupper(str_replace(['_', '-'], ' ', $name));
                    // Create simple price variety based on id to avoid flat pricing
                    $price = $meta['base_price'] + (10 * (($id % 7) + 1));
                    $description = $makeDescription($dir, $prettyName, $id);

                    $products[] = [
                        'id'       => $id,
                        'name'     => $prettyName,
                        'price'    => $price,
                        'image'    => base_url('products/' . $dir . '/' . $file),
                        'category' => $meta['label'],
                        'description' => $description,
                    ];
                    $id++;
                }
            }
        }

        $this->load->view('pages/Products', ['products' => $products]);
    }

    public function about()
    {
        $this->load->view('pages/AboutUs');
    }

    public function about_more()
    {
        $this->load->view('pages/AboutUsMore');
    }

    public function tradables()
    {
        $this->load->view('pages/Tradables');
    }

    public function trade_now()
    {
        $this->load->view('pages/TradeNow');
    }

    public function trading()
    {
        $this->load->view('pages/Trading');
    }

    public function ordering()
    {
        $this->load->view('pages/Ordering');
    }

    public function notification()
    {
        $this->load->view('pages/Notification');
    }

    public function order_notification()
    {
        $this->load->view('pages/OrderNotification');
    }

    public function news()
    {
        $this->load->view('pages/News');
    }

    public function contact()
    {
        $this->load->view('pages/Contact');
    }

    public function test()
    {
        $this->load->view('pages/test');
    }
}
