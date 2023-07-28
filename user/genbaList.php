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

if ($_SESSION['auth_type'] == 1) { // if not admin 
	header("Location: ./../../index.php");
}

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
</style>
<title>勤務管理表</title>
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
				<span class="text-left">勤務管理表</span>
			</div>
		</div>
		<div class="col-md-6"></div>
		<div class="col-md-2 text-right">
			<div class="title_btn">
				<input type="button" id="btnNew" value=" 新規 ">
			</div>
		</div>
	</div>

	<div class="form-group">
		<table class="table table-bordered datatable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 5%;">ID</th>
					<th style="text-align: center; width: 20%;">勤務時間タイプ</th>
					<th style="text-align: center; width: 10%;">勤務開始時間</th>
					<th style="text-align: center; width: 10%;">勤務終了時間</th>
					<th style="text-align: center; width: 10%;">昼休</th>
					<th style="text-align: center; width: 10%;">夜休</th>
					<th style="text-align: center; width: 7%;">使用</th>
					<th style="text-align: center; width: auto;">備考</th>
				</tr>
			</thead>

			<tbody>
				<?php if (empty($genbadatas_list)) { ?>
					<tr>
						<td colspan="12" align="center"><?php echo $data_save_no; ?></td>
					</tr>
					<?php } elseif (!empty($genbadatas_list)) {
					foreach ($genbadatas_list as $genba) {
					?>
						<tr>
							<td class="td1"><span><?= $genba['genid'] ?></span></td>
							<td class="td2"><a href="#"><span class="showModal" style="text-decoration-line: underline;"><?= $genba['genbaname'] ?></span></td>
							<td class="td3"><span><?= $genba['workstrtime'] ?></span></td>
							<td class="td4"><span><?= $genba['workendtime'] ?></span></td>
							<td class="td5"><span><?= $genba['offtime1'] ?></span></td>
							<td class="td6"><span><?= $genba['offtime2'] ?></span></td>
							<td class="td7">
								<span>
									<?php if ($genba['use_yn'] == "1") {
										echo "<p style='font-weight:bold;color:green;'>使用</p>";
									} else {
										echo "<p style='font-weight:bold;color:red;'>中止</p>";
									}
									?>
								</span>
							</td>
							<td class="td8"><span><?= $genba['bigo'] ?></span></td>
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
								<label for="genbacompany_rmodal">勤務時間タイプ</label>
								<input type="text" class="form-control" id="genbaname_rmodal" name="genbaname_rmodal" placeholder="勤務時間タイプ">
							</div>
							<div class="col-md-3">
								<label for="use_rmodal"><strong>使用</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" id="use_rmodal" name="use_rmodal" value="1">使用
									<input type="radio" id="use_rmodal" name="use_rmodal" value="0">中止
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="workstr_rmodal">業務開始時間</label>
								<input type="text" class="form-control" id="workstr_rmodal" name="workstr_rmodal" placeholder="09:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="workend_rmodal">業務終了時間</label>
								<input type="text" class="form-control" id="workend_rmodal" name="workend_rmodal" placeholder="18:00" required="required" style="text-align: center">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3">
								<label for="offtime1_rmodal">昼休(時:分)</label>
								<input type="text" class="form-control" id="offtime1_rmodal" name="offtime1_rmodal" placeholder="01:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-3">
								<label for="offtime2_rmodal">夜休(時:分)</label>
								<input type="text" class="form-control" id="offtime2_rmodal" name="offtime2_rmodal" placeholder="00:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="bigo_rmodal">備考</label>
								<input type="text" class="form-control" id="bigo_rmodal" name="bigo_rmodal" placeholder="備考" required="required" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="SaveKinmu" class="btn btn-primary" id="btnReg_rmodal" role="button" value="登録">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-warning" data-dismiss="modal" id="modalClose">閉じる</button>
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
								<label for="genbaname_cmodal">勤務時間タイプ</label>
								<input type="text" class="form-control" name="genbaname_cmodal" id="genbaname_cmodal" placeholder="勤務時間タイプ">
								<input type="hidden" id="genid_cmodal" name="genid_cmodal">
								<input type="hidden" id="companyid_cmodal" name="companyid_cmodal">
								<input type="hidden" id="strymd_cmodal" name="strymd_cmodal">
								<input type="hidden" id="endymd_cmodal" name="endymd_cmodal">
							</div>
							<div class="col-md-3">
								<label for="use_cmodal"><strong>使用</strong></label>
								<div class="custom-control custom-radio">
									<input type="radio" name="use_cmodal" id="use_cmodal1" value="1">使用
									<input type="radio" name="use_cmodal" id="use_cmodal2" value="0">中止
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="workstr_cmodal">業務開始時間</label>
								<input type="text" class="form-control" name="workstr_cmodal" id="workstr_cmodal" placeholder="09:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="workend_cmodal">業務終了時間</label>
								<input type="text" class="form-control" name="workend_cmodal" id="workend_cmodal" placeholder="18:00" required="required" style="text-align: center">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-3">
								<label for="offtime1_cmodal">昼休(時:分)</label>
								<input type="text" class="form-control" name="offtime1_cmodal" id="offtime1_cmodal" placeholder="01:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-3">
								<label for="offtime2_cmodal">夜休(時:分)</label>
								<input type="text" class="form-control" name="offtime2_cmodal" id="offtime2_cmodal" placeholder="00:00" required="required" style="text-align: center">
							</div>
							<div class="col-md-6">
								<label for="bigo_cmodal">備考</label>
								<input type="text" class="form-control" name="bigo_cmodal" id="bigo_cmodal" placeholder="備考" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-3"></div>
						<div class="col-md-2">
							<input type="submit" name="UpdateKinmu" class="btn btn-primary" id="btnUpd_cmodel" role="button" value="編集">
						</div>
						<div class="col-md-2">
							<input type="submit" name="DeleteKinmu" class="btn btn-warning" id="btnDel_cmodel" role="button" value="削除">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-warning" data-dismiss="modal" id="modalClose">閉じる</button>
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
	$(document).on('click', '#btnNew', function(e) {
		$("#use_rmodal").prop('checked', true);
		$('#modal').modal('toggle');
	});

	// Click (modify) employee ID in the grid: popup & display contents
	$(document).on('click', '.showModal', function() {
		$('#modal2').modal('toggle');
		var Genbaname = $(this).text();
		<?php
		if (!empty($genbadatas_list)) {
			foreach ($genbadatas_list as $key) {
		?>
				if ('<?php echo $key['genbaname'] ?>' == Genbaname) {
					var genid_cmodal = $("input[name=genid_cmodal]:hidden");
					genid_cmodal.val("<?php echo $key['genid'] ?>");
					var genid_cmodal = genid_cmodal.val();
					var companyid_cmodal = $("input[name=companyid_cmodal]:hidden");
					companyid_cmodal.val("<?php echo $key['companyid'] ?>");
					var companyid_cmodal = companyid_cmodal.val();
					var strymd_cmodal = $("input[name=strymd_cmodal]:hidden");
					strymd_cmodal.val("<?php echo $key['strymd'] ?>");
					var strymd_cmodal = strymd_cmodal.val();
					var endymd_cmodal = $("input[name=endymd_cmodal]:hidden");
					endymd_cmodal.val("<?php echo $key['endymd'] ?>");
					var endymd_cmodal = endymd_cmodal.val();
					$("#genbaname_cmodal").text($('[name="genbaname_cmodal"]').val("<?php echo $key['genbaname'] ?>"));
					$("input[name='use_cmodal'][value='<?php echo $key['use_yn']; ?>']").prop('checked', true);
					$("#workstr_cmodal").text($('[name="workstr_cmodal"]').val("<?php echo $key['workstrtime'] ?>"));
					$("#workend_cmodal").text($('[name="workend_cmodal"]').val("<?php echo $key['workendtime'] ?>"));
					$("#offtime1_cmodal").text($('[name="offtime1_cmodal"]').val("<?php echo $key['offtime1'] ?>"));
					$("#offtime2_cmodal").text($('[name="offtime2_cmodal"]').val("<?php echo $key['offtime2'] ?>"));
					$("#bigo_cmodal").text($('[name="bigo_cmodal"]').val("<?php echo $key['bigo'] ?>"));
				}
		<?php
			}
		}
		?>
	});

	// Check Error 新規
	$(document).on('click', '#btnReg_rmodal', function(e) {
		var genbaname_rmodal = $("#genbaname_rmodal").val();
		var workstr_rmodal = $("#workstr_rmodal").val();
		var workend_rmodal = $("#workend_rmodal").val();
		var offtime1_rmodal = $("#offtime1_rmodal").val();
		var offtime2_rmodal = $("#offtime2_rmodal").val();

		if (genbaname_rmodal == "") {
			alert("<?php echo $user_genbaname_empty; ?>");
			$("#genbaname_rmodal").focus();
			return false;
		}

		if (workstr_rmodal == "") {
			alert("<?php echo $user_workstr_empty; ?>");
			$("#workstr_rmodal").focus();
			return false;
		}

		if (workend_rmodal == "") {
			alert("<?php echo $user_workend_empty; ?>");
			$("#workend_rmodal").focus();
			return false;
		}

		if (offtime1_rmodal == "") {
			alert("<?php echo $user_offtime1_empty; ?>");
			$("#offtime1_rmodal").focus();
			return false;
		}

		if (offtime2_rmodal == "") {
			alert("<?php echo $user_offtime2_empty; ?>");
			$("#offtime2_rmodal").focus();
			return false;
		}

		function validateTimeFormat(time) {
			var regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
			return regex.test(time);
		}

		var isValid_workstr = validateTimeFormat(workstr_rmodal);
		if (!isValid_workstr) {
			alert("<?php echo $user_workstr_incorrect; ?>");
			$("#workstr_rmodal").focus();
			return false;
		}

		var isValid_workend = validateTimeFormat(workend_rmodal);
		if (!isValid_workend) {
			alert("<?php echo $user_workend_incorrect; ?>");
			$("#workend_rmodal").focus();
			return false;
		}

		var isValid_offtime1 = validateTimeFormat(offtime1_rmodal);
		if (!isValid_offtime1) {
			alert("<?php echo $user_offtime1_incorrect; ?>");
			$("#offtime1_rmodal").focus();
			return false;
		}

		var isValid_offtime2 = validateTimeFormat(offtime2_rmodal);
		if (!isValid_offtime2) {
			alert("<?php echo $user_offtime2_incorrect; ?>");
			$("#offtime2_rmodal").focus();
			return false;
		}
	});

	// Check Error 編集
	$(document).on('click', '#btnUpd_cmodel', function(e) {
		var genbaname_cmodal = $("#genbaname_cmodal").val();
		var workstr_cmodal = $("#workstr_cmodal").val();
		var workend_cmodal = $("#workend_cmodal").val();
		var offtime1_cmodal = $("#offtime1_cmodal").val();
		var offtime2_cmodal = $("#offtime2_cmodal").val();

		if (genbaname_cmodal == "") {
			alert("<?php echo $user_genbaname_empty; ?>");
			$("#genbaname_cmodal").focus();
			return false;
		}

		if (workstr_cmodal == "") {
			alert("<?php echo $user_workstr_empty; ?>");
			$("#workstr_cmodal").focus();
			return false;
		}

		if (workend_cmodal == "") {
			alert("<?php echo $user_workend_empty; ?>");
			$("#workend_cmodal").focus();
			return false;
		}

		if (offtime1_cmodal == "") {
			alert("<?php echo $user_offtime1_empty; ?>");
			$("#offtime1_cmodal").focus();
			return false;
		}

		if (offtime2_cmodal == "") {
			alert("<?php echo $user_offtime2_empty; ?>");
			$("#offtime2_cmodal").focus();
			return false;
		}

		function validateTimeFormat(time) {
			var regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
			return regex.test(time);
		}

		var isValid_workstr = validateTimeFormat(workstr_cmodal);
		if (!isValid_workstr) {
			alert("<?php echo $user_workstr_incorrect; ?>");
			$("#workstr_cmodal").focus();
			return false;
		}

		var isValid_workend = validateTimeFormat(workend_cmodal);
		if (!isValid_workend) {
			alert("<?php echo $user_workend_incorrect; ?>");
			$("#workend_cmodal").focus();
			return false;
		}

		var isValid_offtime1 = validateTimeFormat(offtime1_cmodal);
		if (!isValid_offtime1) {
			alert("<?php echo $user_offtime1_incorrect; ?>");
			$("#offtime1_cmodal").focus();
			return false;
		}

		var isValid_offtime2 = validateTimeFormat(offtime2_cmodal);
		if (!isValid_offtime2) {
			alert("<?php echo $user_offtime2_incorrect; ?>");
			$("#offtime2_cmodal").focus();
			return false;
		}
	});
</script>
<?php include('../inc/footer.php'); ?>