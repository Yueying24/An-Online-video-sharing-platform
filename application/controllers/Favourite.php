<?php
class Favourite extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function voting($id, $name){

        $username = $this->session->userdata('username');

        //If the user did not like this video,after click 'liked', insert its info into database
        if(!$this->user_model->liked_record($username, $id)){

            $this->user_model ->insert_liked($id, $name);
        }
        redirect('Home/videos/'.$id);
    }

    public function remove($id){
        $this->user_model->remove($id);
        redirect('Home/profile/');
    }

    public function add_to_list($sports_id, $title, $name){
        
        $this->user_model->playlist($sports_id, $title, $name);
        redirect('Home/videos/'.$sports_id);
    }
      
}
