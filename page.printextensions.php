<?php		
//	License for all code of this FreePBX module can be found in the license file inside the module directory
//	Copyright 2015 Sangoma Technologies.
//
if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }
$heading = $amp_conf['DASHBOARD_FREEPBX_BRAND'] . ' ' . _("Extensions");

?>
<style>
@media print {
  @page {
    size: 330mm 427mm;
    margin: 14mm;
  }
  .container {
    width: 1170px;
  }
  .footer {
  	display: none;
  }
}
</style>
<div class="container-fluid">
	<h1><?php echo $heading?></h1>
	<div class = "display full-border">
		<div class="row">
			<div class="col-sm-9">
				<div class="fpbx-container">
					<div class="display full-border">
						<?php echo \FreePBX::Printextensions()->getSections();?>
					</div>
				</div>
			</div>
			<div class="col-sm-3 hidden-xs bootnav hidden-print">
				<div class="list-group">
					 <a href="#" class="list-group-item clickable" id="pedl"><i class="fa fa-file-pdf-o"></i>&nbsp;<?php echo _("Download PDF")?></a>
					 <a href="#" class="list-group-item clickable" onClick="window.print()"><i class="fa fa-print"></i>&nbsp;<?php echo _("Print")?></a>
				</div>
			</div>
		</div>
	</div>
</div>
