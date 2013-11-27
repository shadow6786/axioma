<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class {controller_name} extends Main_Controller {
	function __construct()
 	{
   		parent::__construct();
		$this->load->model("{model}",'',TRUE);
		$this->load->model("menu",'',TRUE);
		$this->load->library('form_validation');
		{inline_rule}
	}
	function index()
	{
		 $data["menu"] = $this->menu->get_mymenu();
		 $data["main_content"] =  "{folder}/{view}_list";
		 $data["scripts"] = array("data_table.js");
		 $this->load->view('templates/admin_template',$data);
	}
	function edit($id = NULL)
	{
		if(isset($id))
		{
			if(!$this->form_validation->run())
			{
				$this->data['custom_error'] = (validation_errors() ? '<div class="form_error">'.validation_errors().'</div>' : false);
			}
			else{
				$data = array(
                    {edit_data}
            	);
            	if ($this->{model}->edit($data,'{primaryKey}',$this->input->post('{primaryKey}')) == TRUE)
				{
					redirect(base_url('admin/{controller_name_l}/index/'));
				}
			}
			$data["menu"] = $this->menu->get_mymenu();
			$data["main_content"] = "{folder}/{view}_edit";
			$this->load->view('templates/admin_template',$data);
		} else {
			$this->index();
		}
	}	
	function add()
	{
		if(!$this->form_validation->run())
			{
				$this->data['custom_error'] = (validation_errors() ? '<div class="form_error">'.validation_errors().'</div>' : false);
			}
			else{
				$data = array(
                    {data}
            	);
            	if ($this->{model}->add($data) > 0)
				{
					redirect(base_url('admin/{controller_name_l}/index/'));
				}
			}
		$data["menu"] = $this->menu->get_mymenu();
		$data["main_content"] = "{folder}/{view}_add";
		$this->load->view('templates/admin_template',$data);
	}
	function datatable()
	{
		$this->load->library('Datatables');
        $this->load->library('table');
        $this->load->database();
        $this->datatables->select('*')
        ->from('{table}');

        echo $this->datatables->generate();
	}
}
/* End of file {controller_name_l}.php */
/* Location: ./system/application/controllers/{controller_name_l}.php */