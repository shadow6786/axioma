<?php 
	$this->load->view('template/basic_metas');
	$this->load->view('template/basic_header');
	$this->load->view($main_content);
	$this->load->view('template/basic_footer');
	$this->load->view('template/basic_scripts');