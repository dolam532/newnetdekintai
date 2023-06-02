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
	<script src="../assets/js/jquery-ui.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

		.day-of-week-saturday {
			color: blue;
		}

		.day-of-week-sunday {
			color: red;
		}
	</style>
</head>
<?php
// Include const.php
require_once '../inc/const.php';
include('../inc/message.php');
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
	window.onload = function () {
		var currentDate = new Date();
		var currentMonth = currentDate.getMonth(); // Month is zero-based in JavaScript
		var currentYear = currentDate.getFullYear();
		document.getElementById('selyy').value = currentYear;
		document.getElementById('selmm').value = currentMonth < 10 ? '0' + currentMonth : currentMonth;
		handleDateChange(currentYear, currentMonth);
	};


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
				if (dayOfWeekJapanese === '土') {
					html += '<span class="day-of-week-saturday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else if (dayOfWeekJapanese === '日') {
					html += '<span class="day-of-week-sunday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else {
					html += '<span>' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				}
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
				if (dayOfWeekJapanese === '土') {
					html += '<span class="day-of-week-saturday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else if (dayOfWeekJapanese === '日') {
					html += '<span class="day-of-week-sunday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else {
					html += '<span>' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				}
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



		// loop foreach write data 

		var totalDayStartHours = 0;
		var totalDayStartMinutes = 0;
		var totalDayEndHours = 0;
		var totalDayEndMinutes = 0;
		var totalJobStartHours = 0;
		var totalJobStartMinutes = 0;
		var totalJobEndHours = 0;
		var totalJobEndMinutes = 0;
		var totalOffTimeHours = 0;
		var totalOffTimeMinutes = 0;
		var totalWorkHours = 0;
		var totalWorkMinutes = 0;
		var totalJanHours = 0;
		var totalJanMinutes = 0;


		var cdaystarthhList = document.getElementsByName('cdaystarthh');
		var cdaystartmmList = document.getElementsByName('cdaystartmm');
		var cdayendhhList = document.getElementsByName('cdayendhh');
		var cdayendmmList = document.getElementsByName('cdayendmm');
		var cjobstarthhList = document.getElementsByName('cjobstarthh');
		var cjobstartmmList = document.getElementsByName('cjobstartmm');
		var cjobendhhList = document.getElementsByName('cjobendhh');
		var cjobendmmList = document.getElementsByName('cjobendmm');
		var cofftimehhList = document.getElementsByName('cofftimehh');
		var cofftimemmList = document.getElementsByName('cofftimemm');
		var cworkhhList = document.getElementsByName('cworkhh');
		var cworkmmList = document.getElementsByName('cworkmm');
		var cjanhhList = document.getElementsByName('cjanhh');
		var cjanmmList = document.getElementsByName('cjanmm');


		var rowCount = cdaystarthhList.length;
		var nCountJobDay = 0;
		var nCountWorkDay = 0;
		var nCountDelayCount = 0;
		var nCountEarlyCount = 0;
		for (var i = 0; i < rowCount; i++) {
			var dayStartHours = parseInt(cdaystarthhList[i].innerHTML);
			var dayStartMinutes = parseInt(cdaystartmmList[i].innerHTML);
			var dayEndHours = parseInt(cdayendhhList[i].innerHTML);
			var dayEndMinutes = parseInt(cdayendmmList[i].innerHTML);
			var jobStartHours = parseInt(cjobstarthhList[i].innerHTML);
			var jobStartMinutes = parseInt(cjobstartmmList[i].innerHTML);
			var jobEndHours = parseInt(cjobendhhList[i].innerHTML);
			var jobEndMinutes = parseInt(cjobendmmList[i].innerHTML);
			var offTimeHours = parseInt(cofftimehhList[i].innerHTML);
			var offTimeMinutes = parseInt(cofftimemmList[i].innerHTML);
			var workHours = parseInt(cworkhhList[i].innerHTML);
			var workMinutes = parseInt(cworkmmList[i].innerHTML);
			var janHours = parseInt(cjanhhList[i].innerHTML);
			var janMinutes = parseInt(cjanmmList[i].innerHTML);

			if (!isNaN(dayStartHours)) {
				totalDayStartHours += dayStartHours;
				// get WorkDay 
				nCountWorkDay +=1;

			}
			if (!isNaN(dayStartMinutes)) {
				totalDayStartMinutes += dayStartMinutes;
			}
			if (!isNaN(dayEndHours)) {
				totalDayEndHours += dayEndHours;
			}
			if (!isNaN(dayEndMinutes)) {
				totalDayEndMinutes += dayEndMinutes;
			}
			if (!isNaN(jobStartHours)) {
				totalJobStartHours += jobStartHours;
					// get JobDay 
				nCountJobDay+=1;
				// check delay and early  ....
				

			}
			if (!isNaN(jobStartMinutes)) {
				totalJobStartMinutes += jobStartMinutes;
			}
			if (!isNaN(jobEndHours)) {
				totalJobEndHours += jobEndHours;
			}
			if (!isNaN(jobEndMinutes)) {
				totalJobEndMinutes += jobEndMinutes;
			}
			if (!isNaN(offTimeHours)) {
				totalOffTimeHours += offTimeHours;
			}
			if (!isNaN(offTimeMinutes)) {
				totalOffTimeMinutes += offTimeMinutes;
			}
			if (!isNaN(workHours)) {
				totalWorkHours += workHours;
			}
			if (!isNaN(workMinutes)) {
				totalWorkMinutes += workMinutes;
			}
			if (!isNaN(janHours)) {
				totalJanHours += janHours;
			}
			if (!isNaN(janMinutes)) {
				totalJanMinutes += janMinutes;
			}

			


			// get JobDay
		}

		totalDayStartHours += Math.floor(totalDayStartMinutes / 60);
		totalDayStartMinutes = totalDayStartMinutes % 60;
		totalDayEndHours += Math.floor(totalDayEndMinutes / 60);
		totalDayEndMinutes = totalDayEndMinutes % 60;
		totalJobStartHours += Math.floor(totalJobStartMinutes / 60);
		totalJobStartMinutes = totalJobStartMinutes % 60;
		totalJobEndHours += Math.floor(totalJobEndMinutes / 60);
		totalJobEndMinutes = totalJobEndMinutes % 60;
		totalOffTimeHours += Math.floor(totalOffTimeMinutes / 60);
		totalOffTimeMinutes = totalOffTimeMinutes % 60;
		totalWorkHours += Math.floor(totalWorkMinutes / 60);
		totalWorkMinutes = totalWorkMinutes % 60;
		totalJanHours += Math.floor(totalJanMinutes / 60);
		totalJanMinutes = totalJanMinutes % 60;

		// Hiển thị các tổng
		console.log("Tổng giờ bắt đầu ngày: " + totalDayStartHours + ":" + totalDayStartMinutes);
		console.log("Tổng giờ kết thúc ngày: " + totalDayEndHours + ":" + totalDayEndMinutes);
		console.log("Tổng giờ bắt đầu công việc: " + totalJobStartHours + ":" + totalJobStartMinutes);
		console.log("Tổng giờ kết thúc công việc: " + totalJobEndHours + ":" + totalJobEndMinutes);
		console.log("Tổng giờ nghỉ giữa giờ: " + totalOffTimeHours + ":" + totalOffTimeMinutes);
		console.log("Tổng giờ làm việc thực tế : " + totalWorkHours + ":" + totalWorkMinutes);
		console.log("Tổng giờ làm thêm: " + totalJanHours + ":" + totalJanMinutes);

		var offDay = nCountJobDay - nCountWorkDay ;
		// //show area
		jobhour_top.innerHTML = "<strong>" +totalDayStartHours+"</strong>";
		jobminute_top.innerHTML = "<strong>" +totalDayStartMinutes+"</strong>";

		workdays_top.innerHTML = "<strong>" +nCountWorkDay+"</strong>";
		jobdays_top.innerHTML = "<strong>" +nCountJobDay+"</strong>";
		offdays_top.innerHTML = "<strong>" +offDay +"</strong>";
		delaydays_top.innerHTML = "<strong>" +totalDayStartMinutes+"</strong>";
		earlydays_top.innerHTML = "<strong>" +totalDayStartMinutes+"</strong>";
		

		// //edit area 
		// var jobhour = document.getElementById('jobhour');
		// var jobminute = document.getElementById('jobminute');
		// var workdays = document.getElementById('workdays');
		// var jobdays = document.getElementById('jobdays');
		// var offdays = document.getElementById('offdays');
		// var delaydays = document.getElementById('delaydays');
		// var earlydays = document.getElementById('earlydays');

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

	async function handleDateChange(selectedYear, selectedMonth) {
		try {
			await handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth);
			await handlerDateChangeUpdateTotalWorkMonth(selectedYear, selectedMonth);
		} catch (error) {
			console.error('Error: ' + error);
			drawDataToTotalMonth(null);
		}
	}

	async function handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth) {

		console.log("Start");
		try {
			const response = await ajaxRequestPromise(
				'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH_DAY
			);

			let parsedResponse = response;
			if (JSON.parse(parsedResponse) === KINTAI_NODATA) {
				parsedResponse = null;
			}

			drawDayOfMonth(selectedYear, selectedMonth, parsedResponse);
		} catch (errorStatus) {
			console.error('Error: ' + errorStatus);
			drawDayOfMonth(selectedYear, selectedMonth, null);
		}
	}

	async function handlerDateChangeUpdateTotalWorkMonth(selectedYear, selectedMonth) {

		try {
			const response = await ajaxRequestPromise(
				'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH
			);

			let parsedResponse = response;
			if (JSON.parse(parsedResponse) === KINTAI_NODATA) {
				parsedResponse = null;
			}
			drawDataToTotalMonth(parsedResponse);
		} catch (errorStatus) {
			console.error('Error: ' + errorStatus);
			drawDataToTotalMonth(null);
		}
	}

	//  Ajax 
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

	function ajaxRequestPromise(url) {
		return new Promise(function (resolve, reject) {
			ajaxRequest(
				url,
				function (response) {
					resolve(response);
				},
				function (errorStatus) {
					reject(errorStatus);
				}
			);
		});
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