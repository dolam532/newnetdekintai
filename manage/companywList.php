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

    span.companyList_class {
        display: none;
    }

    .admin-action-hidden {
        display: none;
    }

    .admin-action-change {
        pointer-events: none;
        background-color: #ccc;
    }

    .template-notice-text {
        font-size: smaller;
        color: red;
        font-style: italic;
    }
</style>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegCWL'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateCL'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['DeleteCL'])) {
    ?>
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['delete_success']; ?>
        </div>
    <?php
        unset($_SESSION['delete_success']);
    }
    ?>
    <title>
        <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
            業務時間登録
        <?php else : ?>
            業務時間編集
        <?php endif; ?>
    </title>
    <form method="post">
        <div class="row">
            <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                <div class="col-md-3 text-left">
                    <div class="title_name">
                        <span class="text-left">業務時間登録</span>
                    </div>
                </div>
                <div class="col-md-3 text-center"></div>
                <div class="col-md-3 text-left">
                    <div class="title_condition">
                        <label for="companyname">会社名 : <input type="text" name="companyname" value="<?= $_POST['companyname'] ?>" style="width: 200px;" placeholder="〇〇会社"></label>
                    </div>
                </div>
                <div class="col-md-3 text-right">
                    <div class="title_btn">
                        <input type="submit" name="btnSearchCWL" value="検索">
                        <input type="button" id="btnNewCWL" value="新規">
                        <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
                    </div>
                </div>
            <?php else : ?>
                <div class="col-md-10 text-left">
                    <div class="title_name">
                        <span class="text-left">業務時間編集</span>
                    </div>
                </div>
                <div class="col-md-2 text-right">
                    <div class="title_btn">
                        <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 5%;">会社ID</th>
                    <th style="text-align: center; width: 10%;">会社名</th>
                    <th style="text-align: center; width: 10%;">業務開始時間</th>
                    <th style="text-align: center; width: 10%;">業務終了時間</th>
                    <th style="text-align: center; width: 10%;">休憩開始時間</th>
                    <th style="text-align: center; width: 10%;">休憩終了時間</th>
                    <th style="text-align: center; width: 10%;">業務時間</th>
                    <th style="text-align: center; width: 10%;">休憩時間</th>
                    <th style="text-align: center; width: 8%;">休暇届タイプ</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($companyworktime_list)) { ?>
                    <tr>
                        <td colspan="8" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($companyworktime_list)) {
                    foreach ($companyworktime_list as $key) {
                    ?>
                        <tr>
                            <td><span><?= $key['companyid'] ?></span></td>
                            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                <a href="#">
                                    <td><span class="showModal"><?= $key['companyname'] ?><span class="companyList_class"><?= ',' . $key['companyid'] ?></span></span></td>
                                </a>
                            <?php else : ?>
                                <td><span><?= $key['companyname'] ?></span></td>
                            <?php endif; ?>
                            <td><span><?= $key['starttime'] ?></span></td>
                            <td><span><?= $key['endtime'] ?></span></td>
                            <td><span><?= $key['breakstarttime'] ?></span></td>
                            <td><span><?= $key['breakstarttime'] ?></span></td>
                            <td><span><?= $key['worktime'] ?></span></td>
                            <td><span><?= $key['breaktime'] ?></span></td>
                            <td><span><?= $key['kyukatype'] ?></span></td>
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
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            業務時間登録(<span>New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-2">
                                    <label for="companyid">会社ID</label>
                                    <input type="text" class="form-control" name="companyid" id="companyid" style="text-align: left" readonly>
                                </div>
                                <div class="col-xs-4">
                                    <label for="companyname">会社名</label>
                                    <select class="form-control" name="companyname" id="companyname" style="text-align: left">
                                        <option value="" selected="selected">選択なし</option>
                                        <?php foreach ($company_list_select as $key) { ?>
                                            <option value="<?= $key['companyid'] ?>"><?= $key['companyname'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <label for="kyukatype"><strong>休暇届タイプ</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="kyukatype" name="kyukatype" checked value="<?php echo array_keys(ConstArray::$search_kyukatype)[0]; ?>">
                                        <?php echo ConstArray::$search_kyukatype[array_keys(ConstArray::$search_kyukatype)[0]]; ?>
                                        <label class="template-notice-text"> (日付けのみ) </label>
                                        <br />
                                        <input type="radio" id="kyukatype" name="kyukatype" value="<?php echo array_keys(ConstArray::$search_kyukatype)[1]; ?>">
                                        <?php echo ConstArray::$search_kyukatype[array_keys(ConstArray::$search_kyukatype)[1]]; ?>
                                        <label class="template-notice-text"> (日付け+時間)</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="starttime">業務開始時間</label>
                                    <input type="text" class="form-control" name="starttime" id="starttime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="endtime">業務終了時間</label>
                                    <input type="text" class="form-control" name="endtime" id="endtime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="worktime">業務時間</label>
                                    <input type="text" class="form-control" name="worktime" id="worktime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="breakstarttime">休憩開始時間</label>
                                    <input type="text" class="form-control" name="breakstarttime" id="breakstarttime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="breakendtime">休憩終了時間</label>
                                    <input type="text" class="form-control" name="breakendtime" id="breakendtime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="breaktime">休憩時間</label>
                                    <input type="text" class="form-control" name="breaktime" id="breaktime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="300" style="text-align: left" placeholder="備考">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                                <div class="col-md-4"></div>
                                <div class="col-md-2">
                                    <input type="submit" name="btnRegCWL" class="btn btn-primary" id="btnRegCWL" role="button" value="登録">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                                </div>
                                <div class="col-md-4"></div>
                            <?php endif; ?>
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
                            業務時間編集
                            (<span id="usname"></span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-2">
                                    <label for="companycode">会社ID</label>
                                    <input type="text" class="form-control admchg" name="udcompanyid" id="udcompanyid" maxlength="10" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="companycode">会社コード</label>
                                    <input type="text" class="form-control" name="udcompanycode" id="udcompanycode" placeholder="companycode" maxlength="10" style="text-align: left">
                                </div>
                                <div class="col-xs-7">
                                    <label for="companyname">会社名</label>
                                    <input type="text" class="form-control" name="udcompanyname" id="udcompanyname" placeholder="companyname" maxlength="20" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="staff">担当者名</label>
                                    <input type="text" class="form-control" name="udstaff" id="udstaff" placeholder="staff" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="telno">電話番号</label>
                                    <input type="text" class="form-control" name="udtelno" id="udtelno" placeholder="telno" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="strymd">契約期間(F)</label>
                                    <input type="text" class="form-control admchg" name="udstrymd" id="udstrymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="endymd">契約期間(T)</label>
                                    <input type="text" class="form-control admchg" name="udendymd" id="udendymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-9">
                                    <label for="address">住所</label>
                                    <input type="text" class="form-control" name="udaddress" id="udaddress" maxlength="150" style="text-align: left" placeholder="東京都東京区1丁目2番地二ホンビル3階">
                                </div>
                                <div class="col-xs-3">
                                    <label for="use_yn"><strong>使用</strong></label>
                                    <div class="custom-control custom-radio admchg">
                                        <input type="radio" name="uduse_yn" id="uduse_yn1" value="1" checked>使用
                                        <input type="radio" name="uduse_yn" id="uduse_yn2" value="0">中止
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-6">
                                    <label for="use_type"><strong>勤務表タイプ</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="uduse_type" name="uduse_type" value="<?php echo array_keys(ConstArray::$search_template)[0]; ?>">
                                        <?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[0]]; ?>
                                        <label class="template-notice-text"> (業務時間のみ) </label>
                                        <br />
                                        <input type="radio" id="uduse_type" name="uduse_type" value="<?php echo array_keys(ConstArray::$search_template)[1]; ?>">
                                        <?php echo ConstArray::$search_template[array_keys(ConstArray::$search_template)[1]]; ?>
                                        <label class="template-notice-text"> (出退社時間+業務時間)</label>
                                    </div>
                                </div>
                                <div class="col-xs-6"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="joken">契約条件</label>
                                    <input type="text" class="form-control admchg" name="udjoken" id="udjoken" maxlength="200" style="text-align: left" placeholder="契約条件">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="udbigo" id="udbigo" maxlength="300" style="text-align: left" placeholder="備考">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL" role="button" value="編集">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="DeleteCL" class="btn btn-warning admdel" role="button" value="削除" onclick="return confirm('選択した会社を削除しますか？');">
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
    // Calculate Time
    function calculateTime() {
        var startTime = $("#starttime").val();
        var endTime = $("#endtime").val();
        var breakStartTime = $("#breakstarttime").val();
        var breakEndTime = $("#breakendtime").val();

        var workTimeDiff = timeDiff(startTime, endTime);
        var breakTimeDiff = timeDiff(breakStartTime, breakEndTime);
        var workHours = workTimeDiff.hours - breakTimeDiff.hours;
        var workMinutes = workTimeDiff.minutes - breakTimeDiff.minutes;

        // Ensure minutes are positive
        if (workMinutes < 0) {
            workMinutes += 60;
            workHours -= 1;
        }
        var WorkTime = (workHours < 10 ? "0" : "") + workHours + ":" + (workMinutes < 10 ? "0" : "") + workMinutes;

        // Update fields
        $("#worktime").val(WorkTime);
        $("#breaktime").val(breakTimeDiff.formatted);
    }

    // Function to calculate time difference
    function timeDiff(start, end) {
        var startTime = new Date("1970-01-01 " + start);
        var endTime = new Date("1970-01-01 " + end);
        var diff = new Date(endTime - startTime);
        var hours = diff.getUTCHours();
        var minutes = diff.getUTCMinutes();
        return {
            hours: hours,
            minutes: minutes,
            formatted: (hours < 10 ? "0" : "") + hours + ":" + (minutes < 10 ? "0" : "") + minutes
        };
    }
    $("#starttime, #endtime, #breakstarttime, #breakendtime").on("change", calculateTime);

    $(document).ready(function() {
        // Attach a change event to the companyname select
        $("#companyname").change(function() {
            var selectedCompanyId = $(this).val();
            $("#companyid").val(selectedCompanyId);
        });
    });

    // New button: popup & clear 
    $(document).on('click', '#btnNewCWL', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegCWL', function(e) {
        var Companyname = $("#companyname").val();
        var Starttime = $("#starttime").val();
        var Endtime = $("#endtime").val();
        var Breakstarttime = $("#breakstarttime").val();
        var Breakendtime = $("#breakendtime").val();

        if (Companyname == "") {
            alert("<?php echo $manage_CWname_empty; ?>");
            $("#companyname").focus();
            return false;
        }

        if (Starttime == "") {
            alert("<?php echo $manage_CWstarttime_empty; ?>");
            $("#starttime").focus();
            return false;
        }

        if (Endtime == "") {
            alert("<?php echo $manage_CWendtime_empty; ?>");
            $("#endtime").focus();
            return false;
        }

        if (Breakstarttime == "") {
            alert("<?php echo $manage_CWBreakstarttime_empty; ?>");
            $("#breakstarttime").focus();
            return false;
        }

        if (Breakendtime == "") {
            alert("<?php echo $manage_CWBreakendtime_empty; ?>");
            $("#breakendtime").focus();
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
        // check adm del 
        $(".admdel").removeClass("admin-action-hidden");
        $(".admchg").removeClass("admin-action-change");
        <?php if ($_SESSION['auth_type'] !== constant('MAIN_ADMIN')) : ?>
            $(".admdel").addClass("admin-action-hidden");
            $(".admchg").addClass("admin-action-change");
        <?php endif; ?>

        <?php
        foreach ($company_list as $key) {
        ?>
            if ('<?php echo $key['companyid'] ?>' === CompanyId && '<?php echo $key['companyname'] ?>' === CompanyName) {
                $("#usname").text('<?php echo $key['companyname'] ?>');
                $("#udcompanyid").text($('[name="udcompanyid"]').val("<?php echo $key['companyid'] ?>"));
                $("#udcompanycode").text($('[name="udcompanycode"]').val("<?php echo $key['companycode'] ?>"));
                $("#udcompanyname").text($('[name="udcompanyname"]').val("<?php echo $key['companyname'] ?>"));
                $("#udstaff").text($('[name="udstaff"]').val("<?php echo $key['staff'] ?>"));
                $("#udtelno").text($('[name="udtelno"]').val("<?php echo $key['telno'] ?>"));
                $("#udstrymd").text($('[name="udstrymd"]').val("<?php echo $key['strymd'] ?>"));
                $("#udendymd").text($('[name="udendymd"]').val("<?php echo $key['endymd'] ?>"));
                $("#udaddress").text($('[name="udaddress"]').val("<?php echo $key['address'] ?>"));
                $("input[name='uduse_yn'][value='<?php echo $key['use_yn']; ?>']").prop('checked', true);
                $("#udjoken").text($('[name="udjoken"]').val("<?php echo $key['joken'] ?>"));
                $("#udbigo").text($('[name="udbigo"]').val("<?php echo $key['bigo'] ?>"));
                $("input[name='uduse_type'][value='<?php echo $key['template']; ?>']").prop('checked', true);
            }
        <?php
        }
        ?>
    });

    // Datepicker Calender
    $("#udstrymd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    $("#udendymd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    // Check Error
    $(document).on('click', '#btnUpdateCL', function(e) {
        var Companycode = $("#udcompanycode").val();
        var Companyname = $("#udcompanyname").val();
        var Staff = $("#udstaff").val();
        var Telno = $("#udtelno").val();
        var Strymd = $("#udstrymd").val();
        var Endymd = $("#udendymd").val();
        var Address = $("#udaddress").val();
        var Joken = $("#udjoken").val();

        if (Companycode == "") {
            alert("<?php echo $manage_Ccode_empty; ?>");
            $("#udcompanycode").focus();
            return false;
        }

        if (isNaN(Companycode)) {
            alert("<?php echo $manage_Ccode_no; ?>");
            e.preventDefault();
            $("#udcompanycode").focus();
            return false;
        }

        if (Companyname == "") {
            alert("<?php echo $manage_Cname_empty; ?>");
            $("#udcompanyname").focus();
            return false;
        }

        if (Staff == "") {
            alert("<?php echo $manage_staff_empty; ?>");
            $("#staff").focus();
            return false;
        }

        if (Telno == "") {
            alert("<?php echo $manage_telno_empty; ?>");
            $("#udtelno").focus();
            return false;
        }

        if (Strymd == "") {
            alert("<?php echo $manage_strymd_empty; ?>");
            $("#udstrymd").focus();
            return false;
        }

        if (Endymd == "") {
            alert("<?php echo $manage_endymd_empty; ?>");
            $("#udendymd").focus();
            return false;
        }

        if (Address == "") {
            alert("<?php echo $manage_address_empty; ?>");
            $("#udaddress").focus();
            return false;
        }

        if (Joken == "") {
            alert("<?php echo $manage_joken_empty; ?>");
            $("#udjoken").focus();
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
        setTimeout(function() {
            NProgress.done();
        }, 500);
    }
</script>
<?php include('../inc/footer.php'); ?>