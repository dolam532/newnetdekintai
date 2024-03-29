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

#edituser {
    width: 6ch;
}
</style>
<title>社員登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container">
    <?php
	if (isset($_SESSION['save_success']) && isset($_POST['SaveUserList'])) {
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
	if (isset($_SESSION['update_success']) && isset($_POST['UpdateUserList'])) {
		?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['update_success']; ?>
    </div>
    <?php
		unset($_SESSION['update_success']);
	}
	?>
    <?php
	if (isset($_SESSION['delete_success']) && isset($_POST['btnDelUserList'])) {
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
	if (isset($_SESSION['email_is_dupplicate']) && isset($_POST['SaveUserList'])) {
		?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['email_is_dupplicate']; ?>
    </div>
    <?php
		unset($_SESSION['email_is_dupplicate']);
	}
	?>

    <?php
	if (isset($_SESSION['$user_type_undefined']) && isset($_POST['SaveUserList'])) {
		?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['$user_type_undefined']; ?>
    </div>
    <?php
		unset($_SESSION['$user_type_undefined']);
	}
	?>


    <?php
	if (isset($_SESSION['$user_type_undefined']) && isset($_POST['UpdateUserList'])) {
		?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['$user_type_undefined']; ?>
    </div>
    <?php
		unset($_SESSION['$user_type_undefined']);
	}
	?>

    <?php
	if (isset($_SESSION['email_is_dupplicate']) && isset($_POST['UpdateUserList'])) {
		?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="7000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['email_is_dupplicate']; ?>
    </div>
    <?php
		unset($_SESSION['email_is_dupplicate']);
	}
	?>



    <div class="row">
        <div class="col-md-2 text-left">
            <div class="title_name">
                <span class="text-left">社員登録</span>
            </div>
        </div>
        <?php if ($_SESSION['auth_type'] == constant('USER')): ?>
        <div class="col-md-10 text-right">
            <div class="title_btn">
                <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
            </div>
        </div>
        <?php else: ?>
        <form method="post">
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label>区分 : <input type="text" id="searchGrade" name="searchGrade"
                            value="<?= $_POST['searchGrade'] ?>" style="width: 100px;"></label>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label>社員名 : <input type="text" id="searchName" name="searchName"
                            value="<?= $_POST['searchName'] ?>" style="width: 100px;"></label>
                </div>
            </div>
            <div class="col-md-4 text-right">
                <div class="title_btn">
                    <input type="submit" id="ClearButton" name="ClearButton" value="クリア">
                </div>
                <div class="title_btn">
                    <input type="submit" name="SearchButton" value="検索">
                </div>
                <div class="title_btn">
                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')): ?>
                    <input type="button" id="btnNew" value="新規">
                    <?php endif; ?>
                </div>
                <div class="title_btn">
                    <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>

    <!-- ビュー -->
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                    <th style="text-align: center; width: 10%;">会社名</th>
                    <?php endif; ?>
                    <th style="text-align: center; width: 5%;">ID</th>
                    <th style="text-align: center; width: 13%;">社員名</th>
                    <th style="text-align: center; width: 20%;">Email</th>
                    <th style="text-align: center; width: 8%;">部署</th>
                    <th style="text-align: center; width: 8%;">区分</th>
                    <th style="text-align: center; width: 15%;">勤務時間タイプ</th>
                    <th style="text-align: center; width: 10%;">印鑑</th>
                    <th style="text-align: center; width: 8%;">権限</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($userlist_list)) { ?>
                <tr>
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                    <td colspan="9" align="center">
                        <?php echo $data_save_no; ?>
                    </td>
                    <?php else: ?>
                    <td colspan="8" align="center">
                        <?php echo $data_save_no; ?>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php } elseif (!empty($userlist_list)) {
					foreach (@$userlist_list as $user) {
						?>
                <tr>
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                    <td>
                        <span>
                            <?= $user['companyname'] ?>
                        </span>
                    </td>
                    <?php endif; ?>
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                    <td>
                        <?php if ($user['companyid'] == constant('MAIN_COMPANY_ID')): ?>
                        <a href="#">
                            <span class="showModal">
                                <?= $user['uid'] ?>
                            </span>
                        </a>
                        <?php else: ?>
                        <span>
                            <?= $user['uid'] ?>
                        </span>
                        <?php endif; ?>
                    </td>
                    <?php else: ?>
                    <td>
                        <a href="#">
                            <span class="showModal">
                                <?= $user['uid'] ?>
                            </span>
                        </a>
                    </td>
                    <?php endif; ?>

                    <td><span name="name">
                            <?= $user['name'] ?>
                        </span></td>
                    <td><span name="email">
                            <?= $user['email'] ?>
                        </span></td>
                    <td><span name="dept">
                            <?= $user['code_name'] ?>
                        </span></td>
                    <td><span name="grade">
                            <?= $user['grade'] ?>
                        </span></td>
                    <td><span name="genbaname">
                            <?= $user['genbaname'] ?>
                        </span></td>
                    <td>
                        <span name="signstamp">
                            <?php if ($user['signstamp'] == NULL): ?>
                            印鑑無し
                            <?php else: ?>
                            <img width="50" src="<?= $PATH_IMAGE_STAMP . $user['signstamp'] ?>"><br>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td><span name="usertype">
                            <?php
									foreach ($USER_TYPE_TEXT as $key => $value) {
										?>
                            <?php if ($key == $user['type']) {
											echo $value;
										} ?>
                            <?php
									}
									?>
                        </span></td>

                    <td><span name="bigo">
                            <?= $user['bigo'] ?>
                        </span></td>
                </tr>
                <?php }
				} ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 新規 -->
<div class="row">
    <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">社員登録<span id="sname"></span>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@"
                                    required="required" maxlength="100" style="text-align: left">
                                <input type="hidden" name="companyid" value="<?= $_SESSION['auth_companyid'] ?>">
                                <input type="hidden" name="type" value="<?= $_SESSION['auth_type'] ?>">
                            </div>
                            <div class="col-xs-6">
                                <label for="pwd">Password</label>
                                <input type="text" class="form-control" id="pwd" name="pwd" placeholder="パスワード"
                                    required="required" maxlength="20" style="text-align: left" value="1111" readonly>
                            </div>


                        </div>
                        <br>
                        <div class="row">

                            <div class="col-xs-4">
                                <label for="dept">部署</label>
                                <select class="form-control" id="dept" name="dept">
                                    <option value="" disabled selected>選択してください。</option>
                                    <?php foreach ($codebase_list as $key): ?>
                                    <option value="<?= $key["code"] ?>">
                                        <?= $key["name"] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-xs-4">
                                <label for="name">社員名</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="日本 太郎"
                                    required="required" maxlength="100" style="text-align: left">
                            </div>
                            <div class="col-xs-4">
                                <label for="grade">区分</label>
                                <input type="text" class="form-control" id="grade" name="grade" placeholder="役員/管理職/社員"
                                    required="required" maxlength="30" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="bigo">備考</label>
                                <input type="text" class="form-control" name="bigo" maxlength="1000"
                                    style="text-align: left" placeholder="備考">
                            </div>
                            <div class="col-xs-3">
                                <label for="inymd">入社日</label>
                                <input type="text" class="form-control" id="inymd" name="inymd" maxlength="10"
                                    placeholder="日付" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="outymd">退社日</label>
                                <input type="text" class="form-control" id="outymd" name="outymd" maxlength="10"
                                    placeholder="日付" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="genid">勤務時間タイプ</label>
                                <select class="form-control" id="genba_list" name="genba_list">
                                    <option value="" disabled selected>選択してください。</option>
                                    <?php
									foreach ($genba_list_db as $key) {
										?>
                                    <option
                                        value="<?= $key["genid"] . ',' . $key["genbaname"] . ',' . $key["workstrtime"] . ',' . $key["workendtime"] ?>">
                                        <?= $key["genbaname"] . '(' . $key["workstrtime"] . '-' . $key["workendtime"] . ')' ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label for="genstrymd">契約期間(F)</label>
                                <input type="text" class="form-control" id="genstrymd" name="genstrymd" maxlength="10"
                                    placeholder="日付" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="genendymd">契約期間(T)</label>
                                <input type="text" class="form-control" id="genendymd" name="genendymd" maxlength="10"
                                    placeholder="日付" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="signstamp_addNew">印鑑</label>
                                <span style="font-size:smaller; color:red;"> (印鑑はpngタイプを選択してください。)</span>
                                <img width="50" id="signstamp_addNew">
                                <input type="file" name="signstamp" id="fileInput" onchange=checkFileSize(this)>
                            </div>
                            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                            <div class="col-xs-6">
                                <div>
                                    <label for="user_type">権限</label><br>
                                    <?php $count = 0; ?>
                                    <?php foreach ($USER_TYPE_TEXT as $key => $value): ?>
                                    <input type="radio" id="user_type<?= $key ?>" name="user_type" value="<?= $key ?>" <?php if ($value === $USER_TYPE_TEXT[1]) {
													echo 'checked="checked"';
													$count++;
												} ?>>
                                    <label for="user_type<?= $key ?>">
                                        <?= $value ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif ?>

                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="SaveUserList" class="btn btn-primary btn-md" id="btnReg"
                                    role="button" value="登録">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-default" data-dismiss="modal"
                                id="modalClose">閉じる</button>
                        </div>
                        <div class="col-xs-4"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 編集 -->
<div class="row">
    <div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">社員編集
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="email">Email</label>

                                <input type="email" class="form-control" id="ulemail" name="ulemail"
                                    placeholder="email@" required="required" maxlength="100" style="text-align: left"
                                    readonly>
                                <input type="hidden" id="uluid" name="uluid" value="">
                                <input type="hidden" id="currentEmail" name="currentEmail" value="">
                                <input type="hidden" id="ulcompanyid" name="ulcompanyid" value="">
                                <input type="hidden" id="ultype" name="ultype" value="">
                            </div>

                            <div class="col-xs-6">
                                <label for="pwd">Password</label>
                                <input type="password" class="form-control" id="ulpwd" name="ulpwd" placeholder="パスワード"
                                    required="required" maxlength="20" style="text-align: left">
                                <input type="checkbox" id="showPwd"> パスワード表示
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-4">
                                <label for="dept">部署</label>
                                <select class="form-control" id="uldept" name="uldept">
                                    <option value="" disabled selected>選択してください。</option>
                                    <?php foreach ($codebase_list as $key): ?>
                                    <option value="<?= $key["code"] ?>">
                                        <?= $key["name"] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-xs-4">
                                <label for="name">社員名</label>
                                <input type="text" class="form-control" id="ulname" name="ulname" placeholder="日本 太郎"
                                    required="required" maxlength="100" style="text-align: left">
                            </div>
                            <div class="col-xs-4">
                                <label for="grade">区分</label>
                                <input type="text" class="form-control" id="ulgrade" name="ulgrade"
                                    placeholder="役員/管理職/社員" required="required" maxlength="30" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="bigo">備考</label>
                                <input type="text" class="form-control" id="ulbigo" name="ulbigo" maxlength="1000"
                                    style="text-align: left" placeholder="備考">
                            </div>
                            <div class="col-xs-3">
                                <label for="inymd">入社日</label>
                                <input type="text" class="form-control" id="ulinymd" name="ulinymd" maxlength="10"
                                    placeholder="日付け" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="outymd">退社日</label>
                                <input type="text" class="form-control" id="uloutymd" name="uloutymd" maxlength="10"
                                    placeholder="日付け" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="genid">勤務時間タイプ</label>
                                <select class="form-control" id="ulgenba_list" name="ulgenba_list">
                                    <option value="" selected=""></option>
                                    <?php
									foreach ($genba_list_db as $key) {
										?>
                                    <option value="<?= $key["genid"] ?>">
                                        <?= $key["genbaname"] . '(' . $key["workstrtime"] . ' : ' . $key["workendtime"] . ')' ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <label for="genstrymd">契約期間(F)</label>
                                <input type="text" class="form-control" id="ulgenstrymd" name="ulgenstrymd"
                                    maxlength="10" placeholder="日付け" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="genendymd">契約期間(T)</label>
                                <input type="text" class="form-control" id="ulgenendymd" name="ulgenendymd"
                                    maxlength="10" placeholder="日付け" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-6">
                                <label for="signstamp">印鑑</label><span style="font-size:smaller; color:red;">
                                    (印鑑はpngタイプを選択してください。)</span><br>
                                <img width="50" id="udsignstamp">
                                <span id="udsignstamp_name"></span>
                                <input type="hidden" name="udsignstamp_old" id="udsignstamp_old">
                                <input type="file" name="udsignstamp_new" id="udfileInput" onchange=checkFileSize(this)>
                            </div>

                            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                            <div class="col-xs-6">
                                <div>
                                    <label for="uluser_type">権限</label><br>
                                    <?php foreach ($USER_TYPE_TEXT as $key => $value): ?>
                                    <input type="radio" id="uluser_type<?= $key ?>" name="uluser_type"
                                        value="<?= $key ?>">
                                    <label for="uluser_type<?= $key ?>">
                                        <?= $value ?>
                                    </label>
                                    <?php endforeach; ?>
                                </div>

                                <?php endif ?>


                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')): ?>
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="UpdateUserList" class="btn btn-primary btn-md"
                                        id="UpdatebtnReg" role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnDelUserList" class="btn btn-warning" id="btnDel"
                                        role="button" value="削除">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-xs-3"></div>
                            <?php else: ?>
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="UpdateUserList" class="btn btn-primary btn-md"
                                        id="UpdatebtnReg" role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-xs-4"></div>
                            <?php endif; ?>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
<script>
// upload image  add start
$(document).ready(function() {
    // load valid extention to element check 
    <?php $allowedTypesString = "." . implode(", .", $ALLOWED_TYPES_STAMP); ?>
    $('#udfileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
    $('#fileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
    setTimeout(hideLoadingOverlay, 1000);
    startLoading();
    modalInitSetting();
});

function modalInitSetting() {
    $('#showPwd').change(function() {
        if ($(this).is(':checked')) {
            $('#ulpwd').attr('type', 'text');
        } else {
            $('#ulpwd').attr('type', 'password');
        }
    });
}

// check size file upload
function checkFileSize(input) {
    if (input.files.length > 0) {
        var fileSize = input.files[0].size;
        var maxSize = <?php echo $STAMP_MAXSIZE; ?>;
        if (fileSize > maxSize) {
            alert("<?php echo $file_size_isvalid_STAMP; ?>");
            input.value = ""; // delete selected file
        }
    }

    var parentElement = input.parentNode;
    var siblings = parentElement.childNodes;
    for (var i = 0; i < siblings.length; i++) {
        var sibling = siblings[i];
        if (sibling.id && sibling.id.endsWith("_addNew")) {
            validateImage(input, true);
            return;
        }
    }
    validateImage(input, false);
}

// check valid size extention 
function validateImage(inputElement, isaddNew) {
    <?php $allowedTypesJSON = json_encode($ALLOWED_TYPES_STAMP); ?>
    var allowedExtensions = <?php echo $allowedTypesJSON; ?>;
    if (inputElement.files.length > 0) {
        const fileName = inputElement.files[0].name;
        const fileExtension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2);
        if (!allowedExtensions.includes(fileExtension.toLowerCase())) {
            alert("<?php echo $file_extension_invalid_STAMP; ?>");
            inputElement.value = ''; // delete selected file
        } else {
            // show new image 
            if (isaddNew)
                displaySelectedImageAddNew(inputElement)
            else
                displaySelectedImageChange(inputElement)
        }
    }
}

function displaySelectedImageAddNew(input) {
    if (input.files.length > 0) {
        const selectedFile = input.files[0];
        const imageElement = document.getElementById('signstamp_addNew');
        const labelElement = document.querySelector('label[for="signstamp_addNew"]');

        if (selectedFile.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imageElement.src = e.target.result;
                imageElement.alt = selectedFile.name;
                labelElement.style.display = 'none';
            };

            reader.readAsDataURL(selectedFile);
        } else {
            alert("<?php echo $file_extension_invalid_STAMP; ?>");
            input.value = '';
        }
    }
}

// change selected img
function displaySelectedImageChange(input) {
    if (input.files.length > 0) {
        const selectedFile = input.files[0];
        const imageElement = document.getElementById('udsignstamp');
        if (selectedFile.type.match('image.*')) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imageElement.src = e.target.result;
                imageElement.alt = selectedFile.name;
                document.getElementById('udsignstamp_name').innerText = selectedFile.name;
                document.getElementById('udsignstamp_name').hidden = false;
            };
            reader.readAsDataURL(selectedFile);
        } else {
            alert("<?php echo $file_extension_invalid_STAMP; ?>");
            input.value = '';
        }
    }
}

// Datepicker Calender
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

$("#ulgenstrymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});
$("#ulgenendymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});
$("#ulinymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});
$("#uloutymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

// 社員登録 POP UP
$(document).on('click', '#btnNew', function(e) {
    $('#modal').modal('toggle');
    $('label[for="signstamp"]').show();
    $('#fileInput').val('');
});

//社員編集 POP UP
$(document).on('click', '.showModal', function() {
    $('#modal2').modal('toggle');

    $('#showPwd').prop('checked', false);
    $('#ulpwd').attr('type', 'password');

    var Uid = $(this).text().trim();
    $('label[for="signstamp"]').show();
    $('#udfileInput').val('');
    $('#udsignstamp_name').text('');


    var authType = '<?php echo $_SESSION['auth_type']; ?>';
    var admin_fields = [$('#ulemail'), $('#uldept'), $('#ulname'), $('#ulgrade'), $('#ulbigo'), $('#ulbigo'), $(
        '#ulinymd'), $('#uloutymd'), $('#ulgenstrymd'), $('#ulgenstrymd'), $('#ulgenendymd')];

    if (authType === '<?php echo constant('ADMIN') ?>' || authType ===
        '<?php echo constant('ADMINISTRATOR') ?>' || authType === '<?php echo constant('MAIN_ADMIN') ?>') {
        admin_fields.forEach(function(field) {
            field.removeAttr('readonly');
        });
    } else {
        admin_fields.forEach(function(field) {
            field.attr('readonly', 'readonly');
        });
    }

    <?php
		if (!empty($userlist_list)) {
			foreach ($userlist_list as $key) {
				?>
    if ('<?php echo $key['uid'] ?>' == Uid) {
        $("#ulnametitle").text('<?php echo $key['name'] ?>');
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
        $("#currentEmail").text($('[name="currentEmail"]').val("<?php echo $key['email'] ?>"));
        $("#uldept").val("<?php echo $key['dept'] ?>");
        $("#ulbigo").text($('[name="ulbigo"]').val("<?php echo $key['bigo'] ?>"));
        $("#ulinymd").text($('[name="ulinymd"]').val("<?php echo $key['inymd'] ?>"));
        $("#uloutymd").text($('[name="uloutymd"]').val("<?php echo $key['outymd'] ?>"));
        $("#ulinymd").text($('[name="ulinymd"]').val("<?php echo $key['inymd'] ?>"));
        var genbaId = "<?php echo $key['genid']; ?>";
        var options = $("#ulgenba_list option");
        options.each(function(index, option) {
            console.log(option.value);
            if (option.value == genbaId) {
                $(option).prop("selected", true);
                return false;
            }
        });

        if (genbaId !== '' && $("#ulgenba_list option:selected").val() !== genbaId) {
            $("#ulgenba_list option").eq(0).prop("selected", true);
        }
        $("#ulgenstrymd").text($('[name="ulgenstrymd"]').val("<?php echo $key['genstrymd'] ?>"));
        $("#ulgenendymd").text($('[name="ulgenendymd"]').val("<?php echo $key['genendymd'] ?>"));
        $("#ulgenendymd").text($('[name="ulgenendymd"]').val("<?php echo $key['genendymd'] ?>"));

        //  get current usertype 
        var ultypeValue = $('#ultype').val();
        $('input[name="uluser_type"]').each(function() {
            if ($(this).val() === ultypeValue) {
                $(this).prop('checked', true);
            }
        });

        var udsignstamp_old = $("input[name=udsignstamp_old]:hidden");
        udsignstamp_old.val("<?php echo $key['signstamp'] ?>");
        var udsignstamp_old = udsignstamp_old.val();

        var imagePath = "<?= $PATH_IMAGE_STAMP . $key['signstamp'] ?>";
        $("#udsignstamp").attr("src", imagePath);
    }
    <?php
			}
		}
		?>
});

// Clear Input Tag
$(document).on('click', '#ClearButton', function(e) {
    $("#searchName").val("");
    $("#searchGrade").val("");
});

// Check Error 社員登録
$(document).on('click', '#btnReg', function(e) {
    var pwd = $("#pwd").val();
    var name = $("#name").val();
    var email = $("#email").val();
    var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    var passwordRegex = /^[A-Za-z0-9]+$/; // 半角英数字のみを許可
    var dept = $("#dept").val();
    var grade = $("#grade").val();
    var inymd = $("#inymd").val();
    var genba_list = $("#genba_list").val();

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
    if (!emailRegex.test(email)) {
        alert("<?php echo $login_email_fail; ?>");
        e.preventDefault();
        $("#email").focus();
        return false;
    }
    if (!passwordRegex.test(pwd)) {
        alert("<?php echo $login_pwd_fail; ?>");
        e.preventDefault();
        $("#pwd").focus();
        return false;
    }
    <?php
		if (!empty($userlist_list)) {
			foreach ($userlist_list as $key) {
				?>
    if ('<?php echo $key['email'] ?>' === email) {
        alert("<?php echo $email_is_dupplicate; ?>");
        $("#email").focus();
        e.preventDefault();
        return;
    }
    <?php
			}
		}
		?>
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
    if (inymd == "") {
        alert("<?php echo $user_inymd_empty; ?>");
        $("#inymd").focus();
        e.preventDefault();
        return;
    }
    if (genba_list == "") {
        alert("<?php echo $user_genba_list_empty; ?>");
        $("#genba_list").focus();
        e.preventDefault();
        return;
    }
});

// Check Error 社員編集
$(document).on('click', '#UpdatebtnReg', function(e) {
    var pwd = $("#ulpwd").val();
    var name = $("#ulname").val();
    var email = $("#ulemail").val();
    var emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    var passwordRegex = /^[A-Za-z0-9]+$/; // 半角英数字のみを許可
    var dept = $("#uldept").val();
    var grade = $("#ulgrade").val();
    var inymd = $("#ulinymd").val();
    var genba_list = $("#ulgenba_list").val();

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
    if (!emailRegex.test(email)) {
        alert("<?php echo $login_email_fail; ?>");
        e.preventDefault();
        $("#ulemail").focus();
        return false;
    }
    if (!passwordRegex.test(pwd)) {
        alert("<?php echo $$login_pwd_fail; ?>");
        e.preventDefault();
        $("#ulpwd").focus();
        return false;
    }
    <?php
		if (!empty($admin_list)) {
			foreach ($admin_list as $key) {
				?>
    if ('<?php echo $key['email'] ?>' === email) {
        alert("<?php echo $email_is_dupplicate; ?>");
        $("#ulemail").focus();
        e.preventDefault();
        return;
    }
    <?php
			}
		}
		?>
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
    if (inymd == "") {
        alert("<?php echo $user_inymd_empty; ?>");
        $("#ulinymd").focus();
        e.preventDefault();
        return;
    }
    if (genba_list == "") {
        alert("<?php echo $user_genba_list_empty; ?>");
        $("#ulgenba_list").focus();
        e.preventDefault();
        return;
    }
});
</script>
<?php include('../inc/footer.php'); ?>