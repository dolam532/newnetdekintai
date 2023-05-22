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
					<label>基準日 :
						<select id="selyy" name="selyy" class="seldate" style="padding:5px;">
							<option value="2019">2019</option>
							<option value="2020">2020</option>
							<option value="2021">2021</option>
						</select>
						<select id="selmm" name="selmm" class="seldate" style="padding:5px;">
							<option>01</option>
							<option>02</option>
							<option>03</option>
							<option>04</option>
							<option>05</option>
							<option>06</option>
							<option>07</option>
							<option>08</option>
							<option>09</option>
							<option>10</option>
							<option>11</option>
							<option>12</option>
						</select>
					</label>
				</div>
			</div>
			<div class="col-md-3 text-right">
				<div class="title_btn">
					<p><a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnKintaiPrint();" class="btn btn-default" style="width: 120px;">勤務表印刷</a></p>
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
				<tbody>
					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(0);return false;">
								<span>05/01(月)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/01">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(1);return false;">
								<span>05/02(火)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/02">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(2);return false;">
								<span>05/03(水)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/03">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(3);return false;">
								<span>05/04(木)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/04">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(4);return false;">
								<span>05/05(金)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/05">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(5);return false;">
								<span style="color:blue;">05/06(土)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/06">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(6);return false;">
								<span style="color:red;">05/07(日)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/07">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(7);return false;">
								<span>05/08(月)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/08">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(8);return false;">
								<span>05/09(火)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/09">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(9);return false;">
								<span>05/10(水)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/10">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(10);return false;">
								<span>05/11(木)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/11">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(11);return false;">
								<span>05/12(金)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/12">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(12);return false;">
								<span style="color:blue;">05/13(土)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/13">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(13);return false;">
								<span style="color:red;">05/14(日)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/14">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(14);return false;">
								<span>05/15(月)</span>
							</a>
						</td>
						<td><span name="cdaystarthh">16</span>:<span name="cdaystartmm">34</span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh">10</span>:<span name="cjobstartmm">00</span></td>
						<td><span name="cjobendhh">18</span>:<span name="cjobendmm">00</span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/15">
							<input type="hidden" name="tdaystarthh" value="16">
							<input type="hidden" name="tdaystartmm" value="34">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="10">
							<input type="hidden" name="tjobstartmm" value="00">
							<input type="hidden" name="tjobendhh" value="18">
							<input type="hidden" name="tjobendmm" value="00">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(15);return false;">
								<span>05/16(火)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/16">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(16);return false;">
								<span>05/17(水)</span>
							</a>
						</td>
						<td><span name="cdaystarthh">17</span>:<span name="cdaystartmm">02</span></td>
						<td><span name="cdayendhh">17</span>:<span name="cdayendmm">04</span></td>
						<td><span name="cjobstarthh">10</span>:<span name="cjobstartmm">00</span></td>
						<td><span name="cjobendhh">18</span>:<span name="cjobendmm">00</span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/17">
							<input type="hidden" name="tdaystarthh" value="17">
							<input type="hidden" name="tdaystartmm" value="02">
							<input type="hidden" name="tdayendhh" value="17">
							<input type="hidden" name="tdayendmm" value="04">
							<input type="hidden" name="tjobstarthh" value="10">
							<input type="hidden" name="tjobstartmm" value="00">
							<input type="hidden" name="tjobendhh" value="18">
							<input type="hidden" name="tjobendmm" value="00">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(17);return false;">
								<span>05/18(木)</span>
							</a>
						</td>
						<td><span name="cdaystarthh">13</span>:<span name="cdaystartmm">21</span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh">10</span>:<span name="cjobstartmm">00</span></td>
						<td><span name="cjobendhh">18</span>:<span name="cjobendmm">00</span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/18">
							<input type="hidden" name="tdaystarthh" value="13">
							<input type="hidden" name="tdaystartmm" value="21">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="10">
							<input type="hidden" name="tjobstartmm" value="00">
							<input type="hidden" name="tjobendhh" value="18">
							<input type="hidden" name="tjobendmm" value="00">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(18);return false;">
								<span>05/19(金)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/19">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(19);return false;">
								<span style="color:blue;">05/20(土)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/20">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(20);return false;">
								<span style="color:red;">05/21(日)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/21">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(21);return false;">
								<span>05/22(月)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/22">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(22);return false;">
								<span>05/23(火)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/23">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(23);return false;">
								<span>05/24(水)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/24">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(24);return false;">
								<span>05/25(木)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/25">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(25);return false;">
								<span>05/26(金)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/26">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(26);return false;">
								<span style="color:blue;">05/27(土)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/27">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(27);return false;">
								<span style="color:red;">05/28(日)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/28">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(28);return false;">
								<span>05/29(月)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/29">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(29);return false;">
								<span>05/30(火)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/30">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>

					<tr>
						<td>
							<a href="http://localhost:8080/web/kintai/kintaiReg#" onclick="fnClickTitle(30);return false;">
								<span>05/31(水)</span>
							</a>
						</td>
						<td><span name="cdaystarthh"></span>:<span name="cdaystartmm"></span></td>
						<td><span name="cdayendhh"></span>:<span name="cdayendmm"></span></td>
						<td><span name="cjobstarthh"></span>:<span name="cjobstartmm"></span></td>
						<td><span name="cjobendhh"></span>:<span name="cjobendmm"></span></td>
						<td><span name="cofftimehh"></span>:<span name="cofftimemm"></span></td>
						<td><span name="cworkhh"></span></td>
						<td><span name="cworkmm"></span></td>
						<td><span name="cjanhh"></span>:<span name="cjanmm"></span></td>
						<td style="text-align:left"><span name="ccomment"></span></td>
						<td style="text-align:left"><span name="cbigo"></span>
							<input type="hidden" name="tuid" value="admin">
							<input type="hidden" name="tgenid" value="0">
							<input type="hidden" name="tworkymd" value="2023/05/31">
							<input type="hidden" name="tdaystarthh" value="">
							<input type="hidden" name="tdaystartmm" value="">
							<input type="hidden" name="tdayendhh" value="">
							<input type="hidden" name="tdayendmm" value="">
							<input type="hidden" name="tjobstarthh" value="">
							<input type="hidden" name="tjobstartmm" value="">
							<input type="hidden" name="tjobendhh" value="">
							<input type="hidden" name="tjobendmm" value="">
							<input type="hidden" name="tofftimehh" value="">
							<input type="hidden" name="tofftimemm" value="">
							<input type="hidden" name="tworkhh" value="">
							<input type="hidden" name="tworkmm" value="">
							<input type="hidden" name="tcomment" value="">
							<input type="hidden" name="tbigo" value="">
						</td>
					</tr>
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
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
					<td><strong>0</strong></td>
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