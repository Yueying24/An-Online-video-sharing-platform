<?php
class Home extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

	public function index() {

        $this->load->view('templates/header');
        $this->load->view('homepage');
        $this->load->view('templates/footer');
    
    }

    public function validate_email($email, $email_code){
        if($this->user_model->validate_email($email, $email_code)){
            $data['Msg'] = '<h1 style=\"margin: 40px auto; text-align:center\">Your email has been activated! You can login to the site from <a href="' . site_url('Login'). '">here</a>.</h1>';
        }
        else{
            $data['Msg'] = '<h1>Invalid link.</h1>';
        }
        $this->load->view('templates/header');
        $this->load->view('email_validation', $data);
        
    }

    public function reset_password_form(){

        $this->load->view('templates/header');
        $this->load->view('reset_password');
    }

    public function reset_password(){
        if(!empty($this->input->post('email'))){
            $this->form_validation-> set_rules('email','Email', 'trim|required|valid_email',
            array('required' => 'The %s is required'  ));

            if($this->form_validation->run() == False){
                $data['Msg'] = "Please enter a valid email.";
                $this->load->view('templates/header');
                $this->load->view('reset_password', $data);

            }
            else{
                $email = trim($this->input->post('email'));
                $check = $this->user_model->email_exist($email);
                if($check){ // return username
                    $this->reset_password_email($email, $check);
                    $data['Msg'] = "An email has been sent to you to reset password.";
                    $this->load->view('templates/header');
                    $this->load->view('reset_password', $data);
                }
                else{
                    $this->load->view('templates/header');
                    $this->load->view('reset_password', array('Msg'=> 'Email address not registered with Health Express, please register first.'));
                }
            }
        }
        else{
            $this->load->view('templates/header');
            $this->load->view('reset_password');
        }
               
    }

    public function reset_password_email($email, $username){

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
        $email_code = md5((string)$email);

        $this->email->from('noreply@infs3202-6baf442d.uqcloud.net');
        $this->email->to($email);
        $this->email->subject('Please reset your password');
        $message = '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"></head><body>';
        $message .='<p>Dear '. $username . ',</p>';
        $message .='<p>We would like to help you reset password. Please <strong><a href="'.base_url().'Home/reset_psw_email_sent/'.$email.'/'. $email_code.'">Click here</a></strong> to reset your password.';
        $message .='<p>Thank you!</p><br><p>The Team at Health Express</p></body></html>';

        $this->email->message($message);
        $this->email->send();
    }


    public function reset_psw_email_sent($email, $email_code){

        if(isset($email, $email_code)){
            $email_hash = sha1($email. $email_code);
        
            if($this->user_model->verify_reset_psw_email($email, $email_code)){
                $this->load->view('templates/header');
                $this->load->view('update_password', array('email'=>$email, 'email_code'=>$email_code));
                
            }
        else{
            $data['Msg'] = '<h4>There was a problem with your link, please click it again or request to reset password again.</h4>';
            $this->load->view('templates/header');
            $this->load->view('reset_password', $data);
            }
        }
        
    }

    public function update_password(){
    
            $this->form_validation-> set_rules('email','Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[15]',
                array('required' => 'The %s is required.'));

            $this->form_validation->set_rules('password_repeat', 'Password Confirmation', 'trim|required|matches[password]',
                array('required' => 'The %s is required.', 'matches' => 'The Password Confirmation does not match the Password.'));
            if($this->form_validation->run() == False){     
                $this->load->view('templates/header');
                $this->load->view('update_password');
            }
            else{
                $update= $this->user_model-> update_password();
                if($update){
                    $this->load->view('templates/header');
                    $this->load->view('update_password_success');
            }
            
        }
    }


    public function workout(){
        $data['all_videos'] = $this -> user_model -> get_all_videos();
        $this->load->view('templates/header');
        $this->load->view('workout', $data);
        $this->load->view('templates/footer');
    }


    public function videos($id, $new_name= ""){
        //Retrieve all comments of this video
        $data['comments'] = $this->user_model->get_comments($id); 
        //Retrieve a specific video info on video page
        $data['video_info'] = $this ->user_model-> video($id);
        $username = $this->session->userdata('username');
        //Retrieve a liked/disliked video numbers
        $data['voting'] = $this->user_model->get_one_liked($username, $id);
        $data['like_count'] = $this->user_model->like_count($id);
        $data['dis_count'] = $this->user_model->dis_count($id);
        $data['name_set'] = $new_name;
        $this->load->view('templates/header');
        $this->load->view('videos', $data);
        $this->load->view('templates/footer');
    }


    public function profile(){
        //Only available when user logged in
        if ($this->session->userdata('logged_in')){
            $username = $this->session->userdata('username');
            $data['image'] = $this->user_model->get_avatar($username);
            $data['info'] = $this->user_model->get_info($username); 
            //get all favourite videos' info of a user
            $data['playlist'] = $this->user_model->get_playlist($username);
            //get all uploaded videos' info of a user
            $data['videos'] = $this->user_model->get_videos($username);
            //get all comments records of a user
            $id=
            $data['my_comments'] = $this->user_model->get_my_comments();
            $this->load->view('templates/header');
            $this->load->view('profile', $data);
            $this->load->view('templates/footer');
        }
        else{
            print "<script type=\"text/javascript\">alert('You should login first.');</script>";
            $this->load->view('templates/header');
            $this->load->view('login');
            $this->load->view('templates/footer');
        }
    }

    //Update a user's basic info on profile page
    public function update_info()
    {
        $username = $this->session->userdata('username');

        $this->user_model->update_info($username);

        delete_cookie('email');

        redirect("Home/profile");

    }

    //Fetch a video info and autocompletion
    public function video_fetch(){
        $keyword = $this->input->get('search');  // value
        $relative_videos = $this->user_model->fetch_videos($keyword);
        
        echo json_encode($relative_videos);
    }

    //Search a video and display it by using ajax
    public function video_search()
    {
        $keyword = $this->input->get('keyword');  // value empty yes no
        $relative_videos = $this->user_model->search_videos($keyword);  

        if (!empty($keyword)) {
            if(!empty($relative_videos)){
                $result = "<div>";
                foreach ($relative_videos as $videos) {
                    $result = $result.'<a href="'. base_url().'Home/videos/'.$videos['sports_id'].'">';
                    $result = $result.'<video width="300" height="250" controls style="margin: 0 10px">'.'<source src="' .base_url(). 'videos/'. $videos['name']. '"type="video/mp4" >'."</video>";
                        $result = $result."<h4>". $videos['title']. "</h4>". "</a>";
                    
                }
                $result = $result."</div>";
                echo $result;
            }else{
                $result = "No videos found.";
                echo $result;
            }
        }
        else{
            $all_videos = $this -> user_model -> get_all_videos();
            $result = "<div class='main'>";
            foreach ($all_videos as $video){
            $result = $result.'<a href="'.site_url('Home/videos/'.$video['sports_id']).'">';
            $result = $result.'<video width="300" height="250" controls style="margin: 0 10px">'.'<source src="'.base_url('videos/'.$video['name']).'" type="video/mp4" >'."</video>";
            $result = $result. '<h4>' .$video['title']. "</h4>"."</a>";
            }
            $result = $result."</div>";
            echo $result;

        }    
            
    }
   
}
    




