<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kintaidetailmodel.php');
include('../inc/header.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
if ($_SESSION['auth_type'] == constant('USER')) { // if not admin 
	header("Location: ../index.php");
}
?>

<style>
	.submission-status_1 {
		color: #337ab7;
	}

	.submission-status_2 {
		color: blue;
	}

	.submission-status_3 {
		color: green;
	}
</style>

<title>社員勤務表</title>
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
	<div class="row">
		<div class="col-md-3 text-left">
			<div class="title_name">
				<span class="text-left">社員勤務表</span>
			</div>
		</div>
		<div class="col-md-12 text-right">
			<form method="post">
				<div class="col-md-4 text-center page-top-selection" name="workYm_page_condition">
					<div class="title_condition">
						<label>基準日:
							<select id="selyy" name="selyy" class="seldate" style="padding:5px;"
								onchange="this.form.submit()">
								<?php
								foreach (ConstArray::$search_year as $key => $value) {
									?>
									<option value="<?= $key ?>" <?php if ($value == $year) {
										  echo ' selected="selected"';
									  } ?>>
										<?= $value ?>
									</option>
									<?php
								}
								?>
							</select>
							<select id="selmm" name="selmm" class="seldate" style="padding:5px;"
								onchange="this.form.submit()">
								<?php
								foreach (ConstArray::$search_month as $key => $value) {
									?>
									<option value="<?= $key ?>" <?php if ($value == $month) {
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
			</form>
			<form method="post">
				<div class="col-md-6 text-center page-top-selection" name="workYm_page_condition">
					<div class="title_condition">
						<label>フィルター
							<select id="filterShow" name="filterShow" class="seldate" style="padding:5px;"
								onchange="this.form.submit()">
								<?php foreach ($SUBMISSTION_STATUS_FILTER as $key => $value) { ?>
									<option name="submission_status" value="<?= $key?>" <?php if ($key == $selectedFilter) {
										  echo ' selected="selected"';
									  } ?>>
										<?= $value ?>
									</option>
									<?php } ?>
							</select>
						</label>

					</div>
				</div>
			</form>
			<div class="title_btn">
				<input type="button" onclick="window.location.href='../kintai/kintaiReg.php'" value="戻る">
			</div>
		</div>


	</div>

	<div class="form-group">
		<table class="table table-bordered datatable">
			<thead>
				<tr class="info">

					<th style="text-align: center; width: 10%;">社員名</th>
					<th style="text-align: center; width: 20%;">Email</th>
					<th style="text-align: center; width: 10%;">部署</th>
					<th style="text-align: center; width: 10%;">区分</th>
					<th style="text-align: center; width: auto;">勤務時間タイプ</th>
					<th style="text-align: center; width: auto;">状態</th>
					<th style="text-align: center; width: 10%;">詳しい情報</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($user_list)) { ?>
					<tr>
						<td colspan="8" align="center">
							<?php echo $data_save_no; ?>
						</td>
					</tr>
				<?php } elseif (!empty($user_list)) {
					foreach ($user_list as $user) {
						?>
						<tr>
							<td><span name="name">
									<?= $user['name'] ?>
								</span></td>
							<td><span name="email">
									<?= $user['email'] ?>
								</span></td>
							<td><span name="dept">
									<?= $user['dept'] ?>
								</span></td>
							<td><span name="grade">
									<?= $user['grade'] ?>
								</span></td>

							<td><span name="genname">
									<?php foreach ($genba_list as $genba) {
										if ($genba['genid'] === $user['genid']) {
											echo $genba['genbaname'];
											break;
										}
									} ?>
								</span></td>

							<td align="center"><span name="submission_status">
									<?php echo $SUBMISSTION_STATUS[$user['submission_status']] ?>
								</span></td>

							<td class="text-center">
								<form method="post"
									action="../kintaidetail/kintaiUserDetail.php?uid=<?= $user['uid'] ?>&name=<?= $user['name'] ?>&dept=<?= $user['dept'] ?>&email=<?= $user['email'] ?>">
									<input type="hidden" value="<?= $user['uid'] ?>" name="uid_g">
									<input type="hidden" value="<?= $user['email'] ?>" name="email_g">
									<input type="hidden" value="<?= $user['name'] ?>" name="name_g">
									<input type="hidden" value="<?= $user['genid'] ?>" name="genid_g">
									<input type="hidden" value="<?= $user['dept'] ?>" name="dept_g">
									<input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL"
										role="button" value="詳細">
								</form>
							</td>
						</tr>
					<?php }
				} ?>
			</tbody>
		</table>
	</div>
</div>

<script>

	$(document).ready(function () {

		setTimeout(hideLoadingOverlay, 1000);
		startLoading();
		SetFormViewBySubmissionStatusHandler();

	});

	function SetFormViewBySubmissionStatusHandler() {
		SetColorToSubmissionStatus();
	}

	function SetColorToSubmissionStatus() {
    $('[name="submission_status"]').each(function() {
        var submissionStatusText = $(this).text().trim();
        switch (submissionStatusText) {
            case '<?php echo $SUBMISSTION_STATUS[0] ?>':
                $(this).removeClass();
                break;
            case '<?php echo $SUBMISSTION_STATUS[1] ?>':
                $(this).removeClass().addClass('submission-status_1');
                break;
            case '<?php echo $SUBMISSTION_STATUS[2] ?>':
                $(this).removeClass().addClass('submission-status_2');
                break;
            case '<?php echo $SUBMISSTION_STATUS[3] ?>':
                $(this).removeClass().addClass('submission-status_3');
                break;
            case '<?php echo $SUBMISSTION_STATUS[11] ?>':
                $(this).removeClass();
                break;
            default:
                // Default behavior if no match found
                break;
        }
    });
}


</script>