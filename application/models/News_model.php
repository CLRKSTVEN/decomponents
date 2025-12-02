<?php
defined('BASEPATH') or exit('No direct script access allowed');

class News_model extends CI_Model
{
    private $table = 'news';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_table();
    }

    private function ensure_table()
    {
        if ($this->db->table_exists($this->table)) {
            return;
        }

        $this->load->dbforge();
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'unique'     => true,
            ],
            'excerpt' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'body' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'is_published' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table($this->table, true);
    }

    private function slugify($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        if ($slug === '') {
            $slug = 'news-' . time();
        }
        return $slug;
    }

    private function ensure_unique_slug($baseSlug, $id = null)
    {
        $slug = $baseSlug;
        $i = 1;
        while (true) {
            $this->db->where('slug', $slug);
            if ($id) {
                $this->db->where('id !=', (int)$id);
            }
            $exists = $this->db->get($this->table)->row();
            if (!$exists) {
                return $slug;
            }
            $slug = $baseSlug . '-' . $i;
            $i++;
        }
    }

    public function all($publishedOnly = true)
    {
        if (!$this->db->table_exists($this->table)) {
            return [];
        }
        if ($publishedOnly) {
            $this->db->where('is_published', 1);
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    public function find($id)
    {
        if (!$this->db->table_exists($this->table)) {
            return null;
        }
        return $this->db->get_where($this->table, ['id' => (int)$id])->row_array();
    }

    public function save($data, $id = null)
    {
        if (empty($data['title'])) {
            return false;
        }

        $baseSlug = $this->slugify($data['title']);
        $data['slug'] = $this->ensure_unique_slug($baseSlug, $id);
        $now = date('Y-m-d H:i:s');

        if ($id) {
            $data['updated_at'] = $now;
            return $this->db->update($this->table, $data, ['id' => (int)$id]);
        }

        $data['created_at'] = $now;
        $data['updated_at'] = $now;
        return $this->db->insert($this->table, $data);
    }

    public function delete($id)
    {
        if (!$this->db->table_exists($this->table)) {
            return false;
        }
        return $this->db->delete($this->table, ['id' => (int)$id]);
    }
}
