<?php
    class Diet extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->model('user_model');
            $this->load->library('image_lib');
            // Per page limit
            $this->perPage = 6;
        }

        //View diet page
        public function index()
        {   
            
            $count = $this->db->get('diets')->num_rows();

            if(isset($Msg)){
                $data["Msg"] = $Msg;
            }
            $page = $this->input->get("page");
            if(!empty($page)){
                
                $start = ceil($page * $this->perPage);
                $query = $this->db->limit($start, ($page-1)*$this->perPage)->get("diets");
                $data['diets'] = $query->result();

                $result = $this->load->view('more_diet', $data);
                return $result ;

            }else{
                $data["count"] = ceil($this->db->get('diets')->num_rows()/6);
                $query = $this->db->limit($this->perPage, 0)->get("diets");
                $data['diets'] = $query->result();

                $this->load->view('templates/header');
                $this->load->view('diet', $data);
                $this->load->view('templates/footer');
            }
            
        
        }

        // Perform manipulation on image ("crop","resize","rotate","watermark".)
        public function diet_upload() {
            // image will store in root directory "diet" folder.
            $config['upload_path'] = './diet/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            $config['max_size'] =  1024*1024*100;

            if($this->session->userdata('logged_in')) {
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('diets')) {
                    $data['error'] = $this->upload->display_errors('<p style="color: red; margin-top: 30px;">', '</p>');
                    $data['get_diets'] = $this->user_model->get_diets();
                    $this->load->view('templates/header');
                    $this->load->view('diet', $data);
                    $this->load->view('templates/footer');
                } else {
                    //If image upload in folder, set also this value in "$data"
                    $data = $this->upload->data();

                    switch (!empty($this->input->post("mode"))) {
                        case "crop":
                             // Crop the image and upload it in folder
                             $this->crop($data);
                             break;

                        case "resize":
                             // Resize the image and upload it in folder
                            $this->resize($data);
                            break;

                        case "rotate":
                             // Rotate the image and upload it in folder
                            $this->rotate($data);
                            break;

                        case "watermark":
                            // Watermarking the image and upload it in folder
                            $this->water_marking($data);
                            break;
                            
                    }
                    $uploaded['username'] = $this->session->userdata('username');
                    $uploaded['date'] = date('Y-m-d H:i:s');
                    $uploaded['title'] = $this->input->post('title');
                    if(!empty($this->input->post("mode"))){
                        $uploaded['name'] = 'new_'.$data['file_name'];
                    }
                    else{
                        $uploaded['name'] = $data['file_name'];
                    }
                    //insert diet info into database
                    if(!empty($uploaded)){
                        $insert = $this->user_model->insert_diet($uploaded);
                        $Msg = "";
                        if ($insert){
                            $Msg = 'Your diet was successfully uploaded!';
                        }
                        else{
                            $Msg = 'Some problem occurred, please try again.';
                        }
                        redirect("Diet/index/");
                    }
                }
        }
            else{
            //The user can only manipulate their image when logged in.
            echo "<script type='text/javascript'>alert('You should login first.');</script>";
            $data['get_diets'] = $this->user_model->get_diets();
            $this->load->view('templates/header');
            $this->load->view('diet', $data);
            $this->load->view('templates/footer');
            }
    }

        // Resize Manipulation.
        public function resize($data)
        {
            $img = 'new_'.$data['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data['full_path'];
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $this->input->post('width');
            $config['height'] = $this->input->post('height');
            $config['new_image'] = './diet/'.$img;

            $this->image_lib->initialize($config);
            $src = $config['new_image'];
            $new['new_image'] = substr($src, 1);

            $this->image_lib->resize();
            return $new;

        }
        // Rotate Manipulation.
        public function rotate($data) {
            $img = 'new_'.$data['file_name'];
            $config['image_library'] = 'gd2';
            $config['library_path'] = '/usr/bin/';
            $config['source_image'] = $data['full_path'];
            $config['rotation_angle'] = $this->input->post('degree');
            $config['quality'] = "90%";
            $config['new_image'] = './diet/' . $img;

            $this->image_lib->initialize($config);
            $src = $config['new_image'];
            $new['new_image'] = substr($src, 1);

            $this->image_lib->rotate();
            return $new;
        }
        // Water Mark Manipulation.
        public function water_marking($data) {

            $img = 'new_'.$data['file_name'];
            $config['image_library'] = 'gd';
            $config['source_image'] = $data['full_path'];
            $config['wm_text'] = $this->input->post('text');
            $config['wm_type'] = 'text';
            $config['wm_font_path'] = './system/fonts/texb.ttf';
            $config['wm_font_size'] = '100';
            $config['wm_font_color'] = '#ffffff';
            $config['wm_vrt_alignment'] = 'bottom';
            $config['wm_hor_alignment'] = 'center';
            $config['new_image'] = './diet/' .$img;

            $this->image_lib->initialize($config);
            $src = $config['new_image'];
            $new['new_image'] = substr($src, 1);

            $this->image_lib->watermark();

            return $new;
        }
        // Crop Manipulation.
        public function crop($data) {
            $img = 'new_'.$data['file_name'];
            $config['image_library'] = 'gd2';
            $config['source_image'] = $data['full_path'];
            $config['x_axis'] = $this->input->post('x1');
            $config['y_axis'] = $this->input->post('y1');
            $config['maintain_ratio'] = TRUE;
            $config['width'] = $this->input->post('width_cor');
            $config['height'] = $this->input->post('height_cor');
            $config['new_image'] = './diet/' . $img;

            $this->image_lib->initialize($config);
            $src = $config['new_image'];
            $new['new_image'] = substr($src, 1);

            $this->image_lib->crop();
            return $new;
        }

        /*function loadMoreData(){
            $conditions = array();
            
            // Get last post ID
            $lastID = $this->input->post('id');
            
            // Get post rows num
            $conditions['where'] = array('id >'=>$lastID);
            $conditions['returnType'] = 'count';
            $data['postNum'] = $this->user_model->getRows($conditions);
            
            // Get posts data from the database
            $conditions['returnType'] = '';
            $conditions['order_by'] = "id ASC";
            $conditions['limit'] = $this->perPage;
            $data['diets'] = $this->user_model->getRows($conditions);
            
            $data['postLimit'] = $this->perPage;
            
            // Pass data to view
            $this->load->view('more_diet',$data);
    
        } */

    }


