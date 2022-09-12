<?php
namespace FreePBX\modules;
/*
 * Class stub for BMO Module class
 * In _Construct you may remove the database line if you don't use it
 * In getActionbar change "modulename" to the display value for the page
 * In getActionbar change extdisplay to align with whatever variable you use to decide if the page is in edit mode.
 *
 */

use FreePBX_Helpers;
use BMO;

include __DIR__ . '/vendor/autoload.php';
require('PDF.php');

class Printextensions extends \FreePBX_Helpers implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->content = "";
		$this->hooks = $freepbx->Hooks();
		$this->initConfigDefault();
	}
	public function install() {
		$this->initConfigDefault();
	}
	public function uninstall() {}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {
	}

	public function showPage($page, $params = array())
	{
		global $amp_conf;
		$data = array(
			"printextensions" => $this,
			'request'	 	  => $_REQUEST,
			'page' 		 	  => $page,
			'amp_conf' 		  => $amp_conf,
			'brand'			  => $amp_conf['DASHBOARD_FREEPBX_BRAND'],
			'config'		  => $this->getConfigAll(),
		);
		$data = array_merge($data, $params);

		switch ($page) 
		{
			case "printextensions":
				$data['ls_ext'] = $this->getSections(false, true);
				$data['heading'] = sprintf(_("%s Extensions"), $data['brand']);
				$data_return = load_view(__DIR__."/views/page.extensions.list.php", $data);
			break;

			default:
				$data_return = sprintf(_("Page Not Found (%s)!!!!"), $page);
		}
		return $data_return;
	}

	public function ajaxRequest($req, &$setting)
	{
		// ** Allow remote consultation with Postman **
		// ********************************************
		// $setting['authenticate'] = false;
		// $setting['allowremote'] = true;
		// return true;
		// ********************************************
		switch($req)
		{
			case "getPdf":
			case "settings_set":
			case "settings_set_default":
				return true;
			break;
		}
		return false;
	}

	public function ajaxCustomHandler()
	{
		switch($_REQUEST['command']) {
			case "getPdf":
				if (! isset($_REQUEST['names'])) {
					http_response_code(400);
				}
				else if($_SERVER['REQUEST_METHOD'] !== 'POST')
				{
					http_response_code(405);
				}
				else
				{
					http_response_code(200);

					$names = explode(",", $_REQUEST['names']);
					$pdf = $this->generatePdf($names);
					
					$pdf->Output('I','freepbx-extensions.pdf', true);	//Open Pdf
					//$pdf->Output('D','freepbx-extensions.pdf', true); //Force Download
				}
				exit();
			break;
		}
	}

	public function ajaxHandler()
	{
		$command = isset($_REQUEST['command']) ? trim($_REQUEST['command']) : '';

		switch ($command)
		{
			case "getPdf":
				exit();
				break;

			case "settings_set":
				$settings = isset($_REQUEST['settings']) ? $_REQUEST['settings'] : NULL;
				if ( empty($settings) )
				{
					$data_return = array("status" => false, "message" => _("Missing data!"));
				}
				else
				{
					if ($this->setConfigAll($settings))
					{
						$data_return = array("status" => true, "message" => _("Successful Update"));
					} 
					else
					{
						$data_return = array("status" => false, "message" => _("Update process failed!"));
					}
				}
				break;

			case "settings_set_default":
				if ($this->setConfigAll($this->getConfigDefault()))
				{
					$data_return = array("status" => true, "message" => _("Successful Update"));
				} 
				else
				{
					$data_return = array("status" => false, "message" => _("Update process failed!"));
				}
				break;

			defualt:
				$data_return = array("status" => false, "message" => _("Command not found!"), "command" => $command);
		}
		return $data_return;
	}

	public function getConfigDefault()
	{
		global $amp_conf;
		return array(
			'header_title' => sprintf(_("%s Extensions"), $amp_conf['DASHBOARD_FREEPBX_BRAND']),
			'header_all_pages' => 'Y',
			'date_format' => 'F j, Y',
			'align_date' => 'R',
			'align_pagination' => 'L',
		);
	}

	public function initConfigDefault()
	{	
		$data = $this->getAll('config');
		if (empty($data)) {
			$this->setConfigAll($this->getConfigAll());
		}
	}

	public function getConfigAll()
	{
		$data_def = $this->getConfigDefault();

		// Get data by DB
		$data = $this->getAll('config');

		// Add options default if not exist
		foreach ($data_def as $key => $val)
		{
			if (! array_key_exists($key, $data))
			{
				$data[$key] = $val;
			}
		}

		// Check value and set default if is not correct value
		foreach ($data as $key => &$val)
		{
			$set_default = false;
			switch($key) {
				case 'header_title':
					if (trim($val) === "") { $set_default = true; }
					break;
				case 'header_all_pages':
					if (! in_array( trim(strtoupper($val)), array("Y", "N"))) { $set_default = true; }
					break;

				case 'date_format':
					$now = date_create()->format($val);
					if ((trim($val) === "") || (trim($now) === "")) { $set_default = true; }
					break;

				case 'align_date':
				case 'align_pagination':
					if (! in_array( trim(strtoupper($val)), array("L", "C", "R"))) { $set_default = true; }
					break;
			}

			if ($set_default) {
				$val = $data_def[$key];
				// Save setting value fixed
				$this->setConfigOpt($key, $val);
			}
		}
		return $data;
	}

	public function getConfigOpt($opt = "")
	{
		$config 	 = $this->getConfigAll();
		$data_return = null;

		if ((! is_null($opt)) && (trim($opt) === ""))
		{
			$opt_fix = strtolower(trim($opt));
			if (array_key_exists($opt_fix, $config)) {
				$data_return = $config[$opt_fix];
			}
		}
		return $data_return;
	}

	public function setConfigAll($data = array())
	{
		$data_def = $this->getConfigDefault();
		foreach ($data as $key => $val)
		{
			$set_default = false;
			switch($key) {
				case 'header_title':
					if (trim($val) === "") { $set_default = true; }
					break;
				case 'header_all_pages':
					if (! in_array( trim(strtoupper($val)), array("Y", "N"))) { $set_default = true; }
					break;

				case 'date_format':
					$now = date_create()->format($val);
					if ((trim($val) === "") || (trim($now) === "")) { $set_default = true; }
					break;

				case 'align_date':
				case 'align_pagination':
					if (! in_array( trim(strtoupper($val)), array("L", "C", "R"))) { $set_default = true; }
					break;
			}

			if ($set_default) {
				$val = $data_def[$key];
			}

			$this->setConfigOpt($key, $val);
		}
		return true;
	}

	public function setConfigOpt($key = null, $val = null) {
		$this->setConfig($key, $val, "config");
	}


	


	public function getSections($sidebar=false, $show_all = false, $sort = true) {
		$sections = array();
		$sidediv = array();
		$users = $this->FreePBX->Core->listUsers(true);
		$ret = array(
			'title'    => _("Users"),
			'textdesc' => _('User'),
			'numdesc'  => _('Extension'),
			'items'    => array(),
		);
		
		foreach ($users as $user) {
			$ret['items'][] = array($user[1],$user[0]);
		}
		$sections['Users'] = $ret;
		$hookdata = $this->hooks->processHooks();
		
		foreach ($hookdata as $key => $value) {
			$sections[$key] = $value;
		}
		foreach ($sections as $k => $v)
		{
			if ((count($v['items']) == 0) && ($show_all == false)) {
				// Skip section if no results
				continue;
			}
			$id = strtolower($k);
			if($sidebar == true) {
				$sidediv[] = array('id'=> $id , 'title' => $v['title']);
			} else {
				$items = $v['items'];
				if ($sort) {
					usort($items, array($this, 'sort_by_orden'));
				}
				$sidediv[] = array('id'=> $id , 'title' => $v['title'], 'items' => $items);
			}
		}
		return $sidediv;
	}

	private function sort_by_orden ($a, $b) {
		$int_a = preg_replace('/[^0-9]/', '', $a[1]);
		$int_b = preg_replace('/[^0-9]/', '', $b[1]);
		return (is_numeric($int_a) ? $int_a : 0) - (is_numeric($int_b) ? $int_b : 0);
	}

	function generatePdf($names = array())
	{
		//Info: http://www.fpdf.org/

		$ls_ext = $this->getSections(false, true);
		$conf = $this->getConfigAll();

		$pdf = new \FreePBX\modules\Printextensions\PDF();
		
		$pdf->title 			= $conf['header_title'];
		$pdf->header_all_pages 	= $conf['header_all_pages'] == "Y" ? true : false;
		$pdf->date_format 		= $conf['date_format'];
		$pdf->align_date 		= $conf['align_date'];
		$pdf->align_pagination 	= $conf['align_pagination'];


		$pdf->AliasNbPages();
		$pdf->AddPage();
		$pdf->SetFont('Arial');

		$max_width = 0;
		foreach ($ls_ext as $k => $v)
		{
			if (! in_array($v['id'], $names))
			{
				continue;
			}		
			if (count($v['items']) == 0)
			{
				continue;
			}

			$pdf->SetFont('','B', 12);
			foreach ($v['items'] as $item)
			{
				if (trim($item[1]) == "") {continue;}
				$new_max = $pdf->GetStringWidth($item[1]);
				if ($new_max > $max_width) {
					$max_width = $new_max;
				}
			}
		}
		if ($max_width > 0) {
			$max_width += 5;
		}

		foreach ($ls_ext as $k => $v)
		{
			if (! in_array($v['id'], $names)) { 
				continue;
			}		
			$pdf->WriteTextUTF8($v['title'], 16, 'B', '', 7);

			$pdf->SetFont('','', 12);
			if (count($v['items']) == 0)
			{
				$pdf->WriteTextUTF8(_("Empty"), '' , 'B', '', 9);
			}
			else
			{
				foreach ($v['items'] as $item)
				{
					if (trim($item[1]) == "") {continue;}

					$pdf->SetFont('','B');
					$pdf->Cell($max_width, 7, $item[1], 0);
					$pdf->SetFont('','');
					$pdf->MultiCell(0, 7, $pdf->fix_utf8($item[0]), 0, 'J');
				}
			}
			$pdf->Ln();
		}
		return $pdf;
	}
}