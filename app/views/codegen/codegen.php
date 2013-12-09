<form action="<?php echo current_url();?>" method="post">
<h1>Axioma CodeGen 3.5</h1>
Tables: <?php
$db_tables = $this->db->list_tables();
echo form_dropdown('table',$db_tables);
?>
<input type="submit" name="table_data" value="Get Table Data" /></p>
</form>
<form action="<?php echo current_url();?>" method="post">
<?php
if(isset($alias)){
?>
<input type="hidden" name="table" value="<?php echo $table ?>" />
<?php 
$table_ar = explode('_',$table);
$table_name = $table_ar['1'];
?>

<table>
<tr>
    <td>
        <p>Controller Name: <input type="text" name="controller" value="<?php echo $table_name ?>" />        
        View Name: <input type="text" name="view" value="<?php echo $table_name ?>" />
        Model Name: <input type="text" name="model" value="<?php echo $table_name ?>" />_model &nbsp;&nbsp;
        Folder Name: <input type="text" name="folder" value="<?php echo trim(substr($table,0,3))?>"/>
        <!--Validation Name: <input type="text" name="validation" value="<?php echo $table_name ?>" />-->
         <input type="submit" name="generate" value="Generate" /></p>    
    </td>
</tr>
<tr>
    <td>
    <h3>Table Data</h3>
    <?php
    //p($alias);
    
    $type = array(
                'exclude'  =>'Do not include',
                'text' => 'Text input',
                'password' => 'Password',
                'textarea' => 'Textarea' , 
				'wysiwyg' => 'Wysiwyg' , 
                'dropdown' => 'Dropdown',
				'datepicker' => 'DatePicker',
				'checkbox' => 'Checkbox'
                );

   $sel = '';
    if(isset($alias)){
    	$fk_tables = array();
		$fk_ids = array();
        foreach($alias as $a){
            $email_default = FALSE;
			$var = "";
			if($a->Comment != "")
			{
				$var = $a->Comment;
			}
			else {
				$var = $a->Field;
			}
            echo '<p> Field: '.$a->Field.'<br>Label:'.form_input('field['.$a->Field.']', ucfirst($var)).' '.$a->Type;
			//exist foreing keys ???
            if(strpos($a->Key,'MUL') !== false)
			{
                $sel = 'dropdown';
                $tab = array_shift($fk[$a->Field]);
				$id = array_shift($fk[$a->Field]);
				
				echo "&nbsp;".form_dropdown('fk['.$a->Field.'][]',$fk[$a->Field]);
				$fk_tables[$a->Field] = $tab;
				$fk_ids[$a->Field] = $id;
            }
			else if(strpos($a->Type,'blob') !== false || strpos($a->Type,'text') !== false)
			{
                $sel = 'textarea';
            }
			else if($a->Key == 'PRI')
			{
                $sel = 'exclude';
                echo form_hidden('primaryKey',$a->Field);
            }
			else if(strpos($a->Field,'password') !== false)
			{
                $sel = 'password';
            }
			else if(strpos($a->Field,'email') !== false)
			{
                $email_default = TRUE;
            }
			else if(strpos($a->Type,'smallint') !== false)
			{
                $sel = 'checkbox';
            }
			else if(strpos($a->Type,'datetime') !== false)
			{
                $sel = 'datepicker';
            }
			else
			{
                 $sel = 'text';
            }
            echo '<br> Type::'.form_dropdown('type['.$a->Field.'][]', $type,$sel);
            echo '<br>';
            echo form_checkbox('rules['.$a->Field.'][]', 'required', TRUE) . ' required :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'trim', TRUE) . ' trim :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'valid_email', $email_default) . ' email :: ';
            echo form_checkbox('rules['.$a->Field.'][]', 'xss_clean', TRUE) . ' xss_clean ::';
            //echo ':: custom rule '. form_input('rules['.$a->Field.'][]', '');
            echo '</p>';
        }
		echo form_hidden("fk_table", $fk_tables);
		echo form_hidden("fk_id",$fk_ids);
    }
    ?>
    </td>
</tr>
</table>
</form>
<?php
}
?>