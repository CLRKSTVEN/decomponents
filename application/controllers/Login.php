<?php
class Login extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        error_reporting(0);
        $this->load->model('Login_model');
        $this->load->model('SettingsModel');
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
        $result['data'] = $this->Login_model->loginImage();
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
        // Get reCAPTCHA keys from SettingsModel (o_srms_settings → srms_settings)
        list($recaptcha_site_key, $recaptcha_secret_key) = $this->SettingsModel->get_recaptcha_keys();

        // Disable reCAPTCHA on localhost/127.0.0.1 to avoid domain mismatch errors.
        $host = $this->input->server('HTTP_HOST');
        $isLocalHost = in_array($host, ['localhost', '127.0.0.1'], true);
        if ($isLocalHost) {
            $recaptcha_site_key   = null;
            $recaptcha_secret_key = null;
        }

        if ($this->input->post('register')) {
            // Basic validation rules (showable via validation_errors in the view)
            $this->form_validation->set_rules('empPosition', 'Position', 'required');
            $this->form_validation->set_rules('fName', 'First Name', 'required');
            $this->form_validation->set_rules('lName', 'Last Name', 'required');
            $this->form_validation->set_rules('empEmail', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

            if ($this->form_validation->run() === FALSE) {
                $data = [
                    'recaptcha_site_key' => $recaptcha_site_key,
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
                    // While debugging, you can display error codes to see the reason.
                    $codes = !empty($errors) ? ' (' . htmlspecialchars(implode(', ', $errors), ENT_QUOTES, 'UTF-8') . ')' : '';
                    $this->session->set_flashdata(
                        'msg',
                        '<div class="alert alert-danger text-center"><b>Captcha validation failed. Please try again.</b>' . $codes . '</div>'
                    );
                    redirect('Login/registration');
                    return;
                }
            }

            // --- end reCAPTCHA block ---

            // Helper for nullable fields
            $null_if_empty = function ($v) {
                $v = trim((string) $v);
                return ($v === '') ? null : $v;
            };

            // Gather and sanitize user input
            $IDNumber    = $this->input->post('IDNumber', true);
            $FirstName   = strtoupper($null_if_empty($this->input->post('fName', true)));
            $MiddleName  = strtoupper($null_if_empty($this->input->post('mName', true)));
            $LastName    = strtoupper($null_if_empty($this->input->post('lName', true)));
            // Force position to Customer regardless of submitted value.
            $empPosition = 'Customer';
            $empSection  = $null_if_empty($this->input->post('empSection', true));
            $empEmail    = trim($this->input->post('empEmail'));

            $username       = $empEmail; // you’re using email as username
            $password       = $this->input->post('password');
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $dateCreated = date('Y-m-d');

            // Check if the user already exists in either table (by username)
            $exists = 0;
            $this->db->where('username', $username);
            $exists += $this->db->get('o_users')->num_rows();

            $this->db->where('username', $username);
            $exists += $this->db->get('users')->num_rows();

            if ($exists > 0) {
                $this->session->set_flashdata(
                    'msg',
                    '<div class="alert alert-danger text-center"><b>This account is already taken. Please choose a different username.</b></div>'
                );
                // 🔧 fix: use correct route (no "Registration" controller)
                redirect('Login/registration');
                return;
            }

            // Start transaction and insert into BOTH tables so records stay in sync
            $this->db->trans_start();

            $data = [
                'username'    => $username,
                'password'    => $hashed_password,
                'position'    => 'Customer',
                'fName'       => $FirstName,
                'mName'       => $MiddleName,
                'lName'       => $LastName,
                'email'       => $empEmail,
                'avatar'      => 'avatar.png',
                'acctStat'    => 'active',
                'dateCreated' => $dateCreated,
                'empPosition' => $empPosition,
                'IDNumber'    => $IDNumber,
                'empSection'  => $empSection,
                'settingsID'  => '1',
            ];

            $this->db->insert('o_users', $data);
            $this->db->insert('users',   $data);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger text-center"><b>Registration failed. Please try again.</b></div>'
                );
            } else {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-success text-center"><b>Your account has been created successfully.</b></div>'
                );
                redirect('Login');
                return;
            }
        }

        // Always pass site key to view (even on first load)
        $data = [
            'recaptcha_site_key' => $recaptcha_site_key,
            'recaptcha_notice'   => $isLocalHost ? 'reCAPTCHA skipped on localhost (domain not whitelisted).' : '',
        ];
        $this->load->view('registration_form', $data);
    }


    function auth()
    {
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE); // Get plain password

        // Validate user credentials and get the stored hashed password
        $validate = $this->Login_model->validate($username);

        if ($validate->num_rows() > 0) {
            $data = $validate->row_array();
            $stored_hashed_password = $data['password']; // password column from DB

            // Verify the password supporting modern hashes (password_hash)
            // and fallback to legacy sha1. Prefer `password_verify()` whenever
            // available so we don't rely on specific hash prefixes. If a legacy
            // sha1 password is detected and the login is successful, rehash the
            // password with bcrypt and update both user tables so future logins
            // use the stronger hash.

            $isValid = false;
            $usedLegacySha1 = false;

            // First, try password_verify if available and stored hash is a string
            if (function_exists('password_verify') && is_string($stored_hashed_password) && $stored_hashed_password !== '') {
                if (password_verify($password, $stored_hashed_password)) {
                    $isValid = true;

                    // If hash needs rehash (algorithm options changed), rehash it.
                    if (function_exists('password_needs_rehash') && password_needs_rehash($stored_hashed_password, PASSWORD_BCRYPT)) {
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $this->db->where('username', $data['username']);
                        $this->db->update('o_users', ['password' => $newHash]);
                        $this->db->where('username', $data['username']);
                        $this->db->update('users', ['password' => $newHash]);
                    }
                }
            }

            // If password_verify didn't validate, try legacy sha1 comparison
            if (! $isValid && is_string($stored_hashed_password) && $stored_hashed_password !== '') {
                if (sha1($password) === $stored_hashed_password) {
                    $isValid = true;
                    $usedLegacySha1 = true;

                    // Rehash using bcrypt and update both tables so we move away
                    // from sha1 on the next login.
                    if (function_exists('password_hash')) {
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $this->db->where('username', $data['username']);
                        $this->db->update('o_users', ['password' => $newHash]);
                        $this->db->where('username', $data['username']);
                        $this->db->update('users', ['password' => $newHash]);
                    }
                }
            }

            if ($isValid) {
                $username = $data['username'];
                $fname = $data['fName'];
                $mname = $data['mName'];
                $lname = $data['lName'];
                $avatar = $data['avatar'];
                $email = $data['email'];
                $level = $data['position'];
                $IDNumber = $data['IDNumber'];
                $acctStat = $data['acctStat'];

                if ($acctStat === 'active') {
                    // User data to be stored in session
                    $user_data = array(
                        'username'  => $username,
                        'fname'     => $fname,
                        'mname'     => $mname,
                        'lname'     => $lname,
                        'avatar'    => $avatar,
                        'email'     => $email,
                        'level'     => $level,
                        'IDNumber'  => $IDNumber,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($user_data);

                    // Redirect based on user level
                    switch ($level) {
                        case 'BAC Secretariat':
                            redirect('page/admin');
                            break;
                        case 'Employee':
                            redirect('page/user');
                            break;
                        case 'Super Admin':
                            redirect('page/superAdmin');
                            break;
                        case 'Property Custodian':
                            redirect('page/p_custodian');
                            break;
                        default:
                            // Handle unexpected levels
                            $this->session->set_flashdata('message', 'Unauthorized access.');
                            redirect('login');
                    }
                } else {
                    $this->session->set_flashdata('message', 'Your account is not active. Please contact support.');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('danger', 'The username or password is incorrect!');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('danger', 'The username or password is incorrect!');
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

        // Check if email exists in o_users table
        $q1 = $this->db->select('email')->from('o_users')->where('email', $email)->get();
        if ($q1->num_rows() > 0) {
            echo json_encode(['exists' => true]);
            return;
        }

        // Check if email exists in users table
        $q2 = $this->db->select('email')->from('users')->where('email', $email)->get();
        if ($q2->num_rows() > 0) {
            echo json_encode(['exists' => true]);
            return;
        }

        // Email does not exist in either table
        echo json_encode(['exists' => false]);
    }

    function forgot()
    {
        // Show form on GET
        if ($this->input->method() === 'get') {
            $data['page_title'] = 'Forgot Password - BERPS';
            $this->load->view('auth_forgot', $data);
            return;
        }

        // Validate email format only
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Forgot Password - BERPS';
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
            $username = trim($this->input->get('u', TRUE));

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

            $data['page_title'] = 'Reset Password - BERPS';
            $data['username']   = $username;
            $this->load->view('auth_reset', $data);
            return;
        }

        // POST: user submitted new password
        $this->form_validation->set_rules('password',  'New Password', 'required|min_length[8]');
        $this->form_validation->set_rules('password2', 'Confirm Password', 'required|matches[password]');

        $username = trim($this->input->post('username', TRUE));

        if ($this->form_validation->run() === FALSE) {
            $data['page_title'] = 'Reset Password - BERPS';
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
