<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class user_model extends CI_Model{

    public function __construct() {
        $this->load->database();
    }

    //register-> insert user's info into database and send validation email
    public function register(){
        
            $data = array(
                'username' => $this->input->post('username'),
                'email'=> $this->input->post('email'),
                'password'=>$this->encrypt->encode($this->input->post('password')),
                'anonymous' => hash("adler32", $this->input->post('username'), TRUE),
            );
            $this->db-> insert("Users", $data);
            $query = $this->db->get_where('Users',array('username' =>$data['username'],'email'=>$data['email'] ));
            if($query->num_rows() == 1){
                return $query -> row();
            }
            else{
                return false;
            }
       
    }

    public function validate_email($email, $email_code){

        $result = $this->db->get_where('Users', array('email' => $email));
        $query = $result->row()->email;
        $verified = $result->row()->verified;
        if($verified == 0){
            if(md5((string)$query) == $email_code){
                $data = array(
                    'verified'=> '1',
                );
                $this->db->where('email',$email);
                $this->db-> update("Users", $data );
                return true;
            }
            else{
                echo 'Error when validate your email, please contact '.$this->config->item('basic_email');
            }
        }
        else{
            return false;
        }
    }
            

    public function verify_reset_psw_email($email, $email_code){

        $result = $this->db->get_where('Users', array('email' => $email));
        $query = $result->row()->email;
        if(md5((string)$query) == $email_code){
            return true;
        }
        else{
            return false;
        }
    }
        

    
    // Log in validation, validate username and password
    public function login($username,$password){
        
        $this->db->select('password');
        $this->db->from('Users');
        $this->db->where('username',$username);
        $query = $this->db->get()->row()->password;
        $decode_p= $this->encrypt->decode($query);

        if($password == $decode_p){
            return true;
        } else {
            return false;
        }

    }

    // Check username exists
    public function user_exist($username){
        $query = $this->db->get_where('Users', array('username' => $username));
        if(!empty($query->row_array())){
            return true;
        } else {
            return false;
        }
    }

    public function email_exist($email){
        $query = $this->db->get_where('Users', array('email' => $email));
        if (!empty($query)){
            $result = $query->row()->username;
            return $result;
        }
        else{
            return false;
        }
    }

    public function update_password(){
        $email = $this->input->post('email');
        $data= array(
            'password'=> $this->encrypt->encode($this->input->post('password'))
        );
        $this->db->where('email', $email);
        if(!empty($data)){
            $this->db-> update("Users", $data);
            return true;
        }
        else{
            return false;
        } 
    }

    // Insert avatars
    public function insert_avatar($username, $filename){
            $data = array(
                'username'=> $username,
                'avatar'=> $filename,
            );
            $this->db->where('username',$username);
            $this->db-> update("Users", $data );
    }

     //Get avatar's info from database
     public function get_avatar($username){
        $this->db->select('avatar');
        $this->db->from('Users');
        $this->db->where('username',$username);
        $query = $this->db->get()->row()->avatar;
        if(!empty($query)){
            return $query;
        }
    }

    //Update user's info in database
    public function update_info($username){
        $data = array(
            'email' => $this->input->post("email"),
            'birth' => $this->input->post("birth"),
            'male' => ($this->input->post("male") ===NULL)? 0:1,
            'female' => ($this->input->post("female") ===NULL)? 0:1,
        );
        $this->db->where('username', $username);
        if(!empty($data)){
            $this->db-> update("Users", $data ); 
        }
    }

    // Get user's info and show it on profile page
    public function get_info($username = ''){
        if (!empty($username)){
            $query = $this->db->get_where('Users',array('username' =>$username ));
        if(!empty ($query)){
            return $query->row();  }
        }
    }

    //Retrieve uploaded videos' info from database
    public function get_videos($username){
        $query = $this->db->get_where('sports',array('username' =>$username ));
        if(!empty ($query)){
            return $query->result_array();
        }
    }

    //Insert uploaded videos' info into database
    public function insert_videos($data = array()){
        $insert = $this->db->insert_batch('sports', $data);
        return $insert?true:false;
    }

    //Insert uploaded diet images into database
    public function insert_diet($data = array()){
        $insert = $this->db->insert('diets', $data);
        return $insert?true:false;
    }

    //Retrieve uploaded images' info
    public function get_diets(){
        $this->db->select('*');
        $this->db->from('diets');

        $query = $this->db->get()->result_array();

        return !empty($query)?$query:false;
    }

    // Insert a comment info into database
    public function comments_insert($name,$sports_id, $new_name){
        $data=array();
        if($new_name == 0){
            $data = array(
                'username'=>$this->session->userdata('username'),
                'sports_id' => $sports_id,
                'content' => $this->input->post('comments'),
                'date' => date('Y-m-d H:i:s'),
                'name' => $name,
                "user_id" =>$this->get_info($this->session->userdata('username'))->id
            );
        }
        else{
            $data = array(
                'username'=> $this->get_info($this->session->userdata('username'))->Anonymous,
                'sports_id' => $sports_id,
                'content' => $this->input->post('comments'),
                'date' => date('Y-m-d H:i:s'),
                'name' => $name,
                "user_id" =>$this->get_info($this->session->userdata('username'))->id
            );

        }
        $this->db-> insert("comments", $data);
    }

    //Get all comments for a video
    public function get_comments($id){
        $query = $this->db->get_where('comments',array('sports_id' =>$id ))->result_array();
        if(isset($query)){
            return $query;
        }
        else{
            return false;
        }
    }

    //Retrieve all comments of a user
    public function get_my_comments(){
        $id = $this->get_info($this->session->userdata('username'))->id;
        $query = $this->db->get_where('comments',array('user_id' =>$id ))->result_array();
        if(!empty ($query)){
            return $query;
        }
    }

    //Get all videos from database
    public function get_all_videos(){
        $this->db->select('*');
        $this->db->from('sports');
        $query = $this->db->get()->result_array(); //  all videos in database
        return $query;
    }

    // Get a specific video info according to sports_id
    public function video($id){
        $query = $this->db->get_where('sports',array('sports_id' =>$id ))->row_array();
        return $query;
    }

    

    //Check if the user has liked the video
    public function liked_record($username, $id){
        $query = $this->db->get_where('voting', array('username' => $username, 'sports_id' => $id, "liked" => 1));
        if(!empty($query->row_array())){
            return true;
        } else {
            return false;
        }
    }

    // Insert favourite videos' info into database
    public function insert_liked($id,$name){

        $data = array(
            'sports_id'=> $id,
            'username' => $this->session->userdata('username'),
            'liked'=> ($this->input->post("liked") ===NULL)? 0:1,
            'disliked' => ($this->input->post("disliked") ===NULL)? 0:1,
            'name'=> $name
        );
        $this->db-> insert("voting", $data );
    }

    //Mark the video as favourite for a user
    public function get_one_liked($username, $id){
        $query = $this->db->get_where('voting',array('username' =>$username, 'sports_id' => $id))->row_array();
        return $query;
    }

    //Search videos from database
    function fetch_videos($keyword){
        $response = array();

        if( $keyword != ''){
            $this->db->select("*");
            $this->db->from('sports');
            $this->db->like('title', $keyword);
            $this->db->order_by('title', "ASC");
        
        $query = $this->db->get()->result();

        foreach($query as $row){
            $response[] = array("value"=>$row->title);
            }
        }  
        return $response;

    }

    function search_videos($keyword){
        $this->db->select("*");
        $this->db->from('sports');
        if( $keyword != ''){
            $this->db->or_like('title', $keyword);
            $this->db->or_like('username', $keyword);
            $this->db->or_like('date', $keyword);
            $this->db->or_like('name', $keyword);
        }
        return $this->db->get()->result_array();

    }


    public function like_count($id){
        $query = $this->db->get_where('voting',array('liked' => 1, 'sports_id' => $id));
        if($query->num_rows() > 0){
            return $query->num_rows();
        }
        else{
            return false;
        }
    }

    public function dis_count($id){
        
            $query = $this->db->get_where('voting',array('disliked' => 1, 'sports_id' => $id));
        if($query->num_rows() > 0){
            return $query->num_rows();
        }
        else{
            return false;
        }
    }

    public function playlist($sports_id, $title, $name){
        
        $this->db->where('sports_id', $sports_id);
        $this->db->where('username', $this->session->userdata('username'));
        $row = $this->db->get('playlist')->row();

        if(!isset($row)){
            $Data = array(
                'sports_id' => $sports_id,
                'username' => $this->session->userdata('username'),
                'title' => $title,
                'name' => $name,
                );
            $this->db->insert('playlist', $Data);
        }
        else{
            return false;
        }
    }

    public function remove($id){
        $this->db->select("*");
        $this->db->from("playlist");
        $this->db->where('id', $id);
        $this->db->delete("playlist");
        
    }

    //Get playlist for a user from database
    public function get_playlist($username){
        $query = $this->db->get_where('playlist',array('username' =>$username))->result_array();
        return $query;
    }

    //
    public function getRows($page){

        $count = $this->db->get('diets')->num_rows();
        $this->perPage = 6 ;
        if(!empty($page)){

            $start = ceil($page * $this->perPage);
            $query = $this->db->limit($start, $this->perPage)->get("diets");
            return $query->result();

        }
        else{
            $query = $this->db->limit(6, $this->perPage)->get("diets");
            return $query->result();
        }   

    }
}