<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Make sure the contact tables exist.
     */
    public function ensure_tables()
    {
        // Staff who can receive/handle messages.
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `contact_staff` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(150) NOT NULL,
                `email` varchar(180) NOT NULL,
                `phone` varchar(60) DEFAULT NULL,
                `role` varchar(120) DEFAULT NULL,
                `created_at` datetime DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_staff_email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");

        // Messages submitted from contact forms.
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `contact_messages` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(150) NOT NULL,
                `email` varchar(180) NOT NULL,
                `subject` varchar(200) NOT NULL,
                `message` text NOT NULL,
                `status` enum('new','read','closed') DEFAULT 'new',
                `staff_id` int(11) DEFAULT NULL,
                `created_at` datetime DEFAULT current_timestamp(),
                PRIMARY KEY (`id`),
                KEY `staff_id` (`staff_id`),
                CONSTRAINT `fk_contact_messages_staff` FOREIGN KEY (`staff_id`) REFERENCES `contact_staff`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
        ");
    }

    public function add_staff(array $data)
    {
        $this->ensure_tables();
        $this->db->insert('contact_staff', $data);
        return $this->db->affected_rows() > 0 ? (int)$this->db->insert_id() : false;
    }

    public function get_staff($limit = 100)
    {
        if (!$this->db->table_exists('contact_staff')) {
            return [];
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('contact_staff')
            ->result_array();
    }

    public function delete_staff($id)
    {
        if (!$this->db->table_exists('contact_staff')) {
            return false;
        }
        return $this->db->where('id', (int)$id)->delete('contact_staff');
    }

    public function save_message(array $data)
    {
        $this->ensure_tables();
        $this->db->insert('contact_messages', $data);
        return $this->db->affected_rows() > 0;
    }

    public function get_messages($limit = 100)
    {
        if (!$this->db->table_exists('contact_messages')) {
            return [];
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('contact_messages')
            ->result_array();
    }
}
