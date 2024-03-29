<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const.php');
include('../inc/const_array.php');
include('../model/kintaimodel.php');
include('../inc/header.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
?>


<style>
	.sumtbl tr th {
		background-color: #D9EDF7;
		text-align: center;
	}

	.sumtbl tr td {
		text-align: center;
	}

	.datatable tr td {
		text-align: center;
	}

	.colorSuccess {
		color: forestgreen;
	}

	.colorError {
		color: red
	}

	.table_hidden {
		display: none;
	}

	.overflow_ellipsis {
		white-space: nowrap;
		overflow: hidden;
		text-overflow: ellipsis;
	}




	.print_btn {
		display: inline-block;
		padding-top: 10px;
	}

	span.kintaiReg_class {
		display: none;
	}

	.holder {
		position: relative;
		height: 20px;
	}

	#jobstarthh,
	#holy_decide,
	#jobstartmm,
	#jobendhh,
	#jobendmm {
		position: relative;
		z-index: 30;
	}

	#daystarthh,
	#daystartmm,
	#dayendhh,
	#dayendmm {
		position: relative;
		z-index: 20;
	}

	#offtimehh,
	#offtimemm {
		position: relative;
		z-index: 10;
	}

	.text_size {
		font-size: smaller;
	}

	.title_name {
		text-align: right;
	}

	.title_condition {
		display: flex;
		justify-content: flex-end;
		align-items: flex-end;
	}




	.top-action-btn {
		display: flex;
		justify-content: flex-end;
		align-items: flex-end;
		margin-top: -10px;

	}

	.col-md-6.right {
		text-align: right;
		margin: 5px 0;
	}

	.submit-top {
		text-align: right;
		/* padding-right: -15px; */
		margin-left: 5px;
	}




	/* lanscape notice */
	#landscape-warning {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0, 0, 0, 0.5);
		z-index: 1000;
		text-align: center;
		padding: 20px;
	}

	#warning-message {
		background: #fff;
		padding: 10px;
		border: 1px solid #000;
		border-radius: 5px;
		display: inline-block;
	}

	/* 2023/11/13 submission-status  add start --> */

	.print_btn-submit {
		display: inline-block;
		margin-top: 30px;
	}

	.top-action-btn-admin {
		display: flex;
		justify-content: flex-end;
		align-items: flex-end;
		margin-top: -40px;
	}

	.col-md-12.right {
		text-align: right;
		margin-bottom: 5px;
	}

	.submission-status {
		margin-left: 20px;
	}

	.submission-status_1 {
		color: #337ab7;
	}

	.submission-status_2 {
		color: blue;
	}

	.submission-status_3 {
		color: green;
	}

	.workmonth-submit {
		box-shadow: 0 0 2px #3498db;
	}

	.submissionStatus {
		display: flex;
		align-content: center;
		justify-content: center;
		flex-wrap: nowrap;
	}

	.submissionStatusNotice {
		display: flex;
		margin-top: -10px;
		margin-right: 5px;
		justify-content: flex-end;
		flex-wrap: nowrap;

	}

	.submissionNoticeElem {
		background: rgba(0, 255, 0, 0.1);
	}



	/* 2023/11/13 submission-status  add end --> */
</style>

<script>
	// everytime load page -> check is innerWidth < 600 -> show messsage 
	// when start time width > 600 -> no show 
	var isWarningDisplayed = false;
	function showLandscapeWarning() {
		if (!isWarningDisplayed && window.innerWidth < 600 && window.orientation !== 90) {
			var landscapeWarning = document.getElementById("landscape-warning");
			landscapeWarning.style.display = "block";

			landscapeWarning.addEventListener("click", function () {
				landscapeWarning.style.display = "none";
			});

			setTimeout(function () {
				landscapeWarning.style.display = "none";
			}, 5000); // 5s
		}
		isWarningDisplayed = true;
	}
	window.addEventListener('load', showLandscapeWarning);
	window.addEventListener('resize', showLandscapeWarning);
	window.addEventListener('orientationchange', showLandscapeWarning);
</script>


<title>勤 務 表</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top: -20px;">
	<?php
	if (isset($_SESSION['save_success']) && isset($_POST['changeGenid'])) {
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
	if (isset($_SESSION['save_success']) && isset($_POST['SaveUpdateKintai'])) {
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
	if (isset($_SESSION['autosave_success']) && isset($_POST['AutoUpdateKintai'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['autosave_success']; ?>
		</div>
		<?php
		unset($_SESSION['autosave_success']);
	}
	?>
	<?php
	if (isset($_SESSION['delete_success']) && isset($_POST['DeleteKintai'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['delete_success']; ?>
		</div>
		<?php
		unset($_SESSION['delete_success']);
	}
	?>
	<?php
	if (isset($_SESSION['save_success']) && isset($_POST['MonthSaveKintai'])) {
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
	if (isset($_SESSION['delete_all_success']) && isset($_POST['DeleteAll'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['delete_all_success']; ?>
		</div>
		<?php
		unset($_SESSION['delete_all_success']);
	}
	?>
	<!-- 2023/11/13 submission-status  add start -->
	<?php
	if (isset($_SESSION['kakutei_success']) && isset($_POST['WorkmonthKakutei'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['kakutei_success']; ?>
		</div>
		<?php
		unset($_SESSION['kakutei_success']);
	}
	?>

	<?php
	if (isset($_SESSION['kakutei_fail']) && isset($_POST['WorkmonthKakutei'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['kakutei_fail']; ?>
		</div>
		<?php
		unset($_SESSION['kakutei_fail']);
	}
	?>



	<?php
	if (isset($_SESSION['modoshi_success']) && isset($_POST['WorkmonthModoshi'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['modoshi_success']; ?>
		</div>
		<?php
		unset($_SESSION['modoshi_success']);
	}
	?>

	<?php
	if (isset($_SESSION['shonin_success']) && isset($_POST['WorkmonthShonin'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['shonin_success']; ?>
		</div>
		<?php
		unset($_SESSION['shonin_success']);
	}
	?>


	<?php
	if (isset($_SESSION['shonin_notkakutei_fail']) && isset($_POST['WorkmonthShonin'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['shonin_notkakutei_fail']; ?>
		</div>
		<?php
		unset($_SESSION['shonin_notkakutei_fail']);
	}
	?>



	<?php
	if (isset($_SESSION['sekininshonin_success']) && isset($_POST['WorkmonthSekininShonin'])) {
		?>
		<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['sekininshonin_success']; ?>
		</div>
		<?php
		unset($_SESSION['sekininshonin_success']);
	}
	?>

	<?php
	if (isset($_SESSION['sekininshonin_notkakutei_fail']) && isset($_POST['WorkmonthSekininShonin'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['sekininshonin_notkakutei_fail']; ?>
		</div>
		<?php
		unset($_SESSION['sekininshonin_notkakutei_fail']);
	}
	?>


	<?php
	if (isset($_SESSION['is_submissed_notchange'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['is_submissed_notchange']; ?>
		</div>
		<?php
		unset($_SESSION['is_submissed_notchange']);
	}
	?>


	<!-- 2023/11/13 submission-status  add end -->


	<!-- 2023/11/16 Update Sekinin  Kanri   add start -->
	<?php
	if (isset($_SESSION['Shonin_KanriSha_Undefine']) && isset($_POST['WorkmonthShonin'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['Shonin_KanriSha_Undefine']; ?>
		</div>
		<?php
		unset($_SESSION['Shonin_KanriSha_Undefine']);
	}
	?>

	<?php
	if (isset($_SESSION['Shonin_SekininSha_Undefine']) && isset($_POST['WorkmonthSekininShonin'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['Shonin_SekininSha_Undefine']; ?>
		</div>
		<?php
		unset($_SESSION['Shonin_SekininSha_Undefine']);
	}
	?>

	<!-- 2023/11/16 Update Sekinin  Kanri   add end -->



	<div class="row">
		<div class="col-md-3 text-left page-top" name="workYm_page_title">
			<div class="title_name text-center">
				<span id="workYm_page_title" class="text-left">勤 務 表</span>
			</div>
		</div>
		<!-- lanscape notice  -->
		<div id="landscape-warning">
			<p id="warning-message">

				画面を横向きにすると、コンテンツが正しく表示されます。
				<br>
				(どこかをクリックすると閉じます)
			</p>
		</div>
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
	</div>
	<!-- 2023/11/10 submission-status  add start -->
	<div class="row"> <br /> </div>
	<div class="row submissionStatus">
		<p class="seldate submission-status">
			<strong>状態</strong>:
		<p id="submission-status" name="submission-status" value="<?php echo $submissionStatus ?>">
			<?php echo $submissionStatusText ?>
		</p>
		</p>
	</div>

	<?php if (($submissionStatusText !== $SUBMISSTION_STATUS[0]) && ($submissionStatusText !== $SUBMISSTION_STATUS[11])): ?>
		<div class="row submissionStatusNotice">
			<p class="submissionNoticeElem" style=' font-style: italic; font-size: smaller;'>*
				<?php echo $kakutei_success ?>
			</p>
		</div>
	<?php endif; ?>
	<br />

	<div class="row top-action-btn-admin">
		<!-- 2023/11/10 submission-status  change start -->
		<!-- <div class="col-md-6 right"> -->
		<div class="col-md-12 right">
			<!-- 2023/11/10 submission-status  change start -->
			<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>

				<div class="print_btn-submit">
					<form method="post">
						<a href="../kintaidetail/kintaiUser.php" class="btn btn-default workmonth-submit"
							style="width: auto;">社員勤務表</a>
					</form>
				</div>
				<?php if ($_SESSION['auth_type'] !== constant('ADMINISTRATOR')): ?>
					<div class="print_btn-submit">
						<form method="post">
							<button type="submit" href="#" name="WorkmonthSekininShonin" id="WorkmonthSekininShonin"
								class="btn btn-default workmonth-submit workmonth-submit-sekininshonin" style="width: auto;"
								onclick="return confirm('責任者承認済みでよろしいでしょうか？ \n変更がある場合は編集中に戻して、編集してください')">責任者承認</button>
							<!-- 2023-11-16 Admin Select Add Start -->
							<input type="hidden" id="selectedKanri" name="selectedKanri"
								value="<?= $admin_listKanri[0]['uid'] ?>">
							<input type="hidden" id="selectedSekinin" name="selectedSekinin"
								value="<?= $admin_listSekinin[0]['uid'] ?>">
							<!-- 2023-11-16 Admin Select Add end -->
						</form>
					</div>
				<?php endif; ?>
				<div class="print_btn-submit">
					<form method="post">
						<button type="submit" href="#" name="WorkmonthShonin" id="WorkmonthShonin"
							class="btn btn-default workmonth-submit workmonth-submit-shonin" style="width: auto;"
							onclick="return confirm('担当者承認済みでよろしいでしょうか？ \n変更がある場合は編集中に戻て、編集してください')">担当者承認</button>
						<!-- 2023-11-16 Admin Select Add Start -->
						<input type="hidden" id="selectedKanri" name="selectedKanri"
							value="<?= $admin_listKanri[0]['uid'] ?>">
						<input type="hidden" id="selectedSekinin" name="selectedSekinin"
							value="<?= $admin_listSekinin[0]['uid'] ?>">
						<!-- 2023-11-16 Admin Select Add end -->
					</form>

				</div>
				<div class="print_btn-submit">
					<form method="post">
						<button type="submit" href="#" name="WorkmonthModoshi" id="WorkmonthModoshi"
							class="btn btn-default workmonth-submit workmonth-submit-modoshi" style="width: auto;"
							onclick="return confirm('編集中に戻してよろしいでしょうか？')">編集中に戻す</button>
					</form>
				</div>
			<?php else: ?>
				<br />
			<?php endif; ?>

		</div>
	</div>
	<!-- 2023/11/10 submission-status  add end -->
	<div class="row top-action-btn">
		<!-- 2023/11/10 submission-status  add start -->
		<!-- 2023/11/10 submission-status  change start -->
		<!-- <div class="col-md-6 right"> -->
		<div class="col-md-12 right">
			<!-- 2023/11/10 submission-status  change end -->
			<div class="print_btn">
				<form method="post">
					<!-- 2023/11/10 submission-status  change start -->
					<button type="submit" href="#" name="WorkmonthKakutei" id="WorkmonthKakutei"
						class="btn btn-default workmonth-submit-kakutei" style="width: auto;"
						onclick="return checkSubmisBefore()">提出</button>
					<!-- 2023/11/10 submission-status  change end -->

				</form>
			</div>
			<!-- 2023/11/10 submission-status  add end -->
			<div class="print_btn">
				<a href="#" onclick="kinmutypeHandle()" ; class="btn btn-default" style="width: auto;">勤務タイプ選択</a>
			</div>


			<div class="print_btn">
				<form method="post">
					<input type="hidden" value="<?= $year ?>" name="year">
					<input type="hidden" value="<?= $month ?>" name="month">
					<button name="DeleteAll" class="btn btn-default" style="width: auto;" type="submit"
						id="DeleteAllBtn" onclick="return confirm('以下のデータを全て削除しますか？')">すべて削除</button>
				</form>
			</div>


			<div class="print_btn">
				<button href="#" onclick="autoInputHandle()" id="AutoRegisterBtn" class="btn btn-default"
					style="width: auto;">自動入力</button>
			</div>
			<div class="print_btn">
				<button id="submit-button" class="btn btn-default" style="width: auto;" type="button">勤務表印刷</button>
			</div>


		</div>
	</div>

	<div class="form-group">
		<table class="table table-bordered datatable table_topViewTable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 8%;">日付</th>
					<?php
					if ($decide_template_ == "1"): ?>
						<th style="text-align: center; width: 20%;" colspan="2">業務時間</th>
					<?php else: ?>
						<th style="text-align: center; width: 14%;" colspan="2">出退社時間</th>
						<th style="text-align: center; width: 14%;" colspan="2">業務時間</th>
					<?php endif; ?>
					<th style="text-align: center; width: 9%;">休憩時間</th>
					<th style="text-align: center; width: 9%;">就業時間</th>
					<th style="text-align: center; width: auto;">業務内容</th>
					<th style="text-align: center; width: 18%;">備考</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($decide_template_ == "1"): ?>
					<?php
					foreach ($datas as $key) {
						?>
						<tr name="workDayOfMonth">
							<td>
								<?php if ($key['decide_color'] == "土"): ?>
									<a href="#" style="color:blue;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>
								<?php elseif ($key['decide_color'] == "日"): ?>
									<a href="#" style="color:red;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>
								<?php elseif ($key['isHoliday']): ?>
									<a href="#" style="color:red;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>

								<?php else: ?>
									<a href="#">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>
								<?php endif; ?>
							</td>
							<td>
								<?= $key['jobstarthh'] ?>:
								<?= $key['jobstartmm'] ?>
								<!-- fix 18: 00  -> 18:00 to show  -->
							</td>
							<td>
								<?= $key['jobendhh'] ?>:
								<?= $key['jobendmm'] ?>
								<!-- fix 18: 00  -> 18:00 to show  -->
							</td>
							<td>
								<?= $key['offtimehh'] ?>:
								<?= $key['offtimemm'] ?>
								<!-- fix 18: 00  -> 18:00 to show  -->
							</td>
							<td>
								<!-- fix 8:0  -> 08:00 to show   -->
								<?= (empty($key['workhh']) && empty($key['workmm'])) || ($key['workhh'] === '00' && $key['workmm'] === '00') ? '' : sprintf('%2d:%02d', $key['workhh'], $key['workmm']) ?>
							</td>
							<td>
								<?= $key['comment'] ?>
							</td>
							<input type="hidden" name="holy_decide" value="<?= $key['holy_decide'] ?>">
							<td>
								<?= $key['bigo'] ?>
							</td>
						</tr>

						<?php
					}
					?>
				<?php else: ?>
					<?php
					foreach ($datas as $key) {
						?>
						<tr name="workDayOfMonth">
							<td>
								<?php if ($key['decide_color'] == "土"): ?>
									<a href="#" style="color:blue;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>
								<?php elseif ($key['decide_color'] == "日"): ?>
									<a href="#" style="color:red;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>

								<?php elseif ($key['isHoliday']): ?>
									<a href="#" style="color:red;">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>

								<?php else: ?>
									<a href="#">
										<span class="showModal">
											<?= $key['date']; ?><span class="kintaiReg_class">
												<?= ',' . $key['jobstarthh'] ?>
											</span>
										</span>
									</a>
								<?php endif; ?>
							</td>
							<td>
								<?= $key['daystarthh'] ?>:
								<?= $key['daystartmm'] ?>
							</td>
							<td>
								<?= $key['dayendhh'] ?>:
								<?= $key['dayendmm'] ?>
							</td>
							<td>
								<?= $key['jobstarthh'] ?>:
								<?= $key['jobstartmm'] ?>
							</td>
							<td>
								<?= $key['jobendhh'] ?>:
								<?= $key['jobendmm'] ?>
							</td>
							<td>
								<?= $key['offtimehh'] ?>:
								<?= $key['offtimemm'] ?>
							</td>
							<td>
								<!-- fix 8:0  08:00 to show   -->
								<?= (empty($key['workhh']) && empty($key['workmm'])) || ($key['workhh'] === '00' && $key['workmm'] === '00') ? '' : sprintf('%02d:%02d', $key['workhh'], $key['workmm']) ?>
							</td>
							<td>
								<?= $key['comment'] ?>
							</td>
							<input type="hidden" name="holy_decide" value="<?= $key['holy_decide'] ?>">
							<td>
								<?= $key['bigo'] ?>
							</td>
						</tr>
						<?php
					}
					?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

<div class="container">
	<table id="footer-Table" class="table table-bordered datatable">
		<tbody class="sumtbl">
			<tr id="footer_table_title">
				<th style="width: 10%; padding-top: 30px;" rowspan="2">実働時間</th>
				<th style="width: 8%;">時間</th>
				<th style="width: 8%;">分</th>
				<th style="width: 5%; padding-top: 30px;" rowspan="3">勤務状況</th>
				<th style="width: 10%;">所定勤務日数</th>
				<th style="width: 10%;">実勤務日数</th>
				<th style="width: 10%;">休暇</th>
				<th style="width: 10%;">欠勤</th>
				<th style="width: 10%;">休業</th>
				<th style="width: 10%;">遅刻</th>
				<th style="width: auto;">早退</th>
			</tr>
			<tr id="footer_table_show_value">
				<?php
				if (!empty($workmonth_list)) {
					foreach ($workmonth_list as $key) {
						?>
						<?php if ($decide_template_ == "1"): ?>
							<td><strong>
									<?= $totalworkhh_top = isset($totalWorkHours) ? $totalWorkHours : (isset($key['jobhour2']) ? $key['jobhour2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $totalworkmm_top = isset($totalWorkMinutes) ? $totalWorkMinutes : (isset($key['jobminute2']) ? $key['jobminute2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $cnprejob_top = isset($jobdays2) ? $jobdays2 : '0'; ?>
								</strong></td>
							<td><strong>
									<?= $cnactjob_top = isset($countJobStartHH) ? $countJobStartHH : (isset($key['workdays2']) ? $key['workdays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $holydayswork_top = isset($countKuyka) ? $countKuyka : (isset($key['holydays2']) ? $key['holydays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $offdayswork_top = isset($countKekkin) ? $countKekkin : (isset($key['offdays2']) ? $key['offdays2'] : '0'); ?>
								</strong></td>

							<td><strong>
									<?= $closedayswork_top = isset($countClose) ? $countClose : (isset($key['closedays2']) ? $key['closedays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $delaydayswork_top = isset($countLate) ? $countLate : (isset($key['delaydays2']) ? $key['delaydays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $earlydayswork_top = isset($countEarly) ? $countEarly : (isset($key['earlydays2']) ? $key['earlydays2'] : '0'); ?>
								</strong></td>

						<?php elseif ($decide_template_ == "2"): ?>
							<td><strong>
									<?= $totaldayhh_top = isset($totalDayHours) ? $totalDayHours : (isset($key['jobhour2']) ? $key['jobhour2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $totaldaymm_top = isset($totalDayMinutes) ? $totalDayMinutes : (isset($key['jobminute2']) ? $key['jobminute2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $cnprejob_top = isset($jobdays2) ? $jobdays2 : '0'; ?>
								</strong></td>
							<td><strong>
									<?= $cnactjob_top = isset($countJobStartHH) ? $countJobStartHH : (isset($key['workdays2']) ? $key['workdays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $holydayswork_top = isset($countKuyka) ? $countKuyka : (isset($key['holydays2']) ? $key['holydays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $offdayswork_top = isset($countKekkin) ? $countKekkin : (isset($key['offdays2']) ? $key['offdays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $closedayswork_top = isset($countClose) ? $countClose : (isset($key['closedays2']) ? $key['closedays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $delaydayswork_top = isset($countLate) ? $countLate : (isset($key['delaydays2']) ? $key['delaydays2'] : '0'); ?>
								</strong></td>
							<td><strong>
									<?= $earlydayswork_top = isset($countEarly) ? $countEarly : (isset($key['earlydays2']) ? $key['earlydays2'] : '0'); ?>
								</strong></td>
						<?php endif; ?>
						<?php
					}
				} else {
					?>
					<?php if ($decide_template_ == "1"): ?>
						<td><strong>
								<?= $totalworkhh_top = isset($totalWorkHours) ? $totalWorkHours : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $totalworkmm_top = isset($totalWorkMinutes) ? $totalWorkMinutes : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $cnprejob_top = isset($jobdays2) ? $jobdays2 : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $cnactjob_top = isset($countJobStartHH) ? $countJobStartHH : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $holydayswork_top = isset($countKuyka) ? $countKuyka : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $offdayswork_top = isset($countKekkin) ? $countKekkin : '0'; ?>
							</strong></td>

						<td><strong>
								<?= $closedayswork_top = isset($countClose) ? $countClose : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $delaydayswork_top = '0'; ?>
							</strong></td>
						<td><strong>
								<?= $earlydayswork_top = '0'; ?>
							</strong></td>
					<?php elseif ($decide_template_ == "2"): ?>
						<td><strong>
								<?= $totaldayhh_top = isset($totalDayHours) ? $totalDayHours : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $totaldaymm_top = isset($totalDayMinutes) ? $totalDayMinutes : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $cnprejob_top = isset($jobdays2) ? $jobdays2 : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $cnactjob_top = isset($countDayStartHH) ? $countDayStartHH : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $holydayswork_top = isset($countKuyka) ? $countKuyka : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $offdayswork_top = isset($countKekkin) ? $countKekkin : '0'; ?>
							</strong></td>

						<td><strong>
								<?= $closedayswork_top = isset($countClose) ? $countClose : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $delaydayswork_top = isset($countLate) ? $countLate : '0'; ?>
							</strong></td>
						<td><strong>
								<?= $earlydayswork_top = isset($countEarly) ? $countEarly : '0'; ?>
							</strong></td>
					<?php endif; ?>
					<?php
				}
				?>
			</tr>
			<tr id="footer_table_edit_input">
				<form method="post">
					<td>
						<input type="hidden" value="<?= $year ?>" name="year">
						<input type="hidden" value="<?= $month ?>" name="month">
						<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
						<?php if ($decide_template_ == "1"): ?>
							<input type="hidden" value="<?= $totalworkhh_top ?>" name="jobhh_top">
							<input type="hidden" value="<?= $totalworkmm_top ?>" name="jobmm_top">
							<input type="hidden" value="<?= $cnprejob_top ?>" name="jobdays_top">
							<input type="hidden" value="<?= $janworkhh_top = '0' ?>" name="janhh_top">
							<input type="hidden" value="<?= $janworkmm_top = '0' ?>" name="janmm_top">
							<input type="hidden" value="<?= $cnactjob_top ?>" name="workdays_top">
							<input type="hidden" value="<?= $holydayswork_top ?>" name="holydays_top">
							<input type="hidden" value="<?= $offdayswork_top ?>" name="offdays_top">
							<input type="hidden" value="<?= $closedayswork_top ?>" name="closedays_top">
							<input type="hidden" value="<?= $delaydayswork_top = '0' ?>" name="delaydays_top">
							<input type="hidden" value="<?= $earlydayswork_top = '0' ?>" name="earlydays_top">
						<?php elseif ($decide_template_ == "2"): ?>
							<input type="hidden" value="<?= $totaldayhh_top ?>" name="jobhh_top">
							<input type="hidden" value="<?= $totaldaymm_top ?>" name="jobmm_top">
							<input type="hidden" value="<?= $cnprejob_top ?>" name="jobdays_top">
							<input type="hidden"
								value="<?= $janworkhh_top = isset($totalJanHours) ? $totalJanHours : '0'; ?>"
								name="janhh_top">
							<input type="hidden"
								value="<?= $janworkmm_top = isset($totalJanMinutes) ? $totalJanMinutes : '0'; ?>"
								name="janmm_top">
							<input type="hidden" value="<?= $cnactjob_top ?>" name="workdays_top">
							<input type="hidden" value="<?= $holydayswork_top ?>" name="holydays_top">
							<input type="hidden" value="<?= $offdayswork_top ?>" name="offdays_top">
							<input type="hidden" value="<?= $closedayswork_top ?>" name="closedays_top">
							<input type="hidden" value="<?= $delaydayswork_top ?>" name="delaydays_top">
							<input type="hidden" value="<?= $earlydayswork_top ?>" name="earlydays_top">
						<?php endif; ?>
						<button type="submit" name="MonthSaveKintai" class="btn btn-primary" id="btnSaveMonth"
							role="button" value="月合計登録">月合計登録</button>
					</td>
					<?php
					if (!empty($workmonth_list)) {
						foreach ($workmonth_list as $key) {
							?>
							<?php if ($decide_template_ == "1"): ?>
								<input type="hidden"
									value="<?= $janworkhh_bottom_pdf = (isset($key['janhour']) && $key['janhour'] !== '') ? $key['janhour'] : $janworkhh_top; ?>"
									name="janhh_bottom">

								<input type="hidden" name="janmm_bottom"
									value="<?= $janworkmm_bottom_pdf = (isset($key['janminute']) && $key['janminute'] !== '') ? $key['janminute'] : $janworkmm_top; ?>">

								<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom"
										id="jobhh_bottom" maxlength="3"
										value="<?= $totalworkhh_bottom_pdf = (isset($key['jobhour']) && $key['jobhour'] !== '') ? $key['jobhour'] : $totaldayhh_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom"
										id="jobmm_bottom" maxlength="3"
										value="<?= $totalworkmm_bottom_pdf = (isset($key['jobminute']) && $key['jobminute'] !== '') ? $key['jobminute'] : $totaldaymm_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom"
										id="jobdays_bottom" maxlength="2"
										value="<?= $cnprejob_bottom_pdf = (isset($key['jobdays']) && $key['jobdays'] !== '') ? $key['jobdays'] : $cnprejob_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom"
										id="workdays_bottom" maxlength="2"
										value="<?= $cnactjob_bottom_pdf = (isset($key['workdays']) && $key['workdays'] !== '') ? $key['workdays'] : $cnactjob_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom"
										id="holydays_bottom" maxlength="2"
										value="<?= $holydayswork_bottom_pdf = (isset($key['holydays']) && $key['holydays'] !== '') ? $key['holydays'] : $holydayswork_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom"
										id="offdays_bottom" maxlength="2"
										value="<?= $offdayswork_bottom_pdf = (isset($key['offdays']) && $key['offdays'] !== '') ? $key['offdays'] : $offdayswork_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="closedays_bottom"
										id="closedays_bottom" maxlength="2"
										value="<?= $closedayswork_bottom_pdf = (isset($key['closedays']) && $key['closedays'] !== '') ? $key['closedays'] : $closedayswork_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom"
										id="delaydays_bottom" maxlength="2"
										value="<?= $delaydayswork_bottom_pdf = (isset($key['delaydays']) && $key['delaydays'] !== '') ? $key['delaydays'] : $delaydayswork_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom"
										id="earlydays_bottom" maxlength="2"
										value="<?= $earlydayswork_bottom_pdf = (isset($key['earlydays']) && $key['earlydays'] !== '') ? $key['earlydays'] : $earlydayswork_top; ?>">
								</td>
							<?php elseif ($decide_template_ == "2"): ?>
								<input type="hidden"
									value="<?= $janworkhh_bottom_pdf = (isset($key['janhour']) && $key['janhour'] !== '') ? $key['janhour'] : $janworkhh_top; ?>"
									name="janhh_bottom">
								<input type="hidden" name="janmm_bottom"
									value="<?= $janworkmm_bottom_pdf = (isset($key['janminute']) && $key['janminute'] !== '') ? $key['janminute'] : $janworkmm_top; ?>">

								<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom"
										id="jobhh_bottom" maxlength="3"
										value="<?= $totalworkhh_bottom_pdf = (isset($key['jobhour']) && $key['jobhour'] !== '') ? $key['jobhour'] : $totaldayhh_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom"
										id="jobmm_bottom" maxlength="2"
										value="<?= $totalworkmm_bottom_pdf = (isset($key['jobminute']) && $key['jobminute'] !== '') ? $key['jobminute'] : $totaldaymm_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom"
										id="jobdays_bottom" maxlength="2"
										value="<?= $cnprejob_bottom_pdf = (isset($key['jobdays']) && $key['jobdays'] !== '') ? $key['jobdays'] : $cnprejob_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom"
										id="workdays_bottom" maxlength="2"
										value="<?= $cnactjob_bottom_pdf = (isset($key['workdays']) && $key['workdays'] !== '') ? $key['workdays'] : $cnactjob_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom"
										id="holydays_bottom" maxlength="2"
										value="<?= $holydayswork_bottom_pdf = (isset($key['holydays']) && $key['holydays'] !== '') ? $key['holydays'] : $holydayswork_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom"
										id="offdays_bottom" maxlength="2"
										value="<?= $offdayswork_bottom_pdf = (isset($key['offdays']) && $key['offdays'] !== '') ? $key['offdays'] : $offdayswork_top; ?>">
								</td>

								<td><input type="text" class="form-control" style="text-align: center" name="closedays_bottom"
										id="closedays_bottom" maxlength="2"
										value="<?= $closedayswork_bottom_pdf = (isset($key['closedays']) && $key['closedays'] !== '') ? $key['closedays'] : $closedayswork_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom"
										id="delaydays_bottom" maxlength="2"
										value="<?= $delaydayswork_bottom_pdf = (isset($key['delaydays']) && $key['delaydays'] !== '') ? $key['delaydays'] : $delaydayswork_top; ?>">
								</td>
								<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom"
										id="earlydays_bottom" maxlength="2"
										value="<?= $earlydayswork_bottom_pdf = (isset($key['earlydays']) && $key['earlydays'] !== '') ? $key['earlydays'] : $earlydayswork_top; ?>">
								</td>
							<?php endif; ?>
							<?php
						}
					} else {
						?>
						<?php if ($decide_template_ == "1"): ?>
							<input type="hidden"
								value="<?= $janworkhh_bottom_pdf = isset($janworkhh_top) ? $janworkhh_top : '0'; ?>"
								name="janhh_bottom">
							<input type="hidden"
								value="<?= $janworkmm_bottom_pdf = isset($janworkmm_top) ? $janworkmm_top : '0'; ?>"
								name="janmm_bottom">
							<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom"
									id="jobhh_bottom" maxlength="3"
									value="<?= $totalworkhh_bottom_pdf = isset($totalworkhh_top) ? $totalworkhh_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom"
									id="jobmm_bottom" maxlength="2"
									value="<?= $totalworkmm_bottom_pdf = isset($totalworkmm_top) ? $totalworkmm_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom"
									id="jobdays_bottom" maxlength="2"
									value="<?= $cnprejob_bottom_pdf = isset($cnprejob_top) ? $cnprejob_top : '0'; ?>"></td>
							<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom"
									id="workdays_bottom" maxlength="2"
									value="<?= $cnactjob_bottom_pdf = isset($cnactjob_top) ? $cnactjob_top : '0'; ?>"></td>
							<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom"
									id="holydays_bottom" maxlength="2"
									value="<?= $holydayswork_bottom_pdf = isset($holydayswork_top) ? $holydayswork_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom"
									id="offdays_bottom" maxlength="2"
									value="<?= $offdayswork_bottom_pdf = (isset($key['offdays']) && $key['offdays'] !== '') ? $key['offdays'] : $offdayswork_top; ?>">
							</td>

							<td><input type="text" class="form-control" style="text-align: center" name="closedays_bottom"
									id="closedays_bottom" maxlength="2"
									value="<?= $closedayswork_bottom_pdf = (isset($key['closedays']) && $key['closedays'] !== '') ? $key['closedays'] : $closedayswork_top; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom"
									id="delaydays_bottom" maxlength="2" value="0"></td>
							<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom"
									id="earlydays_bottom" maxlength="2" value="0"></td>
						<?php elseif ($decide_template_ == "2"): ?>
							<input type="hidden"
								value="<?= $janworkhh_bottom_pdf = isset($janworkhh_top) ? $janworkhh_top : '0'; ?>"
								name="janhh_bottom">
							<input type="hidden"
								value="<?= $janworkmm_bottom_pdf = isset($janworkmm_top) ? $janworkmm_top : '0'; ?>"
								name="janmm_bottom">
							<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom"
									id="jobhh_bottom" maxlength="3"
									value="<?= $totaldayhh_bottom_pdf = isset($totaldayhh_top) ? $totaldayhh_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom"
									id="jobmm_bottom" maxlength="2"
									value="<?= $totalworkhh_bottom_pdf = isset($totaldaymm_top) ? $totaldaymm_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom"
									id="jobdays_bottom" maxlength="2"
									value="<?= $cnprejob_bottom_pdf = isset($cnprejob_top) ? $cnprejob_top : '0'; ?>"></td>
							<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom"
									id="workdays_bottom" maxlength="2"
									value="<?= $cnprejob_bottom_pdf = isset($cnactjob_top) ? $cnactjob_top : '0'; ?>"></td>
							<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom"
									id="holydays_bottom" maxlength="2"
									value="<?= $holydayswork_bottom_pdf = isset($holydayswork_top) ? $holydayswork_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom"
									id="offdays_bottom" maxlength="2"
									value="<?= $offdayswork_bottom_pdf = (isset($key['offdays']) && $key['offdays'] !== '') ? $key['offdays'] : $offdayswork_top; ?>">
							</td>

							<td><input type="text" class="form-control" style="text-align: center" name="closedays_bottom"
									id="closedays_bottom" maxlength="2"
									value="<?= $closedayswork_bottom_pdf = (isset($key['closedays']) && $key['closedays'] !== '') ? $key['closedays'] : $closedayswork_top; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom"
									id="delaydays_bottom" maxlength="2"
									value="<?= $delaydayswork_bottom_pdf = isset($delaydayswork_top) ? $delaydayswork_top : '0'; ?>">
							</td>
							<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom"
									id="earlydays_bottom" maxlength="2"
									value="<?= $earlydayswork_bottom_pdf = isset($earlydayswork_top) ? $earlydayswork_top : '0'; ?>">
							</td>
						<?php endif; ?>
						<?php
					}
					?>
				</form>
			</tr>
		</tbody>


		<!-- Auto Error Message -->
		<?php
		if (!empty($workmonth_list)) {
			foreach ($workmonth_list as $key) {
				$totalworkhh_top = strval($totalworkhh_top);
				$totalworkmm_top = strval($totalworkmm_top);
				$totaldayhh_top = strval($totaldayhh_top);
				$totaldaymm_top = strval($totaldaymm_top);
				$cnprejob_top = strval($cnprejob_top);
				$cnactjob_top = strval($cnactjob_top);
				$holydayswork_top = strval($holydayswork_top);
				$offdayswork_top = strval($offdayswork_top);
				$closedayswork_top = strval($closedayswork_top);
				$delaydayswork_top = strval($delaydayswork_top);
				$earlydayswork_top = strval($earlydayswork_top);

				if ($decide_template_ == "1") {
					if (
						$key['jobhour2'] !== $totalworkhh_top || $key['jobminute2'] !== $totalworkmm_top
						|| $key['jobdays2'] !== $cnprejob_top || $key['workdays2'] !== $cnactjob_top
						|| $key['holydays2'] !== $holydayswork_top || $key['offdays2'] !== $offdayswork_top || $key['closedays2'] !== $closedayswork_top
						|| $key['delaydays2'] !== $delaydayswork_top || $key['earlydays2'] !== $earlydayswork_top
					) {
						echo '<p style="color: red;" id="kintaiWorkMonth-registing">' . $kintai_click_month . '</p>';
					}

					if ($cnprejob_top === '0') {
						echo '<p style="color: red;">' . $kintai_reg_workmonth . '</p>';
					}

				} elseif ($decide_template_ == "2") {

					if (
						$key['jobhour2'] !== $totaldayhh_top || $key['jobminute2'] !== $totaldaymm_top
						|| $key['jobdays2'] !== $cnprejob_top || $key['workdays2'] !== $cnactjob_top
						|| $key['holydays2'] !== $holydayswork_top || $key['offdays2'] !== $offdayswork_top || $key['closedays2'] !== $closedayswork_top
						|| $key['delaydays2'] !== $delaydayswork_top || $key['earlydays2'] !== $earlydayswork_top
					) {
						echo '<p style="color: red;" id="kintaiWorkMonth-registing">' . $kintai_click_month . '</p>';
					}

					if ($cnprejob_top === '0') {
						echo '<p style="color: red;">' . $kintai_reg_workmonth . '</p>';
					}

				}
			}
		} else {
			echo '<p style="color: red;" id="kintaiWorkMonth-registing">' . $kintai_click_month . '</p>';

		}
		?>

	</table>
</div>


<!-- PDF product -->
<form id="autopdf" action="../pdfdownload/generatekinmuhyopdf.php" method="post" target="_blank">
	<input type="hidden" name="data" value="<?php echo htmlspecialchars(json_encode($datas)); ?>">
	<input type="hidden" name="holidaysdata" value="<?php echo htmlspecialchars(json_encode($holidayDates_)); ?>">
	<input type="hidden" name="signstamp_admin"
		value="<?php echo htmlspecialchars(json_encode($signstamp_admin[0]['signstamp'])); ?>">
	<input type="hidden" name="signstamp_kanri"
		value="<?php echo htmlspecialchars(json_encode($signstamp_kanri[0]['signstamp'])); ?>">

	<?php
	$signstamp_value = !isset($signstamp_teishutsu[0]['signstamp']) ? '' : htmlspecialchars(json_encode($signstamp_teishutsu[0]['signstamp']));
	?>
	<input type="hidden" name="signstamp_user" value="<?php echo $signstamp_value; ?>">
	<input type="hidden" name="name" value="<?php echo htmlspecialchars(json_encode($employee_name)); ?>">
	<input type="hidden" name="dept" value="<?php echo htmlspecialchars(json_encode($currentDeptText)); ?>">
	<input type="hidden" name="date_show" value="<?php echo htmlspecialchars(json_encode($date_show)); ?>">
	<input type="hidden" name="companyName" value="<?php echo htmlspecialchars(json_encode($companyName_)); ?>">
	<input type="hidden" name="template" value="<?php echo htmlspecialchars(json_encode($decide_template_)); ?>">
	<input type="hidden" name="submission_status"
		value="<?php echo htmlspecialchars(json_encode($currentSubmission_status)); ?>">
	<!-- top   earlydayswork_top -->
	<input type="hidden" name="totalworkhh_top" value="<?php echo htmlspecialchars(json_encode($totalworkhh_top)); ?>">
	<input type="hidden" name="totalworkmm_top" value="<?php echo htmlspecialchars(json_encode($totalworkmm_top)); ?>">
	<input type="hidden" name="cnprejob_top" value="<?php echo htmlspecialchars(json_encode($cnprejob_top)); ?>">
	<input type="hidden" name="cnactjob_top" value="<?php echo htmlspecialchars(json_encode($cnactjob_top)); ?>">
	<input type="hidden" name="totaldayhh_top" value="<?php echo htmlspecialchars(json_encode($totaldayhh_top)); ?>">
	<input type="hidden" name="totaldaymm_top" value="<?php echo htmlspecialchars(json_encode($totaldaymm_top)); ?>">
	<input type="hidden" name="holydayswork_top"
		value="<?php echo htmlspecialchars(json_encode($holydayswork_top)); ?>">
	<input type="hidden" name="offdayswork_top" value="<?php echo htmlspecialchars(json_encode($offdayswork_top)); ?>">
	<input type="hidden" name="closedayswork_top"
		value="<?php echo htmlspecialchars(json_encode($closedayswork_top)); ?>">
	<input type="hidden" name="delaydayswork_top"
		value="<?php echo htmlspecialchars(json_encode($delaydayswork_top)); ?>">
	<input type="hidden" name="earlydayswork_top"
		value="<?php echo htmlspecialchars(json_encode($earlydayswork_top)); ?>">


	<!-- bottom -->
	<input type="hidden" name="totalworkhh_bottom"
		value="<?php echo htmlspecialchars(json_encode($totalworkhh_bottom_pdf)); ?>">
	<input type="hidden" name="totalworkmm_bottom"
		value="<?php echo htmlspecialchars(json_encode($totalworkmm_bottom_pdf)); ?>">
	<input type="hidden" name="cnprejob_bottom"
		value="<?php echo htmlspecialchars(json_encode($cnprejob_bottom_pdf)); ?>">
	<input type="hidden" name="cnactjob_bottom"
		value="<?php echo htmlspecialchars(json_encode($cnactjob_bottom_pdf)); ?>">
	<input type="hidden" name="totaldayhh_bottom"
		value="<?php echo htmlspecialchars(json_encode($totaldayhh_bottom_pdf)); ?>">
	<input type="hidden" name="totaldaymm_bottom"
		value="<?php echo htmlspecialchars(json_encode($totaldaymm_bottom_pdf)); ?>">
	<input type="hidden" name="holydayswork_bottom"
		value="<?php echo htmlspecialchars(json_encode($holydayswork_bottom_pdf)); ?>">
	<input type="hidden" name="offdayswork_bottom"
		value="<?php echo htmlspecialchars(json_encode($offdayswork_bottom_pdf)); ?>">
	<input type="hidden" name="closedayswork_bottom"
		value="<?php echo htmlspecialchars(json_encode($closedayswork_bottom_pdf)); ?>">
	<input type="hidden" name="delaydayswork_bottom"
		value="<?php echo htmlspecialchars(json_encode($delaydayswork_bottom_pdf)); ?>">
	<input type="hidden" name="earlydayswork_bottom"
		value="<?php echo htmlspecialchars(json_encode($earlydayswork_bottom_pdf)); ?>">
	<input type="hidden" name="workmonth_list" value="<?php echo htmlspecialchars(json_encode($workmonth_list)); ?>">
</form>


<!-- Modal 勤務タイプ選択 -->
<div class="row">
	<div class="modal" id="modal3" tabindex="-3" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						勤務タイプ選択
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<label for="genbaname_rmodal">勤務時間</label>
								<select class="form-control" id="genba_selection_rmodal" name="genba_selection_rmodal">
									<option value="" selected="">
										<?php echo $select_message ?>
									</option>
									<?php foreach ($genba_list as $value) { ?>
										<option
											value="<?= $value['genid'] . ',' . $value['workstrtime'] . ',' . $value['workendtime'] . ',' . $value['offtime1'] . ',' . $value['offtime2'] ?>"
											<?php if ($value['genid'] == $_SESSION['auth_genid']) {
												echo ' selected="selected"';
											} ?>>
											<?= $value['genbaname'] . ':' . $value['workstrtime'] . '-' . $value['workendtime'] . '  || (昼休)' . $value['offtime1'] . '  || (夜休)' . $value['offtime2'] ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<br>
						<input type="hidden" id="selectedGenid" name="selectedGenid" value="">
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="changeGenid" class="btn btn-primary" id="btnchgGenid"
								role="button" value="選択">
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





<!-- Modal 自動入力 -->
<div class="row">
	<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						自動入力設定
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-9">
								<label for="genbaname_rmodal">勤務時間</label>
								<select class="form-control" id="genba_selection_rmodal" name="genba_selection_rmodal">
									<option value="" selected="">
										<?php echo $select_message ?>
									</option>
									<?php foreach ($genba_list as $value) { ?>
										<option
											value="<?= $value['genid'] . ',' . $value['workstrtime'] . ',' . $value['workendtime'] . ',' . $value['offtime1'] . ',' . $value['offtime2'] ?>"
											<?php if ($value['genid'] == $_SESSION['auth_genid']) {
												echo ' selected="selected"';
											} ?>>
											<?= $value['genbaname'] . ':' . $value['workstrtime'] . '-' . $value['workendtime'] . '  || (昼休)' . $value['offtime1'] . '  || (夜休)' . $value['offtime2'] ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-md-3">
								<label for="use_weekofday"><strong>曜日</strong></label>
								<div class="custom-control custom-checkbox">
									<input type="hidden" value="<?= $year ?>" name="year">
									<input type="hidden" value="<?= $month ?>" name="month">
									<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
									<input type="checkbox" name="weekdayCheckbox" id="weekdayCheckbox" value="1">平日
									<input type="checkbox" name="weekendCheckbox" id="weekendCheckbox" value="2">土日
								</div>
							</div>
						</div>
						<br>
						<!--2023/1340-003 change start -->
						<div class="row">
							<div class="col-md-6">
								<label for="workcontent_rmodal">業務内容 <p id="workcontent_rmodal-error"
										style="color: red;"></p></label>
								<input type="text" class="form-control" name="workcontent_rmodal"
									id="workcontent_rmodal"
									placeholder="業務内容(<?php echo $MAX_INPUT_LENGTH_COMMENT ?>桁まで)"
									style="text-align: left" maxlength=<?php echo $MAX_INPUT_LENGTH_COMMENT ?>>
							</div>
							<div class="col-md-6">
								<label for="bigo_rmodal">備考 <p id="bigo_rmodal-error" style="color: red;"></p></label>
								<input type="text" class="form-control" name="bigo_rmodal" id="bigo_rmodal"
									placeholder="備考(<?php echo $MAX_INPUT_LENGTH_BIGO ?>桁まで)" style="text-align: left"
									maxlength=<?php echo $MAX_INPUT_LENGTH_BIGO ?>>
							</div>
						</div>
						<!--2023/1340-003 change end -->
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="AutoUpdateKintai" class="btn btn-primary" id="btnAuto"
								role="button" value="入力確定">
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

<!-- Modal 勤務時間登録編集  -->
<div class="row">
	<div class="modal" id="modal2" tabindex="-2" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						勤務時間<span id="selkindatetext"></span>(<span id="selkindate"></span>)
						<button class="close" data-dismiss="modal" id="modal_close-btn-top">&times;</button>
					</div>
					<div class="modal-body" style="text-align: left">
						<div class="row">
							<div class="col-xs-4">
								<label for="workymd">日付</label>
								<input type="text" class="form-control" id="workymd" name="workymd"
									style="text-align: center" readonly>
								<input type="hidden" id="uid" name="uid">
								<input type="hidden" id="genid" name="genid">
								<input type="hidden" id="date_show" name="date_show">
								<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
							</div>
							<div class="col-xs-2 holder">
								<label>業務開始</label>
								<select id="jobstarthh" name="jobstarthh" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>時</option>
									<?php
									foreach (ConstArray::$search_hour as $key => $value) {
										?>
										<option size="10" value="<?= $key ?>" <?php if ($value == $_POST['jobstarthh']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVjobstarthh" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="jobstartmm" name="jobstartmm" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>分</option>
									<?php
									foreach (ConstArray::$search_minute as $key => $value) {
										?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['jobstartmm']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVjobstartmm" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
							<div class="col-xs-2 holder">
								<label>業務終了</label>
								<select id="jobendhh" name="jobendhh" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>時</option>
									<?php
									foreach (ConstArray::$search_hour as $key => $value) {
										?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['jobendhh']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVjobendhh" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="jobendmm" name="jobendmm" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>分</option>
									<?php
									foreach (ConstArray::$search_minute as $key => $value) {
										?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['jobendmm']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVjobendmm" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
						</div>
						<br>
						<br>
						<?php if ($decide_template_ == "2"): ?>
							<div class="row">
								<div class="col-xs-4"></div>
								<div class="col-xs-2 holder">
									<label>出社時刻</label>
									<select id="daystarthh" name="daystarthh" class="form-control" size="1"
										onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
										<option value="" selected disabled>時</option>
										<?php
										foreach (ConstArray::$search_hour as $key => $value) {
											?>
											<option value="<?= $key ?>" <?php if ($value == $_POST['daystarthh']) {
												  echo ' selected="selected"';
											  } ?>>
												<?= $value ?>
											</option>
											<?php
										}
										?>
									</select>
									<input type="text" id="IVdaystarthh" class="form-control text_size" placeholder="入力(xx)"
										value="">
								</div>
								<div class="col-xs-2 holder">
									<label>&nbsp;</label>
									<select id="daystartmm" name="daystartmm" class="form-control" size="1"
										onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
										<option value="" selected disabled>分</option>
										<?php
										foreach (ConstArray::$search_minute as $key => $value) {
											?>
											<option value="<?= $key ?>" <?php if ($value == $_POST['daystartmm']) {
												  echo ' selected="selected"';
											  } ?>>
												<?= $value ?>
											</option>
											<?php
										}
										?>
									</select>
									<input type="text" id="IVdaystartmm" class="form-control text_size"
										placeholder="入力(xx)">
								</div>
								<div class="col-xs-2 holder">
									<label>退社時刻</label>
									<select id="dayendhh" name="dayendhh" class="form-control" size="1"
										onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
										<option value="" selected disabled>時</option>
										<?php
										foreach (ConstArray::$search_hour as $key => $value) {
											?>
											<option value="<?= $key ?>" <?php if ($value == $_POST['dayendhh']) {
												  echo ' selected="selected"';
											  } ?>>
												<?= $value ?>
											</option>
											<?php
										}
										?>
									</select>
									<input type="text" id="IVdayendhh" class="form-control text_size" placeholder="入力(xx)"
										value="">
								</div>
								<div class="col-xs-2 holder">
									<label>&nbsp;</label>
									<select id="dayendmm" name="dayendmm" class="form-control" size="1"
										onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
										<option value="" selected disabled>分</option>
										<?php
										foreach (ConstArray::$search_minute as $key => $value) {
											?>
											<option value="<?= $key ?>" <?php if ($value == $_POST['dayendmm']) {
												  echo ' selected="selected"';
											  } ?>>
												<?= $value ?>
											</option>
											<?php
										}
										?>
									</select>
									<input type="text" id="IVdayendmm" class="form-control text_size" placeholder="入力(xx)"
										value="">
								</div>
							</div>
							<br>
							<br>
							<br>
							<br>
						<?php endif; ?>
						<div class="row">
							<!-- 2023/10-16/ add start -->
							<div class="col-xs-4 holder">
								<label>休暇理由</label>
								<select id="holy_decide" name="holy_decide" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;'
									onchange='this.size=1; this.blur();handleSelectDayStatusChange(this)'>
									<?php
									foreach ($HOLY_DECIDE as $key => $value) {
										?>
										<option size="10" value="<?= $key ?>" <?php if ($value == $_POST['holy_decide']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
							</div>
							<!-- <div class="col-xs-4"></div> -->
							<!-- 2023/10-16/ add end -->
							<div class="col-xs-2 holder">
								<label>休憩時間</label>
								<select id="offtimehh" name="offtimehh" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>時</option>
									<?php
									foreach (ConstArray::$search_hour as $key => $value) {
										?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['offtimehh']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVofftimehh" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="offtimemm" name="offtimemm" class="form-control" size="1"
									onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
									<option value="" selected disabled>分</option>
									<?php
									foreach (ConstArray::$search_minute as $key => $value) {
										?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['offtimemm']) {
											  echo ' selected="selected"';
										  } ?>>
											<?= $value ?>
										</option>
										<?php
									}
									?>
								</select>
								<input type="text" id="IVofftimemm" class="form-control text_size" placeholder="入力(xx)"
									value="">
							</div>
							<div class="col-xs-2">
								<label for="workhh">就業時間</label>
								<input type="text" class="form-control" name="workhh" id="workhh" placeholder="0"
									required="required" style="text-align: center" readonly>
							</div>
							<div class="col-xs-2">
								<label for="workmm">&nbsp;</label>
								<input type="text" class="form-control" name="workmm" id="workmm" placeholder="0"
									required="required" style="text-align: center" readonly>
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="comment">業務内容
									<!--2023/1340-003 add start -->
									<p id="comment-error" style="color: red;"></p>
									<!--2023/1340-003 add end -->
								</label>

								<input type="text" class="form-control" name="comment" id="comment"
									placeholder="業務内容(<?php echo $MAX_INPUT_LENGTH_COMMENT ?>桁まで)"
									style="text-align: left" maxlength=<?php echo $MAX_INPUT_LENGTH_COMMENT ?>>
							</div>
							<div class="col-xs-6">
								<label for="bigo">備考
									<!--2023/1340-003 add start -->
									<p id="bigo-error" style="color: red;"></p>
									<!--2023/1340-003 add end -->
								</label>
								<input type="text" class="form-control" name="bigo" id="bigo"
									placeholder="備考(<?php echo $MAX_INPUT_LENGTH_BIGO ?>桁まで)" style="text-align: left"
									maxlength=<?php echo $MAX_INPUT_LENGTH_BIGO ?>>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<input type="submit" name="SaveUpdateKintai" class="btn btn-primary" id="btnReg" role="button">
						<input type="submit" name="DeleteKintai" class="btn btn-warning" id="btnDel" role="button"
							value="削除">
						<button type="button" class="btn btn-default " data-dismiss="modal" id="modalClose">閉じる</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>

	//kintaiWorkMonth-
	//2023/11/10 submission-status  add start 
	SubmisstionStatusNotice = "";

	//2023/11/10 submission-status  add end

	// Submit for select
	jQuery(function () {
		jQuery('.seldate').change(function () {
			if (this.form !== null) {
				this.form.submit();

			}
		});
		SetFormViewBySubmissionStatusHandler();
		SetHollyDaysTextHandler();
	});

	function SetHollyDaysTextHandler() {
		var elements = document.querySelectorAll('tr[name="workDayOfMonth"]');
		const currentMonthHolydays = <?php echo json_encode($holidayDates_) ?>;
		let currentSelectedYear = $('#selyy').val();
		const currentMonthDatas = <?php echo json_encode($datas) ?>;
		for (var i = 0; i < elements.length; i++) {
			var monthFromElement = elements[i].textContent.trim();
			var cleanMonth = monthFromElement.replace(/\([^)]*\)/g, '');
			var dateToCompare = (currentSelectedYear + '/' + cleanMonth).substring(0, 10);

			for (var j = 0; j < currentMonthDatas.length; j++) {
				var currentDate = currentMonthDatas[j];
				if (currentDate.workymd === dateToCompare && currentDate.bigo === undefined && currentDate.isHoliday == true) {
					var lastTdElement = elements[i].querySelector('td:last-child'); 
					lastTdElement.textContent = currentMonthHolydays[dateToCompare]; 
				}
			}
		}

	}


	function SetFormViewBySubmissionStatusHandler() {
		// set Color
		SetColorToSubmissionStatus();
		// Set Turn On Off Button
		setFormInputableWithSubmissionStatus();
	}

	function SetColorToSubmissionStatus() {
		var submissionStatusText = $('#submission-status').text().trim();
		if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[0] ?>' ) {
			$('#submission-status').removeClass();
		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[1] ?>') {
			$('#submission-status').removeClass();
			$('#submission-status').addClass('submission-status_1');
		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[2] ?>') {
			$('#submission-status').removeClass();
			$('#submission-status').addClass('submission-status_2');
		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[3] ?>') {
			$('#submission-status').removeClass();
			$('#submission-status').addClass('submission-status_3');
		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[11] ?>') {
			$('#submission-status').removeClass();
		}
	}
	

	function setFormInputableWithSubmissionStatus() {
		var submissionStatusText = $('#submission-status').text().trim();
		//  buttons -> Default On
		var adminButtons = $("#WorkmonthSekininShonin, #WorkmonthShonin, #WorkmonthModoshi ");
		var userButtons = $("#AutoRegisterBtn , #DeleteAllBtn, #btnSaveMonth , #WorkmonthKakutei ");
		var modalButons = $("#btnReg , #btnDel ");
		adminButtons.prop("disabled", false);
		userButtons.prop("disabled", false);
		modalButons.prop("disabled", false);
		SubmisstionStatusNotice = "<?php echo $submised_not_change ?>"

		// when match -> off element 
		if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[0] ?>') {
			adminButtons.prop("disabled", true);
			SubmisstionStatusNotice = ""

		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[1] ?>') {
			userButtons.prop("disabled", true);
			modalButons.prop("disabled", true);

		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[2] ?>') {
			userButtons.prop("disabled", true);
			modalButons.prop("disabled", true);

		} else if (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[3] ?>') {
			userButtons.prop("disabled", true);
			modalButons.prop("disabled", true);
		} else if  (submissionStatusText === '<?php echo $SUBMISSTION_STATUS[11] ?>') {
			adminButtons.prop("disabled", true);
			SubmisstionStatusNotice = ""

		}
	}


	// add check before submiss
	function checkSubmisBefore() {
		if ($("#kintaiWorkMonth-registing").length) {
			$("#kintaiWorkMonth-registing")[0].scrollIntoView();
			alert("<?php echo $is_not_registed_WorkMonth ?>");
			return false;
		} else {
			if (confirm("<?php echo $kakutei_ninsho_message ?>")) {
				return true;
			} else {
				return false;
			}
		}
	}

	//2023/11/10 submission-status  add end 	

	// Funtion for click day of week
	$(document).on('click', '.showModal', function () {
		$('#modal2').modal('toggle');
		var ArrayData = $(this).text();
		var SeparateArr = ArrayData.split('/');
		var Date_ = SeparateArr[1].substr(0, 2);

		var SeparateArr2 = ArrayData.split(',');
		var CheckData = SeparateArr2[1];
		if (CheckData.trim().length === 0) {
			$('#btnReg').val("登録");
			$('#selkindatetext').text("登録");
		} else {
			$('#btnReg').val("編集");
			$('#selkindatetext').text("編集")
		}

		var uid = $("input[name=uid]:hidden");
		uid.val("<?php echo $_SESSION['auth_uid'] ?>");
		var uid = uid.val();
		var genid = $("input[name=genid]:hidden");
		genid.val("<?php echo $_SESSION['auth_genid'] ?>");
		var genid = genid.val();
		var date_show = $("input[name=date_show]:hidden");
		date_show.val("<?php echo $date_show ?>" + Date_);
		var date_show = date_show.val();
		$("#workymd").text($('[name="workymd"]').val(date_show));
		//2023/11/10 submission-status  chg start 
		//$("#selkindate").text(date_show + SubmisstionStatusNotice);
		var fullText = date_show + " " + SubmisstionStatusNotice;
		var redText = "<span style='color: red; font-style: italic;'>" + SubmisstionStatusNotice + "</span>";
		$("#selkindate").html(fullText.replace(SubmisstionStatusNotice, redText));
		//2023/11/10 submission-status  chg end 
		<?php
		foreach ($datas as $key) {
			?>
			if ('<?php echo $key['workymd'] ?>' === date_show) {
				// combobox
				$("#jobstarthh").val("<?php echo $key['jobstarthh'] ?>");
				$("#jobstartmm").val("<?php echo $key['jobstartmm'] ?>");
				$("#jobendhh").val("<?php echo $key['jobendhh'] ?>");
				$("#jobendmm").val("<?php echo $key['jobendmm'] ?>");
				$("#offtimehh").val("<?php echo $key['offtimehh'] ?>");
				$("#offtimemm").val("<?php echo $key['offtimemm'] ?>");
				$("#workhh").val("<?php echo $key['workhh'] ?>");
				$("#workmm").val("<?php echo $key['workmm'] ?>");
				$("#comment").text($('[name="comment"]').val("<?php echo $key['comment'] ?>"));
				$("#bigo").text($('[name="bigo"]').val("<?php echo $key['bigo'] ?>"));
				$("#daystarthh").val("<?php echo $key['daystarthh'] ?>");
				$("#daystartmm").val("<?php echo $key['daystartmm'] ?>");
				$("#dayendhh").val("<?php echo $key['dayendhh'] ?>");
				$("#dayendmm").val("<?php echo $key['dayendmm'] ?>");

				// 023-10-03/1340-001 add start
				$("#holy_decide").val("<?php echo $key['holy_decide'] ?>");
				var holyDecideValue = "<?php echo $key['holy_decide'] ?>";
				if (holyDecideValue === '' || holyDecideValue === null) {
					$("#holy_decide").val(<?= json_encode(array_keys($HOLY_DECIDE)[0]) ?>);
				} else {
					$("#holy_decide").val(holyDecideValue);
				}


				$("#IVjobstarthh").val("<?php echo $key['jobstarthh'] ?>");
				$("#IVjobstartmm").val("<?php echo $key['jobstartmm'] ?>");
				$("#IVjobendhh").val("<?php echo $key['jobendhh'] ?>");
				$("#IVjobendmm").val("<?php echo $key['jobendmm'] ?>");
				$("#IVofftimehh").val("<?php echo $key['offtimehh'] ?>");
				$("#IVofftimemm").val("<?php echo $key['offtimemm'] ?>");
				$("#workhh").val("<?php echo $key['workhh'] ?>");
				$("#workmm").val("<?php echo $key['workmm'] ?>");
				$("#comment").text($('[name="comment"]').val("<?php echo $key['comment'] ?>"));
				$("#bigo").text($('[name="bigo"]').val("<?php echo $key['bigo'] ?>"));
				$("#IVdaystarthh").val("<?php echo $key['daystarthh'] ?>");
				$("#IVdaystartmm").val("<?php echo $key['daystartmm'] ?>");
				$("#IVdayendhh").val("<?php echo $key['dayendhh'] ?>");
				$("#IVdayendmm").val("<?php echo $key['dayendmm'] ?>");
				// 2023-10-03/1340-001 add end start
				// selected when holydays
				const currentMonthHolydays = <?php echo json_encode($holidayDates_) ?>;
				for (let dateKey in currentMonthHolydays) {
					let date = currentMonthHolydays[dateKey];
					if (dateKey === date_show) {
						$("#holy_decide").val(<?php echo json_encode(array_keys($HOLY_DECIDE)[1]) ?>);
						if ("<?php echo $key['bigo'] ?>" === "") {
							$("#bigo").text($('[name="bigo"]').val(date));
						}
						break;
					}
				}


			}
			<?php
		}
		?>
	});

	// Time calculate Func
	function calculateWorkTime() {
		var offtimehh = $('#offtimehh').val() || "0";
		var offtimemm = $('#offtimemm').val() || "00";
		var offtime_ = offtimehh + ':' + offtimemm;
		var o = offtime_.split(':');

		var jobstarthh = $('#jobstarthh').val() || "0";
		var jobstartmm = $('#jobstartmm').val() || "00";
		var jobendhh = $('#jobendhh').val() || "0";
		var jobendmm = $('#jobendmm').val() || "00";

		<?php if ($decide_template_ == "1"): ?>
			var jobstartime_ = jobstarthh + ':' + jobstartmm;
			var jobendtime_ = jobendhh + ':' + jobendmm;
			var s = jobstartime_.split(':');
			var e = jobendtime_.split(':');
		<?php elseif ($decide_template_ == "2"): ?>
			var daystarthh = $('#daystarthh').val() || "0";
			var daystartmm = $('#daystartmm').val() || "00";
			var dayendhh = $('#dayendhh').val() || "0";
			var dayendmm = $('#dayendmm').val() || "00";
			var daystartime_ = daystarthh + ':' + daystartmm;
			var dayendtime_ = dayendhh + ':' + dayendmm;
			var s = daystartime_.split(':');
			var e = dayendtime_.split(':');
		<?php endif; ?>

		var min = e[1] - s[1] - o[1];
		var hour_carry = 0;
		if (min < 0) {
			min += 60;
			hour_carry += 1;
		}
		var hour = e[0] - s[0] - o[0] - hour_carry;
		var workhh = hour;
		if (workhh <= 0) {
			workhh = workhh + 24;
		}
		var workmm = min;
		$('#workhh').val(workhh);
		$('#workmm').val(workmm);
	}

	// Time calculate combobox
	$('#jobstarthh, #jobstartmm, #jobendhh, #jobendmm, #daystarthh, #daystartmm, #dayendhh, #dayendmm, #offtimehh, #offtimemm')
		.on('change', function (e) {
			calculateWorkTime();
		});

	//  input Time calculate 
	$('#IVjobstarthh, #IVjobstartmm , #IVjobendhh, #IVjobendmm, #IVdaystarthh, #IVdaystartmm, #IVdayendhh, #IVdayendmm, #IVofftimehh, #IVofftimemm')
		.on('change', function (e) {
			calculateWorkTime();
		});

	// Regex check value is  1 -> 99 
	function isValidInput(value) {
		var regex = /^(?:[0-9]|[0][0-9]|[1-9][0-9])$/;
		return regex.test(value);
	}
	// check format hours  0 -> 23 
	function formatHour(value) {

		if (isNaN(value)) {
			return "0";
		}
		value = parseInt(value);
		if (value <= 0) {
			return "0";
		} else if (value > 23) {
			return "23";
		} else if (value < 10) {
			return value;
		}
		return value.toString();
	}

	// check format minutes 0 -> 59 
	function formatMinute(value) {
		if (isNaN(value) || value <= 0) {
			return "00";
		}
		value = parseInt(value);
		if (value <= 0) {
			return "00";
		} else if (value > 59) {
			return "59";
		} else if (value < 10) {
			return "0" + value;
		}
		return value.toString();
	}

	// Check Error
	$(document).on('click', '#btnReg', function (e) {


		var jobstarthh = $("#jobstarthh option:selected").val();
		var jobstartmm = $("#jobstartmm option:selected").val();
		var jobendhh = $("#jobendhh option:selected").val();
		var jobendmm = $("#jobendmm option:selected").val();
		var offtimehh = $("#offtimehh option:selected").val();
		var offtimemm = $("#offtimemm option:selected").val();

		<?php if ($decide_template_ == "2"): ?>
			var daystarthh = $("#daystarthh option:selected").val();
			var daystartmm = $("#daystartmm option:selected").val();
			var dayendhh = $("#dayendhh option:selected").val();
			var dayendmm = $("#dayendmm option:selected").val();

			// check when input one -> all need input 
			if (daystarthh !== "" || daystartmm !== "" || dayendhh !== "" || dayendmm !== "" ||
				jobstarthh !== "" || jobstartmm !== "" || jobendhh !== "" || jobendmm !== "" ||
				offtimehh !== "" || offtimemm !== "") {
				if (daystarthh == "") {
					alert("<?php echo $kintai_start_empty; ?>");
					$("#daystarthh").focus();
					return false;
				}
				if (daystartmm == "") {
					alert("<?php echo $kintai_start_empty; ?>");
					$("#daystartmm").focus();
					return false;
				}
				if (dayendhh == "") {
					alert("<?php echo $kintai_end_empty; ?>");
					$("#dayendhh").focus();
					return false;
				}

				if (dayendmm == "") {
					alert("<?php echo $kintai_end_empty; ?>");
					$("#dayendmm").focus();
					return false;
				}
				if (jobstarthh == "") {
					alert("<?php echo $kintai_bstart_empty; ?>");
					$("#jobstarthh").focus();
					return false;
				}

				if (jobstartmm == "") {
					alert("<?php echo $kintai_bstart_empty; ?>");
					$("#jobstartmm").focus();
					return false;
				}

				if (jobendhh == "") {
					alert("<?php echo $kintai_bend_empty; ?>");
					$("#jobendhh").focus();
					return false;
				}
				if (jobendmm == "") {
					alert("<?php echo $kintai_bend_empty; ?>");
					$("#jobendmm").focus();
					return false;
				}
				if (offtimehh == "") {
					alert("<?php echo $$kintai_offtime_empty; ?>");
					$("#offtimehh").focus();
					return false;
				}
				if (offtimemm == "") {
					alert("<?php echo $$kintai_offtime_empty; ?>");
					$("#offtimemm").focus();
					return false;
				}
			}

			if (isNaN(daystarthh)) {
				alert("<?php echo $kintai_start_no; ?>");
				e.preventDefault();
				$("#daystarthh").focus();
				return false;
			}
			if (isNaN(daystartmm)) {
				alert("<?php echo $kintai_start_no; ?>");
				e.preventDefault();
				$("#daystartmm").focus();
				return false;
			}

			if (isNaN(dayendhh)) {
				alert("<?php echo $kintai_end_no; ?>");
				e.preventDefault();
				$("#dayendhh").focus();
				return false;
			}

			if (isNaN(dayendmm)) {
				alert("<?php echo $kintai_end_no; ?>");
				e.preventDefault();
				$("#dayendmm").focus();
				return false;
			}
		<?php endif; ?>
		// check when input one -> all need input 
		if (jobstarthh !== "" || jobstartmm !== "" || jobendhh !== "" || jobendmm !== "" ||
			offtimehh !== "" || offtimemm !== "") {
			if (jobstarthh == "") {
				alert("<?php echo $kintai_bstart_empty; ?>");
				$("#jobstarthh").focus();
				return false;
			}
			if (jobstartmm == "") {
				alert("<?php echo $kintai_bstart_empty; ?>");
				$("#jobstartmm").focus();
				return false;
			}

			if (jobendhh == "") {
				alert("<?php echo $kintai_bend_empty; ?>");
				$("#jobendhh").focus();
				return false;
			}
			if (jobendmm == "") {
				alert("<?php echo $kintai_bend_empty; ?>");
				$("#jobendmm").focus();
				return false;
			}
			if (offtimehh == "") {
				alert("<?php echo $$kintai_offtime_empty; ?>");
				$("#offtimehh").focus();
				return false;
			}
			if (offtimemm == "") {
				alert("<?php echo $$kintai_offtime_empty; ?>");
				$("#offtimemm").focus();
				return false;
			}
		}

		if (isNaN(jobstarthh)) {
			alert("<?php echo $kintai_bstart_no; ?>");
			e.preventDefault();
			$("#jobstarthh").focus();
			return false;
		}
		if (isNaN(jobstartmm)) {
			alert("<?php echo $kintai_bstart_no; ?>");
			e.preventDefault();
			$("#jobstartmm").focus();
			return false;
		}

		if (isNaN(jobendhh)) {
			alert("<?php echo $kintai_bend_no; ?>");
			e.preventDefault();
			$("#jobendhh").focus();
			return false;
		}

		if (isNaN(jobendmm)) {
			alert("<?php echo $kintai_bend_no; ?>");
			e.preventDefault();
			$("#jobendmm").focus();
			return false;
		}

		if (isNaN(offtimehh)) {
			alert("<?php echo $kintai_offtime_no; ?>");
			e.preventDefault();
			$("#offtimehh").focus();
			return false;
		}

		if (isNaN(offtimemm)) {
			alert("<?php echo $kintai_offtime_no; ?>");
			e.preventDefault();
			$("#offtimemm").focus();
			return false;
		}
	});


	// 勤務タイプ選択
	function kinmutypeHandle() {
		$('#modal3').modal('toggle');

	}

	// 自動入力
	function autoInputHandle() {
		$('#modal').modal('toggle');
		$("#weekdayCheckbox").prop('checked', true);
		setTimeout(hideLoadingOverlay, 1000);
		startLoading();
	}

	// Submit for 自動入力 Error Check
	$("#submit-button").click(function (event) {
		event.preventDefault();
		$("#autopdf").submit();
		setTimeout(hideLoadingOverlay, 1000);
		startLoading();
	});


	// Select input tag
	$(document).ready(function () {
		// Function to handle input fields
		function handleInput(inputId, selectId) {
			var inputValue = $(inputId);
			var selectOption = $(selectId);
			// 入力した値を正しく選択ボックスへ反映
			inputValue.on('input', function () {
				var c;
				if ($(this)[0].attributes.id.value.includes('hh')) {
					formattedValue = formatValue($(this).val(), true);
				} else if ($(this)[0].attributes.id.value.includes('mm')) {
					formattedValue = formatValue($(this).val(), false);
				}

				$(this).val(formattedValue);
				var matchingOptions = selectOption.find('option').filter(function () {
					var optionValue = $(this).val();
					return optionValue === formattedValue || optionValue === formattedValue.replace(
						/^0+/, '');
				});
				if (formattedValue === "00" && matchingOptions.length > 1) {
					$(matchingOptions[1]).prop('selected', true);
				} else if (matchingOptions.length > 0) {
					$(matchingOptions[0]).prop('selected', true);
				} else {
					selectOption.val('');
				}
			});
			// inputへ反映
			selectOption.on('change', function () {
				var selectedValue = $(this).val();
				inputValue.val(selectedValue);
			});
		}

		// Function format value to "01", "02", ..., "09"
		function formatValue(value, hoursOrMinuteFlg) {
			var result = value;
			if (value.length === 1) {
				result = "0" + value;
			} else {

				if (value.length > 2) {
					result = value.slice(-2);
				}
				value = value.replace(/^0+/, '');
			}
			if (hoursOrMinuteFlg) {
				value = formatHour(value);
			} else {
				value = formatMinute(value);
			}
			if (isNaN(value)) {
				value = "0";
			}
			return value.toString();
		}

		// Usage of the function for each input-select pair
		handleInput('#IVjobstarthh', '#jobstarthh');
		handleInput('#IVjobstartmm', '#jobstartmm');
		handleInput('#IVjobendhh', '#jobendhh');
		handleInput('#IVjobendmm', '#jobendmm');
		handleInput('#IVdaystarthh', '#daystarthh');
		handleInput('#IVdaystartmm', '#daystartmm');
		handleInput('#IVdayendhh', '#dayendhh');
		handleInput('#IVdayendmm', '#dayendmm');
		handleInput('#IVofftimehh', '#offtimehh');
		handleInput('#IVofftimemm', '#offtimemm');

		$('#genba_selection_rmodal').change(function () {
			var selectedOption = $(this).val();
			var genid = selectedOption.split(',')[0];
			$('#selectedGenid').val(genid);
		});
		setTimeout(hideLoadingOverlay, 1000);
		startLoading();


	});

	// Check Error
	$(document).on('click', '#btnAuto', function (e) {
		var genba_select_ = $("#genba_selection_rmodal").val();
		if (genba_select_ === "") {
			alert("<?php echo $select_message; ?>");
			$("#genba_selection_rmodal").focus();
			return false;
		}
	});

	// check input length call ↓
	validateLength("comment");
	validateLength("bigo");
	validateLength("workcontent_rmodal");
	validateLength("bigo_rmodal");


	function validateLength(inputId) {
		var $input = $("#" + inputId);
		var $errorMsg = $("#" + inputId + "-error");
		var maxLength = parseInt($input.attr("maxlength"));
		$input.on("input", function () {
			var text = $input.val();
			var charCount = Array.from(text).length;
			if (charCount > maxLength) {
				$errorMsg.text(maxLength + "<?php echo $kintai_check_input_err; ?>");
				$input.css("borderColor", "red");
			} else {
				$errorMsg.text("");
				$input.css("borderColor", "");
				$input.prop("disabled", false);
			}
		});
	}



	// date status change , not nomal is off day and input time is readonly
	function handleSelectDayStatusChange(elem) {
		var selectedOption = elem.options[elem.selectedIndex];
		var selectedValue = selectedOption.value;
		var selectedText = selectedOption.textContent;

		var oldValue = $("#bigo").text($('[name="bigo"]')).val();
		$("#bigo").text($('[name="bigo"]').val(''));
		if (selectedValue !== '<?= json_encode(array_keys($HOLY_DECIDE)[0]) ?>') {
			var holyDecideValue = <?= json_encode($HOLY_DECIDE) ?>;
			var selectedDecideValue = holyDecideValue[selectedValue];
			$("#bigo").text($('[name="bigo"]').val('【' + selectedDecideValue + '】'));
		}

		// set input default
		var ids = [
			'#jobstarthh',
			'#jobstartmm',
			'#jobendhh',
			'#jobendmm',
			'#daystarthh',
			'#daystartmm',
			'#dayendhh',
			'#dayendmm',
			'#offtimehh',
			'#offtimemm',
			'#IVjobstarthh',
			'#IVjobstartmm',
			'#IVjobendhh',
			'#IVjobendmm',
			'#IVdaystarthh',
			'#IVdaystartmm',
			'#IVdayendhh',
			'#IVdayendmm',
			'#IVofftimehh',
			'#IVofftimemm',
			'#workhh',
			'#workmm',
		];

		for (var i = 0; i < ids.length; i++) {
			$(ids[i]).val('');
		}
	}





</script>
<?php include('../inc/footer.php'); ?>