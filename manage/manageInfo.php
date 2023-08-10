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

if ($_SESSION['auth_type'] == 1) { // if not admin 
    header("Location: ./../../index.php");
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
</style>
<title>管理情報登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['update_mi_success']) && isset($_POST['btnRegMi'])) {
    ?>
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['update_mi_success']; ?>
        </div>
    <?php
        unset($_SESSION['update_mi_success']);
    }
    ?>
    <form method="post">
        <div class="row">
            <div class="col-md-12 text-left">
                <div class="title_name">
                    <span class="text-left">管理情報登録</span>
                </div>
            </div>
        </div>
        <hr>
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
                        <div class="col-md-2">
                            <label for="kyukatimelimit">年間休暇時間</label>
                            <input id="kyukatimelimit" name="kyukatimelimit" class="form-control" type="text" value="<?= $key['kyukatimelimit'] ?>" maxlength="2">
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
                <p class="text-center"><a class="btn btn-warning btn-md" id="btnClose" href="../contact/noticeList.php" role="button">閉じる </a></p>
            </div>
            <div class="col-xs-4"></div>
        </div>
    </form>
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

    // Check Error
    $(document).on('click', '#btnReg', function(e) {
        var magamYm = $("#magamYm").val();
        var magamYmd = $("#magamYmd").val();
        var kyukatimeLimit = $("#kyukatimelimit").val();

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

        if (kyukatimeLimit == "") {
            alert("<?php echo $manage_kyukatimelimit_empty; ?>");
            $("#kyukatimelimit").focus();
            return false;
        }

        if (isNaN(kyukatimeLimit)) {
            alert("<?php echo $manage_kyukatimelimit_no; ?>");
            e.preventDefault();
            $("#kyukatimelimit").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>