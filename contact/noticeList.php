<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/const.php');
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

    span.noticeList_class {
        display: none;
    }
</style>
<title>お知らせ登録</title>
<?php include('../inc/menu.php'); ?>
<div class="container" style="margin-top:-20px;">
    <?php
    if (isset($_SESSION['save_success']) && isset($_POST['btnRegNL'])) {
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
    if (isset($_SESSION['update_success']) && isset($_POST['btnUpdateNL'])) {
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

    if (isset($_SESSION['delete_success']) && isset($_POST['btnDelNL'])) {
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
        <div class="col-md-3 text-left">
            <div class="title_name">
                <span class="text-left">お知らせ登録</span>
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
                        <input type="text" id="searchKeyword" name="searchKeywordTC" value="<?= $_POST['searchKeywordTC'] ?>" style="width: 200px;">
                        <input type="hidden" name="rdoSearch" value="<?= $_POST['rdoSearch'] ?>">
                    </label>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_btn">
                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                        <input type="submit" name="SearchButtonNL" value="検索">&nbsp;&nbsp;&nbsp;
                        <input type="button" id="btnNewNL" value="新規">
                    <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
                        <input type="submit" name="SearchButtonNL" value="検索">
                    <?php endif; ?>
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
                        <th style="text-align: center; width: 10%;">写真</th>
                        <th style="text-align: center; width: 5%;">view</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($notice_list)) { ?>
                        <tr>
                            <td colspan="6" align="center">
                                <?php echo $data_save_no; ?>
                            </td>
                        </tr>
                        <?php } elseif (!empty($notice_list)) {
                        $counter = 1;
                        foreach ($notice_list as $key) {
                        ?>
                            <tr>
                                <td><span>
                                        <?= $counter++; // $key['bid'] change show number  
                                        ?>
                                    </span></td>
                                <td style="text-align:left">
                                    <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR')) : ?>
                                        <a href="#">
                                            <span class="showModal">
                                                <span class="noticeList_class">
                                                    <?= $key['bid'] . ',' . $key['uid'] . ',' ?>
                                                </span>
                                                <?php if ($_POST['rdoSearch'] == "1") : ?>
                                                    <?= $key['content'] ?>
                                                <?php else : ?>
                                                    <?= $key['title'] ?>
                                                <?php endif; ?>
                                            </span>
                                        </a>
                                    <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
                                        <?php if ($_POST['rdoSearch'] == "1") : ?>
                                            <span>
                                                <?= $key['content'] ?>
                                            </span>
                                        <?php else : ?>
                                            <span>
                                                <?= $key['title'] ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td><span>
                                        <?= $key['name'] ?>
                                    </span></td>
                                <td><span>
                                        <?= $key['reg_dt'] ?>
                                    </span></td>
                                <td><span>
                                        <?= $key['reader'] ?>
                                    </span></td>
                                <td>
                                    <span>
                                        <?php if ($key['imagefile'] == NULL) : ?>
                                            写真無し
                                        <?php else : ?>
                                            <img width="50" src="<?= $PATH_IMAGE_NOTICE . $key['imagefile'] ?>"><br>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td><span>
                                        <?= $key['viewcnt'] ?>
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
        <div class="modal" id="modal" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            お知らせ登録(<span id="sname">
                                <?= $_SESSION['auth_name'] ?>
                            </span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="title">タイトル</label>
                                    <input type="text" class="form-control" name="title" id="title" placeholder="タイトル">
                                    <input type="hidden" name="uid" value="<?= $_SESSION['auth_uid'] ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="content">内容</label>
                                    <textarea type="text" class="form-control" rows="3" name="content" id="content" placeholder="お知らせ内容"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="reader">確認者</label>
                                    <input type="text" class="form-control" name="reader" id="reader" style="text-align: left" placeholder="日本 太郎">
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
                                    <input type="text" class="form-control" name="reg_dt" value="<?= date('Y-m-d') ?>" style="text-align: center" readonly>
                                </div>
                                <div class="col-xs-4">
                                    <label for="viewcnt">view Cnt</label>
                                    <input type="text" class="form-control" name="viewcnt" value="0" style="text-align: center" readonly>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="imagefile_addNew">写真</label>
                                    <img width="50" id="imagefile_addNew">
                                    <input type="file" name="imagefile" accept="image/*" id="fileInput" onchange=checkFileSize(this)>
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
                </form>
            </div>
        </div>
    </div>

    <!-- 編集 -->
    <div class="row">
        <div class="modal" id="modal2" tabindex="-1" style="display: none;">
            <div class="modal-dialog">
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-content">
                        <div class="modal-header">
                            お知らせ編集
                            (<span id="udtname"></span>)
                            <button class="close" data-dismiss="modal">x</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="title">タイトル</label>
                                    <input type="text" class="form-control" name="udtitle" id="udtitle">
                                    <input type="hidden" name="udbid" id="udbid">
                                    <input type="hidden" name="uduid" id="uduid">
                                    <input type="hidden" name="udimagefile_name" id="udimagefile_name">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="content">内容</label>
                                    <textarea type="text" class="form-control" rows="3" name="udcontent" id="udcontent"></textarea>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="reader">確認者</label>
                                    <input type="text" class="form-control" name="udreader" id="udreader" style="text-align: left">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-4">
                                    <label for="uname">作成者</label>
                                    <input type="text" class="form-control" name="udname" id="udname" style="text-align: center" readonly>
                                </div>
                                <div class="col-xs-4">
                                    <label for="reg_dt">作成日</label>
                                    <input type="text" class="form-control" name="udreg_dt" id="udreg_dt" style="text-align: center">
                                </div>
                                <div class="col-xs-4">
                                    <label for="viewcnt">view Cnt</label>
                                    <input type="text" class="form-control" name="udviewcnt" id="udviewcnt" style="text-align: center">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="udimagefile">写真</label><br>
                                    <img width="50" id="udimagefile">
                                    <span id="imagename" name="imagename" hidden></span>
                                    <input type="hidden" name="udimagefile_old" id="udimagefile_old">
                                    <input type="file" name="udimagefile_new" id="udfileInput" onchange=checkFileSize(this)>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnUpdateNL" class="btn btn-primary" id="btnUpdate" role="button" value="編集">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="btnDelNL" class="btn btn-warning" id="btnDel" role="button" value="削除">
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
    $(document).ready(function() {
        $("input[name='rdoSearch']").click(function() {
            $("#searchForm").submit(); // Trigger form submission
        });

        // 2023-10-11/1340-005
        // upload image add start
        // load valid extention to element check 
        <?php $allowedTypesString = "." . implode(", .", $ALLOWED_TYPES); ?>
        $('#udfileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
        $('#fileInput').attr('accept', "<?php echo $allowedTypesString; ?>");
        // 2023-10-11/1340-005
        // pload image add end

    });

    // New button: popup & clear 
    $(document).on('click', '#btnNewNL', function(e) {
        $('#modal').modal('toggle');
        $('label[for="imagefile_addNew"]').show();
        $('#fileInput').val('');
    });

    // Check Error
    $(document).on('click', '#btnRegNL', function(e) {
        var Title = $("#title").val();
        var Content = $("#content").val();
        var Reader = $("#reader").val();
        var FileInput = $("#fileInput").val();

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

        if (FileInput == "") {
            alert("<?php echo $image_empty_error; ?>");
            $("#fileInput").focus();
            return false;
        }
    });

    // Year/month click on grid (edit): popup & content display
    $(document).on('click', '.showModal', function() {
        $('#modal2').modal('toggle');
        $('label[for="udimagefile"]').show();
        $('#udfileInput').val('');

        var ArrayData = $(this).text();
        var SeparateArr = ArrayData.split(',');
        var Bid = SeparateArr[0].trim();
        var Uid = SeparateArr[1].trim();

        <?php
        if (!empty($notice_list)) {
            foreach ($notice_list as $key) {
        ?>
                if ('<?php echo $key['bid'] ?>' == Bid && '<?php echo $key['uid'] ?>' == Uid) {
                    $("#udtname").text('<?php echo $key['name'] ?>');
                    $("#udtitle").text($('[name="udtitle"]').val("<?php echo $key['title'] ?>"));
                    $("#udcontent").text($('[name="udcontent"]').val("<?php echo $key['content'] ?>"));
                    $("#udreader").text($('[name="udreader"]').val("<?php echo $key['reader'] ?>"));
                    $("#udname").text($('[name="udname"]').val("<?php echo $key['name'] ?>"));
                    $("#udviewcnt").text($('[name="udviewcnt"]').val("<?php echo $key['viewcnt'] ?>"));
                    $("#udreg_dt").text($('[name="udreg_dt"]').val("<?php echo $key['reg_dt'] ?>"));

                    var udimagefile_old = $("input[name=udimagefile_old]:hidden");
                    udimagefile_old.val("<?php echo $key['imagefile'] ?>");
                    var udimagefile_old = udimagefile_old.val();
                    // $("#udimagefile_name").text('<?php // echo $key['imagefile'] 
                                                    ?>');
                    var imagePath = "<?php echo $PATH_IMAGE_NOTICE . $key['imagefile']; ?>";
                    $("#udimagefile").attr("src", imagePath);

                    var udbid = $("input[name=udbid]:hidden");
                    udbid.val("<?php echo $key['bid'] ?>");
                    var udbid = udbid.val();

                    var uduid = $("input[name=uduid]:hidden");
                    uduid.val("<?php echo $key['uid'] ?>");
                    var uduid = uduid.val();

                    var udimagefile_name = $("input[name=udimagefile_name]:hidden");
                    udimagefile_name.val("<?php echo $key['imagefile'] ?>");
                    var udimagefile_name = udimagefile_name.val();

                }
        <?php
            }
        }
        ?>
    });

    // Datepicker Calender
    $("#udreg_dt").datepicker({
        changeYear: true,
        dateFormat: 'yy-mm-dd'
    });

    // Check Error
    $(document).on('click', '#btnUpdate', function(e) {
        var Title = $("#udtitle").val();
        var Content = $("#udcontent").val();
        var Reader = $("#udreader").val();
        var Regdt = $("#udreg_dt").val();
        var Viewcnt = $("#udviewcnt").val();

        if (Title == "") {
            alert("<?php echo $content_noteT_empty; ?>");
            $("#udtitle").focus();
            return false;
        }

        if (Content == "") {
            alert("<?php echo $content_noteC_empty; ?>");
            $("#udcontent").focus();
            return false;
        }

        if (Reader == "") {
            alert("<?php echo $content_noteR_empty; ?>");
            $("#udreader").focus();
            return false;
        }

        if (Regdt == "") {
            alert("<?php echo $content_noteRegdt_empty; ?>");
            $("#udreg_dt").focus();
            return false;
        }

        if (Viewcnt == "") {
            alert("<?php echo $content_noteViewcnt_empty; ?>");
            $("#udviewcnt").focus();
            return false;
        }
    });

    // 2023-10-11/1340-005
    // upload image add start
    // check size file upload 
    function checkFileSize(input) {
        if (input.files.length > 0) {
            var fileSize = input.files[0].size;
            var maxSize = <?php echo $NOTICE_IMAGE_MAXSIZE; ?>;
            if (fileSize > maxSize) {
                alert("<?php echo $file_size_isvalid; ?>");
                input.value = ""; // delete selected file
            }
        }

        var parentElement = input.parentNode;
        var siblings = parentElement.childNodes;
        for (var i = 0; i < siblings.length; i++) {
            var sibling = siblings[i];
            if (sibling.id && sibling.id.endsWith("_addNew")) {
                validateImage(input, true);
                return;
            }
        }
        validateImage(input, false);
    }

    // check valid size extention 
    function validateImage(inputElement, isaddNew) {
        <?php $allowedTypesJSON = json_encode($ALLOWED_TYPES); ?>
        var allowedExtensions = <?php echo $allowedTypesJSON; ?>;
        if (inputElement.files.length > 0) {
            const fileName = inputElement.files[0].name;
            const fileExtension = fileName.slice(((fileName.lastIndexOf(".") - 1) >>> 0) + 2);
            if (!allowedExtensions.includes(fileExtension.toLowerCase())) {
                alert("<?php echo $file_extension_invalid; ?>");
                inputElement.value = ''; // delete selected file
            } else {

                // show new image 
                if (isaddNew)
                    displaySelectedImageAddNew(inputElement)
                else
                    displaySelectedImageChange(inputElement)
            }
        }
    }

    // ad new show selected img 
    function displaySelectedImageAddNew(input) {
        if (input.files.length > 0) {
            const selectedFile = input.files[0];
            const imageElement = document.getElementById('imagefile_addNew');
            const labelElement = document.querySelector('label[for="imagefile_addNew"]');

            if (selectedFile.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageElement.src = e.target.result;
                    imageElement.alt = selectedFile.name;
                    labelElement.style.display = 'none';
                };

                reader.readAsDataURL(selectedFile);
            } else {
                alert("<?php echo $file_extension_invalid; ?>");
                input.value = '';
            }
        }
    }

    // change selected img
    function displaySelectedImageChange(input) {
        if (input.files.length > 0) {
            const selectedFile = input.files[0];
            const imageElement = document.getElementById('udimagefile');
            if (selectedFile.type.match('image.*')) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    imageElement.src = e.target.result;
                    imageElement.alt = selectedFile.name;
                    document.getElementById('imagename').innerText = selectedFile.name;
                    document.getElementById('imagename').hidden = false;
                };
                reader.readAsDataURL(selectedFile);
            } else {
                alert("<?php echo $file_extension_invalid; ?>");
                input.value = '';
            }
        }
    }
    // 2023-10-11/1340-005
    // upload image  add end
</script>
<?php include('../inc/footer.php'); ?>