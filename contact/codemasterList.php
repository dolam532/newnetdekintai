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
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegCL'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateCL'])) {
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
    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelCL'])) {
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
        <div class="col-md-10">
            <div class="title_name">
                <span class="text-left">基礎コード登録</span>
            </div>
        </div>
        <div class="col-md-2 text-right">
            <div class="title_btn">
                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')): ?>
                    <input type="button" id="btnNewCL" value="新規 ">
                <?php endif; ?>
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
                            <td colspan="2" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        </tr>
                    <?php } elseif (!empty($codetype_list)) {
                        foreach ($codetype_list as $key) {
                            ?>
                            <?php if ($key['typecode'] == $_POST['typecode']): ?>
                                <tr>
                                    <td align="center"><span style="font-weight:bold">
                                            <?= $key['typecode'] ?>
                                        </span></td>
                                    <td>
                                        <a href="#" class="submitLink">
                                            <span style="font-weight:bold">
                                                <?= $key['typename'] ?>
                                            </span>
                                            <span class="codemasterList_class">
                                                <?= $key['typecode'] . ',' . $key['typename'] ?>
                                            </span>
                                        </a>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td align="center"><span>
                                            <?= $key['typecode'] ?>
                                        </span></td>
                                    <td>
                                        <a href="#" class="submitLink">
                                            <span>
                                                <?= $key['typename'] ?>
                                            </span>
                                            <span class="codemasterList_class">
                                                <?= $key['typecode'] . ',' . $key['typename'] ?>
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
                        <th style="text-align: center; width: 20%;">Code</th>
                        <th style="text-align: center; width: 40%;">名</th>
                        <th style="text-align: center; width: 40%;">備考</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($codebase_list)) { ?>
                        <tr>
                            <td colspan="3" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        </tr>
                    <?php } elseif (!empty($codebase_list)) {
                        foreach ($codebase_list as $key) {
                            ?>
                            <tr>
                                <td align="center"><span>
                                        <?= $key['code'] ?>
                                    </span></td>
                                <td>
                                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')): ?>
                                        <a href="#">
                                            <span class="showModal">
                                                <span class="codemasterList_class">
                                                    <?= $key['id'] . ',' ?>
                                                </span>
                                                <?= $key['name'] ?>
                                            </span>
                                        </a>
                                    <?php elseif ($_SESSION['auth_type'] == constant('USER')): ?>
                                        <?= $key['name'] ?>
                                    <?php endif; ?>

                                </td>
                                <td><span>
                                        <?= $key['remark'] ?>
                                    </span></td>
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
                    <input type="hidden" name="typecode" value="<?= $_POST['typecode'] ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            基礎コード登録(<span id="sname">New</span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="code">Code</label>
                                    <input type="text" class="form-control" name="code" id="code" placeholder="コード"
                                        style="text-align: center" maxlength=<?php echo $MAX_LENGTH_CODE ?>>
                                </div>
                                <div class="col-md-5">
                                    <label for="name">名</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="name"
                                        style="text-align: left">
                                </div>
                                <div class="col-md-5">
                                    <label for="remark">備考</label>
                                    <input type="text" class="form-control" name="remark" id="remark"
                                        placeholder="remark" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-4"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnRegCL" class="btn btn-primary" id="btnRegCL"
                                        role="button" value="登録">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
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
        <div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false"
            style="display: none;">
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
                                    <input type="text" class="form-control" name="udcode" id="udcode"
                                        style="text-align: center" readonly>
                                    <input type="hidden" name="udid" id="udid">
                                    <input type="hidden" name="udcompanyid" id="udcompanyid">
                                    <input type="hidden" name="uduid" id="uduid">
                                    <input type="hidden" name="udtypecode" id="udtypecode">
                                </div>
                                <div class="col-md-5">
                                    <label for="name">名</label>
                                    <input type="text" class="form-control" name="udname" id="udname" placeholder="name"
                                        style="text-align: left">
                                </div>
                                <div class="col-md-5">
                                    <label for="remark">備考</label>
                                    <input type="text" class="form-control" name="udremark" id="udremark"
                                        placeholder="remark" style="text-align: left">
                                </div>
                            </div>
                            <br>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnUpdateCL" class="btn btn-primary" id="btnUpdateCL"
                                        role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnDelCL" class="btn btn-warning" id="btnDelCL"
                                        role="button" value="削除">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
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
    $(document).ready(function () {
        $(".submitLink").click(function (event) {
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
    $(document).on('click', '#btnNewCL', function (e) {
        $('#modal').modal('toggle');
    });

    // Check Error
    $(document).on('click', '#btnRegCL', function (e) {
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
    $(document).on('click', '.showModal', function () {
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
                    var uduid = $("input[name=uduid]:hidden");
                    uduid.val("<?php echo $key['uid'] ?>");
                    var uduid = uduid.val();
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
    $(document).on('click', '#btnUpdateCL', function (e) {
        var Name = $("#udname").val();
        if (Name == "") {
            alert("<?php echo $content_cmlN_empty; ?>");
            $("#udname").focus();
            return false;
        }
    });
</script>
<?php include('../inc/footer.php'); ?>