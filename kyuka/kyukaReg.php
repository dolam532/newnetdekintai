<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');

// Select data from tbl_user
$sql_user = 'SELECT `uid`, `name` FROM `tbl_user`';
$result_user = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result_user, MYSQLI_ASSOC);

// Select data from tbl_userkyuka
$sql_userkyuka = 'SELECT * FROM `tbl_userkyuka`
JOIN `tbl_codebase` ON `tbl_codebase`.`code` = `tbl_userkyuka`.`kyukacode`
WHERE
`tbl_codebase`.`code` = `tbl_userkyuka`.`kyukacode`
AND 
`tbl_codebase`.`typecode` = 02';
$result_userkyuka = mysqli_query($conn, $sql_userkyuka);
$userkyuka_list = mysqli_fetch_all($result_userkyuka, MYSQLI_ASSOC);

// Select data from tbl_codebase
$sql_codebase = 'SELECT `code`, `name` FROM `tbl_codebase`
WHERE `tbl_codebase`.`typecode` = 02 GROUP BY `code`, `name`';
$result_codebase = mysqli_query($conn, $sql_codebase);
$codebase_list = mysqli_fetch_all($result_codebase, MYSQLI_ASSOC);

// Save data to tbl_userkyuka table of database
if (isset($_POST['save'])) {
	if ($_POST['kyukaid'] = "") {
		$_POST['kyukaid'] = "0";
	}

	// 申込区分 時間時
	if ($_POST['kyukatype'] == "0") {
		$_POST['totcnt'] = "0";
		$_POST['ymdcnt'] = "0";
		$_POST['timecnt'] = "0";
		$_POST['uid'] = "0";
		$_POST['vacationid'] = "0";
		$_POST['kyukaymd'] = "0";
	}

	$_POST['kyukaid'] = intval($_POST['kyukaid']);
	$reg_dt = date('Y-m-d H:i:s');

	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$vacationid = mysqli_real_escape_string($conn, $_POST['vacationid']);
	$kyukaymd = mysqli_real_escape_string($conn, $_POST['kyukaymd']);
	$kyukaid = mysqli_real_escape_string($conn, $_POST['kyukaid']);
	$kyukacode = mysqli_real_escape_string($conn, $_POST['kyukacode']);
	$kyukatype = mysqli_real_escape_string($conn, $_POST['kyukatype']);
	$strymd = mysqli_real_escape_string($conn, $_POST['strymd']);
	$endymd = mysqli_real_escape_string($conn, $_POST['endymd']);
	$strtime = mysqli_real_escape_string($conn, $_POST['strtime']);
	$endtime = mysqli_real_escape_string($conn, $_POST['endtime']);
	$ymdcnt = mysqli_real_escape_string($conn, $_POST['ymdcnt']);
	$inymd = mysqli_real_escape_string($conn, $_POST['inymd']);
	$timecnt = mysqli_real_escape_string($conn, $_POST['timecnt']);
	$allowok = mysqli_real_escape_string($conn, $_POST['allowok']);
	$destcode = mysqli_real_escape_string($conn, $_POST['destcode']);
	$destplace = mysqli_real_escape_string($conn, $_POST['destplace']);
	$desttel = mysqli_real_escape_string($conn, $_POST['desttel']);


	$sql_userkyuka_i = "INSERT INTO `tbl_userkyuka` (`uid`, `vacationid`, `kyukaymd`, `kyukaid`, `kyukacode`, `kyukatype`, `strymd`, `endymd`, `strtime`, `endtime`, `ymdcnt`, `timecnt`, `allowok`, `destcode`, `destplace`, `desttel`, `reg_dt`) 
	VALUES('$uid', '$vacationid', '$kyukaymd', '$kyukaid', '$kyukacode' ,'$kyukatype' ,'$strymd', '$endymd', '$strtime', '$endtime', '$ymdcnt', '$timecnt', '$allowok', '$destcode', '$destplace', '$desttel', '$reg_dt')";
	if (mysqli_query($conn, $sql_userkyuka_i)) {
		$_SESSION['save_success'] =  $save_success;
	} else {
		echo 'query error: ' . mysqli_error($conn);
	}
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
		<div class="row">
			<div class="col-md-2 text-left">
				<div class="title_name">
					<span class="text-left">休年届</span>
				</div>
			</div>
			<form method="post">
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
								<?php
								foreach ($user_list as $value) {
								?>
									<option value="<?= $value['uid'] ?>"><?= $value['name'] ?></option>
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
								<option value="" selected="selected"></option>
								<?php
								foreach (ConstArray::$search_year as $key => $value) {
								?>
									<option value="<?= $key ?>" <?php if ($value == date("Y")) {
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

				<div class="col-md-2 text-right">
					<div class="title_btn">
						<input type="button" id="btnSearch" value="検索 ">&nbsp;&nbsp;&nbsp;
						<input type="button" id="btnNew" value="新規 ">
					</div>
				</div>
			</form>
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
								<td><span name="cymdcnt"><?= $userkyuka['ymdcnt'] ?>(<?= $userkyuka['timecnt'] ?>)</span></td>
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
										<input type="submit" name="save" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-center">
										<a class="btn btn-primary btn-md" id="btnDel" href="http://localhost:8080/web/kyuka/kyukaReg#" role="button">削除 </a>
									</p>
								</div>
								<div class="col-md-2">
									<p class="text-center">
										<a class="btn btn-primary btn-md" id="btnRet" href="http://localhost:8080/web/kyuka/kyukaReg#" role="button">閉じる </a>
									</p>
								</div>
								<div class="col-md-2"></div>
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
	</script>
</body>

</html>