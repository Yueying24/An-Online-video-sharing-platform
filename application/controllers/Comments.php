<?php  class Comments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index(){

        $this->load->view('templates/header');
        $this->load->view('videos', array('error' => ' '));
        $this->load->view('templates/footer');
    }


    public function add_comments($name, $sports_id){

        $this->form_validation->set_rules('comments', 'Comment', 'required');
        $new_name = $this->input->post("name_set");  //0 or 1
        if ($this->form_validation->run() == FALSE){
            redirect('Home/videos/'.$sports_id.'/'.$new_name);
        }
        else{
            $this->user_model->comments_insert($name, $sports_id, $new_name);
            $data["comments"] = $this->user_model->get_comments($sports_id);
            $data["name_set"] = $new_name;
            redirect('Home/videos/'.$sports_id.'/'.$new_name);
        }
    }
}
