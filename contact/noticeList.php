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
<title>お知らせ</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <div class="row">
        <div class="col-md-3 text-left">
            <div class="title_name">
                <span class="text-left">お知らせ</span>
            </div>
        </div>
        <div class="col-md-2 text-left">
            <div class="title_condition">
                <label>
                    <input type="radio" name="rdoSearch" value="title" checked="">タイトル
                    <input type="radio" name="rdoSearch" value="content">内容
                </label>
            </div>
        </div>
        <div class="col-md-4 text-left">
            <div class="title_condition">
                <label>タイトル : <input type="text" id="searchKeyword" name="searchKeyword" value="" style="width: 200px;">
                </label>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <div class="title_btn">
                <input type="submit" name="SearchButton" value="検索">&nbsp;&nbsp;&nbsp;
                <input type="button" id="btnNew" value="新規">
            </div>
        </div>
        <div class="form-group">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 5%;">No</th>
                        <th style="text-align: center; width: auto;">タイトル</th>
                        <th style="text-align: center; width: 10%;">作成者</th>
                        <th style="text-align: center; width: 15%;">作成日</th>
                        <th style="text-align: center; width: 25%;">確認者</th>
                        <th style="text-align: center; width: 5%;">view</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($notice_list)) { ?>
                        <tr>
                            <td colspan="6" align="center"><?php echo $data_save_no; ?></td>
                        </tr>
                        <?php } elseif (!empty($notice_list)) {
                        foreach ($notice_list as $key) {
                        ?>
                            <tr>
                                <td><span><?= $key['bid'] ?></span></td>
                                <td style="text-align:left"><a href="#"><span class="showModal"><?= $key['title'] ?></span></a></td>
                                <td><span><?= $key['name'] ?></span></td>
                                <td><span><?= $key['reg_dt'] ?></span></td>
                                <td><span><?= $key['reader'] ?></span></td>
                                <td><span><?= $key['viewcnt'] ?></span></td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        お知らせ登録(<span id="sname">??</span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="title">タイトル</label>
                                <input type="text" class="form-control" id="title" placeholder="Title">
                                <input type="hidden" id="seq" value="0">
                                <input type="hidden" id="bid" value="4">
                                <input type="hidden" id="uid" value="admin">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="content">内容</label>
                                <textarea type="text" class="form-control" rows="5" id="content" placeholder=""></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="reader">確認者</label>
                                <input type="text" class="form-control" id="reader" placeholder="" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-4">
                                <label for="uname">作成者</label>
                                <input type="text" class="form-control" id="uname" placeholder="" style="text-align: center">
                            </div>
                            <div class="col-xs-4">
                                <label for="reg_dt">作成日</label>
                                <input type="text" class="form-control" id="reg_dt" placeholder="" required="required" style="text-align: center">
                            </div>
                            <div class="col-xs-4">
                                <label for="viewcnt">view Cnt</label>
                                <input type="text" class="form-control" id="viewcnt" placeholder="" style="text-align: center">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-3"></div>
                        <div class="col-xs-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="http://old.netdekintai.com/netdekintai/info/noticeList#" role="button">登録 </a></p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel" href="http://old.netdekintai.com/netdekintai/info/noticeList#" role="button">削除 </a></p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center"><a class="btn btn-warning btn-md" id="btnRet" href="http://old.netdekintai.com/netdekintai/info/noticeList#" role="button">閉じる </a></p>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>

    </script>
    <?php include('../inc/footer.php'); ?>