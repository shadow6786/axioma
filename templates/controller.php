<?php

class {controller_name} extends CI_Controller {
    
    function __construct() {
        parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form','url','codegen_helper'));
		$this->load->model('panel/{folder}/{model}','',TRUE);
		$this->data["title"] = "{controller_name}";
		$this->data["page_segment"] = "{controller_name}";
		$this->data["father"]="{folder}";
	}	
	
	function index(){
		$this->manage();
	}

	function manage(){
        $this->load->library('table');
        $this->load->library('pagination');
        
        //paging
        //$config['base_url'] = base_url().'panel/{folder}/{controller_name_l}/manage/';
        	
        // make sure to put the primarykey first when selecting , 
		//eg. 'userID,name as Name , lastname as Last_Name' , Name and Last_Name will be use as table header.
		// Last_Name will be converted into Last Name using humanize() function, under inflector helper of the CI core.
		$this->data['results'] = $this->{model}->get("{fields_list}",'',1000,$this->uri->segment(4));
		$this->data["scripts"]=array('plugins/data-tables/jquery.dataTables.js',
                           'plugins/data-tables/DT_bootstrap.js');
		$this->data["main_content"] = 'panel/{folder}/{view}_list';
		$this->load->view('panel/panel_template',$this->data);
    }
	
    function add(){        
        $this->load->library('form_validation');
		$this->data['custom_error'] = '';
		{inline_rule}
        if ($this->form_validation->run() == false)
        {
             $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">'.validation_errors().'</div>' : false);
        }
        else
        {                            
            $data = array(
                    {data}
            );
			if ($this->{model}->add($data) == TRUE)
			{
				//$this->data['custom_error'] = '<div class="form_ok"><p>Added</p></div>';
				// or redirect
				redirect(base_url().'panel/{folder}/{controller_name_l}/manage/');
			}
			else
			{
				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured.</p></div>';
			}
		}
		{foreing_model}
		{foreing_data}

		$this->data["scripts"]="";
		$this->data["main_content"] = 'panel/{folder}/{view}_add';
		$this->load->view('panel/panel_template',$this->data);
    }	
    
    function edit($id){        
        $this->load->library('form_validation');
		$this->data['custom_error'] = '';
		{inline_rule}
        if ($this->form_validation->run() == false)
        {
             $this->data['custom_error'] = (validation_errors() ? '<div class="form_error">'.validation_errors().'</div>' : false);
        }
        else
        {                            
            $data = array(
                    {edit_data}
            );
			if ($this->{model}->edit($data,'{primaryKey}',$this->input->post('{primaryKey}')) == TRUE)
			{
				redirect(base_url().'panel/{folder}/{controller_name_l}/manage/');
			}
			else
			{
				$this->data['custom_error'] = '<div class="form_error"><p>An Error Occured</p></div>';

			}
		}
		$this->data['result'] = $this->{model}->get('{field_edit}','{primaryKey} = '.$id,1,0,true);
		
		{foreing_model}
		{foreing_data}
		
		$this->data["scripts"]= "";
		$this->data["main_content"] = 'panel/{folder}/{view}_edit';
		$this->load->view('panel/panel_template',$this->data);
    }
	
    function delete($id){
            //$ID =  $this->uri->segment(4);
            $this->{model}->delete('{primaryKey}',$id);
            redirect(base_url().'panel/{folder}/{controller_name_l}/manage/');
    }
}

/* End of file {controller_name_l}.php */
/* Location: ./system/application/controllers/{controller_name_l}.php */