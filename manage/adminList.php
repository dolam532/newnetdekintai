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
    <form method="post">
        <div class="row">
            <div class="col-md-3 text-left">
                <div class="title_name">
                    <span class="text-left">管理者登録</span>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchAdminGrade">区分 : <input type="text" name="searchAdminGrade" value="<?= $_POST['searchAdminGrade'] ?>" style="width: 100px;" placeholder="社員"></label>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchAdminName">会社名 : <input type="text" name="searchAdminName" value="<?= $_POST['searchAdminName'] ?>" style="width: 100px;" placeholder="〇〇会社"></label>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <input type="submit" name="SearchButtonAM" value="検索">&nbsp;
                    <input type="button" id="btnNewCL" value="新規">
                </div>
            </div>
        </div>
    </form>
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 8%;">ID</th>
                    <th style="text-align: center; width: 8%;">PASSWORD</th>
                    <th style="text-align: center; width: 10%;">社員名</th>
                    <th style="text-align: center; width: 12%;">Email</th>
                    <th style="text-align: center; width: 10%;">部署</th>
                    <th style="text-align: center; width: 10%;">区分</th>
                    <th style="text-align: center; width: 12%;">会社名</th>
                    <th style="text-align: center; width: 8%;">印鑑</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($admin_list)) { ?>
                    <tr>
                        <td colspan="8" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($admin_list)) {
                    foreach ($admin_list as $key) {
                    ?>
                        <tr>
                            <td><a href="#"><span class="showModal"><?= $key['uid'] ?></span></a></td>
                            <td><span><?= $key['pwd'] ?></span></td>
                            <td><span><?= $key['name'] ?></span></td>
                            <td><span><?= $key['email'] ?></span></td>
                            <td><span><?= $key['dept'] ?></span></td>
                            <td><span><?= $key['grade'] ?></span></td>
                            <td><span><?= $key['companyname'] ?></span></td>
                            <td>
                                <span>
                                    <?php if ($key['signstamp'] == NULL) : ?>
                                        印鑑無し
                                    <?php else : ?>
                                        <img width="50" src="<?= "../assets/uploads/" . $key['signstamp'] ?>"><br>
                                        <?= $key['signstamp'] ?>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td><span><?= $key['bigo'] ?></span></td>
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
                            社員登録(<span>New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="uid">ID</label>
                                    <input type="text" class="form-control" name="uid" id="uid" placeholder="ID" maxlength="10" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="pwd">PASSWORD</label>
                                    <input type="text" class="form-control" name="pwd" id="pwd" placeholder="pwd" maxlength="20" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="name">社員名</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="name" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="grade">区分</label>
                                    <input type="text" class="form-control" name="grade" id="grade" placeholder="役員/管理/社員" maxlength="30" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="email">email</label>
                                    <input type="text" class="form-control" name="email" id="email" placeholder="email" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-6">
                                    <label for="dept">部署</label>
                                    <input type="text" class="form-control" name="dept" id="dept" placeholder="開発部" maxlength="50" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="companyid">会社名</label>
                                    <select class="form-control" name="companyid" id="companyid">
                                        <option value="" selected="">会社名を選択してください。</option>
                                        <?php foreach ($company_list_select as $value) { ?>
                                            <option value="<?= $value['companyid']  ?>" <?php if ($value['companyid'] == $_POST['companyid']) {
                                                                                            echo ' selected="selected"';
                                                                                        } ?>>
                                                <?= $value['companyname'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <label for="signstamp">印鑑</label>
                                    <input type="file" name="signstamp" accept="image/*" id="fileInput">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="1000" style="text-align: left" placeholder="備考">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegAM" class="btn btn-primary" id="btnRegAM" role="button" value="登録">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
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
                            社員編集
                            (<span id="usname"></span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="uid">ID</label>
                                    <input type="text" class="form-control" name="uduid" id="uduid" placeholder="ID" maxlength="10" style="text-align: left" readonly>
                                </div>
                                <div class="col-xs-3">
                                    <label for="pwd">PASSWORD</label>
                                    <input type="text" class="form-control" name="udpwd" id="udpwd" placeholder="pwd" maxlength="20" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="name">社員名</label>
                                    <input type="text" class="form-control" name="udname" id="udname" placeholder="name" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="grade">区分</label>
                                    <input type="text" class="form-control" name="udgrade" id="udgrade" placeholder="役員/管理/社員" maxlength="30" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="email">email</label>
                                    <input type="text" class="form-control" name="udemail" id="udemail" placeholder="email" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-6">
                                    <label for="dept">部署</label>
                                    <input type="text" class="form-control" name="uddept" id="uddept" placeholder="開発部" maxlength="50" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="companyid">会社名</label>
                                    <select class="form-control" name="udcompanyid" id="udcompanyid">
                                        <?php foreach ($company_list_select as $value) { ?>
                                            <option value="<?= $value['companyid']  ?>" <?php if ($value['companyid'] == $_POST['companyid']) {
                                                                                            echo ' selected="selected"';
                                                                                        } ?>>
                                                <?= $value['companyname'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="udbigo" id="udbigo" maxlength="1000" style="text-align: left" placeholder="備考">
                                </div>
                                <div class="col-xs-6">
                                    <label for="signstamp">印鑑</label><br>
                                    <img width="50" id="udsignstamp" alt="印鑑無し">
                                    <span id="udsignstamp_name"></span>
                                    <input type="hidden" name="udsignstamp_old" id="udsignstamp_old">
                                    <input type="file" name="udsignstamp_new" id="udfileInput" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <input type="submit" name="btnUpdateAM" class="btn btn-primary" id="btnUpdateAM" role="button" value="編集">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="DeleteAM" class="btn btn-warning" role="button" value="削除">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
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
    // New button: popup & clear 
    $(document).on('click', '#btnNewCL', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegAM', function(e) {
        var Uid = $("#uid").val();
        var Pwd = $("#pwd").val();
        var Name = $("#name").val();
        var Grade = $("#grade").val();
        var Email = $("#email").val();
        var Dept = $("#dept").val();
        var Companyid = $("#companyid").val();
        var letters = /^[A-Za-z]+$/;

        if (Uid == "") {
            alert("<?php echo $manage_id_empty; ?>");
            e.preventDefault();
            $("#uid").focus();
            return true;
        }

        if (!Uid.match(letters)) {
            alert("<?php echo $manage_id_alphabet; ?>");
            e.preventDefault();
            $("#uid").focus();
            return true;
        }

        <?php
        if (!empty($admin_list)) {
            foreach ($admin_list as $key) {
        ?>
                if ('<?php echo $key['uid'] ?>' == Uid) {
                    alert("<?php echo $manage_Uid_have; ?>");
                    $("#uid").focus();
                    return false;
                }
        <?php
            }
        }
        ?>

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
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var Uid = $(this).text();

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
                $("#uddept").text($('[name="uddept"]').val("<?php echo $key['dept'] ?>"));
                $("#udcompanyid").val("<?php echo $key['companyid']; ?>");
                
                var udsignstamp_old = $("input[name=udsignstamp_old]:hidden");
                udsignstamp_old.val("<?php echo $key['signstamp'] ?>");
                var udsignstamp_old = udsignstamp_old.val();
                $("#udsignstamp_name").text('<?php echo $key['signstamp'] ?>');
                var imagePath = "../assets/uploads/<?php echo $key['signstamp']; ?>";
                $("#udsignstamp").attr("src", imagePath);
                
                $("#udbigo").text($('[name="udbigo"]').val("<?php echo $key['bigo'] ?>"));
            }
        <?php
        }
        ?>
    });

    // Check Error
    $(document).on('click', '#btnUpdateAM', function(e) {
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

    $('#udfileInput').on('change', function() {
        var maxFileSize = 2 * 1024 * 1024;
        var fileInput = this;
        var fileSize = fileInput.files[0].size;
        if (fileSize > maxFileSize) {
            alert("<?php echo $image_size_error; ?>");
            fileInput.value = '';
            e.preventDefault();
            $("#udfileInput").focus();
            return true;
        }
    });

    $('#fileInput').on('change', function() {
        var maxFileSize = 2 * 1024 * 1024;
        var fileInput = this;
        var fileSize = fileInput.files[0].size;
        if (fileSize > maxFileSize) {
            alert("<?php echo $image_size_error; ?>");
            fileInput.value = '';
            e.preventDefault();
            $("#fileInput").focus();
            return true;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>