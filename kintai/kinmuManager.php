<?php
// include('../inc/menu.php');
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');

if ($_SESSION['auth'] == false) {
    header("Location: ../loginout/loginout.php");
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
                    <input type="button" id="btnNew" value=" 新規 ">
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



    <!--=============================================-->
    <!--===========Modal ======================-->
    <!--=============================================-->
    <div class="row">
        <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        現場登録(<span id="sname">??</span>)
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="genbaname">現場名</label>
                                <input type="text" class="form-control" id="genbaname" placeholder="現場名">
                                <input type="hidden" id="seq" value="">
                                <input type="hidden" id="genid">
                                <input type="hidden" id="companyid" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-9">
                                <label for="genbacompany">会社名</label>
                                <input type="text" class="form-control" id="genbacompany" placeholder=会社名>
                            </div>
                            <div class="col-md-3">
                                <label for="use_yn"><strong>使用</strong></label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" name="use_yn" value="1">使用
                                    <input type="radio" name="use_yn" value="0">中止
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="strymd">作業期間(F)</label>
                                <input type="text" class="form-control" id="strymd" placeholder="2019/10/10"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="endymd">作業期間(T)</label>
                                <input type="text" class="form-control" id="endymd" placeholder="2019/12/31"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="worktime1">勤務時間(F)</label>
                                <input type="text" class="form-control" id="worktime1" placeholder="09:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="worktime2">勤務時間(T)</label>
                                <input type="text" class="form-control" id="worktime2" placeholder="18:00"
                                    required="required" style="text-align: center">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="offtime1">昼休(時:分)</label>
                                <input type="text" class="form-control" id="offtime1" placeholder="01:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-3">
                                <label for="offtime2">夜休(時:分)</label>
                                <input type="text" class="form-control" id="offtime2" placeholder="00:00"
                                    required="required" style="text-align: center">
                            </div>
                            <div class="col-md-6">
                                <label for="bigo">備考</label>
                                <input type="text" class="form-control" id="bigo" placeholder="備考" required="required"
                                    style="text-align: left">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align: center">
                        <div class="col-md-3"></div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnReg" href="#"
                                    role="button">登録 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-primary btn-md" id="btnDel" href="#"
                                    role="button">削除 </a></p>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center"><a class="btn btn-warning btn-md" id="btnRet" href="#"
                                    role="button">閉じる </a></p>
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

            // changeShowStatus(false);
            // return;

            if (data === undefined) {
                drawEmptyData();
                return;
            }
            changeShowStatus(true);
            console.log(data);
            for (var i = 0, len = data.length; i < len; i++) {
                // id
                var row = document.createElement("tr");
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
                cgenidSpan.innerText = data[i]['use_yn'] === "1" ? "可" : "不可";
                if (data[i]['use_yn'] === "1") {
                    cgenidSpan.classList.add("colorGreen");
                } else {
                    cgenidSpan.classList.add("colorRed");
                }
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell) ;

                //cbigo
                var cgenidCell = document.createElement("td");
                var cgenidSpan = document.createElement("span");
                cgenidSpan.setAttribute("name", "cbigo");
                cgenidSpan.innerText = data[i]['bigo'];
                cgenidCell.appendChild(cgenidSpan);
                row.appendChild(cgenidCell);

                var tbody = document.getElementById("body__showdata");
                tbody.appendChild(row);
            }

        }


        function changeShowStatus(status) {
            var noDataShow = document.querySelector('#body__dataNotExists');
            var dataShow = document.querySelector('#body__showdata')
            // if true => show 
            // if false => hidden -> nodata 
            if (status) {
                dataShow.classList.remove('hidden-table');
                noDataShow.classList.add('hidden-table');
            } else {
                dataShow.classList.add('hidden-table');
                noDataShow.classList.remove('hidden-table');
            }
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