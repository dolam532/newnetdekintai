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
                    <form id="searchForm" method="post">
                        <?php
                        foreach (ConstArray::$search_notice as $key => $value) {
                        ?>
                            <input type='radio' name='rdoSearch' value='<?= $key ?>' <?php if ($key == $_POST['rdoSearch']) {
                                                                                            echo ' checked="checked"';
                                                                                        } ?>>
                            <?= $value ?>
                            </input>
                        <?php
                        }
                        ?>
                    </form>
                </label>
            </div>
        </div>
        <form method="post">
            <div class="col-md-4 text-left">
                <div class="title_condition">
                    <label>
                        <?php if ($_POST['rdoSearch'] == "1") : ?>
                            内容 :
                        <?php else : ?>
                            タイトル :
                        <?php endif; ?>
                        <input type="text" id="searchKeyword" name="searchKeywordTC" value="" style="width: 200px;">
                        <input type="hidden" name="rdoSearch" value="<?= $_POST['rdoSearch'] ?>">
                    </label>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <input type="submit" name="SearchButtonNL" value="検索">&nbsp;&nbsp;&nbsp;
                    <input type="button" id="btnNewNL" value="新規">
                </div>
            </div>
        </form>
        <div class="form-group">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 5%;">No</th>
                        <th style="text-align: center; width: auto;">
                            <?php if ($_POST['rdoSearch'] == "1") : ?>
                                内容
                            <?php else : ?>
                                タイトル
                            <?php endif; ?>
                        </th>
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
                                <td style="text-align:left">
                                    <a href="#">
                                        <span class="showModal">
                                            <?php if ($_POST['rdoSearch'] == "1") : ?>
                                                <?= $key['content'] ?>
                                            <?php else : ?>
                                                <?= $key['title'] ?>
                                            <?php endif; ?>
                                        </span>
                                    </a>
                                </td>
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

    <!-- 新規 -->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        お知らせ登録(<span id="sname"><?= $_SESSION['auth_name'] ?></span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="title">タイトル</label>
                                <input type="text" class="form-control" name="title" id="title">
                                <input type="hidden" name="uid" value="<?= $_SESSION['auth_uid'] ?>">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="content">内容</label>
                                <textarea type="text" class="form-control" rows="5" name="content" id="content"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="reader">確認者</label>
                                <input type="text" class="form-control" name="reader" id="reader" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-xs-4">
                                <label for="uname">作成者</label>
                                <input type="text" class="form-control" value="<?= $_SESSION['auth_name'] ?>" style="text-align: center" readonly>
                            </div>
                            <div class="col-xs-4">
                                <label for="reg_dt">作成日</label>
                                <input type="text" class="form-control" value="<?= date('Y-m-d') ?>" style="text-align: center" readonly>
                            </div>
                            <div class="col-xs-4">
                                <label for="viewcnt">view Cnt</label>
                                <input type="text" class="form-control" value="0" style="text-align: center" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-xs-4"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnRegNL" class="btn btn-primary" id="btnRegNL" role="button" value="登録">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                        </div>
                        <div class="col-xs-4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("input[name='rdoSearch']").click(function() {
            $("#searchForm").submit(); // Trigger form submission
        });
    });

    // New button: popup & clear 
    $(document).on('click', '#btnNewNL', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegNL', function(e) {
        var Title = $("#title").val();
        var Content = $("#content").val();
        var Reader = $("#reader").val();

        if (Title == "") {
            alert("<?php echo $content_noteT_empty; ?>");
            $("#title").focus();
            return false;
        }

        if (Content == "") {
            alert("<?php echo $content_noteC_empty; ?>");
            $("#content").focus();
            return false;
        }

        if (Reader == "") {
            alert("<?php echo $content_noteR_empty; ?>");
            $("#reader").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>