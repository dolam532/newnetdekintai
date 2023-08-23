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

    span.adminList_class {
        display: none;
    }
</style>
<title>管理者登</title>
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
    <form method="post">
        <div class="row">
            <div class="col-md-3 text-left">
                <div class="title_name">
                    <span class="text-left">管理者登</span>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchAdminGrade">区分 : <input type="text" name="searchAdminGrade" value="<?= $_POST['searchAdminGrade'] ?>" style="width: 100px;"></label>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchAdminName">会社名 : <input type="text" name="searchAdminName" value="<?= $_POST['searchAdminName'] ?>" style="width: 100px;"></label>
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
                    <th style="text-align: center; width: 10%;">ID</th>
                    <th style="text-align: center; width: 10%;">PASSWORD</th>
                    <th style="text-align: center; width: 10%;">社員名</th>
                    <th style="text-align: center; width: 14%;">Email</th>
                    <th style="text-align: center; width: 10%;">部署</th>
                    <th style="text-align: center; width: 10%;">区分</th>
                    <th style="text-align: center; width: 10%;">会社名</th>
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
                            <td><a href="#"><span class="showModal"><?= $key['uid'] ?><span class="adminList_class"><?= ',' . $key['companyid'] ?></span></span></a></td>
                            <td><span><?= $key['pwd'] ?></span></td>
                            <td><span><?= $key['name'] ?></span></td>
                            <td><span><?= $key['email'] ?></span></td>
                            <td><span><?= $key['dept'] ?></span></td>
                            <td><span><?= $key['grade'] ?></span></td>
                            <td><span><?= $key['companyname'] ?></span></td>
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
                                <div class="col-xs-12">
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
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="1000" style="text-align: left">
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
                            使用者編集
                            (<span id="usname"></span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="companycode">会社コード</label>
                                    <input type="text" class="form-control" name="udcompanycode" id="udcompanycode" placeholder="companycode" maxlength="10" style="text-align: left" readonly>
                                    <input type="hidden" name="udcompanyid" id="udcompanyid">
                                </div>
                                <div class="col-xs-9">
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
                                    <input type="text" class="form-control" name="udstrymd" id="udstrymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="endymd">契約期間(T)</label>
                                    <input type="text" class="form-control" name="udendymd" id="udendymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-9">
                                    <label for="address">住所</label>
                                    <input type="text" class="form-control" name="udaddress" id="udaddress" maxlength="150" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="use_yn"><strong>使用</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="uduse_yn" id="uduse_yn1" value="1" checked>使用
                                        <input type="radio" name="uduse_yn" id="uduse_yn2" value="0">中止
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="joken">契約条件</label>
                                    <input type="text" class="form-control" name="udjoken" id="udjoken" maxlength="200" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="udbigo" id="udbigo" maxlength="300" style="text-align: left">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-md-3"></div>
                            <div class="col-md-2">
                                <input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL" role="button" value="編集">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" name="DeleteCL" class="btn btn-warning" role="button" value="削除">
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
    $(document).on('click', '#btnRegCL', function(e) {
        var Companycode = $("#companycode").val();
        var Companyname = $("#companyname").val();
        var Staff = $("#staff").val();
        var Telno = $("#telno").val();
        var Strymd = $("#strymd").val();
        var Endymd = $("#endymd").val();
        var Address = $("#address").val();
        var Joken = $("#joken").val();

        if (Companycode == "") {
            alert("<?php echo $manage_Ccode_empty; ?>");
            $("#companycode").focus();
            return false;
        }

        if (isNaN(Companycode)) {
            alert("<?php echo $manage_Ccode_no; ?>");
            e.preventDefault();
            $("#companycode").focus();
            return false;
        }

        if (Companyname == "") {
            alert("<?php echo $manage_Cname_empty; ?>");
            $("#companyname").focus();
            return false;
        }

        if (Staff == "") {
            alert("<?php echo $manage_staff_empty; ?>");
            $("#staff").focus();
            return false;
        }

        if (Telno == "") {
            alert("<?php echo $manage_telno_empty; ?>");
            $("#telno").focus();
            return false;
        }

        if (Strymd == "") {
            alert("<?php echo $manage_strymd_empty; ?>");
            $("#strymd").focus();
            return false;
        }

        if (Endymd == "") {
            alert("<?php echo $manage_endymd_empty; ?>");
            $("#endymd").focus();
            return false;
        }

        if (Address == "") {
            alert("<?php echo $manage_address_empty; ?>");
            $("#address").focus();
            return false;
        }

        if (Joken == "") {
            alert("<?php echo $manage_joken_empty; ?>");
            $("#joken").focus();
            return false;
        }

        <?php
        if (!empty($admin_list)) {
            foreach ($admin_list as $key) {
        ?>
                if ('<?php echo $key['companycode'] ?>' == Companycode) {
                    alert("<?php echo $manage_Ccode_have; ?>");
                    $("#companycode").focus();
                    return false;
                }
        <?php
            }
        }
        ?>
    });

    // Datepicker Calender
    $("#strymd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    $("#endymd").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    // Funtion for click day of week
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var CompanyName = SeparateArr[0];
        var CompanyId = SeparateArr[1];

        <?php
        foreach ($admin_list as $key) {
        ?>
            if ('<?php echo $key['companyid'] ?>' === CompanyId && '<?php echo $key['companyname'] ?>' === CompanyName) {
                $("#usname").text('<?php echo $key['companyname'] ?>');
                $("#udcompanycode").text($('[name="udcompanycode"]').val("<?php echo $key['companycode'] ?>"));
                var udcompanyid = $("input[name=udcompanyid]:hidden");
                udcompanyid.val("<?php echo $key['companyid'] ?>");
                var udcompanyid = udcompanyid.val();
                $("#udcompanyname").text($('[name="udcompanyname"]').val("<?php echo $key['companyname'] ?>"));
                $("#udstaff").text($('[name="udstaff"]').val("<?php echo $key['staff'] ?>"));
                $("#udtelno").text($('[name="udtelno"]').val("<?php echo $key['telno'] ?>"));
                $("#udstrymd").text($('[name="udstrymd"]').val("<?php echo $key['strymd'] ?>"));
                $("#udendymd").text($('[name="udendymd"]').val("<?php echo $key['endymd'] ?>"));
                $("#udaddress").text($('[name="udaddress"]').val("<?php echo $key['address'] ?>"));
                $("input[name='uduse_yn'][value='<?php echo $key['use_yn']; ?>']").prop('checked', true);
                $("#udjoken").text($('[name="udjoken"]').val("<?php echo $key['joken'] ?>"));
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
    $(document).on('click', '#btnUpdateCL', function(e) {
        var Companyname = $("#udcompanyname").val();
        var Staff = $("#udstaff").val();
        var Telno = $("#udtelno").val();
        var Strymd = $("#udstrymd").val();
        var Endymd = $("#udendymd").val();
        var Address = $("#udaddress").val();
        var Joken = $("#udjoken").val();

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
</script>
<?php include('../inc/footer.php'); ?>