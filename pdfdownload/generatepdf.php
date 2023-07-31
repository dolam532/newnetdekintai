<?php
include "../pdfdownload/tcpdf.php";

$tcpdf = new TCPDF("P", "mm", "A4", true, "UTF-8");
$tcpdf->SetPrintHeader(false);
$tcpdf->SetLeftMargin(10); // Set the left margin to 0
$tcpdf->AddPage();

$name = json_decode($_POST['name'], true);
$dept = json_decode($_POST['dept'], true);
$date_show = json_decode($_POST['date_show'], true);
$template = json_decode($_POST['template'], true);
$data = json_decode($_POST['data'], true);
$workmonth_list = json_decode($_POST['workmonth_list'], true);

// Define your CSS styles
$style_bold = 'font-weight: 700;';

// Title
$tcpdf->SetFont("kozgopromedium", "U", 12); // Set the font, style, and size for the title
$tcpdf->writeHTMLCell(0, 8, '', '', '<span style="' . $style_bold . '">' . substr($date_show, 0, 4) . '年' . substr($date_show, 5, 2) . '月 勤務表' . '</span>', 0, 1, false, true, 'C');

// Text in the top left corner
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the text
$tcpdf->SetXY(10, 18); // Set the X and Y position for the text
$tcpdf->Cell(0, 7, 'ガナシス株式会社 御中', 0, 1, 'L'); // Output the text aligned to the left
$tcpdf->SetFont("kozgopromedium", "U", 10);
$tcpdf->Cell(0, 7, '所属：' . $dept . '                                ', 0, 1, 'L'); // Output the text aligned to the left
$tcpdf->Cell(0, 7, '氏名：' . $name . '                   (印)', 0, 1, 'L'); // Output the text aligned to the left

// Table
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the table cells
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the table cells
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the table cells
$tcpdf->SetLineWidth(0.2); // Set the line width for the table borders

$tcpdf->SetXY(140, 18); // Set the X and Y position for the table
$tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->Cell(30, 7, '社長', 1, 0, 'C', true); // Output the first cell with background color
$tcpdf->Cell(30, 7, '担当', 1, 1, 'C', true); // Output the second cell with background color

$tcpdf->SetXY(140, 25); // Set the X and Y position for the table
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->Cell(30, 18, '', 1, 0, 'C', true); // Output the third cell with background color
$tcpdf->Cell(30, 18, '', 1, 1, 'C', true); // Output the fourth cell with background color
$tcpdf->Ln(1.2);

// Table header
$tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the header
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border


if ($template == "1") {
	$tcpdf->Cell(20, 7, '日付', 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
	$tcpdf->Cell(40, 7, '業務時間', 1, 0, 'C', true);
	$tcpdf->Cell(30, 7, '休憩時間', 1, 0, 'C', true);
	$tcpdf->Cell(30, 7, '間就業時間', 1, 0, 'C', true);
	$tcpdf->Cell(40, 7, '業務内容', 1, 0, 'C', true);
	$tcpdf->Cell(30, 7, '備考', 1, 1, 'C', true); // Add 1 to move to the next line
} elseif ($template == "2") {
	$tcpdf->Cell(20, 7, '日付', 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
	$tcpdf->Cell(25, 7, '出退社時刻', 1, 0, 'C', true);
	$tcpdf->Cell(25, 7, '業務時間', 1, 0, 'C', true);
	$tcpdf->Cell(25, 7, '休憩時間', 1, 0, 'C', true);
	$tcpdf->Cell(25, 7, '間就業時間', 1, 0, 'C', true);
	$tcpdf->Cell(40, 7, '業務内容', 1, 0, 'C', true);
	$tcpdf->Cell(30, 7, '備考', 1, 1, 'C', true); // Add 1 to move to the next line
}

// Table data
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
foreach ($data as $row) {
	if ($row["template"] == "1") {
		$tcpdf->Cell(20, 6.8, $row["date"], 1, 0, 'C', true);
		$tcpdf->Cell(20, 6.8, $row["jobstarthh"] . ':' . $row["jobstartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(20, 6.8, $row["jobendhh"] . ':' . $row["jobendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $row["offtimehh"] . ':' . $row["offtimemm"], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $row["workhh"] . ':' . $row["workmm"], 1, 0, 'C', true);
		$tcpdf->Cell(40, 6.8, $row["comment"], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $row["bigo"], 1, 1, 'C', true); // Add 1 to move to the next line
	} elseif ($row["template"] == "2") {
		$tcpdf->Cell(20, 6.8, $row["date"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["daystarthh"] . ':' . $row["daystartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["dayendhh"] . ':' . $row["dayendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["jobstarthh"] . ':' . $row["jobstartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["jobendhh"] . ':' . $row["jobendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(25, 6.8, $row["offtimehh"] . ':' . $row["offtimemm"], 1, 0, 'C', true);
		$tcpdf->Cell(25, 6.8, $row["workhh"] . ':' . $row["workmm"], 1, 0, 'C', true);
		$tcpdf->Cell(40, 6.8, $row["comment"], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $row["bigo"], 1, 1, 'C', true); // Add 1 to move to the next line
	}
}
$tcpdf->Ln(1.2);

// Set up the table header
$tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the header
$tcpdf->Cell(25, 13.6, '実働時間', 1, 0, 'C', true);
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
if (!empty($workmonth_list)) {
	foreach ($workmonth_list as $key) {
		$tcpdf->Cell(20, 13.6, $key['jobhour'] . ':' . $key['jobminute'], 1, 0, 'C', true);
	}
} else {
	$tcpdf->Cell(20, 13.6, ':', 1, 0, 'C', true);
}
$tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->Cell(25, 13.6, '勤務状況', 1, 0, 'C', true);
$tcpdf->Cell(30, 6.8, '所定勤務日数', 1, 0, 'C', true);
$tcpdf->Cell(30, 6.8, '日実勤務日数', 1, 0, 'C', true);
$tcpdf->Cell(15, 6.8, '休暇', 1, 0, 'C', true);
$tcpdf->Cell(15, 6.8, '欠勤', 1, 0, 'C', true);
$tcpdf->Cell(15, 6.8, '遲刻', 1, 0, 'C', true);
$tcpdf->Cell(15, 6.8, '早退', 1, 1, 'C', true); // Add 1 to move to the next line

// Set up the table data
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data
if (!empty($workmonth_list)) {
	foreach ($workmonth_list as $key) {
		$tcpdf->Cell(70, 6.8, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $key['jobdays'], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $key['workdays'], 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $key['holydays'], 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $key['offdays'], 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $key['delaydays'], 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $key['earlydays'], 1, 1, 'C', true);
	}
} else {
	$tcpdf->Cell(70, 6.8, '', 0, 0, 'C', false);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 1, 'C', true);
}

$tcpdf->Output("download.pdf", "I");
