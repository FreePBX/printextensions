<?php /* $Id */
// Copyright (C) 2008 Philippe Lindheimer & Bandwidth.com (plindheimer at bandwidth dot com)
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation, version 2
// of the License.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

$dispnum = 'printextensions';
global $active_modules;

$html_txt = '<div class="content">';

if (!$extdisplay) {
	$html_txt .= '<br><h2>'._("FreePBX Extension Layout").'</h2><table border="0" width="75%">';
	$html_txt .= "<tr width=90%><td align=left><b>"._("Name")."</b></td><td width=\"10%\" align=\"right\"><b>"._("Extension")."</b></td></tr></table>\n";
}

$full_list = framework_check_extension_usage(true);
foreach ($full_list as $key => $value) {

	$sub_heading_id = $txtdom = $active_modules[$key]['rawname'];
	if ($active_modules[$key]['rawname'] == 'featurecodeadmin' || ($quietmode && !isset($_REQUEST[$sub_heading_id]))) {
		continue; // featurecodes are fetched below
	}
	if ($txtdom == 'core') {
		$txtdom = 'amp';
	}
	$sub_heading =  dgettext($txtdom,$active_modules[$key]['name']);
	$module_select[$sub_heading_id] = $sub_heading;
	$sub_heading .=  " "._("Extensions");
	$html_txt_arr[$sub_heading] =  "<div class=\"$sub_heading_id\"><table border=\"0\" width=\"75%\"><tr colspan=\"2\" width='100%'><td><br /><strong>".sprintf("%s",$sub_heading)."</strong></td></tr>\n";
	foreach ($value as $exten => $item) {
		$description = explode(":",$item['description'],2);
		$html_txt_arr[$sub_heading] .= "<tr width=\"90%\"><td>".(trim($description[1])==''?$exten:$description[1])."</td><td width=\"10%\" align=\"right\">".$exten."</td></tr>\n";
	}
	$html_txt_arr[$sub_heading] .= "</table></div>";
}

// Now, get all featurecodes.
//
$sub_heading_id =  'featurecodeadmin';
if (!$quietmode || isset($_REQUEST[$sub_heading_id])) {
	$featurecodes = featurecodes_getAllFeaturesDetailed();
	$sub_heading =  dgettext($txtdom,$active_modules['featurecodeadmin']['name']);
	$module_select[$sub_heading_id] = $sub_heading;
	$html_txt_arr[$sub_heading] =  "<div class=\"$sub_heading_id\"><table border=\"0\" width=\"75%\"><tr colspan=\"2\" width='100%'><td><br /><strong>".sprintf("%s",$sub_heading)."</strong></td></tr>\n";
	foreach ($featurecodes as $item) {
		$bind_domains = array();
		if (isset($bind_domains[$item['modulename']]) || (extension_loaded('gettext') && is_dir("modules/".$item['modulename']."/i18n"))) {
			if (!isset($bind_domains[$item['modulename']])) {
				$bind_domains[$item['modulename']] = true;
				bindtextdomain($item['modulename'],"modules/".$item['modulename']."/i18n");
				bind_textdomain_codeset($item['modulename'], 'utf8');
			}
		}
		$moduleena = ($item['moduleenabled'] == 1 ? true : false);
		$featureena = ($item['featureenabled'] == 1 ? true : false);
		$featurecodedefault = (isset($item['defaultcode']) ? $item['defaultcode'] : '');
		$featurecodecustom = (isset($item['customcode']) ? $item['customcode'] : '');
		$thiscode = ($featurecodecustom != '') ? $featurecodecustom : $featurecodedefault;
		$thismodena = ($moduleena != '') ? $featurecodecustom : $featurecodedefault;
		$txtdom = $item['modulename'];
		// if core then get translations from amp
		if ($txtdom == 'core') {
			$txtdom = 'amp';
		}
		textdomain($txtdom);
		if ($featureena && $moduleena) {
			$html_txt_arr[$sub_heading] .= "<tr width=\"90%\"><td>".sprintf(dgettext($txtdom,$item['featuredescription']))."</td><td width=\"10%\" align=\"right\">".$thiscode."</td></tr>\n";
		}
	}
}
$html_txt_arr[$sub_heading] .= "</table></div>";
ksort($html_txt_arr);
$html_txt .= implode("\n",$html_txt_arr);

if (!$quietmode) {
	asort($module_select);
	$rnav_txt = '<div class="rnav"><form name="print" action="'.$_SERVER['PHP_SELF'].'?quietmode=on&'.$_SERVER['QUERY_STRING'].'" target=\"_blank\" method="post"><ul>';
	foreach ($module_select as $id => $sub) {
		$rnav_txt .= "<li><input type=\"checkbox\" value=\"$id\" name=\"$id\" id=\"$id\" class=\"disp_filter\" CHECKED /><label id=\"lab_$id\" name=\"lab_$id\" for=\"$id\">$sub</label></li>\n";
	}
	$rnav_txt .= "</ul><hr><div style=\"text-align:center\"><input type=\"submit\" value=\""._("Printer Friendly Page")."\" /></div>\n";
	echo $rnav_txt;
?>
	<script language="javascript">
	<!-- Begin

	$(document).ready(function(){
		$(".disp_filter").click(function(){
			$("."+this.id).slideToggle();
		});
	});

	// End -->
	</script>
	</form></div>
<?php
}
echo $html_txt."</div>";
?>
