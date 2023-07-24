<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kintaimodel.php');
include('../inc/header.php');
include('../inc/menu.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}

?>
<style type="text/css">
	.datatable tr td {
		text-align: center;
	}

	#myBar {
		width: 1%;
		height: 30px;
		background-color: #4CAF50;
	}
</style>
<title>月勤務表</title>
<div class="container">
	<div class="row">
		<div class="col-md-5 text-left">
			<div class="title_name">
				<span class="text-left">月勤務表</span>
			</div>
		</div>
		<form method="post">
			<div class="col-md-4 text-center">
				<div class="title_condition">
					<label>基準日:
						<select id="selyyM" name="selyyM" class="seldate" style="padding:5px;" onchange="this.form.submit()">
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
						<select id="selmmM" name="selmmM" class="seldate" style="padding:5px;" onchange="this.form.submit()">
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
		<div class="col-md-3 text-right"></div>
	</div>
	<table class="table table-bordered datatable">
		<thead>
			<tr class="info">
				<th style="text-align: center; width: 12%;">名前</th>
				<th style="text-align: center; width: 7%;">作業年月</th>
				<th style="text-align: center; width: 7%;">実働時間</th>
				<th style="text-align: center; width: 7%;">実働分</th>
				<th style="text-align: center; width: 7%;">勤務日</th>
				<th style="text-align: center; width: 7%;">実勤務日</th>
				<th style="text-align: center; width: 5%;">休暇</th>
				<th style="text-align: center; width: 5%;">残業</th>
				<th style="text-align: center; width: 5%;">欠勤</th>
				<th style="text-align: center; width: 5%;">遅刻</th>
				<th style="text-align: center; width: 5%;">早退</th>
				<th style="text-align: center; width: auto;">備考</th>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($workmonth_select_list)) { ?>
				<tr>
					<td colspan="12" align="center"><?php echo $data_save_no; ?></td>
				</tr>
				<?php } elseif (!empty($workmonth_select_list)) {
				foreach ($workmonth_select_list as $workmonth_select) {
				?>
					<tr>
						<td><a href="../kintai/kintaiReg.php"><span><?= $workmonth_select['name'] ?></span></a></td>
						<td><span><?= $workmonth_select['workym'] ?></span></td>
						<td><span><?= $workmonth_select['jobhour'] ?></span></td>
						<td><span><?= $workmonth_select['jobminute'] ?></span></td>
						<td><span><?= $workmonth_select['workdays'] ?></span></td>
						<td><span><?= $workmonth_select['jobdays'] ?></span></td>
						<td><span><?= $workmonth_select['janhour'] . ':' . $workmonth_select['janminute'] ?></span></td>
						<td><span><?= $workmonth_select['holydays'] ?></span></td>
						<td><span><?= $workmonth_select['offdays'] ?></span></td>
						<td><span><?= $workmonth_select['delaydays'] ?></span></td>
						<td><span><?= $workmonth_select['earlydays'] ?></span></td>
						<td><span><?= $workmonth_select['bigo'] ?></span></td>
					</tr>
			<?php }
			} ?>
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