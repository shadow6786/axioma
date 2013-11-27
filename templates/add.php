<div id="page-content">
	<h1 id="page-header">Agregar nuevo registro</h1>
	<div class="fluid-container">
		<div class="row-fluid">
			<article class="span12">
				<div class="jarviswidget">
					<header>
						<h2>Complete los campos para agregar nuevos {controller_name_l}</h2>
					</header>
					<div class="inner-space">
						<?php   echo form_open(current_url(),array('class' => "form-horizontal themed")); ?>
							<fieldset>
								{forms_inputs}
								<div class="form-actions">
									<button type="button" class="btn">
										Atras
									</button>
									<button type="reset" class="btn medium btn-danger">
										Cancelar
									</button>
									<button type="submit" class="btn medium btn-primary">
										Guardar
									</button>
								</div>
							</fieldset>
						<?php echo form_close(); ?>
					</div>
				</div>
			</article>
		</div>
	</div>
</div>