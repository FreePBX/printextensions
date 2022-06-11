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
		$this->core = $freepbx->Core();
		$this->hooks = $freepbx->Hooks();
	}
	public function install() {}
	public function uninstall() {}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {
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

	public function ajaxHandler() {}

	public function getSections($sidebar=false, $show_all = false, $sort = true) {
		$sections = array();
		$sidediv = array();
		$users = $this->core->listUsers(true);
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

		$pdf = new \FreePBX\modules\Printextensions\PDF();
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