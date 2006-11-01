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


$gresults = printextensions_allusers();
?>

<div class="content">
<?php
if (!$quietmode) {
	echo "<a href=\"config.php?type=tool&display=printextensions&quietmode=on\" target=\"_blank\">Printer Friendly</a>\n";
}


if (!$extdisplay) {
	echo '<br><h2>'._("Company Directory").'</h2><table border="0" width="500">';
	echo "<tr width=250><td align=left><b>Name</b></td><td width=\"50\" align=\"center\"><b>Extension</b></td><td width=\"200\" align=\"center\"><b>Assigned DID</b></td></tr>";
	echo "<tr><td colspan=\"3\"><hr noshade /></td></tr>";
	
}

if (isset($gresults)) {
		foreach ($gresults as $gresult) {
			$defined = is_array($set_users) ? (in_array($gresult[0], $set_users) ? "(edit)" : "(add)") : "add";
			echo "<tr width=\"250\"><td>".$gresult[1]."</td><td width=\"50\" align=\"right\">".$gresult[0]."</td><td width=\"200\" align=\"right\">".$gresult[2]."</td></tr>";
		}
}
?>
</table>
<p><a href="http://aussievoip.com.au/wiki/freePBX-PrintExtensions">Print Extensions v1.3</a></p>
</div>
