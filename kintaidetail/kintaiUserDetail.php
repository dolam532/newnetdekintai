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

	span.kintaiUserDetail_class {
		display: none;
	}

	.col-md-12.text-center.title {
		margin-top: 10px;
		margin-bottom: 10px;
		font-size: 22px;
		font-weight: bold;
	}

	.subtitle,
	.underline,
	.one {
		font-size: 16px;
		font-weight: bold;
		margin-top: 15px;
		margin-bottom: 15px;
	}

	.underline {
		text-decoration: underline;
	}

	.print_btn {
		display: inline-block;
		padding-top: 30px;
	}
</style>
<title><?= $_SESSION['employee_name']; ?>の勤務表</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top: -20px;">
	<div class="row">
		<div class="col-md-3 text-left" name="workYm_page_title">
			<div class="title_name text-center">
				<span id="workYm_page_title" class="text-left"><?= $_SESSION['employee_name']; ?>の勤務表</span>
			</div>
		</div>
		<form method="post">
			<input type="hidden" value="<?= $_SESSION['employee_uid'] ?>" name="uid">
			<input type="hidden" value="<?= $_SESSION['employee_name'] ?>" name="name">
			<input type="hidden" value="<?= $_SESSION['employee_dept'] ?>" name="dept">
			<input type="hidden" value="<?= $_SESSION['employee_genid'] ?>" name="genid">
			<div class="col-md-4 text-center" name="workYm_page_condition">
				<div class="title_condition">
					<label>基準日:
						<select id="selyy" name="selyy" class="seldate" style="padding:5px;" onchange="this.form.submit()">
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
						<select id="selmm" name="selmm" class="seldate" style="padding:5px;" onchange="this.form.submit()">
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
						<select id="template_table" name="template_table" class="seldate" style="padding:5px;" onchange="this.form.submit()">
							<?php
							foreach (ConstArray::$search_template as $key => $value) {
							?>
								<option value="<?= $key ?>" <?php if ($key == $_POST["template_table"]) {
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
		<div class="col-md-5 text-right">
			<div class="print_btn">
				<form method="post">
					<input type="hidden" value="<?= $year ?>" name="year">
					<input type="hidden" value="<?= $month ?>" name="month">
					<button id="delete_all" name="DeleteAll" class="btn btn-default" style="width: auto;" type="submit">すべて削除</button>
				</form>
			</div>
			<div class="print_btn">
				<a href="#" onclick="autoInputHandle()" class="btn btn-default" style="width: auto;">自動入力</a>
			</div>
			<div class="print_btn">
				<button id="submit-button" class="btn btn-default" style="width: auto;" type="button">勤務表印刷</button>
			</div>
			<div class="print_btn">
				<input type="button" class="btn btn-default" style="width: auto;" onclick="window.location.href='./kintaiUser.php'" value="戻る ">
			</div>
		</div>
	</div>

	<div class="form-group">
		<table class="table table-bordered datatable table_topViewTable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 8%;">日付</th>
					<?php
					if ($decide_template_ == "1") : ?>
						<th style="text-align: center; width: 20%;" colspan="2">業務時間</th>
					<?php else : ?>
						<th style="text-align: center; width: 14%;" colspan="2">出退社時刻</th>
						<th style="text-align: center; width: 14%;" colspan="2">業務時間</th>
					<?php endif; ?>
					<th style="text-align: center; width: 9%;">休憩時間</th>
					<th style="text-align: center; width: 9%;">就業時間</th>
					<th style="text-align: center; width: auto;">業務内容</th>
					<th style="text-align: center; width: 20%;">備考</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($decide_template_ == "1") : ?>
					<?php
					foreach ($datas as $key) {
					?>
						<tr>
							<td>
								<?php if ($key['decide_color'] == "土") : ?>
									<a href="#" style="color:blue;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php endif; ?>
							</td>
							<td><?= $key['jobstarthh'] ?>:<?= $key['jobstartmm'] ?></td>
							<td><?= $key['jobendhh'] ?>:<?= $key['jobendmm'] ?></td>
							<td><?= $key['offtimehh'] ?>:<?= $key['offtimemm'] ?></td>
							<td><?= $key['workhh'] ?>:<?= $key['workmm'] ?></td>
							<td><?= $key['comment'] ?></td>
							<td><?= $key['bigo'] ?></td>
						</tr>

					<?php
					}
					?>
				<?php else : ?>
					<?php
					foreach ($datas as $key) {
					?>
						<tr>
							<td>
								<?php if ($key['decide_color'] == "土") : ?>
									<a href="#" style="color:blue;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiUserDetail_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php endif; ?>
							</td>
							<td><?= $key['daystarthh'] ?>:<?= $key['daystartmm'] ?></td>
							<td><?= $key['dayendhh'] ?>:<?= $key['dayendmm'] ?></td>
							<td><?= $key['jobstarthh'] ?>:<?= $key['jobstartmm'] ?></td>
							<td><?= $key['jobendhh'] ?>:<?= $key['jobendmm'] ?></td>
							<td><?= $key['offtimehh'] ?>:<?= $key['offtimemm'] ?></td>
							<td><?= $key['workhh'] ?>:<?= $key['workmm'] ?></td>
							<td><?= $key['comment'] ?></td>
							<td><?= $key['bigo'] ?></td>
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
				<th style="width: 10%; padding-top: 30px;" rowspan="3">勤務状況</th>
				<th style="width: 12%;">所定勤務日数</th>
				<th style="width: 12%;">実勤務日数</th>
				<th style="width: 10%;">休暇</th>
				<th style="width: 10%;">欠勤</th>
				<th style="width: 10%;">遅刻</th>
				<th style="width: auto;">早退</th>
			</tr>
			<tr id="footer_table_show_value">
				<?php
				if (!empty($workmonth_list)) {
					foreach ($workmonth_list as $key) {
				?>
						<?php if ($decide_template_ == "1") : ?>
							<td><strong><?= $totalworkhh_top = isset($totalWorkHours) ? $totalWorkHours : (isset($key['jobhour2']) ? $key['jobhour2'] : '0'); ?></strong></td>
							<td><strong><?= $totalworkmm_top = isset($totalWorkMinutes) ? $totalWorkMinutes : (isset($key['jobminute2']) ? $key['jobminute2'] : '0'); ?></strong></td>
							<td><strong><?= $cnprejob_top = isset($countJobStartHH) ? $countJobStartHH : (isset($key['jobdays2']) ? $key['jobdays2'] : '0'); ?></strong></td>
							<td><strong><?= $cnactjob_top = isset($countJobStartHH) ? $countJobStartHH : (isset($key['workdays2']) ? $key['workdays2'] : '0'); ?></strong></td>
							<td><strong><?= $holydayswork_top = isset($key['holydays2']) ? $key['holydays2'] : '0'; ?></strong></td>
							<td><strong><?= $offdayswork_top = isset($countJobAct) ? $countJobAct : (isset($key['offdays2']) ? $key['offdays2'] : '0'); ?></strong></td>
							<td><strong><?= $delaydayswork_top = isset($countLate) ? $countLate : (isset($key['delaydays2']) ? $key['delaydays2'] : '0'); ?></strong></td>
							<td><strong><?= $earlydayswork_top = isset($countEarly) ? $countEarly : (isset($key['earlydays2']) ? $key['earlydays2'] : '0'); ?></strong></td>
						<?php elseif ($decide_template_ == "2") : ?>
							<td><strong><?= $totaldayhh_top = isset($totalDayHours) ? $totalDayHours : (isset($key['jobhour2']) ? $key['jobhour2'] : '0'); ?></strong></td>
							<td><strong><?= $totaldaymm_top = isset($totalDayMinutes) ? $totalDayMinutes : (isset($key['jobminute2']) ? $key['jobminute2'] : '0'); ?></strong></td>
							<td><strong><?= $cnprejob_top = isset($countJobStartHH) ? $countJobStartHH : (isset($key['jobdays2']) ? $key['jobdays2'] : '0'); ?></strong></td>
							<td><strong><?= $cnactjob_top = isset($countDayStartHH) ? $countDayStartHH : (isset($key['workdays2']) ? $key['workdays2'] : '0'); ?></strong></td>
							<td><strong><?= $holydayswork_top = isset($key['holydays2']) ? $key['holydays2'] : '0'; ?></strong></td>
							<td><strong><?= $offdayswork_top = isset($countJobAct) ? $countJobAct : (isset($key['offdays2']) ? $key['offdays2'] : '0'); ?></strong></td>
							<td><strong><?= $delaydayswork_top = isset($countLate) ? $countLate : (isset($key['delaydays2']) ? $key['delaydays2'] : '0'); ?></strong></td>
							<td><strong><?= $earlydayswork_top = isset($countEarly) ? $countEarly : (isset($key['earlydays2']) ? $key['earlydays2'] : '0'); ?></strong></td>
						<?php endif; ?>
					<?php
					}
				} else {
					?>
					<?php if ($decide_template_ == "1") : ?>
						<td><strong><?= $totalworkhh_top = isset($totalWorkHours) ? $totalWorkHours : '0'; ?></strong></td>
						<td><strong><?= $totalworkmm_top = isset($totalWorkMinutes) ? $totalWorkMinutes : '0'; ?></strong></td>
						<td><strong><?= $cnprejob_top = isset($countJobStartHH) ? $countJobStartHH : '0'; ?></strong></td>
						<td><strong><?= $cnactjob_top = isset($countJobStartHH) ? $countJobStartHH : '0'; ?></strong></td>
						<td><strong><?= $holydayswork_top = '0' ?></strong></td>
						<td><strong><?= $offdayswork_top = '0'; ?></strong></td>
						<td><strong><?= $delaydayswork_top = '0'; ?></strong></td>
						<td><strong><?= $earlydayswork_top = '0'; ?></strong></td>
					<?php elseif ($decide_template_ == "2") : ?>
						<td><strong><?= $totaldayhh_top = isset($totalDayHours) ? $totalDayHours : '0'; ?></strong></td>
						<td><strong><?= $totaldaymm_top = isset($totalDayMinutes) ? $totalDayMinutes : '0'; ?></strong></td>
						<td><strong><?= $cnprejob_top = isset($countJobStartHH) ? $countJobStartHH : '0'; ?></strong></td>
						<td><strong><?= $cnactjob_top = isset($countDayStartHH) ? $countDayStartHH : '0'; ?></strong></td>
						<td><strong><?= $holydayswork_top = '0' ?></strong></td>
						<td><strong><?= $offdayswork_top = isset($countJobAct) ? $countJobAct : '0'; ?></strong></td>
						<td><strong><?= $delaydayswork_top = isset($countLate) ? $countLate : '0'; ?></strong></td>
						<td><strong><?= $earlydayswork_top = isset($countEarly) ? $countEarly : '0'; ?></strong></td>
					<?php endif; ?>
				<?php
				}
				?>
			</tr>
			<tr id="footer_table_edit_input">
				<td><input type="submit" name="MonthSaveKintai" class="btn btn-primary" id="btnSaveMonth" role="button" value="月登録"></td>
				<?php
				if (!empty($workmonth_list)) {
					foreach ($workmonth_list as $key) {
				?>
						<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom" id="jobhh_bottom" maxlength="3" value="<?= isset($key['jobhour']) ? $key['jobhour'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom" id="jobmm_bottom" maxlength="2" value="<?= isset($key['jobminute']) ? $key['jobminute'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom" id="jobdays_bottom" maxlength="2" value="<?= isset($key['jobdays']) ? $key['jobdays'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom" id="workdays_bottom" maxlength="2" value="<?= isset($key['workdays']) ? $key['workdays'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom" id="holydays_bottom" maxlength="2" value="<?= isset($key['holydays']) ? $key['holydays'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom" id="offdays_bottom" maxlength="2" value="<?= isset($key['offdays']) ? $key['offdays'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom" id="delaydays_bottom" maxlength="2" value="<?= isset($key['delaydays']) ? $key['delaydays'] : '0'; ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom" id="earlydays_bottom" maxlength="2" value="<?= isset($key['earlydays']) ? $key['earlydays'] : '0'; ?>"></td>
					<?php
					}
				} else {
					?>
					<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom" id="jobhh_bottom" maxlength="3" value="0"></td>
					<td rowspan="3"><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom" id="jobmm_bottom" maxlength="2" value="0"></td>
					<td rowspan="3"><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom" id="jobdays_bottom" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="workdays_bottom" id="workdays_bottom" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="holydays_bottom" id="holydays_bottom" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="offdays_bottom" id="offdays_bottom" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="delaydays_bottom" id="delaydays_bottom" maxlength="2" value="0"></td>
					<td><input type="text" class="form-control" style="text-align: center;" name="earlydays_bottom" id="earlydays_bottom" maxlength="2" value="0"></td>
				<?php }
				?>
			</tr>
		</tbody>
	</table>
</div>

<!-- Modal 勤務時間登録編集  -->
<div class="row">
	<div class="modal" id="modal" tabindex="-2" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						勤務時間<span id="KUDdatetext"></span>(<span id="KUDdate"></span>)
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body" style="text-align: left">
						<div class="row">
							<div class="col-xs-4">
								<label for="workymd">日付</label>
								<input type="text" class="form-control" id="workymd" name="workymd" style="text-align: center" readonly>
								<input type="hidden" id="uid" name="uid">
								<input type="hidden" id="genid" name="genid">
								<input type="hidden" id="date_show" name="date_show">
								<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
							</div>
							<div class="col-xs-2 holder">
								<label>業務開始</label>
								<select id="jobstarthh" name="jobstarthh" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVjobstarthh" class="form-control text_size" placeholder="入力(xx)">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="jobstartmm" name="jobstartmm" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVjobstartmm" class="form-control text_size" placeholder="入力(xx)">
							</div>
							<div class="col-xs-2 holder">
								<label>業務終了</label>
								<select id="jobendhh" name="jobendhh" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVjobendhh" class="form-control text_size" placeholder="入力(xx)">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="jobendmm" name="jobendmm" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVjobendmm" class="form-control text_size" placeholder="入力(xx)">
							</div>
						</div>
						<br>
						<br>
						<?php if ($decide_template_ == "2") : ?>
							<div class="row">
								<div class="col-xs-4"></div>
								<div class="col-xs-2 holder">
									<label>出社時刻</label>
									<select id="daystarthh" name="daystarthh" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
									<input type="text" id="IVdaystarthh" class="form-control text_size" placeholder="入力(xx)">
								</div>
								<div class="col-xs-2 holder">
									<label>&nbsp;</label>
									<select id="daystartmm" name="daystartmm" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
									<input type="text" id="IVdaystartmm" class="form-control text_size" placeholder="入力(xx)">
								</div>
								<div class="col-xs-2 holder">
									<label>退社時刻</label>
									<select id="dayendhh" name="dayendhh" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
									<input type="text" id="IVdayendhh" class="form-control text_size" placeholder="入力(xx)">
								</div>
								<div class="col-xs-2 holder">
									<label>&nbsp;</label>
									<select id="dayendmm" name="dayendmm" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
									<input type="text" id="IVdayendmm" class="form-control text_size" placeholder="入力(xx)">
								</div>
							</div>
							<br>
							<br>
							<br>
							<br>
						<?php endif; ?>
						<div class="row">
							<div class="col-xs-4"></div>
							<div class="col-xs-2 holder">
								<label>休憩時間</label>
								<select id="offtimehh" name="offtimehh" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVofftimehh" class="form-control text_size" placeholder="入力(xx)">
							</div>
							<div class="col-xs-2 holder">
								<label>&nbsp;</label>
								<select id="offtimemm" name="offtimemm" class="form-control" size="1" onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
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
								<input type="text" id="IVofftimemm" class="form-control text_size" placeholder="入力(xx)">
							</div>
							<div class="col-xs-2">
								<label for="workhh">就業時間</label>
								<input type="text" class="form-control" name="workhh" id="workhh" placeholder="0" required="required" style="text-align: center" readonly>
							</div>
							<div class="col-xs-2">
								<label for="workmm">&nbsp;</label>
								<input type="text" class="form-control" name="workmm" id="workmm" placeholder="0" required="required" style="text-align: center" readonly>
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="comment">業務内容</label>
								<input type="text" class="form-control" name="comment" id="comment" placeholder="content" style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" name="bigo" id="bigo" placeholder="remark" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<input type="submit" name="SaveUpdateKintai" class="btn btn-primary" id="btnReg" role="button">
						<input type="submit" name="DeleteKintai" class="btn btn-warning" id="btnDel" role="button" value="削除">
						<button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	// Submit for select
	jQuery(function() {
		jQuery('.seldate').change(function() {
			this.form.submit();
		});
	});

	// Funtion for click day of week
	$(document).on('click', '.showModal', function() {
		$('#modal').modal('toggle');
		var ArrayData = $(this).text();
		var SeparateArr = ArrayData.split('/');
		var Date_ = SeparateArr[1].substr(0, 2);

		var SeparateArr2 = ArrayData.split(',');
		var CheckData = SeparateArr2[1];
		if (CheckData === "") {
			$('#btnReg').val("登録");
			$('#KUDdatetext').text("登録");
		} else {
			$('#btnReg').val("編集");
			$('#KUDdatetext').text("編集");
		}

		var uid = $("input[name=uid]:hidden");
		uid.val("<?php echo $_SESSION['employee_uid'] ?>");
		var uid = uid.val();
		var genid = $("input[name=genid]:hidden");
		genid.val("<?php echo $_SESSION['employee_genid'] ?>");
		var genid = genid.val();
		var date_show = $("input[name=date_show]:hidden");
		date_show.val("<?php echo $date_show ?>" + Date_);
		var date_show = date_show.val();
		$("#workymd").text($('[name="workymd"]').val(date_show));
		$("#KUDdate").text(date_show);
		<?php
		foreach ($datas as $key) {
		?>
			if ('<?php echo $key['workymd'] ?>' === date_show) {
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

			}
		<?php
		}
		?>
	});
</script>

<?php include('../inc/footer.php'); ?>