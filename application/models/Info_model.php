<?php

class Info_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* read functions */

    public function get() {
        $this->db->select()->from('info');

        return fetch($this->db);
    }

    /* update functions */

    public function set($data) {
        $this->db->update('info', $data);
    }

}

?>
