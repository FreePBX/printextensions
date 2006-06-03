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
$gresults = extensions_allusers();
?>

<div class="content">
<?php 

if (!$extdisplay) {
	echo '<br><h2>'._("Company Directory").'</h2><table border=0>';
	}

if (isset($gresults)) {
        foreach ($gresults as $gresult) {
                $defined = is_array($set_users) ? (in_array($gresult[0], $set_users) ? "(edit)" : "(add)") : "add";
                echo "<tr><td>$gresult[1]</td><td>($gresult[0])</td></tr>";

        }
}
?>
</table>
</div>
