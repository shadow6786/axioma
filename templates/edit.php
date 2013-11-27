<div class="span9" >
<div class="headline no-margintop"><h3>Edit record</h3></div>

<?php     
echo form_open(current_url(),array('class' => 'form-horizontal')); 

if (strlen($custom_error) > 0) {?>
<div class="alert alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
   <strong>Notice:</strong> Please check the validations before you save the record!<br />
<?php echo $custom_error; ?>
</div>
	<?php } ?>	
    
{primary}
{forms_inputs}
<hr class="dottedhr" />
	<div class="pull-right">
    <a class="btn" href="<?= base_url('admin/{controller_name_l}/') ?>">Back</a>
    <?php echo form_reset( 'clear', 'Reset','class="btn"'); ?>
		<?php echo form_submit( 'submit', 'Save','class="btn "'); ?>
         
    </div>

<?php echo form_close(); ?>
</div>