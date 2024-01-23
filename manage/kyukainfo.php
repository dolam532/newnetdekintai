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
    text-align: center;
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

input[type=checkbox],
input[type=radio] {
    margin: 10px 0 0;
}

.print_btn-submit {
    display: inline-block;
    margin-top: 30px;
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


<div class="row">

    <div class="col-md-3">
    </div>
    <!-- input  -->
    <div class="col-md-6 text-center">
        <h3>年次有給休暇日数登録</h3>
        <br>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-8 col-ms-12">
                    <table class="table table-bordered datatable">
                        <thead>

                            <th class="info table-notic" style="text-align: center; color: #31708f;">
                                <?=trim($kiukaInfoList[0]['titletop'])?></th>
                            <th class="info table-notic" style="text-align: center; color: #31708f;">
                                <?=trim($kiukaInfoList[0]['titlebottom'])?></th>
                            <th class="info table-notic" style="text-align: center; color: #31708f;">選択</th>

                        </thead>
                        <tbody>
                            <?php 
							for ($i = $MIN_KYUKA_INFO_COUNT; $i <= $MAX_KYUKA_INFO_COUNT; $i++) {
								$keytop = "ttop" . $i;
								$keybottom = "tbottom" . $i;
								if (!isset($kiukaInfoListDatasShow[$keytop]) || trim($kiukaInfoListDatasShow[$keytop]) == '') {
									continue;
								}
								if (!isset($kiukaInfoListDatasShow[$keybottom]) || trim($kiukaInfoListDatasShow[$keybottom]) == '') {
									continue;
								}

                                if($i == $MIN_KYUKA_INFO_COUNT || $i == 21) {  // Min max 
                                    echo '<tr>';
                                
                                    echo '<td class="table-notic" style="text-align: center;">';
    echo '<input style="width: 100%;" type="text" id="data-top-' . $i . '" name="data-top-' . $i . '" value="' . $kiukaInfoListDatasShow[$keytop] . '">';
    echo '</td>';               
    
    echo '<td  class="table-notic" style="text-align: center;">';
    echo '<input style="width: 100%;" type="text"  id="data-bottom-' . $i . '" name="data-bottom-' . $i . '" value="' . $kiukaInfoListDatasShow[$keybottom] . '">';
    echo '</td>';
                                    
                                    echo '</tr>';
                                }  else {
                                    echo '<tr>';
                                
                                    echo '<td class="table-notic" style="text-align: center;">';
    echo '<input style="width: 100%;" type="text"  id="data-top-' . $i . '" name="data-top-' . $i . '" value="' . $kiukaInfoListDatasShow[$keytop] . '">';
    echo '</td>';               
    
    echo '<td  class="table-notic" style="text-align: center;">';
    echo '<input style="width: 100%;" type="text"    id="data-bottom-' . $i . '"name="data-bottom-' . $i . '" value="' . $kiukaInfoListDatasShow[$keybottom] . '">';
    echo '</td>';
                                    
                                    echo '</tr>';
                                }
                                }
							?>
                        </tbody>

                        <input type="hidden" name="title_value">
                        <input type="hidden" name="message_value">
                        <input type="hidden" name="subTitle_value">
                        <input type="hidden" name="title_row_1">
                        <input type="hidden" name="data_row_1">
                        <input type="hidden" name="title_row_2">
                        <input type="hidden" name="data_row_2">
                        <input type="hidden" id="selectedId" name="selectedId">




                    </table>
                </div>

            </div>


        </div>

        <div class="print_btn-submit">
            <button id="addNewBtn" class="btn btn-primary">追加</button>
        </div>

        <div class="print_btn-submit">
            <form method="post" class="form-inline ml-2">
                <button id="updateBtn" name="UpdateKyukaInfo" class="btn btn-info" style="width: auto;" type="submit"
                    onclick="return confirm('選択した項目を削除でよろしいでしょうか？')">編集</button>
            </form>
        </div>

        <div class="print_btn-submit">
            <form method="post" class="form-inline ml-2">
                <button id="deleteBtn" name="DeleteKyukaInfo" class="btn btn-warning" style="width: auto;" type="submit"
                    onclick="return confirm('選択した項目を削除でよろしいでしょうか？')">削除</button>
            </form>
        </div>
    </div>

    <div class="col-md-3">
    </div>


</div>






<script>
window.onload = function() {
    setTimeout(hideLoadingOverlay, 500);
    startLoading();
    inputSelectionCheckboxHandler();
    checkInputValue();


};

function inputSelectionCheckboxHandler() {
    // Duyệt qua tất cả các phần tử input
    $('input[id^="data-top-"]').each(function() {
        var $newRadioButton = $('<input  type="radio" name="radioButton">');
        $newRadioButton.val(this.id);

        $newRadioButton.change(function() {
            if (this.checked) {
                var value = this.value.replace('data-top-', '');
                $('#selectedId').val(value);
                checkInputValue();

            }
        });

        $(this).parent().parent().append($newRadioButton);
    });


}


function editFunction(id) {
    console.log("Editing " + id);
}



function checkInputValue() {
    if ($('#selectedId').val() === '') {
        $('#updateBtn, #deleteBtn').addClass('disabled');
    } else {
        $('#updateBtn, #deleteBtn').removeClass('disabled');
    }
    $('#updateBtn, #deleteBtn').click(function(e) {
        if ($(this).hasClass('disabled')) {
            e.preventDefault();
        }
    });

}


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
</script>

<?php include('../inc/footer.php'); ?>