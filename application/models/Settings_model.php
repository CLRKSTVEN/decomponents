<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    private $defaults = [
        'site_name'      => 'DeComponents',
        'tagline'        => 'PC parts, trading, and upgrades in one place.',
        'support_email'  => 'support@decomponents.local',
        'support_phone'  => '+63 900 000 0000',
        'address'        => '123 DeComponents St, Metro Manila',
        'logo_path'      => 'Pictures/DeComponents.jpeg',
        'hero_image'     => 'Pictures/intelcpu.jpg',
        'facebook_url'   => 'https://facebook.com/decomponents',
        'instagram_url'  => 'https://instagram.com/decomponents',
        'youtube_url'    => 'https://youtube.com/@DeComponents',
        'hero_title'     => 'Build Faster. Trade Smarter.',
        'hero_subtitle'  => 'GPUs, CPUs, PSUs, and more curated for your next rig.',
        'hero_cta_label' => 'Shop Components',
        'hero_cta_link'  => 'products',
    ];

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table();
    }

    /**
     * Ensure site_settings table exists with the expected columns.
     */
    private function ensure_table()
    {
        if ($this->db->table_exists('site_settings')) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `site_settings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `site_name` varchar(255) DEFAULT NULL,
            `tagline` varchar(255) DEFAULT NULL,
            `support_email` varchar(255) DEFAULT NULL,
            `support_phone` varchar(100) DEFAULT NULL,
            `address` text DEFAULT NULL,
            `logo_path` varchar(255) DEFAULT NULL,
            `facebook_url` varchar(255) DEFAULT NULL,
            `instagram_url` varchar(255) DEFAULT NULL,
            `youtube_url` varchar(255) DEFAULT NULL,
            `hero_image` varchar(255) DEFAULT NULL,
            `hero_title` varchar(255) DEFAULT NULL,
            `hero_subtitle` varchar(255) DEFAULT NULL,
            `hero_cta_label` varchar(255) DEFAULT NULL,
            `hero_cta_link` varchar(255) DEFAULT NULL,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->db->query($sql);
    }

    /**
     * Fetch the first row from site_settings, merged with defaults.
     */
    public function get_settings()
    {
        $this->ensure_table();

        $row = $this->db->limit(1)->get('site_settings')->row_array();
        if (!$row) {
            return $this->defaults;
        }

        return array_merge($this->defaults, $row);
    }

    /**
     * Insert or update the single settings row.
     */
    public function upsert_settings(array $data)
    {
        $this->ensure_table();

        $row = $this->db->limit(1)->get('site_settings')->row_array();
        if ($row) {
            return $this->db->where('id', $row['id'])->update('site_settings', $data);
        }

        // Ensure a primary key value if table does not auto-increment id
        if (!isset($data['id'])) {
            $data['id'] = 1;
        }

        return $this->db->insert('site_settings', $data);
    }
}
