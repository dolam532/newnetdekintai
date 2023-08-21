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

    span.uservacationList_class {
        display: none;
    }
</style>
<title>年次休暇登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnUpdateUvl'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelUvl'])) {
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
                    <span class="text-left">年次休暇登録</span>
                </div>
            </div>
            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                <div class="col-md-3 text-left">
                    <div class="title_condition">
                        <label>入社日 : <input type="text" id="searchYmd" name="searchYmd" value="<?= $_POST['searchYmd'] ?>" style="width: 100px; text-align:center"></label>
                    </div>
                </div>
                <div class="col-md-3 text-left">
                    <div class="title_condition">
                        <label>社員名 : <input type="text" id="searchName" name="searchName" value="<?= $_POST['searchName'] ?>" style="width: 100px; text-align:center"></label>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <div class="title_btn">
                        <input type="submit" name="uservacationListSearch" value="検索 ">&nbsp;
                    </div>
                </div>
            <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
                <div class="col-md-9 text-right"></div>
            <?php endif; ?>
        </div>
    </form>

    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 10%;">社員名</th>
                    <th style="text-align: center; width: 8%;">入社日</th>
                    <th style="text-align: center; width: 8%;">勤続年数</th>
                    <th style="text-align: center; width: 10%;">年次開始日</th>
                    <th style="text-align: center; width: 10%;">年次終了日</th>
                    <th style="text-align: center; width: 8%;">前年残数</th>
                    <th style="text-align: center; width: 8%;">当年付与</th>
                    <th style="text-align: center; width: 8%;">使用(日)</th>
                    <th style="text-align: center; width: 8%;">使用(時)</th>
                    <th style="text-align: center; width: 8%;">残数(日)</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($uservacation_list)) { ?>
                    <tr>
                        <td colspan="11" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($uservacation_list)) {
                    foreach ($uservacation_list as $key) {
                    ?>
                        <tr>
                            <td><span><?= $key['name'] ?></span></td>
                            <td><span><?= $key['inymd'] ?></span></td>
                            <td align="center">
                                <?php
                                $targetDate = new DateTime($key['inymd']);
                                $currentDate = new DateTime();
                                $interval = $currentDate->diff($targetDate);
                                $years = $interval->y;
                                echo $years;
                                ?>
                            </td>
                            <td><a href="#"><span class="showModal"><?= isset($key['vacationstr']) ? $key['vacationstr']  : '未登錄'; ?><span class="uservacationList_class"><?= ',' . $key['uid'] . ',' . $key['inymd'] ?></span></span></a></td>
                            <td align="center"><span><?= $key['vacationend'] ?></span></td>
                            <td align="center"><span><?= $key['oldcnt'] ?></span></td>
                            <td align="center"><span><?= $key['newcnt'] ?></span></td>
                            <td align="center"><span><?= $key['usecnt'] ?></span></td>
                            <td align="center"><span><?= $key['usetime'] ?></span></td>
                            <td align="center"><span><?= $key['restcnt'] ?></span></td>
                            <td><span><?= $key['bigo'] ?></span></td>
                        </tr>
                <?php
                    }
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
                    <div class="modal-header">
                        年次休暇登録
                        (<span id="usname"></span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="uid">ID</label>
                                <input type="text" class="form-control" name="uduid" id="uduid" style="text-align: left" readonly>
                                <input type="hidden" name="udvacationid" id="udvacationid">
                            </div>
                            <div class="col-md-3">
                                <label for="name">名</label>
                                <input type="text" class="form-control" name="udname" id="udname" style="text-align: center" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="inymd">入社日</label>
                                <input type="text" class="form-control" name="udinymd" id="udinymd" style="text-align: center" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="yearcnt">勤続年数</label>
                                <input type="text" class="form-control" name="udyearcnt" id="udyearcnt" style="text-align: center" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="vacationstr">年次開始日</label>
                                <input type="text" class="form-control" name="udvacationstr" id="udvacationstr" maxlength="10" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="vacationend">年次終了日</label>
                                <input type="text" class="form-control" name="udvacationend" id="udvacationend" maxlength="10" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="oldcnt">前年残数</label>
                                <input type="text" class="form-control" name="udoldcnt" id="udoldcnt" maxlength="2" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="newcnt">当年付与</label>
                                <input type="text" class="form-control" name="udnewcnt" id="udnewcnt" maxlength="2" style="text-align: center">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="usecnt">使用(日)</label>
                                <input type="text" class="form-control" name="udusecnt" id="udusecnt" maxlength="2" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="usetime">使用(時)</label>
                                <input type="text" class="form-control" name="udusetime" id="udusetime" maxlength="2" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="restcnt">残数(日)</label>
                                <input type="text" class="form-control" name="udrestcnt" id="udrestcnt" maxlength="2" style="text-align: center" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-md-3"></div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <input type="submit" name="btnUpdateUvl" class="btn btn-primary" id="btnUpdateUvl" role="button" value="登録">
                            </p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <input type="submit" name="btnDelUvl" class="btn btn-warning" id="btnDel" role="button" value="削除">
                            </p>
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
<script>
    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var Vacationstr = SeparateArr[0];
        var Uid = SeparateArr[1];
        var Inymd = SeparateArr[2];
        if (Inymd == "") {
            alert("<?php echo $info_uvl_joincompany_empty; ?>");
            return false;
        } else {
            $('#modal').modal('toggle');
            $("#usname").text(Uid);
            $("#uduid").text($('[name="uduid"]').val(Uid));
            $("#udinymd").text($('[name="udinymd"]').val(Inymd));
            <?php
            if (!empty($uservacation_list)) {
                foreach ($uservacation_list as $key) {
            ?>
                    if ('<?php echo $key['uid'] ?>' == Uid) {
                        $("#udvacationstr").text($('[name="udvacationstr"]').val("<?php echo $key['vacationstr'] ?>"));
                        $("#udname").text($('[name="udname"]').val("<?php echo $key['name'] ?>"));
                        var targetDate = new Date("<?php echo $key['inymd']; ?>");
                        var currentDate = new Date();
                        var yearDifference = currentDate.getFullYear() - targetDate.getFullYear();
                        var currentMonth = currentDate.getMonth();
                        var targetMonth = targetDate.getMonth();
                        if (currentMonth < targetMonth || (currentMonth === targetMonth && currentDate.getDate() < targetDate.getDate())) {
                            yearDifference--;
                        }
                        $("#udyearcnt").text($('[name="udyearcnt"]').val(yearDifference));
                        $("#udvacationend").text($('[name="udvacationend"]').val("<?php echo $key['vacationend'] ?>"));
                        $("#udoldcnt").text($('[name="udoldcnt"]').val("<?php echo isset($key['oldcnt']) ? $key['oldcnt']  : '0'; ?>"));
                        $("#udnewcnt").text($('[name="udnewcnt"]').val("<?php echo isset($key['newcnt']) ? $key['newcnt']  : '0'; ?>"));
                        $("#udusecnt").text($('[name="udusecnt"]').val("<?php echo isset($key['usecnt']) ? $key['usecnt']  : '0'; ?>"));
                        $("#udusetime").text($('[name="udusetime"]').val("<?php echo isset($key['usetime']) ? $key['usetime']  : '0'; ?>"));
                        $("#udrestcnt").text($('[name="udrestcnt"]').val("<?php echo isset($key['restcnt']) ? $key['restcnt']  : '0'; ?>"));
                        var udvacationid = $("input[name=udvacationid]:hidden");
                        udvacationid.val("<?php echo $key['vacationid'] ?>");
                        var udvacationid = udvacationid.val();
                    }
            <?php
                }
            }
            ?>
        }
    });

    // Datepicker Calender
    $("#udvacationstr").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    $("#udvacationend").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    // Check Error
    $(document).on('click', '#btnUpdateUvl', function(e) {
        var Vacationstr = $("#udvacationstr").val();
        var Vacationend = $("#udvacationend").val();

        if (Vacationstr == "") {
            alert("<?php echo $info_uvlvacationstr_empty; ?>");
            $("#udvacationstr").focus();
            return false;
        }

        if (Vacationend == "") {
            alert("<?php echo $info_uvlvacationend_empty; ?>");
            $("#udvacationend").focus();
            return false;
        }
    });

    $(document).ready(function() {
        function calculateRestCount() {
            var udoldcnt = $("#udoldcnt").val() * 1;
            var udnewcnt = $("#udnewcnt").val() * 1;
            var udusecnt = $("#udusecnt").val() * 1;
            var udusetime = $("#udusetime").val() * 1;

            var quotient = Math.floor(udusetime / 8);
            var restCountD;
            restCountD = udoldcnt + udnewcnt - udusecnt - quotient;
            $("#udrestcnt").val(restCountD);
        }
        $("#udoldcnt, #udnewcnt, #udusecnt, #udusetime").on("change keyup", calculateRestCount);
    });
</script>
<?php include('../inc/footer.php'); ?>