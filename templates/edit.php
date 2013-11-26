<div class="row">
	<div class="span12" >
	<div class="headline no-margintop">
        <h3 class="short_headline">
            <span>Editar registro</span>
        </h3>
    </div>
	<?php echo form_open(current_url(),array('class' => 'form-horizontal')); ?>
		<?php if (strlen($custom_error) > 0) : ?>
            <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>Aviso:</strong> Por favor revisa los campos validados antes de poder guardar el registro.<br />
            </div>
        <?php endif; ?>
		{primary}
		{forms_inputs}
		<hr class="dottedhr" />
		<div>
			<a class="btn" href="<?= base_url('panel/{folder}/{controller_name_l}/') ?>">Volver</a>
			<?php echo form_reset( 'clear', 'Resetear','class="btn"'); ?>
			<?php echo form_submit( 'submit', 'Guardar','class="btn btn-primary"'); ?>
		</div>
	<?php echo form_close(); ?>
	</div>
</div>