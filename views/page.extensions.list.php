<div class="container-fluid">
	<h1><?php echo _("Print Extensions") ?></h1>
	<div class = "display full-border">
		<div class="row printextension_header">
			<div class="col-sm-12">
				<?php 
					$data = array(
						'ls_ext' => $ls_ext,
						'heading' => $heading,
						'config' => $config,
					);
					echo load_view(__DIR__."/view.extensions.header.php", $data); 
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 list-extensions">
				<?php
					$data = array(
						'ls_ext' => $ls_ext,
					);
					echo load_view(__DIR__."/view.extensions.list.php", $data);
				?>
			</div>
		</div>
	</div>
</div>