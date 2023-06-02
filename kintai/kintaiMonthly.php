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
</head>

<body>
	<?php include('../inc/menu.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-5 text-left">
				<div class="title_name">
					<span class="text-left">月勤務表</span>
				</div>
			</div>
			<div class="col-md-4 text-center">
				<div class="title_condition">
					<label>基準日 :</label>
					<select id="selyy" name="selyy" style="padding:5px;">
						<option value="2019">2019</option>
						<option value="2020">2020</option>
						<option value="2021">2021</option>
						<option value="2022">2022</option>
						<option value="2023">2023</option>
						<option value="2024">2024</option>
						<option value="2025">2025</option>
					</select>
					<select id="selmm" name="selmm" style="padding:5px;">
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
					<td><a href="http://localhost:8080/web/kintai/kintaiMonthly#"><span class="nameClick" name="cusername">GANASYS</span></a></td>
					<td><span name="cweekday">202305</span></td>
					<td><span name="cdaystart">0</span></td>
					<td><span name="cdayend">0</span></td>
					<td><span name="cjobstart">0</span></td>
					<td><span name="cjobend">0</span></td>
					<td><span>0:0</span></td>
					<td><span name="cofftime">0</span></td>
					<td><span name="cworkhh">0</span></td>
					<td><span name="cworkmm">0</span></td>
					<td><span name="cbigo"></span></td>
				</tr>



			</tbody>
		</table>
	</div>
</body>

</html>