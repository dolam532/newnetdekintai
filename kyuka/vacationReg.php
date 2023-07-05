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

	.col-md-6.col-sm-6.col-sx-6.text-right.all-div.last {
		float: right;
	}

	.form-group {
		margin-top: 10px;
	}

	/* For Mobile Landscape View iPhone XR,12Pro */
	@media screen and (max-device-width: 896px) and (orientation: landscape) {}

	/* For Mobile Landscape View iPhone X,6,7,8 PLUS */
	@media screen and (max-device-width: 837px) and (orientation: landscape) {}

	/* For Mobile portrait View iPhone XR,12Pro */
	@media screen and (max-device-width: 414px) and (orientation: portrait) {}

	/* For Mobile portrait View iPhone X,6,7,8 PLUS */
	@media screen and (max-device-width: 375px) and (orientation: portrait) {}
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
			<div class="col-md-6 col-sm-6 col-sx-6 text-left all-div">
				<div class="title_name">
					<span class="text-left">休暇情報</span>
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-sx-6 text-right all-div last">
				<div class="title_btn">
					<input type="button" onclick="window.location.href='./kyukaReg.php'" value="戻る ">
				</div>
			</div>
		</div>
		<div class="form-group">
			<table class="table table-bordered datatable">
				<thead>
					<tr class="info">
						<th class="th0" style="text-align: center; width: 2%;">ID</th>
						<th class="th1" style="text-align: center; width: 8%;">休暇ID</th>
						<th class="th2" style="text-align: center; width: 10%;">休暇開始日</th>
						<th class="th3" style="text-align: center; width: 16%;">休暇終了日</th>
						<th class="th4" style="text-align: center; width: 10%;">前回の休暇日数</th>
						<th class="th5" style="text-align: center; width: 16%;">休暇残日数</th>
						<th class="th6" style="text-align: center; width: 8%;">休暇使用日数</th>
						<th class="th7" style="text-align: center; width: 8%;">休暇使用時間</th>
						<th class="th8" style="text-align: center; width: auto;">休暇休憩回数</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($vacationinfo_list)) { ?>
						<tr>
							<td colspan="12" align="center"><?php echo $data_save_no; ?></td>
						</tr>
						<?php } elseif (!empty($vacationinfo_list)) {
						foreach ($vacationinfo_list as $vacationinfo) {
						?>
							<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
								<tr>
									<td class="td0"><a href="#"><span class="showModal" style="text-decoration-line: underline;"><?= $vacationinfo['uid'] ?><span class="vacationid_class"><?= ',' . $vacationinfo['vacationid'] ?></span></span></td>
									<td class="td1"><span><?= $vacationinfo['vacationid'] ?></span></td>
									<td class="td2"><span><?= $vacationinfo['vacationstr'] ?></span></td>
									<td class="td3"><span><?= $vacationinfo['vacationend'] ?></span></td>
									<td class="td4"><span><?= $vacationinfo['oldcnt'] ?></td>
									<td class="td5"><span><?= $vacationinfo['newcnt'] ?></span></td>
									<td class="td6"><span><?= $vacationinfo['usecnt'] ?></span></td>
									<td class="td7"><span><?= $vacationinfo['usetime'] ?></span></td>
									<td class="td8"><span><?= $vacationinfo['restcnt'] ?></span></td>
								</tr>
							<?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
								<?php
								if ($vacationinfo['type'] == $_SESSION['auth_type'] && $vacationinfo['uid'] == $_SESSION['auth_uid']) {
								?>
									<tr>
										<td class="td0"><a href="#"><span class="showModal" style="text-decoration-line: underline;"><?= $vacationinfo['uid'] ?><span class="vacationid_class"><?= ',' . $vacationinfo['vacationid'] ?></span></span></td>
										<td class="td1"><span><?= $vacationinfo['vacationid'] ?></span></td>
										<td class="td2"><span><?= $vacationinfo['vacationstr'] ?></span></td>
										<td class="td3"><span><?= $vacationinfo['vacationend'] ?></span></td>
										<td class="td4"><span><?= $vacationinfo['oldcnt'] ?></td>
										<td class="td5"><span><?= $vacationinfo['newcnt'] ?></span></td>
										<td class="td6"><span><?= $vacationinfo['usecnt'] ?></span></td>
										<td class="td7"><span><?= $vacationinfo['usetime'] ?></span></td>
										<td class="td8"><span><?= $vacationinfo['restcnt'] ?></span></td>
									</tr>
								<?php } ?>
							<?php endif; ?>
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
						<div class="modal-header">
							<span id="ustitle">休暇登録</span>
							(<span id="usname">New</span>)
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row one">
								<div class="col-md-4 col-sm-4 col-sx-4 uid">
									<label for="uid">ID</label>
									<input type="text" class="form-control" id="usuid" name="usuid" style="text-align: left" readonly>
									<input type="hidden" id="usvacationid" name="usvacationid">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacationstr">
									<label for="vacationstr">休暇開始日</label>
									<input type="text" class="form-control" id="usvacationstr" name="usvacationstr" maxlength="10" placeholder="日付け" style="text-align: left">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacationend">
									<label for="vacationend">休暇終了日</label>
									<input type="text" class="form-control" id="usvacationend" name="usvacationend" maxlength="10" placeholder="日付け" style="text-align: left">
								</div>
							</div>
							<br>
							<div class="row two">
								<div class="col-md-6 col-sm-6 col-sx-6 vacation">
									<label for="oldcnt">休暇使用数</label>
									<input type="text" class="form-control" id="usoldcnt" name="usoldcnt" placeholder="番号" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-6 col-sm-6 col-sx-6 vacation">
									<label for="newcnt">休暇残日数</label>
									<input type="text" class="form-control" id="usnewcnt" name="usnewcnt" placeholder="番号" required="required" maxlength="10" style="text-align: center">
								</div>
							</div>
							<br>
							<div class="row three">
								<div class="col-md-4 col-sm-4 col-sx-4 vacation">
									<label for="usecnt">休暇使用数</label>
									<input type="text" class="form-control" id="ususecnt" name="ususecnt" placeholder="番号" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacation">
									<label for="usetime">休暇使用時間</label>
									<input type="text" class="form-control" id="ususetime" name="ususetime" placeholder="番号" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacation">
									<label for="restcnt">休暇休憩回数</label>
									<input type="text" class="form-control" id="usrestcnt" name="usrestcnt" placeholder="番号" required="required" maxlength="10" style="text-align: center">
								</div>
							</div>
						</div>
						<div class="modal-footer" style="text-align: center">
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<input type="submit" name="SaveUpdateKyuka" class="btn btn-primary btn-md" id="btnRegSaveUpdate" role="button" value="登録">
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
	// Calender datepicker
	$("#usvacationstr").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});
	$("#usvacationend").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	// Click (modify) employee ID in the grid: popup & display contents
	$(document).on('click', '.showModal', function() {
		$('#modal').modal('toggle');
		var ArrayID = $(this).text();
		var SeparateArrID = ArrayID.split(',');
		var Uid = SeparateArrID[0];
		var Vacationid = SeparateArrID[1];
		if (Vacationid != "") {
			<?php
			if (!empty($vacationinfo_list)) {
				foreach ($vacationinfo_list as $key) {
			?>
					if ('<?php echo $key['uid'] ?>' == Uid) {
						$("#ustitle").text('休暇編集');
						$("#usname").text('<?php echo $key['name'] ?>');
						$("#usuid").text($('[name="usuid"]').val("<?php echo $key['uid'] ?>"));
						var vacationid = $("input[name=usvacationid]:hidden");
						vacationid.val("<?php echo $key['vacationid'] ?>");
						var vacationid = vacationid.val();
						$("#usvacationstr").text($('[name="usvacationstr"]').val("<?php echo $key['vacationstr'] ?>"));
						$("#usvacationend").text($('[name="usvacationend"]').val("<?php echo $key['vacationend'] ?>"));
						$("#usoldcnt").text($('[name="usoldcnt"]').val("<?php echo $key['oldcnt'] ?>"));
						$("#usnewcnt").text($('[name="usnewcnt"]').val("<?php echo $key['newcnt'] ?>"));
						$("#ususecnt").text($('[name="ususecnt"]').val("<?php echo $key['usecnt'] ?>"));
						$("#ususetime").text($('[name="ususetime"]').val("<?php echo $key['usetime'] ?>"));
						$("#usrestcnt").text($('[name="usrestcnt"]').val("<?php echo $key['restcnt'] ?>"));
					}
			<?php
				}
			}
			?>
		}
		if (Vacationid == "") {
			$("#ustitle").text('休暇登録');
			$("#usname").text('New');
			$("#usuid").text($('[name="usuid"]').val(Uid));
			var vacationid = $("input[name=usvacationid]:hidden");
			vacationid.val("0");
			var vacationid = vacationid.val();
			$("#usvacationstr").text($('[name="usvacationstr"]').val(""));
			$("#usvacationend").text($('[name="usvacationend"]').val(""));
			$("#usoldcnt").text($('[name="usoldcnt"]').val(""));
			$("#usnewcnt").text($('[name="usnewcnt"]').val(""));
			$("#ususecnt").text($('[name="ususecnt"]').val(""));
			$("#ususetime").text($('[name="ususetime"]').val(""));
			$("#usrestcnt").text($('[name="usrestcnt"]').val(""));
		}
	});

	// Check Error
	$(document).on('click', '#btnRegSaveUpdate', function(e) {
		var vacationstr = $("#usvacationstr").val();
		var vacationend = $("#usvacationend").val();
		var oldcnt = $("#usoldcnt").val();
		var newcnt = $("#usnewcnt").val();
		var usecnt = $("#ususecnt").val();
		var usetime = $("#ususetime").val();
		var restcnt = $("#usrestcnt").val();
		var number_no = /^[0-9]+$/;

		if (vacationstr == "") {
			alert("<?php echo $kyuka_vacationstr_empty; ?>");
			$("#usvacationstr").focus();
			e.preventDefault();
			return;
		}
		if (vacationend == "") {
			alert("<?php echo $kyuka_vacationend_empty; ?>");
			$("#usvacationend").focus();
			e.preventDefault();
			return;
		}
		if (oldcnt == "") {
			alert("<?php echo $kyuka_oldcnt_empty; ?>");
			$("#usoldcnt").focus();
			return;
		}
		if (newcnt == "") {
			alert("<?php echo $kyuka_newcnt_empty; ?>");
			$("#usnewcnt").focus();
			e.preventDefault();
			return;
		}
		if (usecnt == "") {
			alert("<?php echo $kyuka_usecnt_empty; ?>");
			$("#ususecnt").focus();
			e.preventDefault();
			return;
		}
		if (usetime == "") {
			alert("<?php echo $kyuka_usetime_empty; ?>");
			$("#ususetime").focus();
			e.preventDefault();
			return;
		}
		if (restcnt == "") {
			alert("<?php echo $kyuka_restcnt_empty; ?>");
			$("#usrestcnt").focus();
			e.preventDefault();
			return;
		}

		if (!oldcnt.match(number_no)) {
			alert("<?php echo $kyuka_oldcnt_no; ?>");
			e.preventDefault();
			$("#usoldcnt").focus();
			return true;
		}

		if (!newcnt.match(number_no)) {
			alert("<?php echo $kyuka_newcnt_no; ?>");
			e.preventDefault();
			$("#usnewcnt").focus();
			return true;
		}

		if (!usecnt.match(number_no)) {
			alert("<?php echo $kyuka_usecnt_no; ?>");
			e.preventDefault();
			$("#ususecnt").focus();
			return true;
		}

		if (!usetime.match(number_no)) {
			alert("<?php echo $kyuka_usetime_no; ?>");
			e.preventDefault();
			$("#ususetime").focus();
			return true;
		}

		if (!restcnt.match(number_no)) {
			alert("<?php echo $kyuka_restcnt_no; ?>");
			e.preventDefault();
			$("#usrestcnt").focus();
			return true;
		}
	});
</script>
<?php include('../inc/footer.php'); ?>