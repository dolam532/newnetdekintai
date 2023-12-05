<?php
include "../pdfdownload/tcpdf.php";



$tcpdf = new TCPDF("P", "mm", "A4", true, "UTF-8");
$tcpdf->SetPrintHeader(false);
$tcpdf->SetLeftMargin(10); // Set the left margin to 0
$tcpdf->AddPage();

// Image file path

$signstamp_admin = json_decode($_POST['signstamp_admin'], true);
$signstamp_kanri = json_decode($_POST['signstamp_kanri'], true);


$signstamp_user = json_decode($_POST['signstamp_user'], true);
// var_dump($signstamp_kanri);

// Assuming $signstamp_data contains HTML code, including an image tag.
$signstamp_admin_ = '<img src="../assets/uploads/signstamp/' . $signstamp_admin . '" width="40" height="40" />';
$signstamp_kanri_ = '<img src="../assets/uploads/signstamp/' . $signstamp_kanri . '" width="40" height="40" />';
$signstamp_user_ = '<img src="../assets/uploads/signstamp/' . $signstamp_user . '" width="40" height="40" />';

// Set the X and Y coordinates for the cell
$x_user = 56;
$y_user = 29;
$x_StampMark = 60;
$y_StampMark = 33;

$x_name = 20;
$y_name = 33;

$x_dept = 20;
$y_dept = 26;


$x_admin = 105;
$y_admin = 27;
$x_kanri = 177;
$y_kanri = 27;

// Width and height for the cell
$w = 100;
$h = 50;

// Border and newline settings
$border = 0;
$ln = 1;

// Align the content to the left
$align = 'L';

// set turn of next page and set margin 
$tcpdf->SetAutoPageBreak(false, 10);

$name = json_decode($_POST['name'], true);
$dept = json_decode($_POST['dept'], true);
$date_show = json_decode($_POST['date_show'], true);
$template = json_decode($_POST['template'], true);
$data = json_decode($_POST['data'], true);
$workmonth_list = json_decode($_POST['workmonth_list'], true);
$companyName = json_decode($_POST['companyName'], true);
$submission_status = json_decode($_POST['submission_status'], true);

//set output file name
$fileOutputName = str_replace(' ', '', $name).'_'. substr($date_show, 0, 4) .  substr($date_show, 5, 2).'_勤務表'.'_.pdf';



// top
$totalworkhh_top = str_pad((string) json_decode($_POST['totalworkhh_top'], true), 2, '0', STR_PAD_LEFT);
$totalworkmm_top = str_pad((string) json_decode($_POST['totalworkmm_top'], true), 2, '0', STR_PAD_LEFT);
$totaldayhh_top = str_pad((string) json_decode($_POST['totaldayhh_top'], true), 2, '0', STR_PAD_LEFT);
$totaldaymm_top = str_pad((string) json_decode($_POST['totaldaymm_top'], true), 2, '0', STR_PAD_LEFT);
$cnprejob_top = json_decode($_POST['cnprejob_top'], true);
$cnactjob_top = json_decode($_POST['cnactjob_top'], true);
$holydayswork_top = json_decode($_POST['holydayswork_top'], true);
$offdayswork_top = json_decode($_POST['offdayswork_top'], true);
$delaydayswork_top = json_decode($_POST['delaydayswork_top'], true);
$earlydayswork_top = json_decode($_POST['earlydayswork_top'], true);


// bottom
$totalworkhh = str_pad((string) json_decode($_POST['totalworkhh_bottom'], true), 2, '0', STR_PAD_LEFT);
$totalworkmm = str_pad((string) json_decode($_POST['totalworkmm_bottom'], true), 2, '0', STR_PAD_LEFT);
$totaldayhh = str_pad((string) json_decode($_POST['totaldayhh'], true), 2, '0', STR_PAD_LEFT);
$totaldaymm = str_pad((string) json_decode($_POST['totaldaymm'], true), 2, '0', STR_PAD_LEFT);
$cnprejob = json_decode($_POST['cnprejob_bottom'], true);
$cnactjob = json_decode($_POST['cnactjob_bottom'], true);
$holydayswork = json_decode($_POST['holydayswork_bottom'], true);
$offdayswork = json_decode($_POST['offdayswork_bottom'], true);
$delaydayswork = json_decode($_POST['delaydayswork_bottom'], true);
$earlydayswork = json_decode($_POST['earlydayswork_bottom'], true);

// Define your CSS styles
$style_bold = 'font-weight: 700;';

// Title
$tcpdf->SetFont("kozgopromedium", "U", 18); // Set the font, style, and size for the title
$tcpdf->writeHTMLCell(0, 8, '', '', '<span style="' . $style_bold . '">' . substr($date_show, 0, 4) . '年' . substr($date_show, 5, 2) . '月 勤務表' . '</span>', 0, 1, false, true, 'C');



$blankInName = str_repeat(' ', 45);
// Text in the top left corner
$tcpdf->SetFont("kozgopromedium", "B", 12); // Set the font and style for the text
$tcpdf->SetXY(10, 18); // Set the X and Y position for the text
$tcpdf->Cell(0, 7, $companyName.' 御中', 0, 1, 'L'); // Output the text aligned to the left
$tcpdf->SetFont("kozgopromedium", "U", 10);
$tcpdf->Cell(0, 7, '所属：' . $blankInName. '', 0, 1, 'L'); // Output the text aligned to the left

$textInMark = '(印)';
$showName = mb_convert_kana($name, 'R', 'UTF-8');






$tcpdf->Cell(0, 7, '氏名：' . $blankInName , 0, 0.3, 'L');
$tcpdf->SetFont("kozgopromedium", "", 10);


//dept $dept 
$tcpdf->writeHTMLCell($w, $h, $x_dept, $y_dept, $dept, $border, $ln, 0, true, $align);
// name 
$tcpdf->writeHTMLCell($w, $h, $x_name, $y_name, $showName, $border, $ln, 0, true, $align);
// (印)
$tcpdf->writeHTMLCell($w, $h, $x_StampMark, $y_StampMark, $textInMark, $border, $ln, 0, true, $align);
if($submission_status > 0) {
// 印鑑
$tcpdf->writeHTMLCell($w, $h, $x_user, $y_user, $signstamp_user_, $border, $ln, 0, true, $align);
}

// Table
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the table cells
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the table cells
$tcpdf->SetFont("kozgopromedium", "", 12); // Set the font and style for the table cells
$tcpdf->SetLineWidth(0.2); // Set the line width for the table borders

$tcpdf->SetXY(140, 18); // Set the X and Y position for the table
// $tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->Cell(30, 7, '責任者', 1, 0, 'C', true); // Output the first cell with background color
$tcpdf->Cell(30, 7, '担当者', 1, 1, 'C', true); // Output the second cell with background color

$tcpdf->SetXY(140, 25); // Set the X and Y position for the table
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->Cell(30, 17, '', 1, 0, 'C');
$tcpdf->Cell(30, 17, '', 1, 1, 'C');

$signstamp_admin_show = '';
$signstamp_kanri_show = '';

if($submission_status > 1) {
	$signstamp_kanri_show = $signstamp_kanri_ ;
} 
if($submission_status > 2) {
	$signstamp_admin_show = $signstamp_admin_ ;
} 

$tcpdf->writeHTMLCell($w, $h, $x_admin, $y_admin, $signstamp_admin_show, $border, $ln, 0, true, 'C');
$tcpdf->writeHTMLCell($w, $h, $x_kanri, $y_kanri, $signstamp_kanri_show, $border, 0, 0, true, $align);

$tcpdf->Ln(22);

// Table header
// $tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->SetFont("kozgopromedium", "", 12); // Set the font and style for the header
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border

if ($template == "1") {
	$tcpdf->Cell(20, 7, '日付', 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
	$tcpdf->Cell(40, 7, '業務時間', 1, 0, 'C', true);
	$tcpdf->Cell(20, 7, '休憩時間', 1, 0, 'C', true);
	$tcpdf->Cell(20, 7, '就業時間', 1, 0, 'C', true);
	$tcpdf->Cell(60, 7, '業務内容', 1, 0, 'C', true);
	$tcpdf->Cell(30, 7, '備考', 1, 1, 'C', true); // Add 1 to move to the next line
} elseif ($template == "2") {
	$tcpdf->Cell(16, 7, '日付', 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
	$tcpdf->Cell(25, 7, '出退社時間', 1, 0, 'C', true);
	$tcpdf->Cell(25, 7, '業務時間', 1, 0, 'C', true);
	$tcpdf->Cell(18, 7, '休憩時間', 1, 0, 'C', true);
	$tcpdf->Cell(18, 7, '就業時間', 1, 0, 'C', true);
	$tcpdf->Cell(60, 7, '業務内容', 1, 0, 'C', true);
	$tcpdf->Cell(28, 7, '備考', 1, 1, 'C', true); // Add 1 to move to the next line
}

// Table data
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
// $tcpdf->SetTextColor(255, 255, 0); // Set the text color for the data rows

$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border

function getColorForDate($date , $isHolyday) {
    if (strpos($date, '日') !== false) {
        return array(255, 0, 0); // Red
    } elseif (strpos($date, '土') !== false) {
        return array(0, 0, 255); // Blue
    } elseif ($isHolyday) {
		return array(255, 0, 0); // Red
    } else {
        return array(40, 40, 40); // Black (default)
    }
}


foreach ($data as $row) {
	if ($row["template"] == "1") {

		$cellColor = getColorForDate($row["date"] , $row["isHoliday"]);
		$tcpdf->SetFont("kozgopromedium", "B", 10);
		$tcpdf->SetTextColor($cellColor[0], $cellColor[1], $cellColor[2]); 
		$tcpdf->Cell(20, 6.8, $row["date"], 1, 0, 'C', true);


		$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
		$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
		$tcpdf->SetFont("kozgopromedium", "", 10);
		$tcpdf->SetLineWidth(0.2);


		$tcpdf->Cell(20, 6.8, 	formatHour($row["jobstarthh"]). ':' . $row["jobstartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(20, 6.8, 		formatHour($row["jobendhh"]). ':' . $row["jobendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(20, 6.8, 	formatHour($row["offtimehh"]). ':' . $row["offtimemm"], 1, 0, 'C', true);

		// formatHour($row["offtimehh"])
		$workhh = $row["workhh"];
		$workmm = $row["workmm"];
		if (empty($workhh) && empty($workmm)) {
			$workTime = '';
		} else {
			$workTime = sprintf('%2d:%02d', $workhh, $workmm);
		}
		$tcpdf->Cell(20, 6.8, $workTime, 1, 0, 'C', true);

		$tcpdf->Cell(60, 6.8, $row["comment"], 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $row["bigo"], 1, 1, 'C', true); // Add 1 to move to the next line
	} elseif ($row["template"] == "2") {
		$cellColor = getColorForDate($row["date"] ,  $row["isHoliday"]);

		$tcpdf->SetTextColor($cellColor[0], $cellColor[1], $cellColor[2]); 
		$tcpdf->Cell(16, 6.8, $row["date"], 1, 0, 'C', true);


		$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
		$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
		$tcpdf->SetFont("kozgopromedium", "", 10);
		$tcpdf->SetLineWidth(0.2);


		$tcpdf->Cell(12.5, 6.8, $row["daystarthh"] . ':' . $row["daystartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["dayendhh"] . ':' . $row["dayendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["jobstarthh"] . ':' . $row["jobstartmm"], 1, 0, 'C', true);
		$tcpdf->Cell(12.5, 6.8, $row["jobendhh"] . ':' . $row["jobendmm"], 1, 0, 'C', true);
		$tcpdf->Cell(18, 6.8, $row["offtimehh"] . ':' . $row["offtimemm"], 1, 0, 'C', true);

		$workhh = $row["workhh"];
		$workmm = $row["workmm"];
		if (empty($workhh) && empty($workmm)) {
			$workTime = '';
		} else {
			$workTime = sprintf('%02d:%02d', $workhh, $workmm);
		}
		$tcpdf->Cell(18, 6.8, $workTime, 1, 0, 'C', true);

		$tcpdf->Cell(60, 6.8, $row["comment"], 1, 0, 'C', true);
		$tcpdf->Cell(28, 6.8, $row["bigo"], 1, 1, 'C', true); // Add 1 to move to the next line
	}
}
$tcpdf->Ln(5);



// Set up the table header
$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->Cell(45, 6.8, '実働時間', 1, 0, 'C', true);


$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 12); // Set the font and style for the header


$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
$tcpdf->Cell(25, 20.4, '勤務状況', 1, 0, 'C', true);
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
	if ($template == "1") {
		// top

		$tcpdf->Cell(45, 6.8, formatHour($totalworkhh_top) . ':' . $totalworkmm_top, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob_top, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork_top, 1, 1, 'C', true);

		// bottom
		$tcpdf->Cell(45, 6.8, formatHour($totalworkhh). ':' . $totalworkmm, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork, 1, 1, 'C', true);
	} elseif ($template == "2") {
		// top 
		$tcpdf->Cell(45, 6.8, 	formatHour($totaldayhh_top) . ':' . $totaldaymm_top, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob_top, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork_top, 1, 1, 'C', true);
		// bottom
		$tcpdf->Cell(45, 6.8,		formatHour($totalworkhh). ':' . $totalworkmm, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork, 1, 1, 'C', true);
	}
} else {
	$tcpdf->Cell(45, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 1, 'C', true);

	$tcpdf->Cell(45, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(30, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 0, 'C', true);
	$tcpdf->Cell(15, 6.8, '', 1, 1, 'C', true);
}

function formatHour($hours)
{
        if (strlen($hours) > 1 && substr($hours, 0, 1) === '0') {
                return substr($hours, 1);
        } else {
                return $hours;
        }
}


$tcpdf->Output($fileOutputName, "I");
