<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    private $defaults = [
        'site_name'     => 'EZShop',
        'tagline'       => 'Curated fits, modern essentials.',
        'support_email' => 'support@ezshop.local',
        'support_phone' => '+63 900 000 0000',
        'address'       => '123 EZ Street, Metro Manila',
        'logo_path'     => 'Products/Logo1.png',
        'facebook_url'  => 'https://facebook.com',
        'instagram_url' => 'https://instagram.com',
        'youtube_url'   => 'https://youtube.com',
        'hero_title'    => 'Elevate Your Style, Define Your Identity',
        'hero_subtitle' => 'Discover curated fashion that speaks to your unique personality. From sophisticated workwear to weekend essentials.',
        'hero_cta_label' => 'Shop Now',
        'hero_cta_link' => 'Ezshop/shop',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch the first row from site_settings, merged with defaults.
     */
    public function get_settings()
    {
        if (!$this->db->table_exists('site_settings')) {
            return $this->defaults;
        }

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
        if (!$this->db->table_exists('site_settings')) {
            return false;
        }

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
