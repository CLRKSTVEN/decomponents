<?php
class Login_model extends CI_Model
{
  public function loginImage()
  {
    $query = $this->db->get('srms_settings', 1);
    return $query->result();
  }

  public function validate($username)
  {
    // Allow login by username OR email OR IDNumber. Try `o_users` first, then `users`.
    $escaped = $this->db->escape($username);
    $where = "(username = $escaped OR email = $escaped OR IDNumber = $escaped)";

    $q = $this->db
      ->where($where, null, false)
      ->get('o_users', 1);

    if ($q->num_rows() > 0) {
      return $q;
    }

    return $this->db
      ->where($where, null, false)
      ->get('users', 1);
  }

  public function forgotPassword($identifier)
  {
    // Try to find by username OR email OR IDNumber in `o_users` then `users`.
    $escaped = $this->db->escape($identifier);
    $where = "(username = $escaped OR email = $escaped OR IDNumber = $escaped)";

    $q = $this->db->where($where, null, false)->get('o_users', 1);
    if ($q->num_rows() > 0) {
      return $q->row_array();
    }

    $q = $this->db->where($where, null, false)->get('users', 1);
    return $q->row_array();
  }

  public function findUserByEmail($email)
  {
    // Find user by email in o_users first, then users
    $escaped = $this->db->escape($email);

    $q = $this->db->where('email', $email)->get('o_users', 1);
    if ($q->num_rows() > 0) {
      return $q->row_array();
    }

    $q = $this->db->where('email', $email)->get('users', 1);
    return $q->row_array();
  }

  public function sendpassword(array $user, string $targetEmail)
  {
    // Email where the user wants to receive the link
    $email = trim($targetEmail);

    if ($email === '') {
      $this->session->set_flashdata('error', 'Email address is empty. Cannot send reset link.');
      redirect('login/forgot', 'refresh');
      return;
    }

    // Build simple reset link using username only
    $reset_link = site_url('login/reset?u=' . urlencode($user['username']));

    // Load email config + lib
    $this->load->config('email');
    $this->load->library('email');

    $this->email->set_mailtype('html');
    $this->email->set_newline("\r\n");

    $fname = isset($user['fName']) ? $user['fName'] : '';

    // Use your pretty reset_password.php template
    $mail_message = $this->load->view(
      'reset_password',   // email view
      [
        'user'       => (object) $user,
        'reset_link' => $reset_link,
        'expires_at' => null,   // you’re not enforcing expiry now
      ],
      TRUE
    );

    // Important: must match smtp_user domain (srmsportal.com)
    $this->email->from('no-reply@srmsportal.com', 'BAC Online Team');
    $this->email->to($email);
    $this->email->subject('Password Reset Request');
    $this->email->message($mail_message);

    if ($this->email->send()) {
      $this->session->set_flashdata('success', 'Password reset link sent to your email!');
    } else {
      $debug = $this->email->print_debugger();
      log_message('error', 'Password reset email failed: ' . $debug);
      $this->session->set_flashdata('error', 'Failed to send reset link, please try again!');
    }

    redirect('login', 'refresh');
  }
}
