<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function load_settings()
{
	//$ci será como $this
	$ci =& get_instance();
	$query = $ci->db->get('cnf_configuraciones');

	if($query->num_rows())
	{
		$data= array();
		foreach($query->result() as $row)
		{
			$data[$row->variable_cnf] = $row->valor_cnf;
		}
	}
	return $data;
}

function set_config_item($key, $value)
{
	$CI =& get_instance();
	$CI->db->update('cnf_configuraciones', array('valor_cnf' => $value), array('variable_cnf' => $key));
}