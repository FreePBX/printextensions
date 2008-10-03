<?php /* $Id: page.printextensions.php 1197 2006-04-26 21:12 KerryG $ */
//Copyright (C) 2006 Kerry Garrison (kgarrison at servicepointe dot net)
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$dispnum = 'printextensions'; //used for switch on config.php

//isset($_REQUEST['action'])?$action = $_REQUEST['action']:$action='';


?>

<div class="content">
<?php
if (!$quietmode) {
	echo "<br /><a href=\"config.php?type=tool&display=printextensions&quietmode=on\" target=\"_blank\"><b>"._("Printer Friendly")."</b></a>\n";
}


if (!$extdisplay) {
	echo '<br><h2>'._("PBX Extension Layout").'</h2><table border="0" width="95%">';
	echo "<tr width=90%><td align=left><b>"._("Name")."</b></td><td width=\"10%\" align=\"right\"><b>"._("Extension")."</b></td></tr>";
}

global $active_modules;
$full_list = framework_check_extension_usage(true);
// get all featurecodes
$featurecodes = featurecodes_getAllFeaturesDetailed();
foreach ($full_list as $key => $value) {
	$txtdom = $active_modules[$key]['rawname'];
	if ($txtdom == 'core') textdomain('amp');
	echo "<tr colspan=\"2\" width='100%'><td><br /><strong>".dgettext($txtdom,sprintf("%s",$active_modules[$key]['name']))."</strong></td></tr>";
	foreach ($value as $exten => $item) {
		$description = explode(":",$item['description'],2);
		// if from featurecodeadmin then skip as we deal with those later
		if ($active_modules[$key]['rawname'] != 'featurecodeadmin')
		    {
		    echo "<tr width=\"90%\"><td>".(trim($description[1])==''?$exten:$description[1])."</td><td width=\"10%\" align=\"right\">".$exten."</td></tr>";
		    }
		}
    }
// Now, get all featurecodes. Code gracefully 'borrowed' from featurecodeadmin
foreach($featurecodes as $item) {
    $moduleena = ($item['moduleenabled'] == 1 ? true : false);
    $featureena = ($item['featureenabled'] == 1 ? true : false);
    $featurecodedefault = (isset($item['defaultcode']) ? $item['defaultcode'] : '');
    $featurecodecustom = (isset($item['customcode']) ? $item['customcode'] : '');
    $thiscode = ($featurecodecustom != '') ? $featurecodecustom : $featurecodedefault;
    $thismodena = ($moduleena != '') ? $featurecodecustom : $featurecodedefault;
    $txtdom = $item['modulename'];
    // if it is from core, we get translations from amp
    if ($txtdom == 'core') textdomain('amp');
    if ($featureena == true && $moduleena == true) 
	 echo "<tr width=\"90%\"><td>".sprintf("%s",dgettext($item['modulename'],$item['featuredescription']))."</td><td width=\"10%\" align=\"right\">".$thiscode."</td></tr>";
};
?>
</table>
</div>
