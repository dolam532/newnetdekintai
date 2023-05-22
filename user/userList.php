<?php
// connect to database
include('../inc/dbconnect.php');

// fetch the resulting rows as a array
if (isset($_POST['searchGrade']) || isset($_POST['searchName'])) {
	$searchData = $_POST['searchGrade'];
	$searchName = $_POST['searchName'];
	$clear = $_POST['clear'];

	if ($clear !== NULL) {
		$sql_user = 'SELECT * FROM `tbl_user`';
	} elseif (!empty($searchData) && empty($searchName)) {
		$sql_user = "SELECT * FROM `tbl_user` WHERE grade LIKE '%$searchData%'";
	} elseif (!empty($searchName) && empty($searchData)) {
		$sql_user = "SELECT * FROM `tbl_user` WHERE name LIKE '%$searchName%'";
	} else if (!empty($searchData) && !empty($searchName)) {
		$sql_user = "SELECT * FROM `tbl_user` WHERE grade LIKE '%$searchData%' AND name LIKE '%$searchName%'";
	} else {
		$sql_user = 'SELECT * FROM `tbl_user`';
	}
} else {
	$sql_user = 'SELECT * FROM `tbl_user`';
}
$result = mysqli_query($conn, $sql_user);
$user_list = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>

	<!-- common Javascript -->
	<script type="text/javascript" src="../assets/js/common.js"> </script>

	<!-- Datepeeker 위한 link -->
	<link rel="stylesheet" href="../assets/css/jquery-ui.min.css">
	<script src="../assets/js/jquery-ui.min.js"></script>

	<!-- common CSS -->
	<link rel="stylesheet" href="../assets/css/common.css">
	<title>社員登録</title>
	<style type="text/css">
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
</head>

<body>
	<?php include('../inc/header.php'); ?>
	<div class="container">
		<div class="row">
			<div class="col-md-3 text-left">
				<div class="title_name">
					<span class="text-left">社員登録</span>
				</div>
			</div>
			<form method="post">
				<div class="col-md-3 text-left">
					<div class="title_condition">
						<label>区分 : <input type="text" name="searchGrade" style="width: 100px;"></label>
					</div>
				</div>
				<div class="col-md-3 text-left">
					<div class="title_condition">
						<label>社員名 : <input type="text" name="searchName" style="width: 100px;"></label>
					</div>
				</div>
				<div class="col-md-3 text-right">
					<div class="title_btn">
						<input type="submit" name="clear" value="クリア">&nbsp;&nbsp;&nbsp;
						<input type="submit" value="検索">&nbsp;&nbsp;&nbsp;
						<input type="button" id="btnNew" value="新規">
					</div>
				</div>
			</form>
		</div>

		<div class="form-group">
			<table class="table table-bordered datatable">
				<thead>
					<tr class="info">
						<th style="text-align: center; width: 10%;">ID</th>
						<th style="text-align: center; width: 10%;">PASSWORD</th>
						<th style="text-align: center; width: 10%;">社員名</th>
						<th style="text-align: center; width: 14%;">Email</th>
						<th style="text-align: center; width: 10%;">部署</th>
						<th style="text-align: center; width: 8%;">区分</th>
						<th style="text-align: center; width: 16%;">現場</th>
						<th style="text-align: center; width: auto;">備考</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($user_list)) { ?>
						<tr>
							<td colspan="8" align="center">登録されたデータがありません.</td>
						</tr>
						<?php } elseif (!empty($user_list)) {
						foreach ($user_list as $user) {
						?>
							<tr>
								<td><a href="#"><span class="showModal"><?= $user['uid'] ?></span></a></td>
								<td><span name="cpwd">********</span></td>
								<td><span name="cname"><?= $user['name'] ?></span></td>
								<td><span name="cemail"><?= $user['email'] ?></span></td>
								<td><span name="cdept"><?= $user['dept'] ?></span></td>
								<td><span name="cgrade"><?= $user['grade'] ?></span></td>
								<td><span name="cgenbaname"><?= $user['genbaname'] ?></span></td>
								<td><span name="cbigo"><?= $user['bigo'] ?></span>
									<input type="hidden" name="tuid" value="<?= $user['uid'] ?>">
									<input type="hidden" name="tcompanyid" value="<?= $user['companyid'] ?>">
									<input type="hidden" name="ttype" value="<?= $user['type'] ?>">
									<input type="hidden" name="tpwd" value="<?= $user['pwd'] ?>">
									<input type="hidden" name="tname" value="<?= $user['name'] ?> 一郎">
									<input type="hidden" name="temail" value="<?= $user['email'] ?>">
									<input type="hidden" name="tdept" value="<?= $user['dept'] ?>">
									<input type="hidden" name="tgrade" value="<?= $user['grade'] ?>">
									<input type="hidden" name="tbigo" value="<?= $user['bigo'] ?>">
									<input type="hidden" name="tgenid" value="<?= $user['genid'] ?>">
									<input type="hidden" name="tgenbaname" value="<?= $user['genbaname'] ?>">
									<input type="hidden" name="tgenstrymd" value="<?= $user['genstrymd'] ?>">
									<input type="hidden" name="tgenendymd" value="<?= $user['genendymd'] ?>">
									<input type="hidden" name="tinymd" value="<?= $user['inymd'] ?>">
									<input type="hidden" name="toutymd" value="<?= $user['outymd'] ?>">
								</td>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="row">
		<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						社員登録(<span id="sname"></span>)
						<button class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-xs-3">
								<label for="uid">ID</label>
								<input type="text" class="form-control" id="uid" placeholder="ID" required="required" maxlength="10" style="text-align: left">
								<input type="hidden" id="seq" value="">
								<input type="hidden" id="companyid" value="">
								<input type="hidden" id="type" value="">
							</div>
							<div class="col-xs-3">
								<label for="pwd">PASSWORD</label>
								<input type="password" class="form-control" id="pwd" placeholder="pwd" required="required" maxlength="20" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="name">社員名</label>
								<input type="text" class="form-control" id="name" placeholder="name" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="grade">区分</label>
								<input type="text" class="form-control" id="grade" placeholder="役員/管理/社員" required="required" maxlength="30" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="email">email</label>
								<input type="text" class="form-control" id="email" placeholder="email" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="dept">部署</label>
								<input type="text" class="form-control" id="dept" placeholder="開発部" maxlength="50" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" id="bigo" maxlength="1000" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="inymd">入社日</label>
								<input type="text" class="form-control" id="inymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="outymd">退社日</label>
								<input type="text" class="form-control" id="outymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="genid">現場</label>
								<select class="form-control" id="genid" name="genid"></select>
							</div>
							<div class="col-xs-3">
								<label for="genstrymd">契約期間(F)</label>
								<input type="text" class="form-control" id="genstrymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="genendymd">契約期間(T)</label>
								<input type="text" class="form-control" id="genendymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-xs-4"></div>
						<div class="col-xs-2">
							<p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="#" role="button">登録 </a></p>
						</div>
						<div class="col-xs-2">
							<p class="text-center"><a class="btn btn-primary btn-md" id="btnRet" data-dismiss="modal">閉じる </a></p>
						</div>
						<div class="col-xs-4"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		//신규버튼 : popup & clear 
		$(document).on('click', '#btnNew', function(e) {
			$('#modal').modal('toggle');
		});
	</script>
</body>

</html>