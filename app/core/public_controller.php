<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Public_controller extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$conf = load_settings();
		foreach ($conf as $key => $value) {
			//var_dump($key);
			//var_dump($value);
			$this->data[$key] = $value;
		}
	}
}