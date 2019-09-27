<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library('session');
	}

	public function index()
	{
		if( $this->session->has_userdata('user_data') ){
			redirect('/home');
		}
		$this->load->view('layouts/header');
		$this->load->view('login');
		$this->load->view('layouts/footer');
		$this->session->unset_userdata('login_error'); // to avoid showing error when the page is refreshed
	}

	public function verify(){

		$auth   = $this->load->model('Auth_model');
		$verify = $this->Auth_model->select($this->input->post());

		if(!$verify){
			$this->session->set_userdata('login_error', true);
			redirect('/');
		}
		else{
			$this->session->unset_userdata('login_error');
			$this->session->set_userdata('user_data', $verify); // userdata stored in session
			redirect('/home');
		}

	}

	public function registration_form($status = false){

		$array = [
			'status' => $status
		];

		$this->load->view('layouts/header');
		$this->load->view('register', $array);
		$this->load->view('layouts/footer');
	}

	public function register(){
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', array('required', 'min_length[5]'));
		$this->form_validation->set_rules('password', 'Password', array('required', 'min_length[5]'));
		$this->form_validation->set_rules('confirm_password', 'Password Confirm', array('required', 'min_length[5]', 'matches[password]'));

		if ($this->form_validation->run() == FALSE){
			$this->registration_form();
		}
		else{
			$auth = $this->load->model('Auth_model');
			$create = $this->Auth_model->create($this->input->post());
			if($create == true){
				redirect('/');
			}
		}

	}

	public function logout(){
		$array = array('user_data', 'sem', 'sy');
		$this->session->unset_userdata($array);
		redirect('/');
	}

}
