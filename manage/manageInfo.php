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
echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css'>";
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

    span.manageinfo_class {
        display: none;
    }
</style>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegMMI'])) {
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
    if ((isset($_SESSION['update_success']) && isset($_POST['btnRegMi'])) || (isset($_SESSION['update_success']) && isset($_POST['btnUpdateMMI']))) {
    ?>
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['update_success']; ?>
        </div>
    <?php
        unset($_SESSION['update_success']);
    }
    ?>
    <title>
        <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
            会社情報登録
        <?php else : ?>
            会社情報編集
        <?php endif; ?>
    </title>
    <div class="row">
        <div class="col-md-8 text-left">
            <div class="title_name">
                <span class="text-left">
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                        会社情報登録
                    <?php else : ?>
                        会社情報編集
                    <?php endif; ?>
                </span>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div class="title_btn">
                <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                    <input type="button" id="btnNewMI" value="新規">
                <?php endif; ?>
                <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
            </div>
        </div>
    </div>

    <!-- Show data MAIN_ADMIN -->
    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
        <div class="form-group" style="margin-top:10px;">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 10%;">Company ID</th>
                        <th style="text-align: center; width: 25%;">Company Name</th>
                        <th style="text-align: center; width: 15%;">締切（月）</th>
                        <th style="text-align: center; width: 15%;">締切（日）</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($manageinfo_list)) { ?>
                        <tr>
                            <td colspan="8" align="center"><?php echo $data_save_no; ?></td>
                        </tr>
                        <?php } elseif (!empty($manageinfo_list)) {
                        foreach ($manageinfo_list as $key) {
                        ?>
                            <tr>
                                <td><span><?= $key['companyid'] ?></span></td>
                                <td>
                                    <span>
                                        <a href="#">
                                            <span class="showModal"><?= $key['companyname'] ?><span class="manageinfo_class"><?= ',' . $key['companyid'] ?></span></span>
                                        </a>
                                    </span>
                                </td>
                                <td><span><?= $key['magamym'] ?></span></td>
                                <td><span><?= $key['magamymd'] ?></span></td>
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
                    <form method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                会社情報登録(<span>New</span>)
                                <button class="close" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="companycode">Company ID</label>
                                        <input type="text" class="form-control" name="companyid" id="companyid" value="<?= $new_companyID_mi ?>" maxlength="10" style="text-align: left" readonly>
                                    </div>
                                    <div class="col-xs-9">
                                        <label for="companyname">Company Name</label>
                                        <input type="text" class="form-control" name="companyname" id="companyname" placeholder="xxx株式会社" maxlength="20" style="text-align: left">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="staff">締切（月）</label>
                                        <input type="text" class="form-control" name="magamym" id="magamYm" placeholder="2019/01" maxlength="100" style="text-align: left">
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="telno">締切（日）</label>
                                        <input type="text" class="form-control" name="magamymd" id="magamYmd" placeholder="2019/01/01" maxlength="100" style="text-align: left">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="text-align: center">
                                <div class="col-xs-4"></div>
                                <div class="col-xs-2">
                                    <p class="text-center">
                                        <input type="submit" name="btnRegMMI" class="btn btn-primary" id="btnRegMMI" role="button" value="登録">
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
                    <form method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                会社情報編集(<span id="usname"></span>)
                                <button class="close" data-dismiss="modal">x</button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <label for="companycode">Company ID</label>
                                        <input type="text" class="form-control" name="udcompanyid" id="udcompanyid" value="<?= $new_companyID_mi ?>" maxlength="10" style="text-align: left" readonly>
                                    </div>
                                    <div class="col-xs-9">
                                        <label for="companyname">Company Name</label>
                                        <input type="text" class="form-control" name="udcompanyname" id="udcompanyname" placeholder="xxx株式会社" maxlength="20" style="text-align: left">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <label for="staff">締切（月）</label>
                                        <input type="text" class="form-control" name="udmagamym" id="udmagamYm" placeholder="2019/01" maxlength="100" style="text-align: left">
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="telno">締切（日）</label>
                                        <input type="text" class="form-control" name="udmagamymd" id="udmagamYmd" placeholder="2019/01/01" maxlength="100" style="text-align: left">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="text-align: center">
                                <div class="col-xs-3"></div>
                                <div class="col-xs-2">
                                    <p class="text-center">
                                        <input type="submit" name="btnUpdateMMI" class="btn btn-primary" id="btnUpdateMMI" role="button" value="編集">
                                    </p>
                                </div>
                                <div class="col-md-2">
                                    <input type="submit" name="DeleteMMI" class="btn btn-warning" role="button" value="削除">
                                </div>
                                <div class="col-xs-2">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                                </div>
                                <div class="col-xs-3"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Show data ADMIN -->
        <hr>
        <form method="post">
            <div class="row">
                <?php if (empty($manageinfo_list)) { ?>
                    <div class="form-group row">
                        <div class="col-md-12"><?php echo $data_save_no; ?></div>
                    </div>
                    <?php } elseif (!empty($manageinfo_list)) {
                    foreach ($manageinfo_list as $key) {
                    ?>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="companyid">Company ID</label>
                                <input id="companyid" name="companyid" placeholder="Company ID" class="form-control" type="text" value="<?= $key['companyid'] ?>" readonly>
                            </div>
                            <div class="col-md-10">
                                <label for="companyname">Company Name</label>
                                <input id="companyname" name="companyname" placeholder="Company Name" class="form-control" type="text" value="<?= $key['companyname'] ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="magamYm">締切（月）</label>
                                <input id="magamYm" name="magamYm" placeholder="yyyy/mm" class="form-control" type="text" value="<?= $key['magamym'] ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="magamYmd">締切（日）</label>
                                <input id="magamYmd" name="magamYmd" placeholder="yyyy/mm/dd" class="form-control" type="text" value="<?= $key['magamymd'] ?>">
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                <?php
                    }
                } ?>
            </div>
            <br>
            <hr>
            <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-2">
                    <p class="text-center">
                        <input type="submit" name="btnRegMi" class="btn btn-primary" id="btnReg" role="button" value="登録">
                    </p>
                </div>
                <div class="col-xs-2">
                    <p class="text-center"><a class="btn btn-default btn-md" id="btnClose" href="../contact/noticeList.php" role="button">閉じる </a></p>
                </div>
                <div class="col-xs-4"></div>
            </div>
        </form>
    <?php endif; ?>
</div>
<script>
    // Datepicker Calender
    $("#magamYm").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm'
    });

    $("#magamYmd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    $("#udmagamYm").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm'
    });

    $("#udmagamYmd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    // Check Error ADMIN
    $(document).on('click', '#btnReg', function(e) {
        var magamYm = $("#magamYm").val();
        var magamYmd = $("#magamYmd").val();

        if (magamYm == "") {
            alert("<?php echo $manage_magamym_empty; ?>");
            $("#magamYm").focus();
            return false;
        }

        if (magamYmd == "") {
            alert("<?php echo $manage_magamymd_empty; ?>");
            $("#magamYmd").focus();
            return false;
        }
    });

    // New button: popup & clear 
    $(document).on('click', '#btnNewMI', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error MAIN_ADMIN Save Data
    $(document).on('click', '#btnRegMMI', function(e) {
        var companyname = $("#companyname").val();
        var magamYm = $("#magamYm").val();
        var magamYmd = $("#magamYmd").val();

        if (companyname == "") {
            alert("<?php echo $manage_Cname_empty; ?>");
            $("#companyname").focus();
            return false;
        }

        if (magamYm == "") {
            alert("<?php echo $manage_magamym_empty; ?>");
            $("#magamYm").focus();
            return false;
        }

        if (magamYmd == "") {
            alert("<?php echo $manage_magamymd_empty; ?>");
            $("#magamYmd").focus();
            return false;
        }
    });

    // Funtion for click day of week
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var CompanyName = SeparateArr[0];
        var CompanyId = SeparateArr[1];

        <?php
        foreach ($manageinfo_list as $key) {
        ?>
            if ('<?php echo $key['companyid'] ?>' === CompanyId && '<?php echo $key['companyname'] ?>' === CompanyName) {
                $("#usname").text('<?php echo $key['companyname'] ?>');
                $("#udcompanyid").text($('[name="udcompanyid"]').val("<?php echo $key['companyid'] ?>"));
                $("#udcompanyname").text($('[name="udcompanyname"]').val("<?php echo $key['companyname'] ?>"));
                $("#udmagamym").text($('[name="udmagamym"]').val("<?php echo $key['magamym'] ?>"));
                $("#udmagamymd").text($('[name="udmagamymd"]').val("<?php echo $key['magamymd'] ?>"));
            }
        <?php
        }
        ?>
    });

    // Check Error MAIN_ADMIN Update Data
    $(document).on('click', '#btnUpdateMMI', function(e) {
        var companyname = $("#udcompanyname").val();
        var magamYm = $("#udmagamYm").val();
        var magamYmd = $("#udmagamYmd").val();

        if (companyname == "") {
            alert("<?php echo $manage_Cname_empty; ?>");
            $("#udcompanyname").focus();
            return false;
        }

        if (magamYm == "") {
            alert("<?php echo $manage_magamym_empty; ?>");
            $("#udmagamYm").focus();
            return false;
        }

        if (magamYmd == "") {
            alert("<?php echo $manage_magamymd_empty; ?>");
            $("#udmagamYmd").focus();
            return false;
        }
    });


    window.onload = function() {
		setTimeout(hideLoadingOverlay, 500);
		startLoading();
};


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