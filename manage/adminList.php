<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/managemodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}
if ($_SESSION['auth_type'] == constant('USER')) { // if not admin 
    header("Location: ../index.php");
}
?>

<!-- ****CSS*****  -->
<style type="text/css">
    /* modal position(center)*/
    .modal {
        text-align: center;
    }

    @media screen and (min-width: 768px) {
        .modal:before {
            display: inline-block;
            vertical-align: middle;
            content: " ";
            height: 100%;
        }
    }

    /* modal popup lock */
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<title>管理者登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegAM'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateAM'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['DeleteAM'])) {
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
    if (isset($_SESSION['email_is_dupplicate']) && isset($_POST['btnRegAM'])) {
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
    if (isset($_SESSION['$user_type_undefined']) && isset($_POST['btnRegAM'])) {
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
    if (isset($_SESSION['$user_type_undefined']) && isset($_POST['btnUpdateAM'])) {
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
    if (isset($_SESSION['$user_type_undefined']) && isset($_POST['DeleteAM'])) {
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
	if (isset($_SESSION['email_is_dupplicate']) && isset($_POST['btnUpdateAM'])) {
		?>
		<div class="alert alert-danger alert-dismissible" role="alert" auto-close="7000">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php echo $_SESSION['email_is_dupplicate']; ?>
		</div>
		<?php
		unset($_SESSION['email_is_dupplicate']);
	}
	?>

    <form method="post">
        <div class="row">
            <div class="col-md-3 text-left">
                <div class="title_name">
                    <span class="text-left">管理者登録</span>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchAdminGrade">区分 : <input type="text" name="searchAdminGrade"
                            value="<?= $_POST['searchAdminGrade'] ?>" style="width: 100px;" placeholder="社員"></label>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="">社員名 : <input type="text" name="searchAdminName"
                            value="<?= $_POST['searchAdminName'] ?>" style="width: 100px;" placeholder="名前"></label>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <input type="submit" name="SearchButtonAM" value="検索">
                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
                        <input type="button" id="btnNewAL" value="新規">
                    <?php endif; ?>
                    <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
                </div>
            </div>
        </div>
    </form>
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 5%;">ID</th>

                    <th style="text-align: center; width: 13%;">社員名</th>
                    <th style="text-align: center; width: 20%;">Email</th>
                    <th style="text-align: center; width: 8%;">部署</th>
                    <th style="text-align: center; width: 8%;">区分</th>
                    <th style="text-align: center; width: 15%;">勤務時間タイプ</th>
                    <!-- <th style="text-align: center; width: 12%;">会社名</th> -->
                    <th style="text-align: center; width: 8%;">印鑑</th>
                    <th style="text-align: center; width: 8%;">権限</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admin_list)) { ?>
                    <tr>
                        <td colspan="8" align="center">
                            <?php echo $data_save_no; ?>
                        </td>
                    </tr>
                <?php } elseif (!empty($admin_list)) {
                    foreach ($admin_list as $key) {
                        ?>
                        <tr>
                            <td><a href="#"><span class="showModal">
                                        <?= $key['uid'] ?>
                                    </span></a></td>
                    
                            <td><span>
                                    <?= $key['name'] ?>
                                </span></td>
                            <td><span>
                                    <?= $key['email'] ?>
                                </span></td>
                            <td>
                                <span>
                                    <?php foreach ($codebase_list_dept as $k): ?>
                                        <?php
                                        if ($k['code'] == $key['dept']) {
                                            echo $k['name'];
                                        }
                                        ?>
                                    <?php endforeach; ?>
                                </span>
                            </td>
                            <td><span>
                                    <?= $key['grade'] ?>
                                </span></td>

                                <td><span name="genbaname">
									<?= $key['genbaname'] ?>
								</span></td>               
                            <td>
                                <span>
                                    <?php if ($key['signstamp'] == NULL): ?>
                                        印鑑無し
                                    <?php else: ?>
                                        <img width="50" src="<?= $PATH_IMAGE_STAMP . $key['signstamp'] ?>"><br>
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td><span name="usertype">
									<?php
									foreach ($USER_TYPE_TEXT as $k => $value) {
										?>
										 <?php if ($k == $key['type']) {
											  echo $value ;
										  } ?>
										<?php
									}
									?>
								</span></td>
                            <td><span>
                                    <?= $key['bigo'] ?>
                                </span></td>
                        </tr>
                        <?php
                    }
                } ?>
            </tbody>
        </table>
    </div>

    <!-- 新規 -->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            社員登録<span></span>
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="email"
                                        maxlength="100" style="text-align: left">
                                    <input type="hidden" class="form-control" name="uid" id="uid" placeholder="ID"
                                        maxlength="10" style="text-align: left">
                                </div>
                                <div class="col-xs-6">
                                    <label for="pwd">Password</label>
                                    <input type="text" class="form-control" name="pwd" id="pwd" placeholder="pwd"
                                        maxlength="20" style="text-align: left" value="1111">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="name">社員名</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="name"
                                        maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="grade">区分</label>
                                    <input type="text" class="form-control" name="grade" id="grade"
                                        placeholder="役員/管理/社員" maxlength="30" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="dept">部署</label>
                                    <select class="form-control" id="dept" name="dept">
                                        <option value="" disabled selected>選択してください。</option>
                                        <?php foreach ($codebase_list_dept as $key): ?>
                                            <option value="<?= $key["code"] ?>">
                                                <?= $key["name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
                      

                                <div class="col-xs-6">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="1000"
                                        style="text-align: left" placeholder="備考">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                            <div class="col-xs-6">
                                    <label for="signstamp_addNew">印鑑</label>
                                    <img width="50" id="signstamp_addNew">
                                    <input type="file" name="signstamp" onchange=checkFileSize(this) id="fileInput">
                                </div>

                                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
								<div class="col-xs-6">
									<div>
										<label for="user_type">権限</label><br>
										<?php $count = 0; ?>
										<?php foreach ($USER_TYPE_TEXT as $key => $value): ?>
											<input type="radio" id="user_type<?= $key ?>" name="user_type" value="<?= $key ?>"
												<?php if ($value === $USER_TYPE_TEXT[3]) {
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
                                    <input type="submit" name="btnRegAM" class="btn btn-primary" id="btnRegAM"
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
        <div class="modal" id="modal2" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            管理者編集
                            <span id="usname"></span>
                            <button class="close" data-dismiss="modal">x</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="hidden" class="form-control" name="uduid" id="uduid" placeholder="ID"
                                        maxlength="10" style="text-align: left" readonly>
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" name="udemail" id="udemail"
                                        placeholder="email" maxlength="100" style="text-align: left" readonly>
                                        <input type="hidden" id="ultype" name="ultype" value="">   
                                        <input type="hidden" id="currentEmail" name="currentEmail" value="">      
                                </div>
                                <div class="col-xs-6">
                                    <label for="pwd">Password</label>
                                    <input type="text" class="form-control" name="udpwd" id="udpwd" placeholder="パスワード"
                                        maxlength="20" style="text-align: left">
                                        <input type="checkbox" id="showPwd"> パスワード表示
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="name">社員名</label>
                                    <input type="text" class="form-control" name="udname" id="udname" placeholder="name"
                                        maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="grade">区分</label>
                                    <input type="text" class="form-control" name="udgrade" id="udgrade"
                                        placeholder="役員/管理/社員" maxlength="30" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="dept">部署</label>
                                    <select class="form-control" id="uddept" name="uddept">
                                        <option value="" disabled selected>選択してください。</option>
                                        <?php foreach ($codebase_list_dept as $key): ?>
                                            <option value="<?= $key["code"] ?>">
                                                <?= $key["name"] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="genid">勤務時間タイプ</label>
                                    <select class="form-control" id="udgenba_list" name="udgenba_list">
                                        <option value="" disabled selected>選択してください。</option>
                                        <?php
                                        foreach ($genba_list_db as $key) {
                                            ?>
                                            <option value="<?= $key["genid"] ?>">
                                                <?= $key["genbaname"] . '(' . $key["workstrtime"] . '-' . $key["workendtime"] . ')' ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
            
                                <div class="col-xs-6">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="udbigo" id="udbigo" maxlength="1000"
                                        style="text-align: left" placeholder="備考">
                                </div>

                            </div>
                            <br>
                            <div class="row">
                            <div class="col-xs-6">
                                    <label for="signstamp">印鑑</label><br>
                                    <img width="50" id="udsignstamp">
                                    <span id="udsignstamp_name"></span>
                                    <input type="hidden" name="udsignstamp_old" id="udsignstamp_old">
                                    <input type="file" name="udsignstamp_new" id="udfileInput"
                                        onchange=checkFileSize(this)>
                                </div>

                                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')): ?>
								<div class="col-xs-6">
									<div>
										<label for="uluser_type">権限</label><br>
										<?php foreach ($USER_TYPE_TEXT as $key => $value): ?>
											<input type="radio" id="uluser_type<?= $key ?>" name="uluser_type"
												value="<?= $key ?>" >
											<label for="uluser_type<?= $key ?>">
												<?= $value ?>
											</label>
										<?php endforeach; ?>
									</div>
								<?php endif ?>


							</div>

                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <input type="submit" name="btnUpdateAM" class="btn btn-primary" id="btnUpdateAM"
                                    role="button" value="編集">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="DeleteAM" class="btn btn-warning" role="button" value="削除">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // upload image  add start
    $(document).ready(function () {
        // load valid extention to element check 
        <?php $allowedTypesString = "." . implode(", .", $ALLOWED_TYPES_STAMP); ?>
        $('#udfileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
        $('#fileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
        setTimeout(hideLoadingOverlay, 1000);
        startLoading();
        modalInitSetting();

    });


    function modalInitSetting() {
		$('#showPwd').change(function () {
			if ($(this).is(':checked')) {
				$('#udpwd').attr('type', 'text');
			} else {
				$('#udpwd').attr('type', 'password');
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
                reader.onload = function (e) {
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

                reader.onload = function (e) {
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

    // New button: popup & clear 
    $(document).on('click', '#btnNewAL', function (e) {
        $('#modal').modal('toggle');
        $('label[for="signstamp"]').show();
        $('#fileInput').val('');

    });

    // Check Error
    $(document).on('click', '#btnRegAM', function (e) {
        var Uid = $("#uid").val();
        var Pwd = $("#pwd").val();
        var Name = $("#name").val();
        var Grade = $("#grade").val();
        var Email = $("#email").val();
        var Dept = $("#dept").val();
        var Companyid = $("#companyid").val();
        var letters = /^[A-Za-z]+$/;



        if (Pwd == "") {
            alert("<?php echo $manage_pwd_empty; ?>");
            e.preventDefault();
            $("#pwd").focus();
            return true;
        }

        if (Name == "") {
            alert("<?php echo $manage_name_empty; ?>");
            e.preventDefault();
            $("#name").focus();
            return true;
        }

        if (Grade == "") {
            alert("<?php echo $manage_grade_empty; ?>");
            e.preventDefault();
            $("#grade").focus();
            return true;
        }

        if (Email == "") {
            alert("<?php echo $manage_email_empty; ?>");
            e.preventDefault();
            $("#email").focus();
            return true;
        }

        <?php
        if (!empty($admin_list)) {
            foreach ($admin_list as $key) {
                ?>
                if ('<?php echo $key['email'] ?>' === Email) {
                    alert("<?php echo $email_is_dupplicate; ?>");
                    $("#email").focus();
                    e.preventDefault();
                    return;
                }
                <?php
            }
        }
        ?>

        if (Dept == "") {
            alert("<?php echo $manage_dept_empty; ?>");
            e.preventDefault();
            $("#dept").focus();
            return true;
        }

        if (Companyid == "") {
            alert("<?php echo $manage_companyid_empty; ?>");
            e.preventDefault();
            $("#companyid").focus();
            return true;
        }
    });

    // Funtion for click day of week
    $(document).on('click', '.showModal', function () {
        $('#modal2').modal('toggle');
        var Uid = $(this).text().trim();
        $('label[for="signstamp"]').show();
        $('#udfileInput').val('');
        $('#udsignstamp_name').text('');
        
		$('#showPwd').prop('checked', false);
		$('#udpwd').attr('type', 'password');

        var authType = '<?php echo $_SESSION['auth_type']; ?>';
		var ulemailInput = $('#udemail');
		if (authType === '<?php echo constant('ADMIN') ?>' || authType === '<?php echo constant('ADMINISTRATOR') ?>' || authType === '<?php echo constant('MAIN_ADMIN') ?>') {
			ulemailInput.removeAttr('readonly');
		} else {
			ulemailInput.attr('readonly', 'readonly');
		}

        <?php
        foreach ($admin_list as $key) {
            ?>
            if ('<?php echo $key['uid'] ?>' === Uid) {
                $("#usname").text('<?php echo $key['uid'] ?>');
                $("#uduid").text($('[name="uduid"]').val("<?php echo $key['uid'] ?>"));
                $("#udpwd").text($('[name="udpwd"]').val("<?php echo $key['pwd'] ?>"));
                $("#udname").text($('[name="udname"]').val("<?php echo $key['name'] ?>"));
                $("#udgrade").text($('[name="udgrade"]').val("<?php echo $key['grade'] ?>"));
                $("#udemail").text($('[name="udemail"]').val("<?php echo $key['email'] ?>"));
                $("#currentEmail").text($('[name="currentEmail"]').val("<?php echo $key['email'] ?>"));
       
                $("#uddept").val("<?php echo $key['dept'] ?>");
                $("#udcompanyid").val("<?php echo $key['companyid']; ?>");
                var genbaId = "<?php echo $key['genid']; ?>";
                var options = $("#udgenba_list option");
                options.each(function (index, option) {
                    console.log(option.value);
                    if (option.value == genbaId) {
                        $(option).prop("selected", true);
                        return false;
                    }
                });

                if (genbaId !== '' && $("#udgenba_list option:selected").val() !== genbaId) {
                    $("#udgenba_list option").eq(0).prop("selected", true);
                }
                var udsignstamp_old = $("input[name=udsignstamp_old]:hidden");
                udsignstamp_old.val("<?php echo $key['signstamp'] ?>");
                var udsignstamp_old = udsignstamp_old.val();
                var imagePath = "<?= $PATH_IMAGE_STAMP . $key['signstamp'] ?>";
                $("#udsignstamp").attr("src", imagePath);
                $("#udbigo").text($('[name="udbigo"]').val("<?php echo $key['bigo'] ?>"));

			    //  get current usertype 
                var type = $("input[name=ultype]:hidden");
					type.val("<?php echo $key['type'] ?>");
                 var ultypeValue = $('#ultype').val();
						$('input[name="uluser_type"]').each(function () {
							if ($(this).val() === ultypeValue) {
								$(this).prop('checked', true);
							}
						});


            }
            <?php
        }
        ?>
    });

    // Check Error
    $(document).on('click', '#btnUpdateAM', function (e) {
        var Pwd = $("#udpwd").val();
        var Name = $("#udname").val();
        var Grade = $("#udgrade").val();
        var Email = $("#udemail").val();
        var Dept = $("#uddept").val();
        var Companyid = $("#udcompanyid").val();

        if (Pwd == "") {
            alert("<?php echo $manage_pwd_empty; ?>");
            e.preventDefault();
            $("#udpwd").focus();
            return true;
        }

        if (Name == "") {
            alert("<?php echo $manage_name_empty; ?>");
            e.preventDefault();
            $("#udname").focus();
            return true;
        }

        if (Grade == "") {
            alert("<?php echo $manage_grade_empty; ?>");
            e.preventDefault();
            $("#udgrade").focus();
            return true;
        }

        if (Email == "") {
            alert("<?php echo $manage_email_empty; ?>");
            e.preventDefault();
            $("#udemail").focus();
            return true;
        }

        if (Dept == "") {
            alert("<?php echo $manage_dept_empty; ?>");
            e.preventDefault();
            $("#uddept").focus();
            return true;
        }

        if (Companyid == "") {
            alert("<?php echo $manage_companyid_empty; ?>");
            e.preventDefault();
            $("#udcompanyid").focus();
            return true;
        }
    });
    // loading UX
    function showLoadingOverlay() {
        const overlay = document.getElementById("overlay");
        overlay.style.display = "block";
        document.body.style.pointerEvents = "none";
    }

    function hideLoadingOverlay() {
        const overlay = document.getElementById("overlay");
        overlay.style.display = "none";
        document.body.style.pointerEvents = "auto";
    }

    showLoadingOverlay();

    function startLoading() {
        NProgress.start();
        setTimeout(function () {
            NProgress.done();
        }, 500);
    }
</script>
<?php include('../inc/footer.php'); ?>