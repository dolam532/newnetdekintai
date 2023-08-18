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
<title>使用者登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegCL'])) {
    ?>
        <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?php echo $_SESSION['save_success']; ?>
        </div>
    <?php
        unset($_SESSION['save_success']);
    }
    ?>
    <form method="post">
        <div class="row">
            <div class="col-md-3 text-left">
                <div class="title_name">
                    <span class="text-left">使用者登録</span>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="title_condition">
                    <label for="searchUseyn">使用区分&nbsp;:</label>
                    <?php
                    foreach (ConstArray::$search_company as $key => $value) {
                    ?>
                        <input type='radio' name='searchUseyn' value='<?= $key ?>' <?php if ($key == $_POST['searchUseyn']) {
                                                                                        echo ' checked="checked"';
                                                                                    } ?>>
                        <?= $value ?>
                        </input>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label for="searchCompanyname">会社名&nbsp;:</label>
                    <input type="text" name="searchCompanyname" value="<?= $_POST['searchCompanyname'] ?>" style="width: 200px;">
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <input type="submit" name="SearchButtonCL" value="検索">&nbsp;&nbsp;&nbsp;
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
                    <th style="text-align: center; width: 20%;">会社名</th>
                    <th style="text-align: center; width: 8%;">担当者</th>
                    <th style="text-align: center; width: 12%;">電話番号</th>
                    <th style="text-align: center; width: 12%;">契約期間</th>
                    <th style="text-align: center; width: 5%;">使用</th>
                    <th style="text-align: center; width: 20%;">契約条件</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($company_list)) { ?>
                    <tr>
                        <td colspan="8" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($company_list)) {
                    foreach ($company_list as $key) {
                    ?>
                        <tr>
                            <td><span><?= $key['companycode'] ?></span></td>
                            <td><a href="#"><span class="showModal"><?= $key['companyname'] ?></span></a></td>
                            <td><span><?= $key['staff'] ?></span></td>
                            <td><span><?= $key['telno'] ?></span></td>
                            <td><span><?= $key['strymd'] . '~' . $key['endymd'] ?></span></td>
                            <td>
                                <span>
                                    <?php if ($key['use_yn'] == "1") {
                                        echo "<p style='font-weight:bold;color:green;'>使用</p>";
                                    } else {
                                        echo "<p style='font-weight:bold;color:red;'>中止</p>";
                                    }
                                    ?>
                                </span>
                            </td>
                            <td><span><?= $key['joken'] ?></span></td>
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
                            使用者登録(<span>New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="companycode">ID</label>
                                    <input type="text" class="form-control" name="companycode" id="companycode" placeholder="ID" maxlength="10" style="text-align: left">
                                </div>
                                <div class="col-xs-9">
                                    <label for="companyname">会社名</label>
                                    <input type="text" class="form-control" name="companyname" id="companyname" placeholder="companyname" maxlength="20" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="staff">担当者名</label>
                                    <input type="text" class="form-control" name="staff" id="staff" placeholder="staff" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="telno">電話番号</label>
                                    <input type="text" class="form-control" name="telno" id="telno" placeholder="telno" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="strymd">契約期間(F)</label>
                                    <input type="text" class="form-control" name="strymd" id="strymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="endymd">契約期間(T)</label>
                                    <input type="text" class="form-control" name="endymd" id="endymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-9">
                                    <label for="address">住所</label>
                                    <input type="text" class="form-control" name="address" id="address" maxlength="150" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="use_yn"><strong>使用</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="use_yn" value="1" checked>使用
                                        <input type="radio" name="use_yn" value="0">中止
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="joken">契約条件</label>
                                    <input type="text" class="form-control" name="joken" id="joken" maxlength="200" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="300" style="text-align: left">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegCL" class="btn btn-primary" id="btnRegCL" role="button" value="登録">
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
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <form method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            使用者登録(<span>New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>

                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="companycode">ID</label>
                                    <input type="text" class="form-control" name="companycode" id="companycode" placeholder="ID" maxlength="10" style="text-align: left">
                                    <input type="hidden" name="companyid" id="companyid">
                                </div>
                                <div class="col-xs-9">
                                    <label for="companyname">会社名</label>
                                    <input type="text" class="form-control" name="companyname" id="companyname" placeholder="companyname" maxlength="20" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-3">
                                    <label for="staff">担当者名</label>
                                    <input type="text" class="form-control" name="staff" id="staff" placeholder="staff" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="telno">電話番号</label>
                                    <input type="text" class="form-control" name="telno" id="telno" placeholder="telno" maxlength="100" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="strymd">契約期間(F)</label>
                                    <input type="text" class="form-control" name="strymd" id="strymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="endymd">契約期間(T)</label>
                                    <input type="text" class="form-control" name="endymd" id="endymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-9">
                                    <label for="address">住所</label>
                                    <input type="text" class="form-control" name="address" id="address" maxlength="150" style="text-align: left">
                                </div>
                                <div class="col-xs-3">
                                    <label for="use_yn"><strong>使用</strong></label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="use_yn" value="1" checked>使用
                                        <input type="radio" name="use_yn" value="0">中止
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="joken">契約条件</label>
                                    <input type="text" class="form-control" name="joken" id="joken" maxlength="200" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="bigo">備考</label>
                                    <input type="text" class="form-control" name="bigo" id="bigo" maxlength="300" style="text-align: left">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegCL" class="btn btn-primary" id="btnRegCL" role="button" value="登録">
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
        if (!empty($company_list)) {
            foreach ($company_list as $key) {
        ?>
                if ('<?php echo $key['companycode'] ?>' == Companycode) {
                    alert("<?php echo $manage_Ccode_have; ?>");
                    $("#holiday").focus();
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
</script>
<?php include('../inc/footer.php'); ?>