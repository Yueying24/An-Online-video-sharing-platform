<?php
class Register extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index() {
        $this->load->view('templates/header');
        $this->load->view('register');
        $this->load->view('templates/footer');
    }

    //Form validation -> register
    public function register()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|max_length[12]|required|is_unique[Users.username]',
            array('required' => 'The %s is required', 'is_unique'=> 'The %s already exists'  ));

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[Users.email]',
            array('required' => 'The %s is required', 'is_unique'=> 'The %s must be unique.'  ));

        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[15]',
            array('required' => 'The %s is required.'));

        $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'trim|required|matches[password]',
            array('required' => 'The %s is required.', 'matches' => 'The Password Confirmation does not match the Password.'));

        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('templates/header');
            $this->load->view('register');
            $this->load->view('templates/footer');
        }
        else {
            $data = $this->user_model->register();
            $email_code = md5((string)$data->email);
            $email = $data->email;
            $username = $data->username;

            $config = array(
                'protocol' => 'smtp',
                'smtp_host' => 'mailhub.eait.uq.edu.au',
                'smtp_port' => 25,
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE,
                'newline' => '\r\n',
            );

            $this->load->library('email', $config);
            $this->email->initialize($config);

            $this->email->from('noreply@infs3202-6baf442d.uqcloud.net');
            $this->email->to($email);
            $this->email->subject('Please activate your account here');
            $message = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>';
            $message .='<p>Dear '. $username . ',</p>';
            $message .='<p>Thanks for registering on Health Express. Please <strong><a href="'.base_url().'Home/validate_email/'.$email.'/'. $email_code.'">Click here</a></strong> to activate your account.
                    Once you click the link your email will be verified and you are able to login into the Health Express and start your journey.</p>';
            $message .='<p>Thank you!</p><br><p>The Team at Health Express</p></body></html>';

            $this->email->message($message);
            $this->email->send();

            set_cookie('email',$email, time() + 60 * 60 * 24);

            $Msgs['msg'] = 'An email has been sent to you for validation.';
            $this->load->view('templates/header');
            $this->load->view('register', $Msgs);
            $this->load->view('templates/footer');
            
        }
    }

}