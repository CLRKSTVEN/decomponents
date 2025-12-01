<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all categories.
     */
    public function all()
    {
        if (!$this->db->table_exists('categories')) {
            return [];
        }

        return $this->db
            ->order_by('name', 'ASC')
            ->get('categories')
            ->result_array();
    }
}
