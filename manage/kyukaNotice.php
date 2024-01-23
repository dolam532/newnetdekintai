<?php
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../inc/const.php');
include('../inc/header.php');
include('../model/managemodel.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}
if ($_SESSION['auth_type'] == constant('USER')) { 
    header("Location: ../index.php");
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

/* modal popup lock */
.modal-dialog {
    display: inline-block;
    text-align: left;
    vertical-align: middle;
}

.text-area-small {
    text-align: center;
    width: 10%;
    height: 300%;
    overflow: auto;
}

.text-area-medium {
    text-align: center;
    width: 20%;
    height: 300%;
    overflow: auto;
}

.submissionStatusNotice {
    display: flex;
    margin-top: -15px;
    justify-content: center;
    flex-wrap: nowrap;
    font-size: 80%;
}
</style>

<?php include('../inc/menu.php'); ?>

<?php
    if (isset($_SESSION['update_success']) && isset($_POST['kyukanoticeRegister'])) {
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
    if (isset($_SESSION['kyuka_info_not_existing']) && isset($_POST['kyukanoticeRegister'])) {
        ?>
<div class="alert alert-danger alert-dismissible" role="alert" auto-close="10000">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?php echo $_SESSION['kyuka_info_not_existing']; ?>
</div>
<?php
        unset($_SESSION['kyuka_info_not_existing']);
    }
    ?>

<div class="row">
    <form method="post" onsubmit="return validationData()">
        <!-- input  -->
        <div class="col-md-6 text-center">
            <h3>休暇知おらせ登録</h3>
            <br>
            <div class="modal-header text-left">
                <input value="<?=trim($kiukaNoticeList[0]['title'])?>" id="form_title" name="form_title"></input>
            </div>
            <div class="modal-body" style="text-align: left">
                <div class="row">
                    <div class="col-md-12 col-ms-12">
                        <textarea class="alert alert-warning" id="message-area" name="message-area"
                            style="width: 100%; overflow:auto;" oninput="autoGrow(this)" rows="4"><?=trim($kiukaNoticeList[0]['message'])?>
                    </textarea>
                    </div>
                    <div class="col-md-12 col-ms-12 sub-middle">
                        <div class="alert alert-info" style="margin-bottom: 10px;">
                            <input name="sub_title" value="<?=trim($kiukaNoticeList[0]['subtitle'])?>"
                                id="sub_title"></input>
                        </div>
                    </div>
                    <div class="col-md-12 col-ms-12">
                        <table class="table table-bordered datatable">
                            <thead>
                            </thead>

                            <input type="hidden" name="title_value">
                            <input type="hidden" name="message_value">
                            <input type="hidden" name="subTitle_value">
                            <input type="hidden" name="title_row_1">
                            <input type="hidden" name="data_row_1">
                            <input type="hidden" name="title_row_2">
                            <input type="hidden" name="data_row_2">

                        </table>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="kyukanoticeRegister"
                name="kyukanoticeRegister">登録</button>
        </div>
    </form>


    <!-- overview  -->
    <div class="col-md-6 text-center">
        <h3>休暇お知らせ表示確認</h3><br>
        <span class="submissionStatusNotice">（登録したら、休暇お知らせを更新されます。）</span>

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><span class="popup-title"
                        id="form_title"><strong><?=trim($kiukaNoticeList[0]['title'])?></strong></span>
                    <button class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="text-align: left">
                    <div class="row">
                        <div class="col-md-12 col-ms-12">
                            <textarea class="alert alert-warning" readonly id="message-area2" name="message-area2"
                                style="width:100%; overflow: hidden; resize: none;" style="width: 100%; overflow:auto;"
                                oninput="autoGrow(this)" rows="4"><?=trim($kiukaNoticeList[0]['message'])?>
                    </textarea>
                        </div>
                        <div class="col-md-12 col-ms-12 sub-middle">
                            <div class="alert alert-info" style="margin-bottom: 10px;">
                                <strong><?=trim($kiukaNoticeList[0]['subtitle'])?></strong>
                            </div>
                        </div>
                        <div class="col-md-12 col-ms-12">
                            <table class="table table-bordered datatable">
                                <thead>
                                    <tr>
                                        <th class="info table-notic" style="text-align: center; color: #31708f;">
                                            <?=trim($kiukaInfoList[0]['titletop'])?></th>
                                        <?php 
                                       for ($i = $MIN_KYUKA_INFO_COUNT ; $i <= $MAX_KYUKA_INFO_COUNT; $i++) {
                                        $key = "ttop" . $i;
                                        if (!isset($kiukaInfoListDatasShow[$key]) || trim($kiukaInfoListDatasShow[$key]) == '') {
                                            continue;
                                        }
                                        $text =  trim($kiukaInfoListDatasShow[$key]);
                                            echo '<td name="data-row-' . $i . '" class="table-notic" style="text-align: center;">' . $text . '</td>';
                                         }
                                      ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="info" style="text-align: center; color: #31708f;">
                                            <?=trim($kiukaInfoList[0]['titlebottom'])?></th>
                                            <?php 
                                       for ($i = $MIN_KYUKA_INFO_COUNT ; $i <= $MAX_KYUKA_INFO_COUNT; $i++) {
                                        $key = "tbottom" . $i;
                                        if (!isset($kiukaInfoListDatasShow[$key]) || trim($kiukaInfoListDatasShow[$key]) == '') {
                                            continue;
                                        }
                                        $text =  trim($kiukaInfoListDatasShow[$key]);
                                            echo '<td name="data-row-' . $i . '" class="table-notic" style="text-align: center;">' . $text . '</td>';
                                         }
                                      ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>




<script>
window.onload = function() {
    setTimeout(hideLoadingOverlay, 500);
    startLoading();
    autoGrow(document.getElementById("message-area"));
    autoGrow(document.getElementById("message-area2"));
};


// get Data by form 


function validationData() {

    var title_value = $('#form_title').val();
    var message_value = $('#message-area').val();
    var subTitle_value = $('#sub_title').val();

    var title_row_1 = $('textarea[name="title-row-1"]').val().replace(/,/g, '、');
    var data_row_1 = $('textarea[name="data-row-1"]').map(function() {
        return $(this).val().replace(/,/g, '、');
    }).get().join(',');

    var title_row_2 = $('textarea[name="title-row-2"]').val().replace(/,/g, '、');
    var data_row_2 = $('textarea[name="data-row-2"]').map(function() {
        return $(this).val().replace(/,/g, '、');
    }).get().join(',');

    $('input[name="title_value"]').val(title_value);
    $('input[name="message_value"]').val(message_value);
    $('input[name="subTitle_value"]').val(subTitle_value);
    $('input[name="title_row_1"]').val(title_row_1);
    $('input[name="data_row_1"]').val(data_row_1);
    $('input[name="title_row_2"]').val(title_row_2);
    $('input[name="data_row_2"]').val(data_row_2);
    // set data to form 
    return true;
}


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
    }, 500);
}


// auto resize text area
function autoGrow(textarea) {
    textarea.style.height = "auto";
    let scrollHeight = textarea.scrollHeight;
    if (scrollHeight > textarea.rows * 16 * 4) {
        textarea.style.height = (textarea.rows * 16 * 4) + "px";
        textarea.style.overflowY = "scroll";
    } else {
        textarea.style.height = scrollHeight + "px";
    }
}
</script>

<?php include('../inc/footer.php'); ?>