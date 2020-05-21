<?php

class Admin extends CI_Controller {

    private $data;

    public function __construct() {
        parent::__construct();

        $this->load->library('ticketing');
        $this->load->library('form_validation');
        $this->load->library('session');

        if (isset($this->session->e_ticketing_auth)) {
            $this->data['auth'] = TRUE;
        }
        $this->data['status'] = $this->ticketing->status();
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        $this->auth_check();

        $categories = array();
        $categories_raw = $this->ticketing->ci->categories_model->get_all();
        foreach ($categories_raw as $category_raw) {
            $category['id'] = $category_raw->id;
            $category['name'] = $category_raw->name;
            $category['price'] = $category_raw->price;
            $category['capacity'] = $category_raw->capacity;
            $category['available'] = $this->ticketing->get_available_tickets($category_raw->id);

            array_push($categories, $category);
        }

        $this->data['title'] = 'Dashboard';
        $this->data['section'] = 'admin/dashboard';
        $this->data['info'] = $this->ticketing->get_info();
        $this->data['categories'] = $categories;

        $this->data['total_capacity'] = 0;
        $this->data['total_available'] = 0;
        $this->data['total_sold'] = 0;
        $this->data['total_revenue'] = 0;
        foreach ($categories as $category) {
            $this->data['total_capacity'] += $category['capacity'];
            $this->data['total_available'] += $category['available'];
            $this->data['total_sold'] += $category['capacity'] - $category['available'];
            $this->data['total_revenue'] += ($category['capacity'] - $category['available']) * $category['price'];
        }

        $this->render_begin();
        $this->render('admin/dashboard');
        $this->render_end();
    }

    public function howto() {
        $this->auth_check();

        $this->data['title'] = 'Panduan';
        $this->data['section'] = 'admin/howto';

        $this->render_begin();
        $this->render('admin/howto');
        $this->render_end();
    }

    public function settings() {
        $this->auth_check();

        $this->ticketing->form_enable('admin_highlight');
        $this->ticketing->form_enable('admin_last_check_timestamp');
        $this->ticketing->form_enable('admin_category_id');
        $this->ticketing->form_enable('admin_capacities');

        if ($this->form_validation->run() === TRUE) {
            $this->ticketing->prevent_flood();

            $timestamp = explode('-', $this->input->post('admin_last_check_timestamp'));
            $year = $timestamp[0];
            $month = $timestamp[1];
            $day = $timestamp[2];

            $info_data['highlight'] = $this->input->post('admin_highlight');
            $info_data['last_check_timestamp'] = mktime(0, 0, 0, $month, $day, $year);
            $info_data['category_id'] = $this->input->post('admin_category_id');

            $this->ticketing->ci->info_model->set($info_data);

            $capacities = json_decode($this->input->post('admin_capacities'));
            foreach ($capacities as $catid => $capacity) {
                $this->ticketing->ci->categories_model->set($catid, array('capacity' => $capacity));
            }

            $this->ticketing->set_status(TRUE, 'Pengaturan berhasil diperbarui');
            redirect(site_url('admin/settings'));
        } else {
            $this->ticketing->check_form_errors($this->data);
        }

        $this->data['title'] = 'Pengaturan';
        $this->data['section'] = 'admin/settings';
        $this->data['info'] = $this->ticketing->get_info();
        $this->data['categories'] = $this->ticketing->ci->categories_model->get_all();

        $capacities = array();
        foreach ($this->data['categories'] as $category) {
            $capacities[$category->id] = (int)$category->capacity;
        }
        $capacities = json_encode($capacities);

        $this->data['capacities'] = $capacities;

        $this->render_begin();
        $this->render('admin/settings');
        $this->render_end();
    }

    public function orders($items_per_page = 30, $page = 1, $view_all = 'no', $filter = '') {
        $this->auth_check();

        if ($items_per_page == 0) { $items_per_page = 1; }
        if ($page == 0) { $page = 1; }

        $info = $this->ticketing->get_info();

        $this->data['view_all'] = ($view_all === 'yes');
        $this->data['title'] = 'Daftar Pemesanan';
        $this->data['section'] = 'admin/orders';
        $this->data['entries_few'] = $this->ticketing->ci->orders_model->get_all_order_details_maxcount(FALSE, $filter);
        $this->data['entries_all'] = $this->ticketing->ci->orders_model->get_all_order_details_maxcount(TRUE, $filter);
        $this->data['pages_few'] = ceil($this->data['entries_few'] / $items_per_page);
        $this->data['pages_all'] = ceil($this->data['entries_all'] / $items_per_page);
        $this->data['pages_few'] = ($this->data['pages_few'] == 0) ? 1 : $this->data['pages_few'];
        $this->data['pages_all'] = ($this->data['pages_all'] == 0) ? 1 : $this->data['pages_all'];
        $this->data['filter'] = $filter;

        $this->data['entries'] = $this->data['view_all'] ? $this->data['entries_all'] : $this->data['entries_few'];
        $this->data['pages'] = $this->data['view_all'] ? $this->data['pages_all'] : $this->data['pages_few'];

        if ($page > $this->data['pages']) {
            $page = $this->data['pages'];
        }

        $this->data['items_per_page'] = $items_per_page;
        $this->data['page'] = $page;
        $this->data['orders'] = $this->ticketing->ci->orders_model->get_all_order_details($items_per_page, $page, $this->data['view_all'], $filter);
        $this->data['categories'] = array();
        $this->data['bill'] = array();
        $this->data['reasons'] = array();

        foreach ($this->data['orders'] as $order) {
            $category = $this->ticketing->ci->categories_model->get($order->category_id);

            $this->data['categories'][$order->id] = $category;
            $this->data['bill'][$order->id] = $order->tickets * $category->price + $order->order_id % 1000;
        }

        for ($i = 1; $i <= $this->ticketing->get_reason(NULL); $i++) {
            array_push($this->data['reasons'],
                array(
                    'id' => $i,
                    'text' => $this->ticketing->get_reason($i)
                )
            );
        }

        $this->render_begin();
        $this->render('admin/orders');
        $this->render_end();
    }

    public function order_accept($account_id, $prev_url) {
        $this->auth_check();

        $account = $this->ticketing->ci->accounts_model->get_by_id($account_id);

        if (isset($account)) {
            $order = $this->ticketing->ci->orders_model->get($account_id);
            if (isset($order)) {
                $dashboard_link = site_url('customer_v2/dashboard').'#tickets';
                $mail_message = 'BERHASIL! Pemesanan anda <b>telah diterima</b> oleh administrator kami. Silakan kunjungi event kami. Gunakan e-tiket di bawah sebagai akses untuk masuk ke dalam lokasi event. Terima kasih atas partisipasi anda.<br><br>Link menuju e-tiket: <a href="'.$dashboard_link.'">'.$dashboard_link.'</a> (silakan masuk terlebih dahulu)';

                if ($this->ticketing->mail_to($account->email, $mail_message)) {
                    $account_data['category_id'] = $order->category_id;
                    $account_data['tickets'] = $order->tickets;
                    $account_data['order_id'] = $order->order_id;
                    $account_data['accepted'] = date('Y-m-d', time());

                    $this->ticketing->ci->accounts_model->set_by_id($account_id, $account_data);
                    $this->ticketing->ci->orders_model->remove($account_id);
                    $this->ticketing->set_status(TRUE, 'Pemesanan oleh akun ('.$account->email.') berhasil diterima');
                } else {
                    $url = site_url('admin/order_accept/'.$account_id.'/'.$prev_url);

                    $this->ticketing->set_status(FALSE, 'Gagal saat mengirimkan notifikasi e-mail ke ('.$account->email.'). <a href="'.$url.'">Coba lagi?</a>');
                }
            } else {
                $this->ticketing->set_status(FALSE, 'Akun ('.$account->email.') tidak sedang memesan');
            }
        } else {
            $this->ticketing->set_status(FALSE, 'Kode akun tidak ditemukan');
        }

        redirect(base64_decode(urldecode($prev_url)));
    }

    public function order_reject($account_id, $reason_id, $prev_url) {
        $this->auth_check();

        if ($reason_id != 0) {
            $account = $this->ticketing->ci->accounts_model->get_by_id($account_id);

            if (isset($account)) {
                if ($this->ticketing->ci->orders_model->account_id_exists($account_id)) {
                    $reason = $this->ticketing->get_reason($reason_id);
                    $order_link = site_url('customer_v2/order');
                    $mail_message = 'Pemesanan anda <b>ditolak</b> oleh administrator kami dengan alasan: <strong>'.$reason.'</strong>. Silakan melakukan pemesanan lagi di: <a href="'.$order_link.'">'.$order_link.'</a> (masuk terlebih dahulu). Data calon pengunjung yang sudah anda masukkan dapat diubah, ditambah, atau dikurangi. Terima kasih atas perhatiannya';

                    if ($this->ticketing->mail_to($account->email, $mail_message)) {
                        $this->ticketing->ci->orders_model->remove($account_id);
                        $this->ticketing->set_status(TRUE, 'Notifikasi berhasil dikirim dan pemesanan berhasil ditolak ('.$account->email.')');
                    } else {
                        $url = site_url('admin/order_reject/'.$account_id.'/'.$reason_id.'/'.$prev_url);

                        $this->ticketing->set_status(FALSE, 'Gagal saat mengirimkan notifikasi e-mail ke ('.$account->email.'). <a href="'.$url.'">Coba lagi?</a>');
                    }
                } else {
                    $this->ticketing->set_status(FALSE, 'Akun ('.$account->email.') tidak sedang memesan');
                }
            } else {
                $this->ticketing->set_status(FALSE, 'Kode akun tidak ditemukan');
            }
        } else {
            $this->ticketing->set_status(FALSE, 'Alasan tidak valid');
        }

        redirect(base64_decode(urldecode($prev_url)));
    }

    public function check_in() {
        $this->auth_check();

        $this->data['title'] = 'Check-in Pengunjung';
        $this->data['section'] = 'admin/check_in';

        $this->ticketing->form_enable('admin_account_id');

        if ($this->form_validation->run() === TRUE) {
            $ticket_code = explode('/', $this->input->post('admin_account_id'));
            $account_id = $ticket_code[0];
            $visitor_id = $ticket_code[1];

            $account = $this->ticketing->ci->accounts_model->get_by_id($account_id);

            if (isset($account)) {
                if ($account->tickets > 0) {
                    if (($visitor_id >= 0) && ($visitor_id < $account->tickets)) {
                        $this->data['account'] = $account;
                        $this->data['visitor_id'] = $visitor_id;
                        $this->data['visitor'] = ($account->visitors !== '') ? json_decode($account->visitors)[$visitor_id] : NULL;
                        $this->data['category'] = $this->ticketing->get_category($account->category_id);
                    } else {
                        $this->ticketing->set_status(FALSE, 'Kode calon pengunjung tidak ditemukan');
                    }
                } else {
                    $this->ticketing->set_status(FALSE, 'Akun tersebut tidak membeli tiket');
                }
            } else {
                $this->ticketing->set_status(FALSE, 'Akun tidak ditemukan');
            }
            if ($this->ticketing->failed()) {
                redirect(site_url('admin/check_in'));
            }
        } else {
            $this->ticketing->check_form_errors($this->data);
        }

        $this->render_begin();
        $this->render('admin/check_in');
        $this->render_end();
    }

    public function check_in_confirm($account_id, $visitor_id) {
        $this->auth_check();

        $account = $this->ticketing->ci->accounts_model->get_by_id($account_id);

        if (isset($account)) {
            if ($account->tickets > 0) {
                if ($account->check_ins < $account->tickets) {
                    $visitors = json_decode($account->visitors);
                    if (!$visitors[$visitor_id]->checkIn) {
                        $visitors[$visitor_id]->checkIn = TRUE;

                        $account_data['check_ins'] = $account->check_ins + 1;
                        $account_data['visitors'] = json_encode($visitors);
                        $this->ticketing->ci->accounts_model->set_by_id($account_id, $account_data);
                        $this->ticketing->set_status(TRUE, 'Check-in untuk (<u>'.$account->email.'</u>) berhasil');
                    } else {
                        $this->ticketing->set_status(FALSE, 'Calon pengunjung tersebut sudah melakukan check-in');
                    }
                } else {
                    $this->ticketing->set_status(FALSE, 'Akun tersebut telah melakukan check-in untuk semua tiket');
                }
            } else {
                $this->ticketing->set_status(FALSE, 'Akun tersebut tidak membeli tiket');
            }
        } else {
            $this->ticketing->set_status(FALSE, 'Akun tidak ditemukan');
        }
        redirect(site_url('admin/check_in'));
    }

    public function auth() {
        if (isset($this->session->e_ticketing_auth)) {
            redirect(site_url('admin/dashboard'));
        }

        $this->ticketing->form_enable('password');

        if ($this->form_validation->run() === TRUE) {
            $this->ticketing->prevent_flood();

            if ($this->ticketing->ci->info_model->get()->admin_password === md5($this->input->post('password'))) {
                $this->session->e_ticketing_auth = TRUE;
                redirect(site_url('admin/dashboard'));
            } else {
                $this->ticketing->set_status(FALSE, 'Sorry lur dudu kui password e');
                redirect(site_url('admin/auth'));
            }
        } else {
            $this->ticketing->check_form_errors($this->data);
        }

        $this->data['title'] = 'Masuk Dulu Boz';
        $this->data['section'] = 'admin/auth';

        $this->render_begin();
        $this->render('templates/spacing');
        $this->render('admin/auth');
        $this->render('templates/spacing');
        $this->render_end();
    }

    public function accounts($items_per_page = 30, $page = 1, $filter = '') {
        $this->auth_check();

        if ($items_per_page == 0) { $items_per_page = 1; }
        if ($page == 0) { $page = 1; }

        $info = $this->ticketing->get_info();

        $this->data['title'] = 'List Akun';
        $this->data['section'] = 'admin/accounts';
        $this->data['entries'] = $this->ticketing->ci->accounts_model->get_all_account_details_maxcount($filter);
        $this->data['pages'] = ceil($this->data['entries'] / $items_per_page);
        $this->data['pages'] = ($this->data['pages'] == 0) ? 1 : $this->data['pages'];
        $this->data['filter'] = $filter;
        $this->data['date'] = !empty($this->input->get('date')) ? $this->input->get('date') : '';

        if ($page > $this->data['pages']) {
            $page = $this->data['pages'];
        }

        $this->data['items_per_page'] = $items_per_page;
        $this->data['page'] = $page;
        $this->data['accounts'] = $this->ticketing->ci->accounts_model->get_all_account_details($items_per_page, $page, $filter, $this->data['date']);
        $this->data['categories'] = array();
        $this->data['bill'] = array();
        $this->data['income'] = 0;

        foreach ($this->data['accounts'] as $account) {
            $category = $this->ticketing->ci->categories_model->get($account->category_id);

            $this->data['categories'][$account->id] = $category;
            $this->data['bill'][$account->id] = $account->tickets * $category->price + $account->order_id % 1000;
            $this->data['income'] += $account->tickets * $category->price;
        }

        $this->render_begin();
        $this->render('admin/accounts');
        $this->render_end();
    }

    public function exit() {
        if (isset($this->session->e_ticketing_auth)) {
            unset($_SESSION['e_ticketing_auth']);
        }
        redirect(site_url('admin/auth'));
    }

    private function auth_check() {
        if (!isset($this->session->e_ticketing_auth)) {
            redirect(site_url('admin/auth'));
        }
    }

    private function render_begin() {
        $this->render('templates/page_header');
        $this->render('admin/navbar_server');
        $this->render('templates/body_header');
    }

    private function render_end() {
        $this->render('templates/body_footer');
        $this->render('templates/page_footer');
    }

    private function render($page) {
        $this->ticketing->render($page, $this->data);
    }

}

?>
