<?php
namespace FreePBX\modules\Printextensions;

if (!defined('FREEPBX_IS_AUTH')) { die('No direct script access allowed'); }

include __DIR__ . '/vendor/autoload.php';

class PDF extends \FPDF
{
	var $B=0;
	var $I=0;
	var $U=0;
	var $HREF='';
	var $ALIGN='';

	var $title 				= "Extensions";
	var $header_all_pages 	= true;
	var $date_format 		= "F j, Y";
	var $align_date 		= "R";
	var $align_pagination 	= "C";

	function WriteHTML($html)
	{
		//HTML parser
		$html=str_replace("\n",' ',$html);
		$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				//Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				elseif($this->ALIGN=='center')
					$this->Cell(0,5,$e,0,1,'C');
				else
					$this->Write(5,$e);
			}
			else
			{
				//Tag
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					//Extract properties
					$a2=explode(' ',$e);
					$tag=strtoupper(array_shift($a2));
					$prop=array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$prop[strtoupper($a3[1])]=$a3[2];
					}
					$this->OpenTag($tag,$prop);
				}
			}
		}
	}

	function OpenTag($tag,$prop)
	{
		//Opening tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF=$prop['HREF'];
		if($tag=='BR')
			$this->Ln(5);
		if($tag=='P')
			$this->ALIGN=$prop['ALIGN'];
		if($tag=='HR')
		{
			if( !empty($prop['WIDTH']) )
				$Width = $prop['WIDTH'];
			else
				$Width = $this->w - $this->lMargin-$this->rMargin;
			$this->Ln(2);
			$x = $this->GetX();
			$y = $this->GetY();
			$this->SetLineWidth(0.4);
			$this->Line($x,$y,$x+$Width,$y);
			$this->SetLineWidth(0.2);
			$this->Ln(2);
		}
	}

	function CloseTag($tag)
	{
		//Closing tag
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF='';
		if($tag=='P')
			$this->ALIGN='';
	}

	function SetStyle($tag,$enable)
	{
		//Modify style and select corresponding font
		$this->$tag+=($enable ? 1 : -1);
		$style='';
		foreach(array('B','I','U') as $s)
			if($this->$s>0)
				$style.=$s;
		$this->SetFont('',$style);
	}

	function PutLink($URL,$txt)
	{
		//Put a hyperlink
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}

	
	function Header()
	{
		if (! $this->header_all_pages) {
			if ($this->PageNo() != 1) {
				return;
			}
		}
		
		global $amp_conf;
		// $this->Image($amp_conf['BRAND_IMAGE_TANGO_LEFT'], 10, 8, 22);
		$this->Image($amp_conf['BRAND_IMAGE_FREEPBX_FOOT'], 5, 8, 50);
		
		$this->SetFont('Arial','B', 22);
		$this->Cell(0,10, $this->title,0,0,'R');
		$this->Ln(20);
		
	}

	function Footer()
	{
		$this->SetY(-15);
		$this->SetFont('Arial','I',8);
		$this->Cell(0,10, sprintf( _('Page %s/%s'), $this->PageNo(), '{nb}') ,0,0, $this->align_pagination);
		$this->Cell(0,10, date($this->date_format)  ,0,0, $this->align_date);
	}


	function WriteTextUTF8($text, $size='', $style='', $font='', $size_newline='')
	{
		if ( empty($font) && empty($style) && ! empty($size))
		{
			$this->SetFontSize($size);
		}
		else if (! empty($font) || ! empty($style))
		{
			$this->SetFont($font, $style, $size);
		}
		$this->Write(5, $this->fix_utf8($text));
		if (! is_null($size_newline)) {
			if ($size_newline === '')
			{
				$this->Ln();
			}
			else
			{
				$this->Ln($size_newline);
			}
		}
	}

	function fix_utf8($txt) {
		$txt = iconv('UTF-8', 'ISO-8859-1',  $txt);
		return $txt;
	}

}
?>