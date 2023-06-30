<?php
// include('../inc/menu.php');
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
}

if ($_SESSION['auth_type'] == 1) { // if not admin 
    header("Location: ./../../index.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title class="page_header_text">Kintai</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>

    <!-- common Javascript -->
    <script type="text/javascript" src="../assets/js/common.js"> </script>

    <!-- Datepeeker 위한 link    -->
    <script src="../assets/js/jquery-ui.min.js"></script>

    <!-- common CSS -->
    <link rel="stylesheet" href="../assets/css/common.css">

</head>


<!-- ****CSS*****  -->
<style type="text/css">
    .datatable tr th {
        background-color: #D9EDF7;
        text-align: center;
    }

    .datatable tr td {
        text-align: center;
    }

    .btn {
        width: 80px;
        height: 32px;
    }

    .hidden-table {
        display: none;
    }

    .colorRed {
        color: red;
    }

    .colorGreen {
        color: green;
    }

    .V_hidden_text {
        visibility: hidden;
    }


    .colorSuccess {
        color: forestgreen;
    }

    .colorError {
        color: red
    }
</style>
<!-- ****HTML*****  -->

<body>
    <?php include('../inc/menu.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="title_name">
                    <span class="text-left">勤務管理表</span>
                </div>
            </div>
            <div class="col-md-8"></div>
            <div class="col-md-2 text-right">
                <div class="title_btn">
                    <input type="button" id="btnNew" onclick="registerClickfn()" value=" 新規 ">
                </div>
            </div>
        </div>

        <div class="form-group">
            <table class="table table-bordered datatable">
                <thead>
                    <tr class="info">
                        <th style="text-align: center; width: 5%;">ID</th>
                        <th style="text-align: center; width: 20%;">現場名</th>
                        <!-- <th style="text-align: center; width: 16%;" colspan="2">作業期間</th> -->
                        <th style="text-align: center; width: 13%;">勤務開始時間</th>
                        <th style="text-align: center; width: 13%;">勤務終了時間</th>
                        <th style="text-align: center; width: 7%;">昼休</th>
                        <th style="text-align: center; width: 7%;">夜休</th>
                        <th style="text-align: center; width: 6%;">使用</th>
                        <th style="text-align: center; width: auto;">備考</th>
                    </tr>
                </thead>

                <tbody id="body__dataNotExists" class="hidden-table">
                    <!-- nodata -->
                    <tr>
                        <td colspan="11" align="center">登録されたデータがありません.</td>
                    </tr>
                </tbody>

                <tbody id="body__showdata">

                </tbody>
            </table>
        </div>
    </div>

    <!--==============--cmodal : change modal====-->
    <!--===========　編集 Modal ======================-->
    <!--=============================================-->
    <div class="row">
        <div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        現場編集(<span id="">管理者のみ</span>)
                        <button class="close" data-dismiss="modal" onclick="handlerCloseModal(2)">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-9">
                                <label for="genbaname--cmodal">現場名</label>
                                <input type="text" class="form-control" id="genbaname--cmodal" placeholder="現場名">
                                <input type="hidden" id="genbaid--cmodal">
                            </div>
                            <div class="col-md-3">
                                <label for="use_yn--cmodal"><strong>使用</strong></label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="use_yn--cmodal" value="1">使用
                                    <input type="radio" name="use_yn--cmodal" value="0">中止
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="workstrTime--cmodal">業務開始時間</label>
                                <input type="text" class="form-control" id="workstrTime--cmodal" placeholder="09:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-6">
                                <label for="workendTime--cmodal">業務終了時間</label>
                                <input type="text" class="form-control" id="workendTime--cmodal" placeholder="18:00"
                                    required="required" style="text-align: center">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="offtime1--cmodal">昼休(時:分)</label>
                                <input type="text" class="form-control" id="offtime1--cmodal" placeholder="01:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="offtime2--cmodal">夜休(時:分)</label>
                                <input type="text" class="form-control" id="offtime2--cmodal" placeholder="00:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-6">
                                <label for="bigo--cmodal">備考</label>
                                <input type="text" class="form-control" id="bigo--cmodal" placeholder="備考"
                                    required="required" style="text-align: left">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <p style="visibility: hidden;" id="statusText--cmodal"></p>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-md-3"></div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnUpd--cmodal"
                                    onclick="handleupdateModal()" role="button">編集 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel--cmodal" href="#"
                                    onclick="handledeleteModal()" role="button">削除 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-warning btn-md" id="btnRet--cmodal" href="#"
                                    onclick="handlerCloseModal(2)" role="button" data-dismiss="modal">閉じる </a></p>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--=============================================-->
    <!--===========新規 Modal ======================-->
    <!--=============================================-->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        現場登録(<span id="">管理者のみ</span>)
                        <button class="close" data-dismiss="modal" onclick="handlerCloseModal(1)">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-9">
                                <label for="genbaname--rmodal">現場名</label>
                                <input type="text" class="form-control" id="genbaname--rmodal" placeholder="現場名">
                                <input type="hidden" id="genbaid--rmodal">
                            </div>
                            <div class="col-md-3">
                                <label for="use_yn--rmodal"><strong>使用</strong></label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="use_yn--rmodal" value="1">使用
                                    <input type="radio" name="use_yn--rmodal" value="0">中止
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="workstrTime--rmodal">業務開始時間</label>
                                <input type="text" class="form-control" id="workstrTime--rmodal" placeholder="09:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-6">
                                <label for="workendTime--rmodal">業務終了時間</label>
                                <input type="text" class="form-control" id="workendTime--rmodal" placeholder="18:00"
                                    required="required" style="text-align: center">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="offtime1--rmodal">昼休(時:分)</label>
                                <input type="text" class="form-control" id="offtime1--rmodal" placeholder="01:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="offtime2--rmodal">夜休(時:分)</label>
                                <input type="text" class="form-control" id="offtime2--rmodal" placeholder="00:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-6">
                                <label for="bigo--rmodal">備考</label>
                                <input type="text" class="form-control" id="bigo--rmodal" placeholder="備考"
                                    required="required" style="text-align: left">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="text-align: center">
                        <p style="visibility: hidden;" id="statusText--rmodal"></p>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel--rmodal" href="#"
                                    onclick="handlerRegisterModal()" role="button">登録 </a></p>
                        </div>
                        <div class="col-md-3">
                            <p class="text-center"><a class="btn btn-warning btn-md" id="btnRet--rmodal" href="#"
                                    onclick="handlerCloseModal(1)" role="button" data-dismiss="modal">閉じる </a></p>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ****SCRIPT*****  -->
    <script>
        //================================/// 
        //=========== init===============//     OK
        //============================///  
        window.onload = function () {
            handleDrawTableData();
        };


        function handleDrawTableData() {
            dataChanged = false;
            const response = ajaxRequest(
                'kinmuController.php?type=<?php echo $TYPE_GET_ALL_DATA_KINMU ?>',
                'GET',
                function (response) {
                    if (response === "<?php echo $NO_DATA; ?>") {
                        // nodata => 
                        drawEmptyData();
                        return;
                    }
                    parsedResponse = null;
                    try {
                        parsedResponse = JSON.parse(response);
                    } catch (error) {
                        drawEmptyData();
                        return;
                    }
                    drawDatatoView(parsedResponse['genbaList']);
                    return;
                },
                function (errorStatus) {
                    drawEmptyData();
                }
            );
        }

        function drawEmptyData() {
            changeShowStatus(false);
        }

        function drawDatatoView(data) {
            if (data === undefined) {
                drawEmptyData();
                return;
            }

            var tbody = document.getElementById("body__showdata");

            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }


            changeShowStatus(true);
            console.log(data);
            for (var i = 0, len = data.length; i < len; i++) {
                var row = document.createElement("tr");

                // id
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cgenid");
                cgenidSpan.innerText = data[i]['genid'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);

                //cgenbaname
                var cgenbanameCell = document.createElement("td");
                var cgenbanameLink = document.createElement("a");
                cgenbanameLink.setAttribute("href", "#");
                var cgenbanameSpan = document.createElement("span");
                cgenbanameSpan.setAttribute("onclick", "editClickfn(" + i + ")");
                cgenbanameSpan.setAttribute("class", "showModal");
                cgenbanameSpan.setAttribute("name", "cgenbaname");
                cgenbanameSpan.innerText = data[i]['genbaname'];
                cgenbanameLink.appendChild(cgenbanameSpan);
                cgenbanameCell.appendChild(cgenbanameLink);
                row.appendChild(cgenbanameCell);
                // str time
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cworkstrtime");
                cgenidSpan.innerText = data[i]['workstrtime'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);
                // end time
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cworkendtime");
                cgenidSpan.innerText = data[i]['workendtime'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);

                //cofftime1
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cofftime1");
                cgenidSpan.innerText = data[i]['offtime1'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);
                //cofftime2
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cofftime1");
                cgenidSpan.innerText = data[i]['offtime2'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);

                //cuse_yn
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cuse_yn");
                cgenidSpan.innerText = data[i]['use_yn'] === "1" ? "使用" : "中止";
                if (data[i]['use_yn'] === "1") {
                    cgenidSpan.classList.add("colorGreen");
                } else {
                    cgenidSpan.classList.add("colorRed");
                }
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);

                //cbigo
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cbigo");
                cgenidSpan.innerText = data[i]['bigo'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);


                //==// hidden field
                // hiden genid
                var tgenid = document.createElement("input");
                tgenid.setAttribute("name", "tgenid");
                tgenid.setAttribute("type", "hidden");
                tgenid.setAttribute("value", data[i]['genid']);
                row.appendChild(tgenid);

                // hiden genid
                var tgenName = document.createElement("input");
                tgenName.setAttribute("name", "tgenName");
                tgenName.setAttribute("type", "hidden");
                tgenName.setAttribute("value", data[i]['genbaname']);
                row.appendChild(tgenName);

                // hiden str time
                var tstrTime = document.createElement("input");
                tstrTime.setAttribute("name", "tstrTime");
                tstrTime.setAttribute("type", "hidden");
                tstrTime.setAttribute("value", data[i]['workstrtime']);
                row.appendChild(tstrTime);

                // hiden tend time
                var tendTime = document.createElement("input");
                tendTime.setAttribute("name", "tendTime");
                tendTime.setAttribute("type", "hidden");
                tendTime.setAttribute("value", data[i]['workendtime']);
                row.appendChild(tendTime);

                // hidden tofftime1
                var toffTime1 = document.createElement("input");
                toffTime1.setAttribute("name", "toffTime1");
                toffTime1.setAttribute("type", "hidden");
                toffTime1.setAttribute("value", data[i]['offtime1']);
                row.appendChild(toffTime1);

                // hidden tofftime2
                var toffTime2 = document.createElement("input");
                toffTime2.setAttribute("name", "toffTime2");
                toffTime2.setAttribute("type", "hidden");
                toffTime2.setAttribute("value", data[i]['offtime2']);
                row.appendChild(toffTime2);

                // hidden tuse_yn
                var tuse_yn = document.createElement("input");
                tuse_yn.setAttribute("name", "tuse_yn");
                tuse_yn.setAttribute("type", "hidden");
                tuse_yn.setAttribute("value", data[i]['use_yn']);
                row.appendChild(tuse_yn);

                // hidden tbigo
                var tbigo = document.createElement("input");
                tbigo.setAttribute("name", "tbigo");
                tbigo.setAttribute("type", "hidden");
                tbigo.setAttribute("value", data[i]['bigo']);
                row.appendChild(tbigo);

                tbody.appendChild(row);
            }

        }
        //＝＝＝＝=================//
        // =======click edit=====//  
        //＝＝＝＝===============//
        function editClickfn(idx) {
            // 
            // open modal 
            $('#modal2').modal('toggle');
            $("#genbaname--cmodal").val($('[name="tgenName"]').eq(idx).val());
            $("#genbaid--cmodal").val($('[name="tgenid"]').eq(idx).val());
            $('[name="tuse_yn"]').eq(idx).val() === '1'
                ? $("input:radio[name='use_yn--cmodal']:radio[value='1']").prop('checked', true)
                : $("input:radio[name='use_yn--cmodal']:radio[value='0']").prop('checked', true);
            $("#workstrTime--cmodal").val($('[name="tstrTime"]').eq(idx).val());
            $("#workendTime--cmodal").val($('[name="tendTime"]').eq(idx).val());

            $("#offtime1--cmodal").val($('[name="toffTime1"]').eq(idx).val());
            $("#offtime2--cmodal").val($('[name="toffTime2"]').eq(idx).val());
            $("#bigo--cmodal").val($('[name="tbigo"]').eq(idx).val());

        }

        function registerClickfn() {
            // open modal 
            $('#modal').modal('toggle');
            $("input:radio[name='use_yn--rmodal']:radio[value='1']").prop('checked', true)
        }


        //＝＝＝＝=================//
        // =======Register new =====//  
        //＝＝＝＝===============//
        function handlerRegisterModal() {
            var genName = document.getElementById("genbaname--rmodal");
            if (genName.value === "") {
                alert("<?php echo $KINMU_NAME_IS_INVALID ?>");
                return;
            }
            //get value
            var startTime = document.getElementById("workstrTime--rmodal");
            var endTime = document.getElementById("workendTime--rmodal");
            var offtime1 = document.getElementById("offtime1--rmodal");
            var offtime2 = document.getElementById("offtime2--rmodal");
            var bigo = document.getElementById("bigo--rmodal");
            var use_yn;
            var useYnInputs = document.getElementsByName("use_yn--rmodal");
            for (var i = 0; i < useYnInputs.length; i++) {
                if (useYnInputs[i].checked) {
                    use_yn = useYnInputs[i].value;
                    break;
                }
            }
            // check input value empty 
            var isTimeEmpty = startTime.value === "" && endTime.value === "";
            var isOffTime1Empty = offtime1.value === "";
            var isOffTime2Empty = offtime2.value === "";
            if (isTimeEmpty && isOffTime1Empty && isOffTime2Empty) {
                alert("<?php echo $KINMU_DEFAULT_VALUE_USE ?>");
                startTime.value = "09:00";
                endTime.value = "18:00";
                offtime1.value = "01:00";
                offtime2.value = "00:00";
            }
            if (isTimeEmpty ) {
                startTime.value = "09:00";
                endTime.value = "18:00";
            }
            if (isOffTime1Empty) {
                offtime1.value = "00:00";
            }
            if (isOffTime2Empty ) {
                offtime2.value = "00:00";
            }

            // REGEX valid number 
            if (!(/^(\d{1,2}):(\d{1,2})$/.test(startTime.value))) {
                alert("勤務開始時間を正しく入力してください\n 数字しか処理出来ません");
                return;
            } 
            if (!(/^(\d{1,2}):(\d{1,2})$/.test(endTime.value))) {
                alert("勤務終了時間を正しく入力してください\n 数字しか処理出来ません");
                return;
            } 
            if (!(/^(\d{1,2}):(\d{1,2})$/.test(offtime1.value))) {
                alert("昼休時間を正しく入力してください\n 数字しか処理出来ません");
                return;
            }
            if (!(/^(\d{1,2}):(\d{1,2})$/.test(offtime2.value))) {
                alert("夜休時間を正しく入力してください \n 数字しか処理出来ません");
                return;
            } 

            // format 9:1 -> 09:01 
            startTime.value = formatTime(startTime.value);
            endTime.value = formatTime(endTime.value);
            offtime1.value = formatTime(offtime1.value);
            offtime2.value = formatTime(offtime2.value);
             // (genbaname ,workstrtime , workendtime ,offtime1 ,offtime2 , bigo  , use_yn , REG_DT ) 
             var dataObject = {
                genbaname: genName.value,
                use_yn: use_yn,
                workstrtime: startTime.value,
                workendtime: endTime.value,
                offtime1: offtime1.value,
                offtime2: offtime2.value,
                bigo: bigo.value
            };
            const data = JSON.stringify(dataObject); // convert to json 
            const response = ajaxRequest(
                'kinmuController.php?type=<?php echo $TYPE_INSERT_DATA_KINMU ?>&data=' + data,
                'GET',
                function (response) {
                    if (response === "<?php echo $CONNECT_ERROR; ?>") {
                        changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--rmodal");
                        return;
                    }
                    changeStatusMessageModal(true, "<?php echo $KINMU_INSERT_SUCCESS; ?>", "statusText--rmodal");
                    setTimeout(function () {
                        handleDrawTableData();
                    }, 500);
                },
                function (errorStatus) {
                    changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--rmodal");
                }
            );

        }

        function formatTime(time) {
            var timeArr = time.split(":");
            var hours = parseInt(timeArr[0]);
            var minutes = parseInt(timeArr[1]);
            var formattedHours = (hours < 10) ? "0" + hours : hours.toString();
            var formattedMinutes = (minutes < 10) ? "0" + minutes : minutes.toString();
            return formattedHours + ":" + formattedMinutes;
        }


        //＝＝＝＝=================//
        // =======Update =====//  
        //＝＝＝＝===============//
        function handleupdateModal() {
            var genId = document.getElementById("genbaid--cmodal");
            var genName = document.getElementById("genbaname--cmodal");
            var use_yn;
            var useYnInputs = document.getElementsByName("use_yn--cmodal");
            for (var i = 0; i < useYnInputs.length; i++) {
                if (useYnInputs[i].checked) {
                    use_yn = useYnInputs[i].value;
                    break;
                }
            }
            var startTime = document.getElementById("workstrTime--cmodal");
            var endTime = document.getElementById("workendTime--cmodal");
            var offtime1 = document.getElementById("offtime1--cmodal");
            var offtime2 = document.getElementById("offtime2--cmodal");
            var bigo = document.getElementById("bigo--cmodal");

            var dataObject = {
                genid: genId.value,
                genbaname: genName.value,
                use_yn: use_yn,
                workstrtime: startTime.value,
                workendtime: endTime.value,
                offtime1: offtime1.value,
                offtime2: offtime2.value,
                bigo: bigo.value
            };
            const data = JSON.stringify(dataObject); // convert to json 
            const response = ajaxRequest(
                'kinmuController.php?type=<?php echo $TYPE_UPDATE_DATA_KINMU ?>&data=' + data,
                'GET',
                function (response) {
                    if (response === "<?php echo $CONNECT_ERROR; ?>") {
                        changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--cmodal");
                        return;
                    }
                    changeStatusMessageModal(true, "<?php echo $KINMU_UPDATE_SUCCESS; ?>", "statusText--cmodal");
                    setTimeout(function () {
                        handleDrawTableData();
                    }, 500);
                },
                function (errorStatus) {
                    changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--cmodal");
                }
            );
        }

        //＝＝＝＝=================//
        // =======Delete =====//  
        //＝＝＝＝===============//
        function handledeleteModal() {
            var genName = document.getElementById("genbaname--cmodal");
            var confirmation = confirm(`${genName.value}を削除しますか`);
            if (!confirmation)
                return;
            var genId = document.getElementById("genbaid--cmodal");
            var dataObject = {
                genid: genId.value,
            };
            const data = JSON.stringify(dataObject); // convert to json 
            const response = ajaxRequest(
                'kinmuController.php?type=<?php echo $TYPE_DELETE_DATA_KINMU ?>&data=' + data,
                'GET',
                function (response) {
                    if (response === "<?php echo $CONNECT_ERROR; ?>") {
                        changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--cmodal");
                        return;
                    }
                    changeStatusMessageModal(true, genName.value + "を<?php echo $KINMU_DELETE_SUCCESS; ?>", "statusText--cmodal");
                    setTimeout(function () {
                        handleDrawTableData();
                    }, 500);
                },
                function (errorStatus) {
                    changeStatusMessageModal(false, "<?php echo $ADD_DATA_ERROR; ?>", "statusText--cmodal");
                }
            );

        }


        //====// draw message text after register or clear
        function changeStatusMessageModal(status, text, element) {
            // statusText--cmodal
            var statusMessage = document.getElementById(element);
            if (status === null) {
                statusMessage.classList.remove("colorSuccess");
                statusMessage.classList.remove("colorError");
                statusMessage.style.visibility = "hidden";
                return;
            }

            if (status) {
                if (statusMessage.classList.contains("colorError")) {
                    statusMessage.classList.remove("colorError");
                }
                statusMessage.style.visibility = "visible";
                statusMessage.classList.add("colorSuccess");

                statusMessage.innerText = text;
            } else {
                if (statusMessage.classList.contains("colorSuccess")) {
                    statusMessage.classList.remove("colorSuccess");
                }
                statusMessage.classList.add("colorError");
                statusMessage.style.visibility = "visible";
                statusMessage.innerText = text;
            }
        }

        function changeShowStatus(status) {
            // if true => show data
            // if false => show データが存在しません
            var noDataShow = document.querySelector('#body__dataNotExists');
            var dataShow = document.querySelector('#body__showdata')

            if (status) {
                dataShow.classList.remove('hidden-table');
                noDataShow.classList.add('hidden-table');
            } else {
                dataShow.classList.add('hidden-table');
                noDataShow.classList.remove('hidden-table');
            }
        }


        function handlerCloseModal(modalNum) {
            if (modalNum === 1) { // register modal
                var statusModal = document.getElementById('statusText--rmodal');
                closeModalClearStatus(statusModal);
                // clear input
                $("#genbaname--rmodal").val("");
                $("#workstrTime--rmodal").val("");
                $("#workendTime--rmodal").val("");
                $("#offtime1--rmodal").val("");
                $("#offtime2--rmodal").val("");
                $("#bigo--rmodal").val("");
                $("input:radio[name='use_yn--rmodal']:radio[value='1']").prop('checked', true)

            } else if (modalNum === 2) {   // edit modal
                var statusModal = document.getElementById('statusText--cmodal');
                closeModalClearStatus(statusModal);
            }

        }

        function closeModalClearStatus(element) {
            element.innerHTML = "";
            element.style.visibility = "hidden";


        }
        //＝＝＝＝==========//
        // =======ajax=====// OK
        //＝＝＝＝==========//
        function ajaxRequest(url, method, successCallback, errorCallback) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        if (successCallback) {
                            successCallback(xhr.responseText);
                        }
                    } else {
                        if (errorCallback) {
                            errorCallback(xhr.status);
                        }
                    }
                }
            };
            xhr.open(method, url, true);
            xhr.send();
        }

        function ajaxRequestPromise(url, method) {
            return new Promise(function (resolve, reject) {
                ajaxRequest(
                    url,
                    method,
                    function (response) {
                        resolve(response);
                    },
                    function (errorStatus) {
                        reject(errorStatus);
                    }
                );
            });
        }

    </script>
    <?php include('../inc/footer.php'); ?>