<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kyukamodel.php');
include('../inc/header.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
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
			<div class="col-md-3 text-left">
				<div class="title_name">
					<span class="text-left">休暇使用現状</span>
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
			<div class="col-md-3 text-right">
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
			<div class="col-md-3 text-right">
				<div class="title_btn">
					<input type="submit" name="btnSearchMon" value="検索 ">
				</div>
			</div>
		</div>

		<div class="form-group">
			<table class="table table-bordered datatable">
				<thead>
					<tr class="info">
						<th style="text-align: center; width: 12%;">部署</th>
						<th style="text-align: center; width: 10%;">社員名</th>
						<th style="text-align: center; width: 10%;">年次開始日</th>
						<th style="text-align: center; width: 10%;">年次終了日</th>
						<th style="text-align: center; width: 8%;">前年残数</th>
						<th style="text-align: center; width: 8%;">当年付与</th>
						<th style="text-align: center; width: 8%;">使用(日)</th>
						<th style="text-align: center; width: 8%;">使用(時)</th>
						<th style="text-align: center; width: 8%;">残数(日)</th>
						<th style="text-align: center; width: auto;">備考</th>
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
								<td><span><?= $userkyuka['dept'] ?></span></td>
								<td><span><?= $userkyuka['name'] ?></span></td>
								<td><span><?= $userkyuka['vacationstr'] ?></span></td>
								<td><span><?= $userkyuka['vacationend'] ?></span></td>
								<td><span><?= $userkyuka['oldcnt'] ?></span></td>
								<td><span><?= $userkyuka['newcnt'] ?></span></td>
								<td><span><?= $userkyuka['usecnt'] ?></span></td>
								<td><span><?= $userkyuka['usetime'] ?></span></td>
								<td><span><?= $userkyuka['restcnt'] ?></span></td>
								<td><span><?= $userkyuka['remark'] ?></span></td>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</form>
</div>
<?php include('../inc/footer.php'); ?>