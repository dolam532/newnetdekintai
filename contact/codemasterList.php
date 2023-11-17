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
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegCML'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateCML'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelCML'])) {
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
                <span class="text-left">基礎コード登録</span>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <div class="title_btn">
                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                    <input type="button" id="btnNewCML" value="新規">
                <?php endif; ?>
            </div>
            <div class="title_btn">
                <input type="button" onclick="window.location.href='../'" value="トップへ戻る">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 40%;">Type Code</th>
                        <th style="text-align: center; width: auto;">Type Name</th>
                    </tr>
                </thead>
                <tbody>
                    <form id="myForm" method="post">
                        <input type="hidden" name="typecode" id="typecode">
                        <input type="hidden" name="typename" id="typename">
                    </form>
                    <?php if (empty($codetype_list)) { ?>
                        <tr class="info">
                            <td colspan="2" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        </tr>
                        <?php } elseif (!empty($codetype_list)) {
                        foreach ($codetype_list as $key) {
                        ?>
                            <?php if ($key['typecode'] == $_POST['typecode']) : ?>
                                <tr>
                                    <td align="center">
                                        <span style="font-weight:bold; color:red; text-decoration: underline;">
                                            <?= $key['typecode'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="submitLink">
                                            <span style="font-weight:bold; color:red; text-decoration: underline;">
                                                <?= $key['typename'] ?>
                                            </span>
                                            <span class="codemasterList_class">
                                                <?= $key['typecode'] . ',' . $key['typename'] . ',' . $key['companyid'] ?>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td align="center">
                                        <span>
                                            <?= $key['typecode'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="submitLink" id="target_error">
                                            <span>
                                                <?= $key['typename'] ?>
                                            </span>
                                            <span class="codemasterList_class">
                                                <?= $key['typecode'] . ',' . $key['typename'] . ',' . $key['companyid'] ?>
                                            </span>
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
                        <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                            <th style="text-align: center; width: 40%;">会社名</th>
                        <?php endif; ?>
                        <th style="text-align: center; width: 10%;">Code</th>
                        <th style="text-align: center; width: 30%;">名</th>
                        <th style="text-align: center; width: auto;">備考</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($codebase_list)) { ?>
                        <tr>
                            <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                                <td colspan="4" align="center">
                                    <?php echo $data_save_no; ?>
                                </td>
                            <?php else : ?>
                                <td colspan="3" align="center">
                                    <?php echo $data_save_no; ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php } elseif (!empty($codebase_list)) {
                        foreach ($codebase_list as $key) {
                        ?>
                            <tr>
                                <?php if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                                    <td align="center">
                                        <span>
                                            <?= $key['companyname'] ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td align="center">
                                    <span>
                                        <?= $key['code'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                        <a href="#">
                                            <span class="showModal">
                                                <span class="codemasterList_class">
                                                    <?= $key['id'] . ',' ?>
                                                </span>
                                                <?= $key['name'] ?>
                                            </span>
                                        </a>
                                    <?php else : ?>
                                        <span>
                                            <?= $key['name'] ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span>
                                        <?= $key['remark'] ?>
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
                    <input type="hidden" name="typecode" id="typecode" value="<?= $_POST['typecode'] ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            基礎コード登録(<span id="sname">New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" name="code" id="code" placeholder="コード" style="text-align: center" maxlength=<?php echo $MAX_LENGTH_CODE ?>>
                                </div>
                                <div class="col-md-5">
                                    <label for="name">名</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="name" style="text-align: left">
                                </div>
                                <div class="col-md-5">
                                    <label for="remark">備考</label>
                                    <input type="text" class="form-control" name="remark" id="remark" placeholder="remark" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegCML" class="btn btn-primary" id="btnRegCML" role="button" value="登録">
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
                                <div class="col-md-2">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" name="udcode" id="udcode" style="text-align: center" readonly>
                                    <input type="hidden" name="udid" id="udid">
                                    <input type="hidden" name="udcompanyid" id="udcompanyid">
                                    <input type="hidden" name="udemail" id="udemail">
                                    <input type="hidden" name="udtypecode" id="udtypecode">
                                </div>
                                <div class="col-md-5">
                                    <label for="name">名</label>
                                    <input type="text" class="form-control" name="udname" id="udname" placeholder="name" style="text-align: left">
                                </div>
                                <div class="col-md-5">
                                    <label for="remark">備考</label>
                                    <input type="text" class="form-control" name="udremark" id="udremark" placeholder="remark" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnUpdateCML" class="btn btn-primary" id="btnUpdateCML" role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnDelCML" class="btn btn-warning" id="btnDelCML" role="button" value="削除">
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
        $(".submitLink").click(function(event) {
            event.preventDefault(); // Prevent the default link behavior
            var ArrayData = $(this).find(".codemasterList_class").text();
            var SeparateArr = ArrayData.split(',');
            var Typecode = SeparateArr[0].trim();
            var Typename = SeparateArr[1].trim();
            var Companyid = SeparateArr[2].trim();

            $("#typecode").val(Typecode);
            $("#typename").val(Typename);
            $("#companyid").val(Companyid);
            $("#myForm").submit();

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

    // New button: popup & clear 
    $(document).on('click', '#btnNewCML', function(e) {
        var typecode = <?php echo json_encode($_POST['typecode']); ?>;
        if (typecode === null) {
            alert("<?php echo $content_choice_empty; ?>");
            $("#target_error").focus();
            return false;
        }
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegCML', function(e) {
        var Code = $("#code").val();
        var Name = $("#name").val();

        if (Code == "") {
            alert("<?php echo $content_cmlC_empty; ?>");
            $("#code").focus();
            return false;
        }

        if (isNaN(Code)) {
            alert("<?php echo $content_cmlC_no; ?>");
            e.preventDefault();
            $("#code").focus();
            return false;
        }

        if (Name == "") {
            alert("<?php echo $content_cmlN_empty; ?>");
            $("#name").focus();
            return false;
        }

        // check duplicate code 
        var codes = <?php echo json_encode($codes); ?>;
        for (var code of codes) {
            if (code === Code) {
                alert("<?php echo $content_cmlC_duplicate; ?>");
                $("#code").focus();
                return false;
            }
        }
    });

    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var Id = SeparateArr[0].trim();
        <?php
        if (!empty($codebase_list)) {
            foreach ($codebase_list as $key) {
        ?>
                if ('<?php echo $key['id'] ?>' == Id) {
                    $("#udtcode").text('<?php echo $key['code'] ?>');
                    var udid = $("input[name=udid]:hidden");
                    udid.val("<?php echo $key['id'] ?>");
                    var udid = udid.val();
                    var udcompanyid = $("input[name=udcompanyid]:hidden");
                    udcompanyid.val("<?php echo $key['companyid'] ?>");
                    var udcompanyid = udcompanyid.val();
                    var udemail = $("input[name=udemail]:hidden");
                    udemail.val("<?php echo $key['email'] ?>");
                    var udemail = udemail.val();
                    var udtypecode = $("input[name=udtypecode]:hidden");
                    udtypecode.val("<?php echo $key['typecode'] ?>");
                    var udtypecode = udtypecode.val();
                    $("#udcode").text($('[name="udcode"]').val("<?php echo $key['code'] ?>"));
                    $("#udname").text($('[name="udname"]').val("<?php echo $key['name'] ?>"));
                    $("#udremark").text($('[name="udremark"]').val("<?php echo $key['remark'] ?>"));
                }
        <?php
            }
        }
        ?>
    });

    // Check Error
    $(document).on('click', '#btnUpdateCML', function(e) {
        var Name = $("#udname").val();
        if (Name == "") {
            alert("<?php echo $content_cmlN_empty; ?>");
            $("#udname").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>