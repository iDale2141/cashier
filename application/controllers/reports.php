<?php

/**
 * 
 */
class Reports extends CI_Controller
{
	public function __construct(){
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->model('Reports_model');
		if( !$this->session->has_userdata('user_data') ){
			redirect('/');
		}
	}

	public function monthly_collection_report(){
		$class = __CLASS__;
		$this->load->view('layouts/header', ['class' => $class]);
		$this->load->view('pages/monthly_collection_report');
		$this->load->view('pages/reports_script');
		$this->load->view('layouts/footer');
	}

	public function generate_monthly_report(){
		$gmr  = $this->Reports_model->generate_monthly_report($this->input);
		echo json_encode($gmr);
	}

	public function test(){
		$test  = $this->Reports_model->test();
	}

}

?>