<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../model/usermodel.php');
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
	<div class="row">
		<div class="col-md-3 text-left">
			<div class="title_name">
				<span class="text-left">社員登録</span>
			</div>
		</div>
		<form method="post">
			<div class="col-md-3 text-left">
				<div class="title_condition">
					<label>区分 : <input type="text" id="searchGrade" name="searchGrade" value="<?= $_POST['searchGrade'] ?>" style="width: 100px;"></label>
				</div>
			</div>
			<div class="col-md-3 text-left">
				<div class="title_condition">
					<label>社員名 : <input type="text" id="searchName" name="searchName" value="<?= $_POST['searchName'] ?>" style="width: 100px;"></label>
				</div>
			</div>
			<div class="col-md-3 text-right">
				<div class="title_btn">
					<input type="submit" id="ClearButton" name="ClearButton" value="クリア">&nbsp;&nbsp;&nbsp;
					<input type="submit" name="SearchButton" value="検索">&nbsp;&nbsp;&nbsp;
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
				<?php if (empty($userlist_list)) { ?>
					<tr>
						<td colspan="8" align="center">登録されたデータがありません.</td>
					</tr>
					<?php } elseif (!empty($userlist_list)) {
					foreach ($userlist_list as $user) {
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
								<input type="hidden" name="uid" value="<?= $user['uid'] ?>">
								<input type="hidden" name="companyid" value="<?= $user['companyid'] ?>">
								<input type="hidden" name="type" value="<?= $user['type'] ?>">
								<input type="hidden" name="pwd" value="<?= $user['pwd'] ?>">
								<input type="hidden" name="name" value="<?= $user['name'] ?> 一郎">
								<input type="hidden" name="email" value="<?= $user['email'] ?>">
								<input type="hidden" name="dept" value="<?= $user['dept'] ?>">
								<input type="hidden" name="grade" value="<?= $user['grade'] ?>">
								<input type="hidden" name="bigo" value="<?= $user['bigo'] ?>">
								<input type="hidden" name="genid" value="<?= $user['genid'] ?>">
								<input type="hidden" name="genbaname" value="<?= $user['genbaname'] ?>">
								<input type="hidden" name="genstrymd" value="<?= $user['genstrymd'] ?>">
								<input type="hidden" name="genendymd" value="<?= $user['genendymd'] ?>">
								<input type="hidden" name="inymd" value="<?= $user['inymd'] ?>">
								<input type="hidden" name="outymd" value="<?= $user['outymd'] ?>">
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
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">社員登録(<span id="sname">New</span>)
						<button class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-xs-3">
								<label for="uid">ID</label>
								<input type="text" class="form-control" id="uid" name="uid" placeholder="ID" required="required" maxlength="10" style="text-align: left">
								<input type="hidden" name="companyid" value="<?= constant('GANASYS_COMPANY_ID') ?>">
								<input type="hidden" name="type" value="<?= constant('GANASYS_COMPANY_TYPE') ?>">
							</div>
							<div class="col-xs-3">
								<label for="pwd">PASSWORD</label>
								<input type="password" class="form-control" id="pwd" name="pwd" placeholder="pwd" required="required" maxlength="20" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="name">社員名</label>
								<input type="text" class="form-control" id="name" name="name" placeholder="name" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="grade">区分</label>
								<input type="text" class="form-control" id="grade" name="grade" placeholder="役員/管理/社員" required="required" maxlength="30" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="email">email</label>
								<input type="text" class="form-control" id="email" name="email" placeholder="email" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="dept">部署</label>
								<input type="text" class="form-control" id="dept" name="dept" placeholder="開発部" maxlength="50" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" name="bigo" maxlength="1000" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="inymd">入社日</label>
								<input type="text" class="form-control" id="inymd" name="inymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="outymd">退社日</label>
								<input type="text" class="form-control" id="outymd" name="outymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="genid">現場</label>
								<select class="form-control" name="genba_list">
									<option value="" selected=""></option>
									<?php
									foreach ($genba_list_db as $key) {
									?>
										<option value="<?= $key["genid"] . ',' . $key["genbaname"] . ',' . $key["worktime1"] . ',' . $key["worktime2"] ?>"><?= $key["genbaname"] . $key["worktime1"] . $key["worktime2"] ?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div class="col-xs-3">
								<label for="genstrymd">契約期間(F)</label>
								<input type="text" class="form-control" id="genstrymd" name="genstrymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="genendymd">契約期間(T)</label>
								<input type="text" class="form-control" id="genendymd" name="genendymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-xs-4"></div>
						<div class="col-xs-2">
							<p class="text-center">
								<input type="submit" name="SaveUserList" class="btn btn-primary btn-md" id="btnReg" role="button" value="登録">
							</p>
						</div>
						<div class="col-xs-2">
							<p class="text-center">
								<a class="btn btn-primary btn-md" id="btnRet" data-dismiss="modal">閉じる </a>
							</p>
						</div>
						<div class="col-xs-4"></div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	//Datepeeker 설정
	$("#genstrymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});
	$("#genendymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});
	$("#inymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});
	$("#outymd").datepicker({
		changeYear: true,
		dateFormat: 'yy/mm/dd'
	});

	//신규버튼 : popup & clear 
	$(document).on('click', '#btnNew', function(e) {
		$('#modal').modal('toggle');
	});

	//신규버튼 : popup & clear 
	$(document).on('click', '#ClearButton', function(e) {
		$("#searchName").val("");
		$("#searchGrade").val("");
	});

	// Check Error
	$(document).on('click', '#btnReg', function(e) {
		var uid = $("#uid").val();
		var pwd = $("#pwd").val();
		var name = $("#name").val();
		var email = $("#email").val();
		var dept = $("#dept").val();
		var grade = $("#grade").val();

		<?php foreach ($userlist_list as $user) : ?>
			var user_uid = '<?php echo $user["uid"] ?>';
			if (uid == user_uid) {
				alert("他の社員が使用しているidです。");
				$("#uid").focus(); //입력 포커스 이동
				e.preventDefault();
				return; //함수 종료
			}
		<?php endforeach; ?>

		if (uid == "") {
			alert("IDを入力してください。");
			$("#uid").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
		}
		if (pwd == "") {
			alert("Passwordを入力してください。");
			$("#pwd").focus();
			e.preventDefault();
			return;
		}
		if (name == "") {
			alert("社員名を入力してください。.");
			$("#name").focus();
			e.preventDefault();
			return;
		}
		if (email == "") {
			alert("E-mailを入力してください。");
			$("#email").focus();
			return;
		}
		if (dept == "") {
			alert("部署を入力してください。");
			$("#dept").focus();
			e.preventDefault();
			return;
		}
		if (grade == "") {
			alert("区分を入力してください。.");
			$("#grade").focus();
			e.preventDefault();
			return;
		}
	});
</script>
<?php include('../inc/footer.php'); ?>