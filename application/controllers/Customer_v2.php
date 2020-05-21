<?php

class Customer_v2 extends CI_Controller {

    private $data;

    public function __construct() {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->library('session');

        $this->load->config('ticketing');

        if (isset($this->session->e_ticketing_login)) {
            $this->data['login'] = $this->session->e_ticketing_login;
        }
    }

    public function index() {
        $this->login_check();
        $this->validate_action(TRUE, TRUE, TRUE);
    }

    // STEP 1
    public function auth($action = 'undefined') {
        if ($action === 'undefined') {
            redirect(site_url('customer_v2/auth/sign_up').'#auth-page');
        }
        if (isset($this->session->e_ticketing_login)) {
            redirect(site_url('customer_v2/dashboard').'#dashboard-page');
        }
        $this->discard_status();

        $this->data['title'] = 'Langkah 1 dari 3';
        $this->data['info'] = $this->ticketing->get_info();

        if ($action === 'sign_in') {
            $this->ticketing->form_enable('email');
            $this->ticketing->form_enable('password');

            if ($this->form_validation->run() === TRUE) {
                $this->ticketing->prevent_flood();

                if (!isset($this->session->e_ticketing_login)) {
                    $email = $this->input->post('email');
                    $password = $this->input->post('password');

                    $this->ticketing->login($email, $password);
                    if ($this->ticketing->succeeded()) {
                        $this->validate_action(TRUE, TRUE, TRUE);
                    } else {
                        $this->discard_status();
                        //redirect(site_url('customer_v2/auth/sign_in').'#auth-page');
                    }
                }
            } else {
                $this->ticketing->check_form_errors($this->data);
            }
        } else if ($action === 'sign_up') {
            $this->ticketing->form_enable('email');
            $this->ticketing->form_enable('password');
            $this->ticketing->form_enable('password_repeat');
            $this->ticketing->form_enable('captcha');
            $this->ticketing->form_enable('valid_captcha');

            if ($this->form_validation->run() === TRUE) {
                $this->ticketing->prevent_flood();

                $email = $this->input->post('email');
                $password = $this->input->post('password');

                $this->ticketing->register($email, $password);
                redirect(site_url('customer_v2/auth/sign_up').'#auth-page');
            } else {
                $this->ticketing->check_form_errors($this->data);
            }

            $this->data['captcha'] = random_id(5);
        }

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/auth');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    // STEP 2
    public function order($alumni = 0) {
        $this->login_check();
        $this->validate_action(FALSE, TRUE, TRUE);
        $this->discard_status();

        $this->data['title'] = 'Langkah 2 dari 3';
        $this->data['alumni'] = $alumni != 0;

        $this->ticketing->form_enable('tickets');
        $this->ticketing->form_enable('visitors');

        if ($this->form_validation->run() === TRUE) {
            $this->ticketing->prevent_flood();

            $amount = $this->input->post('tickets');
            $visitors = $this->input->post('visitors');

            $this->ticketing->order($this->session->e_ticketing_login, $amount, $visitors, $alumni != 0);
            $this->validate_action(TRUE, TRUE, TRUE);
        } else {
            $this->ticketing->check_form_errors($this->data);
        }

        $account = $this->ticketing->ci->accounts_model->get_by_id($this->session->e_ticketing_login);
        $category = $alumni == 0 ? NULL : $this->ticketing->get_info()->alumni_id;

        $tix = $this->ticketing->get_available_tickets($category);
        $tix = ($tix < 0) ? 0 : $tix;

        $this->data['category'] = $this->ticketing->get_category($category);

        $this->data['available_tickets'] = $tix;

        $this->data['account'] = $account;
        $this->data['visitors'] = json_decode($account->visitors);
        $this->data['visitors'] = isset($this->data['visitors']) ? $this->data['visitors'] : array();

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/order');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    // STEP 3
    public function transfer() {
        $this->login_check();
        $this->validate_action(TRUE, FALSE, TRUE);
        $this->discard_status();

        $account = $this->ticketing->ci->accounts_model->get_by_id($this->session->e_ticketing_login);

        $this->data['info'] = $this->ticketing->get_info();

        $this->data['title'] = 'Langkah 3 dari 3';
        $this->data['account'] = $account;
        $this->data['bill'] = $this->ticketing->get_tickets_cost($account->id);

        $order = $this->ticketing->ci->orders_model->get($account->id);
        $this->data['order'] = $order;
        $this->data['category'] = $this->ticketing->ci->categories_model->get($order->category_id);
        $this->data['bank_account'] = $this->ticketing->get_info()->bank_account;

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/transfer');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    // STEP 4
    public function dashboard() {
        $this->login_check();
        $this->validate_action(TRUE, TRUE, FALSE);
        $this->discard_status();

        $account = $this->ticketing->ci->accounts_model->get_by_id($this->session->e_ticketing_login);

        $this->data['title'] = 'Dashboard';
        $this->data['account'] = $account;
        $this->data['info'] = $this->ticketing->get_info();
        $this->data['visitors'] = json_decode($account->visitors);
        $this->data['alumni'] = $account->category_id == $this->data['info']->alumni_id;
        $this->data['category'] = $this->ticketing->get_category($account->category_id);

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/dashboard');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    public function help($help_kind = NULL) {
        $this->discard_status();

        $this->ticketing->form_enable('email');
        $this->ticketing->form_enable('help_kind');
        $this->ticketing->form_enable('captcha');
        $this->ticketing->form_enable('valid_captcha');

        if (isset($help_kind)) {
            $this->data['help_kind'] = $help_kind;
        }

        if ($this->form_validation->run() === TRUE) {
            $this->ticketing->prevent_flood();

            $email = $this->input->post('email');
            $help_kind = $this->input->post('help_kind');

            $this->ticketing->send_help($email, $help_kind);
            redirect(site_url('customer_v2/help').'#help-page');
        } else {
            $this->ticketing->check_form_errors($this->data);
        }

        $this->data['title'] = 'Perlu Bantuan dari Sistem Kami?';
        $this->data['captcha'] = random_id(5);

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/help');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    public function terms() {
        $this->data['title'] = 'Syarat dan Ketentuan';

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/terms');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    public function info() {
        $this->data['title'] = 'Informasi Lanjut';
        $this->data['info'] = $this->ticketing->get_info();
        $this->data['contact_persons'] = $this->ticketing->ci->contact_persons_model->get_all();

        $this->render('templates/page_header');
        $this->render('customer_v2/navbar');
        $this->render('templates/body_header');
        $this->render('customer_v2/info');
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    public function activate($registration_code) {
        $this->ticketing->prevent_flood();
        $this->ticketing->activate($registration_code);
        $this->login_check();
        $this->validate_action(TRUE, TRUE, TRUE);
    }

    public function recover($recovery_code) {
        $this->ticketing->prevent_flood();

        $recovery = $this->ticketing->ci->recoveries_model->get_by_recovery_code($recovery_code);

        if (isset($recovery)) {
            $account = $this->ticketing->ci->accounts_model->get_by_id($recovery->id);
            $this->discard_status();

            $this->ticketing->form_enable('password');
            $this->ticketing->form_enable('password_repeat');

            if ($this->form_validation->run() === TRUE) {
                $password = $this->input->post('password');

                $this->ticketing->recover($account->email, $password);
                if ($this->ticketing->succeeded()) {
                    $this->validate_action(TRUE, TRUE, TRUE);
                } else {
                    redirect(site_url('customer_v2/recover/'.$recovery_code).'#recover-page');
                }
            } else {
                $this->ticketing->check_form_errors($this->data);
            }

            $this->data['title'] = 'Perbarui Kata Sandi';
            $this->data['email'] = $account->email;
            $this->data['recovery'] = $recovery_code;
            $this->data['modal_items'] = array(
                array(
                    'key' => 'tips',
                    'header' => 'Petunjuk',
                    'content' => 'Dapatkan kembali akun anda dengan mengubah kata sandi pada akun anda'
                )
            );

            $this->render('templates/page_header');
            $this->render('customer_v2/navbar');
            $this->render('templates/body_header');
            $this->render('customer_v2/recover');
            $this->render('templates/body_footer');
            $this->render('templates/page_footer');
        } else {
            die('Kode recovery tidak valid!');
        }
    }

    public function logout() {
        $this->ticketing->logout();
        redirect(site_url('customer_v2'));
    }

    private function discard_status() {
        $this->data['status'] = $this->ticketing->status();
    }

    private function validate_action($step_2, $step_3, $step_4) {
        if (isset($this->session->e_ticketing_login)) {
            $order_state = $this->ticketing->order_state($this->session->e_ticketing_login);
            if (($step_2 === TRUE) && ($order_state === 'TIDAK_MEMESAN')) {
                redirect(site_url('customer_v2/order').'#order-page');
            } else if (($step_3 === TRUE) && ($order_state === 'MEMESAN')) {
                redirect(site_url('customer_v2/transfer').'#transfer-page');
            } else if (($step_4 === TRUE) && ($order_state === 'MEMBELI')) {
                redirect(site_url('customer_v2/dashboard').'#dashboard-page');
            }
        }
    }

    private function login_check() {
        if (!isset($this->session->e_ticketing_login)) {
            redirect(site_url('customer_v2/auth'));
        }
    }

    private function render($page) {
        $this->ticketing->render($page, $this->data);
    }

}

?>
