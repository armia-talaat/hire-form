<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	

	public function index()
	{
		$this->load->view('main');
	}
	public get_cv_address($name="applicant"){

		$this->load->library('upload');



		$config['upload_path']  = './cvs/';
        $config['allowed_types']= 'pdf';
        $config['file_name']    = $name + 'cv';
        $config['max_size']     = 2048;
               

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload()){
            return false;
                       
        }
        else{

        	return $this->upload->data('full_path');
        }
        
	}
	// so we entre an applicant to the database
	public function add_app(){
		$cv = $this->get_cv_address();

		if (!$cv) {

			$error = array('error' => $this->upload->display_errors());

			$this->load->view('main',$error);
		}

		
		
		// get the data from the input
		// get the file a pdf
		$user_data = array(
			'name' =>$this->input->post('name',true),
			'age'=>$this->input->post('age',true),
			'email'=>$this->input->post('email',true),
			'currentStudy' =>$this->input->post('study',true),
			'university'=>$this->input->post('university',true),
			'job'=>$this->input->post('job',true),
			'comment'=>$this->input->post('comment',true), 
			'cv'=>$cv
			);
		// insert the data
		$this->db->trans_start();
		$this->db->insert('people', $user_data);
		$this->db->trans_complete();

		// load the happy page
		$this->load->view('happyEnd');
	}
	// so we read all the applicants from the database
	public function get_apps(){

		$apps = $this->db->get('people');
		
		$this->load->view('applicants',$apps);
	}
	public function hiring_admin(){
		// show a login page
	}
	public function adminLogin(){

		// make sure
			// if yes show the table
			// if no show error with the login page
	}
}
