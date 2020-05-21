<?php

class Recoveries_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /* create functions */

    public function add($account_id, $recovery_code) {
        $data['id'] = $account_id;
        $data['recovery_code'] = $recovery_code;

        $this->db->insert('recoveries', $data);
    }

    /* read functions */

    public function account_id_exists($account_id) {
        return $this->get_by_account_id($account_id) !== NULL;
    }

    public function recovery_code_exists($recovery_code) {
        return $this->get_by_recovery_code($recovery_code) != NULL;
    }

    public function get_by_account_id($account_id) {
        $this->db->select()->from('recoveries');
        $this->db->where('id =', $account_id);

        return fetch($this->db);
    }

    public function get_by_recovery_code($recovery_code) {
        $this->db->select()->from('recoveries');
        $this->db->where('recovery_code =', $recovery_code);

        return fetch($this->db);
    }

    /* delete functions */

    public function remove_by_account_id($account_id) {
        $this->db->where('id =', $account_id);
        $this->db->delete('recoveries');
    }

    public function remove_by_recovery_code($recovery_code) {
        $this->db->where('recovery_code =', $recovery_code);
        $this->db->delete('recoveries');
    }

}

?>
