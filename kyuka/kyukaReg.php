<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/model.php');
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
				<div class="col-md-1 text-left" style="width: 12.499999995%">
					<div class="title_name">
						<span class="text-left">休年届</span>
					</div>
				</div>
				<div class="col-md-3 text-center">
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

				<div class="col-md-3" id="divUid">
					<div class="title_condition">
						<label>社員名 :
							<select id="searchUid" name="searchUid" style="padding:5px;">
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
							</select>
						</label>
					</div>
				</div>

				<div class=" col-md-2 text-right">
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

				<div class="col-md-2 text-right" style="width: 20.8%">
					<div class="title_btn">
						<input type="submit" name="btnSearch" value="検索 ">&nbsp;
						<input type="button" id="btnNew" value="新規 ">&nbsp;
						<input type="button" id="btnAnnt" value="お知らせ ">
					</div>
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
						<?php if (empty($userkyuka_list)) { ?>
							<tr>
								<td colspan="8" align="center">登録されたデータがありません.</td>
							</tr>
							<?php } elseif (!empty($userkyuka_list)) {
							foreach ($userkyuka_list as $userkyuka) {
							?>
								<tr>
									<td><span name="ymd"><?= $userkyuka['kyukaymd'] ?></span></td>
									<td><span name="cname"><?= $userkyuka['name'] ?></span></td>
									<td>
										<span name="crequestdate"><?= $userkyuka['strymd'] ?>~<?= $userkyuka['endymd'] ?></span>
									</td>
									<td><span name="cymdcnt"><?= $userkyuka['ymdcnt'] ?>日(<?= $userkyuka['timecnt'] ?>時)</span></td>
									<td><span name="cvacationdate"><?= $userkyuka['vacationstr'] ?>~<?= $userkyuka['vacationend'] ?></span></td>
									<td><span name="ctotcnt"><?= $userkyuka['oldcnt'] + $userkyuka['newcnt'] ?></span></td>
									<td><span name="crestcnt"><?= date('d', strtotime($userkyuka['endymd']) - strtotime($userkyuka['strymd'])) - 1 ?></span></td>
									<td><span name="callowok">
											<?php
											if ($userkyuka['allowok'] == "0") { ?>
												<span name="callowok" style="color:red">未決裁</span>
											<?php } else { ?>
												<span name="callowok">決裁完了</span>
											<?php } ?>
										</span>
									</td>
									<td><span name="cdestplace"><?= $userkyuka['destplace'] ?></span></td>
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
								<div class="row">
									<div class="col-md-3">
										<label for="kyukaymd">申請日</label>
										<input type="text" class="form-control" id="kyukaymd" name="kyukaymd" placeholder="" required="required" style="text-align: center">
										<input type="hidden" id="seq" value="">
										<input type="hidden" id="kyukaid" value="">
										<input type="hidden" id="companyid" value="">
										<input type="hidden" id="uid" value="">
										<input type="hidden" id="name" value="">
										<input type="hidden" id="vacationstr" value="">
										<input type="hidden" id="vacationend" value="">
										<input type="hidden" id="vacationid" value="">
										<input type="hidden" id="kyukaid" value="">
										<input type="hidden" id="oldcnt" value="">
										<input type="hidden" id="newcnt" value="">
										<input type="hidden" id="kyukatimelimit" value="">
									</div>
									<div class="col-md-5">
										<label for="kyukacode">休暇区分</label>
										<select class="form-control" id="kyukacode" name="kyukacode">
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
									<div class="col-md-4">
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
								<div class="row">
									<div class="col-md-3">
										<label for="strymd">期間(F)</label>
										<input type="text" class="form-control" id="strymd" name="strymd" placeholder="" required="required" maxlength="10" style="text-align: center">
									</div>
									<div class="col-md-3">
										<label for="endymd">期間(T)</label>
										<input type="text" class="form-control" id="endymd" name="endymd" placeholder="" required="required" maxlength="10" style="text-align: center">
									</div>
									<div class="col-md-3">
										<label for="strtime">時間(F)</label>
										<input type="text" class="form-control" id="strtime" name="strtime" placeholder="" required="required" maxlength="2" style="text-align: center">
									</div>
									<div class="col-md-3">
										<label for="endtime">時間(T)</label>
										<input type="text" class="form-control" id="endtime" name="endtime" placeholder="" required="required" maxlength="2" style="text-align: center">
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-2">
										<label for="totcnt">当年付与</label>
										<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center">
									</div>
									<div class="col-md-2">
										<label for="usecnt">使用日数</label>
										<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center">
									</div>
									<div class="col-md-2">
										<label for="usetime">使用時間</label>
										<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center">
									</div>
									<div class="col-md-2">
										<label for="ymdcnt">申込日</label>
										<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="" style="text-align: center">
									</div>
									<div class="col-md-2">
										<label for="timecnt">申込時間</label>
										<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="" style="text-align: center">
									</div>
									<div class="col-md-2">
										<label for="allowok">決裁</label>
										<div class="custom-control custom-radio">
											<input type="radio" name="allowok" value="0">未決裁
											<input type="radio" name="allowok" value="1">決裁完了
										</div>
									</div>
								</div>
								<br>
								<div class="row">
									<div class="col-md-4">
										<label for="destcode">暇中居る連絡先</label>
										<div class="custom-control custom-radio">
											&nbsp;&nbsp;
											<input type="radio" name="destcode" value="0">日本
											<input type="radio" name="destcode" value="1">韓国
											<input type="radio" name="destcode" value="2">その他
										</div>
									</div>
									<div class="col-md-4">
										<label for="destplace">場所</label>
										<input type="text" class="form-control" name="destplace" id="destplace" placeholder="" required="required" style="text-align: left">
									</div>
									<div class="col-md-4">
										<label for="desttel">Tel</label>
										<input type="text" class="form-control" name="desttel" id="desttel" placeholder="" required="required" style="text-align: left">
									</div>
								</div>
							</div>
							<div class="modal-footer" style="text-align: center">
								<div class="col-md-2"></div>
								<div class="col-md-2">
									<p class="text-center">
										<a class="btn btn-warning btn-md" id="btnAllow" href="http://localhost:8080/web/kyuka/kyukaReg#" role="button">決裁 </a>
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-center">
										<input type="submit" name="SaveKyuka" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-center">
										<a class="btn btn-primary btn-md" id="btnDel" href="http://localhost:8080/web/kyuka/kyukaReg#" role="button">削除 </a>
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-center">
										<a class="btn btn-primary btn-md" id="btnRet" href="../kyuka/kyukaReg.php" role="button">閉じる </a>
									</p>
								</div>
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
						<div class="modal-header"><span style="font-size:20px;font-weight: bold;">お知らせ(注意)</span>
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row">
								<div class="col-md-12">
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
								<div class="col-md-12">
									<div class="alert alert-info" style="margin-bottom: 10px;">
										<strong>※&nbsp;年次有給休暇</strong>
									</div>
								</div>
								<div class="col-md-12">
									<table class="table table-bordered datatable">
										<thead>
											<tr>
												<th class="info" style="text-align: center; color: #31708f;">勤続年数</th>
												<td style="text-align: center;">6ヵ月以内</td>
												<td style="text-align: center;">6ヵ月</td>
												<td style="text-align: center;">1年6ヵ月</td>
												<td style="text-align: center;">2年6ヵ月</td>
												<td style="text-align: center;">3年6ヵ月</td>
												<td style="text-align: center;">4年6ヵ月</td>
												<td style="text-align: center;">5年6ヵ月</td>
												<td style="text-align: center;">5年6ヵ月以上</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th class="info" style="text-align: center; color: #31708f;">付与日数</th>
												<td style="text-align: center;">無し</td>
												<td style="text-align: center;">10日</td>
												<td style="text-align: center;">11日</td>
												<td style="text-align: center;">12日</td>
												<td style="text-align: center;">14日</td>
												<td style="text-align: center;">16日</td>
												<td style="text-align: center;">18日</td>
												<td style="text-align: center;">20日</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="modal-footer" style="padding-bottom: 5px;">
								<div class="col-md text-center">
									<a class="btn btn-primary btn-md" id="btnRet" href="../kyuka/kyukaReg.php" role="button">閉じる </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<script>
				//신규버튼 
				$(document).on('click', '#btnNew', function(e) {
					$('#modal').modal('toggle');

					$("#kyukaymd").val("").prop('disabled', true);

					//신규때는 신청구분 선택하기 전에는 사용 불가
					$("#strymd").val("").prop('disabled', true);
					$("#endymd").val("").prop('disabled', true);
					//휴가신청 타입(일/시간) 
					$('input[name="kyukatype"]').prop('checked', false);
					//휴가중 연락처
					$('input[name="destcode"]').prop('checked', false);

					$("#vacationid").val("");
					$("#vacationstr").val("");
					$("#vacationend").val("");

					$("#totcnt").val("0").prop('disabled', true);
					$("#usecnt").val("").prop('disabled', true);
					//신규때는 신청구분 선택하기 전에는 사용 불가
					$("#strtime").val("").prop('disabled', true);
					$("#endtime").val("").prop('disabled', true);

					$("#usetime").val("").prop('disabled', true);
					$("#restcnt").val("").prop('disabled', true);
					$("#kyukatimelimit").val("");

					$("#ymdcnt").val("").prop('disabled', true);
					$("#timecnt").val("").prop('disabled', true);

					$("#kyukaname").focus();
				});

				//휴가신청 타입(일/시간) 선택시 항목 잠그고 풀기  
				$('input[type=radio][name=kyukatype]').change(function() {
					if (this.value == '1') {
						//일 선택
						$("#strymd").prop('disabled', false);
						$("#endymd").prop('disabled', false);
						$("#strtime").prop('disabled', true);
						$("#endtime").prop('disabled', true);
						$("#strtime").val(0);
						$("#endtime").val(0);
						$("#timecnt").val(0);
						$("#ymdcnt").val("NaN");
					} else if (this.value == '0') {
						//시간 선택
						$("#strymd").prop('disabled', false);
						$("#endymd").prop('disabled', true);
						$("#strtime").prop('disabled', false);
						$("#endtime").prop('disabled', false);

						//일자<=>시간 변경시 일수계산을 0으로 한다. 
						$("#ymdcnt").val(0);
						$("#strtime").val(0);
						$("#endtime").val(0);
						$("#timecnt").val(0);
					}
				});

				//휴가중 연락처
				$('input[type=radio][name=destcode]').change(function() {
					if (this.value == '0') {
						//일본
						$("#destplace").val("日本").prop('disabled', true);
					} else if (this.value == '1') {
						//한국
						$("#destplace").val("韓国").prop('disabled', true);
					} else {
						//기타
						$("#destplace").val("").prop('disabled', false);
					}
				});

				//Datepeeker 설정 strtime
				$("#strymd").datepicker({
					dateFormat: 'yy/mm/dd'
				});
				$("#endymd").datepicker({
					dateFormat: 'yy/mm/dd'
				});

				$(document).on('click', '#btnAnnt', function(e) {
					$('#modal2').modal('toggle');
				});
			</script>
</body>

</html>