<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/usermodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}

?>

<title>勤務時間タイプ表</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <div class="row">
        <div class="col-md-4">
            <div class="title_name">
                <span class="text-left">勤務時間タイプ表</span>
            </div>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-2"></div>
    </div>

    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 5%;">No</th>
                    <th style="text-align: center; width: 20%;">勤務時間タイプ</th>
                    <th style="text-align: center; width: 15%;">社員名</th>
                    <th style="text-align: center; width: 26%;" colspan="2">契約期間</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($user_list_g)) { ?>
                    <tr>
                        <td colspan="6" align="center"><?php echo $data_save_no; ?></td>
                    </tr>
                    <?php } elseif (!empty($user_list_g)) {
                    $index = 1;
                    foreach ($user_list_g as $genba) {
                    ?>
                        <tr>
                            <td><span><?= $index ?></span></td>
                            <td><span><?= $genba['genbaname'] ?></span></td>
                            <td><span><?= $genba['name'] ?></span></td>
                            <td><span><?= $genba['genstrymd'] ?></span></td>
                            <td><span><?= $genba['genendymd'] ?></span></td>
                            <td><span><?= $genba['bigo'] ?></span></td>
                        </tr>
                <?php
                        $index++;
                    }
                } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include('../inc/footer.php'); ?>