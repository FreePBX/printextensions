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
</div>

<?php 
$gresults = printextensions_allusers();
?>

<div class="content">
<?php
if (isset($_REQUEST['quietmode'])) {
?>
<head>
    <title>freePBX administration</title>
    <meta http-equiv="Content-Type" content="text/html">
    <link href="common/mainstyle.css" rel="stylesheet" type="text/css"> 
    
    <script type="text/javascript" src="common/script.js.php"></script>
    <script type="text/javascript"> 
		<!--
		// Disable browser's Back button on another pg being able to go back to this pg.
		history.forward();
		//-->
    </script> 
<!--[if IE]>
    <style type="text/css">div.inyourface a{position:absolute;}</style>
<![endif]-->
</head>


<body onload="setAllInfoToHideSelects();">

<?php
} else {
?>
<a href="config.php?type=tool&display=printextensions&quietmode=on">Printer Friendly</a>
<?php
 }
?>


<?php 

if (!$extdisplay) {
	echo '<br><h2>'._("Company Directory").'</h2><table border=0 width=400>';
        echo "<tr width=250><td><b>Name</b></td><td width=50 align=right><b>Extension</b></td><td width=100 align=right><b>Assigned 
DID</b></td></tr>";
echo "<tr><td colspan=3><hr noshade></td></tr>";
	
	}

if (isset($gresults)) {
        foreach ($gresults as $gresult) {
		echo "<tr width=250><td>$gresult[1]</td><td width=50 align=right>$gresult[0]</td><td width=100 
align=right>$gresult[2]</td></tr>";
        }
}
?>
</table>
</div>
