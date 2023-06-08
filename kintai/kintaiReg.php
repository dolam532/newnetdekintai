<?php
session_start();
if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}

?>
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
include '../inc/const.php';
include('../inc/message.php');
?>
<script>
	// Get 
	var NO_DATA_KINTAI = "<?php echo $NO_DATA_KINTAI; ?>";
	var TYPE_GET_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_GET_WORK_YEAR_MONTH_DAY; ?>"
	var TYPE_GET_WORK_YEAR_MONTH = "<?php echo $TYPE_GET_WORK_YEAR_MONTH; ?>"
	var TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY; ?>"
	var TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY; ?>"
	var ADD_DATA_ERROR_KINTAI = "<?php echo $ADD_DATA_ERROR_KINTAI; ?>"



	var TIME_KINTAI_DELAY_IN = parseInt("<?php echo $TIME_KINTAI_DELAY_IN; ?>");
	var TIME_KINTAI_EARLY_OUT = parseInt("<?php echo $TIME_KINTAI_EARLY_OUT; ?>");
	var LIST_DELAY_IN_DATE = [];
	var LIST_DELAY_OUT_DATE = [];

	// ***Handler Script ****
	//================================/// 
	//=========== init===============//     
	//============================///  
	window.onload = function() {
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

		//=====//PARAMETER listDataWorkymd is null 

		if (listDataWorkymd === null) {
			drawWhiteTable(showYear, showMonth);
		} else { //=====//PARAMETER listDataWorkymd not null 
			var html = '';
			// convert data 
			var jsonData = JSON.parse(listDataWorkymd);
			var workYmdList = jsonData.workYmdList;
			// Check List Month => If list  check the list of months if the list is missing or missing days then add it to the list
			var isCheck = checkMonthMissingData(workYmdList, showYear, showMonth);
			if (!isCheck) { // If future month => 
				drawWhiteTable(showYear, showMonth);
				// call drawDayOfMonth
				return;
			}

			for (var i = 0; i < workYmdList.length; i++) {
				var data = workYmdList[i];
				var day = i + 1;
				var formattedDate = '';
				if (data && data.workymd) {
					formattedDate = data.workymd.replace(/\//g, '-');
				}
				var formattedDate = ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2);
				var dateObj = new Date(showYear, showMonth - 1, day);
				var dayOfWeek = dateObj.toLocaleDateString('en-US', {
					weekday: 'short'
				});
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
			dayOfMonthTableBody.innerHTML = html;
		}


	}

	function drawWhiteTable(showYear, showMonth) {
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
		for (var day = 1; day <= daysInMonth; day++) {
			var formattedDate = ('0' + showMonth).slice(-2) + '/' + ('0' + day).slice(-2);
			var dateObj = new Date(showYear, showMonth - 1, day);
			var dayOfWeek = dateObj.toLocaleDateString('en-US', {
				weekday: 'short'
			});
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
		dayOfMonthTableBody.innerHTML = html;
	}

	//====================================================/// 
	//======= check the list of months  ===========//     if the list is missing or missing days then add it to the list 
	//=====================================================/// 
	function checkMonthMissingData(workYmdList, year, month) {



		var flagIsMissingDataOfMonth = false;
		// IF DATA MISSING ???  
		var daysInMonth = new Date(year, month, 0).getDate();;
		// => Current Month if not data => Add new Data to DB 
		if (typeof workYmdList === 'undefined') {
			console.log("list" + workYmdList)

			const currentDate = new Date();
			const currentYear = currentDate.getFullYear(); //
			const currentMonth = currentDate.getMonth() + 1; // 
			const parseYear = parseInt(year, 10);
			const parseMonth = parseInt(month, 10);

			if (currentYear === parseYear && currentMonth === parseMonth) {
				console.log("CurrentYear:" + currentYear + "||| YEARParam :" + parseYear);
				console.log("CurrentMONTH:" + currentMonth + "||| MONTHParam :" + parseMonth);
				insertNewMonthData(year, month);
				return false;
			}

		}


		if (workYmdList.length !== daysInMonth) {
			var arrayMissingDay = [];
			var arrayDayOfWorkList = [];
			var genidDefault = 0;
			console.log("IS NEW MONTH");
			// add value to ararymissing #
			for (let i = 0, len = daysInMonth; i < len; i++) {
				var date = new Date(year, month - 1, i);
				var formattedDate = date.getFullYear() + '/' + (date.getMonth() + 1).toString().padStart(2, '0') + '/' + date.getDate().toString().padStart(2, '0');
				arrayMissingDay.push(formattedDate);
			}
			// add value to DayWOrkList
			for (let i = 0, len = workYmdList.length; i < len; i++) {
				arrayDayOfWorkList.push(workYmdList[i].workymd);
				if (workYmdList[i].genid !== null) {
					genid = workYmdList[i].genidDefault; // get genid of last day *** bad
				}
			}

			var filteredArray = arrayMissingDay
				.filter(function(date) {
					return !arrayDayOfWorkList.includes(date);
				})
				.map(function(date) {
					return {
						genid: genidDefault, // genIdDefault
						workymd: date
					};
				});

			// call AJAX　　// OK 
			const data = JSON.stringify(filteredArray); // convert to json 
			console.log(data);
			const response = ajaxRequest(
				'kintaiRegController.php?type=' +
				TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY + '&data=' + data,
				'GET',
				function(response) {
					//html_entity_decode($data);
					console.log(response);
				},
				function(errorStatus) {
					console.log("Connect ERROR: " + errorStatus);
				}
			);
			return false;
		}
		// one more time get data-> draw
		return workYmdList;
	}

	//====================================================/// 
	//======= Function insert data new month  ===========//     
	//=====================================================/// 
	function insertNewMonthData(year, month) {

		const response = ajaxRequest(
			'kintaiRegController.php?type=' +
			TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY + '&year=' + year + '&month=' + month,
			'GET',
			function(response) {
				console.log("INSERT" + response);
			},
			function(errorStatus) {
				console.log("Connect ERROR: " + errorStatus);
			}
		);

	}



	//====================================================/// 
	//======= Function add value to total month ===========//     
	//=====================================================/// 
	function drawDataToTotalMonth() {
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
		// get list value of days
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
		var nCountDelayIn = 0;
		var nCountEarlyOut = 0;
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

			// flag -> check delay and early 
			isJobDay = false;
			isWorkDay = false;

			if (!isNaN(dayStartHours)) {
				totalDayStartHours += dayStartHours;
				// get WorkDay 
				nCountWorkDay += 1;
				isWorkDay = true;
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
				isJobDay = true;
				// get JobDay 
				nCountJobDay += 1;
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
			//LIST_DELAY_IN_DATE = [];   1日　= INDEX 0
			//LIST_DELAY_OUT_DATE = [];  1日　= INDEX 0
			if (isJobDay && isWorkDay) {
				// Delay Count
				if ((dayStartHours * 60 + dayStartMinutes + TIME_KINTAI_DELAY_IN) > (jobStartHours * 60 + jobStartMinutes)) {
					nCountDelayIn += 1
					LIST_DELAY_IN_DATE.push(i); // WHEN FILL COLOR TO DELAY DATE 
				}
				// Early off count  
				if ((dayEndHours * 60 + dayEndMinutes + TIME_KINTAI_EARLY_OUT) > (jobEndHours * 60 + jobEndMinutes)) {
					nCountEarlyOut += 1;
					LIST_DELAY_OUT_DATE.push(i); // WHEN FILL COLOR TO EARLY OUT DAY 
				}
			}

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

		// offday
		var offDay = nCountJobDay - nCountWorkDay;

		// //show area
		jobhour_top.innerHTML = "<strong>" + totalDayStartHours + "</strong>";
		jobminute_top.innerHTML = "<strong>" + totalDayStartMinutes + "</strong>";
		workdays_top.innerHTML = "<strong>" + nCountWorkDay + "</strong>";
		jobdays_top.innerHTML = "<strong>" + nCountJobDay + "</strong>";
		offdays_top.innerHTML = "<strong>" + offDay + "</strong>";
		delaydays_top.innerHTML = "<strong>" + nCountDelayIn + "</strong>";
		earlydays_top.innerHTML = "<strong>" + nCountEarlyOut + "</strong>";
		//edit area 
		jobhour.value = totalDayStartHours;
		jobminute.value = totalDayStartMinutes;
		workdays.value = nCountWorkDay;
		jobdays.value = nCountJobDay;
		offdays.value = offDay;
		delaydays.value = nCountDelayIn;
		earlydays.value = nCountEarlyOut;

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
			await handlerDateChangeUpdateTotalWorkMonth(selectedYear, selectedMonth); // NOW NOT USE
		} catch (error) {
			drawDataToTotalMonth();
		}
	}
	async function handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth) {
		try {
			const response = await ajaxRequestPromise(
				'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH_DAY, 'GET');

			let parsedResponse = null;
			try {
				parsedResponse = JSON.parse(response);
			} catch (error) {
				parsedResponse = null;
				console.log("GET_DATA_FAILD")
			} // JSON ERROR 

			if (parsedResponse === NO_DATA_KINTAI) {
				parsedResponse = null;
			}
			drawDayOfMonth(selectedYear, selectedMonth, response);
		} catch (errorStatus) {
			drawDayOfMonth(selectedYear, selectedMonth, null);
		}
	}


	async function handlerDateChangeUpdateTotalWorkMonth(selectedYear, selectedMonth) {
		// 	try {
		// 	const response = await ajaxRequestPromise(
		// 		'kintaiRegController.php?year=' + selectedYear + '&month=' + selectedMonth + '&type=' + TYPE_GET_WORK_YEAR_MONTH
		// 	,'GET');
		// 	let parsedResponse = null;
		// 	try {
		// 		parsedResponse = JSON.parse(response);
		// 	} catch (error) { parsedResponse = null; console.log("GET_DATA_FAILD")}// JSON ERROR 

		// 	if (parsedResponse === NO_DATA_KINTAI) {
		// 		parsedResponse = null;
		// 	}

		// 	drawDataToTotalMonth();
		// } catch (errorStatus) {
		// 	console.error('Error: ' + errorStatus);
		// 	drawDataToTotalMonth();
		// }

		// draw data total month 
		drawDataToTotalMonth();
	}
	// ajax
	function ajaxRequest(url, method, successCallback, errorCallback) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
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
		xhr.open(method, url, true);
		xhr.send();
	}

	function ajaxRequestPromise(url, method) {
		return new Promise(function(resolve, reject) {
			ajaxRequest(
				url,
				method,
				function(response) {
					resolve(response);
				},
				function(errorStatus) {
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
						<select id="selyy" name="selyy" class="seldate" style="padding:5px;" onchange="handleDateChange(this.value, document.getElementById('selmm').value)">
							<?php

							$currentYear = date('Y');
							$startYear = constant('START_SHOW_YEAR_KINMUHYO');
							for ($year = $currentYear; $year >= $startYear; $year--) {
								echo '<option value="' . $year . '">' . $year . '</option>';
							}
							?>
						</select>
						<select id="selmm" name="selmm" class="seldate" style="padding:5px;" onchange="handleDateChange(document.getElementById('selyy').value, this.value)">
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
					<p><a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="#" class="btn btn-default" style="width: 120px;">勤務表印刷</a></p>
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
					<td><input type="text" class="form-control" style="text-align: center" name="jobhour" id="jobhour" maxlength="3" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobminute" id="jobminute" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="workdays" id="workdays" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobdays" id="jobdays" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="offdays" id="offdays" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="delaydays" id="delaydays" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="earlydays" id="earlydays" maxlength="2" value="0"></td>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>