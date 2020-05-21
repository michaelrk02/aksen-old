<?php

class Ticketing {

    public $validation_rules;
    public $validation_errors;

    public $ci;

    public function __construct() {
        $this->ci =& get_instance();

        $this->ci->load->model('info_model');
        $this->ci->load->model('contact_persons_model');
        $this->ci->load->model('categories_model');
        $this->ci->load->model('accounts_tmp_model');
        $this->ci->load->model('accounts_model');
        $this->ci->load->model('orders_model');
        $this->ci->load->model('recoveries_model');

        $this->ci->load->config('ticketing');

        $this->ci->load->library('email');
        $this->ci->load->library('session');

        $email_config['mailtype'] = 'html';
        $email_config['charset'] = 'iso-8859-1';
        $email_config['wordwrap'] = TRUE;
        $email_config['crlf'] = "\r\n";
        $email_config['newline'] = "\r\n";
        $email_config['validate'] = TRUE;

        $this->ci->email->initialize($email_config);

        $this->validation_errors = array(
            'required' => '<li>%s harap diisi!</li>',
            'valid_email' => '<li>Format e-mail (%s) tidak valid!</li>',
            'min_length' => '<li>%s terlalu pendek! (minimal %d karakter)</li>',
            'max_length' => '<li>%s terlalu panjang! (maksimal %d karakter)</li>',
            'matches' => '<li>%s tidak cocok dengan %s!</li>',
            'greater_than_equal_to' => '<li>%s tidak boleh kurang dari %d!</li>',
            'less_than_equal_to' => '<li>%s tidak boleh lebih dari %d!</li>',
            'in_list' => '%s tidak valid!'
        );

        $this->validation_rules = array(
            'email' => array(
                'label' => 'e-mail',
                'rules' => 'required|valid_email|max_length[254]',
                'errors' => $this->validation_errors
            ),
            'password' => array(
                'label' => 'kata sandi',
                'rules' => 'required|min_length[8]',
                'errors' => $this->validation_errors
            ),
            'password_repeat' => array(
                'label' => 'kata sandi konfirmasi',
                'rules' => 'required|matches[password]',
                'errors' => $this->validation_errors
            ),
            'captcha' => array(
                'label' => 'captcha',
                'rules' => 'required|matches[valid_captcha]',
                'errors' => $this->validation_errors
            ),
            'valid_captcha' => array(
                'label' => 'gambar',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'tickets' => array(
                'label' => 'jumlah tiket',
                'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[5]',
                'errors' => $this->validation_errors
            ),
            'visitors' => array(
                'label' => 'data pengunjung',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'help_kind' => array(
                'label' => 'jenis bantuan',
                'rules' => 'required|in_list[register-re,recover,recover-re]',
                'errors' => $this->validation_errors
            ),
            'admin_highlight' => array(
                'label' => 'highlight',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'admin_last_check_timestamp' => array(
                'label' => 'waktu cek terakhir',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'admin_category_id' => array(
                'label' => 'ID kategori tiket',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'admin_capacities' => array(
                'label' => 'Kapasitas-kapasitas tiket',
                'rules' => 'required',
                'errors' => $this->validation_errors
            ),
            'admin_account_id' => array(
                'label' => 'ID akun',
                'rules' => 'required',
                'errors' => $this->validation_errors
            )
        );
    }

    public function form_enable($field) {
        $this->ci->form_validation->set_rules($field, $this->validation_rules[$field]['label'], $this->validation_rules[$field]['rules'], $this->validation_errors);
    }

    public function prevent_flood() {
        $ip_address = str_replace('%', '_', urlencode($_SERVER['REMOTE_ADDR']));
        $timestamp = $_SERVER['REQUEST_TIME'];
        $file_path = APPPATH.'accesses/'.$ip_address;
        $suspect = FALSE;

        if (file_exists($file_path)) {
            $data = json_decode(file_get_contents($file_path));
            if (seconds_diff($data->timestamp, $timestamp) < 3) {
                $suspect = TRUE;
            }
        }

        file_put_contents($file_path, json_encode(array('timestamp' => $timestamp)));
        if ($suspect) {
            die('Jika anda melihat pesan ini, mohon untuk meng-refresh setelah beberapa detik. Kami mendeteksi aktivitas mencurigakan dari robot');
        }
    }

    public function mail_to($address, $message_body) {
        if ($this->get_info()->mail_enable == 1) {
            $homepage = site_url();

            $this->ci->email->from($this->ci->config->item('ticketing_email_from_address'), $this->ci->config->item('ticketing_email_from_name'));
            $this->ci->email->to($address);
            $this->ci->email->subject($this->ci->config->item('ticketing_email_subject'));
            $this->ci->email->message(sprintf($this->ci->config->item('ticketing_email_message'), $message_body, $homepage));

            return $this->ci->email->send();
        }

        log_message('info', 'E-mail: '.$address.' ['.$message_body.']');

        return TRUE;
    }

    public function status($keep = FALSE) {
        if ($keep === TRUE) {
            return $this->ci->session->e_ticketing_status;
        }

        $status = $this->ci->session->e_ticketing_status;
        unset($_SESSION['e_ticketing_status']);

        return $status;
    }

    public function set_status($success, $message) {
        if ($this->status(TRUE) === NULL) {
            $status['success'] = $success;
            $status['message'] = $message;

            $this->ci->session->e_ticketing_status = $status;
        }
    }

    public function succeeded() {
        return ($this->status(TRUE)) && ($this->status(TRUE)['success'] === TRUE);
    }

    public function failed() {
        return ($this->status(TRUE)) && ($this->status(TRUE)['success'] === FALSE);
    }

    public function system_error($message) {
        return 'Terjadi kesalahan sistem ('.$message.'). Mohon untuk meng-screencapture dan menghubungi administrator jika anda melihat tulisan ini';
    }

    public function check_form_errors(&$data) {
        if (validation_errors() !== '') {
            $this->set_status(FALSE, str_replace("\n", '', '<ol type="1">'.validation_errors().'<ol>'));

            if (!isset($data['status'])) {
                $data['status'] = $this->status();
            }
        }
    }

    public function get_reason($reason_id) {
        if (isset($reason_id)) {
            switch ($reason_id) {
            case 1: return 'belum melakukan pembayaran';
            case 2: return 'nominal transfer tidak sesuai dengan yang ditentukan';
            case 3: return 'bukan merupakan alumni SMAN 3 Surakarta';
            case 4: return 'terjadi suatu kekeliruan. Mohon hubungi CP yang tersedia untuk mengklarifikasi';
            }

            return $this->system_error('reason ID tidak diketahui');
        }

        return 4;
    }

    /* administrative functions */

    public function get_info() {
        return $this->ci->info_model->get();
    }

    public function get_category($id = NULL) {
        $category_id = isset($id) ? $id : $this->ci->info_model->get()->category_id;

        return $this->ci->categories_model->get($category_id);
    }

    public function get_tickets_capacity($category_id = NULL) {
        if (!isset($category_id)) {
            $category = $this->get_category();
        } else {
            $category = $this->ci->categories_model->get($category_id);
        }

        return $category->capacity;
    }

    public function get_available_tickets($category_id = NULL) {
        if (!isset($category_id)) {
            $category_id = $this->ci->info_model->get()->category_id;
        }

        $capacity = $this->get_tickets_capacity($category_id);
        $sum_tickets = $this->ci->accounts_model->sum_tickets($category_id);
        $available = $capacity - $sum_tickets;

        return /*($available < 0) ? 0 :*/ $available;
    }

    /* customer functions */

    public function check_account_id($id, $invert = FALSE) {
        $exists = $this->ci->accounts_model->id_exists($id);

        if (!$invert) {
            if ($exists) {
                return TRUE;
            } else {
                $this->set_status(FALSE, 'ID akun <code>'.$id.'</code> tidak terdaftar!');
            }
        } else {
            if (!$exists) {
                return TRUE;
            } else {
                $this->set_status(FALSE, 'ID akun <code>'.$id.'</code> sudah terdaftar sebelumnya!');
            }
        }

        return FALSE;
    }

    public function check_account_email($email, $invert = FALSE) {
        $exists = $this->ci->accounts_model->email_exists($email);

        if (!$invert) {
            if ($exists) {
                return TRUE;
            } else {
                $this->set_status(FALSE, 'E-mail <u>'.$email.'</u> tidak terdaftar!');
            }
        } else {
            if (!$exists) {
                return TRUE;
            } else {
                $this->set_status(FALSE, 'E-mail <u>'.$email.'</u> sudah terdaftar sebelumnya!');
            }
        }

        return FALSE;
    }

    public function activation_message($registration_code) {
        $activation_link = site_url('customer_v2/activate/'.$registration_code);
        $mail_message = 'Akun anda telah didaftarkan tetapi <b>belum aktif</b>. Untuk mengaktifkan akun anda, klik pada link berikut: <a href="'.$activation_link.'">'.$activation_link.'</a>.<br><br><b>PERINGATAN: Jika ini bukan dari anda, mohon abaikan saja. Terima kasih</b>';

        return $mail_message;
    }

    public function recovery_message($recovery_code) {
        $recovery_link = site_url('customer_v2/recover/'.$recovery_code);
        $mail_message = 'Klik link berikut: <a href="'.$recovery_link.'">'.$recovery_link.'</a> untuk memperbarui kata sandi pada akun anda.<br><br><b>PERINGATAN: Jika ini bukan dari anda, mohon abaikan saja. Terima kasih</b>';

        return $mail_message;
    }

    public function register($email, $password) {
        if (!$this->ci->accounts_tmp_model->email_exists($email)) {
            if ($this->check_account_email($email, TRUE)) {
                do { $registration_code = random_id(10); } while ($this->ci->accounts_tmp_model->registration_code_exists($registration_code));
                $mail_message = $this->activation_message($registration_code);

                if ($this->mail_to($email, $mail_message)) {
                    $this->ci->accounts_tmp_model->add($registration_code, $email, md5($password));
                    $this->set_status(TRUE, 'Akun anda telah didaftarkan tetapi <b>belum aktif</b>. Untuk mengaktifkan akun anda, klik pada link yang telah kami kirim pada e-mail anda. <a href="'.site_url('customer_v2/help/register-re').'">Perlu mengirim ulang?</a>');
                } else {
                    $this->set_status(FALSE, 'Gagal mengirimkan e-mail. Mohon untuk coba lagi');
                }
            }
        } else {
            $this->set_status(FALSE, 'E-mail anda sudah digunakan untuk melakukan pendaftaran sebelumnya. Mohon klik pada link registrasi yang telah kami kirimkan ke e-mail anda. <a href="'.site_url('customer_v2/help/register-re').'">Perlu mengirim ulang?</a>');
        }
    }

    public function activate($registration_code) {
        $account_tmp = $this->ci->accounts_tmp_model->get_by_registration_code($registration_code);

        if (isset($account_tmp)) {
            if (!$this->ci->accounts_model->email_exists($account_tmp->email)) {
                $this->ci->accounts_tmp_model->remove_by_registration_code($registration_code);
                $this->ci->accounts_model->add($account_tmp->email, $account_tmp->password);

                $account = $this->ci->accounts_model->get_by_email($account_tmp->email);
                $this->ci->session->e_ticketing_login = $account->id;

                $this->set_status(TRUE, 'Akun anda telah aktif. Silakan untuk melanjutkan ke prosedur berikutnya');
            } else {
                $this->system_error('e-mail telah aktif');
            }
        } else {
            $this->set_status(FALSE, 'Kode registrasi tidak valid! Mohon hubungi administrator jika ini merupakan kesalahan');
        }
    }

    public function recover($email, $password = NULL) {
        $account = $this->ci->accounts_model->get_by_email($email);

        if (isset($account)) {
            if (isset($password)) {
                $account_data['password'] = md5($password);

                $this->ci->accounts_model->set_by_id($account->id, $account_data);
                $this->ci->recoveries_model->remove_by_account_id($account->id);
                $this->ci->session->e_ticketing_login = $account->id;
                $this->set_status(TRUE, 'Kata sandi berhasil diganti! Anda telah dimasukkan secara otomatis');
            } else {
                if (!$this->ci->recoveries_model->account_id_exists($account->id)) {
                    do { $recovery_code = random_id(10); } while ($this->ci->recoveries_model->recovery_code_exists($recovery_code));
                    $mail_message = $this->recovery_message($recovery_code);

                    $this->ci->recoveries_model->add($account->id, $recovery_code);
                    if ($this->mail_to($email, $mail_message)) {
                        $this->set_status(TRUE, 'Kami telah mengirimkan link pembaruan akun kepada e-mail anda. Silakan untuk mengecek pada inbox e-mail anda');
                    } else {
                        $this->set_status(FALSE, 'Gagal mengirimkan e-mail. Mohon coba lagi. <a href="'.site_url('customer_v2/help/recover-re').'">Perlu mengirim ulang e-mail?</a>');
                    }
                } else {
                    $this->set_status(FALSE, 'E-mail tersebut tidak melakukan pembaruan password');
                }
            }
        } else {
            $this->set_status(FALSE, 'E-mail tersebut tidak terdaftar');
        }
    }

    public function send_help($email, $help_kind) {
        if ($help_kind === 'register-re') {
            if (!$this->ci->accounts_model->email_exists($email)) {
                $account_tmp = $this->ci->accounts_tmp_model->get_by_email($email);

                if (isset($account_tmp)) {
                    $mail_message = $this->activation_message($account_tmp->registration_code);

                    if ($this->mail_to($email, $mail_message)) {
                        $this->set_status(TRUE, 'Link aktivasi berhasil dikirim ulang melalui e-mail');
                    } else {
                        $this->set_status(FALSE, 'Gagal mengirimkan e-mail. Mohon coba lagi');
                    }
                } else {
                    $this->set_status(FALSE, 'E-mail tersebut tidak melakukan pendaftaran');
                }
            } else {
                $this->set_status(FALSE, 'E-mail tersebut sudah terdaftar. Silakan masuk menggunakan e-mail tersebut');
            }
        } elseif ($help_kind === 'recover') {
            $this->recover($email);
        } elseif ($help_kind === 'recover-re') {
            $account = $this->ci->accounts_model->get_by_email($email);

            if (isset($account)) {
                $recovery = $this->ci->recoveries_model->get_by_account_id($account->id);

                if (isset($recovery)) {
                    $mail_message = $this->recovery_message($recovery->recovery_code);

                    if ($this->mail_to($email, $mail_message)) {
                        $this->set_status(TRUE, 'Link pemulihan berhasil dikirim ulang melalui e-mail');
                    } else {
                        $this->set_status(FALSE, 'Gagal mengirimkan e-mail. Mohon coba lagi');
                    }
                } else {
                    $this->set_status(FALSE, 'E-mail tersebut tidak melakukan pemulihan');
                }
            } else {
                $this->set_status(FALSE, 'E-mail tersebut tidak terdaftar!');
            }
        } else {
            $this->set_status(FALSE, 'Jenis bantuan tidak valid! Mohon diperiksa kembali');
        }
    }

    public function login($email, $password) {
        $account = $this->ci->accounts_model->get_by_email($email);

        if (isset($account)) {
            if (md5($password) === $account->password) {
                if ($this->ci->recoveries_model->account_id_exists($account->id)) {
                    $this->ci->recoveries_model->remove_by_account_id($account->id);
                }

                $this->ci->session->e_ticketing_login = $account->id;
                $this->set_status(TRUE, 'Selamat datang di sistem pemesanan tiket kami');
            } else {
                $this->set_status(FALSE, 'Kata sandi yang anda masukkan salah. Mohon coba lagi');
            }
        } else {
            $this->set_status(FALSE, 'Akun tidak ditemukan. Silakan <a href="'.site_url('customer_v2/auth/sign_up').'#auth-page'.'">registrasi</a> atau klik pada link verifikasi di e-mail jika sudah');
        }
    }

    public function logout() {
        if (isset($this->ci->session->e_ticketing_login)) {
            unset($_SESSION['e_ticketing_login']);
            $this->set_status(TRUE, 'Anda telah keluar dari sistem kami');
        }
    }

    public function get_tickets_cost($account_id) {
        if ($this->order_state($account_id) === 'MEMESAN') {
            $order = $this->ci->orders_model->get($account_id);
            $category = $this->ci->categories_model->get($order->category_id);

            return $order->tickets * $category->price + ($order->order_id % 1000);
        }

        return 0;
    }

    public function order_state($account_id) {
        if ($this->ci->orders_model->account_id_exists($account_id)) {
            return 'MEMESAN';
        } else {
            $account = $this->ci->accounts_model->get_by_id($account_id);

            if ($account->tickets > 0) {
                return 'MEMBELI';
            } else {
                return 'TIDAK_MEMESAN';
            }
        }

        return 'TIDAK_DIKETAHUI';
    }

    public function order_state_details($order_state) {
        switch ($order_state) {
        case 'TIDAK_MEMESAN': return 'silakan pesan tiket anda';
        case 'MEMESAN': return 'segera melakukan pembayaran ke nomor rekening yang telah ditentukan';
        case 'MEMBELI': return 'tiket sudah berada di tangan anda. Silakan nikmati event kami';
        }

        return $this->system_error('(order state tidak diketahui)');
    }

    public function order($account_id, $tickets, $visitors, $alumni) {
        $state = $this->order_state($account_id);

        if ($state === 'TIDAK_MEMESAN') {
            if ($tickets > 0) {
                $info = $this->ci->info_model->get();
                $catid = $alumni ? $info->alumni_id : $info->category_id;

                if ($tickets <= $this->get_available_tickets($catid)) {
                    $account_data['last_order'] = time();
                    $account_data['visitors'] = $visitors;

                    $this->ci->orders_model->add($account_id, $info->order_id_next, $catid, $tickets);
                    $this->ci->accounts_model->set_by_id($account_id, $account_data);
                    $this->ci->info_model->set(array('order_id_next' => $info->order_id_next + 1));
                } else {
                    $this->set_status(FALSE, 'Tiket yang dipesan melebihi sisa. Mohon untuk memeriksa kembali detail order anda');
                }
            } else {
                $this->set_status(FALSE, 'Apakah anda yakin untuk tidak membeli tiket?');
            }
            $info = $this->get_info();
            $this->set_status(TRUE, 'Anda telah memesan sebanyak <b>'.$tickets.'</b> tiket. Segera lakukan pembayaran ke rekening yang telah ditentukan sebesar <b>'.rupiah($this->get_tickets_cost($account_id)).'</b>');
        } elseif ($state === 'MEMESAN') {
            $order = $this->ci->orders_model->get($account_id);
            $difference = $tickets - $order->tickets;

            if ($difference != 0) {
                if ($difference <= $this->get_available_tickets()) {
                    $this->ci->orders_model->set($account_id, array('tickets' => $tickets));
                    if ($tickets == 0) {
                        $this->ci->orders_model->remove($account_id);
                        $this->set_status(TRUE, 'Anda telah membatalkan pemesanan tiket anda');
                    }
                } else {
                    $this->set_status(FALSE, 'Tiket yang dipesan melebihi sisa. Mohon untuk memeriksa kembali detail pemesanan anda');
                }
                $this->set_status(TRUE, 'Anda telah mengganti detail pemesanan anda dari '.$order->tickets.' menjadi <b>'.$tickets.'</b> tiket');
            }
        } elseif ($state === 'MEMBELI') {
            $this->set_status(TRUE, 'Pembayaran terakhir anda <b>diterima</b> oleh administrator. Dengan demikian, anda tidak dapat memesan tiket lagi');
        } else {
            $this->set_status(FALSE, $this->system_error('order state tidak diketahui saat memesan'));
        }
    }

    /* view functions */

    public function render($page, &$data) {
        if (!file_exists(APPPATH.'views/'.$page.'.php')) {
            show_404();
        }

        $this->ci->load->view($page, $data);
    }

}

?>
