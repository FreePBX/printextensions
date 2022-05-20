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

									echo '<div id="'.$v['id'].'">';
									echo '	<h3>'.$v['title'].'</h3>';
									echo '	<ul class="row list-group">';
									if (count($v['items']) == 0) {
										echo '<li class="list-group-item col-sm-12"><b>'._("Empty").'</b></li>';
									}
									else {
										foreach ($v['items'] as $item) {
											echo sprintf('<li class="list-group-item col-sm-12"><b>%s</b> - %s</li>', $item[1], $item[0]);
										}
									}
									echo '	</ul>';
									echo '	<br/>';
									echo '</div>';
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav hidden-print">
				<div class="list-group">
					<a href="#" class="list-group-item clickable" id="btnPrintPdf">
						<i class="fa fa-file-pdf-o"></i>&nbsp;<?php echo _("Download PDF")?>
					</a>
					<!-- <a href="#" class="list-group-item clickable" id="btnPrintList">
						<i class="fa fa-print"></i>&nbsp;<?php echo _("Print")?>
					</a> -->
					<ul>
					<?php
					 	foreach($ls_ext as $seclectitem)
						{
							echo '<li class="list-group-item">';
							echo sprintf('<input type="checkbox" value="%1$s" name="module_%1$s" id="module_%1$s" class="disp_filter" checked=""><label id="lab_%1$s" name="lab_%1$s" for="%1$s">%2$s</label>', $seclectitem['id'], $seclectitem['title']);
							echo '</li>';
					 	}
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>