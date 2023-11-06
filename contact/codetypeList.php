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

    span.codetypeList_class {
        display: none;
    }
</style>
<title>型式コード登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegCTL'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateCTL'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelCTL'])) {
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
        <div class="col-md-8">
            <div class="title_name">
                <span class="text-left">型式コード登録</span>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div class="title_btn">
                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                    <input type="button" id="btnNewCTL" value="新規">
                <?php endif; ?>
            </div>
            <div class="title_btn">
                <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 20%;">Type Code</th>
                        <th style="text-align: center; width: 50%;">Type Name</th>
                        <th style="text-align: center; width: auto;">Type Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($codetype_list)) { ?>
                        <tr class="info">
                            <td colspan="3" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        </tr>
                        <?php } elseif (!empty($codetype_list)) {
                        foreach ($codetype_list as $key) {
                        ?>
                            <tr>
                                <td align="center">
                                    <span>
                                        <?= $key['typecode'] ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="#">
                                        <span class="showModal">
                                            <span class="codetypeList_class">
                                                <?= $key['typecode'] . ',' ?>
                                            </span>
                                            <?= $key['typename'] ?>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <span>
                                        <?= $key['typeremark'] ?>
                                    </span>
                                </td>
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
                            型式コード登録(<span id="sname">New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="code">Type Code</label>
                                    <input type="text" class="form-control" name="typecode" id="typecode" placeholder="タイプコード" style="text-align: center" maxlength=<?php echo $MAX_LENGTH_CODE ?>>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">Type Name</label>
                                    <input type="text" class="form-control" name="typename" id="typename" placeholder="タイプ名" style="text-align: left">
                                </div>
                                <div class="col-md-4">
                                    <label for="remark">Type Remark</label>
                                    <input type="text" class="form-control" name="typeremark" id="typeremark" placeholder="タイプ備考" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegCTL" class="btn btn-primary" id="btnRegCTL" role="button" value="登録">
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
                            基礎コード編集
                            (<span id="udtcode"></span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="code">Type Code</label>
                                    <input type="text" class="form-control" name="udtypecode" id="udtypecode" style="text-align: center" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">Type Name</label>
                                    <input type="text" class="form-control" name="udtypename" id="udtypename" placeholder="タイプ名" style="text-align: left">
                                </div>
                                <div class="col-md-4">
                                    <label for="remark">Type Remark</label>
                                    <input type="text" class="form-control" name="udtyperemark" id="udtyperemark" placeholder="タイプ備考" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnUpdateCTL" class="btn btn-primary" id="btnUpdateCTL" role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnDelCTL" class="btn btn-warning" id="btnDelCTL" role="button" value="削除">
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
</div>
<script>
    // Change type code by submit 
    $(document).ready(function() {
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

    // New button: popup & clear 
    $(document).on('click', '#btnNewCTL', function(e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegCTL', function(e) {
        var TypeCode = $("#typecode").val();
        var TypeName = $("#typename").val();

        if (TypeCode == "") {
            alert("<?php echo $content_ctl_empty; ?>");
            $("#typecode").focus();
            return false;
        }

        if (isNaN(TypeCode)) {
            alert("<?php echo $content_ctl_no; ?>");
            e.preventDefault();
            $("#typecode").focus();
            return false;
        }

        if (TypeName == "") {
            alert("<?php echo $content_ctlN_empty; ?>");
            $("#typename").focus();
            return false;
        }

        // check duplicate code 
        var typecodes = <?php echo json_encode($typecodes); ?>;
        for (var typecode of typecodes) {
            if (typecode === TypeCode) {
                alert("<?php echo $content_ctlC_duplicate; ?>");
                $("#typecode").focus();
                return false;
            }
        }
    });

    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var Typecode = SeparateArr[0].trim();
        <?php
        if (!empty($codetype_list)) {
            foreach ($codetype_list as $key) {
        ?>
                if ('<?php echo $key['typecode'] ?>' == Typecode) {
                    $("#udtcode").text('<?php echo $key['typecode'] ?>');
                    var udtypecode = $("input[name=udtypecode]:hidden");
                    udtypecode.val("<?php echo $key['typecode'] ?>");
                    var udtypecode = udtypecode.val();
                    $("#udtypecode").text($('[name="udtypecode"]').val("<?php echo $key['typecode'] ?>"));
                    $("#udtypename").text($('[name="udtypename"]').val("<?php echo $key['typename'] ?>"));
                    $("#udtyperemark").text($('[name="udtyperemark"]').val("<?php echo $key['typeremark'] ?>"));
                }
        <?php
            }
        }
        ?>
    });

    // Check Error
    $(document).on('click', '#btnUpdateCTL', function(e) {
        var TypeName = $("#udtypename").val();
        if (TypeName == "") {
            alert("<?php echo $content_ctlN_empty; ?>");
            $("#udtypename").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>