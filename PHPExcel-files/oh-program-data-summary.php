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








	//Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	$xlsname = str_replace(" ", "_", $_POST["county"]);



	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save("ohio_cnty_files/" . $xlsname . ".xls");
	exit;


