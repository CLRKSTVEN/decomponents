<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials_model extends CI_Model
{
    private $table = 'testimonials';

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table();
    }

    private function ensure_table()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $sql = "CREATE TABLE IF NOT EXISTS `{$this->table}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `role` varchar(255) DEFAULT NULL,
            `quote` text NOT NULL,
            `image` varchar(255) DEFAULT NULL,
            `is_active` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime DEFAULT current_timestamp(),
            `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $this->db->query($sql);
    }

    public function all()
    {
        $this->ensure_table();
        return $this->db
            ->order_by('is_active', 'DESC')
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    public function find($id)
    {
        $this->ensure_table();
        return $this->db->where('id', (int)$id)->get($this->table)->row_array();
    }

    public function get_active($limit = 4)
    {
        $this->ensure_table();
        return $this->db
            ->where('is_active', 1)
            ->order_by('updated_at', 'DESC')
            ->limit($limit)
            ->get($this->table)
            ->result_array();
    }

    public function create(array $data)
    {
        $this->ensure_table();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0;
    }

    public function update($id, array $data)
    {
        $this->ensure_table();
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->where('id', (int)$id)->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->ensure_table();
        return $this->db->where('id', (int)$id)->delete($this->table);
    }
}
