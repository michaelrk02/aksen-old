<?php

class Orders_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* create functions */

    public function add($account_id, $order_id, $category_id, $tickets) {
        $data['id'] = $account_id;
        $data['order_id'] = $order_id;
        $data['category_id'] = $category_id;
        $data['tickets'] = $tickets;

        $this->db->insert('orders', $data);
    }

    /* read functions */

    public function account_id_exists($account_id) {
        return $this->get($account_id) != NULL;
    }

    public function get($account_id) {
        $this->db->select()->from('orders');
        $this->db->where('id =', $account_id);

        return fetch($this->db);
    }

    /* update functions */

    public function set($account_id, $data) {
        $this->db->where('id =', $account_id);
        $this->db->update('orders', $data);
    }

    /* delete functions */

    public function remove($account_id) {
        $this->db->where('id =', $account_id);
        $this->db->delete('orders');
    }

    /* extensions */

    public function get_all_order_details($items_per_page, $page, $view_all, $filter) {
        $this->db->select('accounts.id, email, visitors, orders.tickets, orders.category_id, last_order, orders.order_id')->from('accounts');
        $this->db->join('orders', 'accounts.id = orders.id');

        if (!$view_all) {
            $this->db->where('CEIL(('.time().' - last_order) / (24 * 60 * 60)) > 1');
        }

        $this->db->like('orders.order_id', $filter, 'before');
        $this->db->limit($items_per_page)->offset(($page - 1) * $items_per_page);

        return fetch($this->db, -1);
    }

    public function get_all_order_details_maxcount($view_all, $filter) {
        $this->db->select('COUNT(*)')->from('accounts');
        $this->db->join('orders', 'accounts.id = orders.id');

        if (!$view_all) {
            $this->db->where('CEIL(('.time().' - last_order) / (24 * 60 * 60)) > 1');
        }

        $this->db->like('orders.order_id', $filter, 'before');
        $result = $this->db->get()->row_array(0);

        return isset($result) ? $result['COUNT(*)'] : 0;
    }

}

?>
