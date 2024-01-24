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

                                echo '<tr>';
                                
                                echo '<td class="table-notic" style="text-align: center;">';
echo '<input style="width: 100%;" type="text" id="data-top-' . $i . '" name="data-top-' . $i . '" value="' . $kiukaInfoListDatasShow[$keytop] . '">';
echo '</td>';               

echo '<td  class="table-notic" style="text-align: center;">';
echo '<input style="width: 100%;" type="text"  id="data-bottom-' . $i . '" name="data-bottom-' . $i . '" value="' . $kiukaInfoListDatasShow[$keybottom] . '">';
echo '</td>';
                                
                                echo '</tr>';

    //                             if($i == $MIN_KYUKA_INFO_COUNT || $i == 21) {  // Min max 
    //                                 echo '<tr>';
                                
    //                                 echo '<td class="table-notic" style="text-align: center;">';
    // echo '<input style="width: 100%;" type="text" id="data-top-' . $i . '" name="data-top-' . $i . '" value="' . $kiukaInfoListDatasShow[$keytop] . '">';
    // echo '</td>';               
    
    // echo '<td  class="table-notic" style="text-align: center;">';
    // echo '<input style="width: 100%;" type="text"  id="data-bottom-' . $i . '" name="data-bottom-' . $i . '" value="' . $kiukaInfoListDatasShow[$keybottom] . '">';
    // echo '</td>';
                                    
    //                                 echo '</tr>';
    //                             }  else {
    //                                 echo '<tr>';
                                
    //                                 echo '<td class="table-notic" style="text-align: center;">';
    // echo '<input style="width: 100%;" type="text"  id="data-top-' . $i . '" name="data-top-' . $i . '" value="' . $kiukaInfoListDatasShow[$keytop] . '">';
    // echo '</td>';               
    
    // echo '<td  class="table-notic" style="text-align: center;">';
    // echo '<input style="width: 100%;" type="text"    id="data-bottom-' . $i . '"name="data-bottom-' . $i . '" value="' . $kiukaInfoListDatasShow[$keybottom] . '">';
    // echo '</td>';
                                    
    //                                 echo '</tr>';
    //                             }
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
            <button  onclick="kyukaInsert()" id="addNewBtn" name="InsertKyukaInfo" class="btn btn-primary">追加</button>
        </div>

        <div class="print_btn-submit">
            <!-- <form method="post" class="form-inline ml-2">
                <button id="updateBtn" name="UpdateKyukaInfo" class="btn btn-info" style="width: auto;" >編集</button>
            </form> -->

            <div class="print_btn">
                <a href="#" onclick="kyukaUpdate()" id="updateBtn" name="UpdateKyukaInfo" class="btn btn-info"
                    style="width: auto;">編集</a>
            </div>
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


<!-- Modal 編集 -->
<div class="row">
    <div class="modal" id="modal" tabindex="-2" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        年次有給休暇日数編集<span id="selkindatetext"></span>
                        <button class="close" data-dismiss="modal" id="modal_close-btn-top">&times;</button>
                    </div>
                    <div class="modal-body" style="text-align: left">
                        <div class="row">
                            <div class="col-xs-3 holder">
                                <label><?=trim($kiukaInfoList[0]['titletop'])?>選択</label>
                                <select id="workYearSelect" name="workYearSelect" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>年</option>
                                    <?php
									for ($i = 0; $i <= 50; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>
                            </div>
                            <div class="col-xs-3 holder">
                                <label>&nbsp;</label>
                                <select id="workMonthSelect" name="workMonthSelect" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>月</option>
                                    <?php
									for ($i = 0; $i <= 12; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>

                            </div>
                            <div class="col-xs-2 holder">

                            </div>
                            <div class="col-xs-4 holder">
                                <label><?=trim($kiukaInfoList[0]['titlebottom'])?>選択</label>
                                <select id="kyukadaySelect" name="kyukadaySelect" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>日</option>
                                    <?php
									for ($i = 0; $i <= 100; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>

                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-xs-6">
                                <label for="workYearMonth"> <?=trim($kiukaInfoList[0]['titletop'])?></label>
                                <input type="text" class="form-control" name="workYearMonth" id="workYearMonth"
                                    readonly>
                            </div>

                            <div class="col-xs-2 holder">
                            </div>
                            <div class="col-xs-4">
                                <label for="kyukaDayTime"> <?=trim($kiukaInfoList[0]['titlebottom'])?></label>
                                <input type="text" class="form-control" name="kyukaDayTime" id="kyukaDayTime"
                                    style="text-align: left" readonly>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-xs-10">
                                <span style="color:red;" id="showUpdateNotice" name="showUpdateNotice"> </span>
                            </div>
                        </div>
                        <input type="hidden" name="selectedIndex" class="btn btn-primary" id="selectedIndex">
                        <input type="hidden" id="updateWorkYear" name="updateWorkYear">
                       <input type="hidden" id="updateWorkMonth" name="updateWorkMonth">
                        <input type="hidden" id="updatekyukaDays" name="updatekyukaDays">

                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <input type="submit" name="saveUpdateKyukaInfo" class="btn btn-primary" id="btnReg"
                            role="button" value="登録">
                        <button type="button" class="btn btn-default " data-dismiss="modal" id="modalClose">閉じる</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 追加 -->
<div class="row">
    <div class="modal" id="modal2" tabindex="-2" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post">
                <div class="modal-content">
                    <div class="modal-header">
                        年次有給休暇日数追加<span id="selkindatetext_add"></span>
                        <button class="close" data-dismiss="modal" id="modal_close-btn-top">&times;</button>
                    </div>
                    <div class="modal-body" style="text-align: left">
                        <div class="row">
                            <div class="col-xs-3 holder">
                                <label><?=trim($kiukaInfoList[0]['titletop'])?>選択</label>
                                <select id="workYearSelect_add" name="workYearSelect_add" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>年</option>
                                    <?php
									for ($i = 0; $i <= 50; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>
                            </div>
                            <div class="col-xs-3 holder">
                                <label>&nbsp;</label>
                                <select id="workMonthSelect_add" name="workMonthSelect_add" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>月</option>
                                    <?php
									for ($i = 0; $i <= 12; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>

                            </div>
                            <div class="col-xs-2 holder">

                            </div>
                            <div class="col-xs-4 holder">
                                <label><?=trim($kiukaInfoList[0]['titlebottom'])?>選択</label>
                                <select id="kyukadaySelect_add" name="kyukadaySelect_add" class="form-control" size="1"
                                    onfocus='this.size=6;' onblur='this.size=1;' onchange='this.size=1; this.blur();'>
                                    <option value="" selected disabled>日</option>
                                    <?php
									for ($i = 0; $i <= 100; $i++) {
									?>
                                    <option value="<?= $i ?>" >
                                        <?= $i ?>
                                    </option>
                                    <?php
									}
									?>
                                </select>

                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-xs-6">
                                <label for="workYearMonth_add"> <?=trim($kiukaInfoList[0]['titletop'])?></label>
                                <input type="text" class="form-control" name="workYearMonth_add" id="workYearMonth_add"
                                    readonly>
                            </div>

                            <div class="col-xs-2 holder">
                            </div>
                            <div class="col-xs-4">
                                <label for="kyukaDayTime_add"> <?=trim($kiukaInfoList[0]['titlebottom'])?></label>
                                <input type="text" class="form-control" name="kyukaDayTime_add" id="kyukaDayTime_add"
                                    style="text-align: left" readonly>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-xs-10">
                                <span style="color:red;" id="showUpdateNotice_add" name="showUpdateNotice_add"> </span>
                            </div>
                        </div>
         

                    </div>
                    <input type="hidden" id="newMaxId" name="newMaxId">
                    <input type="hidden" id="insertWorkYear" name="insertWorkYear">
                    <input type="hidden" id="insertWorkMonth" name="insertWorkMonth">
                    <input type="hidden" id="insertkyukaDays" name="insertkyukaDays">

                    <div class="modal-footer" style="text-align: center">
                        <input type="submit" name="saveUpdateKyukaInfo_add" class="btn btn-primary" id="btnReg_add"
                            role="button" value="登録">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>






<script>
window.onload = function() {
    setTimeout(hideLoadingOverlay, 500);
    startLoading();
    inputSelectionCheckboxHandler();
    checkInputValue();
    showTextAfterSelectedHandler();

};

// Show 編集
function kyukaUpdate() {
    $('#modal').modal('toggle');
    var selectedId = $('#selectedId').val();
    var keytop = "ttop" + selectedId;
    var keybottom = "tbottom" + selectedId;
    var kiukaInfoListDatasShow = <?php echo json_encode($kiukaInfoListDatasShow); ?>;

    var selectedTopTextValue = kiukaInfoListDatasShow[keytop];
    var selectedBottomTextValue = kiukaInfoListDatasShow[keybottom];


    var selectedTopvalueYear = 0;
    var selectedTopvalueMonth = 0;

    if (selectedTopTextValue.includes('年')) {
        var yearMonth = selectedTopTextValue.split('年');
        selectedTopvalueYear = yearMonth[0] ? parseInt(yearMonth[0]) : 0;
        if (yearMonth[1]) {
            var month = yearMonth[1].split('月');
            selectedTopvalueMonth = month[0] ? parseInt(month[0]) : 0;
        }
    } else if (selectedTopTextValue.includes('月')) {
        var month = selectedTopTextValue.split('月');
        selectedTopvalueMonth = month[0] ? parseInt(month[0]) : 0;
    }

    var dateCount = selectedBottomTextValue.split('日');
    var selectedBottomValue = dateCount[0] ? parseInt(dateCount[0]) : 0;
    
    // select
    $('#workYearSelect').val(selectedTopvalueYear);
    $('#workMonthSelect').val(selectedTopvalueMonth);
    $('#kyukadaySelect').val(selectedBottomValue);


    var yearStr = selectedTopvalueYear + '年';
    var monthStr = selectedTopvalueMonth + '月'
    if (selectedTopvalueYear === null || isNaN(selectedTopvalueYear) || selectedTopvalueYear == 0) {
        yearStr = ''
    }
    if (selectedTopvalueMonth === null || isNaN(selectedTopvalueMonth) || selectedTopvalueMonth == 0) {
        monthStr = ''
    }
    $('#workYearMonth').val(yearStr + monthStr);
    $('#kyukaDayTime').val(selectedBottomValue + '日');

    $('#showUpdateNotice').text("")
    if(selectedId == <?= $MIN_KYUKA_INFO_COUNT?>) {
        $('#workYearMonth').val(yearStr + monthStr + '以内');
        $('#showUpdateNotice').text("<?= $kyuka_info_min_workYm ?>");
        
    }
    if(selectedId == <?= $MAX_KYUKA_INFO_COUNT?>) {
        $('#workYearMonth').val(yearStr + monthStr + '以上');
        $('#showUpdateNotice').text("<?= $kyuka_info_max_workYm ?>");
    }

    updateDataToUnputs();


}

// Show 追加
function kyukaInsert(){
    $('#modal2').modal('toggle');
    var kiukaInfoListDatasShow = <?php echo json_encode($kiukaInfoListDatasShow); ?>;
    var keys = Object.keys(kiukaInfoListDatasShow);
    var maxKey = keys[keys.length - 3].match(/\d+$/)[0];
    console.log(maxKey);
    $('#newMaxId').val(maxKey);

}

function updateDataToUnputs() {
    $('#selectedIndex').val( $('#selectedId').val());
    $('#updateWorkYear').val($('#workYearSelect').val());
    $('#updateWorkMonth').val($('#workMonthSelect').val());
    $('#updatekyukaDays').val($('#kyukadaySelect').val());

    $('#insertWorkYear').val($('#workYearSelect_add').val());
    $('#insertWorkMonth').val($('#workMonthSelect_add').val());
    $('#insertkyukaDays').val($('#kyukadaySelect_add').val());


    
    // set value to input 
    // <input type="hidden" id="insertWorkYear" name="insertWorkYear">
    //  <input type="hidden" id="insertWorkMonth" name="insertWorkMonth">
    //  <input type="hidden" id="insertkyukaDays" name="insertkyukaDays">

}

// add funtion to select box in modal 
function showTextAfterSelectedHandler() {
    $('#workYearSelect, #workMonthSelect').change(function() {
        var year = $('#workYearSelect').val();
        var month = $('#workMonthSelect').val();
        var yearStr = year + '年';
        var monthStr = month + '月'
        if (year === null || isNaN(year) || year == 0) {
            yearStr = ''
        }
        if (month === null || isNaN(month) || month == 0) {
            monthStr = ''
        }
        $('#workYearMonth').val(yearStr + monthStr);

        updateDataToUnputs();

    });

    $('#kyukadaySelect').change(function() {
        var days = $('#kyukadaySelect').val();

        $('#kyukaDayTime').val(days + '日');
        updateDataToUnputs();
    });




    // Add New 
    $('#workYearSelect_add, #workMonthSelect_add').change(function() {
        var year = $('#workYearSelect_add').val();
        var month = $('#workMonthSelect_add').val();
        var yearStr = year + '年';
        var monthStr = month + '月'
        if (year === null || isNaN(year) || year == 0) {
            yearStr = ''
        }
        if (month === null || isNaN(month) || month == 0) {
            monthStr = ''
        }
        $('#workYearMonth_add').val(yearStr + monthStr);
        updateDataToUnputs();
    });

    $('#kyukadaySelect_add').change(function() {
        var days = $('#kyukadaySelect_add').val();

        $('#kyukaDayTime_add').val(days + '日');
        updateDataToUnputs();
    });


}



function inputSelectionCheckboxHandler() {
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

    if ($('#selectedId').val() === '' ) {
        $('#updateBtn, #deleteBtn').addClass('disabled');
    } else {
        $('#updateBtn, #deleteBtn').removeClass('disabled');
    }

    if ($('#selectedId').val() == <?= $MIN_KYUKA_INFO_COUNT ?> || $('#selectedId').val() == <?= $MAX_KYUKA_INFO_COUNT ?> ) {
        $('#deleteBtn').addClass('disabled');
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