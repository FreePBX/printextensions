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
	echo "<br /><a href=\"config.php?type=tool&display=printextensions&quietmode=on\" target=\"_blank\"><b>Printer Friendly</b></a>\n";
}


if (!$extdisplay) {
	echo '<br><h2>'._("PBX Extension Layout").'</h2><table border="0" width="95%">';
	echo "<tr width=90%><td align=left><b>Name</b></td><td width=\"10%\" align=\"center\"><b>Extension</b></td></tr>";
	//echo "<tr><td colspan=\"3\"><hr noshade /></td></tr>";
	
}

global $active_modules;
$full_list = framework_check_extension_usage(true);
foreach ($full_list as $key => $value) {
	echo "<tr colspan=\"2\" width='100%'><td><br /><strong>".$active_modules[$key]['name']."</strong></td></tr>";
	foreach ($value as $exten => $item) {
		$description = explode(":",$item['description'],2);
		echo "<tr width=\"90%\"><td>".(trim($description[1])==''?$exten:$description[1])."</td><td width=\"10%\" align=\"right\">".$exten."</td></tr>";
	}
};

?>
</table>
</div>
