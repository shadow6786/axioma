<div class="buttons">   
    <a href="<?= base_url('panel/{folder}/{controller_name_l}/add') ?>">
        <div id="create_new_clubs" class="btn btn-primary pull-right">
            <i class="icon-plus"></i> Agregar {controller_name_l}
        </div>
    </a>
</div>
<?php
if(!$results){
	echo '<h3>No Data</h3>';
} else {
	$header = array_keys($results[0]);
    for($i=0; $i<count($results); $i++){
        $id = array_values($results[$i]);
        $results[$i]['Editar']   = anchor(base_url().'panel/{folder}/{controller_name_l}/edit/'.$id[0],'Editar');
        $results[$i]['Eliminar'] = anchor(base_url().'panel/{folder}/{controller_name_l}/delete/'.$id[0],'Eliminar',array('onClick'=>'return deletechecked(\' '.base_url().'panel/{folder}/{controller_name_l}/delete/'.$id[0].' \')'));                                          
		array_shift($results[$i]);                        
    }

    $clean_header = clean_header($header);
    array_shift($clean_header);

    $tmpl = array ( 
        'table_open'        => '<table id="table_{controller_name_l}" class="table table-striped table-bordered table-hover">',
        'heading_row_start' => '<tr>',
        'heading_row_end'   => '<th>Editar</th><th>Eliminar</th></tr>',
        'row_start'         => '<tr>',
        'row_end'           => '</tr>',
        'cell_start'        => '<td>',
        'cell_end'          => '</td>',
        'row_alt_start'     => '<tr>',
        'row_alt_end'       => '</tr>',
        'cell_alt_start'    => '<td>',
        'cell_alt_end'      => '</td>',
        'table_close'       => '</table>'
    );

    $this->table->set_template($tmpl);
    $this->table->set_empty("&nbsp;");
    $this->table->set_heading($clean_header); 
    // view
    echo $this->table->generate($results); 
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#table_{controller_name_l}').dataTable();
    });
</script>