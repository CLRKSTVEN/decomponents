<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Decomponents_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get user by email.
     */
    public function get_user_by_email($email)
    {
        $user = $this->db
            ->where('email', $email)
            ->get('users')
            ->row();

        return $this->append_fullname($user);
    }

    /**
     * Get user by id.
     */
    public function get_user_by_id($id)
    {
        $user = $this->db
            ->where('id', $id)
            ->get('users')
            ->row();

        return $this->append_fullname($user);
    }

    /**
     * Insert new user.
     * $data = ['FirstName','MiddleName','LastName','email','password','profile_picture','role','created_at']
     */
    public function create_user(array $data)
    {
        $this->db->insert('users', $data);
        if ($this->db->affected_rows() > 0) {
            return $this->db->insert_id();
        }
        return false;
    }

    /**
     * Update user fields.
     */
    public function update_user($id, array $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }

    /**
     * Insert an order row.
     * $data keys: user_id, full_name, email, shipping_address, payment_method, status, total_amount, created_at
     */
    public function create_order(array $data)
    {
        $this->db->insert('orders', $data);
        if ($this->db->affected_rows() > 0) {
            return (int)$this->db->insert_id();
        }
        return false;
    }

    /**
     * Insert a single order item if the table exists.
     */
    public function add_order_item($orderId, $productId, $qty, $unitPrice)
    {
        if (!$this->db->table_exists('order_items')) {
            return false;
        }
        $data = [
            'order_id'   => (int)$orderId,
            'product_id' => (int)$productId,
            'quantity'   => (int)max(1, $qty),
            'unit_price' => (float)$unitPrice,
        ];
        $this->db->insert('order_items', $data);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Get orders for a user.
     */
    public function get_orders_by_user($userId)
    {
        $hasOrderItems = $this->db->table_exists('order_items');
        $hasProducts   = $this->db->table_exists('products');

        if ($hasOrderItems && $hasProducts) {
            $rows = $this->db
                ->select('o.*, MAX(p.image) AS product_image, MAX(p.name) AS product_name')
                ->from('orders o')
                ->join('order_items oi', 'oi.order_id = o.id', 'left')
                ->join('products p', 'p.id = oi.product_id', 'left')
                ->where('o.user_id', $userId)
                ->group_by('o.id')
                ->order_by('o.created_at', 'DESC')
                ->get()
                ->result();

            // Fallback thumbnail if no item is linked
            if (!empty($rows)) {
                $fallback = $this->db->select('image, name')->from('products')->limit(1)->get()->row_array();
                if (!empty($fallback)) {
                    foreach ($rows as &$r) {
                        if (empty($r->product_image)) {
                            $r->product_image = $fallback['image'];
                            $r->product_name = $fallback['name'];
                        }
                    }
                    unset($r);
                }
            }
            return $rows;
        }

        // Fallback: basic orders list
        return $this->db
            ->where('user_id', $userId)
            ->order_by('created_at', 'DESC')
            ->get('orders')
            ->result();
    }

    /**
     * Basic stats helpers for admin dashboard.
     */
    public function count_users($role = null)
    {
        if (!$this->db->table_exists('users')) {
            return 0;
        }
        if ($role !== null) {
            $this->db->where('role', $role);
        }
        return (int)$this->db->count_all_results('users');
    }

    public function count_products()
    {
        if (!$this->db->table_exists('products')) {
            return 0;
        }
        return (int)$this->db->count_all('products');
    }

    public function count_orders($status = null)
    {
        if (!$this->db->table_exists('orders')) {
            return 0;
        }
        if ($status !== null) {
            $this->db->where('status', $status);
        }
        return (int)$this->db->count_all_results('orders');
    }

    public function recent_orders($limit = 5)
    {
        if (!$this->db->table_exists('orders')) {
            return [];
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('orders')
            ->result_array();
    }

    /**
     * Update order status (admin).
     */
    public function update_order_status($orderId, $status)
    {
        $allowed = ['pending', 'paid', 'fulfilled', 'cancelled'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        return $this->db->where('id', $orderId)->update('orders', ['status' => $status]);
    }

    /**
     * Get a single order by id.
     */
    public function get_order($orderId)
    {
        if (!$this->db->table_exists('orders')) {
            return null;
        }
        return $this->db->where('id', $orderId)->get('orders')->row_array();
    }

    /**
     * All orders (admin).
     */
    public function get_all_orders($limit = 100)
    {
        if (!$this->db->table_exists('orders')) {
            return [];
        }
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('orders')
            ->result_array();
    }

    /**
     * All customers (admin).
     */
    public function get_customers($limit = 100)
    {
        if (!$this->db->table_exists('users')) {
            return [];
        }
        return $this->db
            ->where('role', 'customer')
            ->order_by('created_at', 'DESC')
            ->limit($limit)
            ->get('users')
            ->result_array();
    }

    /**
     * Add a computed fullname property for convenience.
     */
    private function append_fullname($user)
    {
        if ($user) {
            $first  = trim((string)($user->FirstName ?? ''));
            $middle = trim((string)($user->MiddleName ?? ''));
            $last   = trim((string)($user->LastName ?? ''));

            $user->fullname = trim($first . ' ' . $middle . ' ' . $last);

            $front = trim($first . ($middle ? ' ' . $middle : ''));
            if ($last !== '' && $front !== '') {
                $user->fullname_sorted = $last . ', ' . $front;
            } elseif ($last !== '') {
                $user->fullname_sorted = $last;
            } else {
                $user->fullname_sorted = $front;
            }
        }
        return $user;
    }

    /**
     * Ensure an admin user exists; create if missing.
     */
    public function ensure_admin_user($email, $password)
    {
        if (!$this->db->table_exists('users')) {
            return null;
        }

        $existing = $this->get_user_by_email($email);
        if ($existing) {
            return $existing;
        }

        $data = [
            'FirstName'       => 'Admin',
            'MiddleName'      => '',
            'LastName'        => 'User',
            'email'           => $email,
            'password'        => password_hash($password, PASSWORD_DEFAULT),
            'profile_picture' => 'upload/profile/avatar.png',
            'role'            => 'admin', // enum requires lowercase
            'created_at'      => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('users', $data);
        return $this->get_user_by_email($email);
    }
}
