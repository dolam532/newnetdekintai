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

    span.codemasterList_class {
        display: none;
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
                <input type="button" id="btnNewCL" value="新規 ">
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
                    <form id="myForm" method="post">
                        <input type="hidden" name="typecode" id="typecode">
                        <input type="hidden" name="typename" id="typename">
                    </form>
                    <?php if (empty($codetype_list)) { ?>
                        <tr class="info">
                            <td colspan="2" align="center"><?php echo $data_save_no; ?></td>
                        </tr>
                        <?php } elseif (!empty($codetype_list)) {
                        foreach ($codetype_list as $key) {
                        ?>
                            <?php if ($key['typecode'] == $_POST['typecode']) : ?>
                                <tr>
                                    <td align="center"><span style="font-weight:bold"><?= $key['typecode'] ?></span></td>
                                    <td>
                                        <a href="#" class="submitLink">
                                            <span style="font-weight:bold"><?= $key['typename'] ?></span>
                                            <span class="codemasterList_class"><?= $key['typecode'] . ',' . $key['typename'] ?></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td align="center"><span><?= $key['typecode'] ?></span></td>
                                    <td>
                                        <a href="#" class="submitLink">
                                            <span><?= $key['typename'] ?></span>
                                            <span class="codemasterList_class"><?= $key['typecode'] . ',' . $key['typename'] ?></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                    <?php
                        }
                    } ?>
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
                    <?php if (empty($codebase_list)) { ?>
                        <tr>
                            <td colspan="3" align="center"><?php echo $data_save_no; ?></td>
                        </tr>
                        <?php } elseif (!empty($codebase_list)) {
                        foreach ($codebase_list as $key) {
                        ?>
                            <tr>
                                <td align="center"><span><?= $key['typecode'] ?></span></td>
                                <td>
                                    <a href="#">
                                        <span class="showModal">
                                            <span class="codemasterList_class"><?= $key['companyid']  . ',' ?></span>
                                            <?= $key['name'] ?>
                                        </span>
                                    </a>
                                </td>
                                <td><span><?= $key['bigo'] ?></span></td>
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
                        <div class="col-xs-3"></div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL" role="button" value="編集">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <p class="text-center">
                                <input type="submit" name="btnDelCL" class="btn btn-warning" id="btnDelCL" role="button" value="削除">
                            </p>
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Change type code by submit 
    $(document).ready(function() {
        $(".submitLink").click(function(event) {
            event.preventDefault(); // Prevent the default link behavior
            var ArrayData = $(this).find(".codemasterList_class").text();
            var SeparateArr = ArrayData.split(',');
            var Typecode = SeparateArr[0].trim();
            var Typename = SeparateArr[1].trim();

            $("#typecode").val(Typecode);
            $("#typename").val(Typename);
            $("#myForm").submit();
        });
    });

    // New button: popup & clear 
    $(document).on('click', '#btnNewCL', function(e) {
        $('#modal').modal('toggle');
    });
</script>
<?php include('../inc/footer.php'); ?>