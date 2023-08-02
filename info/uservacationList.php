<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/header.php');
include('../model/infomodel.php');
// include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}

if ($_SESSION['auth_type'] == 1) { // if not admin 
    header("Location: ./../../index.php");
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

    /* 모달 팝업 lock */
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<title>年次休暇登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <div class="row">
        <div class="col-md-3 text-left">
            <div class="title_name">
                <span class="text-left">年次休暇登録</span>
            </div>
        </div>
        <div class="col-md-3 text-left">
            <div class="title_condition">
                <label>基準日 : <input type="text" id="searchYmd" name="searchYmd" value="" style="width: 100px; text-align:center" class="hasDatepicker"></label>
            </div>
        </div>
        <div class="col-md-3 text-left">
            <div class="title_condition">
                <label>社員名 : <input type="text" id="searchName" name="searchName" value="" style="width: 100px;"></label>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <div class="title_btn">
                <input type="button" id="btnSearch" value="検索 ">
            </div>
        </div>
    </div>

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
                            <td align="center"><?= $key['vacationstr'] ?></td>
                            <td><a href="#"><span class="showModal"><?= $key['holiday'] ?></span></a></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td align="center"><span><?= $key['holiyear'] ?></span></td>
                            <td><span><?= $key['holiyear'] ?></span></td>
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
            <div class="modal-content">
                <div class="modal-header">
                    年次休暇登録(<span id="sname"></span>)
                    <button class="close" data-dismiss="modal">x</button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="uid">ID</label>
                            <input type="text" class="form-control" id="uid" placeholder="" style="text-align: left">
                            <input type="hidden" id="vacationid" value="">
                        </div>
                        <div class="col-md-3">
                            <label for="name">名</label>
                            <input type="text" class="form-control" id="name" placeholder="" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="inymd">入社日</label>
                            <input type="text" class="form-control" id="inymd" placeholder="" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="yearcnt">勤続年数</label>
                            <input type="text" class="form-control" id="yearcnt" placeholder="" style="text-align: center">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="vacationstr">年次開始日</label>
                            <input type="text" class="form-control hasDatepicker" id="vacationstr" placeholder="" required="required" maxlength="10" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="vacationend">年次終了日</label>
                            <input type="text" class="form-control hasDatepicker" id="vacationend" placeholder="" required="required" maxlength="10" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="oldcnt">前年残数</label>
                            <input type="text" class="form-control" id="oldcnt" placeholder="" required="required" maxlength="2" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="newcnt">当年付与</label>
                            <input type="text" class="form-control" id="newcnt" required="required" placeholder="" maxlength="2" style="text-align: center">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-3">
                            <label for="usecnt">使用(日)</label>
                            <input type="text" class="form-control" id="usecnt" placeholder="" required="required" maxlength="2" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="usetime">使用(時)</label>
                            <input type="text" class="form-control" id="usetime" placeholder="" required="required" maxlength="2" style="text-align: center">
                        </div>
                        <div class="col-md-3">
                            <label for="restcnt">残数(日)</label>
                            <input type="text" class="form-control" id="restcnt" placeholder="" required="required" maxlength="2" style="text-align: center">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center">
                    <div class="col-md-3"></div>
                    <div class="col-md-2">
                        <p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="http://old.netdekintai.com/netdekintai/info/searchUserVacationList?searchYmd=2023/08/02&amp;searchName=%E6%96%B0%E4%BA%95%20%E4%B8%80%E9%83%8E#" role="button">登録 </a></p>
                    </div>
                    <div class="col-md-2">
                        <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel" href="http://old.netdekintai.com/netdekintai/info/searchUserVacationList?searchYmd=2023/08/02&amp;searchName=%E6%96%B0%E4%BA%95%20%E4%B8%80%E9%83%8E#" role="button">削除 </a></p>
                    </div>
                    <div class="col-md-2">
                        <p class="text-center"><a class="btn btn-primary btn-md" id="btnRet" href="http://old.netdekintai.com/netdekintai/info/searchUserVacationList?searchYmd=2023/08/02&amp;searchName=%E6%96%B0%E4%BA%95%20%E4%B8%80%E9%83%8E#" role="button">閉じる </a></p>
                    </div>
                    <div class="col-md-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php include('../inc/footer.php'); ?>