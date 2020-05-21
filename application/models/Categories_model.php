<?php

class Categories_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function get($id) {
        $this->db->select()->from('categories');
        $this->db->where('id =', $id);

        return fetch($this->db);
    }

    public function get_all() {
        $this->db->select()->from('categories');

        return fetch($this->db, -1);
    }

    public function set($id, $data) {
        $this->db->where('id =', $id);
        $this->db->update('categories', $data);
    }

}

?>
