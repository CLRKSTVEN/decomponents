<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return all products with their category name/slug.
     */
    public function get_all_with_categories()
    {
        if (!$this->db->table_exists('products')) {
            return [];
        }

        return $this->db
            ->select('p.*, c.name as category_name, c.slug as category_slug')
            ->from('products p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->order_by('p.created_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Fetch a single product by id.
     */
    public function get_product($id)
    {
        if (!$this->db->table_exists('products')) {
            return null;
        }

        return $this->db
            ->where('id', $id)
            ->get('products')
            ->row_array();
    }

    /**
     * Fetch products by category slug (e.g., menswear, womenswear).
     * Returns an array of associative rows.
     */
    public function get_products_by_category_slug($slug)
    {
        if (!$slug) {
            return [];
        }

        if (!$this->db->table_exists('products')) {
            return [];
        }

        return $this->db
            ->select('p.id, p.name, p.slug, p.description, p.price, p.image, p.is_featured, p.inventory, c.slug as category_slug')
            ->from('products p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('c.slug', $slug)
            ->order_by('p.created_at', 'DESC')
            ->get()
            ->result_array();
    }

    /**
     * Insert a product row.
     */
    public function create_product(array $data)
    {
        $this->db->insert('products', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update product row.
     */
    public function update_product($id, array $data)
    {
        return $this->db->where('id', $id)->update('products', $data);
    }

    /**
     * Delete product.
     */
    public function delete_product($id)
    {
        return $this->db->where('id', $id)->delete('products');
    }

    /**
     * Featured products for landing/home.
     */
    public function get_featured_products($limit = 6)
    {
        if (!$this->db->table_exists('products')) {
            return [];
        }

        return $this->db
            ->where('is_featured', 1)
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('products')
            ->result_array();
    }

    /**
     * Count products by category slug.
     */
    public function count_by_category_slug($slug)
    {
        if (!$slug || !$this->db->table_exists('products')) {
            return 0;
        }
        return (int)$this->db
            ->from('products p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('c.slug', $slug)
            ->count_all_results();
    }

    /**
     * Paginated products by category slug.
     */
    public function get_products_by_category_slug_paginated($slug, $limit, $offset = 0)
    {
        if (!$slug || !$this->db->table_exists('products')) {
            return [];
        }

        return $this->db
            ->select('p.id, p.name, p.slug, p.description, p.price, p.image, p.is_featured, p.inventory, c.slug as category_slug')
            ->from('products p')
            ->join('categories c', 'c.id = p.category_id', 'left')
            ->where('c.slug', $slug)
            ->order_by('p.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result_array();
    }
}
