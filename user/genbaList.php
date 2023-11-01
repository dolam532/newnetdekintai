<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/usermodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css'>";
?>

<!-- ****CSS*****  -->
<style>
	.datatable tr th {
		background-color: #D9EDF7;
		text-align: center;
	}

	.datatable tr td {
		text-align: center;
	}

	.btn {
		width: 80px;
		height: 32px;
	}

	.hidden-table {
		display: none;
	}

	.colorRed {
		color: red;
	}

	.colorGreen {
		color: green;
	}

	.V_hidden_text {
		visibility: hidden;
	}


	.colorSuccess {
		color: forestgreen;
	}

	.colorError {
		color: red
	}

	.admin-action-hidden {
		display: none;
	}
</style>
<title>勤務時間タイプ設定</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
	<?php
	if (isset($_SESSION['save_success']) && isset($_POST['SaveKinmu'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['save_success']; ?>
		</div>
		<?php
		unset($_SESSION['save_success']);
	}
	?>
	<?php
	if (isset($_SESSION['update_success']) && isset($_POST['UpdateKinmu'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['update_success']; ?>
		</div>
		<?php
		unset($_SESSION['update_success']);
	}
	?>
	<?php
	if (isset($_SESSION['delete_success']) && isset($_POST['DeleteKinmu'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['delete_success']; ?>
		</div>
		<?php
		unset($_SESSION['delete_success']);
	}
	?>
	<div class="row">
		<div class="col-md-4">
			<div class="title_name">
				<span class="text-left">勤務時間タイプ設定</span>
			</div>
		</div>
		<div class="col-md-4"></div>
		<div class="col-md-4 text-right">
			<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') ||$_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
				<div class="title_btn">
					<input type="button" id="btnNew" value="新規">
				</div>
			<?php endif; ?>
			<div class="title_btn">
				<input type="button" onclick="window.location.href='../'" value="トップへ戻る">
			</div>
		</div>
	</div>

	<div class="form-group">
		<table class="table table-bordered datatable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 3%;">ID</th>
					<th style="text-align: center; width: 13%;">勤務時間タイプ</th>
					<th style="text-align: center; width: 13%;">勤務会社名</th>
					<th style="text-align: center; width: 13%;">勤務作業期間</th>
					<th style="text-align: center; width: 10%;">勤務開始時間</th>
					<th style="text-align: center; width: 10%;">勤務終了時間</th>
					<th style="text-align: center; width: 5%;">昼休</th>
					<th style="text-align: center; width: 5%;">夜休</th>
					<th style="text-align: center; width: 7%;">使用</th>
					<th style="text-align: center; width: auto;">備考</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($genbadatas_list)) { ?>
					<tr>
						<td colspan="10" align="center">
							<?php echo $data_save_no; ?>
						</td>
					</tr>
				<?php } elseif (!empty($genbadatas_list)) {
					foreach ($genbadatas_list as $genba) {
						?>
						<tr>
							<td class="td1"><span>
									<?= $genba['genid'] ?>
								</span></td>
							<td class="td2">

								<a href="#"><span class="showModal" id="showModalChange"
										style="text-decoration-line: underline;" data-genid="<?= $genba['genid'] ?>">
										<?= $genba['genbaname'] ?>
									</span>
							</td>
							<td class="td3"><span>
									<?= $genba['genbacompany'] ?>
								</span></td>
							<td class="td4"><span>
									<?= $genba['strymd'] ?>~
									<?= $genba['endymd'] ?>
								</span></td>
							<td class="td5"><span>
									<?= $genba['workstrtime'] ?>
								</span></td>
							<td class="td6"><span>
									<?= $genba['workendtime'] ?>
								</span></td>
							<td class="td7"><span>
									<?= $genba['offtime1'] ?>
								</span></td>
							<td class="td8"><span>
									<?= $genba['offtime2'] ?>
								</span></td>
							<td class="td9">
								<span>
									<?php if ($genba['use_yn'] == "1") {
										echo "<p style='font-weight:bold;color:green;'>使用</p>";
									} else {
										echo "<p style='font-weight:bold;color:red;'>中止</p>";
									}
									?>
								</span>
							</td>
							<td class="td10"><span>
									<?= $genba['bigo'] ?>
								</span></td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
</div>

<!-- 新規 Modal -->
<div class="row">
	<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						現場登録(<span id="sname">管理者のみ</span>)
						<button class="close" data-dismiss="modal">x</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-9">
								<label for="genbacompany">勤務時間タイプ</label>
								<input type="text" class="form-control" id="genbaname" name="genbaname"
									placeholder="勤務時間タイプ">
							</div>
							<div class="col-md-3">
								<label for="use_yn"><strong>使用</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" id="use_yn" name="use_yn" value="1">使用
									<input type="radio" id="use_yn" name="use_yn" value="0">中止
								</div>
								<br />
								<!-- 2023/10/20---- add start  -->
								<label for="use_type"><strong>タイプ</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" id="use_type" name="use_type"
										value="<?php echo array_keys(ConstArray::$search_template)[0]; ?>">
									<?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[0]]; ?>
									<br />
									<input type="radio" id="use_type" name="use_type"
										value="<?php echo array_keys(ConstArray::$search_template)[1]; ?>">
									<?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[1]]; ?>
								</div>
								<!-- 2023/10/20---- add end  -->
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="genbacompany">勤務会社名</label>
								<input type="text" class="form-control" id="genbacompany" name="genbacompany"
									placeholder="勤務会社名" style="text-align: left">
							</div>
							<div class="col-md-6">
								<label for="work_period">業務作業期間</label>
								<div style="display: flex;">
									<input type="text" class="form-control" id="strymd" name="strymd" placeholder="日付">~
									<input type="text" class="form-control" id="endymd" name="endymd" placeholder="日付">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="workstrtime">業務開始時間</label>
								<input type="text" class="form-control" id="workstrtime" name="workstrtime"
									placeholder="09:00" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="workendtime">業務終了時間</label>
								<input type="text" class="form-control" id="workendtime" name="workendtime"
									placeholder="18:00" style="text-align: center">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3">
								<label for="offtime1">昼休(時:分)</label>
								<input type="text" class="form-control" id="offtime1" name="offtime1"
									placeholder="01:00" style="text-align: center">
							</div>
							<div class="col-md-3">
								<label for="offtime2">夜休(時:分)</label>
								<input type="text" class="form-control" id="offtime2" name="offtime2"
									placeholder="00:00" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" id="bigo" name="bigo" placeholder="備考"
									style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="SaveKinmu" class="btn btn-primary" id="btnReg_GL" role="button"
								value="登録">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-default" data-dismiss="modal"
								id="modalClose">閉じる</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- 編集 Modal -->
<div class="row">
	<div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">現場編集(<span id="sname">管理者のみ</span>)
						<button class="close" data-dismiss="modal">x</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-9">
								<label for="udgenbaname">勤務時間タイプ</label>
								<input type="text" class="form-control" name="udgenbaname" id="udgenbaname"
									placeholder="勤務時間タイプ">
								<input type="hidden" id="udgenid" name="udgenid">
								<input type="hidden" id="udcompanyid" name="udcompanyid">
							</div>
							<div class="col-md-3">
								<label for="uduse_yn"><strong>使用</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" name="uduse_yn" id="uduse_yn1" value="1">使用
									<input type="radio" name="uduse_yn" id="uduse_yn2" value="0">中止
								</div>
								<!-- 2023/10/20---- add start  -->
								<label for="uduse_type"><strong>タイプ</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" id="uduse_type1" name="uduse_type"
										value="<?php echo array_keys(ConstArray::$search_template)[0]; ?>">
									<?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[0]]; ?>
									<br />
									<input type="radio" id="uduse_type2" name="uduse_type"
										value="<?php echo array_keys(ConstArray::$search_template)[1]; ?>">
									<?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[1]]; ?>
								</div>
								<!-- 2023/10/20---- add end  -->
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="udgenbacompany">勤務会社名</label>
								<input type="text" class="form-control" id="udgenbacompany" name="udgenbacompany"
									placeholder="勤務会社名" style="text-align: left">
							</div>
							<div class="col-md-6">
								<label for="udwork_period">業務作業期間</label>
								<div style="display: flex;">
									<input type="text" class="form-control" id="udstrymd" name="udstrymd"
										placeholder="日付">~
									<input type="text" class="form-control" id="udendymd" name="udendymd"
										placeholder="日付">
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="udworkstrtime">業務開始時間</label>
								<input type="text" class="form-control" name="udworkstrtime" id="udworkstrtime"
									placeholder="09:00" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="udworkendtime">業務終了時間</label>
								<input type="text" class="form-control" name="udworkendtime" id="udworkendtime"
									placeholder="18:00" style="text-align: center">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3">
								<label for="udofftime1">昼休(時:分)</label>
								<input type="text" class="form-control" name="udofftime1" id="udofftime1"
									placeholder="01:00" style="text-align: center">
							</div>
							<div class="col-md-3">
								<label for="udofftime2">夜休(時:分)</label>
								<input type="text" class="form-control" name="udofftime2" id="udofftime2"
									placeholder="00:00" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="bigo_cmodal">備考</label>
								<input type="text" class="form-control" name="bigo_cmodal" id="bigo_cmodal"
									placeholder="備考" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-3"></div>
						<div class="col-md-2">
							<input type="submit" name="UpdateKinmu" class="btn btn-primary admin-action" id="btnUpd_GL"
								role="button" value="編集">
						</div>
						<div class="col-md-2">
							<input type="submit" name="DeleteKinmu" class="btn btn-warning admin-action" id="btnDel_GL"
								role="button" value="削除">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-default admin-action" data-dismiss="modal"
								id="btnCls_GL" id="modalClose">閉じる</button>
						</div>
						<div class="col-md-3"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	// New button
	$(document).on('click', '#btnNew', function (e) {
		$("use_yn").prop('checked', true);
		$('#modal').modal('toggle');
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

	$("#udstrymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	$("#udendymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	// Click (modify) employee ID in the grid: popup & display contents
	$(document).on('click', '.showModal', function () {
		// check when user admin 
		
		$(".admin-action").removeClass("admin-action-hidden");
		var genid = $(this).attr('data-genid');
		<?php if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')): ?>
			$(".admin-action").addClass("admin-action-hidden");
			return ;


		<?php else: ?>
			if (genid === '0' && "<?php echo $_SESSION['auth_type']; ?>" !== "<?php echo constant('MAIN_ADMIN'); ?>") {
				$(".admin-action").addClass("admin-action-hidden");
			}
		<?php endif; ?>
		$('#modal2').modal('toggle');

		<?php
		if (!empty($genbadatas_list)) {

			foreach ($genbadatas_list as $key) {
				?>
				if ('<?php echo $key['genid'] ?>' == genid) {
					var udgenid = $("input[name=udgenid]:hidden");
					udgenid.val("<?php echo $key['genid'] ?>");
					var udgenid = udgenid.val();
					var udcompanyid = $("input[name=udcompanyid]:hidden");
					udcompanyid.val("<?php echo $key['companyid'] ?>");
					var udcompanyid = udcompanyid.val();
					$("#udgenbaname").text($('[name="udgenbaname"]').val("<?php echo $key['genbaname'] ?>"));
					$("#udgenbacompany").text($('[name="udgenbacompany"]').val("<?php echo $key['genbacompany'] ?>"));
					$("input[name='uduse_yn'][value='<?php echo $key['use_yn']; ?>']").prop('checked', true);
					$("input[name='uduse_type'][value='<?php echo $key['template']; ?>']").prop('checked', true);
					$("#udstrymd").text($('[name="udstrymd"]').val("<?php echo $key['strymd'] ?>"));
					$("#udendymd").text($('[name="udendymd"]').val("<?php echo $key['endymd'] ?>"));
					$("#udworkstrtime").text($('[name="udworkstrtime"]').val("<?php echo $key['workstrtime'] ?>"));
					$("#udworkendtime").text($('[name="udworkendtime"]').val("<?php echo $key['workendtime'] ?>"));
					$("#udofftime1").text($('[name="udofftime1"]').val("<?php echo $key['offtime1'] ?>"));
					$("#udofftime2").text($('[name="udofftime2"]').val("<?php echo $key['offtime2'] ?>"));
					$("#bigo_cmodal").text($('[name="bigo_cmodal"]').val("<?php echo $key['bigo'] ?>"));
				}
				<?php
			}
		}
		?>
	});

	// Check Error 新規
	$(document).on('click', '#btnReg_GL', function (e) {
		// check not admin 
		<?php if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')): ?>
			return;
		<?php endif; ?>
		
		var genbaname = $("#genbaname").val();
		var genbacompany = $("#genbacompany").val();
		var strymd = $("#strymd").val();
		var endymd = $("#endymd").val();
		var workstrtime = $("#workstrtime").val();
		var workendtime = $("#workendtime").val();
		var offtime1 = $("#offtime1").val();
		var offtime2 = $("#offtime2").val();

		if (genbaname == "") {
			alert("<?php echo $user_genbaname_empty; ?>");
			$("#genbaname").focus();
			return false;
		}

		if (genbacompany == "") {
			alert("<?php echo $user_genbacompany_empty; ?>");
			$("#genbaname").focus();
			return false;
		}

		if (strymd == "") {
			alert("<?php echo $user_strymd_empty; ?>");
			$("#strymd").focus();
			return false;
		}

		if (endymd == "") {
			alert("<?php echo $user_endymd_empty; ?>");
			$("#endymd").focus();
			return false;
		}

		if (workstrtime == "") {
			alert("<?php echo $user_workstr_empty; ?>");
			$("#workstrtime").focus();
			return false;
		}

		if (workendtime == "") {
			alert("<?php echo $user_workend_empty; ?>");
			$("#workendtime").focus();
			return false;
		}

		if (offtime1 == "") {
			alert("<?php echo $user_offtime1_empty; ?>");
			$("#offtime1").focus();
			return false;
		}

		if (offtime2 == "") {
			alert("<?php echo $user_offtime2_empty; ?>");
			$("#offtime2").focus();
			return false;
		}

		function validateTimeFormat(time) {
			var regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
			return regex.test(time);
		}

		var isValid_workstr = validateTimeFormat(workstrtime);
		if (!isValid_workstr) {
			alert("<?php echo $user_workstr_incorrect; ?>");
			$("#workstrtime").focus();
			return false;
		}

		var isValid_workend = validateTimeFormat(workendtime);
		if (!isValid_workend) {
			alert("<?php echo $user_workend_incorrect; ?>");
			$("#workendtime").focus();
			return false;
		}

		var isValid_offtime1 = validateTimeFormat(offtime1);
		if (!isValid_offtime1) {
			alert("<?php echo $user_offtime1_incorrect; ?>");
			$("#offtime1").focus();
			return false;
		}

		var isValid_offtime2 = validateTimeFormat(offtime2);
		if (!isValid_offtime2) {
			alert("<?php echo $user_offtime2_incorrect; ?>");
			$("#offtime2").focus();
			return false;
		}
	});

	// Check Error 編集
	$(document).on('click', '#btnUpd_GL', function (e) {
		// check not admin 
		<?php if ($_SESSION['auth_type'] !== constant('ADMIN') && $_SESSION['auth_type'] !== constant('ADMINISTRATOR') && $_SESSION['auth_type'] !== constant('MAIN_ADMIN')): ?>
			return;
		<?php endif; ?>
		// check 0 id  
		var genid = $('#showModalChange').attr('data-genid');
		if (genid === '0' && "<?php echo $_SESSION['auth_type']; ?>" !== "<?php echo constant('MAIN_ADMIN'); ?>") {
			return;
		}


		var udgenbaname = $("#udgenbaname").val();
		var udgenbacompany = $("#udgenbacompany").val();
		var udstrymd = $("#udstrymd").val();
		var udendymd = $("#udendymd").val();
		var udworkstrtime = $("#udworkstrtime").val();
		var udworkendtime = $("#udworkendtime").val();
		var udofftime1 = $("#udofftime1").val();
		var udofftime2 = $("#udofftime2").val();

		if (udgenbaname == "") {
			alert("<?php echo $user_genbaname_empty; ?>");
			$("#udgenbaname").focus();
			return false;
		}

		if (udgenbacompany == "") {
			alert("<?php echo $user_genbacompany_empty; ?>");
			$("#udgenbacompany").focus();
			return false;
		}

		if (udstrymd == "") {
			alert("<?php echo $user_strymd_empty; ?>");
			$("#udstrymd").focus();
			return false;
		}

		if (udendymd == "") {
			alert("<?php echo $user_endymd_empty; ?>");
			$("#udendymd").focus();
			return false;
		}

		if (udworkstrtime == "") {
			alert("<?php echo $user_workstr_empty; ?>");
			$("#udworkstrtime").focus();
			return false;
		}

		if (udworkendtime == "") {
			alert("<?php echo $user_workend_empty; ?>");
			$("#udworkendtime").focus();
			return false;
		}

		if (udofftime1 == "") {
			alert("<?php echo $user_offtime1_empty; ?>");
			$("#udofftime1").focus();
			return false;
		}

		if (udofftime2 == "") {
			alert("<?php echo $user_offtime2_empty; ?>");
			$("#udofftime2").focus();
			return false;
		}

		function validateTimeFormat(time) {
			var regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
			return regex.test(time);
		}

		var isValid_workstr = validateTimeFormat(udworkstrtime);
		if (!isValid_workstr) {
			alert("<?php echo $user_workstr_incorrect; ?>");
			$("#udworkstrtime").focus();
			return false;
		}

		var isValid_workend = validateTimeFormat(udworkendtime);
		if (!isValid_workend) {
			alert("<?php echo $user_workend_incorrect; ?>");
			$("#udworkendtime").focus();
			return false;
		}

		var isValid_offtime1 = validateTimeFormat(udofftime1);
		if (!isValid_offtime1) {
			alert("<?php echo $user_offtime1_incorrect; ?>");
			$("#udofftime1").focus();
			return false;
		}

		var isValid_offtime2 = validateTimeFormat(udofftime2);
		if (!isValid_offtime2) {
			alert("<?php echo $user_offtime2_incorrect; ?>");
			$("#udofftime2").focus();
			return false;
		}
	});
</script>
<?php include('../inc/footer.php'); ?>