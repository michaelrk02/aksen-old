<?php

class Accounts_tmp_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* create functions */

    public function add($registration_code, $email, $password) {
        $data['registration_code'] = $registration_code;
        $data['email'] = $email;
        $data['password'] = $password;

        $this->db->insert('accounts_tmp', $data);
    }

    /* read functions */

    public function registration_code_exists($registration_code) {
        return $this->get_by_registration_code($registration_code) !== NULL;
    }

    public function email_exists($email) {
        return $this->get_by_email($email) !== NULL;
    }

    public function get_by_registration_code($registration_code) {
        $this->db->select()->from('accounts_tmp');
        $this->db->where('registration_code =', $registration_code);

        return fetch($this->db);
    }

    public function get_by_email($email) {
        $this->db->select()->from('accounts_tmp');
        $this->db->where('email =', $email);

        return fetch($this->db);
    }

    /* delete functions */

    public function remove_by_registration_code($registration_code) {
        $this->db->where('registration_code =', $registration_code);
        $this->db->delete('accounts_tmp');
    }

    public function remove_by_email($email) {
        $this->db->where('email =', $email);
        $this->db->delete('accounts_tmp');
    }

}

?>
