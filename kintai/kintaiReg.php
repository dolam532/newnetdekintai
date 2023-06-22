<?php
// include('../inc/menu.php');
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}

?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title class="page_header_text">Kintai</title>

	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>

	<!-- common Javascript -->
	<script type="text/javascript" src="../assets/js/common.js"> </script>

	<!-- Datepeeker 위한 link    -->
	<script src="../assets/js/jquery-ui.min.js"></script>
	<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

	<!-- common CSS -->
	<link rel="stylesheet" href="../assets/css/common.css">

</head>


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

	.colorSuccess {
		color: forestgreen;
	}

	.colorError {
		color: red
	}

	.table_hidden {
		display: none;
	}

	.shorten-height {
		height: 50%;
	}

	.double-width {
		width: 200%;
	}

	.table {
		margin-top: -10px;
		margin-bottom: 0px;
	}
</style>

<!-- Print Css -->
<style type="text/css" media="print">
	@page {
		/* //size: ; */
		margin-top: .5rem;
		margin-bottom: .3rem;
	}

	/* print one */
	.page-two {
		display: none;
	}


	.row {
		display: flex;
		justify-content: center;
	}

	.centered-text {
		text-align: center;
	}


	.table>tbody>tr>td {
		padding: 1px;
		line-height: 1.8rem;
	}

	.table__topViewTable {
		border: 2px solid black;

		.info {
			border: 2px solid black;
		}
	}


	/* Header  */


	.table-container {
		display: flex;
	}

	.left-table,
	.right-table {
		flex: 1;
		padding: 10px;
	}

	.left-table p {
		position: relative;
		margin-bottom: 30px;
	}

	.right-table {
		position: relative;
		margin-left: 10px;
		text-align: right;
	}

	.left-table p:not(:first-child) {
		border-bottom: 1px dotted;
		padding-bottom: 1px;
		padding-top: auto;

	}

	.left-table p:nth-child(3) {
		position: relative;

	}


	.left-table p:nth-child(3)::after {
		content: "（印）";
		position: absolute;
		top: -1px;
		right: 0;
		/* margin-bottom: 40px; */
	}

	.right-table {
		display: grid;
		grid-template-columns: repeat(2, 1fr);
		grid-template-rows: auto auto;
		gap: 0;
		position: relative;
		margin-left: 0px;

	}

	.row1 {
		position: relative;
		left: 50px;
		padding-left: 0px;
	}

	.row2 {
		right: 50px;
		padding-right: 0px;
	}

	.cell {
		border: 1px solid black;
		padding: 5px;
	}

	.cell-empty {
		height: 7em;
		border-top: none;
		position: relative;
		top: -10px;
		width: 70%;
		margin-left: 50px;
	}

	.cell-text {
		grid-column: 1 / span 2;
		position: relative;
		text-align: center;
		width: 70%;
		margin-left: 50px;
	}

	.row2 .cell {
		border-left: none;
	}

	.table-container {
		position: relative;
		margin-top: 0px;
		/* Điều chỉnh giá trị này để đẩy phần tử bên dưới lên */

	}


	.table__topViewTable {
		margin-top: -30px;
	}





	/* left */

	#footer__workTime-label {
		border: 2px solid black;
		border-top: 2px solid black;
		border-right: 0px;
	}

	#footer__workhh-label {
		border: 2px solid black;
		border-left: 1px solid gray;

	}



	/* show value */
	#footer___table__show_value {
		border: 1px solid gray;
		border-bottom: 2px solid black;
		border-right: 2px solid black;

		#workdays_top {
			border-right: 1px solid gray;
		}

		#jobdays_top {
			border-right: 1px solid gray;
		}

		#offdays_top {
			border-right: 1px solid gray;
		}

		#delaydays_top {
			border-right: 1px solid gray;
		}

		#earlydays_top {
			border-right: 1px solid gray;
		}

	}


	/* right */

	#footer__workStatus-label {
		border: 2px solid black;
		border-right: 1px solid gray;
	}

	#footer__jobDays-label {
		border: 1px solid gray;
	}

	#footer__workDays-label {
		border: 1px solid gray;
	}

	#footer__offDays-label {
		border: 1px solid gray;
	}

	#footer__delayDays-label {
		border: 1px solid gray;
	}

	#footer__earlyDays-label {
		border: 1px solid gray;
		border-right: 2px solid black;
	}

	#footer___table__title {
		border: 2px solid black;
		border-bottom: 1px solid gray;

	}
</style>

<body>
	<?php include('../inc/menu.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-left" name="workYm_page_title">
				<div class="title_name text-center">
					<span class="text-left">勤 務 表</span>
				</div>
			</div>
			<div class="col-md-4 text-center" name="workYm_page_condition">
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

						<select id="template_table" name="template_table" class="seldate" style="padding:5px;"
							onchange="handlerChangeTemplate()">
							<option value="1">Template A</option>
							<option value="2">Template B</option>
						</select>
					</label>
				</div>
			</div>
			<div class="col-md-3 text-right">
				<div class="title_btn print_btn">
					<p><a href="#" onclick="preparePrint()" class="btn btn-default" style="width: 120px;">勤務表印刷</a></p>

				</div>
			</div>
		</div>


		<div class="col-md-3 text-left print_Infotext_region" style="display: none;">
			<div class="form-group">
				<div class="table-container">
					<div class="left-table">
						<p>
							<?php echo $COMPANY_NAME; ?> 御中
						</p>
						<p>所属：
							<?php echo $_SESSION['auth_dept']; ?>
						</p>
						<p>氏名：
							<?php echo $_SESSION['auth_name']; ?>
						</p>
					</div>
					<div class="right-table">
						<div class="row1">
							<p class="cell cell-text">
								<?php echo $SIGN_TITLE1; ?>
							</p>
							<p class="cell cell-empty"></p>
						</div>
						<div class="row2">
							<p class="cell cell-text">
								<?php echo $SIGN_TITLE2; ?>
							</p>
							<p class="cell cell-empty"></p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="form-group">
			<table class="table table-bordered datatable table__topViewTable">
				<thead>
					<tr class="info">
						<th id="table__title--day" name="cDayTime_col" style="text-align: center; width: 8%;">日付</th>
						<th id="table__title--inOutTime" name="cDayStartEnd_col" style="text-align: center; width: 14%;"
							colspan="2">出退社時刻</th>
						<th id="table__title--jobTime" name="cJobTime_col" style="text-align: center; width: 14%;"
							colspan="2">業務時間</th>
						<th id="table__title--offTime" name="cOffTime_col" style="text-align: center; width: 9%;">休憩時間
						</th>
						<th id="table__title--workTime" name="cWorkTime_col" style="text-align: center; width: 9%;">就業時間
						</th>
						<!-- <th style="text-align: center; width: 7%;">残業</th> -->
						<th id="table__title--workInfo" name="cWorkInfo_col" style="text-align: center; width: auto;">
							業務内容</th>
						<th id="table__title--bigo" name="cBigo_col" style="text-align: center; width: 20%;">備考</th>
					</tr>
				</thead>
				<tbody id="dayOfMonthTableBody">

				</tbody>
			</table>
		</div>
	</div>
	<div class="container" id="divmonthly">
		<table id="footer-Table" class="table table-bordered datatable">
			<tbody class="sumtbl">
				<tr id="footer___table__title">
					<th id="footer__workTime-label" style="width: 10%; padding-top: 30px;" rowspan="2">実働時間</th>
					<th id="footer__workhh-label" style="width: 10%; ">時 間</th>
					<th id="footer__workmm-label" style="width: 10%; ">分</th>
					<th id="footer__workStatus-label" style="width: 10%; padding-top: 30px;" rowspan="3">勤務状況</th>
					<th id="footer__jobDays-label" style="width: 15%; ">所定勤務日数</th>
					<th id="footer__workDays-label" style="width: 12%; ">実勤務日数</th>
					<th id="footer__offDays-label" style="width: 10%; ">欠勤</th>
					<th id="footer__delayDays-label" style="width: 10%; ">遅刻</th>
					<th id="footer__earlyDays-label" style="width: 10%; ">早退</th>
				</tr>
				<tr id="footer___table__show_value">
					<td id="jobhour_top"><strong>0</strong></td>
					<td id="jobminute_top"><strong>0</strong></td>
					<td id="workdays_top"><strong>0</strong></td>
					<td id="jobdays_top"><strong>0</strong></td>
					<td id="offdays_top"><strong>0</strong></td>
					<td id="delaydays_top"><strong>0</strong></td>
					<td id="earlydays_top"><strong>0</strong></td>
					<td id="janhh_top" style="display: none;"><strong>0</strong></td>
					<td id="janmm_top" style="display: none;"><strong>0</strong></td>

				</tr>
				<tr id="footer___table__edit_input">
					<td><button type="button" class="btn btn-primary" id="btnUpdMonthly"
							onclick=MonthDataRegister()>月登録</button></td>
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

					<td style="display: none;"><input type="text" class="form-control" style="text-align: center;"
							name="janhh" id="janhh" maxlength="2" value="0"></td>
					<td style="display: none;"><input type="text" class="form-control" style="text-align: center;"
							name="janmm" id="janmm" maxlength="2" value="0"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<!--=============================================-->
	<!--===========Modal ======================-->
	<!--=============================================-->
	<div class="row">
		<div class="modal" id="modal2" tabindex="-2" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						勤務時間変更(<span id="selkindate"></span>)
						<button class="close" onclick="changeStatusMessageModal(null , null)"
							data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body" style="text-align: left">
						<div class="row" name="templateB_Onshow">
							<div class="col-xs-4">
								<label for="workymd">日付</label>
								<input type="text" class="form-control" id="workymd" placeholder="" required="required"
									style="text-align: center">
								<input type="hidden" id="seq" value="">
								<input type="hidden" id="uid">
								<input type="hidden" id="genid">
							</div>
							<div class="col-xs-2">
								<label>出社時刻</label>
								<select class="form-control" id="daystarthh">
									<option value="" selected></option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<input type="text" class="form-control" id="daystartmm"
									onfocus="handleInputFocus(this, daystartmmSelect)"
									onblur="handleInputBlur(this, daystartmmSelect)">
								<select class="form-control" id="daystartmmSelect"
									oninput="handleSelect(daystartmm, this , false)">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>退社時刻</label>
								<select class="form-control" id="dayendhh">
									<option value="" selected></option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<input type="text" class="form-control" id="dayendmm"
									onfocus="handleInputFocus(this, dayendmmSelect)"
									onblur="handleInputBlur(this, dayendmmSelect)">
								<select class="form-control" id="dayendmmSelect"
									oninput="handleSelect(dayendmm, this , false)">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>

						</div>
						<br>
						<div class="row">
							<div class="col-xs-4">
							</div>
							<div class="col-xs-2">
								<label>業務開始</label>
								<select class="form-control" id="jobstarthh" oninput="updateChangeJobTimeModal()">
									<option value="" selected></option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<input type="text" class="form-control" id="jobstartmm"
									onfocus="handleInputFocus(this, jobstartmmSelect)"
									onblur="handleInputBlur(this, jobstartmmSelect)"
									oninput="updateChangeJobTimeModal()">
								<select class="form-control" id="jobstartmmSelect"
									oninput="handleSelect(jobstartmm, this , true)">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>

							<div class="col-xs-2">
								<label>業務終了</label>
								<select class="form-control" id="jobendhh" oninput="updateChangeJobTimeModal()">
									<option value="" selected></option>
									<option value="01">01</option>
									<option value="02">02</option>
									<option value="03">03</option>
									<option value="04">04</option>
									<option value="05">05</option>
									<option value="06">06</option>
									<option value="07">07</option>
									<option value="08">08</option>
									<option value="09">09</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<input type="text" class="form-control" id="jobendmm"
									onfocus="handleInputFocus(this, jobendmmSelect)"
									onblur="handleInputBlur(this, jobendmmSelect)" oninput="updateChangeJobTimeModal()">
								<select class="form-control" id="jobendmmSelect"
									oninput="handleSelect(jobendmm, this , true)">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-4">
							</div>
							<div class="col-xs-2">
								<label>休憩時間</label>
								<select class="form-control" id="offtimehh" oninput="updateChangeJobTimeModal()">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="01">01</option>
									<option value="02">02</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<select class="form-control" id="offtimemm" oninput="updateChangeJobTimeModal()">
									<option value="" selected></option>
									<option value="00">00</option>
									<option value="10">10</option>
									<option value="20">20</option>
									<option value="30">30</option>
									<option value="40">40</option>
									<option value="50">50</option>
								</select>
							</div>
							<div class="col-xs-2">
								<label for="workhh">就業時間</label>
								<input type="text" class="form-control" id="workhh" placeholder="0" required="required"
									style="text-align: center">
							</div>
							<div class="col-xs-2">
								<label for="workmm">&nbsp;</label>
								<input type="text" class="form-control" id="workmm" placeholder="0" required="required"
									style="text-align: center">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="comment">業務内容</label>
								<input type="text" class="form-control" id="comment" placeholder="" required="required"
									style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" id="bigo" placeholder="" required="required"
									style="text-align: left">
							</div>
						</div>
					</div>
					<div id="statusMessage" class="row text-center" style="font-weight: bold; visibility: hidden;">
						Status Message
					</div>
					<div class="modal-footer" style="text-align: center">
						<button type="button" class="btn btn-primary update" onclick="updateDataSeletedDay()"
							id="btnUpd">登録</button>
						<button type="button" class="btn btn-primary" onclick="deleteDataSelected()"
							id="btnDel">削除</button>
						<button type="button" class="btn btn-default" data-dismiss="modal"
							onclick="changeStatusMessageModal(null , null)" id="modalClose">閉じる</button>
					</div>
				</div>
			</div>
		</div>
	</div>


	<script>
		// TYPE
		var TYPE_GET_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_GET_WORK_YEAR_MONTH_DAY; ?>";
		var TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY; ?>";
		var TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY = "<?php echo $TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY; ?>";
		var TYPE_DELETE_DATA_OF_SELETED_DAY = "<?php echo $TYPE_DELETE_DATA_OF_SELETED_DAY; ?>";
		var TYPE_REGISTER_DATA_OF_SELETED_DAY = "<?php echo $TYPE_REGISTER_DATA_OF_SELETED_DAY; ?>";
		var TYPE_REGISTER_DATA_OF_MONTH = "<?php echo $TYPE_REGISTER_DATA_OF_MONTH; ?>";
		var TYPE_GET_DATA_KINMUHYO = "<?php echo $TYPE_GET_DATA_KINMUHYO; ?>";
		var TYPE_REGISTER_NEW_DATA_OF_MONTH = "<?php echo $TYPE_REGISTER_NEW_DATA_OF_MONTH; ?>";


		// Message
		var NO_DATA_KINTAI = "<?php echo $NO_DATA_KINTAI; ?>";
		var ADD_DATA_ERROR_KINTAI = "<?php echo $ADD_DATA_ERROR_KINTAI; ?>";
		var UPDATE_DATA_SUCCESS = "<?php echo $UPDATE_DATA_SUCCESS; ?>";
		var DELETE_DATA_SUCCESS = "<?php echo $DELETE_DATA_SUCCESS; ?>";
		var CONNECT_ERROR = "<?php echo $CONNECT_ERROR; ?>";
		var UPDATE_DATA_MONTH_SUCCESS = "<?php echo $UPDATE_DATA_MONTH_SUCCESS; ?>";

		var WORK_TIME_IS_MISSING = "<?php echo $WORK_TIME_IS_MISSING; ?>";
		var WORK_MOTH_IS_MISSING = "<?php echo $WORK_MOTH_IS_MISSING; ?>";
		var WORK_TIME_MONTH_IS_MISSING = "<?php echo $WORK_TIME_MONTH_IS_MISSING; ?>";



		// CONS 
		var TIME_KINTAI_DELAY_IN = parseInt("<?php echo $TIME_KINTAI_DELAY_IN; ?>");
		var TIME_KINTAI_EARLY_OUT = parseInt("<?php echo $TIME_KINTAI_EARLY_OUT; ?>");
		var LIST_DELAY_IN_DATE = [];
		var LIST_DELAY_OUT_DATE = [];




		// info
		var currentName = "<?php echo $_SESSION['auth_name']; ?>";


		// check modal data changed ? 
		dataChanged = false;

		rerunCreateNewMonthFlag = false;
		rerunCreateNewTotalMonthFlag = false;


		// ***Handler Script Region ****

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

		//==================================/// 
		//======= Draw data to table  ======//      OK
		//================================///  
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

			if (listDataWorkymd === null) {
				drawWhiteTable(showYear, showMonth);
				return;
			} else { //=====//PARAMETER listDataWorkymd not null 
				var html = '';
				// var jsonData = JSON.parse(listDataWorkymd);
				var workYmdList = listDataWorkymd;
				// Check List Month => If list  check the list of months if the list is missing or missing days then add it to the list
				var isCheck = checkMonthMissingData(workYmdList, showYear, showMonth);

				if (!isCheck) { // If future month => 
					drawWhiteTable(showYear, showMonth);
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
					// html += '<td name="templateB_Onshow"><span name="cdaystarthh">' + (data.daystarthh || '') + '</span>:<span name="cdaystartmm">' + (data.daystartmm ? data.daystartmm.toString().padStart(2, '0') : '') + '</span></td>';
					// html += '<td name="templateB_Onshow"><span name="cdayendhh">' + (data.dayendhh || '') + '</span>:<span name="cdayendmm">' + (data.dayendmm ? data.dayendmm.toString().padStart(2, '0') : '') + '</span></td>';
					// html += '<td><span name="cjobstarthh">' + (data.jobstarthh || '') + '</span>:<span name="cjobstartmm">' + (data.jobstartmm ? data.jobstartmm.toString().padStart(2, '0') : '') + '</span></td>';
					// html += '<td><span name="cjobendhh">' + (data.jobendhh || '') + '</span>:<span name="cjobendmm">' + (data.jobendmm ? data.jobendmm.toString().padStart(2, '0') : '') + '</span></td>';
					// html += '<td><span name="cofftimehh">' + (data.offtimehh ? data.offtimehh.toString() : '') + '</span>:<span name="cofftimemm">' + (data.offtimemm ? data.offtimemm.toString().padStart(2, '0') : '') + '</span></td>';
					// html += '<td><span name="cworkhh">' + (data.workhh ? data.workhh.toString().padStart(1, '0') : '') + '</span>:<span name="cworkmm">' + ((data.workhh !== 0 && data.workmm === 0) ? '00' : (data.workmm ? data.workmm.toString().padStart(2, '0') : '')) + '</span></td>';
					// html += '<td style="display:none;" ><span  name="cjanhh">' + (data.janhh ? data.janhh.toString().padStart(2, '0') : '') + '</span>:<span name="cjanmm">' + ((data.janhh !== 0 && data.janmm === 0) ? '00' : (data.janmm ? data.janmm.toString().padStart(2, '0') : '')) + '</span></td>';
					html += '<td name="templateB_Onshow">';
					if (data.daystarthh) {
						html += '<span name="cdaystarthh">' + data.daystarthh + '</span>:';
					}
					html += '<span name="cdaystartmm">' + (data.daystartmm ? data.daystartmm.toString().padStart(2, '0') : '') + '</span>';
					html += '</td>';

					html += '<td name="templateB_Onshow">';
					if (data.dayendhh) {
						html += '<span name="cdayendhh">' + data.dayendhh + '</span>:';
					}

					html += '<span name="cdayendmm">' + (data.dayendmm ? data.dayendmm.toString().padStart(2, '0') : '') + '</span>';
					html += '</td>';

					html += '<td>';
					if (data.jobstarthh) {
						html += '<span name="cjobstarthh">' + data.jobstarthh + '</span>:';
					}
					html += '<span name="cjobstartmm">' + (data.jobstartmm ? data.jobstartmm.toString().padStart(2, '0') : '') + '</span>';
					html += '</td>';

					html += '<td>';
					if (data.jobendhh) {
						html += '<span name="cjobendhh">' + data.jobendhh + '</span>:';
					}
					html += '<span name="cjobendmm">' + (data.jobendmm ? data.jobendmm.toString().padStart(2, '0') : '') + '</span>';
					html += '</td>';

					html += '<td>';
					if (data.offtimehh) {
						html += '<span name="cofftimehh">' + data.offtimehh + '</span>:';
					}
					html += '<span name="cofftimemm">' + (data.offtimemm ? data.offtimemm.toString().padStart(2, '0') : '') + '</span>';
					html += '</td>';

					html += '<td>';
					if (data.workhh !== 0 && data.workmm === 0) {
						html += '<span name="cworkhh">' + data.workhh.toString().padStart(1, '0') + '</span>:00';
					} else {
						if (data.workhh) {
							html += '<span name="cworkhh">' + data.workhh.toString().padStart(1, '0') + '</span>:';
						}
						html += '<span name="cworkmm">' + (data.workmm ? data.workmm.toString().padStart(2, '0') : '') + '</span>';
					}
					html += '</td>';

					html += '<td style="display:none;">';
					if (data.janhh !== 0 && data.janmm === 0) {
						html += '<span name="cjanhh">' + data.janhh.toString().padStart(2, '0') + '</span>:00';
					} else {
						if (data.janhh) {
							html += '<span name="cjanhh">' + data.janhh.toString().padStart(2, '0') + '</span>:';
						}
						html += '<span name="cjanmm">' + (data.janmm ? data.janmm.toString().padStart(2, '0') : '') + '</span>';
					}
					html += '</td>';

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
				handlerChangeTemplate();
			}
		}

		//======================================================/// 
		//======= Draw White table if data not exists  =========//     
		//=====================================================///  OK
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
				html += '<a href="#" onclick="fnClickTitle(' + (day - 1) + '); return false;">';
				if (dayOfWeekJapanese === '土') {
					html += '<span class="day-of-week-saturday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else if (dayOfWeekJapanese === '日') {
					html += '<span class="day-of-week-sunday">' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				} else {
					html += '<span>' + formattedDate + '(' + dayOfWeekJapanese + ')</span>';
				}
				html += '</a>';
				html += '</td>';
				html += '<td name="templateB_Onshow"><span name="cdaystarthh"></span><span name="cdaystartmm"></span></td>';
				html += '<td name="templateB_Onshow"><span name="cdayendhh"></span><span name="cdayendmm"></span></td>';
				html += '<td><span name="cjobstarthh"></span><span name="cjobstartmm"></span></td>';
				html += '<td><span name="cjobendhh"></span><span name="cjobendmm"></span></td>';
				html += '<td><span name="cofftimehh"></span><span name="cofftimemm"></span></td>';
				html += '<td><span name="cworkhh"></span><span name="cworkmm"></span></td>';
				html += '<td style="display : none;"><span name="cjanhh"></span><span name="cjanmm"></span></td>';
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
			handlerChangeTemplate();
		}
		//====================================================/// 
		//======= Change Table Template  ===================//    
		//=====================================================///  OK

		function handlerChangeTemplate() {
			var selectElement = document.getElementById('template_table');
			var selectedValue = selectElement.value;

			var listDayStartEnd = document.getElementsByName("templateB_Onshow");
			var colDayStartEnd = document.getElementsByName("cDayStartEnd_col");

			// var modalDayTime = document.getElementById('modal__dayTimeSelect');

			var elements = [listDayStartEnd, colDayStartEnd];
			if (selectedValue == 1) {  //Template A
				console.log("add hidden");
				// add class table_hidden
				elements.forEach(function (elementList) {
					elementList.forEach(function (element) {
						if (!element.classList.contains('table_hidden')) {
							element.classList.add('table_hidden');
						}
					});
				});

				// resize other column 
			} else if (selectedValue == 2) {//Template B
				// remove class table_hidden
				console.log("remove hidden");
				elements.forEach(function (elementList) {
					elementList.forEach(function (element) {
						if (element.classList.contains('table_hidden')) {
							element.classList.remove('table_hidden');
						}
					});
				});
			}
			resizeColumns(selectedValue);
			// change Modal 
		}
		// resize column 
		function resizeColumns(templateCode) {
			var thDate = document.querySelector('th[name="cDayTime_col"]');
			var thStartEnd = document.querySelector('th[name="cDayStartEnd_col"]');
			var thWorkTime = document.querySelector('th[name="cJobTime_col"]');
			var thBreakTime = document.querySelector('th[name="cOffTime_col"]');
			var thWorkHours = document.querySelector('th[name="cWorkTime_col"]');
			if (templateCode == 1) {
				thDate.style.width = '13%';
				// thStartEnd.style.width = '17%';
				thWorkTime.style.width = '17%';
				thBreakTime.style.width = '12%';
				thWorkHours.style.width = '12%';

			} else if (templateCode == 2) {
				thDate.style.width = '9%';
				thStartEnd.style.width = '16%';
				thWorkTime.style.width = '16%';
				thBreakTime.style.width = '9%';
				thWorkHours.style.width = '9%';

			}





		}

		//====================================================/// 
		//======= check the list of months  ===================//     if the list is missing or missing days then add it to the list 
		//=====================================================///  OK
		function checkMonthMissingData(workYmdList, year, month) {
			var flagIsMissingDataOfMonth = false;
			// IF DATA MISSING ???  
			var daysInMonth = new Date(year, month, 0).getDate();;
			// => Current Month if not data => Add new Data to DB 
			if (typeof workYmdList === 'undefined') {
				const currentDate = new Date();
				const currentYear = currentDate.getFullYear(); //
				const currentMonth = currentDate.getMonth() + 1; // 
				const parseYear = parseInt(year, 10);
				const parseMonth = parseInt(month, 10);
				if (currentYear === parseYear && currentMonth === parseMonth) {
					insertNewMonthData(year, month);
					return false;
				}
			}


			if (workYmdList.length !== daysInMonth) {
				var arrayMissingDay = [];
				var arrayDayOfWorkList = [];
				var genidDefault = 0;
				// add value to ararymissing #
				for (let i = 1; i <= daysInMonth; i++) {
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
					.filter(function (date) {
						return !arrayDayOfWorkList.includes(date);
					})
					.map(function (date) {
						return {
							genid: genidDefault, // genIdDefault
							workymd: date
						};
					});

				// call AJAX　　
				const data = JSON.stringify(filteredArray); // convert to json 
				const response = ajaxRequest(
					'kintaiRegController.php?type=' +
					TYPE_INSERT_MISSING_WORK_YEAR_MONTH_DAY + '&data=' + data,
					'GET',
					function (response) {
						//html_entity_decode($data);
						console.log(response);
					},
					function (errorStatus) {
						console.log("Connect ERROR: " + errorStatus);
					}
				);
				return false;
			}
			return workYmdList;
		}

		//======================================================/// 
		//======= Function insert data new month  ==============//     OK
		//=====================================================/// 
		function insertNewMonthData(selectedYear, selectedMonth) {
			strMonth = selectedMonth < 10 ? '0' + selectedMonth : selectedMonth;
			if (strMonth.length >= 3)
				strMonth = strMonth.slice(-2);

			var dataObject = {
				workym: (selectedYear + strMonth)
			};
			const data = JSON.stringify(dataObject); // convert to json 
			const response = ajaxRequest(
				'kintaiRegController.php?type=' +
				TYPE_INSERT_NEW_WORK_YEAR_MONTH_DAY + '&data=' + data,
				'GET',
				function (response) {
					console.log("INSERTed");
				},
				function (errorStatus) {
					console.log("Connect ERROR: " + errorStatus);
				}
			);
			setTimeout(function () {
				handleDateChange(selectedYear, selectedMonth);
			}, 1000);
		}

		function insertNewMonthTotalData(selectedYear, selectedMonth) {
			strMonth = selectedMonth < 10 ? '0' + selectedMonth : selectedMonth;
			if (strMonth.length >= 3)
				strMonth = strMonth.slice(-2);
			var dataObject = {
				workym: (selectedYear + strMonth)
			};
			const data = JSON.stringify(dataObject); // convert to json 
			const response = ajaxRequest(
				'kintaiRegController.php?type=' +
				TYPE_REGISTER_NEW_DATA_OF_MONTH + '&data=' + data,
				'GET',
				function (response) {
					console.log("Inserted new Total Month" + response);
				},
				function (errorStatus) {
					console.log("Connect ERROR: " + errorStatus);
				}
			);

			// reload 
			setTimeout(function () {
				handleDateChange(selectedYear, selectedMonth);
			}, 1000);
		}

		//====================================================/// 
		//======= Function add value to total month ===========//     OK
		//=====================================================/// 
		function drawDataToTotalMonth() {
			// loop foreach write data 
			var totalDayTime = 0;
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
				// var janHours = parseInt(cjanhhList[i].innerHTML);
				// var janMinutes = parseInt(cjanmmList[i].innerHTML);

				var janHours = 0;
				var janMinutes = 0;

				// flag -> check delay and early 
				isJobDay = false;
				isWorkDay = false;
				if (!isNaN(dayStartHours)) {
					// set WorkDay 
					nCountWorkDay += 1;
					isWorkDay = true;
				}
				if (!isNaN(jobStartHours)) {

					isJobDay = true;
					// set JobDay 
					nCountJobDay += 1;
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

				// 欠席、相対、遅刻自動計算
				// if (isJobDay && isWorkDay) {
				// 	// check 夜勤 
				// 	var startDayTime = dayStartHours * 60 + (!isNaN(dayStartMinutes) ? dayStartMinutes : 0);
				// 	var endDayTime = dayEndHours * 60 + dayEndMinutes;
				// 	var startJobTime = jobStartHours * 60 + (!isNaN(jobStartMinutes) ? jobStartMinutes : 0);
				// 	var endJobTime = jobEndHours * 60 + jobEndMinutes;
				// 	var isDayYakin = false;
				// 	var isJobYakin = false;

				// 	if (startDayTime > endDayTime) {
				// 		endDayTime += 1440;
				// 		isDayYakin = true;
				// 	}
				// 	if (startJobTime > endJobTime) {
				// 		endJobTime += 1440;
				// 		isJobYakin = true;
				// 		if (!isDayYakin && isJobYakin) {
				// 			endDayTime += 1440;
				// 			startDayTime += 1440;
				// 		}
				// 	}
				// 	// Delay Count
				// 	if ((startDayTime + TIME_KINTAI_DELAY_IN) > (startJobTime) && isJobYakin) {
				// 		nCountDelayIn += 1
				// 		LIST_DELAY_IN_DATE.push(i);
				// 	}
				// 	// Early off count  
				// 	if ((endDayTime + TIME_KINTAI_EARLY_OUT) < (endJobTime)) {
				// 		nCountEarlyOut += 1;
				// 		LIST_DELAY_OUT_DATE.push(i);
				// 	}

				// 	// not yakin 
				// 	if (!isJobYakin && !isDayYakin) {
				// 		if ((startDayTime + TIME_KINTAI_DELAY_IN) > (startJobTime)) {
				// 			nCountDelayIn += 1
				// 			LIST_DELAY_IN_DATE.push(i);
				// 		}
				// 	}
				// 	// total day time 
				// 	totalDayTime += (endJobTime - startDayTime);
				// 	//totalDayTime = endJobTime - startDayTime - (totalOffTimeHours*60 + totalOffTimeMinutes +TIME_KINTAI_DELAY_IN) ; // 総合勤務時間は毎日15分引く？　仕事準備
				// }

			}

			totalDayTime = (totalWorkHours * 60 + totalWorkMinutes);
			var totalDayHours = Math.floor(totalDayTime / 60);
			var totalDayMinutes = totalDayTime % 60;

			totalOffTimeHours += Math.floor(totalOffTimeMinutes / 60);
			totalOffTimeMinutes = totalOffTimeMinutes % 60;
			totalWorkHours += Math.floor(totalWorkMinutes / 60);
			totalWorkMinutes = totalWorkMinutes % 60;
			totalJanHours += Math.floor(totalJanMinutes / 60);
			totalJanMinutes = totalJanMinutes % 60;
			var offDay = nCountJobDay - nCountWorkDay;

			// //show area
			jobhour_top.innerHTML = "<strong>" + totalDayHours + "</strong>";
			jobminute_top.innerHTML = "<strong>" + totalDayMinutes + "</strong>";
			// workdays_top.innerHTML = "<strong>" + nCountWorkDay + "</strong>";  //  ??? 
			workdays_top.innerHTML = "<strong>" + nCountJobDay + "</strong>";
			jobdays_top.innerHTML = "<strong>" + nCountJobDay + "</strong>";

			// offdays_top.innerHTML = "<strong>" + offDay + "</strong>";
			// delaydays_top.innerHTML = "<strong>" + nCountDelayIn + "</strong>";
			// earlydays_top.innerHTML = "<strong>" + nCountEarlyOut + "</strong>";

			//edit area // get value from db 


			// jobhour.value = totalDayHours;
			// jobminute.value = totalDayMinutes;
			// workdays.value = nCountWorkDay;
			// jobdays.value = nCountJobDay;
			// offdays.value = offDay;
			// delaydays.value = nCountDelayIn;
			// earlydays.value = nCountEarlyOut;




			// over time 
			janhh_top.innerHTML = totalJanHours;
			janmm_top.innerHTML = totalJanMinutes;
			janhh.value = totalJanHours;
			janmm.value = totalJanMinutes;

			// Template


		}
		//===============================================================// 
		//======= Funtion for click day of week --> show register ======//     OK
		//============================================================///  
		function fnClickTitle(i) {
			var magamym = selyy.value;
			var magamymd = selmm.value;
			var workymd = $('[name="tworkymd"]').eq(i).val();
			if (workymd <= magamymd) {
				$('#btnUpd').prop('disabled', true);
				$('#btnDel').prop('disabled', true);
				alert("締切(" + magamymd + ")以前の日は登録と削除できません。");
			} else {
				$('#btnUpd').prop('disabled', false);
				$('#btnDel').prop('disabled', false);
			}
			$('#modal2').modal('toggle');
			// index, row
			$("#seq").val(i);
			//title
			$("#selkindate").text($('[name="tworkymd"]').eq(i).val());
			$("#uid").val($('[name="tuid"]').eq(i).val());
			$("#genid").val($('[name="tgenid"]').eq(i).val());
			$("#workymd").val($('[name="tworkymd"]').eq(i).val()).prop('disabled', true);
			$("#workymd2").val($('[name="tworkymd"]').eq(i).val());
			$("#daystarthh").val($('[name="tdaystarthh"]').eq(i).val());
			$("#daystartmm").val($('[name="tdaystartmm"]').eq(i).val()); // 
			$("#dayendhh").val($('[name="tdayendhh"]').eq(i).val());
			$("#dayendmm").val($('[name="tdayendmm"]').eq(i).val());     // 
			$("#jobstarthh").val($('[name="tjobstarthh"]').eq(i).val());
			$("#jobstartmm").val($('[name="tjobstartmm"]').eq(i).val());
			$("#jobendhh").val($('[name="tjobendhh"]').eq(i).val());
			$("#jobendmm").val($('[name="tjobendmm"]').eq(i).val());
			$("#offtimehh").val($('[name="tofftimehh"]').eq(i).val());
			$("#offtimemm").val($('[name="tofftimemm"]').eq(i).val());
			$("#workhh").val($('[name="tworkhh"]').eq(i).val()).prop('disabled', true);
			$("#workmm").val($('[name="tworkmm"]').eq(i).val()).prop('disabled', true);
			$("#comment").val($('[name="tcomment"]').eq(i).val());
			$("#bigo").val($('[name="tbigo"]').eq(i).val());

			// Set default minute == 00 
			setDefaultValueOfModal();
			updateChangeJobTimeModal();
			return true;
		}
		function setDefaultValueOfModal() {
			daystartmm.value = daystartmm.value === "" ? '00' : daystartmm.value;
			dayendmm.value = dayendmm.value === "" ? '00' : dayendmm.value;
			jobstartmm.value = jobstartmm.value === "" ? '00' : jobstartmm.value;
			jobendmm.value = jobendmm.value === "" ? '00' : jobendmm.value;
			offtimehh.value = offtimehh.value === "" ? '00' : offtimehh.value;
			offtimemm.value = offtimemm.value === "" ? '00' : offtimemm.value;
			workmm.value = workmm.value === "" ? '00' : workmm.value;
		}

		function getWorkTime(startHH, startMM, endHH, endMM, offTimeHour, offTimeMinute) {
			var totalWork = 0;
			if (!isNaN(startHH) && !isNaN(startMM) && !isNaN(endHH) && !isNaN(endMM)
				&& !isNaN(offTimeHour) && !isNaN(offTimeMinute)) {
				startHH = parseInt(startHH, 10);
				startMM = parseInt(startMM, 10);
				endHH = parseInt(endHH, 10);
				endMM = parseInt(endMM, 10);
				offTimeHour = parseInt(offTimeHour, 10);
				offTimeMinute = parseInt(offTimeMinute, 10);
				var timeStart = startHH * 60 + startMM;
				var timeEnd = endHH * 60 + endMM;
				// check 夜勤
				if (timeEnd < timeStart) {
					timeEnd += 1440;
				}
				var totalWork = timeEnd - timeStart - (offTimeHour * 60 + offTimeMinute);
				// return total minute
			}
			return totalWork;
		}

		//＝＝＝＝==========//
		// Popupの登録ボタン// OK
		//＝＝＝＝==========//
		function updateDataSeletedDay() {
			// get overTime  
			var overTimehh = 0;
			var overTimemm = 0;
			var overTime = 0;
			if (!isNaN(daystarthh.value) && !isNaN(dayendhh.value)) {

				var offTimeHHCheck = isNaN(offtimehh.value) ? '0' : offtimehh.value;
				var offTimeMMCheck = isNaN(offtimemm.value) ? '0' : offtimemm.value;

				var workDayTime = getWorkTime(daystarthh.value, daystartmm.value,
					dayendhh.value, dayendmm.value, offTimeHHCheck, offTimeMMCheck);

				var jobTime = getWorkTime(jobstarthh.value, jobstartmm.value, jobendhh.value,
					jobendmm.value, offTimeHHCheck, offTimeMMCheck);

				overTime = workDayTime - jobTime - TIME_KINTAI_DELAY_IN; // 仕事前　15分TIME_KINTAI_DELAY_IN　計算？？？　=> 残業　＝　残業　－　TIME_KINTAI_DELAY_IN　　
				//overTime = workDayTime - jobTime;    ***確認必要*** 残業の計算仕方

				if (overTime > 0) {
					overTimehh = Math.floor(overTime / 60);
					overTimemm = overTime % 60;
				}

			}
			//create object data 
			var dataObject = {
				selectedDate: workymd.value,
				daystarthh: daystarthh.value,
				daystartmm: daystartmm.value,
				dayendhh: dayendhh.value,
				dayendmm: dayendmm.value,
				jobstarthh: jobstarthh.value,
				jobstartmm: jobstartmm.value,
				jobendhh: jobendhh.value,
				jobendmm: jobendmm.value,
				offtimehh: offtimehh.value,
				offtimemm: offtimemm.value,
				workhh: workhh.value,
				workmm: workmm.value,
				janhh: overTimehh,
				janmm: overTimemm,
				comment: comment.value,
				bigo: bigo.value
			};
			// Call Ajax for delete data
			const data = JSON.stringify(dataObject); // convert to json 
			const response = ajaxRequest(
				'kintaiRegController.php?type=' +
				TYPE_REGISTER_DATA_OF_SELETED_DAY + '&data=' + data,
				'GET',
				function (response) {
					if (response === CONNECT_ERROR) {
						changeStatusMessageModal(false, ADD_DATA_ERROR_KINTAI);
						console.log("Connect ERROR: ");
						return;
					}
					dataChanged = true;
					changedDataCloseModal();
					changeStatusMessageModal(true, UPDATE_DATA_SUCCESS);

				},
				function (errorStatus) {
					changeStatusMessageModal(false, ADD_DATA_ERROR_KINTAI);
					console.log("Connect ERROR: ");
				}
			);
		}

		//＝＝＝＝==========//
		// Popupの削除ボタン// OK
		//＝＝＝＝==========//
		function deleteDataSelected() {
			console.log("DELETE");
			var parts = workymd.value.split("/");
			var year = parts[0];
			var month = parts[1];
			var day = parts[2];
			// Confimation Alert
			var confirmation = confirm(`${year}年${month}月${day}日 の勤務データを削除しますか`);
			// If OK
			if (confirmation) {
				// Call Ajax for delete data
				var dataObject = {
					selectedDate: workymd.value,
				};
				const data = JSON.stringify(dataObject); // convert to json 
				const response = ajaxRequest(
					'kintaiRegController.php?type=' +
					TYPE_DELETE_DATA_OF_SELETED_DAY + '&data=' + data,
					'GET',
					function (response) {
						if (response === CONNECT_ERROR) {
							changeStatusMessageModal(false, ADD_DATA_ERROR_KINTAI);
							console.log("Connect ERROR: " + errorStatus);
							return;
						}
						dataChanged = true;
						changedDataCloseModal();
						resetInputOfModal();
						changeStatusMessageModal(true, DELETE_DATA_SUCCESS);
						console.log("DELETED" + response);

					},
					function (errorStatus) {
						changeStatusMessageModal(false, ADD_DATA_ERROR_KINTAI);
						console.log("Connect ERROR: " + errorStatus);
					}
				);
			}
		}

		//====// re draw page after changed 
		function changedDataCloseModal() {
			if (dataChanged) {
				var parts = workymd.value.split("/");
				var year = parts[0];
				var month = parts[1];
				handleDateChange(year, month);
			}
			changeStatusMessageModal(null, "")
		}
		//====// clear form modal  OK
		function resetInputOfModal() {
			daystarthh.value = "";
			daystartmm.value = "";
			dayendhh.value = "";
			dayendmm.value = "";
			jobstarthh.value = "";
			jobstartmm.value = "";
			jobendhh.value = "";
			jobendmm.value = "";
			offtimehh.value = "";
			offtimemm.value = "";
			workhh.value = "";
			workmm.value = "";
			comment.value = "";
			bigo.value = "";

		}
		//====// draw message text after register or clear
		function changeStatusMessageModal(status, text) {
			var statusMessage = document.getElementById("statusMessage");
			if (status === null) {
				statusMessage.classList.remove("colorSuccess");
				statusMessage.classList.remove("colorError");
				statusMessage.style.visibility = "hidden";
				return;
			}

			if (status) {
				if (statusMessage.classList.contains("colorError")) {
					statusMessage.classList.remove("colorError");
				}
				statusMessage.style.visibility = "visible";
				statusMessage.classList.add("colorSuccess");

				statusMessage.innerText = text;
			} else {
				if (statusMessage.classList.contains("colorSuccess")) {
					statusMessage.classList.remove("colorSuccess");
				}
				statusMessage.classList.add("colorError");
				statusMessage.style.visibility = "visible";
				statusMessage.innerText = text;
			}
		}

		//====// 
		function updateChangeJobTimeModal() {
			var jobStartHour = parseInt(jobstarthh.value, 10);
			var jobStartMinute = parseInt(jobstartmm.value, 10);
			var jobEndHour = parseInt(jobendhh.value, 10);
			var jobEndMinute = parseInt(jobendmm.value, 10);
			var offTimeHour = parseInt(offtimehh.value, 10);
			var offTimeMinute = parseInt(offtimemm.value, 10);
			// Check Value is NOT  NUMBER 
			if (isNaN(jobStartHour) || isNaN(jobEndHour)) {
				workhh.value = '0';
				workmm.value = '0';
				return;
			} else {
				offTimeHour = isNaN(offTimeHour) ? 0 : offTimeHour;
				offTimeMinute = isNaN(offTimeMinute) ? 0 : offTimeMinute;
				jobStartMinute = isNaN(jobStartMinute) ? 0 : jobStartMinute;
				jobEndMinute = isNaN(jobEndMinute) ? 0 : jobEndMinute;

				var totalWorkMinutes = getWorkTime(jobStartHour, jobStartMinute, jobEndHour, jobEndMinute, offTimeHour, offTimeMinute);
				var totalWorkHours = Math.floor(totalWorkMinutes / 60);
				var remainingMinutes = totalWorkMinutes % 60;
				workhh.value = totalWorkHours.toString().padStart(2, '0');
				workmm.value = remainingMinutes.toString().padStart(2, '0');
			}
		}


		//====================================================================/// 
		//=======function for bind change year month combo box==============//     
		//============================================================///  
		function handleDateChange(selectedYear, selectedMonth) {

			var currentDate = new Date();
			var currentMonth = currentDate.getMonth() + 1; //
			var currentYear = currentDate.getFullYear();
			var isCurrentMonth = currentYear === selectedYear && selectedMonth === currentMonth;
			try {
				strMonth = selectedMonth < 10 ? '0' + selectedMonth : selectedMonth;
				if (strMonth.length >= 3)
					strMonth = strMonth.slice(-2);

				var dataObject = {
					workym: (selectedYear + strMonth)
				};
				const data = JSON.stringify(dataObject); // convert to json 
				const response = ajaxRequest(
					'kintaiRegController.php?type=' + TYPE_GET_DATA_KINMUHYO + '&data=' + data,
					'GET',
					function (response) {
						// check data current month = null => if === current create new data -> else print white 
						let parsedResponse = null;
						let parsedResponse2 = null;
						try {
							parsedResponse = JSON.parse(response);
							parsedResponse2 = parsedResponse;
							if (parsedResponse.includes(WORK_TIME_IS_MISSING)) {
								if (isCurrentMonth) {
									console.log("Inserted Time data");
									insertNewMonthData(currentYear, currentMonth);
									return;
								} else {
									handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
									handlerDateChangeUpdateTotalWorkMonth(null);
									drawDataToTotalMonth();
								}
							} else if (parsedResponse.includes(WORK_MOTH_IS_MISSING)) {
								if (isCurrentMonth) {
									console.log("Inserted month data");
									insertNewMonthTotalData(currentYear, currentMonth);
									return;
								} else {
									handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
									handlerDateChangeUpdateTotalWorkMonth(null);
									drawDataToTotalMonth();
								}
							} else if (parsedResponse.includes(WORK_TIME_MONTH_IS_MISSING)) {
								if (isCurrentMonth) {
									console.log("Inserted month and timedata");
									insertNewMonthData(currentYear, currentMonth);
									insertNewMonthTotalData(currentYear, currentMonth);
									return;
								} else {
									handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
									handlerDateChangeUpdateTotalWorkMonth(null);
									drawDataToTotalMonth();
								}
							} else if (parsedResponse.includes(CONNECT_ERROR)) {
								handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
								handlerDateChangeUpdateTotalWorkMonth(null);
								drawDataToTotalMonth();
								console.log("接続エラーが発生しました。");
								return;
							} else if (parsedResponse.includes(NO_DATA_KINTAI)) {
								handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
								handlerDateChangeUpdateTotalWorkMonth(null);
								drawDataToTotalMonth();
								console.log("データが存在しませんでした。又はエラーが発生しました。");
								return;
							} else {

							}
						} catch (error) {
							if (response.includes('workYmdList')) {
								handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, parsedResponse['workYmdList']);
								handlerDateChangeUpdateTotalWorkMonth(parsedResponse['workym']);
								drawDataToTotalMonth();
								return;
							}

							handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
							handlerDateChangeUpdateTotalWorkMonth(null);
							return;
						}
					},
					function (errorStatus) {  // connect faild
						return;
					}
				);
			} catch (error) {
				handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, null);
				handlerDateChangeUpdateTotalWorkMonth(null)
				drawDataToTotalMonth();
			}
		}

		function handleDateChangeUpdateWorkMonth(selectedYear, selectedMonth, data) {
			if (data === null || data === CONNECT_ERROR || data === undefined) {
				drawDayOfMonth(selectedYear, selectedMonth, null);
			} else {

				drawDayOfMonth(selectedYear, selectedMonth, data);
			}
		}

		function handlerDateChangeUpdateTotalWorkMonth(data) {
			if (data === null || data === CONNECT_ERROR || data === undefined) {
				drawInputDataTotalWorkMonth(null);
			} else {
				drawInputDataTotalWorkMonth(data);
			}
		}

		function drawInputDataTotalWorkMonth(data) {
			if (data === null || data === undefined) {
				return;
			}
			jobhour.value = data['jobhour2'];
			jobminute.value = data['jobminute2'];
			workdays.value = data['workdays2'];
			jobdays.value = data['jobdays2'];
			offdays.value = data['offdays'];
			delaydays.value = data['delaydays'];
			earlydays.value = data['earlydays'];

		}


		//＝＝＝＝==========//
		// =======Handler Select box of minute on popup=====// OK
		//＝＝＝＝==========//
		function handleInputFocus(input, select) {
			input.value = "";
			select.value = "";
		}

		function handleInputBlur(input, select) {
			if (input.value === "") {
				select.value = "";
			} else {
				select.value = "";
			}
		}
		function handleSelect(input, select, isReDrawWorkTime) {
			var selectedValue = select.value;
			if (selectedValue) {
				input.value = selectedValue < 10 ? ('0' + selectedValue.toString()) : selectedValue;
				select.value = "";
				if (input.value === '000') {
					input.value = '00';
				}
			}
			// Check Re-Draw Work Time total
			isReDrawWorkTime && updateChangeJobTimeModal();
		}


		//＝＝＝＝==============================================//
		// ========================月登録Button==================// 
		//＝＝＝＝==============================================//
		function MonthDataRegister() {
			//create object data 
			var currentWorkYm = selyy.value + selmm.value;
			var genId = document.getElementsByName('tgenid')[0].value; // get First value 
			var bigoText = "";  // ???

			var dataObject = {
				genid: genId,
				workym: currentWorkYm,
				jobhour: jobhour_top.innerText,
				jobminute: jobminute_top.innerText,
				jobhour2: jobhour.value,
				jobminute2: jobminute.value,
				janhour: janhh_top.innerText,
				janminute: janmm_top.innerText,
				janhour2: janhh.value,
				janminute2: janmm.value,
				workdays: workdays_top.innerText,
				workdays2: workdays.value,
				jobdays: jobdays_top.innerText,
				jobdays2: jobdays.value,
				offdays: offdays.value,
				delaydays: delaydays.value,
				earlydays: earlydays.value,
				bigo: bigoText
			};
			// Call Ajax for delete data
			const data = JSON.stringify(dataObject); // convert to json 
			const response = ajaxRequest(
				'kintaiRegController.php?type=' +
				TYPE_REGISTER_DATA_OF_MONTH + '&data=' + data,
				'GET',
				function (response) {
					if (response === CONNECT_ERROR) {
						console.log("Connect ERROR: ");
						return;
					}
					dataChanged = false; // 登録した後、修正中場所がない
					// if(dataChanged === true) {　　????　　// 月のデータが変更があれば次の月に切り替える前に確認
					// 	
					// }

					// show OK Alert 　
					window.alert(UPDATE_DATA_MONTH_SUCCESS);
				},
				function (errorStatus) {
					window.alert(UPDATE_DATA_MONTH_ERROR);
				}
			);
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

		//=====================================//
		//===========勤務表印刷================//
		//====================================//

		function preparePrint() {
			// Create Clone 
			var pageClone = document.documentElement.cloneNode(true);
			// Modify clone
			modifyPageClone(pageClone);
			// Create new window 
			var printWindow = window.open('', '_blank');
			if (printWindow) {





				// Write modified content to print page
				printWindow.document.open();
				printWindow.document.write(pageClone.outerHTML);
				printWindow.document.close();


				// print page completed
				printWindow.addEventListener('load', function () {
					// Print start
					printWindow.print();
				});
				// console.log(pageClone);
				// Close new tab after print 
				printWindow.addEventListener('afterprint', function () {
					printWindow.close();
				});
			} else {
				console.error(CAN_NOT_OPEN_NEW_TAB_PRINT);
			}
		}



		function modifyPageClone(pageClone) {

			var elementsToRemove = [];
			// get element 
			var headerElement = pageClone.querySelectorAll('.header_navbar');
			var titleCondition = pageClone.querySelectorAll('.title_condition');
			var pageHeaderText = pageClone.querySelectorAll('.page_header_text');
			var printButton = pageClone.querySelectorAll('.print_btn');
			var modal = pageClone.querySelectorAll('.modal');
			var editInput = pageClone.querySelectorAll('#footer___table__edit_input');

			addElementToList(elementsToRemove, headerElement);
			addElementToList(elementsToRemove, titleCondition);
			addElementToList(elementsToRemove, pageHeaderText);
			addElementToList(elementsToRemove, printButton);
			addElementToList(elementsToRemove, modal);
			addElementToList(elementsToRemove, editInput);

			// remove Element 
			for (var i = 0; i < elementsToRemove.length; i++) {
				elementsToRemove[i].remove();
			}

			// create new html
			var infoRow = document.createElement('div');
			infoRow.classList.add('row');

			var infoColLeft = document.createElement('div');
			infoColLeft.classList.add('col-md-3', 'text-left');

			var infoColRight = document.createElement('div');
			infoColRight.classList.add('col-md-3', 'text-right');
			var currentYm = selyy.value + '年' + selmm.value + '月    ';

			// add content
			var kintai_print_title_option = {
				workYm: '基準日',
				genId: '現場番号',
				printTime: '印刷日',
				department: '所属',
				name: '氏名',
				position: '氏役割'
			};
			infoColLeft.innerHTML = kintai_print_title_option.name + ' : ' + currentName;
			infoColRight.innerHTML = kintai_print_title_option.workYm + ' : ' + currentYm;

			// add children
			// infoRow.appendChild(infoColLeft);
			// infoRow.appendChild(infoColRight);

			var titleElement = pageClone.querySelector('.print_Infotext_region');
			titleElement.style.display = 'block';
			titleElement.parentNode.insertBefore(infoRow, titleElement.nextSibling);

			// Edit  Footer table 
			var workInfoLabel = pageClone.querySelector('#footer__workStatus-label');
			if (workInfoLabel) {
				workInfoLabel.setAttribute('rowspan', '2');
			}
			var workTimeLabel = pageClone.querySelector('#footer__workTime-label');
			var workHoursLabel = pageClone.querySelector('#footer__workhh-label');
			var workMinutesLabel = pageClone.querySelector('#footer__workmm-label');
			var workStatusLabel = pageClone.querySelector('#footer__workStatus-label');
			var jobDaysLabel = pageClone.querySelector('#footer__jobDays-label');
			var workDaysLabel = pageClone.querySelector('#footer__workDays-label');
			var offDaysLabel = pageClone.querySelector('#footer__offDays-label');
			var delayDaysLabel = pageClone.querySelector('#footer__delayDays-label');
			var earlyDaysLabel = pageClone.querySelector('#footer__earlyDays-label');

			// header text
			var titleElem = pageClone.querySelector('div[name="workYm_page_title"] .text-left');
			titleElem.innerText = currentYm + titleElem.innerText;
			titleElem.style.position = 'relative';
			titleElem.style.borderBottom = '2px double black';


			var workDaysShow = pageClone.querySelector('#workdays_top');
			var jobDaysShow = pageClone.querySelector('#jobdays_top');
			var offDaysShow = pageClone.querySelector('#offdays_top');
			var delayDaysShow = pageClone.querySelector('#delaydays_top');
			var earlydaysShow = pageClone.querySelector('#earlydays_top');

			var listEditBottomElem = [workTimeLabel, workHoursLabel, workMinutesLabel, workStatusLabel,
				jobDaysLabel, workDaysLabel, offDaysLabel, delayDaysLabel, earlyDaysLabel
				, workDaysShow, jobDaysShow, offDaysShow, delayDaysShow, earlydaysShow];


			for (var i = 0; i < listEditBottomElem.length; i++) {
				var element = listEditBottomElem[i];
				element.style.borderTop = '0px';
			}

			// value show 
			workDaysShow.innerText = workdays.value;
			jobDaysShow.innerText = jobdays.value;
			offDaysShow.innerText = offdays.value;
			delayDaysShow.innerText = delaydays.value;
			earlydaysShow.innerText = earlydays.value;




			// parrent table
			var tableFooter = pageClone.querySelector('#footer-Table');
			tableFooter.classList.remove('table-bordered');

			var jobhour_top = pageClone.querySelector('#jobhour_top');
			var jobminute_top = pageClone.querySelector('#jobminute_top');
			var jobHours = document.querySelector('#jobhour').value;
			var jobMinute = document.querySelector('#jobminute').value;


			jobhour_top.remove();
			jobminute_top.remove();


			// workHoursLabel.remove();
			workTimeLabel.style.padding = '1px';
			workTimeLabel.style.verticalAlign = 'middle';

			// workHoursLabel
			workHoursLabel.setAttribute('rowspan', '2');
			workHoursLabel.style.verticalAlign = 'middle';

			// workMinutesLabel
			workMinutesLabel.style.display = 'none';
			workMinutesLabel.style.borderBottom = '1px';
			workMinutesLabel.setAttribute('rowspan', '2');


			// var workStatusLabel 


			// var jobDaysLabel 

			// var workDaysLabel 

			// var offDaysLabel 

			// var delayDaysLabel

			// var earlyDaysLabel 

			workTimeLabel.style.width = '20%';
			workHoursLabel.style.width = 'auto';

			if (jobMinute < 10) {
				jobMinute = "05";
			} else if (jobMinute === 0) {
				jobMinute = "00";
			}
			workHoursLabel.innerText = jobHours + ':' + jobMinute;


			function addElementToList(list, element) {
				for (var j = 0; j < element.length; j++) {
					list.push(element[j]);
				}
			}

		}

// ***END Handler Script Region ****
	</script>


	<?php include('../inc/footer.php'); ?>
	<!-- </body>

</html> -->
