<?php

class Contact_persons_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* read functions */

    public function get_all() {
        $this->db->select('*')->from('contact_persons');

        return fetch($this->db, -1);
    }

}

?>
