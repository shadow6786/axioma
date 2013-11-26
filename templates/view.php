<?php // Change the css classes to suit your needs    
$attributes = array('class' => '', 'id' => '');
echo form_open('my_form', $attributes); ?>

<div class="control-group">
<label class="control-label" for="inputEmail">Email</label>
<div class="controls">
<input type="text" id="inputEmail" class="border-radius-none" placeholder="Email">
</div>
</div>



<label for="first_name">First Name <span class="required">*</span></label>
<div class="controls">
<?php echo form_error('first_name'); ?>
<br /><input id="first_name" type="text" name="first_name" maxlength="200" value="<?php echo set_value('first_name'); ?>"  />
</div>
</div>



<div class="control-group">
<label for="last_name">Last Name <span class="required">*</span></label>
<?php echo form_error('last_name'); ?>
<br /><input id="last_name" type="text" name="last_name" maxlength="200" value="<?php echo set_value('last_name'); ?>"  />
</div>
</div>
                        

        <?php echo form_submit( 'submit', 'Submit'); ?>

<?php echo form_close(); ?>
