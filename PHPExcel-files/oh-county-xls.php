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

// Read the template file
$objPHPexcel = PHPExcel_IOFactory::load(dirname(__FILE__) . '/../Examples/DataSummaryDRAFT.xlsx');

$countyform = $_POST["countyform"];

$result = array();
foreach (explode("|", $countyform) as $c) {
	$result[] = explode(":", $c);
}

$objWorksheet = $objPHPexcel->getSheet(2);
$sheet2arr = array(5,6,7,9,11,12,13,15,17,18,19,21,23,24,25,27);


//var_dump($result);
$thecount = 0;
foreach ($result as $mb) {
	$thecount = $thecount + 1;
	// $objPHPExcel->setActiveSheetIndex(0)
            // ->setCellValue('A' . $thecount, $mb[0])
            // ->setCellValue('B' . $thecount, $mb[0])
            // ->setCellValue('C' . $thecount, $mb[0])
            // ->setCellValue('D' . $thecount, $mb[0]);

		if ($mb[0] == "Q1") {
			$objWorksheet->getCell('B' . $thecount)->setValue($mb[1]);
		}



}








$objWriter = PHPExcel_IOFactory::createWriter($objPHPexcel, 'Excel5');
$objWriter->save('write.xls');
