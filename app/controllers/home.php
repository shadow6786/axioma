<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Public_controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function index()
	{
		$data["main_content"] = "public/home";
		$this->load->view('template/public_template', $data);
	}

}

/* End of file home.php */
/* Location: .//C/wamp/www/axioma/app/controllers/home.php */