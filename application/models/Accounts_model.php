<?php

class Accounts_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* create functions */

    public function add($email, $password) {
        do { $id = random_id(5); } while ($this->id_exists($id));

        $data['id'] = $id;
        $data['email'] = $email;
        $data['password'] = $password;
        $data['tickets'] = 0;
        $data['visitors'] = '';
        $data['last_order'] = 0;
        $data['check_ins'] = 0;
        $data['category_id'] = 0;
        $data['order_id'] = 0;

        $this->db->insert('accounts', $data);
    }

    /* read functions */

    public function id_exists($id) {
        return $this->get_by_id($id) !== NULL;
    }

    public function email_exists($email) {
        return $this->get_by_email($email) !== NULL;
    }

    public function get_by_id($id) {
        $this->db->select()->from('accounts');
        $this->db->where('id =', $id);

        return fetch($this->db);
    }

    public function get_by_email($email) {
        $this->db->select()->from('accounts');
        $this->db->where('email =' , $email);

        return fetch($this->db);
    }

    public function sum_tickets($category_id) {
        $this->db->select_sum('tickets')->from('accounts');
        $this->db->where('category_id =', $category_id);

        $result = fetch($this->db);

        return isset($result) ? $result->tickets : NULL;
    }

    public function get_all_account_details($items_per_page, $page, $filter, $date = NULL) {
        $this->db->select('*')->from('accounts');
        $this->db->where('category_id !=', 0);
        $this->db->where('tickets >', 0);
        if (!empty($date)) $this->db->where('accepted =', $date);
        $this->db->like('order_id', $filter, 'before');
        $this->db->limit($items_per_page)->offset(($page - 1) * $items_per_page);

        return fetch($this->db, -1);
    }

    public function get_all_account_details_maxcount($filter, $date = NULL) {
        $this->db->select('COUNT(*)')->from('accounts');
        $this->db->where('category_id !=', 0);
        $this->db->where('tickets >', 0);
        if (!empty($date)) $this->db->where('accepted =', $date);
        $this->db->like('order_id', $filter, 'before');
        $result = $this->db->get()->row_array(0);

        return isset($result) ? $result['COUNT(*)'] : 0;
    }

    /* update functions */

    public function set_by_id($id, $data) {
        $this->db->where('id =', $id);
        $this->db->update('accounts', $data);
    }

    public function set_by_email($email, $data) {
        $this->db->where('email =', $email);
        $this->db->update('accounts', $data);
    }

}

?>
