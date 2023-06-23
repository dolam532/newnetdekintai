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
					foreach (@$userlist_list as $user) {
					?>
						<tr>
							<td><a href="#"><span class="showModal"><?= $user['uid'] ?></span></a></td>
							<td><span name="pwd">********</span></td>
							<td><span name="name"><?= $user['name'] ?></span></td>
							<td><span name="email"><?= $user['email'] ?></span></td>
							<td><span name="dept"><?= $user['dept'] ?></span></td>
							<td><span name="grade"><?= $user['grade'] ?></span></td>
							<td><span name="genbaname"><?= $user['genbaname'] ?></span></td>
							<td><span name="bigo"><?= $user['bigo'] ?></span>
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
								<input type="email" class="form-control" id="email" name="email" placeholder="email" required="required" maxlength="100" style="text-align: left">
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

<div class="row">
	<div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<form method="post">
				<div class="modal-content">
					<div class="modal-header">社員登録(<span id="ulsname"></span>)
						<button class="close" data-dismiss="modal">&times;</button>
					</div>

					<div class="modal-body">
						<div class="row">
							<div class="col-xs-3">
								<label for="uid">ID</label>
								<input type="text" class="form-control" id="uluid" name="uluid" style="text-align: left" readonly>
								<input type="hidden" id="ulcompanyid" name="ulcompanyid" value="">
								<input type="hidden" id="ultype" name="ultype" value="">
							</div>
							<div class="col-xs-3">
								<label for="pwd">PASSWORD</label>
								<input type="password" class="form-control" id="ulpwd" name="ulpwd" placeholder="pwd" required="required" maxlength="20" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="name">社員名</label>
								<input type="text" class="form-control" id="ulname" name="ulname" placeholder="name" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="grade">区分</label>
								<input type="text" class="form-control" id="ulgrade" name="ulgrade" placeholder="役員/管理/社員" required="required" maxlength="30" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="email">email</label>
								<input type="email" class="form-control" id="ulemail" name="ulemail" placeholder="email" required="required" maxlength="100" style="text-align: left">
							</div>
							<div class="col-xs-6">
								<label for="dept">部署</label>
								<input type="text" class="form-control" id="uldept" name="uldept" placeholder="開発部" maxlength="50" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="bigo">備考</label>
								<input type="text" class="form-control" id="ulbigo" name="ulbigo" maxlength="1000" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="inymd">入社日</label>
								<input type="text" class="form-control" id="ulinymd" name="ulinymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="outymd">退社日</label>
								<input type="text" class="form-control" id="uloutymd" name="uloutymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-xs-6">
								<label for="genid">現場</label>
								<select class="form-control" id="ulgenba_list" name="ulgenba_list">
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
								<input type="text" class="form-control" id="ulgenstrymd" name="ulgenstrymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
							<div class="col-xs-3">
								<label for="genendymd">契約期間(T)</label>
								<input type="text" class="form-control" id="ulgenendymd" name="ulgenendymd" maxlength="10" placeholder="" style="text-align: left">
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center">
						<div class="col-xs-4"></div>
						<div class="col-xs-2">
							<p class="text-center">
								<input type="submit" name="UpdateUserList" class="btn btn-primary btn-md" id="UpdatebtnReg" role="button" value="登録">
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

	//그리드에서 사원ID클릭(수정) : popup & 내용표시 
	$(document).on('click', '.showModal', function() {
		$('#modal2').modal('toggle');
		var Uid = $(this).text();
		<?php
		if (!empty($userlist_list)) {
			foreach ($userlist_list as $key) {
		?>
				if ('<?php echo $key['uid'] ?>' == Uid) {
					$("#ulsname").text('<?php echo $key['name'] ?>');
					$("#uluid").text($('[name="uluid"]').val("<?php echo $key['uid'] ?>"));
					var companyid = $("input[name=ulcompanyid]:hidden");
					companyid.val("<?php echo $key['companyid'] ?>");
					var companyid = companyid.val();
					var type = $("input[name=ultype]:hidden");
					type.val("<?php echo $key['type'] ?>");
					var type = type.val();
					$("#ulpwd").text($('[name="ulpwd"]').val("<?php echo $key['pwd'] ?>"));
					$("#ulname").text($('[name="ulname"]').val("<?php echo $key['name'] ?>"));
					$("#ulgrade").text($('[name="ulgrade"]').val("<?php echo $key['grade'] ?>"));
					$("#ulemail").text($('[name="ulemail"]').val("<?php echo $key['email'] ?>"));
					$("#uldept").text($('[name="uldept"]').val("<?php echo $key['dept'] ?>"));
					$("#ulbigo").text($('[name="ulbigo"]').val("<?php echo $key['bigo'] ?>"));
					$("#ulinymd").text($('[name="ulinymd"]').val("<?php echo $key['inymd'] ?>"));
					$("#uloutymd").text($('[name="uloutymd"]').val("<?php echo $key['outymd'] ?>"));
					$("#ulinymd").text($('[name="ulinymd"]').val("<?php echo $key['inymd'] ?>"));
					$("#ulgenba_list option:selected").text("<?php echo $key["genbaname"] . $key["worktime1"] . $key["worktime2"] ?>").val("<?php echo $key['genid'] ?>");
					$("#ulgenstrymd").text($('[name="ulgenstrymd"]').val("<?php echo $key['genstrymd'] ?>"));
					$("#ulgenendymd").text($('[name="ulgenendymd"]').val("<?php echo $key['genendymd'] ?>"));
				}
		<?php
			}
		}
		?>
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
				alert("<?php echo $user_id_same; ?>");
				$("#uid").focus(); //입력 포커스 이동
				e.preventDefault();
				return; //함수 종료
			}
		<?php endforeach; ?>

		if (uid == "") {
			alert("<?php echo $user_id_empty; ?>");
			$("#uid").focus(); //입력 포커스 이동
			e.preventDefault();
			return; //함수 종료
		}
		if (pwd == "") {
			alert("<?php echo $user_pwd_empty; ?>");
			$("#pwd").focus();
			e.preventDefault();
			return;
		}
		if (name == "") {
			alert("<?php echo $user_name_empty; ?>");
			$("#name").focus();
			e.preventDefault();
			return;
		}
		if (email == "") {
			alert("<?php echo $user_email_empty; ?>");
			$("#email").focus();
			return;
		}
		if (dept == "") {
			alert("<?php echo $user_dept_empty; ?>");
			$("#dept").focus();
			e.preventDefault();
			return;
		}
		if (grade == "") {
			alert("<?php echo $user_grade_empty; ?>");
			$("#grade").focus();
			e.preventDefault();
			return;
		}
	});

	// Check Error
	$(document).on('click', '#UpdatebtnReg', function(e) {
		var pwd = $("#ulpwd").val();
		var name = $("#ulname").val();
		var email = $("#ulemail").val();
		var dept = $("#uldept").val();
		var grade = $("#ulgrade").val();

		if (pwd == "") {
			alert("<?php echo $user_pwd_empty; ?>");
			$("#ulpwd").focus();
			e.preventDefault();
			return;
		}
		if (name == "") {
			alert("<?php echo $user_name_empty; ?>");
			$("#ulname").focus();
			e.preventDefault();
			return;
		}
		if (email == "") {
			alert("<?php echo $user_email_empty; ?>");
			$("#ulemail").focus();
			return;
		}
		if (dept == "") {
			alert("<?php echo $user_dept_empty; ?>");
			$("#uldept").focus();
			e.preventDefault();
			return;
		}
		if (grade == "") {
			alert("<?php echo $user_grade_empty; ?>");
			$("#ulgrade").focus();
			e.preventDefault();
			return;
		}
	});
</script>
<?php include('../inc/footer.php'); ?>