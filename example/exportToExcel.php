<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

$date = $_POST["date"];
$conn = mysqli_connect("192.168.177.20:3307", "german", "german", "ECS"); 
if (!$conn) {
	die("ERROR: Could not connect. " .mysqli_connect_error()); 
} 
//$query = 'SELECT * FROM ecs_loadtrack WHERE mt between timestamp(("'.$date.'"), maketime(8,0,0)) and timestamp(("'.$date.'"), maketime(8,0,0)) + interval 1 day ORDER BY mt AND lotno IS NOT NULL;';
//$query = 'SELECT * FROM ecs_loadtrack WHERE mt between timestamp(date(concat(year(date("$date")), '-', month(date("$date")), '-', 1)), maketime(8,0,0)) and timestamp(("$date"), maketime(8,0,0)) + interval 1 day ORDER BY mt AND lotno IS NOT NULL;';
$query = "
SELECT * FROM ecs_loadtrack WHERE mt between
timestamp(date(concat(year(date('$date')), '-', month(date('$date')), '-', 1)), maketime(8,0,0))
and
timestamp(('$date'), maketime(8,0,0)) + interval 1 day
AND lotno IS NOT NULL
ORDER BY machine_no, mt;
";



$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'MACHINE');
$sheet->setCellValue('B1', 'STAFF ID');
$sheet->setCellValue('C1', 'CHECKED BY');
$sheet->setCellValue('D1', 'LOT NUMBER');
$sheet->setCellValue('E1', 'LEFT');
$sheet->setCellValue('F1', 'LEFT STATUS');
$sheet->setCellValue('G1', 'RIGHT');
$sheet->setCellValue('H1', 'RIGHT STATUS');
$sheet->setCellValue('I1', 'COMMENTS');
$sheet->setCellValue('J1', 'DATE & TIME');
$sheet->setCellValue('K1', 'FINAL STATUS');
$sheet->setCellValue('L1', 'AI_JUDGE');



foreach (range('A','K') as $col) {
	// if ($col == "E" || $col == "G") {
	// 	$sheet->getColumnDimension($col)->setWidth(30);	
	// 	$sheet->getStyle("A:K")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	// } else if ($col == "J") {
	// 	$sheet->getColumnDimension($col)->setWidth(18);
	// 	$sheet->getStyle("A:K")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	// } else {
	// 	$sheet->getColumnDimension($col)->setWidth(15);
	// 	$sheet->getStyle("A:K")->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
	// }
	$sheet->getColumnDimension($col)->setAutoSize(true);
	$sheet->getStyle($col)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
}


$res = $conn->query($query);
if ($res->num_rows > 0) {
	$i = 2;
	while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
		// $sheet->getRowDimension($i)->setRowHeight(100);
		// $leftImage = new Drawing();
		// $rightImage = new Drawing();
		// $leftImage->setWorksheet($sheet);
		// $rightImage->setWorksheet($sheet);

		// $leftImage->setName('Left Image')->setDescription('Left Image')->setPath("../loadtrack/".$row["filepath1"])->setCoordinates("E".$i)->setHeight(125)->setOffsetX(5)->setOffsetY(5);
		// $rightImage->setName("Right Image")->setDescription("Right Image")->setPath("../loadtrack/".$row["filepath2"])->setCoordinates("G".$i)->setHeight(125)->setOffsetX(5)->setOffsetY(5);

		$sheet->setCellValue("A".$i, $row["machine_no"]);
		$sheet->setCellValue("B".$i, $row["id_no"]);
		$sheet->setCellValue("C".$i, $row["checked_by"]);
		$sheet->setCellValue("D".$i, $row["lotno"]);
		$sheet->setCellValue("E".$i, "Click Here to Open Left Image");
		$sheet->getCell('E'.$i)->getHyperlink()->setUrl("//192.168.177.20/web/loadtrack/".$row["filepath1"]);
		$sheet->getStyle('E'.$i)->getFont()->setUnderline(true);
		$sheet->getStyle('E'.$i)->getFont()->getColor()->setRGB("00afff");
		$sheet->setCellValue("F".$i, $row["machine_status1"]);
		$sheet->setCellValue("G".$i, "Click Here to Open Right Image");
		$sheet->getCell('G'.$i)->getHyperlink()->setUrl("//192.168.177.20/web/loadtrack/".$row["filepath2"]);
		$sheet->getStyle('G'.$i)->getFont()->setUnderline(true);
		$sheet->getStyle('G'.$i)->getFont()->getColor()->setRGB("00afff");
		$sheet->setCellValue("H".$i, $row["machine_status2"]);
		$sheet->setCellValue("I".$i, $row["textarea"]);
		$sheet->setCellValue("J".$i, $row["mt"]);
		$sheet->setCellValue("K".$i, $row["confirmation"]);
		$sheet->setCellValue("L".$i, $row["ai_judge"]);
		$i++;
	}
}

ob_end_clean();
$filename = date("d-m-Y")."_".date("H:i:s")."_loadTrackData.xlsx";
header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');


mysqli_close($conn);
?>