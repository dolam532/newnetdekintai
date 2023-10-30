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
		<div class="col-md-9 text-right">
			<div class="title_btn">
				<input type="button" onclick="window.location.href='../kintai/kintaiReg.php'" value="戻る">
			</div>
		</div>
	</div>

	<div class="form-group">
		<table class="table table-bordered datatable">
			<thead>
				<tr class="info">
					<th style="text-align: center; width: 10%;">ID</th>
					<th style="text-align: center; width: 10%;">PASSWORD</th>
					<th style="text-align: center; width: 10%;">社員名</th>
					<th style="text-align: center; width: 20%;">Email</th>
					<th style="text-align: center; width: 10%;">部署</th>
					<th style="text-align: center; width: 10%;">区分</th>
					<th style="text-align: center; width: auto;">勤務時間タイプ</th>
					<th style="text-align: center; width: 10%;">詳しい情報</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($user_list)) { ?>
					<tr>
						<td colspan="8" align="center"><?php echo $data_save_no; ?></td>
					</tr>
					<?php } elseif (!empty($user_list)) {
					foreach ($user_list as $user) {
					?>
						<tr>
							<td><span name="uid"><?= $user['uid'] ?></span></td>
							<td><span name="pwd"><?= $user['pwd'] ?></span></td>
							<td><span name="name"><?= $user['name'] ?></span></td>
							<td><span name="email"><?= $user['email'] ?></span></td>
							<td><span name="dept"><?= $user['dept'] ?></span></td>
							<td><span name="grade"><?= $user['grade'] ?></span></td>
							<td><span name="genbaname"><?= $user['genbaname'] ?></span></td>

							<td class="text-center">
								<form method="post" action="../kintaidetail/kintaiUserDetail.php?uid=<?= $user['uid'] ?>&name=<?= $user['name'] ?>&dept=<?= $user['dept'] ?>">
									<input type="hidden" value="<?= $user['uid'] ?>" name="uid_g">
									<input type="hidden" value="<?= $user['name'] ?>" name="name_g">
									<input type="hidden" value="<?= $user['genid'] ?>" name="genid_g">
									<input type="hidden" value="<?= $user['dept'] ?>" name="dept_g">
									<input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL" role="button" value="詳細">
								</form>
							</td>
						</tr>
				<?php }
				} ?>
			</tbody>
		</table>
	</div>
</div>