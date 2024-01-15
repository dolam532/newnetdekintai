<?php
include "../pdfdownload/tcpdf.php";

$tcpdf = new TCPDF("P", "mm", "A4", true, "UTF-8");
$tcpdf->SetPrintHeader(false);
$tcpdf->SetLeftMargin(10); // Set the left margin to 0
$tcpdf->AddPage();


//==================== view datas config start ====================//// 

//-----------// 
//----1.-----// FORM DATAS IN TOP LEFT REGION
//-----------// 

// 提出日付　↓　default  -> get datetime by 印鑑した日付
$teishutsu_date = '31';
$teishutsu_month = '12';
$teishutsu_year  = '2024';
$teishutsu_time = $teishutsu_year.' 年 '.$teishutsu_month.' 月 ' .$teishutsu_date.' 日';

// 所属　　
// $dept = json_decode($_POST['dept'], true);
$dept = '開発';


// 氏名　
// $name = json_decode($_POST['name'], true);
$name = '山田太郎';

// 印鑑　
// $signstamp_user = json_decode($_POST['signstamp_user'], true);
$signstamp_user = '1_aaaa_y8Y5DhIVfoXrdaH2.png';
$signstamp_user_ = '<img src="../assets/uploads/signstamp/' . $signstamp_user . '" width="40" height="40" />';
$signstamp_user_show = '';  

// check accept of user here 
// if($submission_status > 0 && $submission_status < 11) {}
$signstamp_user_show = $signstamp_user_;  


//-----------// 
//----2.-----// FORM DATAS IN TOP RIGHT REGION
//-----------// 

// 責任者印鑑
/// $signstamp_admin = json_decode($_POST['signstamp_admin'], true);
$signstamp_admin = '1_aaaa_y8Y5DhIVfoXrdaH2.png';
$signstamp_admin_ = '<img src="../assets/uploads/signstamp/' . $signstamp_admin . '" width="40" height="40" />';
$signstamp_admin_show = '';

// check accept of sekininsha here 
// if($submission_status > 2 && $submission_status < 11) {}   
$signstamp_admin_show = $signstamp_admin_ ;


// 担当者印鑑
// $signstamp_kanri = json_decode($_POST['signstamp_kanri'], true);
$signstamp_kanri = '1_aaaa_y8Y5DhIVfoXrdaH2.png';
$signstamp_kanri_ = '<img src="../assets/uploads/signstamp/' . $signstamp_kanri . '" width="40" height="40" />';
$signstamp_kanri_show = '';  

// check accept of tantosha here 
// if($submission_status > 1 && $submission_status < 11) {} 
$signstamp_kanri_show = $signstamp_kanri_ ;


//-----------// 
//----3.-----// FORM DATAS IN  期間 Line  Config Data here 
//-----------// 
$kiukaFullFromYear = 3333;
$kiukaFullFromMonth = 33;
$kiukaFullFromDate = 33;

$kiukaFullToYear = 9999;
$kiukaFullToMonth = 99;
$kiukaFullToDate = 99;

$kyukaFullFromDayOfWeek = '月';
$kyukaFullToDayOfWeek = '水';
// this text view on table
$kyukaRangeTextShow = $kiukaFullFromYear.'年 '. $kiukaFullFromMonth.'月 '.$kiukaFullFromDate.'日'.'('.$kyukaFullFromDayOfWeek.')　～　'
						.$kiukaFullToYear.'年 '. $kiukaFullToMonth.'月 '.$kiukaFullToDate.'日'.'('.$kyukaFullToDayOfWeek.')';

// '日' 
$countKyukaRange = 1000 .'日';


//-----------// 
//----4.-----// FORM DATAS IN  半日期間 Line  Config Data here 
//-----------// 

$kiukaHalfYear = '4444'.'年 ';
$kiukaHalfMonth = '44'.'月 ';
$kiukaHalfDate = '44'.'日';
$kiukaHalfFromHour = '44'.'時';
$kiukaHalfToHour = '44'.'時';

$kyukaHalfDayOfWeek = '火';
// this text view on table
$kyukaHalfRangeTextShow = $kiukaHalfYear. $kiukaHalfMonth.$kiukaHalfDate.'('.$kyukaHalfDayOfWeek.')　'
						. $kiukaHalfFromHour. '　～　'.$kiukaHalfToHour;



//-----------// 
//----5.-----// FORM DATAS IN  休暇区分 Line  Config Data here 
//-----------// 

// 　①年次有給休暇　 　②産前産後休暇　　③生理休暇　　④育児休業 　⑤介護休業　
// 　⑥慶弔休暇　⑦代休　　⑧振替休日　⑨特別休暇(　　　                 　　　　　　)　
//   ⑩その他(　                                   　　                 　　　　　　)

$kiukashurui_1 = '①年次有給休暇';
$kiukashurui_2 = '②産前産後休暇';
$kiukashurui_3 = '③生理休暇';
$kiukashurui_4 = '④育児休業';
$kiukashurui_5 = '⑤介護休業　　　                 　　　　　　　　　　　　';
$kiukashurui_6 = '⑥慶弔休暇';
$kiukashurui_7 = '⑦代休';
$kiukashurui_8 = '⑧振替休日';
$kiukashurui_9 = '⑨特別休暇(　　　                 　　　　　　　　　　　　　)';
$kiukashurui_10 = '⑩その他(　                                   　　                 　　　　　　)';

// this text view on table


$kyukaShuruiFullTextShow = $kiukashurui_1.'　'.$kiukashurui_2.'　'.$kiukashurui_3.'　'.$kiukashurui_4.'　'.$kiukashurui_5.'　'."\n"
							.$kiukashurui_6.'　'.$kiukashurui_7.'　'.$kiukashurui_8.'　'.$kiukashurui_9.'　'."\n"
							."$kiukashurui_10";



//-----------// 
//----6.-----// FORM DATAS IN  入社年月 AND  勤続年数 Line  Config Data here 
//-----------// 
$inYear = 66 ;
$inMonth = 66;

$inTimeYear = 69 ;
$inTimeMonth = 69 ;

$inCompanyYMTextShow = $inYear.'年　'.$inMonth.'月　';
$workInCompanyTimeTextShow = $inTimeYear.'年　'.$inTimeMonth.'ヵ月以上　';
							




//-----------// 
//----7.-----// FORM DATAS IN  年次有給休暇当該年度算定期間 Line  Config Data here 
//-----------// 

$annualPaidFromYear = 7777;
$annualPaidFromMonth = 77;
$annualPaidFromDate = 77;

$annualPaidToYear = 7777;
$annualPaidToMonth = 77;
$annualPaidToDate = 77;

// this text view on table
$annualPaidLeaveCalculationPeriodTextShow = $annualPaidFromYear.'年 '. $annualPaidFromMonth.'月 '.$annualPaidFromDate.'日'.'　～　'
						.$annualPaidToYear.'年 '. $annualPaidToMonth.'月 '.$annualPaidToDate.'日';



//-----------// 
//----8.-----// FORM DATAS IN  年次有給休暇残日数 Line  Config Data here 
//-----------//

// from data value
$kyukaCount2Value = 5;
$kyukaCount3Value = 10;
$kyukaCount4Value = 0;
$kyukaCount5Value = 15;
$kyukaCount6Value = 3;

// by excel form auto calculator 
$kyukaCount1Value = $kyukaCount2Value + $kyukaCount3Value;
$kyukaCount1Value = $kyukaCount4Value + $kyukaCount5Value;
$kyukaCount7Value = $kyukaCount4Value + $kyukaCount6Value;
$kyukaCount8Value = $kyukaCount5Value - $kyukaCount6Value;

$kyukaCount1TextShow = $kyukaCount1Value .'日';
$kyukaCount2TextShow = $kyukaCount2Value .'日';
$kyukaCount3TextShow = $kyukaCount3Value .'日';
$kyukaCount4TextShow = $kyukaCount4Value .'日';
$kyukaCount5TextShow = $kyukaCount5Value .'日';
$kyukaCount6TextShow = $kyukaCount6Value .'日';
$kyukaCount7TextShow = "\n$kyukaCount7Value 日";
$kyukaCount8TextShow = "\n$kyukaCount8Value 日";




//====================  view datas config end ====================//// 

//set output file name
$fileOutputName = str_replace(' ', '', $name).'_'. $teishutsu_year.$teishutsu_month.$teishutsu_date .  substr($date_show, 5, 2).'_休暇届'.'_.pdf';







// Set the X and Y coordinates for the cell
$x_user = 56;
$y_user = 36;
$x_StampMark = 60;
$y_StampMark = 40;

$x_name = 20;
$y_name = 40;

$x_dept = 20;
$y_dept = 33;

$x_admin = 105;
$y_admin = 36;
$x_kanri = 177;
$y_kanri = 36;

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

// Define your CSS styles
$style_bold = 'font-weight: 700;';

//===== VIEW DRAWING=====///


// 0. MAIN Title  DRAW
$tcpdf->SetFont("kozgopromedium", "U", 25); // Set the font, style, and size for the title
// $tcpdf->writeHTMLCell(0, 8, '', '', '<span style="' . $style_bold . '">' . substr($date_show, 0, 4) . '年' . substr($date_show, 5, 2) . '月 勤務表' . '</span>', 0, 1, false, true, 'C');
$tcpdf->writeHTMLCell(0, 15, '', '', '<span style="' . $style_bold . '">' .  '休暇届' . '</span>', 0, 1, false, true, 'C');


// 1. TABLE HEADER LEFT DRAW
$blankInName = str_repeat(' ', 45);
// Text in the top left corner
$tcpdf->SetFont("kozgopromedium", "B", 14); // Set the font and style for the text
$tcpdf->SetXY(10, 25); // Set the X and Y position for the text
$tcpdf->Cell(0, 7, $teishutsu_time , 0, 1, 'L'); // Output the text aligned to the left

$tcpdf->SetFont("kozgopromedium", "U", 10);
$tcpdf->Cell(0, 7, '所属：' . $blankInName. '', 0, 1, 'L'); // Output the text aligned to the left

$textInMark = '(印)';
$showName = mb_convert_kana($name, 'R', 'UTF-8');

$tcpdf->Cell(0, 7, '氏名：' . $blankInName , 0, 0.3, 'L');
$tcpdf->SetFont("kozgopromedium", "", 10);


//dept  
$tcpdf->writeHTMLCell($w, $h, $x_dept, $y_dept, $dept, $border, $ln, 0, true, $align);
// name 
$tcpdf->writeHTMLCell($w, $h, $x_name, $y_name, $showName, $border, $ln, 0, true, $align);
// (印)
$tcpdf->writeHTMLCell($w, $h, $x_StampMark, $y_StampMark, $textInMark, $border, $ln, 0, true, $align);
// 印鑑
$tcpdf->writeHTMLCell($w, $h, $x_user, $y_user, $signstamp_user_show, $border, $ln, 0, true, $align);


// 2.TABLE HEADER RIGHT DRAW
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the table cells
$tcpdf->SetTextColor(0, 0, 0); // Set the text color for the table cells
$tcpdf->SetFont("kozgopromedium", "", 12); // Set the font and style for the table cells
$tcpdf->SetLineWidth(0.2); // Set the line width for the table borders

$tcpdf->SetXY(140, 25); // Set the X and Y position for the table
// $tcpdf->SetFillColor(240, 240, 240); // Set the fill color for the header
$tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
$tcpdf->Cell(30, 8, '責任者', 1, 0, 'C', true); // Output the first cell with background color
$tcpdf->Cell(30, 8, '担当者', 1, 1, 'C', true); // Output the second cell with background color

$tcpdf->SetXY(140, 33); // Set the X and Y position for the table
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->Cell(30, 21, '', 1, 0, 'C');
$tcpdf->Cell(30, 21, '', 1, 1, 'C');
$tcpdf->writeHTMLCell($w, $h, $x_admin, $y_admin, $signstamp_admin_show, $border, $ln, 0, true, 'C');
$tcpdf->writeHTMLCell($w, $h, $x_kanri, $y_kanri, $signstamp_kanri_show, $border, 0, 0, true, $align);
$tcpdf->Ln(22);




// Table Body
// 3. 期間 Line 
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->Cell(40, 7, '期間', 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
$tcpdf->Cell(110, 7, $kyukaRangeTextShow, 1, 0, 'C', true); // Add 'LTRB' to draw an outer border for the cell
$tcpdf->Cell(40, 16, $countKyukaRange, 1, 0, 'C', true); // Add 1 to move to the next line
$tcpdf->Ln(7);

// 4. 期間  *半休　Line 

$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$text4 = "期間\n※半休のみ記入"; // Use "\n" to create a new line
$xTmp4 = $tcpdf->GetX();
$yTmp4 = $tcpdf->GetY();
$tcpdf->MultiCell(40, 7, $text4, 1, 'C', true);
$height4 = $tcpdf->GetY() - $yTmp4;
$tcpdf->SetXY($xTmp4 + 40, $yTmp4);
$tcpdf->Cell(110, $height4, $kyukaHalfRangeTextShow, 1, 0, 'C', true);
$tcpdf->Ln(9);


// 5. 休暇区分　Line  
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$text5 = "休暇区分"; // Use "\n" to create a new line
$xTmp5 = $tcpdf->GetX();
$yTmp5 = $tcpdf->GetY();
$tcpdf->Cell(40, 23, $text5, 1, 'C', true);
$height5 = $tcpdf->GetY() - $yTmp5;
$tcpdf->SetXY($xTmp5 + 40, $yTmp5);
$tcpdf->MultiCell(150, $height5+23, $kyukaShuruiFullTextShow, 1, 0 ,   'C', true);
$tcpdf->Ln(0);



// 6. 入社年月　Line
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$text6 = "入社年月"; // Use "\n" to create a new line
$tcpdf->Cell(40, 7, $text6, 1, 'C', true);
$text6 = "入社年月"; // Use "\n" to create a new line

$tcpdf->Cell(55, 7, $inCompanyYMTextShow, 1, 'C', true);
$text6 = "入社年月"; // Use "\n" to create a new line
$tcpdf->Cell(40, 7, '勤続年数', 1, 'C', true);
$text6 = "入社年月"; // Use "\n" to create a new line
$tcpdf->Cell(55, 7, $workInCompanyTimeTextShow, 1, 'C', true);
$tcpdf->Ln(7);



// 7. 年次有給休暇 当該年度算定期間　Line
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$text7 = "年次有給休暇\n当該年度算定期間"; // Use "\n" to create a new line
$xTmp7 = $tcpdf->GetX();
$yTmp7 = $tcpdf->GetY();
$tcpdf->MultiCell(40, 7, $text7, 1, 'C', true);
$height7 = $tcpdf->GetY() - $yTmp7;
$tcpdf->SetXY($xTmp7 + 40, $yTmp7);
$tcpdf->Cell(150, $height7, $annualPaidLeaveCalculationPeriodTextShow, 1, 0, 'C', true);
$tcpdf->Ln(9);


// 8. 年次有給休暇残日数　Line 
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data rows
$tcpdf->SetLineWidth(0.2); // Set the line width for the table border
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$text8 = "\n\n\n\n年次有給休暇残日数"; // Use "\n" to create a new line
$xTmp8 = $tcpdf->GetX();
$yTmp8 = $tcpdf->GetY();
$tcpdf->SetXY($xTmp8, $yTmp8);
$tcpdf->MultiCell(40, 49, $text8, 1, 'C', true);

// line 1 
$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 40, $yTmp8);
$tcpdf->Cell(45, 7, '①総有給休暇数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount1TextShow, 1, 'C', true);

$tcpdf->Cell(45, 7, '④使用済数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount4TextShow, 1, 'C', true);
$tcpdf->Ln(7);

// line 2 
$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 40, $yTmp8+7);
$tcpdf->Cell(45, 7, '②前年度の繰越残日数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount2TextShow, 1, 'C', true);

$tcpdf->Cell(45, 7, '⑤使用前残日数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount5TextShow, 1, 'C', true);
$tcpdf->Ln(7);

// line 3 
$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 40, $yTmp8+14);
$tcpdf->Cell(45, 7, '③当該年度付与日数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount3TextShow, 1, 'C', true);

$tcpdf->Cell(45, 7, '⑥今回使用数', 1, 'C', true);
$tcpdf->Cell(30, 7, $kyukaCount6TextShow, 1, 'C', true);
$tcpdf->Ln(7);
// line 4 5
$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 40, $yTmp8+21);
$tcpdf->MultiCell(45, 28, "\n\n②＋③＝①\n④＋⑤＝①", 1, 'C', true);

$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 85, $yTmp8+21);
$tcpdf->MultiCell(30, 28, "\n\n④＋⑥＝⑦\n⑤－⑥＝⑧\n④＋⑥＋⑧＝①", 1, 'C', true);

$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 115, $yTmp8+21);
$tcpdf->MultiCell(45, 14, "\n⑦使用後済数\n(④＋⑥)", 1, 'C', true);

$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 160, $yTmp8+21);
$tcpdf->MultiCell(30, 14, $kyukaCount7TextShow, 1, 'C', true);
$tcpdf->Ln(7);

// line 6 7
$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 115, $yTmp8+35);
$tcpdf->MultiCell(45, 14, "\n⑧使用後残日数\n(⑤－⑥)", 1, 'C', true);

$height8 = $tcpdf->GetY() - $yTmp8;
$tcpdf->SetXY($xTmp8 + 160, $yTmp8+35);
$tcpdf->MultiCell(30, 14, $kyukaCount8TextShow, 1, 'C', true);
$tcpdf->Ln(7);








// $xTmp8 = $tcpdf->GetX();
// $yTmp8 = $tcpdf->GetY();

// $height5 = $tcpdf->GetY() - $yTmp8;
// $tcpdf->SetXY($xTmp8 + 40, $yTmp8);
// $tcpdf->MultiCell(150, $height8+49, $kyukaShuruiFullTextShow, 1, 0 ,   'C', true);
// $tcpdf->Ln(0);



// $tcpdf->SetTextColor(255, 255, 0); // Set the text color for the data rows



// function getColorForDate($date , $isHolyday) {
//     if (strpos($date, '日') !== false) {
//         return array(255, 0, 0); // Red
//     } elseif (strpos($date, '土') !== false) {
//         return array(0, 0, 255); // Blue
//     } elseif ($isHolyday) {
// 		return array(255, 0, 0); // Red
//     } else {
//         return array(40, 40, 40); // Black (default)
//     }
// }


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



// // Set up the table header  15
// $tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
// $tcpdf->SetTextColor(0, 0, 0); // Set the text color for the header
// $tcpdf->Cell(30, 6.8, '実働時間', 1, 0, 'C', true);


// $tcpdf->SetFillColor(217, 237, 247); // Set the fill color for the header //#d9edf7 water blue(217, 237, 247).
// $tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
// $tcpdf->SetFont("kozgopromedium", "", 12); // Set the font and style for the header




// Set up the table data
$tcpdf->SetFillColor(255, 255, 255); // Set the fill color for the data rows
$tcpdf->SetTextColor(40, 40, 40); // Set the text color for the data rows
$tcpdf->SetFont("kozgopromedium", "", 10); // Set the font and style for the data
if (!empty($workmonth_list)) {
	if ($template == "1") {
		// top
		$tcpdf->Cell(30, 6.8, formatHour($totalworkhh_top) . ':' . $totalworkmm_top, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob_top, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $closedayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork_top, 1, 1, 'C', true);

		// bottom
		$tcpdf->Cell(30, 6.8, formatHour($totalworkhh). ':' . $totalworkmm, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $closedayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork, 1, 1, 'C', true);
	} elseif ($template == "2") {
		// top 
		$tcpdf->Cell(30, 6.8, 	formatHour($totaldayhh_top) . ':' . $totaldaymm_top, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob_top, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $closedayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork_top, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork_top, 1, 1, 'C', true);
		// bottom
		$tcpdf->Cell(30, 6.8,		formatHour($totalworkhh). ':' . $totalworkmm, 1, 0, 'C', true);
		$tcpdf->Cell(25, 5.1, '', 0, 0, 'C', false);
		$tcpdf->Cell(30, 6.8, $cnprejob, 1, 0, 'C', true);
		$tcpdf->Cell(30, 6.8, $cnactjob, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $holydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $offdayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $closedayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $delaydayswork, 1, 0, 'C', true);
		$tcpdf->Cell(15, 6.8, $earlydayswork, 1, 1, 'C', true);
	}
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