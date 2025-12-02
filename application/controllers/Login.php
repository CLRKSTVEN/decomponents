<?php
class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->load->model('Login_model');
        $this->load->model('SettingsModel');
        $this->load->model('Settings_model');
        $this->load->model('Auth_visual_model');
        $this->load->library(['session', 'form_validation']);
        $this->load->helper(['url', 'form']);
    }


    /**
     * Verify Google reCAPTCHA v2 using cURL (fallback to file_get_contents).
     *
     * @return array [bool $ok, array $errorCodes]
     */
    private function verify_recaptcha($secret, $response)
    {
        if (empty($secret) || empty($response)) {
            log_message('error', 'reCAPTCHA: missing secret or response');
            return [false, ['missing-input']];
        }

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        $postData  = [
            'secret'   => $secret,
            'response' => $response,
            'remoteip' => $this->input->ip_address(),
        ];

        $result = null;

        // Prefer cURL
        if (function_exists('curl_init')) {
            $ch = curl_init($verifyUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
            $result = curl_exec($ch);

            if ($result === false) {
                log_message('error', 'reCAPTCHA cURL error: ' . curl_error($ch));
                curl_close($ch);
                return [false, ['curl-error']];
            }
            curl_close($ch);
        } else {
            // Fallback: file_get_contents
            $opts    = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'content' => http_build_query($postData),
                    'timeout' => 5,
                ]
            ];
            $context = stream_context_create($opts);
            $result  = @file_get_contents($verifyUrl, false, $context);

            if ($result === false) {
                log_message('error', 'reCAPTCHA file_get_contents failed (maybe allow_url_fopen disabled).');
                return [false, ['http-failed']];
            }
        }

        // Log raw result so we can see Google’s response in application/logs
        log_message('error', 'reCAPTCHA raw result: ' . $result);

        $resp       = json_decode($result, true);
        $ok         = (!empty($resp['success']));
        $errorCodes = isset($resp['error-codes']) ? (array)$resp['error-codes'] : [];

        if (!$ok) {
            log_message('error', 'reCAPTCHA failed; error-codes=' . implode(',', $errorCodes));
        }

        return [$ok, $errorCodes];
    }

    function index()
    {
        // If the user is already authenticated, skip the login form.
        $userId = $this->session->userdata('ez_user_id');
        $level  = strtolower((string)$this->session->userdata('level'));
        if ($userId) {
            if ($level === 'admin') {
                redirect('Decomponents/admin');
            } else {
                redirect('products');
            }
            return;
        }

        $result = [
            'data'          => $this->Login_model->loginImage(),
            'login_visual'  => $this->Auth_visual_model->get_active_by_page('login'),
            'siteSettings'  => $this->Settings_model->get_settings(),
        ];

        $this->load->view('home_page', $result);
    }

    // function registration()
    // {
    //     if ($this->input->post('register')) {
    //         $lrn = $this->input->post('lrn');
    //         $fname = strtoupper($this->input->post('fname'));
    //         $mname = strtoupper($this->input->post('mname'));
    //         $lname = strtoupper($this->input->post('lname'));
    //         $sex = $this->input->post('sex');
    //         $bdate = $this->input->post('bdate');
    //         $contactno = $this->input->post('contactno');
    //         $Father = $this->input->post('Father');
    //         $FOccupation = $this->input->post('FOccupation');
    //         $Mother = $this->input->post('Mother');
    //         $MOccupation = $this->input->post('MOccupation');
    //         $Brgy = $this->input->post('Brgy');
    //         $City = $this->input->post('City');
    //         $Province = $this->input->post('Province');
    //         $nameExt = $this->input->post('nameExt');
    //         $PSANo = $this->input->post('PSANo');
    //         $Age = $this->input->post('Age');
    //         $Belong_IP = $this->input->post('Belong_IP');
    //         $IPSpecify = $this->input->post('IPSpecify');
    //         $MTongue = $this->input->post('MTongue');
    //         $Religion = $this->input->post('Religion');
    //         $SpecEducNeed = $this->input->post('SpecEducNeed');
    //         $SENSpecify = $this->input->post('SENSpecify');
    //         $DeviceAvailable = $this->input->post('DeviceAvailable');
    //         $DASpecify = $this->input->post('DASpecify');
    //         $FHEA = $this->input->post('FHEA');
    //         $FEmpStat = $this->input->post('FEmpStat');
    //         $FMobileNo = $this->input->post('FMobileNo');
    //         $MHEA = $this->input->post('MHEA');
    //         $MEmpStat = $this->input->post('MEmpStat');
    //         $MMobileNo = $this->input->post('MMobileNo');
    //         $houseNo = $this->input->post('houseNo');
    //         $sitio = $this->input->post('sitio');
    //         $region = $this->input->post('region');
    //         $StudentType = $this->input->post('StudentType');
    //         $YearLevelToEnroll = $this->input->post('YearLevelToEnroll');

    //         $email = $this->input->post('email');
    //         $date = date('Y-m-d');
    //         $pass = $this->input->post('pass');
    //         $h_upass = sha1($pass);

    //         $que = $this->db->query("select * from users where username='" . $lrn . "'");
    //         $row = $que->num_rows();
    //         if ($row) {

    //             $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center"><b>This account already exists.</b></div>');
    //         } else {
    //             $que = $this->db->query("insert into users values('$lrn','$h_upass','Student','$fname','$mname','$lname','$email','avatar.png','active','$date','$lrn')");
    //             $que1 = $this->db->query("insert into studeprofile values('$lrn','$fname','$mname','$lname','$sex','Single','','Filipino','','','','$contactno','','','','','','$lrn','$bdate','$date','$date','','$contactno','','','','','','','','$email','$Father','$FOccupation','$Mother','$MOccupation','','','','','','','','$Province','$City','$Brgy','','1','','','','','','','','','','','','','','','','','$Belong_IP','$IPSpecify','$MTongue','$SpecEducNeed','$SENSpecify','$DeviceAvailable','$DASpecify','$FHEA','$FEmpStat','','$FMobileNo','$MHEA','$MEmpStat','','$MMobileNo','','','','','$PSANo','$lrn')");
    //             $que = $this->db->query("insert into studentsignup values('','$lrn','$fname','$mname','$lname','$StudentType','$YearLevelToEnroll','$date')");
    //             $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"><b>Your account has been created successfully.</b></div>');
    //             redirect('Login');
    //         }
    //     }
    //     $this->load->view('registration_form', @$data);
    // }

    public function registration()
    {
        $signupVisual = $this->Auth_visual_model->get_active_by_page('signup');
        $siteSettings = $this->Settings_model->get_settings();

        // Get reCAPTCHA keys from SettingsModel (o_srms_settings → srms_settings)
        list($recaptcha_site_key, $recaptcha_secret_key) = $this->SettingsModel->get_recaptcha_keys();
        $host = $this->input->server('HTTP_HOST');
        $recaptcha_notice = '';
        if (empty($recaptcha_site_key) || empty($recaptcha_secret_key)) {
            $recaptcha_notice = 'reCAPTCHA keys are missing. Add site_key and sec_key in o_srms_settings.';
        }

        if ($this->input->post('register')) {
            // Basic validation rules for current DeComponents user schema
            $this->form_validation->set_rules('fName', 'First Name', 'required');
            $this->form_validation->set_rules('lName', 'Last Name', 'required');
            $this->form_validation->set_rules('empEmail', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[password]');

            if ($this->form_validation->run() === FALSE) {
                $data = [
                    'recaptcha_site_key' => $recaptcha_site_key,
                    'recaptcha_notice'   => $recaptcha_notice,
                ];
                return $this->load->view('registration_form', $data);
            }

            if (!empty($recaptcha_site_key) && !empty($recaptcha_secret_key)) {
                $recaptchaResponse = $this->input->post('g-recaptcha-response');

                if (empty($recaptchaResponse)) {
                    $this->session->set_flashdata(
                        'msg',
                        '<div class="alert alert-danger text-center"><b>Please complete the captcha challenge.</b></div>'
                    );
                    redirect('Login/registration');
                    return;
                }

                list($ok, $errors) = $this->verify_recaptcha($recaptcha_secret_key, $recaptchaResponse);

                if (!$ok) {
                    $codes = !empty($errors) ? ' (' . htmlspecialchars(implode(', ', $errors), ENT_QUOTES, 'UTF-8') . ')' : '';
                    $this->session->set_flashdata(
                        'msg',
                        '<div class="alert alert-danger text-center"><b>Captcha validation failed. Please try again.</b>' . $codes . '</div>'
                    );
                    redirect('Login/registration');
                    return;
                }
            }

            // Gather and sanitize user input (matches users table: FirstName, MiddleName, LastName, email, password, profile_picture, role, created_at)
            $FirstName   = trim($this->input->post('fName', true));
            $MiddleName  = trim((string)$this->input->post('mName', true));
            $LastName    = trim($this->input->post('lName', true));
            $email       = trim($this->input->post('empEmail', true));
            $password    = (string)$this->input->post('password');
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Check if the user already exists by email
            $exists = $this->db->where('email', $email)->get('users')->num_rows();
            if ($exists > 0) {
                $this->session->set_flashdata(
                    'msg',
                    '<div class="alert alert-danger text-center"><b>This email already has an account.</b></div>'
                );
                redirect('Login/registration');
                return;
            }

            $data = [
                'FirstName'       => $FirstName,
                'MiddleName'      => $MiddleName === '' ? null : $MiddleName,
                'LastName'        => $LastName,
                'email'           => $email,
                'password'        => $hashed_password,
                'profile_picture' => 'upload/profile/avatar.png',
                'role'            => 'customer',
                'created_at'      => date('Y-m-d H:i:s'),
            ];

            $inserted = $this->db->insert('users', $data);

            if (!$inserted) {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger text-center"><b>Registration failed. Please try again.</b></div>'
                );
            } else {
                $userId = $this->db->insert_id();
                // Auto-login to streamline checkout flow
                $displayName = trim($LastName . ', ' . $FirstName);
                $this->session->set_userdata([
                    'ez_user_id'     => $userId,
                    'level'          => 'customer',
                    'ez_user_name'   => $displayName !== '' ? $displayName : $email,
                    'ez_user_avatar' => 'upload/profile/avatar.png',
                ]);

                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-success text-center"><b>Your account has been created successfully.</b></div>'
                );
                redirect('products');
                return;
            }
        }

        // Always pass site key to view (even on first load)
        $data = [
            'recaptcha_site_key' => $recaptcha_site_key,
            'recaptcha_notice'   => $recaptcha_notice,
            'signup_visual'      => $signupVisual,
            'siteSettings'       => $siteSettings,
        ];
        $this->load->view('registration_form', $data);
    }


    function auth()
    {
        $emailInput = $this->input->post('email', TRUE);
        if ($emailInput === null) {
            $emailInput = $this->input->post('username', TRUE); // fallback for old field name
        }
        $email = trim((string)$emailInput);
        $password = $this->input->post('password', TRUE);

        // Validate user credentials against the users table
        $query = $this->Login_model->validate($email);

        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $stored_hashed_password = $data['password'] ?? '';

            $isValid = false;

            if (is_string($stored_hashed_password) && $stored_hashed_password !== '') {
                if (function_exists('password_verify') && password_verify($password, $stored_hashed_password)) {
                    $isValid = true;
                } elseif ($stored_hashed_password === $password) {
                    // Legacy plain-text fallback: immediately rehash
                    $isValid = true;
                    if (function_exists('password_hash')) {
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $this->db->where('email', $email)->update('users', ['password' => $newHash]);
                        $data['password'] = $newHash;
                    }
                }
            }

            if ($isValid) {
                $avatar = !empty($data['profile_picture']) ? $data['profile_picture'] : 'upload/profile/avatar.png';
                $displayName = trim(($data['FirstName'] ?? '') . ' ' . ($data['MiddleName'] ?? '') . ' ' . ($data['LastName'] ?? ''));
                if ($displayName === '') {
                    $displayName = $data['email'] ?? 'Customer';
                }
                $role = !empty($data['role']) ? $data['role'] : 'customer';

                // Keep legacy session keys for existing navbar includes.
                $this->session->set_userdata('fname', $displayName);
                $this->session->set_userdata('avatar', basename($avatar));

                $this->session->set_userdata([
                    'ez_user_id'     => $data['id'],
                    'level'          => $role,
                    'ez_user_name'   => $displayName,
                    'ez_user_avatar' => $avatar,
                    'email'          => $data['email'] ?? '',
                    'logged_in'      => true,
                ]);

                // Redirect admins to the dashboard, everyone else to products.
                $normalizedRole = strtolower((string)$role);
                $target = $normalizedRole === 'admin' ? 'Decomponents/admin' : 'products';
                redirect($target);
            } else {
                $this->session->set_flashdata('danger', 'The email or password is incorrect!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('danger', 'The email or password is incorrect!');
            redirect('login');
        }
    }



    function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    /**
     * AJAX endpoint to check if email already exists
     * GET /login/checkEmailExists?email=test@example.com
     * Returns JSON: {"exists": true/false}
     */
    public function checkEmailExists()
    {
        $this->output->set_content_type('application/json');

        $email = trim($this->input->get('email', TRUE));

        if (empty($email)) {
            echo json_encode(['exists' => false, 'error' => 'Email is empty']);
            return;
        }

        $exists = $this->db->select('email')->from('users')->where('email', $email)->get()->num_rows() > 0;
        echo json_encode(['exists' => $exists]);
    }

    function forgot()
    {
        // Show form on GET
        if ($this->input->method() === 'get') {
            $data['page_title'] = 'Forgot Password - DeComponents';
            $this->load->view('auth_forgot', $data);
            return;
        }

        // Validate email format only
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Forgot Password - DeComponents';
            $this->load->view('auth_forgot', $data);
            return;
        }

        $email = trim($this->input->post('email', TRUE));

        // Find user by email (search in both o_users and users tables)
        $user = $this->Login_model->findUserByEmail($email);

        if ($user) {
            // Block admin from using forgot password
            if (strtolower($user['username']) === 'admin') {
                $this->session->set_flashdata('error', 'Admin accounts cannot use this form. Please contact system administrator for password reset.');
                redirect('login/forgot');
                return;
            }

            // Send reset link
            $this->Login_model->sendpassword($user, $email);
        } else {
            $this->session->set_flashdata('error', 'Email not found in our system!');
            redirect('login/forgot');
        }
    }

    /**
     * Reset password
     * GET  /login/reset?u=username  → shows form
     * POST /login/reset             → updates password
     */
    public function reset()
    {
        // GET: from email link
        if ($this->input->method() === 'get') {
            $username = trim($this->input->get('u', TRUE)); // email in this flow

            if ($username === '') {
                $this->session->set_flashdata('error', 'Invalid password reset link.');
                redirect('login');
                return;
            }

            // Optionally verify that user exists
            $user = $this->Login_model->forgotPassword($username);
            if (!$user) {
                $this->session->set_flashdata('error', 'User not found for this reset link.');
                redirect('login');
                return;
            }

            $data['page_title'] = 'Reset Password - DeComponents';
            $data['username']   = $username;
            $this->load->view('auth_reset', $data);
            return;
        }

        // POST: user submitted new password
        $this->form_validation->set_rules('password',  'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password2', 'Confirm Password', 'required|matches[password]');

        $username = trim($this->input->post('username', TRUE)); // email

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Reset Password - DeComponents';
            $data['username']   = $username;
            $this->load->view('auth_reset', $data);
            return;
        }

        // Check user again
        $user = $this->Login_model->forgotPassword($username);
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('login');
            return;
        }

        // Update password
        $newPassword = $this->input->post('password', TRUE);
        $hash        = password_hash($newPassword, PASSWORD_BCRYPT);

        $this->db->where('username', $username);
        $this->db->update('o_users', ['password' => $hash]);

        $this->session->set_flashdata('success', 'Your password has been updated. You can now log in.');
        redirect('login');
    }
}
