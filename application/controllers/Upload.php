<?php

class Upload extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index(){

        $this->load->view('templates/header');
        $this->load->view('workout', array('error' => ' '));
        $this->load->view('templates/footer');
    }

    //Upload multiple videos on workout page
    public function videos_upload()
    {
        $config['upload_path'] = './videos/';
        $config['allowed_types'] = 'mp4|mtv|amv|rm|wmv';
        $config['max_size'] = 2048 * 2048 * 100;
        //The user can only upload videos when logged in.
        if ($this->session->userdata('logged_in')) {
            if (!empty($_FILES['videos']['name'] && count(array_filter($_FILES['videos']['name'])) > 0)) {
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                $count = count($_FILES['videos']['name']);
                for ($i = 0; $i < $count; $i++) {
                    $_FILES['file']['name'] = $_FILES['videos']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['videos']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['videos']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['videos']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['videos']['size'][$i];
                    //upload videos
                    if (!$this->upload->do_upload('file')) {
                        $error = array('error' => $this->upload->display_errors('<p style="color: red; margin: 0 auto;">
                                Failed to upload file.', '</p>'));
                        $this->load->view('templates/header');
                        $this->load->view('workout', $error);
                        $this->load->view('templates/footer');
                    }
                    else {
                        $data = $this->upload->data();
                        $uploaded[$i]['username'] = $this->session->userdata('username');
                        $uploaded[$i]['name'] = $data['file_name'];
                        $uploaded[$i]['date'] = date('Y-m-d H:i:s');
                        $uploaded[$i]['title'] = $this->input->post('title');

                        if (!empty($uploaded)) {
                            //insert sports videos info into database
                            $insert = $this->user_model->insert_videos($uploaded);
                            $Msg = $insert ? 'Your videos was successfully uploaded!' : 'Some problem occurred, please try again.';
                        } else {
                            $Msg = 'Sorry, you must choose at least one file.';
                        }
                    }
                }
            }
            else{
                    $Msg = 'Please choose your file. ';
            }
            //Retrieve all videos' info and display videos on workout page
            $data['all_videos'] = $this -> user_model -> get_all_videos();
            $data['Msg'] = $Msg;
            $this->load->view('templates/header');
            $this->load->view('workout', $data);
            $this->load->view('templates/footer');
        }
        else{
            // Alert the user to login if not.
            echo "<script type=\"text/javascript\">alert('You should login first.');</script>";
            $data['all_videos'] = $this -> user_model -> get_all_videos();
            $this->load->view('templates/header');
            $this->load->view('workout', $data);
            $this->load->view('templates/footer');
        }
    }

    //Upload a avatar for the user
    public function avatar_upload(){
        $config['upload_path'] = './avatar/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 1024*1024*200;

        $this->load->library('upload', $config);
        //upload/change avatar
        if (!$this->upload->do_upload('avatar')) {
            $error = array('error' => $this->upload->display_errors('<p style="color:white;">', '</p>'));
            $this->load->view('templates/header');
            $this->load->view('profile', $error);
            $this->load->view('templates/footer');
        }
        else{
            $filename = $this->upload->data('file_name');
            $username = $this->session->userdata('username');
            $this->user_model->insert_avatar($username, $filename);
            redirect('Home/profile');

        }

    }


}
