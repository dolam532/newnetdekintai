<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kyukamodel.php');
include('../inc/header.php');
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

			<div class="col-md-2 text-right">
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
								<td><span><?= $userkyuka['kyukaymd'] ?></span></td>
								<td><span><?= $userkyuka['name'] ?></span></td>
								<td>
									<span><?= $userkyuka['strymd'] ?>~<?= $userkyuka['endymd'] ?></span>
								</td>
								<td><span><?= $userkyuka['ymdcnt'] ?>日(<?= $userkyuka['timecnt'] ?>時)</span></td>
								<td><span><?= $userkyuka['vacationstr'] ?>~<?= $userkyuka['vacationend'] ?></span></td>
								<td><span><?= $userkyuka['oldcnt'] + $userkyuka['newcnt'] ?></span></td>
								<td><span><?= date('d', strtotime($userkyuka['endymd']) - strtotime($userkyuka['strymd'])) - 1 ?></span></td>
								<td><span name="callowok">
										<?php
										if ($userkyuka['allowok'] == "0") { ?>
											<span style="color:red">未決裁</span>
										<?php } else { ?>
											<span>決裁完了</span>
										<?php } ?>
									</span>
								</td>
								<td><span><?= $userkyuka['destplace'] ?></span></td>
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
									<input type="text" class="form-control" name="kyukaymd" style="text-align: center" value="<?= date('Y-m-d'); ?>" readonly>
									<input type="hidden" id="kyukaid" name="kyukaid" value="">
									<input type="hidden" id="companyid" name="companyid" value="">
									<input type="hidden" id="uid" name="uid" value="">
									<input type="hidden" id="vacationstr" name="vacationstr" value="">
									<input type="hidden" id="vacationend" name="vacationend" value="">
									<input type="hidden" id="vacationid" name="vacationid" value="">
									<input type="hidden" id="oldcnt" name="oldcnt" value="">
									<input type="hidden" id="newcnt" name="newcnt" value="">
									<input type="hidden" id="kyukatimelimit" name="kyukatimelimit" value="">
								</div>
								<div class="col-md-5">
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
									<input type="text" class="form-control" id="totcnt" name="totcnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2">
									<label for="usecnt">使用日数</label>
									<input type="text" class="form-control" id="usecnt" name="usecnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2">
									<label for="usetime">使用時間</label>
									<input type="text" class="form-control" id="usetime" name="usetime" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2">
									<label for="ymdcnt">申込日</label>
									<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="" style="text-align: center" readonly>
								</div>
								<div class="col-md-2">
									<label for="timecnt">申込時間</label>
									<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="" style="text-align: center" readonly>
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
							<div class="col-md-3"></div>
							<div class="col-md-2">
								<p class="text-center">
									<input type="submit" name="SaveKyuka" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
								</p>
							</div>
							<div class="col-md-2">
								<p class="text-center">
									<a class="btn btn-primary btn-md" id="btnClear" role="button">クリア </a>
								</p>
							</div>
							<div class="col-md-2">
								<p class="text-center">
									<a class="btn btn-primary btn-md" id="btnRet" href="../kyuka/kyukaReg.php" role="button">閉じる </a>
								</p>
							</div>
							<div class="col-md-3"></div>
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
	</div>
</div>
<script>
	//신규버튼 
	$(document).on('click', '#btnNew', function(e) {
		$('#modal').modal('toggle');

		//신규때는 신청구분 선택하기 전에는 사용 불가
		$("#strymd").val("").prop('disabled', true);
		$("#endymd").val("").prop('disabled', true);

		//신규때는 신청구분 선택하기 전에는 사용 불가
		$("#strtime").val("").prop('disabled', true);
		$("#endtime").val("").prop('disabled', true);
		$("#totcnt").val(0);
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
		dateFormat: 'yy/mm/dd'
	});
	$("#endymd").datepicker({
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

		if (kyukaname == "") {
			alert("休暇区分を入力してください。");
			$("#kyukaname").focus();
			e.preventDefault();
			return; //함수 종료
		}

		// // 년간 사용 가능한 휴가시간제한 체크 (당해년도사용시간+이번에신청한시간 > 년간사용제한시간 이면 에러)
		// if (usetime + timecnt > kyukatimelimit) {
		// 	alert("休暇の申込時間は(" + kyukatimelimit + ")を超えるわけにはいきません。");
		// 	e.preventDefault();
		// 	return;
		// }

		// // 휴가신청기간은 휴가를 부여받은 기간 안에서만 가능해야 하기 때문에 더 큰 경우는 2개로 나눠서 신청하게한다. 
		// if (endymd > vacationend) {
		// 	alert("休暇の申込は(" + vacationstr + " ~ " + vacationend + "の内だけに可能です。");
		// 	e.preventDefault();
		// 	return;
		// }

		//残数(日)	 計算
		restcnt = oldcnt + newcnt - usecnt - parseInt(usetime / 8);
		if (restcnt < 0) {
			alert("残数(日)を超える休暇は申し込む事はできません。。");
			$("#strymd").focus();
			e.preventDefault();
			return; //함수 종료
		}

		if (kyukatype != "0" && kyukatype != "1") {
			alert("申込区分を入力してください。");
			$("#kyukatype").focus();
			e.preventDefault();
			return; //함수 종료
		}

		if (strymd == "") {
			alert("期間(F)を入力してください。");
			$("#strymd").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
		}

		if (kyukatype == "1" && endymd == "") {
			alert("期間(T)を入力してください。");
			$("#endymd").focus();
			e.preventDefault();
			return;
		}

		if (kyukatype == "0" && (strtime == "" || strtime == "0")) {
			alert("時間(F)を入力してください。");
			$("#strtime").focus();
			e.preventDefault();
			return;
		}

		if (kyukatype == "0" && (endtime == "" || endtime == "0")) {
			alert("時間(T)を入力してください。");
			$("#endtime").focus();
			e.preventDefault();
			return;
		}

		if (allowok != "0" && allowok != "1") {
			alert("決裁を入力してください。");
			$("#allowok").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
		}

		if (destcode != "0" && destcode != "1" && destcode != "2") {
			alert("暇中居る場所を入力してください。");
			$("#destcode").focus();
			e.preventDefault();
			return; //함수 종료
		}

		if (destplace == "") {
			alert("場所を入力してください。");
			$("#destplace").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
		}

		if (desttel == "") {
			alert("電話番号を入力してください。");
			$("#desttel").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
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
</script>
<?php include('../inc/footer.php'); ?>