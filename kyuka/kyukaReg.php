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

	.popup-title {
		font-size: 20px;
		font-weight: bold;
	}

	span.vacationid_class {
		display: none;
	}

	.col-md-6.col-sm-6.col-sx-6.sub-bar.text-right.all-div.last {
		float: right;
	}

	.title_sub{
		margin-top: 35px;
	}
</style>
<title>休暇届</title>
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
			<div class="col-md-2 col-sm-2 col-sx-2 text-left all-div">
				<div class="title_name">
					<span class="text-left">休暇届</span>
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-sx-4 sub-bar text-center">
				<div class="title_sub custom-control custom-radio" id="divAllowok">
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
				<div class="title_sub">
					<label>社員名 :
						<select id="searchUid" name="searchUid" style="padding:5px; width:70%;">
							<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
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
			<div class="col-md-3 col-sm-3 col-sx-3 sub-bar text-right all-div">
				<div class="title_sub">
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
		</div>
		<div class="row">
			<div class="col-md-6 col-sm-6 col-sx-6" style="margin-top: -30px;"></div>
			<div class="col-md-6 col-sm-6 col-sx-6 sub-bar text-right all-div last" style="margin-top: -30px;">
				<div class="title_btn">
					<input type="submit" name="btnSearchReg" value="検索 ">&nbsp;
					<input type="button" id="btnNew" value="新規 ">&nbsp;
					<input type="button" id="btnAnnt" value="お知らせ ">&nbsp;
					<input type="button" onclick="window.location.href='./vacationReg.php'" value="休暇情報 ">
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
							<td colspan="12" align="center"><?php echo $data_save_no; ?></td>
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
								<td class="td8"><span name="allowok">
										<?php
										if ($userkyuka['allowok'] == "0") { ?>
											<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
												<a href="#"><span style="color:red;text-decoration-line: underline;" class="showModal">未決裁<span class="vacationid_class"><?= ',' . $userkyuka['uid'] . ',' . $userkyuka['ymdcnt']  . ',' . $userkyuka['timecnt'] ?></span></span>
												<?php else : ?>
													<span style="color:red;">未決裁</span>
												<?php endif; ?>
											<?php } else { ?>
												<span>
													決裁完了
													<?php
													if ($userkyuka['allowdecide'] == "0") { ?>
														<span style="color:red;">NG</span>
													<?php } else { ?>
														OK
													<?php } ?>
												</span>
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
									<?php foreach ($result_uservacationmanage_select as $key) : ?>
										<input type="hidden" name="companyid" value="<?= $key['companyid'] ?>">
										<input type="hidden" name="uid" value="<?= $key['uid'] ?>">
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
								if (!empty($vacationinfo_list)) {
									foreach ($vacationinfo_list as $key) {
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
										}
									}
								}
								?>
								<div class="col-md-2 col-sm-2 col-sx-2 no"></div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="ymdcnt">申込日</label>
									<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2 no">
									<label for="timecnt">申込時間</label>
									<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="" style="text-align: center" readonly>
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
									<a class="btn btn-success btn-md" id="btnClear" role="button">クリア </a>
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-default btn-md" data-dismiss="modal" role="button">閉じる </a>
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
								<a class="btn btn-default btn-md" data-dismiss="modal" role="button">閉じる </a>
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
						<div class="modal-header">
							<span id="ustitle">決裁の決定</span>
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row one">
								<div class="col-md-2 col-sm-2 col-sx-2"></div>
								<div class="col-md-4 col-sm-4 col-sx-4 uid">
									<label for="uid">ID</label>
									<input type="text" class="form-control" id="duid" name="duid" style="text-align: left" readonly>
									<input type="hidden" id="allowok" name="allowok" value="1">
									<input type="hidden" id="newymdcnt" name="newymdcnt">
									<input type="hidden" id="newtimecnt" name="newtimecnt">
									<input type="hidden" id="oldusecnt" name="oldusecnt">
									<input type="hidden" id="oldusetime" name="oldusetime">
									<input type="hidden" id="oldrestcnt" name="oldrestcnt">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacationstr">
									<label for="destcode">許可の決定</label>
									<div class="custom-control custom-radio">
										<input type="radio" class="decide_allowok" name="decide_allowok" value="0" style="margin-right: 5px;">NG&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" class="decide_allowok" name="decide_allowok" value="1" style="margin-right: 5px;">OK
									</div>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2"></div>
							</div>
						</div>
						<div class="modal-footer" style="text-align: center">
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<input type="submit" name="DecideUpdateKyuka" class="btn btn-primary btn-md" id="btnRegDecideUpdate" role="button" value="登録">
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-default btn-md" data-dismiss="modal" role="button">閉じる </a>
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
	// New button
	$(document).on('click', '#btnNew', function(e) {
		var totcnt = $("#totcnt").val();
		var usecnt = $("#usecnt").val();
		var usetime = $("#usetime").val();
		var va_uid = $("#va_uid").val();

		if (totcnt == '0' && usecnt == '' && usetime == '') {
			alert("<?php echo $kyuka_no_receive; ?>");
			e.preventDefault();
			return;
		}

		$('#modal').modal('toggle');

		// In the case of a new application, it cannot be used until the application category is selected.
		$("#strymd").val("").prop('disabled', true);
		$("#endymd").val("").prop('disabled', true);

		// In the case of a new application, it cannot be used until the application category is selected.
		$("#strtime").val("").prop('disabled', true);
		$("#endtime").val("").prop('disabled', true);
	});

	// Lock and unlock items when selecting vacation request type (day/hour)
	$('input[type=radio][name=kyukatype]').change(function() {
		if (this.value == '1') {
			// Select day
			$("#strymd").prop('disabled', false);
			$("#endymd").prop('disabled', false);
			$("#strtime").prop('disabled', true);
			$("#endtime").prop('disabled', true);
			$("#ymdcnt").val("NaN");
			$("#timecnt").val(0);
		} else if (this.value == '0') {
			// Time selection
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

	// Contact while on vacation
	$('input[type=radio][name=destcode]').change(function() {
		if (this.value == '0') {
			// Japan
			$("#destplace").val("日本").prop('readonly', true);
		} else if (this.value == '1') {
			// Korea
			$("#destplace").val("韓国").prop('readonly', true);
		} else {
			// Other
			$("#destplace").val("").prop('readonly', false);
		}
	});

	// Calculation of vacation days when vacation days (str) change
	$("#strymd").change(function() {
		var str = new Date($("#strymd").val());
		var end = new Date($("#endymd").val());

		if (isNull(end) || str > end) {
			$("#endymd").val($("#strymd").val());
			end = new Date($("#endymd").val());
		}

		// If hours are selected, the number of days is set to 0.
		if ($("input[name='kyukatype']:checked").val() == "0") {
			$("#ymdcnt").val("0");
			$("#endymd").val($("#strymd").val());
			return;
		}
		var dateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
		$("#ymdcnt").val(dateDiff + 1);
	});

	// Calculation of vacation days when vacation days (end) change
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

	// Calculation of vacation time change
	$("#strtime").on("change keyup paste", function() {
		if (($("#strtime").val() * 1 > $("#endtime").val() * 1) && ($("#endtime").val().length == 2)) {
			$("#endtime").val($("#strtime").val());
		}
		var timeDiff = $("#endtime").val() - $("#strtime").val()

		timeDiff = timeDiff > 4 ? timeDiff - 1 : timeDiff; // Excluding lunch time
		timeDiff = timeDiff > 8 ? 8 : timeDiff; // Up to 8 hours of application time at a time
		$("#timecnt").val(timeDiff);
	});

	// Calculation of vacation time change
	$("#endtime").on("change keyup paste", function() {
		if (($("#strtime").val() * 1 > $("#endtime").val() * 1) && ($("#endtime").val().length == 2)) {
			$("#endtime").val($("#strtime").val());
		}
		var timeDiff = $("#endtime").val() - $("#strtime").val()

		timeDiff = timeDiff > 4 ? timeDiff - 1 : timeDiff; // Excluding lunch time
		timeDiff = timeDiff > 8 ? 8 : timeDiff; // Up to 8 hours of application time at a time

		$("#timecnt").val(timeDiff);
	});

	// Datepeeker Calender
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

	// Long button treatment
	$(document).on('click', '#btnReg', function(e) {
		var kyukatype = $("input[name='kyukatype']:checked").val();
		var strymd = $("#strymd").val();
		var endymd = $("#endymd").val();
		var ymdcnt = $("#ymdcnt").val();
		var strtime = $("#strtime").val() * 1;
		var endtime = $("#endtime").val() * 1;
		var timecnt = $("#timecnt").val() * 1;
		var kyukaname = $("#kyukaname option:selected").text();
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
			alert("<?php echo $kyuka_ymdcnt_01; ?>" + (oldcnt + newcnt) + "<?php echo $kyuka_ymdcnt_02; ?>");
			$("#ymdcnt").focus();
			return false;
		}

		if (kyukaname == "") {
			alert("<?php echo $kyuka_name_select; ?>");
			$("#kyukaname").focus();
			return false;
		}

		// Check the vacation time limit available for the year (use time in the current year + time requested this time > error if annual use limit time)
		if (usetime + timecnt > kyukatimelimit) {
			alert("<?php echo $kyuka_time_limit_01; ?>" + kyukatimelimit + "<?php echo $kyuka_time_limit_02; ?>");
			return false;
		}

		// Since the vacation request period must be available only within the period granted, in the case of a larger vacation, divide the application into two.
		if (endymd > vacationend) {
			alert("<?php echo $kyuka_endymd_01; ?>" + vacationstr + " ~ " + vacationend + "<?php echo $kyuka_endymd_02; ?>");
			return false;
		}

		// 残数(日)計算
		restcnt = oldcnt + newcnt - usecnt - parseInt(usetime / 8);
		if (restcnt < 0) {
			alert("<?php echo $kyuka_strymd; ?>");
			$("#strymd").focus();
			return false;
		}

		if (kyukatype != "0" && kyukatype != "1") {
			alert("<?php echo $kyuka_type_select; ?>");
			$("#kyukatype").focus();
			return false;
		}

		if (strymd == "") {
			alert("<?php echo $kyuka_strymd_empty; ?>");
			$("#strymd").focus();
			return false;
		}

		if (kyukatype == "1" && endymd == "") {
			alert("<?php echo $kyuka_endymd_empty; ?>");
			$("#endymd").focus();
			return false;
		}

		if (kyukatype == "0" && (strtime == "" || strtime == "0")) {
			alert("<?php echo $kyuka_strtime_empty; ?>");
			$("#strtime").focus();
			return false;
		}

		if (kyukatype == "0" && (endtime == "" || endtime == "0")) {
			alert("<?php echo $kyuka_endtime_empty; ?>");
			$("#endtime").focus();
			return false;
		}

		if (destcode != "0" && destcode != "1" && destcode != "2") {
			alert("<?php echo $kyuka_destcode_select; ?>");
			$("#destcode").focus();
			return false;
		}

		if (destplace == "") {
			alert("<?php echo $kyuka_destplace_empty; ?>");
			$("#destplace").focus();
			return false;
		}

		if (desttel == "") {
			alert("<?php echo $kyuka_desttel_empty; ?>");
			$("#desttel").focus();
			return false;
		}
	});

	// Clear Input Tag Data
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
		$("input[name='destcode']").prop('checked', false);
		$('#destplace').val('');
		$('#desttel').val('');
	});

	// Click (modify) employee ID in the grid: popup & display contents
	$(document).on('click', '.showModal', function() {
		$('#modal3').modal('toggle');
		var ArrayData = $(this).text();
		var SeparateArr = ArrayData.split(',');
		var Uid = SeparateArr[1];
		var Ymdcnt = SeparateArr[2];
		var Timecnt = SeparateArr[3];
		$("#duid").text($('[name="duid"]').val(Uid));
		$("#allowok").text($('[name="allowok"]').val());
		$("input[type='radio'].decide_allowok:checked").val();
		var newymdcnt = $("input[name=newymdcnt]:hidden");
		newymdcnt.val(Ymdcnt);
		var newymdcnt = newymdcnt.val();
		var newtimecnt = $("input[name=newtimecnt]:hidden");
		newtimecnt.val(Timecnt);
		var newtimecnt = newtimecnt.val();
		<?php
		if (!empty($vacationinfo_list)) {
			foreach ($vacationinfo_list as $key) {
		?>
				if ('<?php echo $key['uid'] ?>' == Uid) {
					var oldusecnt = $("input[name=oldusecnt]:hidden");
					oldusecnt.val("<?php echo $key['usecnt'] ?>");
					var oldusecnt = oldusecnt.val();
					var oldusetime = $("input[name=oldusetime]:hidden");
					oldusetime.val("<?php echo $key['usetime'] ?>");
					var oldusetime = oldusetime.val();
					var oldrestcnt = $("input[name=oldrestcnt]:hidden");
					oldrestcnt.val("<?php echo $key['restcnt'] ?>");
					var oldrestcnt = oldrestcnt.val();
				}
		<?php
			}
		}
		?>
	});



	window.onload = function() {
		setTimeout(hideLoadingOverlay, 1000);
		startLoading();
};


	// loading UX
	function showLoadingOverlay() {
		const overlay = document.getElementById("overlay");
		overlay.style.display = "block";
		document.body.style.pointerEvents = "none";
	}

	function hideLoadingOverlay() {
		const overlay = document.getElementById("overlay");
		overlay.style.display = "none";
		document.body.style.pointerEvents = "auto";
	}

	showLoadingOverlay();
	function startLoading() {
		NProgress.start();
		setTimeout(function () {
			NProgress.done();
		}, 1500);
	}



</script>
<?php include('../inc/footer.php'); ?>