<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kyukamodel.php');
include('../inc/header.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css'>";
?>
<style>
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

	nav.navbar.navbar-inverse {
		margin-bottom: 0px;
	}

	.col-md-1.col-sm-1.text-left.all-div {
		width: 12.499999995%;
	}

	.popup-title {
		font-size: 20px;
		font-weight: bold;
	}


	/* For Mobile Landscape View iPhone XR,12Pro */
	@media screen and (max-device-width: 896px) and (orientation: landscape) {
		.container {
			width: 800px;
			padding-right: 10px;
			padding-left: 10px;
		}

		.text-left {
			font-size: 22px;
		}

		.all-div {
			padding: 0px;
		}

		.col-md-1.col-sm-1.text-left.all-div {
			padding-left: 15px;
			width: 10.5% !important;
		}

		.sub-bar {
			font-size: 12px;
		}

		.col-md-3.col-sm-3.sub-bar.text-center {
			padding-left: 10px;
			padding-right: 10px;
		}

		.col-md-2.col-sm-2.sub-bar.text-right.all-div {
			width: 17.5% !important;
		}

		div#divUid {
			margin-right: -35px;
		}

		.col-md-2.col-sm-2.sub-bar.text-right.all-div.last {
			width: 22.5% !important;
		}

		th.th0 {
			font-size: 12px;
		}

		th.th1 {
			width: 10% !important;
			font-size: 12px;
		}

		th.th2 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th3 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th4 {
			width: 8% !important;
			font-size: 12px;
		}

		th.th5 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th6 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th7 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th8 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th9 {
			font-size: 12px;
		}

		td.td0,
		td.td1,
		td.td2,
		td.td3,
		td.td4,
		td.td5,
		td.td6,
		td.td7,
		td.td8,
		td.td9 {
			font-size: 12px;
		}

		.popup-title {
			font-size: 14px;
		}

		.modal-body {
			font-size: 12px;
			padding: 5px;
		}

		.modal-header,
		.alert.alert-info {
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 10px;
		}

		.col-md-12.col-ms-12.sub-middle {
			margin-top: -15px;
			margin-bottom: -8px;
		}

		.alert.alert-warning,
		.table-notic {
			padding: 5px !important;
		}

		table.table.table-bordered.datatable {
			margin-bottom: 0px;
		}

		.modal-footer {
			padding-top: 5px;
			padding-bottom: 0px;
		}

		input.form-control {
			font-size: 12px;
		}

		.row.one {
			margin-top: -5px;
		}

		.row.two,
		.row.three {
			margin-top: -15px;
		}

		.row.four {
			margin-top: -20px;
		}

		.form-control {
			height: 28px;
		}

		.btn {
			height: 30px;
		}
	}

	/* For Mobile Landscape View iPhone X,6,7,8 PLUS */
	@media screen and (max-device-width: 837px) and (orientation: landscape) {
		div#tile_header {
			width: 805px;
		}

		.col-md-1.col-sm-1.col-sx-1.text-left.all-div {
			padding-left: 20px;
			width: 12% !important;
		}

		.col-md-3.col-sm-3.col-sx-3.sub-bar.text-center,
		.col-md-1.col-sm-1.col-sx-1.text-left.all-div,
		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div {
			float: left;
		}

		.col-md-3.col-sm-3.col-sx-3.sub-bar.text-center {
			margin-left: 0px;
		}

		div#divUid {
			float: left;
			margin-right: 20px;
			margin-left: 0px;
		}


		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div {
			margin-left: -20px;
		}

		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div.last {
			display: inline-inline-block;
		}

		.title_btn {
			width: 215px;
		}

		th.th1 {
			width: 10% !important;
		}

		th.th2 {
			width: 9% !important;
		}

		th.th3 {
			width: 18% !important;
		}

		th.th4 {
			width: 9% !important;
		}

		th.th5 {
			width: 18% !important;
		}

		th.th6 {
			width: 7% !important;
		}

		th.th7 {
			width: 7% !important;
		}

		th.th8 {
			width: 9% !important;
		}

		.modal-dialog {
			width: 582px;
		}

		.col-md-3.col-sm-3.col-sx-3.kyukaymd,
		.col-md-5.col-sm-5.col-sx-5.kyukacode,
		.col-md-4.col-sm-4.col-sx-4.kyukatype {
			width: 150px;
			float: left;
		}

		.col-md-3.col-sm-3.col-sx-3.day {
			width: 150px;
			float: left;
		}

		.col-md-2.col-sm-2.col-sx-2.no {
			width: 95px;
			float: left;
		}

		.col-md-4.col-sm-4.col-sx-4.address {
			width: 200px;
			float: left;
		}

		.modal-footer {
			padding-top: 0px;
			padding-bottom: 15px;
		}

		.col-md.text-center {
			margin-top: 10px;
		}
	}

	/* For Mobile portrait View iPhone XR,12Pro */
	@media screen and (max-device-width: 414px) and (orientation: portrait) {
		.col-md-1.col-sm-1.col-sx-1.text-left.all-div {
			width: 35% !important;
			height: 25px;
		}

		.title_name {
			height: 30px;
		}

		.text-left {
			font-size: 22px;
		}

		.sub-bar {
			font-size: 12px;
		}

		.col-md-3.col-sm-3.col-sx-3.sub-bar.text-center {
			padding-left: 0px;
			width: 52%;
			height: 20px;
			float: left;
		}

		div#divUid {
			width: 48%;
			float: left;
		}

		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div {
			padding-left: 10px;
			margin-right: -10px;
			width: 42%;
			float: left;
		}

		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div.last {
			width: 60% !important;
			float: left;
			display: inline;
		}

		td.td0,
		td.td1,
		td.td2,
		td.td3,
		td.td4,
		td.td5,
		td.td6,
		td.td7,
		td.td8,
		td.td9 {
			font-size: 12px;
		}

		th.th0 {
			font-size: 12px;
		}

		th.th1 {
			width: 11% !important;
			font-size: 12px;
		}

		th.th2 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th3 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th4 {
			width: 8% !important;
			font-size: 12px;
		}

		th.th5 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th6 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th7 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th8 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th9 {
			font-size: 12px;
		}

		th.th1 {
			width: 11% !important;
			font-size: 12px;
		}

		th.th2 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th3 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th4 {
			width: 8% !important;
			font-size: 12px;
		}

		th.th5 {
			width: 16% !important;
			font-size: 12px;
		}

		th.th6 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th7 {
			width: 7% !important;
			font-size: 12px;
		}

		th.th8 {
			width: 9% !important;
			font-size: 12px;
		}

		th.th9 {
			font-size: 12px;
		}

		.form-group {
			overflow-y: scroll;
		}
	}

	/* For Mobile portrait View iPhone X,6,7,8 PLUS */
	@media screen and (max-device-width: 375px) and (orientation: portrait) {
		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div {
			margin-right: -16px;
			width: 44%;
		}

		.col-md-3.col-sm-3.col-sx-3.sub-bar.text-center {
			padding: 0px;
		}

		div#divUid {
			padding: 0px;
		}

		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div {
			padding-left: 10px;
		}

		.col-md-2.col-sm-2.col-sx-2.sub-bar.text-right.all-div.last {
			display: inline;
		}
	}
</style>
<?php include('../inc/menu.php'); ?>
<div class="container">
	<?php
	if (isset($_SESSION['save_success'])) {
	?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['save_success']; ?>
		</div>
	<?php
		unset($_SESSION['save_success']);
	}
	?>
	<form method="post">
		<div class="row">
			<div class="col-md-1 col-sm-1 col-sx-1 text-left all-div" style="width: 12.499999995%">
				<div class="title_name">
					<span class="text-left">休暇届</span>
				</div>
			</div>
			<div class="col-md-3 col-sm-3 col-sx-3 sub-bar text-center">
				<div class="title_condition custom-control custom-radio" id="divAllowok">
					<label>&nbsp;
						<?php
						foreach (ConstArray::$search_allowok as $key => $value) {
						?>
							<input type='radio' name='searchAllowok' value='<?= $key ?>' <?php if ($key == $_POST['searchAllowok']) {
																								echo ' checked="checked"';
																							} ?>>
							<?= $value ?>
							</input>
						<?php
						}
						?>
					</label>
				</div>
			</div>

			<div class="col-md-3 col-sm-3 col-sx-3 sub-bar all-div" id="divUid">
				<div class="title_condition">
					<label>社員名 :
						<select id="searchUid" name="searchUid" style="padding:5px;">
							<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
								<option value="" selected="">選択なし</option>
								<?php
								foreach ($user_list as $value) {
								?>
									<option value="<?= $value['uid'] ?>" <?php if ($value['uid'] == $_POST['searchUid']) {
																				echo ' selected="selected"';
																			} ?>>
										<?= $value['name'] ?>
									</option>
								<?php
								}
								?>
							<?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
								<option value="<?= $_SESSION['auth_uid'] ?>"><?= $_SESSION['auth_name'] ?></option>
							<?php endif; ?>
						</select>
					</label>
				</div>
			</div>

			<div class="col-md-2 col-sm-2 col-sx-2 sub-bar text-right all-div">
				<div class="title_condition">
					<label>基準日 :
						<select id="searchYY" name="searchYY" style="padding:5px;">
							<option value="" selected="selected">選択なし</option>
							<?php
							foreach (ConstArray::$search_year as $key => $value) {
							?>
								<option value="<?= $key ?>" <?php if ($value == $_POST['searchYY']) {
																echo ' selected="selected"';
															} ?>>
									<?= $value ?>
								</option>
							<?php
							}
							?>
						</select>
					</label>
				</div>
			</div>

			<div class="col-md-2 col-sm-2 col-sx-2 sub-bar text-right all-div last" style="width: 20.8%">
				<div class="title_btn">
					<input type="submit" name="btnSearchReg" value="検索 ">&nbsp;
					<input type="button" id="btnNew" value="新規 ">&nbsp;
					<input type="button" id="btnAnnt" value="お知らせ ">
				</div>
			</div>
		</div>
		<div class="form-group">
			<table class="table table-bordered datatable">
				<thead>
					<tr class="info">
						<th class="th0" style="text-align: center; width: 1%;">ID</th>
						<th class="th1" style="text-align: center; width: 11%;">申請日</th>
						<th class="th2" style="text-align: center; width: 10%;">休暇区分</th>
						<th class="th3" style="text-align: center; width: 16%;">申請期間</th>
						<th class="th4" style="text-align: center; width: 10%;">申込日(時)</th>
						<th class="th5" style="text-align: center; width: 16%;">年次期間</th>
						<th class="th6" style="text-align: center; width: 8%;">総休暇数</th>
						<th class="th7" style="text-align: center; width: 8%;">残日数</th>
						<th class="th8" style="text-align: center; width: 8%;">決裁</th>
						<th class="th9" style="text-align: center; width: auto;">暇中居る連絡先</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($userkyuka_list)) { ?>
						<tr>
							<td colspan="12" align="center">登録されたデータがありません.</td>
						</tr>
						<?php } elseif (!empty($userkyuka_list)) {
						foreach ($userkyuka_list as $userkyuka) {
						?>
							<tr>
								<td class="td0"><span><?= $userkyuka['uid'] ?></span></td>
								<td class="td1"><span><?= $userkyuka['kyukaymd'] ?></span></td>
								<td class="td2"><span><?= $userkyuka['name'] ?></span></td>
								<td class="td3">
									<span><?= $userkyuka['strymd'] ?>~<?= $userkyuka['endymd'] ?></span>
								</td>
								<td class="td4"><span><?= $userkyuka['ymdcnt'] ?>日(<?= $userkyuka['timecnt'] ?>時)</span></td>
								<td class="td5"><span><?= $userkyuka['vacationstr'] ?>~<?= $userkyuka['vacationend'] ?></span></td>
								<td class="td6"><span><?= $userkyuka['oldcnt'] + $userkyuka['newcnt'] ?></span></td>
								<td class="td7"><span><?= $userkyuka['oldcnt'] + $userkyuka['newcnt'] - $userkyuka['usecnt'] - (int)($userkyuka['usetime'] / 8) ?></span></td>
								<td class="td8"><span name="callowok">
										<?php
										if ($userkyuka['allowok'] == "0") { ?>
											<span style="color:red">未決裁</span>
										<?php } else { ?>
											<span>決裁完了</span>
										<?php } ?>
									</span>
								</td>
								<td class="td9"><span><?= $userkyuka['destplace'] ?></span></td>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</form>

	<?php if ($_SESSION['auth_type'] == constant('ADMIN')) : ?>
		<form method="post">
			<div class="row">
				<div class="col-md-1 col-sm-1 col-sx-1 text-left all-div" style="width: 15%">
					<div class="title_name">
						<span class="text-left">休暇情報</span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<table class="table table-bordered datatable">
					<thead>
						<tr class="info">
							<th class="th0" style="text-align: center; width: 2%;">ID</th>
							<th class="th1" style="text-align: center; width: 8%;">休暇ID</th>
							<th class="th2" style="text-align: center; width: 10%;">始まり</th>
							<th class="th3" style="text-align: center; width: 16%;">終わり</th>
							<th class="th4" style="text-align: center; width: 10%;">古い数</th>
							<th class="th5" style="text-align: center; width: 16%;">新しい数</th>
							<th class="th6" style="text-align: center; width: 8%;">使用数</th>
							<th class="th7" style="text-align: center; width: 8%;">使用時間</th>
							<th class="th8" style="text-align: center; width: auto;">休憩回数</th>
						</tr>
					</thead>
					<tbody>
						<?php if (empty($vacationinfo_list)) { ?>
							<tr>
								<td colspan="12" align="center">登録されたデータがありません.</td>
							</tr>
							<?php } elseif (!empty($vacationinfo_list)) {
							foreach ($vacationinfo_list as $vacationinfo) {
							?>
								<tr>
									<td class="td0"><a href="#"><span class="showModal"><?= $vacationinfo['uid'] ?></span></td>
									<td class="td1"><span><?= $vacationinfo['vacationid'] ?></span></td>
									<td class="td2"><span><?= $vacationinfo['vacationstr'] ?></span></td>
									<td class="td3"><span><?= $vacationinfo['vacationend'] ?></span></td>
									<td class="td4"><span><?= $vacationinfo['oldcnt'] ?></td>
									<td class="td5"><span><?= $vacationinfo['newcnt'] ?></span></td>
									<td class="td6"><span><?= $vacationinfo['usecnt'] ?></span></td>
									<td class="td7"><span><?= $vacationinfo['usetime'] ?></span></td>
									<td class="td8"><span><?= $vacationinfo['restcnt'] ?></span></td>
								</tr>
						<?php }
						} ?>
					</tbody>
				</table>
			</div>
		</form>
	<?php endif; ?>

	<div class="row">
		<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form method="post">
					<div class="modal-content">
						<div class="modal-header">休年届登録(<span id="sname">New</span>)
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row one">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
									<label for="kyukaymd">申請日</label>
									<input type="text" class="form-control" name="kyukaymd" style="text-align: center" value="<?= date('Y/m/d'); ?>" readonly>
									<?php foreach ($result_userkyuka_select as $key) : ?>
										<input type="hidden" name="companyid" value="<?= $key['companyid'] ?>">
										<input type="hidden" name="uid" value="<?= $key['uid'] ?>">
										<input type="hidden" name="allowid" value="<?= $key['allowid'] ?>">
										<input type="hidden" name="allowdt" value="<?= $key['allowdt'] ?>">
										<input type="hidden" name="vacationid" value="<?= $key['vacationid'] ?>">
										<input type="hidden" id="vacationstr" name="vacationstr" value="<?= $key['vacationstr'] ?>">
										<input type="hidden" id="vacationend" name="vacationend" value="<?= $key['vacationend'] ?>">
										<input type="hidden" id="oldcnt" name="oldcnt" value="<?= $key['oldcnt'] ?>">
										<input type="hidden" id="newcnt" name="newcnt" value="<?= $key['newcnt'] ?>">
										<input type="hidden" id="restcnt" name="restcnt" value="<?= $key['restcnt'] ?>">
										<input type="hidden" id="kyukatimelimit" name="kyukatimelimit" value="<?= $key['kyukatimelimit'] ?>">
									<?php endforeach; ?>
								</div>
								<div class="col-md-5 col-sm-5 col-sx-5 kyukacode">
									<label for="kyukacode">休暇区分</label>
									<select class="form-control" id="kyukaname" name="kyukacode">
										<option value=""></option>
										<?php
										foreach ($codebase_list as $key) {
										?>
											<option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 kyukatype">
									<label for="kyukatype">申込区分</label>
									<div class="custom-control custom-radio">
										&nbsp;
										<input type="radio" name="kyukatype" value="0">時間
										&nbsp;&nbsp;
										<input type="radio" name="kyukatype" value="1">日付
									</div>
								</div>
							</div>
							<br>
							<div class="row two">
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strymd">期間(F)</label>
									<input type="text" class="form-control" id="strymd" name="strymd" placeholder="" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endymd">期間(T)</label>
									<input type="text" class="form-control" id="endymd" name="endymd" placeholder="" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strtime">時間(F)</label>
									<input type="text" class="form-control" id="strtime" name="strtime" placeholder="" required="required" maxlength="2" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endtime">時間(T)</label>
									<input type="text" class="form-control" id="endtime" name="endtime" placeholder="" required="required" maxlength="2" style="text-align: center">
								</div>
							</div>
							<br>
							<div class="row three">
								<?php
								if (!empty($result_userkyuka_select)) {
									foreach ($result_userkyuka_select as $key) {
										if ($key['uid'] == $_SESSION['auth_uid']) {
								?>
											<div class="col-md-2 col-sm-2 col-sx-2 no">
												<label for="totcnt">当年付与</label>
												<input type="hidden" id="va_uid" name="va_uid" value="<?= $key['uid'] ?>">
												<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center" readonly value="<?= $key['oldcnt'] + $key['newcnt'] ?>">
											</div>
											<div class="col-md-2 col-sm-2 col-sx-2 no">
												<label for="usecnt">使用日数</label>
												<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center" readonly value="<?= $key['usecnt'] ?>">
											</div>
											<div class=" col-md-2 col-sm-2 col-sx-2 no">
												<label for="usetime">使用時間</label>
												<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center" readonly value="<?= $key['usetime'] ?>">
											</div>
										<?php
										} else {
										?>
											<div class="col-md-2 col-sm-2 col-sx-2 no">
												<label for="totcnt">当年付与</label>
												<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center" readonly value="0">
											</div>
											<div class="col-md-2 col-sm-2 col-sx-2 no">
												<label for="usecnt">使用日数</label>
												<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center" readonly value="0">
											</div>
											<div class=" col-md-2 col-sm-2 col-sx-2 no">
												<label for="usetime">使用時間</label>
												<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center" readonly value="0">
											</div>
									<?php
										}
									}
								} else {
									?>
									<div class="col-md-2 col-sm-2 col-sx-2 no">
										<label for="totcnt">当年付与</label>
										<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center" readonly value="0">
									</div>
									<div class="col-md-2 col-sm-2 col-sx-2 no">
										<label for="usecnt">使用日数</label>
										<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center" readonly value="0">
									</div>
									<div class=" col-md-2 col-sm-2 col-sx-2 no">
										<label for="usetime">使用時間</label>
										<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center" readonly value="0">
									</div>
								<?php
								}
								?>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="ymdcnt">申込日</label>
									<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="timecnt">申込時間</label>
									<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="allowok">決裁</label>
									<div class="custom-control custom-radio">
										<input type="radio" name="allowok" value="0">未決裁
										<input type="radio" name="allowok" value="1">決裁完了
									</div>
								</div>
							</div>
							<br>
							<div class="row four">
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="destcode">暇中居る連絡先</label>
									<div class="custom-control custom-radio">
										&nbsp;&nbsp;
										<input type="radio" name="destcode" value="0">日本
										<input type="radio" name="destcode" value="1">韓国
										<input type="radio" name="destcode" value="2">その他
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="destplace">場所</label>
									<input type="text" class="form-control" name="destplace" id="destplace" placeholder="" required="required" style="text-align: left">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="desttel">Tel</label>
									<input type="text" class="form-control" name="desttel" id="desttel" placeholder="" required="required" style="text-align: left">
								</div>
							</div>
						</div>
						<div class="modal-footer" style="text-align: center">
							<div class="col-md-3 col-sm-3 col-sx-3"></div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<input type="submit" name="SaveKyuka" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-primary btn-md" id="btnClear" role="button">クリア </a>
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-primary btn-md" data-dismiss="modal" role="button">閉じる </a>
								</p>
							</div>
							<div class="col-md-3 col-sm-3 col-sx-3"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><span class="popup-title">お知らせ(注意)</span>
						<button class="close" data-dismiss="modal">x</button>
					</div>

					<div class="modal-body" style="text-align: left">
						<div class="row">
							<div class="col-md-12 col-ms-12">
								<div class="alert alert-warning">
									<strong>1&nbsp;</strong>事前許可が必要なので、担当者に許可の届け (休暇届) を提出すること。<br>
									<strong>&nbsp;・</strong>原則として1週間前までに、少なくとも前々日までに提出すること。<br>
									<strong>&nbsp;・</strong>連続4日以上 (所定休日が含まれる場合を含む。) の休暇を取得するときは、1ヵ月前までに提出すること。<br>
									<strong>&nbsp;・</strong>緊急・病気の場合は、その時点ですぐに提出すること。<br>
									<strong>2&nbsp;</strong>年間で5日分はその年で必ず取ること。<br>
									<strong>3&nbsp;</strong>有給休暇は1年に限って繰り越しできます (2の5日分は除外、 0.5日分は除外)。<br>
									<strong>4&nbsp;</strong>半休(5時間以内)の場合は0.5日にて表現してください。その他詳しい内容は担当者に聞いてください。
								</div>
							</div>
							<div class="col-md-12 col-ms-12 sub-middle">
								<div class="alert alert-info" style="margin-bottom: 10px;">
									<strong>※&nbsp;年次有給休暇</strong>
								</div>
							</div>
							<div class="col-md-12 col-ms-12">
								<table class="table table-bordered datatable">
									<thead>
										<tr>
											<th class="info table-notic" style="text-align: center; color: #31708f;">勤続年数</th>
											<td class="table-notic" style="text-align: center;">6ヵ月以内</td>
											<td class="table-notic" style="text-align: center;">6ヵ月</td>
											<td class="table-notic" style="text-align: center;">1年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">2年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">3年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">4年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">5年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">5年6ヵ月以上</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="info" style="text-align: center; color: #31708f;">付与日数</th>
											<td class="table-notic" style="text-align: center;">無し</td>
											<td class="table-notic" style="text-align: center;">10日</td>
											<td class="table-notic" style="text-align: center;">11日</td>
											<td class="table-notic" style="text-align: center;">12日</td>
											<td class="table-notic" style="text-align: center;">14日</td>
											<td class="table-notic" style="text-align: center;">16日</td>
											<td class="table-notic" style="text-align: center;">18日</td>
											<td class="table-notic" style="text-align: center;">20日</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer" style="padding-bottom: 5px;">
							<div class="col-md text-center">
								<a class="btn btn-primary btn-md" data-dismiss="modal" role="button">閉じる </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="modal" id="modal3" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form method="post">
					<div class="modal-content">
						<div class="modal-header">休年届登録(<span id="sname">New</span>)
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row one">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
									<label for="kyukaymd">申請日</label>
									<input type="text" class="form-control" name="kyukaymd" style="text-align: center" value="<?= date('Y/m/d'); ?>" readonly>
									<?php foreach ($result_userkyuka_select as $key) : ?>
										<input type="hidden" name="companyid" value="<?= $key['companyid'] ?>">
										<input type="hidden" name="uid" value="<?= $key['uid'] ?>">
										<input type="hidden" name="allowid" value="<?= $key['allowid'] ?>">
										<input type="hidden" name="allowdt" value="<?= $key['allowdt'] ?>">
										<input type="hidden" name="vacationid" value="<?= $key['vacationid'] ?>">
										<input type="hidden" id="vacationstr" name="vacationstr" value="<?= $key['vacationstr'] ?>">
										<input type="hidden" id="vacationend" name="vacationend" value="<?= $key['vacationend'] ?>">
										<input type="hidden" id="oldcnt" name="oldcnt" value="<?= $key['oldcnt'] ?>">
										<input type="hidden" id="newcnt" name="newcnt" value="<?= $key['newcnt'] ?>">
										<input type="hidden" id="restcnt" name="restcnt" value="<?= $key['restcnt'] ?>">
										<input type="hidden" id="kyukatimelimit" name="kyukatimelimit" value="<?= $key['kyukatimelimit'] ?>">
									<?php endforeach; ?>
								</div>
								<div class="col-md-5 col-sm-5 col-sx-5 kyukacode">
									<label for="kyukacode">休暇区分</label>
									<select class="form-control" id="kyukaname" name="kyukacode">
										<option value=""></option>
										<?php
										foreach ($codebase_list as $key) {
										?>
											<option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
										<?php
										}
										?>
									</select>
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 kyukatype">
									<label for="kyukatype">申込区分</label>
									<div class="custom-control custom-radio">
										&nbsp;
										<input type="radio" name="kyukatype" value="0">時間
										&nbsp;&nbsp;
										<input type="radio" name="kyukatype" value="1">日付
									</div>
								</div>
							</div>
							<br>
							<div class="row two">
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strymd">期間(F)</label>
									<input type="text" class="form-control" id="strymd" name="strymd" placeholder="" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endymd">期間(T)</label>
									<input type="text" class="form-control" id="endymd" name="endymd" placeholder="" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strtime">時間(F)</label>
									<input type="text" class="form-control" id="strtime" name="strtime" placeholder="" required="required" maxlength="2" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endtime">時間(T)</label>
									<input type="text" class="form-control" id="endtime" name="endtime" placeholder="" required="required" maxlength="2" style="text-align: center">
								</div>
							</div>
							<br>
							<div class="row three">
								<?php
								$last_key = end($result_userkyuka_select);
								foreach ($result_userkyuka_select as $key) {
									if ($key == $last_key) {
								?>
										<div class="col-md-2 col-sm-2 col-sx-2 no">
											<label for="totcnt">当年付与</label>
											<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center" readonly value="<?= $key['oldcnt'] + $key['newcnt'] ?>">
										</div>
										<div class="col-md-2 col-sm-2 col-sx-2 no">
											<label for="usecnt">使用日数</label>
											<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center" readonly value="<?= $key['usecnt'] ?>">
										</div>
										<div class=" col-md-2 col-sm-2 col-sx-2 no">
											<label for="usetime">使用時間</label>
											<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center" readonly value="<?= $key['usetime'] ?>">
										</div>
								<?php
									}
								}
								?>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="ymdcnt">申込日</label>
									<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="timecnt">申込時間</label>
									<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="allowok">決裁</label>
									<div class="custom-control custom-radio">
										<input type="radio" name="allowok" value="0">未決裁
										<input type="radio" name="allowok" value="1">決裁完了
									</div>
								</div>
							</div>
							<br>
							<div class="row four">
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="destcode">暇中居る連絡先</label>
									<div class="custom-control custom-radio">
										&nbsp;&nbsp;
										<input type="radio" name="destcode" value="0">日本
										<input type="radio" name="destcode" value="1">韓国
										<input type="radio" name="destcode" value="2">その他
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="destplace">場所</label>
									<input type="text" class="form-control" name="destplace" id="destplace" placeholder="" required="required" style="text-align: left">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="desttel">Tel</label>
									<input type="text" class="form-control" name="desttel" id="desttel" placeholder="" required="required" style="text-align: left">
								</div>
							</div>
						</div>
						<div class="modal-footer" style="text-align: center">
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<input type="submit" name="SaveKyuka" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-primary btn-md" data-dismiss="modal" role="button">閉じる </a>
								</p>
							</div>
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	//신규버튼 
	$(document).on('click', '#btnNew', function(e) {
		var totcnt = $("#totcnt").val();
		var usecnt = $("#usecnt").val();
		var usetime = $("#usetime").val();
		var va_uid = $("#va_uid").val();
		// var auth_uid = '<!?php echo $_SESSION['auth_uid'] ?>';
		// alert(auth_uid);
		// alert(va_uid);
		// if (va_uid === 'tarang') {
		// 	alert("休暇はまだもらえません。");
		// 	e.preventDefault();
		// 	return;
		// }

		if (totcnt == '0' && usecnt == '0' && usetime == '0') {
			alert("休暇はまだもらえません。");
			e.preventDefault();
			return;
		}

		$('#modal').modal('toggle');

		//신규때는 신청구분 선택하기 전에는 사용 불가
		$("#strymd").val("").prop('disabled', true);
		$("#endymd").val("").prop('disabled', true);

		//신규때는 신청구분 선택하기 전에는 사용 불가
		$("#strtime").val("").prop('disabled', true);
		$("#endtime").val("").prop('disabled', true);
	});

	//휴가신청 타입(일/시간) 선택시 항목 잠그고 풀기  
	$('input[type=radio][name=kyukatype]').change(function() {
		if (this.value == '1') {
			//일 선택
			$("#strymd").prop('disabled', false);
			$("#endymd").prop('disabled', false);
			$("#strtime").prop('disabled', true);
			$("#endtime").prop('disabled', true);
			$("#ymdcnt").val("NaN");
			$("#timecnt").val(0);
		} else if (this.value == '0') {
			//시간 선택
			$("#strymd").prop('disabled', false);
			$("#endymd").prop('disabled', true);
			$("#strtime").prop('disabled', false);
			$("#endtime").prop('disabled', false);
			$("#strtime").val(0);
			$("#endtime").val(0);
			$("#ymdcnt").val(0);
			$("#timecnt").val(0);
		}
	});

	//휴가중 연락처
	$('input[type=radio][name=destcode]').change(function() {
		if (this.value == '0') {
			//일본
			$("#destplace").val("日本").prop('readonly', true);
		} else if (this.value == '1') {
			//한국
			$("#destplace").val("韓国").prop('readonly', true);
		} else {
			//기타
			$("#destplace").val("").prop('readonly', false);
		}
	});

	//휴가일(str) 변경시 휴가일 계산
	$("#strymd").change(function() {
		var str = new Date($("#strymd").val());
		var end = new Date($("#endymd").val());

		if (isNull(end) || str > end) {
			$("#endymd").val($("#strymd").val());
			end = new Date($("#endymd").val());
		}

		//시간을 선택하면 일수는 0으로 한다. 
		if ($("input[name='kyukatype']:checked").val() == "0") {
			$("#ymdcnt").val("0");
			$("#endymd").val($("#strymd").val());
			return;
		}
		var dateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
		$("#ymdcnt").val(dateDiff + 1);
	});

	//휴가일(end) 변경시 휴가일 계산
	$("#endymd").change(function() {
		var str = new Date($("#strymd").val());
		var end = new Date($("#endymd").val());

		if (str > end) {
			$("#endymd").val($("#strymd").val());
			end = new Date($("#endymd").val());
		}

		var dateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
		$("#ymdcnt").val(dateDiff + 1);
	});

	//휴가시간 변경시 계산
	$("#strtime").on("change keyup paste", function() {
		if (($("#strtime").val() * 1 > $("#endtime").val() * 1) && ($("#endtime").val().length == 2)) {
			$("#endtime").val($("#strtime").val());
		}
		var timeDiff = $("#endtime").val() - $("#strtime").val()

		timeDiff = timeDiff > 4 ? timeDiff - 1 : timeDiff; //점심시간 제외
		timeDiff = timeDiff > 8 ? 8 : timeDiff; //한번에 신청 가능한 시간 최대 8시간

		$("#timecnt").val(timeDiff);
	});

	//휴가시간 변경시 계산
	$("#endtime").on("change keyup paste", function() {
		if (($("#strtime").val() * 1 > $("#endtime").val() * 1) && ($("#endtime").val().length == 2)) {
			$("#endtime").val($("#strtime").val());
		}
		var timeDiff = $("#endtime").val() - $("#strtime").val()

		timeDiff = timeDiff > 4 ? timeDiff - 1 : timeDiff; //점심시간 제외
		timeDiff = timeDiff > 8 ? 8 : timeDiff; //한번에 신청 가능한 시간 최대 8시간

		$("#timecnt").val(timeDiff);
	});

	//Datepeeker 설정 strtime
	$("#strymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	$("#endymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	$(document).on('click', '#btnAnnt', function(e) {
		$('#modal2').modal('toggle');
	});

	//저장버튼 처리
	$(document).on('click', '#btnReg', function(e) {
		var kyukatype = $("input[name='kyukatype']:checked").val();
		var strymd = $("#strymd").val();
		var endymd = $("#endymd").val();
		var ymdcnt = $("#ymdcnt").val();
		var strtime = $("#strtime").val() * 1;
		var endtime = $("#endtime").val() * 1;
		var timecnt = $("#timecnt").val() * 1;
		var kyukaname = $("#kyukaname option:selected").text();
		var allowok = $("input[name='allowok']:checked").val();
		var destcode = $("input[name='destcode']:checked").val();
		var destplace = $("#destplace").val();
		var desttel = $("#desttel").val();
		var vacationstr = $("#vacationstr").val();
		var vacationend = $("#vacationend").val();
		var oldcnt = $("#oldcnt").val() * 1;
		var newcnt = $("#newcnt").val() * 1;
		var usecnt = $("#usecnt").val() * 1;
		var usetime = $("#usetime").val() * 1;
		var restcnt = $("#restcnt").val() * 1;
		var kyukatimelimit = $("#kyukatimelimit").val() * 1;

		if (ymdcnt > (oldcnt + newcnt)) {
			alert("休暇の申込日は(" + (oldcnt + newcnt) + "日)を超えるわけにはいきません。");
			$("#ymdcnt").focus();
			return false; //함수 종료
		}

		if (kyukaname == "") {
			alert("休暇区分を入力してください。");
			$("#kyukaname").focus();
			return false; //함수 종료
		}

		// 년간 사용 가능한 휴가시간제한 체크 (당해년도사용시간+이번에신청한시간 > 년간사용제한시간 이면 에러)
		if (usetime + timecnt > kyukatimelimit) {
			alert("休暇の申込時間は(" + kyukatimelimit + "時間)を超えるわけにはいきません。");
			return false;
		}

		// 휴가신청기간은 휴가를 부여받은 기간 안에서만 가능해야 하기 때문에 더 큰 경우는 2개로 나눠서 신청하게한다. 
		if (endymd > vacationend) {
			alert("休暇の申込は(" + vacationstr + " ~ " + vacationend + "の内だけに可能です。");
			return false;
		}

		//残数(日)	 計算
		restcnt = oldcnt + newcnt - usecnt - parseInt(usetime / 8);
		if (restcnt < 0) {
			alert("残数(日)を超える休暇は申し込む事はできません。");
			$("#strymd").focus();
			return false; //함수 종료
		}

		if (kyukatype != "0" && kyukatype != "1") {
			alert("申込区分を入力してください。");
			$("#kyukatype").focus();
			return false; //함수 종료
		}

		if (strymd == "") {
			alert("期間(F)を入力してください。");
			$("#strymd").focus(); //입력 포커스 이동
			return false; //함수 종료
		}

		if (kyukatype == "1" && endymd == "") {
			alert("期間(T)を入力してください。");
			$("#endymd").focus();
			return false;
		}

		if (kyukatype == "0" && (strtime == "" || strtime == "0")) {
			alert("時間(F)を入力してください。");
			$("#strtime").focus();
			return false;
		}

		if (kyukatype == "0" && (endtime == "" || endtime == "0")) {
			alert("時間(T)を入力してください。");
			$("#endtime").focus();
			return false;
		}

		if (allowok != "0" && allowok != "1") {
			alert("決裁を入力してください。");
			$("#allowok").focus(); //입력 포커스 이동
			return false; //함수 종료
		}

		if (destcode != "0" && destcode != "1" && destcode != "2") {
			alert("暇中居る場所を入力してください。");
			$("#destcode").focus();
			return false; //함수 종료
		}

		if (destplace == "") {
			alert("場所を入力してください。");
			$("#destplace").focus(); //입력 포커스 이동
			return false; //함수 종료
		}

		if (desttel == "") {
			alert("電話番号を入力してください。");
			$("#desttel").focus(); //입력 포커스 이동
			return false; //함수 종료
		}
	});

	//삭제버튼 : Delete 
	$(document).on('click', '#btnClear', function(e) {
		$('#kyukaname').val('');
		$("input[name='kyukatype']").prop('checked', false);
		$('#strymd').val('');
		$('#endymd').val('');
		$('#strtime').val('');
		$('#endtime').val('');
		$('#usecnt').val('');
		$('#usetime').val('');
		$('#ymdcnt').val('');
		$('#timecnt').val('');
		$("input[name='allowok']").prop('checked', false);
		$("input[name='destcode']").prop('checked', false);
		$('#destplace').val('');
		$('#desttel').val('');
	});

	//그리드에서 사원ID클릭(수정) : popup & 내용표시 
	$(document).on('click', '.showModal', function() {
		$('#modal3').modal('toggle');
		var Uid = $(this).text();
		// alert(Uid);
	});
</script>
<?php include('../inc/footer.php'); ?>