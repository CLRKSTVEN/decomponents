<?php
// Reuse the legacy login page template (home_page.php) so old routes keep working.
$this->load->view('home_page', [
    'data'         => isset($data) ? $data : [],
    'allow_signup' => isset($allow_signup) ? $allow_signup : 'Yes',
    'active_sy'    => isset($active_sy) ? $active_sy : '',
    'active_sem'   => isset($active_sem) ? $active_sem : '',
]);
