<?php
class Login extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

	public function index() {

        $this->load->view('templates/header');
        $config = array(
            'img_path'      => 'captcha/',
            'img_url'       => base_url().'captcha/',
            'font_path'     => 'system/fonts/texb.ttf',
            'img_width'     => '160',
            'img_height'    => 50,
            'word_length'   => 5,
            'font_size'     => 18,
            'pool'   => '0123456789',
        );
        $captcha = create_captcha($config);
        // set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode', $captcha['word']);
        $data['captchaImg'] = $captcha['image'];
        $this->load->view('login', $data);
        $this->load->view('templates/footer');
    
    }

    public function captcha(){
        // Captcha configuration
        $config = array(
            'img_path'      => 'captcha/',
            'img_url'       => base_url().'captcha/',
            'font_path'     => 'system/fonts/texb.ttf',
            'img_width'     => '160',
            'img_height'    => 50,
            'word_length'   => 5,
            'font_size'     => 18,
            'pool'   => '0123456789',
        );
        $captcha = create_captcha($config);
        
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        $data['captchaImg'] = $captcha['image'];
        // Display captcha image
        return $data['captchaImg'];
    }

    public function verify(){
        if (!$this->session->userdata('logged_in')) {
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $remember = $this->input->post("remember");
            $inputCaptcha = $this->input->post('captcha');
    
            //validate username
            if ($this->user_model->user_exist($username)) {

                //validate password & captcha
                $sessCaptcha = $this->session->userdata('captchaCode');
                 
                if ($this->user_model->login($username, $password)) {
                    if($inputCaptcha == $sessCaptcha){
                        //Create session
                        $user_data = array(
                            "username" => $username,
                            "logged_in" => true,
                        );
                        $this->session->set_userdata($user_data);

                        //Set cookie to show 'remember me'.
                        if($remember){
                            set_cookie('username',$username, time() + 60 * 60 * 24);
                            set_cookie('password',$password, time() + 60 * 60 * 24);
                        }
                        else{
                            delete_cookie('username');
                            delete_cookie('password');
                        }
                        $this->load->view('templates/header');
                        $this->load->view('homepage');
                        $this->load->view('templates/footer');
                    }
                    else{
                        $data['captcha'] = 'Captcha code does not match, please try again.';
                        $data['captchaImg']=$this->captcha();
                        $this->load->view('templates/header');
                        $this->load->view('login', $data);
                    }
                }
                else{
                    $data['error'] = "The password is incorrect.";
                    $data['captchaImg']=$this->captcha();
                    $this->load->view('templates/header');
                    $this->load->view('login', $data);
                }
            }
            else{
                //username and password do not exist -> register
                $data['title'] = "The account does not exist.";
                $data['captchaImg']=$this->captcha();
                $this->load->view('templates/header');
                $this->load->view('login', $data);
            }
        }
        else{
            $this->load->view('templates/header');
            $this->load->view('homepage');
            $this->load->view('templates/footer');
            }
    }

    public function refresh(){
        // Captcha configuration
        $config = array(
            'img_path'      => 'captcha/',
            'img_url'       => base_url().'captcha/',
            'font_path'     => 'system/fonts/texb.ttf',
            'img_width'     => '160',
            'img_height'    => 50,
            'word_length'   => 5,
            'font_size'     => 18,
            'pool'   => '0123456789',
        );
        $captcha = create_captcha($config);
        
        // Unset previous captcha and set new captcha word
        $this->session->unset_userdata('captchaCode');
        $this->session->set_userdata('captchaCode',$captcha['word']);
        
        // Display captcha image
        echo $captcha['image'];
    }


    public function logout()
    {
        // unset session when logout
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('username');

        redirect('Home');
    }
}