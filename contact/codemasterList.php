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
<title>基礎コード登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <div class="row">
        <div class="col-md-10">
            <div class="title_name">
                <span class="text-left">基礎コード登録</span>
            </div>
        </div>
        <div class="col-md-2 text-right">
            <div class="title_btn">
                <input type="button" id="btnNew" value="新規 ">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 30%;">Code</th>
                        <th style="text-align: center; width: 70%;">Type</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center"><span style="font-weight:bold">01</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#" onclick="fn_TypeClick(&#39;01&#39;)"><span style="font-weight:bold">部署</span></a></td>
                    </tr>
                    <tr>
                        <td align="center"><span style="font-weight:normal">02</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#" onclick="fn_TypeClick(&#39;02&#39;)"><span style="font-weight:normal">休暇種類</span></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8">
            <table class="table table-bordered datatable" id="tblcodebase">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 20%;">Code</th>
                        <th style="text-align: center; width: 40%;">名</th>
                        <th style="text-align: center; width: 40%;">備考</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center"><span name="ccode">01</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">開発部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="01">
                            <input type="hidden" name="tname" value="開発部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span name="ccode">02</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">管理部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="02">
                            <input type="hidden" name="tname" value="管理部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span name="ccode">01</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">開発部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="01">
                            <input type="hidden" name="tname" value="開発部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span name="ccode">02</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">管理部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="02">
                            <input type="hidden" name="tname" value="管理部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span name="ccode">01</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">開発部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="01">
                            <input type="hidden" name="tname" value="開発部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                    <tr>
                        <td align="center"><span name="ccode">02</span></td>
                        <td><a href="http://old.netdekintai.com/netdekintai/info/codemasterList#"><span name="cname" class="showModal">管理部</span></a></td>
                        <td><span name="cremark"></span>
                            <input type="hidden" name="tcompanyid" value="1">
                            <input type="hidden" name="tuid" value="">
                            <input type="hidden" name="ttypecode" value="01">
                            <input type="hidden" name="tcode" value="02">
                            <input type="hidden" name="tname" value="管理部">
                            <input type="hidden" name="tremark" value="">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 新規 -->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        基礎コード登録(<span id="sname">New</span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="code">Code</label>
                                <input type="text" class="form-control" id="code" placeholder="" style="text-align: center" disabled="">
                                <input type="hidden" id="seq" value="">
                                <input type="hidden" id="uid" value="admin">
                                <input type="hidden" id="companyid" value="1">
                                <input type="hidden" id="typecode" value="01">
                            </div>
                            <div class="col-md-5">
                                <label for="name">名</label>
                                <input type="text" class="form-control" id="name" placeholder="" style="text-align: left">
                            </div>
                            <div class="col-md-5">
                                <label for="remark">備考</label>
                                <input type="text" class="form-control" id="remark" placeholder="" style="text-align: left">
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-md-3"></div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="http://old.netdekintai.com/netdekintai/info/codemasterList#" role="button">登録 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel" href="http://old.netdekintai.com/netdekintai/info/codemasterList#" role="button">削除 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnRet" href="http://old.netdekintai.com/netdekintai/info/codemasterList#" role="button">閉じる </a></p>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

</script>
<?php include('../inc/footer.php'); ?>