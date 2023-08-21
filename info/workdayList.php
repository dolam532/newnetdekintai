<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/infomodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}
?>

<!-- ****CSS*****  -->
<style type="text/css">
    .datatable tr th {
        background-color: #D9EDF7;
        text-align: center;
    }

    .datatable tr td {
        text-align: center;
    }

    .btn {
        width: 80px;
        height: 32px;
    }

    .modal-body>.row {
        margin: 8px;
    }

    div label {
        padding: 5px;
    }
</style>
<title>勤務日登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegWdl'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateWdl'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelWdl'])) {
    ?>
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['delete_success']; ?>
        </div>
    <?php
        unset($_SESSION['delete_success']);
    }
    ?>
    <div class="row">
        <div class="col-md-4">
            <div class="title_name">
                <span class="text-left">勤務日登録</span>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2 text-right">
            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                <div class="title_btn">
                    <input type="button" id="btnNew" value="新規 ">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 10%;">年</th>
                    <th style="text-align: center; width: 7.5%;">1月</th>
                    <th style="text-align: center; width: 7.5%;">2月</th>
                    <th style="text-align: center; width: 7.5%;">3月</th>
                    <th style="text-align: center; width: 7.5%;">4月</th>
                    <th style="text-align: center; width: 7.5%;">5月</th>
                    <th style="text-align: center; width: 7.5%;">6月</th>
                    <th style="text-align: center; width: 7.5%;">7月</th>
                    <th style="text-align: center; width: 7.5%;">8月</th>
                    <th style="text-align: center; width: 7.5%;">9月</th>
                    <th style="text-align: center; width: 7.5%;">10月</th>
                    <th style="text-align: center; width: 7.5%;">11月</th>
                    <th style="text-align: center; width: 7.5%;">12月</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($workday_list)) { ?>
                    <tr>
                        <td colspan="13" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($workday_list)) {
                    foreach ($workday_list as $key) {
                    ?>
                        <tr>
                            <td>
                                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                    <a href="#"><span class="showModal"><?= $key['workyear'] ?></span></a>
                                <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
                                    <span class="showModal"><?= $key['workyear'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span><?= isset($key['one_monthwd']) ? $key['one_monthwd']  : '0'; ?></span></td>
                            <td><span><?= isset($key['two_monthwd']) ? $key['two_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['three_monthwd']) ? $key['three_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['four_monthwd']) ? $key['four_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['five_monthwd']) ? $key['five_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['six_monthwd']) ? $key['six_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['seven_monthwd']) ? $key['seven_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['eight_monthwd']) ? $key['eight_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['nine_monthwd']) ? $key['nine_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['ten_monthwd']) ? $key['ten_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['eleven_monthwd']) ? $key['eleven_monthwd'] : '0'; ?></span></td>
                            <td><span><?= isset($key['twelve_monthwd']) ? $key['twelve_monthwd'] : '0'; ?></span></td>
                        </tr>
                <?php
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- 新規 -->
<div class="row">
    <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
        <div class="modal-dialog">
            <form method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        勤務日登録<span id="sname">(New)</span>
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-1">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workyear">勤務年</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" id="workyear" name="workyear" placeholder="" maxlength="4">
                                <input type="hidden" name="companyid" value="<?= constant('GANASYS_COMPANY_ID') ?>">
                            </div>
                            <div class="col-xs-6">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday01">01月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month01" value="01">
                                <input type="text" class="form-control text-center" name="workday01" id="workday01" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday02">02月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month02" value="02">
                                <input type="text" class="form-control text-center" name="workday02" id="workday02" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday03">03月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month03" value="03">
                                <input type="text" class="form-control text-center" name="workday03" id="workday03" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday04">04月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month04" value="04">
                                <input type="text" class="form-control text-center" name="workday04" id="workday04" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday05">05月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month05" value="05">
                                <input type="text" class="form-control text-center" name="workday05" id="workday05" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday06">06月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month06" value="06">
                                <input type="text" class="form-control text-center" name="workday06" id="workday06" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday07">07月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month07" value="07">
                                <input type="text" class="form-control text-center" name="workday07" id="workday07" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday08">08月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month08" value="08">
                                <input type="text" class="form-control text-center" name="workday08" id="workday08" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday09">09月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month09" value="09">
                                <input type="text" class="form-control text-center" name="workday09" id="workday09" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <input type="hidden" name="month10" value="10">
                                <label for="workday10">10月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" name="workday10" id="workday10" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday11">11月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month11" value="11">
                                <input type="text" class="form-control text-center" name="workday11" id="workday11" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday12">12月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="month12" value="12">
                                <input type="text" class="form-control text-center" name="workday12" id="workday12" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnRegWdl" class="btn btn-primary" id="btnReg" role="button" value="登録">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                            </p>
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
    <div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
        <div class="modal-dialog">
            <form method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        勤務日編集
                        (<span id="usname"></span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-1">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workyear">勤務年</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" id="udworkyear" name="udworkyear" placeholder="" maxlength="4" readonly>
                                <input type="hidden" name="udcompanyid" value="<?= constant('GANASYS_COMPANY_ID') ?>">
                            </div>
                            <div class="col-xs-6">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday01">01月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth01">
                                <input type="text" class="form-control text-center" name="udworkday01" id="udworkday01" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday02">02月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth02">
                                <input type="text" class="form-control text-center" name="udworkday02" id="udworkday02" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday03">03月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth03">
                                <input type="text" class="form-control text-center" name="udworkday03" id="udworkday03" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday04">04月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth04">
                                <input type="text" class="form-control text-center" name="udworkday04" id="udworkday04" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday05">05月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth05">
                                <input type="text" class="form-control text-center" name="udworkday05" id="udworkday05" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday06">06月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth06">
                                <input type="text" class="form-control text-center" name="udworkday06" id="udworkday06" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday07">07月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth07">
                                <input type="text" class="form-control text-center" name="udworkday07" id="udworkday07" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday08">08月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth08">
                                <input type="text" class="form-control text-center" name="udworkday08" id="udworkday08" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday09">09月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth09">
                                <input type="text" class="form-control text-center" name="udworkday09" id="udworkday09" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <input type="hidden" name="udmonth10">
                                <label for="workday10">10月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" name="udworkday10" id="udworkday10" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1"></div>
                            <div class="col-xs-2 text-right">
                                <label for="workday11">11月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth11">
                                <input type="text" class="form-control text-center" name="udworkday11" id="udworkday11" maxlength="2">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="workday12">12月</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="hidden" name="udmonth12">
                                <input type="text" class="form-control text-center" name="udworkday12" id="udworkday12" maxlength="2">
                            </div>
                            <div class="col-xs-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnUpdateWdl" class="btn btn-primary" id="btnUpdate" role="button" value="編集">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnDelWdl" class="btn btn-warning" id="btnDel" role="button" value="削除">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                            </p>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // New button: popup & clear 
    $(document).on('click', '#btnNew', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnReg', function(e) {
        var workyear = $("#workyear").val();
        var workday01 = $("#workday01").val();
        var workday02 = $("#workday02").val();
        var workday03 = $("#workday03").val();
        var workday04 = $("#workday04").val();
        var workday05 = $("#workday05").val();
        var workday06 = $("#workday06").val();
        var workday07 = $("#workday07").val();
        var workday08 = $("#workday08").val();
        var workday09 = $("#workday09").val();
        var workday10 = $("#workday10").val();
        var workday11 = $("#workday11").val();
        var workday12 = $("#workday12").val();

        if (workyear == "") {
            alert("<?php echo $info_workyear_empty; ?>");
            $("#workyear").focus();
            return false;
        }
        if (isNaN(workyear)) {
            alert("<?php echo $info_workyear_no; ?>");
            e.preventDefault();
            $("#workyear").focus();
            return false;
        }
        if (isNaN(workday01)) {
            alert("<?php echo $info_workday01_no; ?>");
            e.preventDefault();
            $("#workday01").focus();
            return false;
        }
        if (isNaN(workday02)) {
            alert("<?php echo $info_workday02_no; ?>");
            e.preventDefault();
            $("#workday02").focus();
            return false;
        }
        if (isNaN(workday03)) {
            alert("<?php echo $info_workday03_no; ?>");
            e.preventDefault();
            $("#workday03").focus();
            return false;
        }
        if (isNaN(workday04)) {
            alert("<?php echo $info_workday04_no; ?>");
            e.preventDefault();
            $("#workday04").focus();
            return false;
        }
        if (isNaN(workday05)) {
            alert("<?php echo $info_workday05_no; ?>");
            e.preventDefault();
            $("#workday05").focus();
            return false;
        }
        if (isNaN(workday06)) {
            alert("<?php echo $info_workday06_no; ?>");
            e.preventDefault();
            $("#workday06").focus();
            return false;
        }
        if (isNaN(workday07)) {
            alert("<?php echo $info_workday07_no; ?>");
            e.preventDefault();
            $("#workday07").focus();
            return false;
        }
        if (isNaN(workday08)) {
            alert("<?php echo $info_workday08_no; ?>");
            e.preventDefault();
            $("#workday08").focus();
            return false;
        }
        if (isNaN(workday09)) {
            alert("<?php echo $info_workday09_no; ?>");
            e.preventDefault();
            $("#workday09").focus();
            return false;
        }
        if (isNaN(workday10)) {
            alert("<?php echo $info_workday10_no; ?>");
            e.preventDefault();
            $("#workday10").focus();
            return false;
        }
        if (isNaN(workday11)) {
            alert("<?php echo $info_workday11_no; ?>");
            e.preventDefault();
            $("#workday11").focus();
            return false;
        }
        if (isNaN(workday12)) {
            alert("<?php echo $info_workday12_no; ?>");
            e.preventDefault();
            $("#workday12").focus();
            return false;
        }
        <?php
        if (!empty($workday_list)) {
            foreach ($workday_list as $key) {
        ?>
                if ('<?php echo $key['workyear'] ?>' == workyear) {
                    alert("<?php echo $info_workyear_have; ?>");
                    $("#workyear").focus();
                    return false;
                }
        <?php
            }
        }
        ?>
    });

    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        var Workyear = $(this).text();
        $('#modal2').modal('toggle');

        <?php
        if (!empty($workday_list)) {
            foreach ($workday_list as $key) {
        ?>
                if ('<?php echo $key['workyear'] ?>' == Workyear) {
                    $("#usname").text('<?php echo $key['workyear'] ?>');
                    $("#udworkyear").text($('[name="udworkyear"]').val("<?php echo $key['workyear'] ?>"));

                    var udmonth01 = $("input[name=udmonth01]:hidden");
                    udmonth01.val("<?php echo $key['one_month'] ?>");
                    var udmonth01 = udmonth01.val();
                    $("#udworkday01").text($('[name="udworkday01"]').val("<?php echo isset($key['one_monthwd']) ? $key['one_monthwd']  : '0'; ?>"));
                    var udmonth02 = $("input[name=udmonth02]:hidden");
                    udmonth02.val("<?php echo $key['two_month'] ?>");
                    var udmonth02 = udmonth02.val();
                    $("#udworkday02").text($('[name="udworkday02"]').val("<?php echo isset($key['two_monthwd']) ? $key['two_monthwd']  : '0'; ?>"));
                    var udmonth03 = $("input[name=udmonth03]:hidden");
                    udmonth03.val("<?php echo $key['three_month'] ?>");
                    var udmonth03 = udmonth03.val();
                    $("#udworkday03").text($('[name="udworkday03"]').val("<?php echo isset($key['three_monthwd']) ? $key['three_monthwd']  : '0'; ?>"));
                    var udmonth04 = $("input[name=udmonth04]:hidden");
                    udmonth04.val("<?php echo $key['four_month'] ?>");
                    var udmonth04 = udmonth04.val();
                    $("#udworkday04").text($('[name="udworkday04"]').val("<?php echo isset($key['four_monthwd']) ? $key['four_monthwd']  : '0'; ?>"));
                    var udmonth05 = $("input[name=udmonth05]:hidden");
                    udmonth05.val("<?php echo $key['five_month'] ?>");
                    var udmonth05 = udmonth05.val();
                    $("#udworkday05").text($('[name="udworkday05"]').val("<?php echo isset($key['five_monthwd']) ? $key['five_monthwd']  : '0'; ?>"));
                    var udmonth06 = $("input[name=udmonth06]:hidden");
                    udmonth06.val("<?php echo $key['six_month'] ?>");
                    var udmonth06 = udmonth06.val();
                    $("#udworkday06").text($('[name="udworkday06"]').val("<?php echo isset($key['six_monthwd']) ? $key['six_monthwd']  : '0'; ?>"));
                    var udmonth07 = $("input[name=udmonth07]:hidden");
                    udmonth07.val("<?php echo $key['seven_month'] ?>");
                    var udmonth07 = udmonth07.val();
                    $("#udworkday07").text($('[name="udworkday07"]').val("<?php echo isset($key['seven_monthwd']) ? $key['seven_monthwd']  : '0'; ?>"));
                    var udmonth08 = $("input[name=udmonth08]:hidden");
                    udmonth08.val("<?php echo $key['eight_month'] ?>");
                    var udmonth08 = udmonth08.val();
                    $("#udworkday08").text($('[name="udworkday08"]').val("<?php echo isset($key['eight_monthwd']) ? $key['eight_monthwd']  : '0'; ?>"));
                    var udmonth09 = $("input[name=udmonth09]:hidden");
                    udmonth09.val("<?php echo $key['nine_month'] ?>");
                    var udmonth09 = udmonth09.val();
                    $("#udworkday09").text($('[name="udworkday09"]').val("<?php echo isset($key['nine_monthwd']) ? $key['nine_monthwd']  : '0'; ?>"));
                    var udmonth10 = $("input[name=udmonth10]:hidden");
                    udmonth10.val("<?php echo $key['ten_month'] ?>");
                    var udmonth10 = udmonth10.val();
                    $("#udworkday10").text($('[name="udworkday10"]').val("<?php echo isset($key['ten_monthwd']) ? $key['ten_monthwd']  : '0'; ?>"));
                    var udmonth11 = $("input[name=udmonth11]:hidden");
                    udmonth11.val("<?php echo $key['eleven_month'] ?>");
                    var udmonth11 = udmonth11.val();
                    $("#udworkday11").text($('[name="udworkday11"]').val("<?php echo isset($key['eleven_monthwd']) ? $key['eleven_monthwd']  : '0'; ?>"));
                    var udmonth12 = $("input[name=udmonth12]:hidden");
                    udmonth12.val("<?php echo $key['twelve_month'] ?>");
                    var udmonth12 = udmonth12.val();
                    $("#udworkday12").text($('[name="udworkday12"]').val("<?php echo isset($key['twelve_monthwd']) ? $key['twelve_monthwd']  : '0'; ?>"));
                }
        <?php
            }
        }
        ?>
    });

    // Check Error
    $(document).on('click', '#btnUpdate', function(e) {
        var udworkyear = $("#udworkyear").val();
        var udworkday01 = $("#udworkday01").val();
        var udworkday02 = $("#udworkday02").val();
        var udworkday03 = $("#udworkday03").val();
        var udworkday04 = $("#udworkday04").val();
        var udworkday05 = $("#udworkday05").val();
        var udworkday06 = $("#udworkday06").val();
        var udworkday07 = $("#udworkday07").val();
        var udworkday08 = $("#udworkday08").val();
        var udworkday09 = $("#udworkday09").val();
        var udworkday10 = $("#udworkday10").val();
        var udworkday11 = $("#udworkday11").val();
        var udworkday12 = $("#udworkday12").val();

        if (udworkyear == "") {
            alert("<?php echo $info_workyear_empty; ?>");
            $("#udworkyear").focus();
            return false;
        }
        if (isNaN(udworkyear)) {
            alert("<?php echo $info_workyear_no; ?>");
            e.preventDefault();
            $("#udworkyear").focus();
            return false;
        }
        if (isNaN(udworkday01)) {
            alert("<?php echo $info_workday01_no; ?>");
            e.preventDefault();
            $("#udworkday01").focus();
            return false;
        }
        if (isNaN(udworkday02)) {
            alert("<?php echo $info_workday02_no; ?>");
            e.preventDefault();
            $("#udworkday02").focus();
            return false;
        }
        if (isNaN(udworkday03)) {
            alert("<?php echo $info_workday03_no; ?>");
            e.preventDefault();
            $("#udworkday03").focus();
            return false;
        }
        if (isNaN(udworkday04)) {
            alert("<?php echo $info_workday04_no; ?>");
            e.preventDefault();
            $("#udworkday04").focus();
            return false;
        }
        if (isNaN(udworkday05)) {
            alert("<?php echo $info_workday05_no; ?>");
            e.preventDefault();
            $("#udworkday05").focus();
            return false;
        }
        if (isNaN(udworkday06)) {
            alert("<?php echo $info_workday06_no; ?>");
            e.preventDefault();
            $("#udworkday06").focus();
            return false;
        }
        if (isNaN(udworkday07)) {
            alert("<?php echo $info_workday07_no; ?>");
            e.preventDefault();
            $("#udworkday07").focus();
            return false;
        }
        if (isNaN(udworkday08)) {
            alert("<?php echo $info_workday08_no; ?>");
            e.preventDefault();
            $("#udworkday08").focus();
            return false;
        }
        if (isNaN(udworkday09)) {
            alert("<?php echo $info_workday09_no; ?>");
            e.preventDefault();
            $("#udworkday09").focus();
            return false;
        }
        if (isNaN(udworkday10)) {
            alert("<?php echo $info_workday10_no; ?>");
            e.preventDefault();
            $("#udworkday10").focus();
            return false;
        }
        if (isNaN(udworkday11)) {
            alert("<?php echo $info_workday11_no; ?>");
            e.preventDefault();
            $("#udworkday11").focus();
            return false;
        }
        if (isNaN(udworkday12)) {
            alert("<?php echo $info_workday12_no; ?>");
            e.preventDefault();
            $("#udworkday12").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>