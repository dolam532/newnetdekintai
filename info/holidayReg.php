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
</style>
<title>祝日登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegHdr'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateHdr'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelHdr'])) {
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
        <div class="col-md-5 text-left">
            <div class="title_name">
                <span class="text-left">祝日登録</span>
            </div>
        </div>
        <form method="post">
            <div class="col-md-4 text-center">
                <div class="title_condition">
                    <label>基準日 :
                        <select id="selyy" name="selyy" class="seldate" style="padding:5px;" onchange="this.form.submit()">
                            <?php
                            foreach (ConstArray::$search_year as $key => $value) {
                            ?>
                                <option value="<?= $key ?>" <?php if ($value == $year) {
                                                                echo ' selected="selected"';
                                                            } ?>>
                                    <?= $value ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </label>
                </div>
            </div>
        </form>
        <div class="col-md-3 text-right">
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
                    <th style="text-align: center; width: 20%;">年</th>
                    <th style="text-align: center; width: 20%;">祝日</th>
                    <th style="text-align: center; width: 60%;">Remark</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($holiday_list)) { ?>
                    <tr>
                        <td colspan="3" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($holiday_list)) {
                    foreach ($holiday_list as $key) {
                    ?>
                        <tr>
                            <td><span><?= $key['holiyear'] ?></span></td>
                            <td>
                                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                    <a href="#"><span class="showModal"><?= $key['holiday'] ?></span></a>
                                <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
                                    <?= $key['holiday'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td><span><?= $key['holiremark'] ?></span></td>
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
                        祝日登録<span id="sname"></span>
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-2 text-right">
                                <label for="holiday">祝日</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" name="holiday" id="holiday" maxlength="10">
                                <input type="hidden" name="companyid" value="<?= constant('GANASYS_COMPANY_ID') ?>">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="holiremark">Remark</label>
                            </div>
                            <div class="col-xs-5">
                                <input type="text" class="form-control text-left" name="holiremark" id="holiremark" maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnRegHdr" class="btn btn-primary" id="btnReg" role="button" value="登録">
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
                            <div class="col-xs-2 text-right">
                                <label for="holiday">祝日</label>
                            </div>
                            <div class="col-xs-3">
                                <input type="text" class="form-control text-center" name="udholiday" id="udholiday" maxlength="10" readonly>
                                <input type="hidden" name="udcompanyid" value="<?= constant('GANASYS_COMPANY_ID') ?>">
                                <input type="hidden" name="udholiyear" id="udholiyear">
                            </div>
                            <div class="col-xs-2 text-right">
                                <label for="holiremark">Remark</label>
                            </div>
                            <div class="col-xs-5">
                                <input type="text" class="form-control text-left" name="udholiremark" id="udholiremark" maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnUpdateHdr" class="btn btn-primary" id="btnUpdate" role="button" value="登録">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnDelHdr" class="btn btn-warning" id="btnDel" role="button" value="削除">
                            </p>
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
<script>
    // Submit for select
    jQuery(function() {
        jQuery('.seldate').change(function() {
            this.form.submit();
        });
    });

    // New button: popup & clear 
    $(document).on('click', '#btnNew', function(e) {
        $('#modal').modal('toggle');
    });

    // Datepicker Calender
    $("#holiday").datepicker({
        changeYear: true,
        dateFormat: 'yy/mm/dd'
    });

    // Check Error
    $(document).on('click', '#btnReg', function(e) {
        var Holiday = $("#holiday").val();

        if (Holiday == "") {
            alert("<?php echo $info_holiday_empty; ?>");
            $("#holiday").focus();
            return false;
        }

        <?php
        if (!empty($holiday_list)) {
            foreach ($holiday_list as $key) {
        ?>
                if ('<?php echo $key['holiday'] ?>' == Holiday) {
                    alert("<?php echo $info_holiday_have; ?>");
                    $("#holiday").focus();
                    return false;
                }
        <?php
            }
        }
        ?>
    });

    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        var Holiday = $(this).text();
        $('#modal2').modal('toggle');

        <?php
        if (!empty($holiday_list)) {
            foreach ($holiday_list as $key) {
        ?>
                if ('<?php echo $key['holiday'] ?>' == Holiday) {
                    $("#usname").text('<?php echo $key['holiday'] ?>');
                    $("#udholiday").text($('[name="udholiday"]').val("<?php echo $key['holiday'] ?>"));
                    $("#udholiremark").text($('[name="udholiremark"]').val("<?php echo $key['holiremark'] ?>"));

                    var udholiyear = $("input[name=udholiyear]:hidden");
                    udholiyear.val("<?php echo $key['holiyear'] ?>");
                    var udholiyear = udholiyear.val();
                }
        <?php
            }
        }
        ?>
    });
</script>
<?php include('../inc/footer.php'); ?>