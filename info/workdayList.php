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

if ($_SESSION['auth_type'] == 1) { // if not admin 
    header("Location: ./../../index.php");
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
    <div class="row">
        <div class="col-md-4">
            <div class="title_name">
                <span class="text-left">勤務日登録</span>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2 text-right">
            <div class="title_btn">
                <input type="button" id="btnNew" value="新規 ">
            </div>
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
                            <td><a href="#"><span class="showModal"><?= $key['workyear'] ?></span></a></td>
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
<script>
    // New button: popup & clear 
    $(document).on('click', '#btnNew', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnReg', function(e) {
        var workyear = $("#workyear").val();
        var number_no = /^[0-9]+$/;

        if (workyear == "") {
            alert("<?php echo $info_workyear_empty; ?>");
            $("#workyear").focus();
            return false;
        }
        if (!workyear.match(number_no)) {
            alert("<?php echo $info_workyear_no; ?>");
            e.preventDefault();
            $("#workyear").focus();
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
        // your function here
        var i = $(".showModal").index(this); // Index based on all showModal that exists
        console.log(i);

        $('#modal').modal('toggle');
    });
</script>
<?php include('../inc/footer.php'); ?>