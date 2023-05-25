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
	<title>休年届</title>
	<style type="text/css">
		.usertbl tr th {
			background-color: #D9EDF7;
			text-align: center;
		}

		.usertbl tr td {
			text-align: center;
		}

		.btn {
			width: 80px;
			height: 32px;
		}

		div label {
			padding: 5px;
		}
	</style>
</head>

<body>
	<?php include('../inc/header.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-2 text-left">
				<div class="title_name">
					<span class="text-left">休年届</span>
				</div>
			</div>
			<div class="col-md-3 text-center">
				<div class="title_condition custom-control custom-radio" id="divAllowok">
					<label>&nbsp;
						<input type="radio" name="searchAllowok" value="9" checked="">全体
						<input type="radio" name="searchAllowok" value="0">未決裁
						<input type="radio" name="searchAllowok" value="1">決裁完了
					</label>
				</div>
			</div>
			<div class="col-md-3" id="divUid">
				<div class="title_condition">
					<label>社員名 :
						<select id="searchUid" name="searchUid" style="padding:5px;">
							<option value="" selected=""></option>
							<option value="admin" "="">GANASYS</option>
							<option value=" arai" "="">新井 一郎</option>
							<option value=" hasegawa" "="">長谷川 敏明</option>
							<option value=" katou" "="">加藤 三郎</option>
							<option value=" satou" "="">佐藤 次郎</option>
							<option value=" tanaka" "="">田中 利明</option>
							<option value=" sakamoto" "="">坂本 龍馬</option>
							<option value=" zaw" "="">ゾウ テ</option>
							<option value=" thanh" "="">ハ ミン タン</option>
							<option value=" prasanna" "="">プラサンナ</option>
							<option value=" mikash" "="">ミカシュ</option>
							<option value=" dhanushka" "="">ダヌシカ</option>
							<option value=" myo" "="">ミョウ</option>
							<option value=" yamasita" "="">山下</option>
							<option value=" ishihara" "="">石原</option>
							<option value=" dhkang" "="">姜東勳</option>
							<option value=" dglee" "="">李 東揆</option>
						</select>
					</label>
				</div>
			</div>
	    	<div class=" col-md-2 text-right">
								<div class="title_condition">
									<label>基準日 :
										<select id="searchYY" name="searchYY" style="padding:5px;">
											<option value="2020">2020</option>
											<option value="2021">2021</option>
											<option value="2022">2022</option>
											<option value="2023">2023</option>
											<option value="2024">2024</option>
											<option value="2025">2025</option>
											<option value="2026">2026</option>
											<option value="2027">2027</option>
											<option value="2028">2028</option>
											<option value="2029">2029</option>
											<option value="2030">2030</option>
										</select>
									</label>
								</div>
				</div>
				<div class="col-md-2 text-right">
					<div class="title_btn">
						<input type="button" id="btnSearch" value="検索 ">&nbsp;&nbsp;&nbsp;
						<input type="button" id="btnNew" value="新規 ">
					</div>
				</div>

				<div class="form-group">
					<table class="table table-bordered datatable">
						<thead>
							<tr class="info">
								<th style="text-align: center; width: 12%;">申請日</th>
								<th style="text-align: center; width: 10%;">休暇区分</th>
								<th style="text-align: center; width: 16%;">申請期間</th>
								<th style="text-align: center; width: 10%;">申込日(時)</th>
								<th style="text-align: center; width: 16%;">年次期間</th>
								<th style="text-align: center; width: 8%;">総休暇数</th>
								<th style="text-align: center; width: 8%;">残日数</th>
								<th style="text-align: center; width: 8%;">決裁</th>
								<th style="text-align: center; width: auto;">暇中居る連絡先</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="9" align="center">登録されたデータがありません.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</body>

</html>