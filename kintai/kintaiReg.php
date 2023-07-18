<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/commonmodel.php');
include('../model/kintaimodel.php');
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
		padding-top: 30px;
	}
</style>
<title>勤 務 表</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top: -20px;">
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
	<div class="row">
		<div class="col-md-5 text-left" name="workYm_page_title">
			<div class="title_name text-center">
				<span id="workYm_page_title" class="text-left">勤 務 表</span>
			</div>
		</div>
		<form method="post">
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
		<div class="col-md-3 text-right">
			<div class="print_btn">
				<p>
					<a href="#" onclick="autoInputHandle()" class="btn btn-default" style="width: 120px;">自動入力</a>
				</p>
			</div>

			<div class="print_btn">
				<p>
				<form action="../pdfdownload/generatepdf.php" method="post" target="_blank">
					<input type="hidden" name="data" value="<?php echo htmlspecialchars(json_encode($datas)); ?>">
					<input type="hidden" name="name" value="<?php echo htmlspecialchars(json_encode($_SESSION['auth_name'])); ?>">
					<input type="hidden" name="dept" value="<?php echo htmlspecialchars(json_encode($_SESSION['auth_dept'])); ?>">
					<input type="hidden" name="date_show" value="<?php echo htmlspecialchars(json_encode($date_show)); ?>">
					<input type="hidden" name="template" value="<?php echo htmlspecialchars(json_encode($decide_template_)); ?>">
					<button class="btn btn-default" style="width: 120px;" type="submit">勤務表印刷</button>
				</form>
				</p>
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
										<span class="showModal"><?= $key['date']; ?></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?></span>
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
										<span class="showModal"><?= $key['date']; ?></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?></span>
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
				<th id="footer_workTime_label" style="width: 10%; padding-top: 30px;" rowspan="2">実働時間</th>
				<th id="footer_workhh_label" style="width: 8%;">時間</th>
				<th id="footer_workmm_label" style="width: 8%;">分</th>
				<th id="footer_workStatus_label" style="width: 10%; padding-top: 30px;" rowspan="3">勤務状況</th>
				<th id="footer_jobDay_label" style="width: 12%;">所定勤務日数</th>
				<th id="footer_workDay_label" style="width: 12%;">実勤務日数</th>
				<th id="footer_holyDay_label" style="width: 10%;">休暇</th>
				<th id="footer_offDay_label" style="width: 10%;">欠勤</th>
				<th id="footer_delayDay_label" style="width: 10%;">遅刻</th>
				<th id="footer_earlyDay_label" style="width: auto;">早退</th>
			</tr>
			<tr id="footer_table_show_value">
				<td id="workhh_top"><strong><?= $totalWorkHours ?></strong></td>
				<td id="workmm_top"><strong><?= $totalWorkMinutes ?></strong></td>
				<td id="jobday_top"><strong><?= $countJobStartHH ?></strong></td>

				<?php if ($decide_template_ == "1") : ?>
					<td id="workday_top"><strong><?= $countJobStartHH ?></strong></td>
					<td id="holyday_top"><strong>0</strong></td>
					<td id="offday_top"><strong><?= $countJobStartHH - $countJobStartHH ?></strong></td>
					<td id="delayday_top"><strong>0</strong></td>
					<td id="earlyday_top"><strong>0</strong></td>
				<?php elseif ($decide_template_ == "2") : ?>
					<td id="workday_top"><strong><?= $countDayStartHH ?></strong></td>
					<td id="holyday_top"><strong>0</strong></td>
					<td id="offday_top"><strong><?= $countJobStartHH - $countDayStartHH ?></strong></td>
					<td id="delayday_top"><strong><?= $countLate ?></strong></td>
					<td id="earlyday_top"><strong><?= $countEarly ?></strong></td>
				<?php endif; ?>
			</tr>
			<tr id="footer_table_edit_input">
				<form method="post">
					<td>
						<input type="hidden" value="<?= $year ?>" name="year">
						<input type="hidden" value="<?= $month ?>" name="month">
						<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
						<input type="submit" name="MonthSaveKintai" class="btn btn-primary" id="btnSaveMonth" role="button" value="月登録">
					</td>
					<td><input type="text" class="form-control" style="text-align: center" name="workhh_bottom" id="workhh_bottom" maxlength="2" value="<?= $totalWorkHours ?>"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="workmm_bottom" id="workmm_bottom" maxlength="2" value="<?= $totalWorkMinutes ?>"></td>
					<td><input type="text" class="form-control" style="text-align: center" name="jobday_bottom" id="jobday_bottom" maxlength="2" value="<?= $countJobStartHH ?>"></td>
					<?php if ($decide_template_ == "1") : ?>
						<td><input type="text" class="form-control" style="text-align: center" name="workday_bottom" id="workday_bottom" maxlength="2" value="<?= $countJobStartHH ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="holyday_bottom" id="holyday_bottom" maxlength="2" value="0"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="offday_bottom" id="offday_bottom" maxlength="2" value="<?= $countJobStartHH - $countJobStartHH ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="delayday_bottom" id="delayday_bottom" maxlength="2" value="0"></td>
						<td><input type="text" class="form-control" style="text-align: center;" name="earlyday_top" id="earlyday_top" maxlength="2" value="0"></td>
					<?php elseif ($decide_template_ == "2") : ?>
						<td><input type="text" class="form-control" style="text-align: center" name="workday_bottom" id="workday_bottom" maxlength="2" value="<?= $countDayStartHH ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="holyday_bottom" id="holyday_bottom" maxlength="2" value="0"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="offday_bottom" id="offday_bottom" maxlength="2" value="<?= $countJobStartHH - $countDayStartHH ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center" name="delayday_bottom" id="delayday_bottom" maxlength="2" value="<?= $countLate ?>"></td>
						<td><input type="text" class="form-control" style="text-align: center;" name="earlyday_top" id="earlyday_top" maxlength="2" value="<?= $countEarly ?>"></td>
					<?php endif; ?>
				</form>
			</tr>
		</tbody>
	</table>
</div>

<!-- Modal 自動入力 -->
<div class="row">
	<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						自動入力設定<span id=""></span>
						<button class="close" data-dismiss="modal" onclick="handlerCloseModal(1)">&times;</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-9">
								<label for="genbaname_rmodal">勤務時間</label>
								<select class="form-control" id="genba_selection_rmodal" name="genba_selection_rmodal">
									<option value="" selected="">現場を選択してください。</option>
									<?php foreach ($genba_list as $value) { ?>
										<option value="<?= $value['genid'] . ',' . $value['workstrtime'] . ',' . $value['workendtime'] . ',' . $value['offtime1'] . ',' . $value['offtime2']  ?>" <?php if ($value['genid'] == $_SESSION['auth_genid']) {
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
						<br>
						<div class="row">
							<div class="col-md-6">
								<label for="workcontent_rmodal">業務内容</label>
								<input type="text" class="form-control" name="workcontent_rmodal" id="workcontent_rmodal" placeholder="業務内容" style="text-align: left">
							</div>
							<div class="col-md-6">
								<label for="bigo_rmodal">備考</label>
								<input type="text" class="form-control" name="bigo_rmodal" id="bigo_rmodal" placeholder="備考" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-md-4"></div>
						<div class="col-md-2">
							<input type="submit" name="AutoUpdateKintai" class="btn btn-primary" id="btnAuto" role="button" value="入力確定">
						</div>
						<div class="col-md-2">
							<button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
						</div>
						<div class="col-md-4"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal 勤務時間変更  -->
<div class="row">
	<div class="modal" id="modal2" tabindex="-2" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">
						勤務時間変更(<span id="selkindate"></span>)
						<button class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body" style="text-align: left">
						<div class="row">
							<div class="col-xs-4">
								<label for="workymd">日付</label>
								<input type="text" class="form-control" id="workymd" name="workymd" placeholder="" style="text-align: center" readonly>
								<input type="hidden" id="uid" name="uid">
								<input type="hidden" id="genid" name="genid">
								<input type="hidden" id="date_show" name="date_show">
								<input type="hidden" value="<?= $decide_template_ ?>" name="template_table_">
							</div>
							<div class="col-xs-2">
								<label>業務開始</label>
								<select id="jobstarthh" name="jobstarthh" class="form-control">
									<option value="" selected></option>
									<?php
									foreach (ConstArray::$search_hour as $key => $value) {
									?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['jobstarthh']) {
																		echo ' selected="selected"';
																	} ?>>
											<?= $value ?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<select id="jobstartmm" name="jobstartmm" class="form-control">
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
							</div>
							<div class="col-xs-2">
								<label>業務終了</label>
								<select id="jobendhh" name="jobendhh" class="form-control">
									<option value="" selected></option>
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
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<select id="jobendmm" name="jobendmm" class="form-control">
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
							</div>
						</div>
						<br>
						<?php if ($decide_template_ == "2") : ?>
							<div class="row">
								<div class="col-xs-4 "></div>
								<div class="col-xs-2">
									<label>出社時刻</label>
									<select id="daystarthh" name="daystarthh" class="form-control">
										<option value="" selected></option>
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
								</div>
								<div class="col-xs-2">
									<label>&nbsp;</label>
									<select id="daystartmm" name="daystartmm" class="form-control">
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
								</div>
								<div class="col-xs-2">
									<label>退社時刻</label>
									<select id="dayendhh" name="dayendhh" class="form-control">
										<option value="" selected></option>
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
								</div>
								<div class="col-xs-2">
									<label>&nbsp;</label>
									<select id="dayendmm" name="dayendmm" class="form-control">
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
								</div>
							</div>
							<br>
						<?php endif; ?>
						<div class="row">
							<div class="col-xs-4"></div>
							<div class="col-xs-2">
								<label>休憩時間</label>
								<select id="offtimehh" name="offtimehh" class="form-control">
									<option value="" selected></option>
									<?php
									foreach (ConstArray::$rest_hour as $key => $value) {
									?>
										<option value="<?= $key ?>" <?php if ($value == $_POST['offtimehh'] ?? '01') {
																		echo ' selected="selected"';
																	} ?>>
											<?= $value ?>
										</option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-xs-2">
								<label>&nbsp;</label>
								<select id="offtimemm" name="offtimemm" class="form-control">
									<?php
									foreach (ConstArray::$rest_minute as $key => $value) {
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
						<div class="row">
							<div class="col-xs-6">
								<label for="comment">業務内容</label>
								<input type="text" class="form-control" name="comment" id="comment" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" name="bigo" id="bigo" placeholder="" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<input type="submit" name="SaveUpdateKintai" class="btn btn-primary" id="btnReg" role="button" value="登録">
						<input type="submit" name="DeleteKintai" class="btn btn-primary" id="btnDel" role="button" value="削除">
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
		$('#modal2').modal('toggle');
		var ArrayData = $(this).text();
		var SeparateArr = ArrayData.split('/');
		var Date_ = SeparateArr[1].substr(0, 2);
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
		$("#selkindate").text(date_show);
		<?php
		foreach ($datas as $key) {
		?>
			if ('<?php echo $key['workymd'] ?>' === date_show) {
				document.getElementById("jobstarthh").value = "<?php echo $key['jobstarthh'] ?>";
				document.getElementById("jobstartmm").value = "<?php echo $key['jobstartmm'] ?? '00'; ?>";
				document.getElementById("jobendhh").value = "<?php echo $key['jobendhh'] ?>";
				document.getElementById("jobendmm").value = "<?php echo $key['jobendmm'] ?? '00'; ?>";
				document.getElementById("offtimehh").value = "<?php echo $key['offtimehh'] ?>";
				document.getElementById("offtimemm").value = "<?php echo $key['offtimemm'] ?? '00'; ?>";
				document.getElementById("workhh").value = "<?php echo $key['workhh'] ?>";
				document.getElementById("workmm").value = "<?php echo $key['workmm'] ?>";
				$("#comment").text($('[name="comment"]').val("<?php echo $key['comment'] ?>"));
				$("#bigo").text($('[name="bigo"]').val("<?php echo $key['bigo'] ?>"));
				document.getElementById("daystarthh").value = "<?php echo $key['daystarthh'] ?>";
				document.getElementById("daystartmm").value = "<?php echo empty($key['daystartmm']) ? "00" : $key['daystartmm']; ?>";
				document.getElementById("dayendhh").value = "<?php echo $key['dayendhh'] ?>";
				document.getElementById("dayendmm").value = "<?php echo empty($key['daystartmm']) ? "00" : $key['dayendmm']; ?>";
			}
		<?php
		}
		?>
	});

	// Time calculate
	$('#jobstarthh, #jobstartmm, #jobendhh, #jobendmm, #daystarthh, #daystartmm, #dayendhh, #dayendmm, #offtimehh, #offtimemm').on('change', function(e) {
		var offtimehh = $('#offtimehh').val();
		var offtimemm = $('#offtimemm').val();
		var offtime_ = offtimehh + ':' + offtimemm;
		o = offtime_.split(':');
		var jobstarthh = $('#jobstarthh').val();
		var jobstartmm = $('#jobstartmm').val();
		var jobendhh = $('#jobendhh').val();
		var jobendmm = $('#jobendmm').val();
		<?php if ($decide_template_ == "1") : ?>
			var jobstartime_ = jobstarthh + ':' + jobstartmm;
			var jobendtime_ = jobendhh + ':' + jobendmm;
			s = jobstartime_.split(':');
			e = jobendtime_.split(':');
		<?php elseif ($decide_template_ == "2") : ?>
			var daystarthh = $('#daystarthh').val();
			var daystartmm = $('#daystartmm').val();
			var dayendhh = $('#dayendhh').val();
			var dayendmm = $('#dayendmm').val();
			var daystartime_ = daystarthh + ':' + daystartmm;
			var dayendtime_ = dayendhh + ':' + dayendmm;
			s = daystartime_.split(':');
			e = dayendtime_.split(':');
		<?php endif; ?>

		min = e[1] - s[1] - o[1];
		hour_carry = 0;
		if (min < 0) {
			min += 60;
			hour_carry += 1;
		}
		hour = e[0] - s[0] - o[0] - hour_carry;
		var workhh = hour;
		if (workhh <= 0) {
			workhh = workhh + 24;
		}
		var workmm = min;
		$('#workhh').val(workhh);
		$('#workmm').val(workmm);
	});

	// Check Error
	$(document).on('click', '#btnReg', function(e) {
		<?php if ($decide_template_ == "2") : ?>
			var daystarthh = $("#daystarthh option:selected").val();
			var dayendhh = $("#dayendhh option:selected").val();

			if (daystarthh == "") {
				alert("<?php echo $kintai_start_empty; ?>");
				$("#daystarthh").focus();
				return false;
			}

			if (dayendhh == "") {
				alert("<?php echo $kintai_end_empty; ?>");
				$("#dayendhh").focus();
				return false;
			}
		<?php endif; ?>

		var jobstarthh = $("#jobstarthh option:selected").val();
		var jobendhh = $("#jobendhh option:selected").val();
		var offtimehh = $("#offtimehh option:selected").val();

		if (jobstarthh == "") {
			alert("<?php echo $kintai_bstart_empty; ?>");
			$("#jobstarthh").focus();
			return false;
		}

		if (jobendhh == "") {
			alert("<?php echo $kintai_bend_empty; ?>");
			$("#jobendhh").focus();
			return false;
		}

		if (offtimehh == "") {
			alert("<?php echo $kintai_offtime_empty; ?>");
			$("#offtimehh").focus();
			return false;
		}
	});

	// 自動入力
	function autoInputHandle() {
		$('#modal').modal('toggle');
		$("#weekdayCheckbox").prop('checked', true);
	}
</script>

<?php include('../inc/footer.php'); ?>