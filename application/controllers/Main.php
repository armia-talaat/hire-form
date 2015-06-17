<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	

	public function index()
	{
		$this->load->view('main');
	}
	public function get_cv_address($name="applicant"){      
       
      
        if ( ! $this->upload->do_upload('cv')){
            echo $this->upload->display_errors();
            
            exit();
            return false;
                       
        }
        else{

        	return $this->upload->data('full_path');
        }
        
	}

	// so we entre an applicant to the database
	public function add_app(){

		
		// get the data from the input
		// get the file a pdf
		$user_data = array(
			'name' =>$this->input->post('name',true),
			'age'=> $this->input->post('day',true).":".$this->input->post('month',true).":".$this->input->post('year',true),
			'email'=>$this->input->post('email',true),
			'currentStudy' =>$this->input->post('study',true),
			'university'=>$this->input->post('university',true),
			'job'=>$this->input->post('job',true),
			'comment'=>$this->input->post('comment',true), 
			);
		
		// insert the data
		$this->db->trans_start();
		$this->db->insert('people', $user_data);
		$id= $this->db->insert_id();
		$this->db->trans_complete();
		

		$this->load->library('upload');
		
		

		$cv = array('cv' => $this->get_cv_address($this->input->post('name',true)));
		
		 $error = $this->db->error();
		if ($error['code']!== 0) {

			
		
			$this->load->view('main',array('error'=>"Something went wrong"));

			exit();
		}


		if (!$cv['cv']) {

			
		
			$this->load->view('main',array('error'=>"Something went wrong"));

			exit();
		}

		// update the cv column table and end
		
		$this->db->update('people', $cv, array('id' => $id));
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
