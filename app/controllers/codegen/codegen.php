<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CI Generator
 * http://projects.keithics.com/crud-generator-for-codeigniter/ 
 * Copyright (c) 2011 Keith Levi Lumanog
 * Dual MIT and GPL licenses.
 *
 * A CI generator to easily generates CRUD CODE, feel free to improve my code or customized it the way you like.
 * as inspired by Gii of Yii Framework. Last update August 15, 2011
 */
 

class Codegen extends CI_Controller {


    function index(){
		$data = '';
        $this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
        if ($this->input->post('table_data') || !$_POST)
        {
            // get table data
            $this->form_validation->set_rules('table', 'Table', 'required|trim|xss_clean|max_length[200]');

            if ($this->form_validation->run() == false)
            {
				//¿alguien borro esto?
            } else
            {
                $table = $this->db->list_tables();
                $data['table'] = $table[$this->input->post('table')];
				//var_dump( $data['table']);
				//exit;
                $result = $this->db->query("SHOW FULL FIELDS from " . $data['table']);
				$data['alias'] = $result->result();
				/* GET ALL FOREING KEYS OF THE TABLE */
				//$foreign_key = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE CONSTRAINT_NAME <> 'PRIMARY' AND CONSTRAINT_NAME not like '%Unique%'  AND TABLE_SCHEMA = 'cms' AND TABLE_NAME = '".$data["table"]."'");
				$foreign_key = $this->db->query("SELECT a.table_schema, a.table_name, a.constraint_name,
                      a.constraint_type,
                      convert(group_concat(DISTINCT b.column_name
                      ORDER BY b.ordinal_position SEPARATOR ', '), char)
                      as column_name,
                      b.referenced_table_name, b.referenced_column_name
                FROM information_schema.table_constraints a
                INNER JOIN information_schema.key_column_usage b
                ON a.constraint_name = b.constraint_name AND
                  a.table_schema = b.table_schema AND
                  a.table_name = b.table_name
                WHERE a.constraint_type ='FOREIGN KEY' AND a.table_schema = '".$data['table']."' AND a.table_name = '".$data['table']."'"."
                GROUP BY a.table_schema, a.table_name, a.constraint_name,
                        a.constraint_type, b.referenced_table_name,
                        b.referenced_column_name
                UNION
                SELECT table_schema, table_name, index_name as constraint_name,
                      if(index_type='FULLTEXT', 'FULLTEXT', 'NON UNIQUE')
                      as constraint_type,
                      convert(group_concat(column_name
                      ORDER BY seq_in_index separator ', '), char) as column_name,
                      null as referenced_table_name, null as referenced_column_name
                FROM information_schema.statistics
                WHERE non_unique = 1 AND index_type = 'FOREIGN KEY' AND table_schema = 'agenda_bd' AND table_name = '".$data['table']."'"."  
                GROUP BY table_schema, table_name, constraint_name, constraint_type,
                        referenced_table_name, referenced_column_name
                ORDER BY table_schema, table_name, constraint_name");
				$fk =  $foreign_key->result();
				/* PROCES AL KEYS INTO A INDEXED ARRAY */ 
				$arr = array();
				if (count($fk)){
					//var_dump($fk);
					//exit;
    				foreach ($fk as $for_key) {
    					
    					//$key = $for_key->COLUMN_NAME;
    					$key = $for_key->column_name;
    					//$var = $this->db->query("show full columns from ".$for_key->REFERENCED_TABLE_NAME)->result_array();
                        $var = $this->db->query("show full columns from ".$for_key->referenced_table_name)->result_array();
    					$aux = array();
    					//$aux[] = $for_key->REFERENCED_TABLE_NAME;
    					$aux[] = $for_key->referenced_table_name; 
    					foreach ($var as $cmb) {
    						$aux[$cmb["Field"]] = $cmb["Field"];
    					}
    					$arr[$key] =  $aux;
    				}
				}
				/*
				echo "<pre>";
				print_r($arr);
				exit;
				*/
				$data["fk"] = $arr;
           	}
            
            $this->load->view('codegen/codegen', $data);

        } else if ($this->input->post('generate'))
            {
            	
            	echo "<pre>";
				//print_r($this->input->post());
				//exit;
				
                $this->load->helper('file');
				
				$exist_check = FALSE;
				$exist_check = array_search(array("checkbox"), $this->input->post("type"));

                $all_files = array(
                    'app/config/form_validation.php',
                    'app/controllers/panel/'.$this->input->post('folder').'/'.$this->input->post('controller').'.php',
                    'app/models/panel/'.$this->input->post('folder').'/'.$this->input->post('model').'_model.php',
                    'app/views/panel/'.$this->input->post('folder').'/'.$this->input->post('view').'_add.php',
                    'app/views/panel/'.$this->input->post('folder').'/'.$this->input->post('view').'_edit.php',
                    'app/views/panel/'.$this->input->post('folder').'/'.$this->input->post('view').'_list.php'
                    );

                //checking of files if they existed. comment if you want to overwrite files!
                $err = 0;
                /*** // uncomment me to allow overwrites
                foreach($all_files as $af){
                    if($this->fexist($af)){
                        $err++;
                        echo $this->fexist($af)."<br>";    
                    }
                }
                
                if($err > 0){
					echo 'Files Exists - Generator stopped.<br>';
                    echo '<h3>Post Data Below:</h3><br>';
                    echo '<pre>';
                    print_r($_POST);
                    echo '<pre>';
                    exit;
                }
                ***/
                $rules = $this->input->post('rules');
                $label = $this->input->post('field');
                $type = $this->input->post('type');
				$foreing_keys = $this->input->post('fk');
				$fk_tables = $this->input->post('fk_table');
				$fk_ids = $this->input->post('fk_id');
				/*
				print_r($fk_ids);
				exit;
				*/
                $include_model =array();
				$result_fk = array();
                // looping of labels and forms , for edit and add
                foreach($label as $k => $v){
                    if($type[$k][0] != 'exclude'){
                    $labels[] = $v;
                    $form_fields[] = $k;
                    if($rules[$k][0] != 'required'){
                        $required = '';
                    }else{
                        $required = '<span class="required">*</span>';
                    }
					//echo $type[$k][0] . '<br/>';
					
                    // this will create a form for Add and Edit , quite dirty for now
                    if($type[$k][0] == 'textarea'){
                         $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<textarea id="'.$k.'" name="'.$k.'" class="span12"><?php echo set_value(\''.$k.'\'); ?></textarea>
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                   
                                    </div>
                                    ';
                         $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<textarea id="'.$k.'" name="'.$k.'" class="span12"><?php echo $result->'.$k.' ?></textarea>
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                    
                                    </div>
                                    ';
                    }else if($type[$k][0] == 'checkbox'){
                         $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<select id="'.$k.'" name="'.$k.'" class="span2" > <option value="1" <?php if( set_value(\''.$k.'\')==1) echo "selected=\"selected\""  ?> >Sí</option><option value="0" <?php if( set_value(\''.$k.'\')==0) echo "selected=\"selected\""  ?>>No</option></select> 
										
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                   
                                    </div>
                                    ';
                         $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<select id="'.$k.'" name="'.$k.'" class="span2" > <option value="1" <?php if( $result->'.$k.'==1) echo "selected=\"selected\""  ?> >Sí</option><option value="0" <?php if( $result->'.$k.'==0) echo "selected=\"selected\""  ?>>No</option></select> 
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                    
                                    </div>
                                    ';
                    }
					else if($type[$k][0] == 'wysiwyg'){
                         $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<textarea class="wysiwyg_'.$k.'" id="'.$k.'" name="'.$k.'" style="width:98%"  rows="10"><?php echo set_value(\''.$k.'\'); ?></textarea>
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                   
                                    </div>
                                    ';
                                    
                         $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls">
										<textarea class="wysiwyg_'.$k.'" id="'.$k.'" name="'.$k.'"  style="width:98%"  rows="10"><?php echo $result->'.$k.' ?></textarea>
										<?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                    
                                    </div>
                                    ';
                    }
					else if($type[$k][0] =='datepicker'){
						//datepicker
                       $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls"> 
									
									 <div class="dtk_'.$k.' input-append date form_datetime "  data-date="<?php echo set_value(\''.$k.'\'); ?>">
				<input id="'.$k.'" name="'.$k.'" size="16" type="text" value="<?php echo set_value(\''.$k.'\'); ?>" readonly>
				<span class="add-on"><i class="icon-calendar"></i></span>
			  </div>
			  
			  
			
									<?php echo form_error(\''.$k.'\',\'<div class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                    
                                    </div>
                                    ';
                        $edit_form[] = '
                                    <div class="control-group"><label class="control-label " for="'.$k.'">'.$v.$required.'</label>                                
                                 <div class="controls">   <div class="dtk_'.$k.' input-append date" data-date="<?php echo $result->'.$k.' ?>" data-date-format="yyyy-mm-dd hh:mm:ss">
				<input id="'.$k.'" name="'.$k.'" size="16" type="text" value="<?php echo $result->'.$k.' ?>" readonly>
				<span class="add-on"><i class="icon-calendar"></i></span>
			  </div>
			  
			  
								   <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message" >\',\'</div>\'); ?>
								    </div>
                                  
                                    </div>
                                    ';
                    }
					else if($type[$k][0] =='dropdown'){
						$val_fk = $foreing_keys[$k];
						$table_fk = $fk_tables[$k];
						$table_a_fk = explode('_', $table_fk);
						$table_name_fk = $table_a_fk[1];
						$field_fk = $val_fk[0];
						$id = $fk_ids[$k];
						$include_model[] = "\$this->load->model('panel/".$this->input->post('folder')."/".$table_name_fk."_model');\t\n";
						$result_fk[] = "\$this->data['".$table_name_fk."'] = \$this->".$table_name_fk."_model->get_".$table_name_fk."_combo('".$id.",".$field_fk."','');\t\n";
						$add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>  
                                    	<div class="controls"> 
                                    		<select id="'.$k.'" name="'.$k.'" >
		                                    <?php
		                                    foreach ($'.$table_name_fk.' as $row) {
		                                        echo "<option value=".$row["'.$id.'"].">".$row["'.$field_fk.'"]."</option>";
		                                    }
		                                    ?>
		                                    </select>
		                                    <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
                                    	</div>
                                    </div>
                                    ';
                        $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>
                                    	<div class="controls">
                                    		<select id="'.$k.'" name="'.$k.'" >
		                                    <?php
		                                    foreach ($'.$table_name_fk.' as $row) {
		                                        echo "<option value=".$row["'.$id.'"].">".$row["'.$field_fk.'"]."</option>";
		                                    }
		                                    ?>
		                                    </select>
		                                    <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
		                                </div>
		                            </div>
                                    ';
					}
					else if($this->input->post($k.'default')){
                        $enum = explode(',',$this->input->post($k.'default'));
						
                        $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <?php
                                    foreach ($variable as $key => $value) {
                                        
                                    }
                                    //$enum = array('.$this->input->post($k.'default').'); 
                                    //echo form_dropdown(\''.$k.'\', $enum); 
                                    ?>
                                    <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
                                    </div>
                                    ';
                        $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>
                                    <?php
                                    $enum = array('.$this->input->post($k.'default').');                                                                    
                                    echo form_dropdown(\''.$k.'\', $enum,$result->'.$k.'); ?>
                                    <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message">\',\'</div>\'); ?>
                                    </div>
                                    ';
                    }
                    else{
                        //input
                        $add_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                    <div class="controls"><input id="'.$k.'" type="'.$type[$k][0].'" name="'.$k.'" value="<?php echo set_value(\''.$k.'\'); ?>"  />
									<?php echo form_error(\''.$k.'\',\'<div class="inline_validation_message">\',\'</div>\'); ?>
									</div>
                                    
                                    </div>
                                    ';
                        $edit_form[] = '
                                    <div class="control-group"><label class="control-label" for="'.$k.'">'.$v.$required.'</label>                                
                                 <div class="controls">   <input id="'.$k.'" type="'.$type[$k][0].'" name="'.$k.'" value="<?php echo $result->'.$k.' ?>"  />
								   <?php echo form_error(\''.$k.'\',\'<div  class="inline_validation_message" >\',\'</div>\'); ?>
								    </div>
                                  
                                    </div>
                                    ';
                        }
                    }
                }
              
                // this will ensure that the primary key will be selected first.
                $fields_list[] = $this->input->post('primaryKey');
				$fields_edit[] = $this->input->post('primaryKey');
                // looping of rules 
                $table_header = array();
               	foreach($rules as $k => $v){
                    $rules_array = array();
                    $table_header[] = "<th>".$label[$k]."</th>\n\t\t\t\t\t\t\t\t\t\t";
                    if($type[$k][0] != 'exclude'){
                        
                        foreach($rules[$k] as $k1 => $v1){
                            if($v1){
                            $rules_array[] = $v1;
                            }
                        }
                        $form_rules = implode('|',$rules_array);
                        $form_val_data[] = "array(
                                \t'field'=>'".$k."',
                                \t'label'=>'".$label[$k]."',
                                \t'rules'=>'".$form_rules."'
                                )";
						$inline_rule[] = "\$this->form_validation->set_rules('".$k."','".$label[$k]."','".$form_rules."');\n\t\t";
                        $controller_form_data[] = "'".$k."' => set_value('".$k."')";
                        $controller_form_editdata[] = "'".$k."' => \$this->input->post('".$k."')";
						$new_label = str_replace(" ", "_", $label[$k]);
						if($k !== $exist_check){
							$fields_list[] = $k." AS ".$new_label;
						}
						else{
							$fields_list[] = "CASE WHEN ".$k." = 1 THEN 'SI' ELSE 'NO' END AS ".$new_label;
						}
						$fields_edit[] = $k;
                    }
                }
                $new_rule = implode("", $inline_rule);
                $fields = implode(',',$fields_list);
				$field_edit = implode(',',$fields_edit);
				
				$model_fk = "";
				$data_fk = ""; 
				if(count($include_model) > 0)
				{
					$model_fk = implode("", $include_model);
					$data_fk = implode("", $result_fk);
				}
				
                $form_data = implode(','."\n\t\t\t\t\t\t\t\t",$form_val_data);
                
                $file_validation = 'app/config/form_validation.php';
                
                //$search_form = array('{validation_name}','{form_val_data}');
               // $replace_form = array($this->input->post('validation'),$form_data);
				$form_validation_data = "'".$this->input->post('table')."' => array(".$form_data.")";
				
				if(file_exists('app/config/form_validation.php')){
					$form_v = file_get_contents('app/config/form_validation.php');
					 $old_form =  str_replace(array('<?php','?>','$config = array(',');'),'',$form_v)."\t\t\t\t,\n\n\t\t\t\t";
					include('app/config/form_validation.php');
					
					if(isset($config[$this->input->post('table')])){
						// rules already existed , reload rules
						$form_content = str_replace('{form_validation_data}',$form_validation_data,file_get_contents('templates/form_validation.php'));	
					}else{
						// append new rule
						$form_content = str_replace('{form_validation_data}',$old_form.$form_validation_data,file_get_contents('templates/form_validation.php'));	
					}
				
				}else{	
                	$form_content = str_replace('{form_validation_data}',$form_validation_data,file_get_contents('templates/form_validation.php'));
				
            	}
               ////////////////////
                $c_path = 'app/controllers/admin/';
                $m_path = 'app/models/'; 
                $v_path = 'app/views/'.$this->input->post('folder').'/';                              
                
                ///////////////// controller
                $controller = file_get_contents('templates/controller.php');
                $search = array('{folder}','{controller_name}', '{view}', '{table}','{validation_name}',
                '{data}','{edit_data}','{controller_name_l}','{primaryKey}','{fields_list}','{field_edit}','{inline_rule}','{model}','{foreing_model}','{foreing_data}');
                $replace = array(
                            $this->input->post('folder'),
                            ucfirst($this->input->post('controller')), 
                            $this->input->post('view'),
                            $this->input->post('table'),
                             $this->input->post('validation'),
                             implode(','."\n\t\t\t\t\t",$controller_form_data),
                             implode(','."\n\t\t\t\t\t",$controller_form_editdata),
                             $this->input->post('controller'),
                             $this->input->post('primaryKey'),
                             $fields,
                             $field_edit,
                             $new_rule,
                             $this->input->post('model')."_model",
                             $model_fk,
                             $data_fk
                            );

                $c_content = str_replace($search, $replace, $controller);
                //echo $c_content;exit;

                $file_controller = $c_path . $this->input->post('controller') . '.php';
				
				$m_search = array('{model_name}','{table}','{table_n}');
                
				$model = file_get_contents('templates/model.php');
				
				$m_replace = array(ucfirst($this->input->post('model'))."_model",$this->input->post('table'),$this->input->post('model'));
				//$m_replace = array(ucfirst($this->input->post('model'))."_model",$this->input->post('model'));
				
				$m_content = str_replace($m_search, $m_replace, $model);
				
				$file_model = $m_path.$this->input->post('model').'_model.php';
              
                // create view/form, TODO, make this a function! and make a stop overwriting files

                //VIEW/LIST FORM
                $list_v = file_get_contents('templates/list.php');
                $l_search = array('{folder}','{controller_name_l}','{table_headers}');
				
                $headers = implode("", $table_header);

                $l_replace = array($this->input->post('folder'),$this->input->post('controller'),$headers);
                $list_content = str_replace($l_search,$l_replace,$list_v);
                //echo $list_content;exit();
                //ADD FORM
                $add_v = file_get_contents('templates/add.php');
                $a_search = array('{folder}','{controller_name_l}','{forms_inputs}');
				$a_replace = array($this->input->post('folder'),$this->input->post('controller'),implode("\n",$add_form));
                $add_content = str_replace($a_search,$a_replace,$add_v);
                
                //EDIT FORM
                $edit_v = file_get_contents('templates/edit.php');
                $edit_search = array('{folder}','{controller_name_l}','{forms_inputs}','{primary}');
                $edit_replace = array($this->input->post('folder'),$this->input->post('controller'),implode("\n",$edit_form),'<?php echo form_hidden(\''.$this->input->post('primaryKey').'\',$result->'.$this->input->post('primaryKey').') ?>');
                
                $edit_content = str_replace($edit_search,$edit_replace,$edit_v);
                
                $write_files = array(
                                'Controller' => array($file_controller, $c_content),
                                'Model' => array($file_model,$m_content),
                                'view_edit'  => array($v_path.$this->input->post('view').'_edit.php', $edit_content),
                                'view_list'  => array($v_path.$this->input->post('view').'_list.php', $list_content),
                                'view_add'  => array($v_path.$this->input->post('view').'_add.php', $add_content)//,
                               //'form_validation'  => array($file_validation, $form_content) 
                                );
				if(!file_exists($c_path)){
					mkdir($c_path,0777);
				}
				if(!file_exists($m_path)){
					mkdir($m_path,0777);
				}
				if(!file_exists($v_path)){
					mkdir($v_path,0777);
				}
                foreach($write_files as $wf){
                	if(strpos($wf[0],'models') !== false)
					{
						if($this->fexist($wf[0]) === false)
						{
							if($this->writefile($wf[0],$wf[1])){
			                	$err++;
			                    echo $this->writefile($wf[0],$wf[1]);
							}
						}
					}
					else
					{
						if($this->writefile($wf[0],$wf[1])){
		                	$err++;
		                    echo $this->writefile($wf[0],$wf[1]);
						}
					}
                }   
				         
				                
               if($err >0){
                    exit;
                }else{
                    $data['list_content'] = $list_content;
                    
                    $data['add_content'] = $add_content;

                    $data['edit_content'] = $edit_content;
                    
                    $data['controller'] = $c_content;
                    
                 //   $this->load->view('done',$data);
                    echo 'DONE! '. anchor(base_url() .'panel/'.$this->input->post('folder').'/'.$this->input->post('controller').'/');
                }   
            }// if generate
    }
    
    function fexist($path){

             if (file_exists($path))
            {
                // todo , automatically adds new validation
                return $path.' - File exists <br>';
            }
            else{
                return false;
            }        
    }
    
    function writefile($file,$content){
        if (!write_file($file, $content))
        {
            return $file. ' - Unable to write the file';
        } else
        {
            return false;
        }
    }
	
	function get_table_columns($table)
	{
		$result = $this->db->query("show full columns from " . $data['table']);
		print_r($result->result_array());
		exit;
	}

}

/* End of file codegen.php */
/* Location: ./app/controllers/codegen.php */
