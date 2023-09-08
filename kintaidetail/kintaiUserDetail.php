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

	span.kintaiReg_class {
		display: none;
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
			<div class="title_btn">
				<input type="button" onclick="window.location.href='./kintaiUser.php'" value="戻る ">
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
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
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
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php elseif ($key['decide_color'] == "日") : ?>
									<a href="#" style="color:red;">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
									</a>
								<?php else : ?>
									<a href="#">
										<span class="showModal"><?= $key['date']; ?><span class="kintaiReg_class"><?= ',' . $key['jobstarthh'] ?></span></span>
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
				<th style="width: 10%; padding-top: 30px;" rowspan="3">実働時間</th>
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
			<tr id="footer_table_edit_input">
				<?php
				if (!empty($workmonth_list)) {
					foreach ($workmonth_list as $key) {
				?>
						<td><input type="text" class="form-control" style="text-align: center" name="jobhh_bottom" id="jobhh_bottom" maxlength="3" value="<?= isset($key['jobhour']) ? $key['jobhour'] : '0'; ?>"></td>
						<td rowspan="3"><input type="text" class="form-control" style="text-align: center" name="jobmm_bottom" id="jobmm_bottom" maxlength="2" value="<?= isset($key['jobminute']) ? $key['jobminute'] : '0'; ?>"></td>
						<td rowspan="3"><input type="text" class="form-control" style="text-align: center" name="jobdays_bottom" id="jobdays_bottom" maxlength="2" value="<?= isset($key['jobdays']) ? $key['jobdays'] : '0'; ?>"></td>
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
<script>
	// Submit for select
	jQuery(function() {
		jQuery('.seldate').change(function() {
			this.form.submit();
		});
	});
</script>

<?php include('../inc/footer.php'); ?>