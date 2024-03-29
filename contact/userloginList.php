<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/contactmodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
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
</style>
<title>社員ログイン内訳</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <form method="post">
        <div class="row">
            <div class="col-md-5 text-left">
                <div class="title_name">
                    <span class="text-left">社員ログイン内訳</span>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="title_condition">
                    <label>日付 :
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
                        <select id="selmm" name="selmm" class="seldate" style="padding:5px;" onchange="this.form.submit()">
                            <?php
                            foreach (ConstArray::$search_month as $key => $value) {
                            ?>
                                <option value="<?= $key ?>" <?php if ($value == $month) {
                                                                echo ' selected="selected"';
                                                            } ?>>
                                    <?= $value ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <select id="seldd" name="seldd" class="seldate" style="padding:5px;" onchange="this.form.submit()">
                            <?php
                            foreach (ConstArray::$search_day as $key => $value) {
                            ?>
                                <option value="<?= $key ?>" <?php if ($value == $day) {
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
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
                </div>
            </div>
        </div>
    </form>
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                        <th style="text-align: center; width: 12%;">会社名</th>
                    <?php endif; ?>
                    <th style="text-align: center; width: 10%;">ID</th>
                    <th style="text-align: center; width: 15%;">日付</th>
                    <th style="text-align: center; width: 20%;">login time</th>
                    <th style="text-align: center; width: 20%;">IP</th>
                    <th style="text-align: center; width: auto;">Domain</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($userlogin_list)) { ?>
                    <tr>
                        <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                            <td colspan="6" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        <?php else : ?>
                            <td colspan="5" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php } elseif (!empty($userlogin_list)) {
                    foreach ($userlogin_list as $key) {
                    ?>
                        <tr>
                            <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                                <td>
                                    <span><?= $key['companyname'] ?></span>
                                </td>
                            <?php endif; ?>
                            <td><span><?= $key['uid'] ?></span></td>
                            <td><span><?= $key['workymd'] ?></span></td>
                            <td><span><?= $key['logtime'] ?></span></td>
                            <td><span><?= $key['ipaddress'] ?></span></td>
                            <td><span><?= $key['domain'] ?></span></td>
                        </tr>
                <?php
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    // Submit for select
    jQuery(function() {
        jQuery('.seldate').change(function() {
            this.form.submit();
        });
        setTimeout(hideLoadingOverlay, 200);
        startLoading();
    });

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
        }, 200);
    }
</script>
<?php include('../inc/footer.php'); ?>