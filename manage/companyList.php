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

if ($_SESSION['auth_type'] == 1) { // if not admin 
    header("Location: ./../../index.php");
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

    /* 모달 팝업 lock */
    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<title>使用者登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <div class="row">
        <div class="col-md-3 text-left">
            <div class="title_name">
                <span class="text-left">使用者登録</span>
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="title_condition">
                <label for="searchUseyn">使用区分&nbsp;:</label>
                <input type="radio" name="searchUseyn" value="" checked="">全部
                <input type="radio" name="searchUseyn" value="1">使用
                <input type="radio" name="searchUseyn" value="0">中止
            </div>
        </div>
        <div class="col-md-3 text-left">
            <div class="title_condition">
                <label for="searchCompanyname">会社名&nbsp;:</label>
                <input type="text" id="searchCompanyname" name="searchCompanyname" style="width: 100px;">
            </div>
        </div>
        <div class="col-md-3 text-right">
            <div class="title_btn">
                <input type="button" id="btnSearch" value="検索 ">&nbsp;&nbsp;&nbsp;<input type="button" id="btnNew" value="新規 ">
            </div>
        </div>
    </div>
    <div class="form-group">
        <table class="table table-bordered datatable">
            <thead>
                <tr class="info">
                    <th style="text-align: center; width: 10%;">ID</th>
                    <th style="text-align: center; width: 20%;">会社名</th>
                    <th style="text-align: center; width: 8%;">担当者</th>
                    <th style="text-align: center; width: 12%;">telno</th>
                    <th style="text-align: center; width: 12%;">契約期間</th>
                    <th style="text-align: center; width: 5%;">使用</th>
                    <th style="text-align: center; width: 20%;">契約条件</th>
                    <th style="text-align: center; width: auto;">備考</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span>202001001</span></td>
                    <td><a href="http://old.netdekintai.com/netdekintai/info/companyList#"><span class="showModal" name="ccompanyname">ガナシス株式会社</span></a></td>
                    <td><span name="cstaff">坂本</span></td>
                    <td><span name="ctelno"> 03-6454-0461</span></td>
                    <td><span name="ckigan">2019/07/01 ~ 2099/12/31</span></td>
                    <td><span name="cuse_yn">使用</span>
                    </td>
                    <td><span name="cjoken">無料使用者</span></td>
                    <td><span name="cbigo">テスト</span>
                        <input type="hidden" name="tcompanyid" value="1">
                        <input type="hidden" name="tcompanycode" value="202001001">
                        <input type="hidden" name="tcompanyname" value="ガナシス株式会社">
                        <input type="hidden" name="tstaff" value="坂本">
                        <input type="hidden" name="ttelno" value=" 03-6454-0461">
                        <input type="hidden" name="tstrymd" value="2019/07/01">
                        <input type="hidden" name="tendymd" value="2099/12/31">
                        <input type="hidden" name="tuse_yn" value="1">
                        <input type="hidden" name="tjoken" value="無料使用者">
                        <input type="hidden" name="tbigo" value="テスト">
                        <input type="hidden" name="taddress" value="〒165-0026 東京都中野区新井5-29-1 西武信用金庫新井http://localhost:8080/web/info/companyList#薬師ビル502号">
                    </td>
                </tr>

                <tr>
                    <td><span>202003001</span></td>
                    <td><a href="http://old.netdekintai.com/netdekintai/info/companyList#"><span class="showModal" name="ccompanyname">PayPay</span></a></td>
                    <td><span name="cstaff">Pay</span></td>
                    <td><span name="ctelno">03-2222-3333</span></td>
                    <td><span name="ckigan">2020/02/01 ~ 2020/12/31</span></td>
                    <td><span name="cuse_yn">
                            使用


                        </span>
                    </td>
                    <td><span name="cjoken"></span></td>
                    <td><span name="cbigo"></span>
                        <input type="hidden" name="tcompanyid" value="2">
                        <input type="hidden" name="tcompanycode" value="202003001">
                        <input type="hidden" name="tcompanyname" value="PayPay">
                        <input type="hidden" name="tstaff" value="Pay">
                        <input type="hidden" name="ttelno" value="03-2222-3333">
                        <input type="hidden" name="tstrymd" value="2020/02/01">
                        <input type="hidden" name="tendymd" value="2020/12/31">
                        <input type="hidden" name="tuse_yn" value="1">
                        <input type="hidden" name="tjoken" value="">
                        <input type="hidden" name="tbigo" value="">
                        <input type="hidden" name="taddress" value="">
                    </td>
                </tr>



            </tbody>
        </table>
    </div>

    <!-- 新規 -->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        使用者登録(<span id="sname"></span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-3">
                                <label for="companycode">ID</label>
                                <input type="text" class="form-control" id="companycode" placeholder="ID" required="required" maxlength="10" style="text-align: left">
                                <input type="hidden" id="seq" value="">
                                <input type="hidden" id="companyid" value="">
                            </div>
                            <div class="col-xs-9">
                                <label for="companyname">会社名</label>
                                <input type="text" class="form-control" id="companyname" placeholder="companyname" required="required" maxlength="20" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-3">
                                <label for="staff">担当者名</label>
                                <input type="text" class="form-control" id="staff" placeholder="staff" required="required" maxlength="100" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="telno">telno</label>
                                <input type="text" class="form-control" id="telno" placeholder="telno" required="required" maxlength="100" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="strymd">契約期間(F)</label>
                                <input type="text" class="form-control hasDatepicker" id="strymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="endymd">契約期間(T)</label>
                                <input type="text" class="form-control hasDatepicker" id="endymd" maxlength="10" placeholder="2019/01/01" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-9">
                                <label for="address">住所</label>
                                <input type="text" class="form-control" id="address" maxlength="200" style="text-align: left">
                            </div>
                            <div class="col-xs-3">
                                <label for="use_yn"><strong>使用</strong></label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="use_yn" value="1">使用
                                    <input type="radio" name="use_yn" value="0">中止
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="joken">契約条件</label>
                                <input type="text" class="form-control" id="joken" maxlength="200" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="bigo">備考</label>
                                <input type="text" class="form-control" id="bigo" maxlength="300" style="text-align: left">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="http://old.netdekintai.com/netdekintai/info/companyList#" role="button">登録 </a></p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnRet" href="http://old.netdekintai.com/netdekintai/info/companyList#" role="button">閉じる </a></p>
                        </div>
                        <div class="col-xs-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php include('../inc/footer.php'); ?>