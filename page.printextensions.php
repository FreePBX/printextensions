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
$exact = false;
if (isset($_POST['search_pattern'])) {
  if (isset($_POST['exact'])) {
    $search_pattern = $_POST['search_pattern'];
    $exact = true;
  } else if (isset($_POST['bounded'])) {
    $search_pattern = '/^'.$_POST['search_pattern'].'$/';
  } else if (isset($_POST['regex'])) {
    $search_pattern = '/'.$_POST['search_pattern'].'/';
  }
} else {
  $search_pattern = '';
}
if (!$quietmode) {
?>
<br /><br />
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" name="exten_search">
  <input type="hidden" name="display" value="<?php echo $dispnum ?>">
  <input type="hidden" name="type" value="<?php echo $type ?>">
	<table>
		<tr>
    <td class="label" align="right"><a href="#" class="info"><?php echo _("Search:")?><span><?php echo _("You can narrow the list of extensions based on a search criteria. If you search for an exact extension number the page will redirect to the edit page for the given number. You can also do a bounded or unbounded regex search. The bounded search simply encloses you search criteria between a '^' and '$' where as an unbounded one is completely free form. All normal regex patterns are acceptable in your search. So for example, a bounded search of 20\d\d would search for all extensions of the form 20XX. The resulting lists of numbers all contain links to go directly to the edit pages and the Printer Friendly page will reflect the filtered list of numbers.") ?></span></a></td>
			<td class="type"><input name="search_pattern" type="text" size="30" value="<?php echo htmlspecialchars($_POST['search_pattern']);?>" tabindex="<?php echo ++$tabindex;?>"></td>
			<td valign="top">   </td>
			<td valign="top" class="label">
				<input type="submit" name="exact" class="button" value="<?php echo _("Search Exact Exten")?>" tabindex="<?php echo ++$tabindex;?>">
				<input type="submit" name="bounded" class="button" value="<?php echo _("Search Bounded Regex")?>" tabindex="<?php echo ++$tabindex;?>">
				<input type="submit" name="regex" class="button" value="<?php echo _("Search Unbounded Regex")?>" tabindex="<?php echo ++$tabindex;?>">
			</td>
		</tr>
	</table>
</form>
<?php
}

global $active_modules;

$html_txt = '<div class="content">';

if (!$extdisplay) {
	$html_txt .= '<br><h2>'._("FreePBX Extension Layout").'</h2>';
}

$full_list = framework_check_extension_usage(true);

if ($search_pattern != '') {
  $found=0;
  foreach ($full_list as $module => $entries) {
    $this_module = $module;
    foreach (array_keys($entries) as $exten) {
      if (($exact === true && $search_pattern != $exten) || ($exact === false && !preg_match($search_pattern,$exten))) {
        unset($full_list[$module][$exten]);
      } else {
        $found++;
        if ($exact && $found == 1) {
          $found_url = $full_list[$module][$exten]['edit_url'];
        }
      }
      if (!count($full_list[$this_module])) {
        unset($full_list[$this_module]);
      }
    }
  }
}
if ($exact && $found ==1) {
  redirect($found_url);
}

if ($search_pattern != '' && $found == 0) {
  $html_txt .= '<br /><h3>'._("No Matches for the Requested Search").'</h3><br /><br /><br /><br />';
}


foreach ($full_list as $key => $value) {

	$sub_heading_id = $txtdom = $active_modules[$key]['rawname'];
	if ($active_modules[$key]['rawname'] == 'featurecodeadmin' || ($quietmode && !isset($_REQUEST[$sub_heading_id]))) {
		continue; // featurecodes are fetched below
	}
	if ($txtdom == 'core') {
		$txtdom = 'amp';
		$active_modules[$key]['name'] = 'Extensions';
		$core_heading = $sub_heading =  dgettext($txtdom,$active_modules[$key]['name']);
	} else {
		$sub_heading =  dgettext($txtdom,$active_modules[$key]['name']);
	}
	$module_select[$sub_heading_id] = $sub_heading;
	$textext = _("Extension");
	$html_txt_arr[$sub_heading] =  "<div class=\"$sub_heading_id\"><table border=\"0\" width=\"75%\"><tr width='90%'><td><br><strong>".sprintf("%s",$sub_heading)."</strong></td><td width=\"10%\" align=\"right\"><br><strong>".$textext."</strong></td></tr>\n";
	foreach ($value as $exten => $item) {
		$description = explode(":",$item['description'],2);
    $label_desc = trim($description[1])==''?$exten:$description[1];
    if ($quietmode) {
      $label_exten = $exten;
    } else {
      $label_exten = "<a href='".$item['edit_url']."'>$exten</a>";
      $label_desc = "<a href='".$item['edit_url']."'>$label_desc</a>";
    }
		$html_txt_arr[$sub_heading] .= "<tr width=\"90%\"><td>$label_desc</td><td width=\"10%\" align=\"right\">".$label_exten."</td></tr>\n";
	}
	$html_txt_arr[$sub_heading] .= "</table></div>";
}

function core_top($a, $b) {
	global $core_heading;

	if ($a == $core_heading) {
		return -1;
	} elseif ($b == $core_heading) {
		return 1;
	} elseif ($a != $b) {
		return $a < $b ? -1 : 1;
	} else {
		return 0;
	}
}

if (is_array($html_txt_arr)) uksort($html_txt_arr, 'core_top');
if (!$quietmode) {
	//asort($module_select);
	if (is_array($module_select)) uasort($module_select, 'core_top');
}

// Now, get all featurecodes.
//
$sub_heading_id =  'featurecodeadmin';
if ((!$quietmode || isset($_REQUEST[$sub_heading_id])) && isset($full_list['featurecodeadmin'])) {
	$featurecodes = featurecodes_getAllFeaturesDetailed(false);
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

    if ($search_pattern != '') {
      if (!isset($full_list['featurecodeadmin'][$thiscode])) {
        continue;
      }
    }

		$txtdom = $item['modulename'];
		// if core then get translations from amp
		if ($txtdom == 'core') {
			$txtdom = 'amp';
		}
		textdomain($txtdom);
		if ($featureena && $moduleena) {
      $label_desc = sprintf(dgettext($txtdom,$item['featuredescription']));
      if (!$quietmode) {
        $thiscode = "<a href='config.php?type=setup&display=featurecodeadmin'>$thiscode</a>";
        $label_desc = "<a href='config.php?type=setup&display=featurecodeadmin'>$label_desc</a>";
      }
			$html_txt_arr[$sub_heading] .= "<tr width=\"90%\"><td>$label_desc</td><td width=\"10%\" align=\"right\">".$thiscode."</td></tr>\n";
		}
	}
}
$html_txt_arr[$sub_heading] .= "</table></div>";
$html_txt .= implode("\n",$html_txt_arr);

if (!$quietmode && ($search_pattern == '' || $found > 0)) {
	$rnav_txt = '<div class="rnav"><form name="print" action="'.$_SERVER['PHP_SELF'].'" target="_blank" method="post">';
  $rnav_txt .= '<input type="hidden" name="quietmode" value="on">';
  $rnav_txt .= '<input type="hidden" name="display" value="'.$dispnum.'">';
  $rnav_txt .= '<input type="hidden" name="type" value="'.$type.'">';
  if ($search_pattern != '') {
    $rnav_txt .= '<input type="hidden" name="search_pattern" value="'.$_POST['search_pattern'].'">';
    if (isset($_POST['exact'])) {
      $rnav_txt .= '<input type="hidden" name="exact" value="'.$_POST['exact'].'">';
    } else if (isset($_POST['bounded'])) {
      $rnav_txt .= '<input type="hidden" name="bounded" value="'.$_POST['bounded'].'">';
    } else if (isset($_POST['regex'])) {
      $rnav_txt .= '<input type="hidden" name="regex" value="'.$_POST['regex'].'">';
    }
  }

  $rnav_txt .= '<ul>';
	if (is_array($module_select)) foreach ($module_select as $id => $sub) {
		$rnav_txt .= "<li><input type=\"checkbox\" value=\"$id\" name=\"$id\" id=\"$id\" class=\"disp_filter\" CHECKED /><label id=\"lab_$id\" name=\"lab_$id\" for=\"$id\">$sub</label></li>\n";
	}
	$rnav_txt .= "</ul><hr><div style=\"text-align:center\"><input type=\"submit\" value=\"".sprintf(dgettext('printextensions',_("Printer Friendly Page")))."\" /></div>\n";
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
