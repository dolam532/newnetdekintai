<?php
session_start();
include('../inc/const.php');
include('../inc/message.php');
include('../inc/header.php');
include('../inc/menu.php');
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
	<link rel="stylesheet" href="../assets/css/jquery-ui.min.css">
	<script src="../assets/js/jquery-ui.min.js"></script>

	<!-- common CSS -->
	<link rel="stylesheet" href="../assets/css/common.css">
	<title>Monthly Kintai List</title>

	<style type="text/css">
		.datatable tr td {
			text-align: center;
		}

		#myBar {
			width: 1%;
			height: 30px;
			background-color: #4CAF50;
		}
	</style>

	<script>
		//================================/// 
		//=========== init===============//     OK
		//============================///  
		window.onload = function () {
			var currentDate = new Date();
			var currentMonth = currentDate.getMonth() + 1; // Month is zero-based in JavaScript 
			var currentYear = currentDate.getFullYear();
			document.getElementById('selyy').value = currentYear;
			document.getElementById('selmm').value = currentMonth < 10 ? '0' + currentMonth : currentMonth;
			handleDateChange(currentYear, currentMonth);
		};
		// Message
		var NO_DATA_KINTAI = "<?php echo $NO_DATA_KINTAI; ?>";
		var CONNECT_ERROR = "<?php echo $CONNECT_ERROR; ?>"
		var currentName = "<?php echo $_SESSION['auth_name']; ?>"

		// TYPE
		var TYPE_GET_WORK_YEAR_MONTH = "<?php echo $TYPE_GET_WORK_YEAR_MONTH; ?>"



		//====================================================================/// 
		//=======function for bind change year month combo box==============//     
		//============================================================///  
		 function handleDateChange(selectedYear, selectedMonth) {
			var strMonth = selectedMonth.toString().padStart(2, '0');
			handleDateChangeUpdateWorkMonth(selectedYear, strMonth);
		}
		 function handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth) {
			var parsedResponse = null;
			var dataObject = {
				workym: selectedYear + selectedMonth
			};
			const data = JSON.stringify(dataObject); // convert to json 
			const response = ajaxRequest(
				'kintaiRegController.php?type=' + TYPE_GET_WORK_YEAR_MONTH + '&data=' + data,
				'GET',
				function (response) {
					if (response === CONNECT_ERROR) {// connect faild
						handlerDrawDataWorkOfMonth(null);
						return;
					}
					handlerDrawDataWorkOfMonth(response);
				},
				function (errorStatus) {  // connect faild
					console.log("Ajax Error");
					handlerDrawDataWorkOfMonth(null);
					return;
				}
			);

		};

		function handlerDrawDataWorkOfMonth(response) {
			parsedResponse = JSON.parse(response);
			if (parsedResponse === null || parsedResponse === NO_DATA_KINTAI) {
				DrawWhiteTableWorkOfMonth();
				return;
			}
			parsedResponse = parsedResponse['workym'];
			DrawDataWorkOfMonth(parsedResponse);
		}

		function DrawDataWorkOfMonth(response) {
			cusername.innerHTML = response['username'];
			cworktime.innerHTML = response['workym'];
			cdaytimehh.innerHTML = response['jobhour2'];
			cdaytimemm.innerHTML = response['jobminute2'];
			cjobdays.innerHTML = response['jobdays2'];
			cworkdays.innerHTML = response['workdays2'];
			janTime.innerHTML = response['janhour2'] +':'+ response['jobminute2'];;
			coffdays.innerHTML = response['offdays'];
			cdelaydays.innerHTML = response['delaydays'];
			cearlydays.innerHTML = response['earlydays'];
			cbigo.innerHTML = response['bigo'];
		}
		function DrawWhiteTableWorkOfMonth() {
			cusername.innerHTML= currentName;
			cworktime.innerHTML = '0';
			cdaytimehh.innerHTML = '0';
			cdaytimemm.innerHTML = '0';
			cjobdays.innerHTML = '0';
			cworkdays.innerHTML = '0';
			janTime.innerHTML = '0:0';
			coffdays.innerHTML = '0';
			cdelaydays.innerHTML = '0';
			cearlydays.innerHTML = '0';
			cbigo.innerHTML = '';
		}


		//＝＝＝＝==========//
		// =======ajax=====// OK
		//＝＝＝＝==========//
		function ajaxRequest(url, method, successCallback, errorCallback) {
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
			xhr.open(method, url, true);
			xhr.send();
		}

		function ajaxRequestPromise(url, method) {
			return new Promise(function (resolve, reject) {
				ajaxRequest(
					url,
					method,
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
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-left">
				<div class="title_name">
					<span class="text-left">月勤務表</span>
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
			<div class="col-md-3 text-right"></div>
		</div>
		<table class="table table-bordered datatable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 14%;">名前</th>
					<th style="text-align: center; width: 8%;">作業年月</th>
					<th style="text-align: center; width: 8%;">実働時間</th>
					<th style="text-align: center; width: 8%;">実働分</th>
					<th style="text-align: center; width: 8%;">勤務日</th>
					<th style="text-align: center; width: 8%;">実勤務日</th>
					<th style="text-align: center; width: 6%;">残業</th>
					<th style="text-align: center; width: 6%;">欠勤</th>
					<th style="text-align: center; width: 6%;">遅刻</th>
					<th style="text-align: center; width: 6%;">早退</th>
					<th style="text-align: center; width: auto;">備考</th>
				</tr>
			</thead>

			<tbody>

				<tr>
					<td><a href="http://localhost:8080/web/kintai/kintaiMonthly#"><span class="nameClick"
					name="cusername" id="cusername">NAME</span></a></td>
					<td><span name="cworktime" id="cworktime">202305</span></td>
					<td><span name="cdaytimehh" id="cdaytimehh">0</span></td>
					<td><span name="cdaytimemm" id="cdaytimemm">0</span></td>
					<td><span name="cjobdays" id="cjobdays">0</span></td>
					<td><span name="cworkdays" id="cworkdays">0</span></td>
					<td><span name="janTime" id="janTime">0:0</span></td>
					<td><span name="coffdays" id="coffdays">0</span></td>
					<td><span name="cdelaydays" id="cdelaydays">0</span></td>
					<td><span name="cearlydays" id="cearlydays">0</span></td>
					<td><span name="cbigo" id="cbigo"></span></td>
				</tr>



			</tbody>
		</table>
	</div>
</body>

</html>