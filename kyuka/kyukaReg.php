<?php
// connect to database
session_start();
include('../inc/dbconnect.php');
include('../inc/message.php');
include('../inc/const_array.php');
include('../model/kyukamodel.php');
include('../inc/header.php');
include('../model/inactive.php');

if ($_SESSION['auth'] == false) {
	header("Location: ../loginout/loginout.php");
}
echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css'>";
?>
<style>
.usertbl tr th {
    background-color: #D9EDF7;
    text-align: center;
}

.usertbl tr td {
    text-align: center;
}

.btn {
    width: 80px;
    height: 32px;
}

div label {
    padding: 5px;
}

nav.navbar.navbar-inverse {
    margin-bottom: 0px;
}

.popup-title {
    font-size: 20px;
    font-weight: bold;
}

span.vacationid_class,
span.kyukaReg_class {
    display: none;
}

.groupinput {
    display: table;
}

.table-wrap {
    overflow-x: scroll;
}

.table {
    width: 100%;
    border-collapse: collapse;
    white-space: nowrap;
}

.divided {
    position: relative;
}

.notice_time {
    position: absolute;
    top: -13px;
    left: 311px;
    padding: 0px;
    z-index: 2;
    color: red;
    font-size: smaller;
}

.layout {
    position: relative;
    z-index: 1;
}

/* modal popup lock */


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

.submission-status_1 {
    color: #337ab7;
}

.submission-status_2 {
    color: blue;
}

.submission-status_3 {
    color: green;
}

.hiddenInput {
    display: none;
}


</style>
<title>休暇届</title>
<?php include('../inc/menu.php'); ?>
<div class="container">
    <?php
	if (isset($_SESSION['save_success']) && isset($_POST['SaveKyuka'])) {
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
	if (isset($_SESSION['update_success']) && isset($_POST['UpdateKyuka'])) {
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
	if (isset($_SESSION['delete_success']) && isset($_POST['DelKyuka'])) {
	?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['delete_success']; ?>
    </div>
    <?php
		unset($_SESSION['delete_success']);
	}
	?>
    <?php
	if (isset($_SESSION['kakutei_success']) && isset($_POST['Kyukateishutsu'])) {
	?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['kakutei_success']; ?>
    </div>
    <?php
		unset($_SESSION['kakutei_success']);
	}
	?>

    <?php
	if (isset($_SESSION['user_kyuka_data_not_found']) && isset($_POST['Kyukateishutsu'])) {
	?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['user_kyuka_data_not_found']; ?>
    </div>
    <?php
		unset($_SESSION['user_kyuka_data_not_found']);
	}
	?>

    <?php
	if (isset($_SESSION['kakutei_fail']) && isset($_POST['Kyukateishutsu'])) {
	?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['kakutei_fail']; ?>
    </div>
    <?php
		unset($_SESSION['kakutei_fail']);
	}
	?>


    <?php
	if (isset($_SESSION['user_kyuka_modoshi_success']) && isset($_POST['KyukaHenshuModoshi'])) {
	?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['user_kyuka_modoshi_success']; ?>
    </div>
    <?php
		unset($_SESSION['user_kyuka_modoshi_success']);
	}
	?>


    <?php
	if (isset($_SESSION['user_kyuka_modoshi_fail']) && isset($_POST['KyukaHenshuModoshi'])) {
	?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['user_kyuka_modoshi_fail']; ?>
    </div>
    <?php
		unset($_SESSION['user_kyuka_modoshi_fail']);
	}
	?>

    <?php
	if (isset($_SESSION['tanto_shonin_success']) && isset($_POST['KyukaTantoshaShonin'])) {
	?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['tanto_shonin_success']; ?>
    </div>
    <?php
		unset($_SESSION['tanto_shonin_success']);
	}
	?>

    <?php
	if (isset($_SESSION['sekinin_shonin_success']) && isset($_POST['KyukaSekininshaShonin'])) {
	?>
    <div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['sekinin_shonin_success']; ?>
    </div>
    <?php
		unset($_SESSION['sekinin_shonin_success']);
	}
	?>
    <?php
	if (isset($_SESSION['tanto_shonin_error']) && isset($_POST['KyukaTantoshaShonin'])) {
	?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['tanto_shonin_error']; ?>
    </div>
    <?php
		unset($_SESSION['tanto_shonin_error']);
	}
	?>



    <?php
	if (isset($_SESSION['sekinin_shonin_error']) && isset($_POST['KyukaSekininshaShonin'])) {
	?>
    <div class="alert alert-danger alert-dismissible" role="alert" auto-close="5000">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <?php echo $_SESSION['sekinin_shonin_error']; ?>
    </div>
    <?php
		unset($_SESSION['sekinin_shonin_error']);
	}
	?>



    <form method="post">
        <div class="row">
            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
            <div class="col-md-2 text-left">
                <div class="title_name">
                    <span class="text-left">休暇届</span>
                </div>
            </div>
        </div>
        <form method="post">
            <div class="col-md-4 text-left">
                <div class="title_condition">
         
                <label for="filterByStatusCode">状態</label>
                                 <!-- $KYUKA_SUBMISSTION_STATUS_FILTER -->
                                 <select id="filterByStatusCode" name="filterByStatusCode"  
                        onchange='handleSelectFilterStatusChange()' style="padding:2px; width:70%;">
                        <?php
						foreach ($KYUKA_SUBMISSTION_STATUS_FILTER as $key => $value) {
										?>
                        <option name="filterSubmissionStatusCodeOption" size="10" value="<?= $key ?>" <?php if ($key == $filterByStatusCode) {
											  echo ' selected="selected"';
										  } ?>>
                            <?= $value ?>
                        </option>
                        <?php
									}
									?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 text-left">
                <div class="title_condition">
                    <label>社員名 :
                        <select id="searchName" name="searchName" style="padding:2px; width:70%;">
                            <option value="" selected="">全て</option>
                            <?php
								foreach ($user_list as $value) {
								?>
                            <option value="<?= $value['email'] ?>" <?php if ($value['email'] == $_POST['searchName']) {
																				echo ' selected="selected"';
																			} ?>>
                                <?= $value['name'] ?>
                            </option>
                            <?php
								}
								?>
                        </select>
                    </label>
                </div>
            </div>
            <div class="col-md-3 text-right">
                <div class="title_condition">

                    <label for="searchKyukaByYear">申請年月</label>
                    <input style="width:30%;" id="searchKyukaByYear" name="searchKyukaByYear" type="number" value="<?=$searchByYear ?>"/>
                    <select id="searchKyukaByMonth"  name="searchKyukaByMonth" class="seldate" style="padding:3px;">
							<?php
							foreach (ConstArray::$search_month_kyuka as $key => $value) {
								?>
								<option value="<?= $key ?>" <?php if ($key == $searchByMonth) {
									  echo ' selected="selected"';
								  } ?>>
									<?= $value ?>
								</option>
								<?php
							}
							?>
						</select>
                    <br>
                 
                </div>
 
                <div class="title_btn">
                    <input type="submit" id="ClearButton" name="ClearButton" value="クリア ">&nbsp;
                    <input type="submit" name="btnSearchReg" value="検索 ">&nbsp;
                    <input type="button" id="btnNew" value="新規 ">&nbsp;
                    <input type="button" id="btnAnnt" value="お知らせ ">
                </div>



            </div>
            <input type="hidden" id="selectedFilterByStatusCode" name="selectedFilterByStatusCode" value="<?= $filterByStatusCode?>"  />
        </form>
   

        <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
           <div class="row">
           
      
            <div class="col-md-12 text-right" style="display: flex; justify-content: flex-start;">
            <form method="post" style="margin: 0 10px;">
                <button type="submit" name="KyukaHenshuModoshi" class="" style="width: auto;" type="button"
                    onclick="return checkHenshuChuModoshiSubmit()">編集中に戻す</button>

                <input type="hidden" name="user-kyuka-multi-select-input">
                <input type="hidden" name="user-kyuka-multi-select-status">
            </form>
            <form method="post" style="margin: 0 10px;">
                <button type="submit" name="KyukaTantoshaShonin" class="" style="width: auto;" type="button"
                    onclick="return checkTantoshaShoninSubmit()">担当者承認</button>
                <input type="hidden" name="user-kyuka-multi-select-status">

                <input type="hidden" name="user-kyuka-multi-select-input">
            </form>

            <form method="post" style="margin: 0 10px;">
                <button type="submit" name="KyukaSekininshaShonin" class="" style="width: auto;" type="button"
                    onclick="return checkSekininshaShoninSubmit()">責任者承認</button>

                <input type="hidden" name="user-kyuka-multi-select-status">
                <input type="hidden" name="user-kyuka-multi-select-input">
            </form>
        <?php endif; ?>
        </div>
       
    
   
    </div>

        <?php elseif ($_SESSION['auth_type'] == constant('USER')) : ?>
        <div class="col-md-2 text-left">
            <div class="title_name">
                <span class="text-left">休暇届</span>
            </div>
        </div>
        <div class="col-md-4 text-left"></div>
        <div class="col-md-3 text-left">
            <div class="title_condition">
                <label>社員名 : <?= $_SESSION['auth_name'] ?></label>
            </div>
        </div>
        <div class="col-md-3 text-right">
            <div class="title_btn">
                <input type="button" id="btnNew" value="新規 ">&nbsp;
                <input type="button" id="btnAnnt" value="お知らせ ">
            </div>
        </div>
        <?php endif; ?>

       

</div>
<div class="form-group table-wrap">
<div class="col-md-2 text-right" style="display: flex; justify-content: flex-start;">
        <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
            <input type="checkbox" id="user-kyuka-select-all-checkbox" value="全て選択" />
        <?php endif; ?>
            </div>
    <table class="table table-bordered datatable" >
        <thead>
            <tr class="info">
            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                <th style="text-align: center;">選択</th>
            <?php endif; ?>
                <th style="text-align: center;">社員名</th>
                <th style="text-align: center;">申請日</th>
                <th style="text-align: center;">入社年月</th>
                <th style="text-align: center;">申請区分</th>
                <th style="text-align: center;">休暇区分</th>
                <th style="text-align: center;">申請期間</th>
                <th style="text-align: center;">申請日数(時間)</th>
                <th style="text-align: center;">状態</th>
                <th style="text-align: center;">処理</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($userkyuka_list)) { ?>
            <tr>
                <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                    <td colspan="10" align="center"><?php echo $data_save_no; ?></td>
                <?php else: ?>
                    <td colspan="9" align="center"><?php echo $data_save_no; ?></td>
                <?php endif; ?>
            </tr>
            <?php } elseif (!empty($userkyuka_list)) {
					foreach ($userkyuka_list as $userkyuka) {
					?>
            <tr>
            <?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
                <td><input type="checkbox" class="user-kyuka-select-checkbox" value="<?= $userkyuka['kyukaid'] ?>"
                        data-status-value="<?= $userkyuka['submission_status'] ?>"></td>
            <?php endif; ?>
                <td><span>
                    <a href="#">
                        <span class="showModal">
                            <span class="kyukaReg_class" style="display:none;" >
                                <?= $userkyuka['kyukaid'] . ','  ?>
                            </span>
                            <?= $userkyuka['name']?>
                        </span>
                    </a>
                </span></td>
                <td><span><?= $userkyuka['kyukaymd'] ?></span></td>
                <td><span><?= substr($userkyuka['inymd'], 0, 4) ?>年<?= substr($userkyuka['inymd'], 5, 2) ?>月</span>
                </td>
                <td>
                    <span>
                        <?php
									if ($userkyuka['kyukatype'] == "0") {
										if ($user_kyukatemplate_ == "1") {
											echo "日付(半休）";
										} elseif ($user_kyukatemplate_ == "2") {
											echo "日付";
										}
									} elseif ($userkyuka['kyukatype'] == "1") {
										echo " 時間";
									}
									?>
                    </span>
                </td>
                <td><span><?= $userkyuka['kyukaname'] . $userkyuka['kyukanamedetail'] ?></span></td>
                <td>
                    <span>
                        <?php
									if ($userkyuka['kyukatype'] == "1") {
										echo $userkyuka['strymd'] ?>~<?= $userkyuka['endymd'];
																	} elseif ($userkyuka['kyukatype'] == "0") {
																		echo $userkyuka['strymd'] ?>~<?= $userkyuka['strymd'];
																										}
																											?>
                    </span>
                </td>
                <td>
                    <span>
                        <?php
									if ($userkyuka['kyukatype'] == 0) {
										if ($user_kyukatemplate_ == "1") {
											echo ($userkyuka['ymdcnt']+$userkyuka['timecnt']) . "日";
										} elseif ($user_kyukatemplate_ == "2") {
											echo $userkyuka['timecnt'] . "時間";
										}
                                    }
                                    if($userkyuka['kyukatype'] == 1) {
                                        echo ($userkyuka['ymdcnt']+$userkyuka['timecnt']) . "日";
                                    }
										?>
                    </span>
                </td>
              
                <td>
                    <span name="show-submission-status">
                        <?php echo $KYUKA_SUBMISSTION_STATUS[$userkyuka['submission_status']]?>
                    </span>
                </td>
                <td>
                    <span>
                        <div class="print_btn">
                            <button class="btn btn-default submit-button" style="width: auto;" type="button"
                                data-kyukaid="<?= $userkyuka['kyukaid'] ?>">
                                休暇届印刷
                            </button>
                            <form method="post">
                                <button type="submit" name="Kyukateishutsu" class="btn btn-default" style="width: auto;"
                                    type="button" onclick="return checkTeiShutsuSubmit()">提出</button>
                                <input type="hidden" name="selectedUserKyukaId" value="<?= $userkyuka['kyukaid'] ?>">
                                <input type="hidden" name="selectedUserKyukaEmail" value="<?= $userkyuka['email'] ?>">
                                <input type="hidden" name="selectedUserKyukaSubmissionStatus"
                                    value="<?= $userkyuka['submission_status'] ?>">
                            </form>
                    </span>
                </td>
            </tr>
            <?php }
				} ?>
        </tbody>
    </table>
</div>

<!-- 新規 -->
<div class="row">
    <div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post" novalidate>
                <div class="modal-content">
                    <div class="modal-header">休暇届登録(<span id="sname">New</span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body" style="text-align: left; height: 600px; overflow-y: auto;">
                        <div class="row one">
                            <div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
                                <label for="kyukaymd">申請日</label>
                                <input type="text" class="form-control" name="kyukaymd" style="text-align: center"
                                    value="<?= date('Y/m/d'); ?>" readonly>
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 inymd">
                                <label for="inymd">入社年月</label>
                                <input type="text" class="form-control" name="inymd" style="text-align: center"
                                    value="<?= substr($currentUserInYmd, 0, 4) ?>年<?= substr($currentUserInYmd, 5, 2) ?>月"
                                    readonly>
                            </div>
                            <div class="col-md-6 col-sm-6 col-sx-6 kyukacompanyname">
                                <label for="name">社員名</label>
                                <input type="text" class="form-control" name="name" id="name" style="text-align: center"
                                    value="<?= $_SESSION['auth_name'] ?>" readonly>
                            </div>
                        </div>
                        <br>
                        <div class="row two">
                            <div class="col-md-4 col-sm-4 col-sx-4 kyukatype">
                                <label for="kyukatype">申請区分</label>
                                <div class="custom-control custom-radio">
                                    &nbsp;
                                    <?php if ($user_kyukatemplate_ == "1") : ?>
                                    <input type="radio" name="kyukatype" id="kyukatype" value="0">半休
                                    <?php elseif ($user_kyukatemplate_ == "2") : ?>
                                    <input type="radio" name="kyukatype" id="kyukatype" value="0">時間
                                    <?php endif; ?>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="kyukatype" id="kyukatype" value="1">日付
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-8 vacation">
                                <label for="vacation">年度算定期間</label>
                                <div class="groupinput">
                                    <input type="text" class="form-control" id="vacationstr" name="vacationstr"
                                        placeholder="開始日" required="required" maxlength="10" style="text-align: center;"
                                        value="<?= $startdate_ ?>">
                                    <div class="input-group-addon">~</div>
                                    <input type="text" class="form-control" id="vacationend" name="vacationend"
                                        placeholder="終了日" required="required" maxlength="10" style="text-align: center;"
                                        value="<?= $enddate_ ?>">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row three">
                            <div class="col-md-4 col-sm-4 col-sx-4 kyukacode">
                                <label for="kyukacode">休暇区分</label>
                                <select class="form-control" id="kyukacode" name="kyukacode">
                                    <option value="" disabled selected style="font-size:10px;">選択してください。</option>
                                    <?php foreach ($codebase_list_kyuka as $key) : ?>
                                    <option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-8 col-sm-8 col-sx-8 kyukacode">
                                <label for="kyukacode">休暇区分詳細</label>
                                <textarea class="form-control" id="kyukanamedetail" name="kyukanamedetail"
                                    rows="1"></textarea>
                            </div>
                        </div>
                        <div class="row four">
                            <div class="col-md-3 col-sm-3 col-sx-3 day">
                                <label for="strymd">期間(From)</label>
                                <input type="text" class="form-control" id="strymd" name="strymd" placeholder="日付"
                                    required="required" maxlength="10" style="text-align: center">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 day">
                                <label for="endymd">期間(To)</label>
                                <input type="text" class="form-control" id="endymd" name="endymd" placeholder="日付"
                                    required="required" maxlength="10" style="text-align: center">
                            </div>
                            <div class="divided">
                                <div class="layout">
                                    <div class="col-md-3 col-sm-3 col-sx-3 day">
                                        <label for="strtime">時間(From)</label>
                                        <input type="text" class="form-control" id="strtime" name="strtime"
                                            placeholder="00" required="required" maxlength="2"
                                            style="text-align: center">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-sx-3 day">
                                        <label for="endtime">時間(To)</label>
                                        <input type="text" class="form-control" id="endtime" name="endtime"
                                            placeholder="00" required="required" maxlength="2"
                                            style="text-align: center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row five">
                            <div class="col-md-3 col-sm-3 col-sx-3 ">
                                <label for="tothday">総有給休暇</label>
                                <input type="text" class="form-control" id="tothday" name="tothday" placeholder="番号"
                                    style="text-align: center; background-color: #EEEEEE;" value="<?= $tothday_ ?>">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="oldcnt">前年度の繰越残</label>
                                <input type="text" class="form-control" id="oldcnt" name="oldcnt" placeholder="番号"
                                    style="text-align: center" value="<?= $oldcnt_ ?>">
                            </div>
                            <div class=" col-md-3 col-sm-3 col-sx-3">
                                <label for="newcnt">当該年度付与</label>
                                <input type="text" class="form-control" id="newcnt" name="newcnt" placeholder="番号"
                                    style="text-align: center" value="<?= $newcnt_ ?>">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 no">
                                <label for="usefinishcnt">使用済数</label>
                                <input type="text" class="form-control" id="usefinishcnt" name="usefinishcnt"
                                    placeholder="番号" style="text-align: center" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row six">
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usebeforecnt">使用前残</label>
                                <input type="text" class="form-control" id="usebeforecnt" name="usebeforecnt"
                                    placeholder="番号" style="text-align: center; background-color: #EEEEEE;" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usenowcnt">今回使用</label>
                                <input type="text" class="form-control" id="usenowcnt" name="usenowcnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usefinishaftercnt">使用後済</label>
                                <input type="text" class="form-control" id="usefinishaftercnt" name="usefinishaftercnt"
                                    placeholder="番号" style="text-align: center; background-color: #EEEEEE;" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="useafterremaincnt">使用後残</label>
                                <input type="text" class="form-control" id="useafterremaincnt" name="useafterremaincnt"
                                    placeholder="番号" style="text-align: center; background-color: #EEEEEE;" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row seven">
                            <div class="col-md-8 col-sm-8 col-sx-8">
                                <label for="reason">事由</label>
                                <textarea class="form-control" id="reason" name="reason" rows="2"></textarea>
                            </div>
                            <div class="col-md-2 col-sm-2 col-sx-2">
                                <label for="ymdcnt">申請日数</label>
                                <input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class="col-md-2 col-sm-2 col-sx-2">
                                <label for="timecnt">
                                    <?php if ($user_kyukatemplate_ == "1") : ?>
                                    半休日数
                                    <?php elseif ($user_kyukatemplate_ == "2") : ?>
                                    申請時間
                                    <?php endif; ?>
                                </label>
                                <input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row eight">
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="destcode"></label>
                                <div class="custom-control custom-radio">
                                    &nbsp;&nbsp;
                                    <input type="radio" name="destcode" value="0">日本
                                    <input type="radio" name="destcode" value="1">その他
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="destplace">休暇中居る場所</label>
                                <input type="text" class="form-control" name="destplace" id="destplace" placeholder="国"
                                    required="required" style="text-align: left">
                            </div>
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="desttel">緊急連絡先</label>
                                <input type="text" class="form-control" name="desttel" id="desttel"
                                    placeholder="090xxxxxxxx" required="required" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="modal-footer" style="text-align: center">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="SaveKyuka" class="btn btn-primary btn-ms" id="btnReg"
                                        role="button" value="登録">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <a class="btn btn-success btn-ms" id="btnClear" role="button">クリア</a>
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 編集 -->
<div class="row">
    <div class="modal" id="modal2" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <form method="post" novalidate>
                <div class="modal-content">
                    <div class="modal-header">休暇届編集(<span id="usname"></span>)
                        <button class="close" data-dismiss="modal">x</button>
                    </div>
                    <div class="modal-body" style="text-align: left; height: 600px; overflow-y: auto;">
                        <div class="row one">
                            <div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
                                <label for="kyukaymd">申請日</label>
                                <input type="text" class="form-control" name="udkyukaymd" style="text-align: center"
                                    value="<?= date('Y/m/d'); ?>" readonly>
                                <input type="hidden" name="uduid" id="uduid">
                                <input type="hidden" name="udemail" id="udemail">
                                <input type="hidden" name="udkyukaid" id="udkyukaid">
                                <input type="hidden" name="udvacationid" id="udvacationid">
                                <input type="hidden" name="udallowok" id="udallowok">
                                <input type="hidden" name="udallowid" id="udallowid">
                                <input type="hidden" name="udallowdecide" id="udallowdecide">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 inymd">
                                <label for="inymd">入社年月</label>
                                <input type="text" class="form-control" name="udinymd" style="text-align: center"
                                    value="<?= substr($user_inymd_, 0, 4) ?>年<?= substr($user_inymd_, 5, 2) ?>月"
                                    readonly>  <!-- FIX ME SELECTED USER IN YMD-->
                            </div>
                            <div class="col-md-6 col-sm-6 col-sx-6 kyukacompanyname">
                                <label for="name">社員名</label>
                                <input type="text" class="form-control" name="udname" id="udname"
                                    style="text-align: center" value="<?= $user_name_ ?>" readonly>   <!-- FIX ME SELECTED USER NAME-->
                                 
                            </div>
                        </div>
                        <br>
                        <div class="row two">
                            <div class="col-md-4 col-sm-4 col-sx-4 kyukatype">
                                <label for="kyukatype">申請区分</label>
                                <div class="custom-control custom-radio">
                                    &nbsp;
                                    <?php if ($user_kyukatemplate_ == "1") : ?>
                                    <input type="radio" name="udkyukatype" id="udkyukatype" value="0">半休
                                    <?php elseif ($user_kyukatemplate_ == "2") : ?>
                                    <input type="radio" name="udkyukatype" id="udkyukatype" value="0">時間
                                    <?php endif; ?>
                                    &nbsp;&nbsp;
                                    <input type="radio" name="udkyukatype" id="udkyukatype" value="1">日付
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-8 vacation">
                                <label for="vacation">年度算定期間</label>
                                <div class="groupinput">
                                    <input type="text" class="form-control" id="udvacationstr" name="udvacationstr"
                                        placeholder="開始日" required="required" maxlength="10"
                                        style="text-align: center;">
                                    <div class="input-group-addon">~</div>
                                    <input type="text" class="form-control" id="udvacationend" name="udvacationend"
                                        placeholder="終了日" required="required" maxlength="10"
                                        style="text-align: center;">
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row three">
                            <div class="col-md-4 col-sm-4 col-sx-4 kyukacode">
                                <label for="kyukacode">休暇区分</label>
                                <select class="form-control" id="udkyukacode" name="udkyukacode">
                                    <option value="" disabled selected style="font-size:10px;">選択してください。</option>
                                    <?php foreach ($codebase_list_kyuka as $key) : ?>
                                    <option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-8 col-sm-8 col-sx-8 kyukacode">
                                <label for="kyukacode">休暇区分詳細</label>
                                <textarea class="form-control" id="udkyukanamedetail" name="udkyukanamedetail"
                                    rows="1"></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="row four">
                            <div class="col-md-3 col-sm-3 col-sx-3 day">
                                <label for="strymd">期間(F)</label>
                                <input type="text" class="form-control" id="udstrymd" name="udstrymd" placeholder="日付"
                                    required="required" maxlength="10" style="text-align: center">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 day">
                                <label for="endymd">期間(T)</label>
                                <input type="text" class="form-control" id="udendymd" name="udendymd" placeholder="日付"
                                    required="required" maxlength="10" style="text-align: center">
                            </div>
                            <div class="divided">
                                <div class="layout">
                                    <div class="col-md-3 col-sm-3 col-sx-3 day">
                                        <label for="strtime">時間(F)</label>
                                        <input type="text" class="form-control" id="udstrtime" name="udstrtime"
                                            placeholder="00" required="required" maxlength="2"
                                            style="text-align: center">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-sx-3 day">
                                        <label for="endtime">時間(T)</label>
                                        <input type="text" class="form-control" id="udendtime" name="udendtime"
                                            placeholder="00" required="required" maxlength="2"
                                            style="text-align: center">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row five">
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="tothday">総有給休暇</label>
                                <input type="text" class="form-control" id="udtothday" name="udtothday" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="oldcnt">前年度の繰越残</label>
                                <input type="text" class="form-control" id="udoldcnt" name="udoldcnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class=" col-md-3 col-sm-3 col-sx-3">
                                <label for="newcnt">当該年度付与</label>
                                <input type="text" class="form-control" id="udnewcnt" name="udnewcnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3 no">
                                <label for="usefinishcnt">使用済数</label>
                                <input type="text" class="form-control" id="udusefinishcnt" name="udusefinishcnt"
                                    placeholder="番号" style="text-align: center" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row six">
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usebeforecnt">使用前残</label>
                                <input type="text" class="form-control" id="udusebeforecnt" name="udusebeforecnt"
                                    placeholder="番号" style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usenowcnt">今回使用</label>
                                <input type="text" class="form-control" id="udusenowcnt" name="udusenowcnt"
                                    placeholder="番号" style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="usefinishaftercnt">使用後済</label>
                                <input type="text" class="form-control" id="udusefinishaftercnt"
                                    name="udusefinishaftercnt" placeholder="番号" style="text-align: center" value="">
                            </div>
                            <div class="col-md-3 col-sm-3 col-sx-3">
                                <label for="useafterremaincnt">使用後残</label>
                                <input type="text" class="form-control" id="uduseafterremaincnt"
                                    name="uduseafterremaincnt" placeholder="番号" style="text-align: center" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row seven">
                            <div class="col-md-8 col-sm-8 col-sx-8">
                                <label for="reason">事由</label>
                                <textarea class="form-control" id="udreason" name="udreason" rows="2"></textarea>
                            </div>
                            <div class="col-md-2 col-sm-2 col-sx-2">
                                <label for="ymdcnt">申請日数</label>
                                <input type="text" class="form-control" id="udymdcnt" name="udymdcnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                            <div class="col-md-2 col-sm-2 col-sx-2">
                                <label for="timecnt">
                                    <?php if ($user_kyukatemplate_ == "1") : ?>
                                    半休日数
                                    <?php elseif ($user_kyukatemplate_ == "2") : ?>
                                    申請時間
                                    <?php endif; ?>
                                </label>
                                <input type="text" class="form-control" id="udtimecnt" name="udtimecnt" placeholder="番号"
                                    style="text-align: center" value="">
                            </div>
                        </div>
                        <br>
                        <div class="row eight">
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="destcode"></label>
                                <div class="custom-control custom-radio">
                                    &nbsp;&nbsp;
                                    <input type="radio" name="uddestcode" id="uddestcode" value="0">日本
                                    <input type="radio" name="uddestcode" id="uddestcode" value="1">その他
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="destplace">休暇中居る場所</label>
                                <input type="text" class="form-control" name="uddestplace" id="uddestplace"
                                    placeholder="国" required="required" style="text-align: left">
                            </div>
                            <div class="col-md-4 col-sm-4 col-sx-4 address">
                                <label for="desttel">緊急連絡先</label>
                                <input type="text" class="form-control" name="uddesttel" id="uddesttel"
                                    placeholder="090xxxxxxxx" required="required" style="text-align: left">
                            </div>
                        </div>
                        <br>
                        <div class="modal-footer modal-update-kyuka-btn" style="text-align: center">
                            <div class="col-xs-2"></div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="UpdateKyuka"
                                        class="btn btn-primary" id="btnUpdateKyuka" role="button"
                                        value="登録">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input type="submit" name="DelKyuka" class="btn btn-warning"
                                        id="btnDelKyuka" role="button" value="削除">
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <p class="text-center">
                                    <input class="btn btn-success btn-ms" id="btnClearUpdate"
                                        role="button" value="クリア" />
                                </p>
                            </div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="modal-footer show-div" style="text-align: center">
                            <div class="col-xs-5"></div>
                            <div class="col-xs-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                    id="modalClose">閉じる</button>
                            </div>
                            <div class="col-xs-5"></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PDF印刷 product -->
<form id="autopdf" action="../pdfdownload/generatekyukapdf.php" method="post" target="_blank">
    <input type="hidden" name="kyukaymd" id="kyukaymd-input">
    <input type="hidden" name="name" id="name-input">
    <input type="hidden" name="dept" id="dept-input">
    <input type="hidden" name="signstamp" id="signstamp-input">
    <input type="hidden" name="kyukatype" id="kyukatype-input">
    <input type="hidden" name="strymd" id="strymd-input">
    <input type="hidden" name="endymd" id="endymd-input">
    <input type="hidden" name="strtime" id="strtime-input">
    <input type="hidden" name="endtime" id="endtime-input">
    <input type="hidden" name="ymdcnt" id="ymdcnt-input">
    <input type="hidden" name="timecnt" id="timecnt-input">
    <input type="hidden" name="kyukaname" id="kyukaname-input">
    <input type="hidden" name="kyukanamedetail" id="kyukanamedetail-input">
    <input type="hidden" name="inymd" id="inymd-input">
    <input type="hidden" name="kyukatemplate" id="kyukatemplate-input">
    <input type="hidden" name="vacationstr" id="vacationstr-input">
    <input type="hidden" name="vacationend" id="vacationend-input">
    <input type="hidden" name="tothday" id="tothday-input">
    <input type="hidden" name="newcnt" id="newcnt-input">
    <input type="hidden" name="oldcnt" id="oldcnt-input">
    <input type="hidden" name="usefinishcnt" id="usefinishcnt-input">
    <input type="hidden" name="usebeforecnt" id="usebeforecnt-input">
    <input type="hidden" name="usenowcnt" id="usenowcnt-input">
    <input type="hidden" name="usefinishaftercnt" id="usefinishaftercnt-input">
    <input type="hidden" name="useafterremaincnt" id="useafterremaincnt-input">
    <input type="hidden" name="reason" id="reason-input">
    <input type="hidden" name="destplace" id="destplace-input">
    <input type="hidden" name="desttel" id="desttel-input">

    <input type="hidden" name="monthcount" value="<?php echo htmlspecialchars($lastTtopMax); ?>">
    <input type="hidden" name="startdate" value="<?php echo htmlspecialchars($startdate_); ?>">
    <input type="hidden" name="enddate" value="<?php echo htmlspecialchars($enddate_); ?>">

    <input type="hidden" name="noticetitle" value="<?php echo htmlspecialchars($kyuka_notice_title); ?>">
    <input type="hidden" name="noticesubtitle" value="<?php echo htmlspecialchars($kyuka_notice_subtitle); ?>">
    <input type="hidden" name="noticemessage" value="<?php echo htmlspecialchars($kyuka_notice_message); ?>">

    <input type="hidden" name="infotitletop" value="<?php echo htmlspecialchars($kyukainfo_titletop); ?>">
    <input type="hidden" name="infotitlebottom" value="<?php echo htmlspecialchars($kyukainfo_titlebottom); ?>">
    <input type="hidden" name="kyukaInfoListtopString" value="<?php echo htmlspecialchars($kyukaInfoListtopString); ?>">
    <input type="hidden" name="kyukaInfoListbottomString"
        value="<?php echo htmlspecialchars($kyukaInfoListbottomString); ?>">

    <input type="hidden" name="signstamp_sekinin" id="signstamp_sekinin-input">
    <input type="hidden" name="signstamp_tanto" id="signstamp_tanto-input">
    <input type="hidden" name="signstamp_user" id="signstamp_user-input">

    <!-- $user_stamp -->
</form>

<!-- お知らせ -->
<div class="row">
    <div class="modal" id="modal3" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header"><span class="popup-title"
                        id="form_title"><strong><?= trim($kyuka_notice_list[0]['title']) ?></strong></span>
                    <button class="close" data-dismiss="modal">x</button>
                </div>
                <div class="modal-body" style="text-align: left">
                    <div class="row">
                        <div class="col-md-12 col-ms-12">
                            <textarea class="alert alert-warning" readonly id="message-area2" name="message-area2"
                                style="width:100%; overflow: hidden; resize: none;" style="width: 100%; overflow:auto;"
                                oninput="autoGrow(this)"
                                rows="4"><?= trim($kyuka_notice_list[0]['message']) ?></textarea>
                        </div>
                        <div class="col-md-12 col-ms-12 sub-middle">
                            <div class="alert alert-info" style="margin-bottom: 10px;">
                                <strong><?= trim($kyuka_notice_list[0]['subtitle']) ?></strong>
                            </div>
                        </div>
                        <div class="col-md-12 col-ms-12">
                            <table class="table table-bordered datatable"
                                style="margin-top: -80px; white-space: normal;">
                                <?php
									$elementsPerTable = 8;
									for ($i = 0; $i < count($kyukaInfoListtop); $i += $elementsPerTable) {
										echo '<tr>';
										echo '<th class="info" style="width:20%; text-align: center; color: #31708f;">' . trim($kiukaInfoList[0]['titletop']) . '</th>';
										for ($j = $i; $j < $i + $elementsPerTable && $j < count($kyukaInfoListtop); $j++) {
											echo '<td name="data-row-' . $i . '" class="table-notic" style="width:10%; text-align: center;">' . $kyukaInfoListtop[$j] . '</td>';
										}
										echo '</tr>';
										echo '<br>';
										echo '<tr>';
										echo '<th class="info" style="width:20%; text-align: center; color: #31708f;">' . trim($kiukaInfoList[0]['titlebottom']) . '</th>';
										for ($j = $i; $j < $i + $elementsPerTable && $j < count($kyukaInfoListbottom); $j++) {
											echo '<td name="data-row-' . $i . '" class="table-notic" style="width:10%; text-align: center;">' . $kyukaInfoListbottom[$j] . '</td>';
										}
										echo '</tr>';
										echo '<br>';
									}
									?>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer" style="padding-bottom: 5px;">
                        <div class="col-md text-center">
                            <a class="btn btn-default btn-md" data-dismiss="modal" role="button">閉じる </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// New button(新規)
$(document).on('click', '#btnNew', function(e) {
    $('#modal').modal('toggle');
    // In the case of a new application, it cannot be used until the application category is selected.
    $("#strymd").val("").prop('disabled', true);
    $("#endymd").val("").prop('disabled', true);

    // In the case of a new application, it cannot be used until the application category is selected.
    $("#strtime").val("").prop('disabled', true);
    $("#endtime").val("").prop('disabled', true);
});

//自動計算
$("#oldcnt, #newcnt, #tothday, #usefinishcnt, #usenowcnt").on("input", function() {
    // ①総有給休暇数, ②＋③＝①
    var oldcntValue = parseFloat($("#oldcnt").val()) || 0;
    var newcntValue = parseFloat($("#newcnt").val()) || 0;
    var totaly = oldcntValue + newcntValue;
    $("#tothday").val(totaly);

    //⑤使用前残, ⑤＝①ー④
    var tothdayValue = parseFloat($("#tothday").val()) || 0;
    var usefinishcntValue = parseFloat($("#usefinishcnt").val()) || 0;
    var usebeforecntValue = tothdayValue - usefinishcntValue;
    $("#usebeforecnt").val(usebeforecntValue);

    // ⑦使用後済数(④＋⑥)
    var usefinishcntValue = parseFloat($("#usefinishcnt").val()) || 0;
    var usenowcntValue = parseFloat($("#usenowcnt").val()) || 0;
    var totaly = usefinishcntValue + usenowcntValue;
    $("#usefinishaftercnt").val(totaly);

    // ⑧使用後残日数(⑤－⑥)
    var usebeforecntValue = parseFloat($("#usebeforecnt").val()) || 0;
    var usenowcntValue = parseFloat($("#usenowcnt").val()) || 0;
    var suby = usebeforecntValue - usenowcntValue;
    $("#useafterremaincnt").val(suby);
});

// Lock and unlock items when selecting vacation request type (day/hour)
$('input[type=radio][name=kyukatype]').change(function() {
    if (this.value == '1') {
        // Select day
        $("#strymd").prop('disabled', false);
        $("#endymd").prop('disabled', false);
        $("#strtime").prop('disabled', true);
        $("#endtime").prop('disabled', true);
        $("#timecnt").val(0);
        $("#timecnt").prop('disabled', true);
        $("#ymdcnt").prop('disabled', false);
    } else if (this.value == '0') {
        // Time selection
        $("#strymd").prop('disabled', false);
        $("#endymd").prop('disabled', true);
        $("#strtime").prop('disabled', false);
        $("#endtime").prop('disabled', false);
        $("#ymdcnt").val(0);
        $("#ymdcnt").prop('disabled', true);
        $("#timecnt").prop('disabled', false);
    }
});

// Contact while on vacation
$('input[type=radio][name=destcode]').change(function() {
    if (this.value == '0') {
        // Japan
        $("#destplace").val("日本").prop('readonly', true);
    } else {
        // Other
        $("#destplace").val("").prop('readonly', false);
    }
});

// Calculation of vacation days when vacation days (str) change
$("#strymd").change(function() {
    var str = new Date($("#strymd").val());
    var end = new Date($("#endymd").val());

    if (!end || str > end) {
        $("#endymd").val($("#strymd").val());
        end = new Date($("#endymd").val());
    }

    // If hours are selected, the number of days is set to 0.
    if ($("input[name='kyukatype']:checked").val() == "0") {
        $("#ymdcnt").val("0");
        $("#endymd").val($("#strymd").val());
        return;
    }

    var dateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
    $("#ymdcnt").val(dateDiff + 1);
});

// Calculation of vacation days when vacation days (end) change
$("#endymd").change(function() {
    var str = new Date($("#strymd").val());
    var end = new Date($("#endymd").val());

    if (str > end) {
        $("#endymd").val($("#strymd").val());
        end = new Date($("#endymd").val());
    }

    var dateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
    $("#ymdcnt").val(dateDiff + 1);
});

$(document).ready(function() {
    $('input[name="kyukatype"]').change(function() {
        if ($(this).val() == "0" && <?php echo $user_kyukatemplate_; ?> == "1") {
            $('#timecnt').val('0.5');
        }

        if ($(this).val() == "0" && <?php echo $user_kyukatemplate_; ?> == "2") {
            $('#strtime, #endtime').on('input', function() {
                var strtimeValue = $('#strtime').val() || '0';
                var endtimeValue = $('#endtime').val() || '0';
                $('#timecnt').val(endtimeValue - strtimeValue);
            });
        }
    });

    $('input[name="udkyukatype"]').change(function() {
        if ($(this).val() == "0" && <?php echo $user_kyukatemplate_; ?> == "1") {
            $('#udtimecnt').val('0.5');
        }

        if ($(this).val() == "0" && <?php echo $user_kyukatemplate_; ?> == "2") {
            $('#udstrtime, #udendtime').on('input', function() {
                var strtimeValue = $('#udstrtime').val() || '0';
                var endtimeValue = $('#udendtime').val() || '0';
                $('#udtimecnt').val(endtimeValue - strtimeValue);
            });
        }
    });
    SetFormViewBySubmissionStatusHandler();
    multiUserKyukaSelectHandler();
});

// Datepeeker Calender
$("#strymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#endymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#vacationstr").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#vacationend").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$(document).on('click', '#btnAnnt', function(e) {
    $('#modal3').modal('toggle');
    autoGrow(document.getElementById("message-area2"));
});

// Check Empty 登録
$(document).on('click', '#btnReg', function(e) {
    var kyukatype = $("input[name='kyukatype']:checked").val();
    var kyukacode = $("#kyukacode").val();
    var vacationstr = $("#vacationstr").val();
    var vacationend = $("#vacationend").val();
    var strymd = $("#strymd").val();
    var endymd = $("#endymd").val();
    var strtime = $("#strtime").val() * 1;
    var endtime = $("#endtime").val() * 1;
    var tothday = $("#tothday").val() * 1;
    var oldcnt = $("#oldcnt").val() * 1;
    var newcnt = $("#newcnt").val() * 1;
    var usefinishcnt = $("#usefinishcnt").val() * 1;
    var usebeforecnt = $("#usebeforecnt").val() * 1;
    var usenowcnt = $("#usenowcnt").val() * 1;
    var usefinishaftercnt = $("#usefinishaftercnt").val() * 1;
    var useafterremaincnt = $("#useafterremaincnt").val() * 1;
    var reason = $("#reason").val();
    var destplace = $("#destplace").val();
    var destcode = $("input[name='destcode']:checked").val();
    var desttel = $("#desttel").val();
    var user_kyukatemplate_ = "<?php echo $user_kyukatemplate_; ?>";

    if (kyukatype != "0" && kyukatype != "1") {
        alert("<?php echo $kyuka_type_select; ?>");
        $("#kyukatype").focus();
        return false;
    }

    if (!kyukacode) {
        alert("<?php echo $kyuka_name_select; ?>");
        $("#kyukacode").focus();
        return false;
    }

    if (vacationstr == "") {
        alert("<?php echo $kyuka_vacation_empty; ?>");
        $("#vacationstr").focus();
        return false;
    }

    if (vacationend == "") {
        alert("<?php echo $kyuka_vacation_empty; ?>");
        $("#vacationend").focus();
        return false;
    }

    if (strymd == "") {
        alert("<?php echo $kyuka_strymd_empty; ?>");
        $("#strymd").focus();
        return false;
    }

    if (kyukatype == "1" && endymd == "") {
        alert("<?php echo $kyuka_endymd_empty; ?>");
        $("#endymd").focus();
        return false;
    }

    if (kyukatype == "0" && (strtime == "" || strtime == "0")) {
        alert("<?php echo $kyuka_strtime_empty; ?>");
        $("#strtime").focus();
        return false;
    }

    if (kyukatype == "0" && (endtime == "" || endtime == "0")) {
        alert("<?php echo $kyuka_endtime_empty; ?>");
        $("#endtime").focus();
        return false;
    }

    if (tothday == "") {
        alert("<?php echo $kyuka_tothday_empty; ?>");
        $("#tothday").focus();
        return false;
    }

    if (oldcnt === "") {
        alert("<?php echo $kyuka_oldcnt_empty; ?>");
        $("#oldcnt").focus();
        return false;
    }

    if (newcnt == "") {
        alert("<?php echo $kyuka_newcnt_empty; ?>");
        $("#newcnt").focus();
        return false;
    }

    if (usefinishcnt === "") {
        alert("<?php echo $kyuka_usefinishcnt_empty; ?>");
        $("#usefinishcnt").focus();
        return false;
    }
    if (usefinishcnt >= tothday) {
        alert("<?php echo $kyuka_usefinishcnt_edit; ?>");
        $("#usefinishcnt").focus();
        return false;
    }

    if (usebeforecnt == "") {
        alert("<?php echo $kyuka_usebeforecnt_empty; ?>");
        $("#usebeforecnt").focus();
        return false;
    }

    if (usenowcnt == "") {
        alert("<?php echo $kyuka_usenowcnt_empty; ?>");
        $("#usenowcnt").focus();
        return false;
    }
    if (usenowcnt > usebeforecnt) {
        alert("<?php echo $kyuka_usenowcnt_edit; ?>");
        $("#usenowcnt").focus();
        return false;
    }

    if (usefinishaftercnt == "") {
        alert("<?php echo $kyuka_usefinishaftercnt_empty; ?>");
        $("#usefinishaftercnt").focus();
        return false;
    }

    if (useafterremaincnt == "") {
        alert("<?php echo $kyuka_useafterremaincnt_empty; ?>");
        $("#useafterremaincnt").focus();
        return false;
    }

    if (reason == "") {
        alert("<?php echo $kyuka_reason_empty; ?>");
        $("#reason").focus();
        return false;
    }

    if (destcode != "0" && destcode != "1" && destcode != "2") {
        alert("<?php echo $kyuka_destcode_select; ?>");
        $("#destcode").focus();
        return false;
    }

    if (destplace == "") {
        alert("<?php echo $kyuka_destplace_empty; ?>");
        $("#destplace").focus();
        return false;
    }

    if (desttel == "") {
        alert("<?php echo $kyuka_desttel_empty; ?>");
        $("#desttel").focus();
        return false;
    }
});

// Clear Input Tag Data
$(document).on('click', '#btnClear', function(e) {
    $('#kyukaname').val('');
    $('#kyukanamedetail').val('');
    $("#kyukacode").val('');
    $("input[name='kyukatype']").prop('checked', false);
    $('#strymd').val('');
    $('#endymd').val('');
    $('#strtime').val('');
    $('#endtime').val('');
    $('#tothday').val('');
    $('#oldcnt').val('');
    $('#newcnt').val('');
    $('#usefinishcnt').val('');
    $('#usebeforecnt').val('');
    $('#usenowcnt').val('');
    $('#usefinishaftercnt').val('');
    $('#useafterremaincnt').val('');
    $('#reason').val('');
    $('#destplace').val('');
    $("input[name='destcode']").prop('checked', false);
    $('#destplace').val('');
    $('#desttel').val('');
    $('#ymdcnt').val('0');
    $('#timecnt').val('0');
});


// 編集
// ①総有給休暇数, ②＋③＝①
$("#udoldcnt, #udnewcnt").on("input", function() {
    var udoldcntValue = parseFloat($("#udoldcnt").val()) || 0;
    var udnewcntValue = parseFloat($("#udnewcnt").val()) || 0;
    var udtotaly = udoldcntValue + udnewcntValue;
    $("#udtothday").val(udtotaly);
});

// ⑦使用後済数(④＋⑥)
$("#udusefinishcnt, #udusenowcnt").on("input", function() {
    var udusefinishcntValue = parseFloat($("#udusefinishcnt").val()) || 0;
    var udusenowcntValue = parseFloat($("#udusenowcnt").val()) || 0;
    var udtotaly = udusefinishcntValue + udusenowcntValue;
    $("#udusefinishaftercnt").val(udtotaly);
});

// ⑧使用後残日数(⑤－⑥)
$("#udusebeforecnt, #udusenowcnt").on("input", function() {
    var udusebeforecntValue = parseFloat($("#udusebeforecnt").val()) || 0;
    var udusenowcntValue = parseFloat($("#udusenowcnt").val()) || 0;
    var udsuby = udusebeforecntValue - udusenowcntValue;
    $("#uduseafterremaincnt").val(udsuby);
});

// Lock and unlock items when selecting vacation request type (day/hour)
$('input[type=radio][name=udkyukatype]').change(function() {
    if (this.value == '1') {
        // Select day
        $("#udstrymd").prop('disabled', false);
        $("#udendymd").prop('disabled', false);
        $("#udstrtime").prop('disabled', true);
        $("#udendtime").prop('disabled', true);
        $("#udtimecnt").val(0);
        $("#udtimecnt").prop('disabled', true);
        $("#udymdcnt").prop('disabled', false);
    } else if (this.value == '0') {
        // Time selection
        $("#udstrymd").prop('disabled', false);
        $("#udendymd").prop('disabled', true);
        $("#udstrtime").prop('disabled', false);
        $("#udendtime").prop('disabled', false);
        $("#udymdcnt").val(0);
        $("#udymdcnt").prop('disabled', true);
        $("#udtimecnt").prop('disabled', false);
    }
});

// Contact while on vacation
$('input[type=radio][name=uddestcode]').change(function() {
    if (this.value == '0') {
        // Japan
        $("#uddestplace").val("日本").prop('readonly', true);
    } else {
        // Other
        $("#uddestplace").val("").prop('readonly', false);
    }
});

// Calculation of vacation days when vacation days (str) change
$("#udstrymd").change(function() {
    var str = new Date($("#udstrymd").val());
    var end = new Date($("#udendymd").val());

    if (!end || str > end) {
        $("#udendymd").val($("#udstrymd").val());
        end = new Date($("#udendymd").val());
    }

    // If hours are selected, the number of days is set to 0.
    if ($("input[name='udkyukatype']:checked").val() == "0") {
        $("#udendymd").val($("#udstrymd").val());
        return;
    }

    var uddateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
    $("#udymdcnt").val(uddateDiff + 1);
});

// Calculation of vacation days when vacation days (end) change
$("#udendymd").change(function() {
    var str = new Date($("#udstrymd").val());
    var end = new Date($("#udendymd").val());

    if (str > end) {
        $("#udendymd").val($("#udstrymd").val());
        end = new Date($("#udendymd").val());
    }

    var uddateDiff = Math.ceil((end.getTime() - str.getTime()) / (1000 * 3600 * 24));
    $("#udymdcnt").val(uddateDiff + 1);
});

// Datepeeker Calender
$("#udstrymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#udendymd").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#udvacationstr").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

$("#udvacationend").datepicker({
    changeYear: true,
    dateFormat: 'yy/mm/dd'
});

// Check Empty 編集
$(document).on('click', '#btnUpdateKyuka', function(e) {
    var kyukatype = $("input[name='udkyukatype']:checked").val();
    var kyukacode = $("#udkyukacode").val();
    var vacationstr = $("#udvacationstr").val();
    var vacationend = $("#udvacationend").val();
    var strymd = $("#udstrymd").val();
    var endymd = $("#udendymd").val();
    var strtime = $("#udstrtime").val() * 1;
    var endtime = $("#udendtime").val() * 1;
    var tothday = $("#udtothday").val() * 1;
    var oldcnt = $("#udoldcnt").val() * 1;
    var newcnt = $("#udnewcnt").val() * 1;
    var usefinishcnt = $("#udusefinishcnt").val() * 1;
    var usebeforecnt = $("#udusebeforecnt").val() * 1;
    var usenowcnt = $("#udusenowcnt").val() * 1;
    var usefinishaftercnt = $("#udusefinishaftercnt").val() * 1;
    var useafterremaincnt = $("#uduseafterremaincnt").val() * 1;
    var reason = $("#udreason").val();
    var destplace = $("#uddestplace").val();
    var destcode = $("input[name='uddestcode']:checked").val();
    var desttel = $("#uddesttel").val();
    var user_kyukatemplate_ = "<?php echo $user_kyukatemplate_; ?>";

    if (kyukatype != "0" && kyukatype != "1") {
        alert("<?php echo $kyuka_type_select; ?>");
        $("#udkyukatype").focus();
        return false;
    }

    if (!kyukacode) {
        alert("<?php echo $kyuka_name_select; ?>");
        $("#udkyukacode").focus();
        return false;
    }

    if (vacationstr == "") {
        alert("<?php echo $kyuka_vacation_empty; ?>");
        $("#udvacationstr").focus();
        return false;
    }

    if (vacationend == "") {
        alert("<?php echo $kyuka_vacation_empty; ?>");
        $("#udvacationend").focus();
        return false;
    }

    if (strymd == "") {
        alert("<?php echo $kyuka_strymd_empty; ?>");
        $("#udstrymd").focus();
        return false;
    }

    if (kyukatype == "1" && endymd == "") {
        alert("<?php echo $kyuka_endymd_empty; ?>");
        $("#udendymd").focus();
        return false;
    }

    if (kyukatype == "0" && (strtime == "" || strtime == "0")) {
        alert("<?php echo $kyuka_strtime_empty; ?>");
        $("#udstrtime").focus();
        return false;
    }

    if (kyukatype == "0" && (endtime == "" || endtime == "0")) {
        alert("<?php echo $kyuka_endtime_empty; ?>");
        $("#udendtime").focus();
        return false;
    }

    if (tothday == "") {
        alert("<?php echo $kyuka_tothday_empty; ?>");
        $("#udtothday").focus();
        return false;
    }

    if (oldcnt === "") {
        alert("<?php echo $kyuka_oldcnt_empty; ?>");
        $("#udoldcnt").focus();
        return false;
    }

    if (newcnt == "") {
        alert("<?php echo $kyuka_newcnt_empty; ?>");
        $("#udnewcnt").focus();
        return false;
    }

    if (usefinishcnt === "") {
        alert("<?php echo $kyuka_usefinishcnt_empty; ?>");
        $("#udusefinishcnt").focus();
        return false;
    }

    if (usebeforecnt == "") {
        alert("<?php echo $kyuka_usebeforecnt_empty; ?>");
        $("#udusebeforecnt").focus();
        return false;
    }

    if (usenowcnt == "") {
        alert("<?php echo $kyuka_usenowcnt_empty; ?>");
        $("#udusenowcnt").focus();
        return false;
    }

    if (usefinishaftercnt == "") {
        alert("<?php echo $kyuka_usefinishaftercnt_empty; ?>");
        $("#udusefinishaftercnt").focus();
        return false;
    }

    if (useafterremaincnt == "") {
        alert("<?php echo $kyuka_useafterremaincnt_empty; ?>");
        $("#uduseafterremaincnt").focus();
        return false;
    }

    if (reason == "") {
        alert("<?php echo $kyuka_reason_empty; ?>");
        $("#udreason").focus();
        return false;
    }

    if (destcode != "0" && destcode != "1" && destcode != "2") {
        alert("<?php echo $kyuka_destcode_select; ?>");
        $("#uddestcode").focus();
        return false;
    }

    if (destplace == "") {
        alert("<?php echo $kyuka_destplace_empty; ?>");
        $("#uddestplace").focus();
        return false;
    }

    if (desttel == "") {
        alert("<?php echo $kyuka_desttel_empty; ?>");
        $("#uddesttel").focus();
        return false;
    }
});

// Clear Input Tag Data
$(document).on('click', '#btnClearUpdate', function(e) {
    $('#udkyukaname').val('');
    $('#udkyukanamedetail').val('');
    $("#udkyukacode").val('');
    $("input[name='udkyukatype']").prop('checked', false);
    $('#udstrymd').val('');
    $('#udendymd').val('');
    $('#udstrtime').val('');
    $('#udendtime').val('');
    $('#udtothday').val('');
    $('#udoldcnt').val('');
    $('#udnewcnt').val('');
    $('#udusefinishcnt').val('');
    $('#udusebeforecnt').val('');
    $('#udusenowcnt').val('');
    $('#udusefinishaftercnt').val('');
    $('#uduseafterremaincnt').val('');
    $('#udreason').val('');
    $('#uddestplace').val('');
    $("input[name='uddestcode']").prop('checked', false);
    $('#uddestplace').val('');
    $('#uddesttel').val('');
    $('#udymdcnt').val('0');
    $('#udtimecnt').val('0');
});

// 編集
$(document).on('click', '.showModal', function() {
    setOnModalChangeDataButtonHidden(true);
    $('#modal2').modal('toggle');
    var ArrayData = $(this).text().trim();
    var SeparateArr = ArrayData.split(',');
    var Kyukaid = SeparateArr[0];
    var Name = SeparateArr[1].trim();
    <?php
		foreach ($userkyuka_list as $key) {
		?>
    if ('<?php echo $key['kyukaid'] ?>' === Kyukaid && '<?php echo $key['name'] ?>' === Name) {
        isAdmin = false;
        isSubmised = false;
        var isAdmin =
            <?php echo ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) ? 'true' : 'false'; ?>;
        var isSubmised =
            <?php echo ($key['submission_status'] != array_search(0, $KYUKA_SUBMISSTION_STATUS)) ? 'true' : 'false'; ?>;
        if (!isAdmin && isSubmised) {
            setOnModalChangeDataButtonHidden(false);
        }
        if (isAdmin) {
            setOnModalChangeDataButtonHidden(true);
        }

        $("#usname").text('<?php echo $key['name'] ?>');
        $("#udkyukaymd").text($('[name="udkyukaymd"]').val("<?php echo $key['kyukaymd'] ?>"));
        $("#udinymd").text($('[name="udinymd"]').val("<?php echo $key['inymd'] ?>"));
        $("#udname").text($('[name="udname"]').val("<?php echo $key['name'] ?>"));
        $("#uduid").text($('[name="uduid"]').val("<?php echo $key['uid'] ?>"));
        $("#udemail").text($('[name="udemail"]').val("<?php echo $key['email'] ?>"));
        $("#udkyukaid").text($('[name="udkyukaid"]').val("<?php echo $key['kyukaid'] ?>"));
        $("#udvacationid").text($('[name="udvacationid"]').val("<?php echo $key['vacationid'] ?>"));
        $("#udallowok").text($('[name="udallowok"]').val("<?php echo $key['allowok'] ?>"));
        $("#udallowid").text($('[name="udallowid"]').val("<?php echo $key['allowid'] ?>"));
        $("#udallowdecide").text($('[name="udallowdecide"]').val("<?php echo $key['allowdecide'] ?>"));
        $("input[name='udkyukatype'][value='<?php echo $key['kyukatype']; ?>']").prop('checked', true);
        var decide_readOnly = '<?php echo $key['kyukatype']; ?>';
        if (decide_readOnly === "0") {
            $("#udendymd").prop('disabled', true);
            $("#udymdcnt").prop('disabled', true);
            $("#udstrtime").prop('disabled', false);
            $("#udendtime").prop('disabled', false);
        } else if (decide_readOnly === "1") {
            $("#udstrtime").prop('disabled', true);
            $("#udendtime").prop('disabled', true);
            $("#udtimecnt").prop('disabled', true);
        }

        $("select[name='udkyukacode']").val('<?php echo $key['kyukacode']; ?>');
        $("#udkyukanamedetail").text($('[name="udkyukanamedetail"]').val(
            "<?php echo $key['kyukanamedetail'] ?>"));
        $("#udvacationstr").text($('[name="udvacationstr"]').val("<?php echo $key['vacationstr'] ?>"));
        $("#udvacationend").text($('[name="udvacationend"]').val("<?php echo $key['vacationend'] ?>"));
        $("#udstrymd").text($('[name="udstrymd"]').val("<?php echo $key['strymd'] ?>"));
        $("#udendymd").text($('[name="udendymd"]').val("<?php echo $key['endymd'] ?>"));
        $("#udstrtime").text($('[name="udstrtime"]').val("<?php echo $key['strtime'] ?>"));
        $("#udendtime").text($('[name="udendtime"]').val("<?php echo $key['endtime'] ?>"));
        $("#udtothday").text($('[name="udtothday"]').val("<?php echo $key['tothday'] ?>"));
        $("#udoldcnt").text($('[name="udoldcnt"]').val("<?php echo $key['oldcnt'] ?>"));
        $("#udnewcnt").text($('[name="udnewcnt"]').val("<?php echo $key['newcnt'] ?>"));
        $("#udusefinishcnt").text($('[name="udusefinishcnt"]').val("<?php echo $key['usefinishcnt'] ?>"));
        $("#udusebeforecnt").text($('[name="udusebeforecnt"]').val("<?php echo $key['usebeforecnt'] ?>"));
        $("#udusenowcnt").text($('[name="udusenowcnt"]').val("<?php echo $key['usenowcnt'] ?>"));
        $("#udusefinishaftercnt").text($('[name="udusefinishaftercnt"]').val(
            "<?php echo $key['usefinishaftercnt'] ?>"));
        $("#uduseafterremaincnt").text($('[name="uduseafterremaincnt"]').val(
            "<?php echo $key['useafterremaincnt'] ?>"));
        $("#udreason").text($('[name="udreason"]').val("<?php echo $key['reason'] ?>"));
        $("#udymdcnt").text($('[name="udymdcnt"]').val("<?php echo $key['ymdcnt'] ?>"));
        $("#udtimecnt").text($('[name="udtimecnt"]').val("<?php echo $key['timecnt'] ?>"));
        $("input[name='uddestcode'][value='<?php echo $key['destcode']; ?>']").prop('checked', true);
        $("#uddestplace").text($('[name="uddestplace"]').val("<?php echo $key['destplace'] ?>"));
        $("#uddesttel").text($('[name="uddesttel"]').val("<?php echo $key['desttel'] ?>"));
    }
    <?php
		}
		?>
});

// Click (modify) employee ID in the grid: popup & display contents
$(document).on('click', '.showModal2', function() {
    $('#modal4').modal('toggle');
    var ArrayData = $(this).text();
    var SeparateArr = ArrayData.split(',');
    var Uid = SeparateArr[1];
    var Ymdcnt = SeparateArr[2];
    var Timecnt = SeparateArr[3];
    $("#duid").text($('[name="duid"]').val(Uid));
    $("#allowok").text($('[name="allowok"]').val());
    $("input[type='radio'].decide_allowok:checked").val();
    var newymdcnt = $("input[name=newymdcnt]:hidden");
    newymdcnt.val(Ymdcnt);
    var newymdcnt = newymdcnt.val();
    var newtimecnt = $("input[name=newtimecnt]:hidden");
    newtimecnt.val(Timecnt);
    var newtimecnt = newtimecnt.val();
    <?php
		if (!empty($userkyuka_list)) {
			foreach ($userkyuka_list as $key) {
		?>
    if ('<?php echo $key['uid'] ?>' == Uid) {
        var oldusecnt = $("input[name=oldusecnt]:hidden");
        oldusecnt.val("<?php echo $key['usecnt'] ?>");
        var oldusecnt = oldusecnt.val();
        var oldusetime = $("input[name=oldusetime]:hidden");
        oldusetime.val("<?php echo $key['usetime'] ?>");
        var oldusetime = oldusetime.val();
        var oldrestcnt = $("input[name=oldrestcnt]:hidden");
        oldrestcnt.val("<?php echo $key['restcnt'] ?>");
        var oldrestcnt = oldrestcnt.val();
    }
    <?php
			}
		}
		?>
});

// Submit for 休暇印刷
$(".submit-button").click(function(event) {
    event.preventDefault();
    var kyukaidValue = $(this).data("kyukaid");
    <?php
		$codebaseListJson = json_encode($codebase_list);
		if (!empty($userkyuka_list)) {
			foreach ($userkyuka_list as $key) {
		?>
    if ('<?php echo $key['kyukaid'] ?>' == kyukaidValue) {
        $("#autopdf #kyukaymd-input").val("<?php echo htmlspecialchars($key['kyukaymd']); ?>");
        $("#autopdf #name-input").val("<?php echo htmlspecialchars($key['name']); ?>");
        var codebaseList = <?php echo $codebaseListJson; ?>;
        var kyukaidValue = '<?php echo $key['dept']; ?>';
        var deptname = '';
        for (var i = 0; i < codebaseList.length; i++) {
            if (codebaseList[i]['code'] === kyukaidValue) {
                deptname = codebaseList[i]['name'];
                break;
            }
        }
        $("#autopdf #dept-input").val(deptname);
        $("#autopdf #signstamp-input").val("<?php echo htmlspecialchars($key['signstamp']); ?>");
        $("#autopdf #kyukatype-input").val("<?php echo htmlspecialchars($key['kyukatype']); ?>");
        $("#autopdf #strymd-input").val("<?php echo htmlspecialchars($key['strymd']); ?>");
        $("#autopdf #endymd-input").val("<?php echo htmlspecialchars($key['endymd']); ?>");
        $("#autopdf #strtime-input").val("<?php echo htmlspecialchars($key['strtime']); ?>");
        $("#autopdf #endtime-input").val("<?php echo htmlspecialchars($key['endtime']); ?>");
        $("#autopdf #ymdcnt-input").val("<?php echo htmlspecialchars($key['ymdcnt']); ?>");
        $("#autopdf #timecnt-input").val("<?php echo htmlspecialchars($key['timecnt']); ?>");
        $("#autopdf #kyukaname-input").val("<?php echo htmlspecialchars($key['kyukaname']); ?>");
        $("#autopdf #kyukanamedetail-input").val("<?php echo htmlspecialchars($key['kyukanamedetail']); ?>");
        $("#autopdf #inymd-input").val("<?php echo htmlspecialchars($key['inymd']); ?>");
        $("#autopdf #kyukatemplate-input").val("<?php echo htmlspecialchars($key['kyukatemplate']); ?>");
        $("#autopdf #vacationstr-input").val("<?php echo htmlspecialchars($key['vacationstr']); ?>");
        $("#autopdf #vacationend-input").val("<?php echo htmlspecialchars($key['vacationend']); ?>");
        $("#autopdf #tothday-input").val("<?php echo htmlspecialchars($key['tothday']); ?>");
        $("#autopdf #oldcnt-input").val("<?php echo htmlspecialchars($key['oldcnt']); ?>");
        $("#autopdf #newcnt-input").val("<?php echo htmlspecialchars($key['newcnt']); ?>");
        $("#autopdf #usefinishcnt-input").val("<?php echo htmlspecialchars($key['usefinishcnt']); ?>");
        $("#autopdf #usebeforecnt-input").val("<?php echo htmlspecialchars($key['usebeforecnt']); ?>");
        $("#autopdf #usenowcnt-input").val("<?php echo htmlspecialchars($key['usenowcnt']); ?>");
        $("#autopdf #usefinishaftercnt-input").val(
            "<?php echo htmlspecialchars($key['usefinishaftercnt']); ?>");
        $("#autopdf #useafterremaincnt-input").val(
            "<?php echo htmlspecialchars($key['useafterremaincnt']); ?>");
        $("#autopdf #reason-input").val("<?php echo htmlspecialchars($key['reason']); ?>");
        $("#autopdf #destplace-input").val("<?php echo htmlspecialchars($key['destplace']); ?>");
        $("#autopdf #desttel-input").val("<?php echo htmlspecialchars($key['desttel']); ?>");
        $("#autopdf #signstamp_user-input").val("<?php echo htmlspecialchars($key['teishutsu_stamp']); ?>");
        $("#autopdf #signstamp_tanto-input").val("<?php echo htmlspecialchars($key['tantosha_stamp']); ?>");
        $("#autopdf #signstamp_sekinin-input").val("<?php echo htmlspecialchars($key['sekininsha_stamp']); ?>");
        $("#autopdf").submit();
    }
    <?php
			}
		}
		?>
    setTimeout(hideLoadingOverlay, 1000);
    startLoading();
});


window.onload = function() {
    setTimeout(hideLoadingOverlay, 1000);
    startLoading();
    selectAllUserKyukaSelectHandler();

};

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

// Check TeiShutsuBeforeSubmit
function checkTeiShutsuSubmit() {
    if (confirm("<?php echo $kakutei_ninsho_message ?>")) {
        return true;
    } else {
        return false;
    }

}



// Check CheckHenshuChuModoshiSubmit
function checkHenshuChuModoshiSubmit() {
    var listSelectedUid = $("input[name='user-kyuka-multi-select-input']").val().trim();
    if (listSelectedUid === '') {
        alert("<?php echo $multi_select_is_empty ?>")
        return false;
    }
    var isSameStatusSelected = checkSameStatusSubmitBefore();
    if (!isSameStatusSelected) {
        alert("<?php echo $same_kyuka_status_select_msg ?>")
        return false;
    }
    var isMiteiShutsuKyukaSelected = checkSelectedTeishutsuKyuka();
    if (!isMiteiShutsuKyukaSelected) {
        alert("<?php echo $miteishutsu_kyuka_selected_error_msg ?>")
        return false;
    }

    if (confirm("<?php echo $user_kyuka_modoshi_submit ?>")) {
        return true;
    } else {
        return false;
    }
}

function checkTantoshaShoninSubmit() {
    var listSelectedUid = $("input[name='user-kyuka-multi-select-input']").val().trim();
    if (listSelectedUid === '') {
        alert("<?php echo $multi_select_is_empty ?>")
        return false;
    }
    var isSameStatusSelected = checkSameStatusSubmitBefore();
    if (!isSameStatusSelected) {
        alert("<?php echo $same_kyuka_status_select_msg ?>")
        return false;
    }
    var isMiteiShutsuKyukaSelected = checkSelectedTeishutsuKyuka();
    if (!isMiteiShutsuKyukaSelected) {
        alert("<?php echo $miteishutsu_kyuka_selected_error_msg ?>")
        return false;
    }
    if (confirm("<?php echo $user_kyuka_tantosha_submit ?>")) {
        return true;
    } else {
        return false;
    }

}

// $miteishutsu_kyuka_selected_error_msg

function checkSekininshaShoninSubmit() {
    var listSelectedUid = $("input[name='user-kyuka-multi-select-input']").val().trim();
    if (listSelectedUid === '') {
        alert("<?php echo $multi_select_is_empty ?>")
        return false;
    }
    var isSameStatusSelected = checkSameStatusSubmitBefore();
    if (!isSameStatusSelected) {
        alert("<?php echo $same_kyuka_status_select_msg ?>")
        return false;
    }
    var isMiteiShutsuKyukaSelected = checkSelectedTeishutsuKyuka();
    if (!isMiteiShutsuKyukaSelected) {
        alert("<?php echo $miteishutsu_kyuka_selected_error_msg ?>")
        return false;
    }

    if (confirm("<?php echo $user_kyuka_sekininsha_submit ?>")) {
        return true;
    } else {
        return false;
    }
}


function checkSameStatusSubmitBefore() {
    var listSelectedStatus = $("input[name='user-kyuka-multi-select-status']").val().trim().split(',');
    return listSelectedStatus.every(function(status) {
        return status === listSelectedStatus[0];
    });
}

function checkSelectedTeishutsuKyuka() {
    var listSelectedStatus = $("input[name='user-kyuka-multi-select-status']").val().trim().split(',');
    return !listSelectedStatus.some(function(status) {
        return status ===  '<?php echo array_search(0, $KYUKA_SUBMISSTION_STATUS); ?>';
    });
}

function SetFormViewBySubmissionStatusHandler() {
    // Set Turn On Off Button 
    setOnOffTeiShutsuButton();
    setOnOffAdminButons();
    SetColorToSubmissionStatus();
}

function setOnOffTeiShutsuButton() {
    var buttons = $("button[name='Kyukateishutsu']");
    buttons.each(function() {
        var inputValue = $(this).siblings("input[name='selectedUserKyukaSubmissionStatus']").val();
        if (inputValue === '<?php echo array_search(0, $KYUKA_SUBMISSTION_STATUS); ?>') {
            $(this).prop('enable', false);
        } else {
            $(this).prop('disabled', true);
        }
    });

}

function setOnOffAdminButons() {
    var buttonsModoshi = $("button[name='KyukaHenshuModoshi']");
    var buttonsTantoSha = $("button[name='KyukaTantoshaShonin']");
    var buttonsSekininSha = $("button[name='KyukaSekininshaShonin']");
    var buttons = buttonsTantoSha.add(buttonsSekininSha).add(buttonsModoshi);

    buttons.each(function() {
        var inputValue = $(this).siblings("input[name='selectedUserKyukaSubmissionStatusAdmin']").val();
        if (inputValue === '<?php echo array_search(0, $KYUKA_SUBMISSTION_STATUS); ?>') {
            $(this).prop('disabled', true);
        } else {
            $(this).prop('disabled', false);
        }
    });
}


function SetColorToSubmissionStatus() {
    $('span[name="show-submission-status"], option[name="filterSubmissionStatusCodeOption"]').each(function() {
        var submissionStatusText = $(this).text().trim();
        $(this).removeClass();
        if (submissionStatusText === '<?php echo $KYUKA_SUBMISSTION_STATUS[0] ?>') {
            // Do nothing
        } else if (submissionStatusText === '<?php echo $KYUKA_SUBMISSTION_STATUS[1] ?>') {
            $(this).addClass('submission-status_1');
        } else if (submissionStatusText === '<?php echo $KYUKA_SUBMISSTION_STATUS[2] ?>') {
            $(this).addClass('submission-status_2');
        } else if (submissionStatusText === '<?php echo $KYUKA_SUBMISSTION_STATUS[3] ?>') {
            $(this).addClass('submission-status_3');
        } else if (submissionStatusText === '<?php echo $KYUKA_SUBMISSTION_STATUS[11] ?>') {
            // Do nothing
        }
    });


}


function setOnModalChangeDataButtonHidden(flag) {
    var elements = $(".modal-update-kyuka-btn");
    elements.each(function() {
        if (flag) {
            $(this).show();
            $(".show-div").hide();
        } else {
            $(".show-div").show();
            $(this).hide();
        }
    });
}

// Multi Select User Kyuka 
var selectedIds = [];
var selectedStatuses = [];

function multiUserKyukaSelectHandler() {
    $('.user-kyuka-select-checkbox').change(function() {
        updateSelectedValues(this);
    });
}

function updateSelectedValues(checkBox) {
    var statusValue = $(checkBox).data('status-value');
    if (checkBox.checked) {
        selectedIds.push($(checkBox).val());
        selectedStatuses.push(statusValue);
    } else {
        var index = selectedIds.indexOf($(checkBox).val());
        if (index !== -1) {
            selectedIds.splice(index, 1);
            selectedStatuses.splice(index, 1);
        }
    }
    $("input[name='user-kyuka-multi-select-input']").val(selectedIds.join(','));
    $("input[name='user-kyuka-multi-select-status']").val(selectedStatuses.join(','));

    // If all checkboxes are unchecked, uncheck the select all checkbox
    if ($('.user-kyuka-select-checkbox:checked').length === 0) {
        $('#user-kyuka-select-all-checkbox').prop('checked', false);
        selectedIds = [];
        selectedStatuses = [];
    }
}

function selectAllUserKyukaSelectHandler() {
    $('#user-kyuka-select-all-checkbox').change(function() {
             selectedIds = [];
            selectedStatuses = [];
        $('.user-kyuka-select-checkbox').each(function() {
            $(this).prop('checked', $('#user-kyuka-select-all-checkbox').is(':checked'));
            updateSelectedValues(this);
        });
    });
}

function handleSelectFilterStatusChange() {
    var selectedValue = $('#filterByStatusCode').val();
    $('#selectedFilterByStatusCode').val(selectedValue);
}
</script>
<?php include('../inc/footer.php'); ?>