<?php
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2015 Sangoma Technologies.
//
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
$heading = $amp_conf['DASHBOARD_FREEPBX_BRAND'] . ' ' . _("Extensions");

$ls_ext = \FreePBX::Printextensions()->getSections(false, true);
?>
<div class="container-fluid">
	<h1><?php echo $heading?></h1>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-9 list-extensions">
				<div class="fpbx-container">
					<div class="display full-border">
						<div class="row holder">
							<div class="col-sm-12">
							<?php 
								foreach ($ls_ext as $k => $v)
								{
									$sidediv[] = array('id'=> $v['id'] , 'title' => $v['title'], 'items' => $v['items']);

									echo '<div class="row" id="'.$v['id'].'">';
									echo '	<h3>'.$v['title'].'</h3>';
									echo '	<ul class="list-group">';
									if (count($v['items']) == 0) {
										echo '<li class="list-group-item col-sm-6"><b>'._("Empty").'</b></li>';
									}
									else {
										foreach ($v['items'] as $item) {
											echo sprintf('<li class="list-group-item col-sm-6"><b>%s</b> - %s</li>', $item[1], $item[0]);
										}
									}
									echo '	</ul>';
									echo '	<br/><br/>';
									echo '</div>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav hidden-print">
				<div class="row">
					<ul class="list-group">
						<li class="list-group-item list-group-item-info">
							<h2><?php echo _("Group Extensions to Print"); ?></h2>
						</li>
						<?php
							foreach($ls_ext as $seclectitem)
							{
								echo '<li class="list-group-item">';
								echo sprintf('<input type="checkbox" value="%1$s" name="module_%1$s" id="module_%1$s" class="disp_filter" checked=""><label id="lab_%1$s" name="lab_%1$s" for="%1$s">%2$s</label>', $seclectitem['id'], $seclectitem['title']);
								echo '</li>';
							}
						?>

						<li class="list-group-item list-group-item-success">
							<a href="#" class="btn btn-block" id="btnPrintPdf">
								<i class="fa fa-file-pdf-o"></i>&nbsp;<?php echo _("Download PDF")?>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>