<?php
namespace FreePBX\modules;
/*
 * Class stub for BMO Module class
 * In _Construct you may remove the database line if you don't use it
 * In getActionbar change "modulename" to the display value for the page
 * In getActionbar change extdisplay to align with whatever variable you use to decide if the page is in edit mode.
 *
 */

class Printextensions implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->content = "";
	}
	public function install() {}
	public function uninstall() {}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {
	}
	public function getSections(){
		$sections = array();
		$users = \FreePBX::Core()->listUsers(true);
		$ret = array();
		$ret['title'] = _("Users");
		$featurecodes = \featurecodes_getAllFeaturesDetailed();
		$ret['textdesc'] = _('User');
    	$ret['numdesc'] = _('Extension');
    	$ret['items'] = array();
    	foreach ($users as $user) {
    		$ret['items'][] = array($user[1],$user[0]);
    	}
		$sections[] = $ret;
		$hookdata = \FreePBX::Hooks()->processHooks();
		foreach ($hookdata as $key => $value) {
			$sections[] = $value;
		}
		$html .= '<div class="row holder">';
		$html .= '<div class="col-sm-12">';
		foreach ($sections as $k => $v){
			$html .= '<div class="row">';
			$html .= '<h3>'.$v['title'].'</h3>';
			$html .= '<ul class="list-group">';
			foreach ($v['items'] as $item) {
				$html .= '	<li class="list-group-item col-sm-6"><b>'.$item[1].'</b> - '.$item[0].'</li>';
			}
			$html .= '</ul>';
			$html .= '<br/>';
			$html .= '	</div>';
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
