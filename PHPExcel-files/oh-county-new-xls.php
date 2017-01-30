<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Chicago');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once( dirname(__FILE__) . '/../Classes/PHPExcel.php' );


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


$countyform = $_POST["countyform"];
$email = $_POST["email"];

$r = explode("|", $countyform);

$wksheetarr = explode("|", $_POST['formlist']);
$wksheetarrcount = 0;
foreach ($wksheetarr as $ws) {

				$myWorkSheet = new PHPExcel_Worksheet($objPHPExcel, $ws);
				// Attach the “My Data” worksheet as the first worksheet in the PHPExcel object
				$objPHPExcel->addSheet($myWorkSheet, $wksheetarrcount);
				$wksheetarrcount = $wksheetarrcount + 1;
}
$thecount = 1;
$wscount = -1;
$numinprogress = false;
foreach ($r as $mb) {
	//var_dump($mb[0]);
	//echo $mb . "<br />";

	if (strlen($mb) > 0) {
		if (substr($mb,0,1) != "Q") {
			if(substr($mb,0,3) == "YTD") {
				$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount,'=SUM(B' . $thecount . ':E' . $thecount . ')');
				// $yarr = explode(":", $mb);
				// $objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount, $yarr[1]);
				$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('F' . $thecount)->getFont()->setBold(true);
				$thecount = $thecount + 1;
			} elseif (substr($mb,0,7) == "ODH-CHC") {
				$wscount = $wscount + 1;
				$thecount = 1;
				// $objPHPExcel->setActiveSheetIndex($wscount);
				// var_dump( $objPHPExcel->setActiveSheetIndex($wscount) );
				// echo "<br /><br /><br /><br />";
			} else {

				$mb = str_replace("<strong>", "", $mb);
				$mb = str_replace("</strong>", "", $mb);
				$mb = str_replace("<br />", "", $mb);
				$mb = str_replace('<strong style="font-size:12pt;">', '', $mb);
				if (substr($mb,0,8) == "SECTION_") {
					$mb = str_replace("SECTION_", "", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('B' . $thecount, 'Q1');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('C' . $thecount, 'Q2');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('D' . $thecount, 'Q3');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('E' . $thecount, 'Q4');
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('F' . $thecount, 'YTD');
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('A' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('B' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('C' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('D' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('E' . $thecount)->getFont()->setBold(true);
					$objPHPExcel->setActiveSheetIndex($wscount)->getStyle('F' . $thecount)->getFont()->setBold(true);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,9) == "TEXTAREA_") {
					$mb = str_replace("TEXTAREA_", "", $mb);
					$mb = str_replace(":", ":  ", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,5) == "TEXT_") {
					$mb = str_replace("TEXT_", "", $mb);
					$mb = str_replace(":", ":  ", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				} elseif (substr($mb,0,5) == "HTML_") {
					$mb = str_replace("HTML_", "", $mb);
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					if (strpos($mb,'progress') !== false) {
						$numinprogress = true;
						//$thecount = $thecount + 1;
					}


				} else {
					$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('A' . $thecount, $mb);
					$thecount = $thecount + 1;
				}
			}
		} elseif(substr($mb,0,2) == "Q1") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('B' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q2") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('C' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q3") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('D' . $thecount, $Qnum);
		} elseif(substr($mb,0,2) == "Q4") {
			$Qnum = substr($mb, 3);
			$objPHPExcel->setActiveSheetIndex($wscount)->setCellValue('E' . $thecount, $Qnum);
			if ($numinprogress == true) {
				$thecount = $thecount + 1;
				$numinprogress = false;
			}
		}
	}
}

$letterArray = array('B','C','D','E');

foreach ($letterArray as $letter) {
	//Active Living Sum of Number Impacted
	$AL = 0;
	$i = 4;
	do {
		$AL = $AL + $objPHPExcel->setActiveSheetIndex(1)->getCell($letter . $i)->getValue();
		$i = $i + 5;

	} while ($i < 115);
	$AL = $AL + $objPHPExcel->setActiveSheetIndex(1)->getCell($letter . '120')->getValue();
	//$objPHPExcel->setActiveSheetIndex(1)->setCellValue($letter . '131', $AL);

	//Healthy Eating Sum of Number Impacted
	$HE = 0;
	$h = 6;
	do {
		$HE = $HE + $objPHPExcel->setActiveSheetIndex(2)->getCell($letter . $h)->getValue();
		$h = $h + 5;

	} while ($h < 27);
	$j = 32;
	do {
		$HE = $HE + $objPHPExcel->setActiveSheetIndex(2)->getCell($letter . $j)->getValue();
		$j = $j + 5;

	} while ($j < 133);
	//$objPHPExcel->setActiveSheetIndex(2)->setCellValue($letter . '143', $HE);

	//Tobacco Sum of Number Impacted
	$TB = 0;
	$k = 4;
	do {
		$TB = $TB + $objPHPExcel->setActiveSheetIndex(3)->getCell($letter . $k)->getValue();
		$k = $k + 5;

	} while ($k < 35);
	$TB = $TB + $objPHPExcel->setActiveSheetIndex(3)->getCell($letter . '40')->getValue();
	//$objPHPExcel->setActiveSheetIndex(3)->setCellValue($letter . '51', $TB);

	//Supplemental Sum of Number Impacted
	$SP = 0;
	$p = 4;
	do {
		$SP = $SP + $objPHPExcel->setActiveSheetIndex(4)->getCell($letter . $p)->getValue();
		$p = $p + 5;

	} while ($p < 15);

	//$objPHPExcel->setActiveSheetIndex(4)->setCellValue($letter . '17', $SP);
}


	//Get sum Total Impacted and put on General tab.
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A51', 'Number Impacted (ALL TABS):');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B51', 'Q1');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C51', 'Q2');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D51', 'Q3');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E51', 'Q4');
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F51', 'YTD');
	$objPHPExcel->setActiveSheetIndex(0)->getStyle('A51:F51')->getFont()->setBold(true);

	$q1 = $objPHPExcel->setActiveSheetIndex(1)->getCell('B126')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('B133')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('B51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('B17')->getValue();
	$q2 = $objPHPExcel->setActiveSheetIndex(1)->getCell('C126')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('C133')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('C51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('C17')->getValue();
	$q3 = $objPHPExcel->setActiveSheetIndex(1)->getCell('D126')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('D133')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('D51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('D17')->getValue();
	$q4 = $objPHPExcel->setActiveSheetIndex(1)->getCell('E126')->getValue() + $objPHPExcel->setActiveSheetIndex(2)->getCell('E133')->getValue() + $objPHPExcel->setActiveSheetIndex(3)->getCell('E51')->getValue() + $objPHPExcel->setActiveSheetIndex(4)->getCell('E17')->getValue();
	$ytdsum = $q1 + $q2 + $q3 + $q4;

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B52', $q1);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C52', $q2);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D52', $q3);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E52', $q4);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F52', '=SUM(B52:E52)');

	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A39', 'Total Q1: ' . $q1);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A40', 'Total Q2: ' . $q2);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A41', 'Total Q3: ' . $q3);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A42', 'Total Q4: ' . $q4);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A43', 'Total YTD: ' . $ytdsum);


	//Write total sum of Impacts for county and save into Impact Summary Excel file.
	//cc_ohio_save_impact_summary($_POST["county"], $objPHPExcel->setActiveSheetIndex(0)->getCell('F52')->getValue());



	//Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$xlsname = str_replace(" ", "_", $_POST["county"]);

	$sheetIndex = $objPHPExcel->getIndex($objPHPExcel-> getSheetByName('Worksheet'));
	$objPHPExcel->removeSheetByIndex($sheetIndex);

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $xlsname . '.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;


