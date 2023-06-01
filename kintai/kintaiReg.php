<!DOCTYPE html>
<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>

	<!-- common Javascript -->
	<script type="text/javascript" src="../assets/js/common.js"> </script>

	<!-- Datepeeker 위한 link -->
	<link rel="stylesheet" href="../assets/css/jquery-ui.min.css">
	<script src="../assets/js/jquery-ui.min.js"></script>

	<!-- common CSS -->
	<link rel="stylesheet" href="../assets/css/common.css">
	<title>Kintai</title>
	<style type="text/css">
		.sumtbl tr th {
			background-color: #D9EDF7;
			text-align: center;
		}

		.sumtbl tr td {
			text-align: center;
		}

		.datatable tr td {
			text-align: center;
		}
	</style>
</head>
<?php
// Include const.php
require_once '../inc/const.php';
include('../inc/message.php');
include('../inc/request_param.php');
?>
<script>

	// Get CONST
	var KINTAI_NODATA = "<?php echo $KINTAI_NODATA; ?>";
	var TYPE_GET_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_GET_WORK_YEAR_MONTH_DAY; ?>"
	var TYPE_GET_WORK_YEAR_MONTH = "<?php echo $TYPE_GET_WORK_YEAR_MONTH; ?>"



	// ***Handler Script ****
	//================================/// 
	//=========== init===============//     
	//============================///  
	document.addEventListener('DOMContentLoaded', function () {
		var currentDate = new Date();
		var currentMonth = currentDate.getMonth(); // Month is zero-based in JavaScript
		var currentYear = currentDate.getFullYear();
		document.getElementById('selyy').value = currentYear;
		document.getElementById('selmm').value = currentMonth < 10 ? '0' + currentMonth : currentMonth;
		// Function to call handleDateChange after AJAX requests are complete
		handleDateChange(currentYear, currentMonth);
	});






	//================================/// 
	//======= Add Day Of Month ======//     
	//============================///  
	function drawDayOfMonth(showYear, showMonth, listDataWorkymd) {
		var daysInMonth = new Date(showYear, showMonth, 0).getDate();
		var dayOfWeekNames = {
			'Mon': '月',
			'Tue': '火',
			'Wed': '水',
			'Thu': '木',
			'Fri': '金',
			'Sat': '土',
			'Sun': '日'
		};
		var html = '';
		//=====//PARAMETER listDataWorkymd is null 
		if (listDataWorkymd === null) {
			html = '';
			for (var day = 1; day <= daysInMonth; day++) {
				var formattedDate = ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2);
				var dateObj = new Date(showYear, showMonth - 1, day);
				var dayOfWeek = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
				var dayOfWeekJapanese = dayOfWeekNames[dayOfWeek];
				html += '<tr>';
				html += '<td>';
				html += '<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(' + (day - 1) + '); return false;">';
				html += '<span>' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				html += '</a>';
				html += '</td>';
				html += '<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>';
				html += '<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>';
				html += '<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>';
				html += '<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>';
				html += '<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>';
				html += '<td><span name="cworkhh"></span></td>';
				html += '<td><span name="cworkmm"></span></td>';
				html += '<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>';
				html += '<td style="text-align:left"><span name="ccomment"></span></td>';
				html += '<td style="text-align:left"><span name="cbigo"></span>';
				html += '<input type="hidden" name="tuid" value="admin">';
				html += '<input type="hidden" name="tgenid" value="0">';
				html += '<input type="hidden" name="tworkymd" value="' + showYear + '/' + ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2) + '">';
				html += '<input type="hidden" name="tdaystarthh" value="">';
				html += '<input type="hidden" name="tdaystartmm" value="">';
				html += '<input type="hidden" name="tdayendhh" value="">';
				html += '<input type="hidden" name="tdayendmm" value="">';
				html += '<input type="hidden" name="tjobstarthh" value="">';
				html += '<input type="hidden" name="tjobstartmm" value="">';
				html += '<input type="hidden" name="tjobendhh" value="">';
				html += '<input type="hidden" name="tjobendmm" value="">';
				html += '<input type="hidden" name="tofftimehh" value="">';
				html += '<input type="hidden" name="tofftimemm" value="">';
				html += '<input type="hidden" name="tworkhh" value="">';
				html += '<input type="hidden" name="tworkmm" value="">';
				html += '<input type="hidden" name="tcomment" value="">';
				html += '<input type="hidden" name="tbigo" value="">';
				html += '</td>';
				html += '</tr>';
			}
		} else { //=====//PARAMETER listDataWorkymd not null 
			html = '';
			// convert data 
			var jsonData = JSON.parse(listDataWorkymd);
			const workYmdList = jsonData.workYmdList;
			for (var i = 0; i < workYmdList.length; i++) {
				var data = workYmdList[i];
				var day = i + 1;
				var formattedDate = '';
				if (data && data.workymd) {
					formattedDate = data.workymd.replace(/\//g, '-');
				}
				var formattedDate = ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2);
				var dateObj = new Date(showYear, showMonth - 1, day);
				var dayOfWeek = dateObj.toLocaleDateString('en-US', { weekday: 'short' });
				var dayOfWeekJapanese = dayOfWeekNames[dayOfWeek];

				//Create HTML for one row
				html += '<tr>';
				html += '<td>';
				html += '<a href="#" onclick="fnClickTitle(' + i + '); return false;">';
				html += '<span>' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				html += '</a>';
				html += '</td>';
				html += '<td><span name="cdaystarthh">' + (data.daystarthh || '') + '</span>:<span name="cdaystartmm">' + (data.daystartmm || '') + '</span></td>';
				html += '<td><span name="cdayendhh">' + (data.dayendhh || '') + '</span>:<span name="cdayendmm">' + (data.dayendmm || '') + '</span></td>';
				html += '<td><span name="cjobstarthh">' + (data.jobstarthh || '') + '</span>:<span name="cjobstartmm">' + (data.jobstartmm || '') + '</span></td>';
				html += '<td><span name="cjobendhh">' + (data.jobendhh || '') + '</span>:<span name="cjobendmm">' + (data.jobendmm || '') + '</span></td>';
				html += '<td><span name="cofftimehh">' + (data.offtimehh || '') + '</span>:<span name="cofftimemm">' + (data.offtimemm || '') + '</span></td>';
				html += '<td><span name="cworkhh">' + (data.workhh || '') + '</span></td>';
				html += '<td><span name="cworkmm">' + (data.workmm || '') + '</span></td>';
				html += '<td><span name="cjanhh">' + (data.janhh || '') + '</span>:<span name="cjanmm">' + (data.janmm || '') + '</span></td>';
				html += '<td><span name="ccomment">' + (data.comment || '') + '</span></td>';
				html += '<td><span name="cbigo">' + (data.bigo || '') + '</span>';
				html += '<input type="hidden" name="tuid" value="' + data.uid + '">';
				html += '<input type="hidden" name="tgenid" value="' + data.genid + '">';
				html += '<input type="hidden" name="tworkymd" value="' + showYear + '/' + ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2) + '">';
				html += '<input type="hidden" name="tdaystarthh" value="' + (data.daystarthh || '') + '">';
				html += '<input type="hidden" name="tdaystartmm" value="' + (data.daystartmm || '') + '">';
				html += '<input type="hidden" name="tdayendhh" value="' + (data.dayendhh || '') + '">';
				html += '<input type="hidden" name="tdayendmm" value="' + (data.dayendmm || '') + '">';
				html += '<input type="hidden" name="tjobstarthh" value="' + (data.jobstarthh || '') + '">';
				html += '<input type="hidden" name="tjobstartmm" value="' + (data.jobstartmm || '') + '">';
				html += '<input type="hidden" name="tjobendhh" value="' + (data.jobendhh || '') + '">';
				html += '<input type="hidden" name="tjobendmm" value="' + (data.jobendmm || '') + '">';
				html += '<input type="hidden" name="tofftimehh" value="' + (data.offtimehh || '') + '">';
				html += '<input type="hidden" name="tofftimemm" value="' + (data.offtimemm || '') + '">';
				html += '<input type="hidden" name="tworkhh" value="' + (data.workhh || '') + '">';
				html += '<input type="hidden" name="tworkmm" value="' + (data.workmm || '') + '">';
				html += '<input type="hidden" name="tcomment" value="' + (data.comment || '') + '">';
				html += '<input type="hidden" name="tbigo" value="' + (data.bigo || '') + '">';
				html += '</td>';
				html += '</tr>';
			}

		}
		document.getElementById('dayOfMonthTableBody').innerHTML = html;
	}

	//====================================================/// 
	//======= Funtion add value to total month ===========//     
	//=====================================================/// 
	function drawDataToTotalMonth(dataJson) {
		// Parse JSON data
		// var dataJson = JSON.parse(dataJson);
		// const data = dataJson.data;
		// console.log(dataJson);

		// show area
		// var jobhour_top = document.getElementById('jobhour_top');
		// var jobminute_top = document.getElementById('jobminute_top');
		// var workdays_top = document.getElementById('workdays_top');
		// var jobdays_top = document.getElementById('jobdays_top');
		// var offdays_top = document.getElementById('offdays_top');
		// var delaydays_top = document.getElementById('delaydays_top');
		// var earlydays_top = document.getElementById('earlydays_top');



		// jobhour_top.value = data.workym && data.workym[0].jobhour_top ? data.workym[0].jobhour_top : "0";
		// jobminute_top.value = data.workym && data.workym[0].jobminute_top ? data.workym[0].jobminute_top : "0";
		// workdays_top.value = data.workym && data.workym[0].workdays_top ? data.workym[0].workdays_top : "0";
		// jobdays_top.value = data.workym && data.workym[0].jobdays_top ? data.workym[0].jobdays_top : "0";
		// offdays_top.value = data.workym && data.workym[0].offdays_top ? data.workym[0].offdays_top : "0";
		// delaydays_top.value = data.workym && data.workym[0].delaydays_top ? data.workym[0].delaydays_top : "0";
		// earlydays_top.value = data.workym && data.workym[0].earlydays_top ? data.workym[0].earlydays_top : "0";



		// // edit area 
		// var jobhour = document.getElementById('jobhour');
		// var jobminute = document.getElementById('jobminute');
		// var workdays = document.getElementById('workdays');
		// var jobdays = document.getElementById('jobdays');
		// var offdays = document.getElementById('offdays');
		// var delaydays = document.getElementById('delaydays');
		// var earlydays = document.getElementById('earlydays');

		// jobhour.value = data.workym && data.workym[0].jobhour ? data.workym[0].jobhour : "0";
		// jobminute.value = data.workym && data.workym[0].jobminute ? data.workym[0].jobminute : "0";
		// workdays.value = data.workym && data.workym[0].workdays ? data.workym[0].workdays : "0";
		// jobdays.value = data.workym && data.workym[0].jobdays ? data.workym[0].jobdays : "0";
		// offdays.value = data.workym && data.workym[0].offdays ? data.workym[0].offdays : "0";
		// delaydays.value = data.workym && data.workym[0].delaydays ? data.workym[0].delaydays : "0";
		// earlydays.value = data.workym && data.workym[0].earlydays ? data.workym[0].earlydays : "0";



	}




	//====================================================================/// 
	//======= Funtion for click day of week --> show register ======//     
	//============================================================///  
	function fnClickTitle(DayOfMonth) {
		console.log(DayOfMonth);
	}
	//====================================================================/// 
	//=======function for bind change year month combo box==============//     
	//============================================================///  

	function handleDateChange(selectedYear, selectedMonth) {
		var dayOfMonthTableBody = document.getElementById('dayOfMonthTableBody');
		var workYearMonthDayComplete = false;
		var totalMonthOnce = false;


		// Ajax Request for Work Year Month Day 
		ajaxRequest(
			'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH_DAY,
			function (response) {
				if (JSON.parse(response) === KINTAI_NODATA) {
					response = null;
				}
				workYearMonthDayComplete = true;
				drawDayOfMonth(selectedYear, selectedMonth, response);
			},
			function (errorStatus) {
				workYearMonthDayComplete = true;
				console.error('Error: ' + errorStatus);
				drawDayOfMonth(selectedYear, selectedMonth, null);
			}
		);
		
		// Ajax Request for Total Month 
			ajaxRequest(
			'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH,
			function (response) {
				if (JSON.parse(response) === KINTAI_NODATA) {
					response = null;
				}
				console.log(response);
				drawDataToTotalMonth(response) 
			},
			function (errorStatus) {
				console.error('Error: ' + errorStatus);
				drawDataToTotalMonth(null) 
			}
		);

	}




	// Call Ajax 
	function ajaxRequest(url, successCallback, errorCallback) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					if (successCallback) {
						successCallback(xhr.responseText);
					}
				} else {
					if (errorCallback) {
						errorCallback(xhr.status);
					}
				}
			}
		};
		xhr.open('GET', url, true);
		xhr.send();
	}


</script>

<body>
	<?php include('../inc/header.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-left">
				<div class="title_name">
					<span class="text-left">勤 務 表</span>
				</div>
			</div>
			<div class="col-md-4 text-center">
				<div class="title_condition">
					<label>基準日:
						<select id="selyy" name="selyy" class="seldate" style="padding:5px;"
							onchange="handleDateChange(this.value, document.getElementById('selmm').value)">
							<?php

							$currentYear = date('Y');
							$startYear = constant('START_SHOW_YEAR_KINMUHYO');
							for ($year = $currentYear; $year >= $startYear; $year--) {
								echo '<option value="' . $year . '">' . $year . '</option>';
							}
							?>
						</select>
						<select id="selmm" name="selmm" class="seldate" style="padding:5px;"
							onchange="handleDateChange(document.getElementById('selyy').value, this.value)">
							<?php
							for ($month = 1; $month <= 12; $month++) {
								$formattedMonth = sprintf("%02d", $month);
								echo '<option value="' . $formattedMonth . '">' . $formattedMonth . '</option>';
							}
							?>
						</select>
					</label>
				</div>
			</div>
			<div class="col-md-3 text-right">
				<div class="title_btn">
					<p><a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="#" class="btn btn-default"
							style="width: 120px;">勤務表印刷</a></p>
				</div>
			</div>
		</div>
		<div class="form-group">
			<table class="table table-bordered datatable">
				<thead>
					<tr class="info">
						<th style="text-align: center; width: 8%;">日付</th>
						<th style="text-align: center; width: 14%;" colspan="2">出退社時刻</th>
						<th style="text-align: center; width: 14%;" colspan="2">業務時間</th>
						<th style="text-align: center; width: 8%;">休憩時間</th>
						<th style="text-align: center; width: 7%;">就業時</th>
						<th style="text-align: center; width: 7%;">就業分</th>
						<th style="text-align: center; width: 7%;">残業</th>
						<th style="text-align: center; width: auto;">業務内容</th>
						<th style="text-align: center; width: 20%;">備考</th>
					</tr>
				</thead>
				<tbody id="dayOfMonthTableBody">

				</tbody>
			</table>
		</div>
	</div>
	<div class="container" id="divmonthly">
		<table class="table table-bordered datatable">
			<tbody class="sumtbl">
				<tr>
					<th style="width: 10%; padding-top: 30px;" rowspan="2">実働時間</th>
					<th style="width: 10%; ">時 間</th>
					<th style="width: 10%; ">分</th>
					<th style="width: 10%; padding-top: 30px;" rowspan="3">勤務状況</th>
					<th style="width: 15%; ">所定勤務日数</th>
					<th style="width: 12%; ">実勤務日数</th>
					<th style="width: 10%; ">欠勤</th>
					<th style="width: 10%; ">遅刻</th>
					<th style="width: 10%; ">早退</th>
				</tr>
				<tr>
					<td id="jobhour_top"><strong>0</strong></td>
					<td id="jobminute_top"><strong>0</strong></td>
					<td id="workdays_top"><strong>0</strong></td>
					<td id="jobdays_top"><strong>0</strong></td>
					<td id="offdays_top"><strong>0</strong></td>
					<td id="delaydays_top"><strong>0</strong></td>
					<td id="earlydays_top"><strong>0</strong></td>
				</tr>
				<tr>
					<td><button type="button" class="btn btn-primary" id="btnUpdMonthly">月登録</button></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobhour" id="jobhour"
							maxlength="3" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobminute"
							id="jobminute" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="workdays" id="workdays"
							maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobdays" id="jobdays"
							maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="offdays" id="offdays"
							maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="delaydays"
							id="delaydays" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="earlydays"
							id="earlydays" maxlength="2" value="0"></td>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>