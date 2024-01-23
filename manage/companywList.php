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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateCWL'])) {
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
                    <th style="text-align: center; width: 8%;">休暇届テンプレート</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($companyworktime_list)) { ?>
                    <tr>
                        <td colspan="10" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($companyworktime_list)) {
                    foreach ($companyworktime_list as $key) {
                    ?>
                        <tr>
                            <td><span><?= $key['companyid'] ?></span></td>
                            <td>
                                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                    <a href="#">
                                        <span class="showModal"><?= $key['companyname'] ?><span class="companyList_class"><?= ',' . $key['companyid'] ?></span></span>
                                    </a>
                                <?php else : ?>
                                    <span><?= $key['companyname'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span><?= $key['starttime'] ?></span></td>
                            <td><span><?= $key['endtime'] ?></span></td>
                            <td><span><?= $key['breakstarttime'] ?></span></td>
                            <td><span><?= $key['breakstarttime'] ?></span></td>
                            <td><span><?= $key['worktime'] ?></span></td>
                            <td><span><?= $key['breaktime'] ?></span></td>
                            <td><span><?= $key['kyukatemplate'] ?></span></td>
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
                                    <label for="kyukatemplate"><strong>休暇届テンプレート</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="kyukatemplate" name="kyukatemplate" checked value="<?php echo array_keys(ConstArray::$search_kyukatemplate)[0]; ?>">
                                        <?php echo ConstArray::$search_kyukatemplate[array_keys(ConstArray::$search_kyukatemplate)[0]]; ?>
                                        <label class="template-notice-text"> (日付けのみ) </label>
                                        <br />
                                        <input type="radio" id="kyukatemplate" name="kyukatemplate" value="<?php echo array_keys(ConstArray::$search_kyukatemplate)[1]; ?>">
                                        <?php echo ConstArray::$search_kyukatemplate[array_keys(ConstArray::$search_kyukatemplate)[1]]; ?>
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
                                    <label for="companyid">会社ID</label>
                                    <input type="text" class="form-control" name="udcompanyid" id="udcompanyid" style="text-align: left" readonly>
                                </div>
                                <div class="col-xs-4">
                                    <label for="companyname">会社名</label>
                                    <input type="text" class="form-control" name="udcompanyname" id="udcompanyname" style="text-align: left" readonly>
                                </div>
                                <div class="col-xs-6">
                                    <label for="kyukatemplate"><strong>休暇届テンプレート</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="udkyukatemplate" name="udkyukatemplate" value="<?php echo array_keys(ConstArray::$search_kyukatemplate)[0]; ?>">
                                        <?php echo ConstArray::$search_kyukatemplate[array_keys(ConstArray::$search_kyukatemplate)[0]]; ?>
                                        <label class="template-notice-text"> (日付けのみ) </label>
                                        <br />
                                        <input type="radio" id="udkyukatemplate" name="udkyukatemplate" value="<?php echo array_keys(ConstArray::$search_kyukatemplate)[1]; ?>">
                                        <?php echo ConstArray::$search_kyukatemplate[array_keys(ConstArray::$search_kyukatemplate)[1]]; ?>
                                        <label class="template-notice-text"> (日付け+時間)</label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="starttime">業務開始時間</label>
                                    <input type="text" class="form-control" name="udstarttime" id="udstarttime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="endtime">業務終了時間</label>
                                    <input type="text" class="form-control" name="udendtime" id="udendtime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="worktime">業務時間</label>
                                    <input type="text" class="form-control" name="udworktime" id="udworktime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="breakstarttime">休憩開始時間</label>
                                    <input type="text" class="form-control" name="udbreakstarttime" id="udbreakstarttime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="breakendtime">休憩終了時間</label>
                                    <input type="text" class="form-control" name="udbreakendtime" id="udbreakendtime" placeholder="00:00" maxlength="5" style="text-align: left">
                                </div>
                                <div class="col-xs-4">
                                    <label for="breaktime">休憩時間</label>
                                    <input type="text" class="form-control" name="udbreaktime" id="udbreaktime" placeholder="00:00" maxlength="5" style="text-align: left">
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
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <input type="submit" name="btnUpdateCWL" class="btn btn-primary" id="btnUpdateCWL" role="button" value="編集">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                            </div>
                            <div class="col-md-4"></div>
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

        var UdstartTime = $("#udstarttime").val();
        var UdendTime = $("#udendtime").val();
        var UdbreakStartTime = $("#udbreakstarttime").val();
        var UdbreakEndTime = $("#udbreakendtime").val();

        var UdworkTimeDiff = timeDiff(UdstartTime, UdendTime);
        var UdbreakTimeDiff = timeDiff(UdbreakStartTime, UdbreakEndTime);
        var UdworkHours = UdworkTimeDiff.hours - UdbreakTimeDiff.hours;
        var UdworkMinutes = UdworkTimeDiff.minutes - UdbreakTimeDiff.minutes;

        // Ensure minutes are positive
        if (UdworkMinutes < 0) {
            UdworkMinutes += 60;
            UdworkHours -= 1;
        }
        var UdWorkTime = (UdworkHours < 10 ? "0" : "") + UdworkHours + ":" + (UdworkMinutes < 10 ? "0" : "") + UdworkMinutes;

        // Update fields
        $("#udworktime").val(UdWorkTime);
        $("#udbreaktime").val(UdbreakTimeDiff.formatted);
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
    $("#udstarttime, #udendtime, #udbreakstarttime, #udbreakendtime").on("change", calculateTime);

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
        var Companyid = $("#companyid").val();
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

        <?php
        if (!empty($companyworktime_list)) {
            foreach ($companyworktime_list as $key) {
        ?>
                if ('<?php echo $key['companyid'] ?>' == Companyid) {
                    alert("<?php echo $manage_CWcompany_have; ?>");
                    $("#companyid").focus();
                    return false;
                }
        <?php
            }
        }
        ?>
    });


    // Funtion for click day of week
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var CompanyName = SeparateArr[0];
        var CompanyId = SeparateArr[1];

        <?php
        foreach ($companyworktime_list as $key) {
        ?>
            if ('<?php echo $key['companyid'] ?>' === CompanyId && '<?php echo $key['companyname'] ?>' === CompanyName) {
                $("#usname").text('<?php echo $key['companyname'] ?>');
                $("#udcompanyid").text($('[name="udcompanyid"]').val("<?php echo $key['companyid'] ?>"));
                $("#udcompanyname").text($('[name="udcompanyname"]').val("<?php echo $key['companyname'] ?>"));
                $("input[name='udkyukatemplate'][value='<?php echo $key['kyukatemplate']; ?>']").prop('checked', true);
                $("#udstarttime").text($('[name="udstarttime"]').val("<?php echo $key['starttime'] ?>"));
                $("#udendtime").text($('[name="udendtime"]').val("<?php echo $key['endtime'] ?>"));
                $("#udworktime").text($('[name="udworktime"]').val("<?php echo $key['worktime'] ?>"));
                $("#udbreakstarttime").text($('[name="udbreakstarttime"]').val("<?php echo $key['breakstarttime'] ?>"));
                $("#udbreakendtime").text($('[name="udbreakendtime"]').val("<?php echo $key['breakendtime'] ?>"));
                $("#udbreaktime").text($('[name="udbreaktime"]').val("<?php echo $key['breaktime'] ?>"));
                $("#udbigo").text($('[name="udbigo"]').val("<?php echo $key['bigo'] ?>"));
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
    $(document).on('click', '#btnUpdateCWL', function(e) {
        var Companyname = $("#udcompanyname").val();
        var Starttime = $("#udstarttime").val();
        var Endtime = $("#udendtime").val();
        var Breakstarttime = $("#udbreakstarttime").val();
        var Breakendtime = $("#udbreakendtime").val();

        if (Companyname == "") {
            alert("<?php echo $manage_CWname_empty; ?>");
            $("#udcompanyname").focus();
            return false;
        }

        if (Starttime == "") {
            alert("<?php echo $manage_CWstarttime_empty; ?>");
            $("#udstarttime").focus();
            return false;
        }

        if (Endtime == "") {
            alert("<?php echo $manage_CWendtime_empty; ?>");
            $("#udendtime").focus();
            return false;
        }

        if (Breakstarttime == "") {
            alert("<?php echo $manage_CWBreakstarttime_empty; ?>");
            $("#udbreakstarttime").focus();
            return false;
        }

        if (Breakendtime == "") {
            alert("<?php echo $manage_CWBreakendtime_empty; ?>");
            $("#udbreakendtime").focus();
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