<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_visual_model extends CI_Model
{
    private $table = 'auth_visuals';

    private $fallbacks = [
        'login' => [
            'badge'         => 'Premium Fashion',
            'headline'      => 'Welcome Back to Style',
            'subheadline'   => 'Sign in to access your personalized shopping experience, saved favorites, and exclusive member benefits.',
            'feature_one'   => 'Track your orders in real-time',
            'feature_two'   => 'Save favorites & build wishlists',
            'feature_three' => 'Exclusive member-only deals',
            'feature_four'  => 'Fast & secure checkout',
            'image_path'    => 'Products/advertise2.jpg',
        ],
        'signup' => [
            'badge'         => 'Join Premium Fashion',
            'headline'      => 'Start Your Style Journey',
            'subheadline'   => 'Create your account to unlock exclusive member benefits, personalized recommendations, and seamless shopping.',
            'feature_one'   => 'Curated essentials & occasion pieces',
            'feature_two'   => 'Fast shipping with easy exchanges',
            'feature_three' => 'Secure checkout & saved preferences',
            'feature_four'  => 'Early access to new collections',
            'image_path'    => 'Products/advertise3.jpg',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table();
    }

    /**
     * Ensure the auth_visuals table exists.
     */
    private function ensure_table()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `page` enum('login','signup') NOT NULL DEFAULT 'login',
            `badge` varchar(120) DEFAULT NULL,
            `headline` varchar(255) NOT NULL,
            `subheadline` varchar(255) DEFAULT NULL,
            `feature_one` varchar(255) DEFAULT NULL,
            `feature_two` varchar(255) DEFAULT NULL,
            `feature_three` varchar(255) DEFAULT NULL,
            `feature_four` varchar(255) DEFAULT NULL,
            `image_path` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->db->query($sql);
    }

    /**
     * Fetch all records ordered by page and recency.
     */
    public function all()
    {
        $this->ensure_table();
        return $this->db
            ->order_by('page', 'ASC')
            ->order_by('is_active', 'DESC')
            ->order_by('updated_at', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    /**
     * Find a single record.
     */
    public function find($id)
    {
        $this->ensure_table();
        return $this->db
            ->where('id', (int)$id)
            ->get($this->table)
            ->row_array();
    }

    /**
     * Get the active visual for a page, or fallback defaults.
     */
    public function get_active_by_page($page)
    {
        $this->ensure_table();
        $page = $this->normalize_page($page);

        $row = $this->db
            ->where('page', $page)
            ->where('is_active', 1)
            ->order_by('updated_at', 'DESC')
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get($this->table)
            ->row_array();

        if (!$row) {
            $row = $this->db
                ->where('page', $page)
                ->order_by('updated_at', 'DESC')
                ->order_by('id', 'DESC')
                ->limit(1)
                ->get($this->table)
                ->row_array();
        }

        if ($row) {
            $row['page'] = $page;
            return $row;
        }

        return $this->fallbacks[$page] + [
            'id'        => null,
            'page'      => $page,
            'is_active' => 1,
        ];
    }

    /**
     * Create a new record.
     */
    public function create(array $data)
    {
        $this->ensure_table();
        $payload = $this->filter_payload($data);
        if (empty($payload['headline'])) {
            return false;
        }

        if (!empty($payload['is_active'])) {
            $this->deactivate_others($payload['page']);
        }

        $payload['created_at'] = date('Y-m-d H:i:s');
        $payload['updated_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $payload);
        return $this->db->affected_rows() > 0 ? (int)$this->db->insert_id() : false;
    }

    /**
     * Update a record.
     */
    public function update($id, array $data)
    {
        $this->ensure_table();
        $payload = $this->filter_payload($data);
        if (empty($payload['headline'])) {
            return false;
        }

        if (!empty($payload['is_active'])) {
            $this->deactivate_others($payload['page'], (int)$id);
        }

        $payload['updated_at'] = date('Y-m-d H:i:s');
        return $this->db
            ->where('id', (int)$id)
            ->update($this->table, $payload);
    }

    /**
     * Delete a record.
     */
    public function delete($id)
    {
        $this->ensure_table();
        return $this->db->where('id', (int)$id)->delete($this->table);
    }

    private function filter_payload(array $data)
    {
        $page = $this->normalize_page($data['page'] ?? 'login');

        return [
            'page'          => $page,
            'badge'         => $this->trim_or_null($data['badge'] ?? null),
            'headline'      => trim((string)($data['headline'] ?? '')),
            'subheadline'   => $this->trim_or_null($data['subheadline'] ?? null),
            'feature_one'   => $this->trim_or_null($data['feature_one'] ?? null),
            'feature_two'   => $this->trim_or_null($data['feature_two'] ?? null),
            'feature_three' => $this->trim_or_null($data['feature_three'] ?? null),
            'feature_four'  => $this->trim_or_null($data['feature_four'] ?? null),
            'image_path'    => $this->trim_or_null($data['image_path'] ?? null),
            'is_active'     => !empty($data['is_active']) ? 1 : 0,
        ];
    }

    private function normalize_page($page)
    {
        $page = strtolower((string)$page);
        return in_array($page, ['login', 'signup'], true) ? $page : 'login';
    }

    private function trim_or_null($value)
    {
        if ($value === null) {
            return null;
        }
        $value = trim((string)$value);
        return $value === '' ? null : $value;
    }

    private function deactivate_others($page, $skipId = null)
    {
        $this->db->where('page', $this->normalize_page($page));
        if ($skipId !== null) {
            $this->db->where('id !=', (int)$skipId);
        }
        $this->db->update($this->table, ['is_active' => 0]);
    }
}
