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
	<form method="post">
		<div class="row">
			<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
				<div class="col-md-2 text-left">
					<div class="title_name">
						<span class="text-left">休暇届</span>
					</div>
				</div>
				<div class="col-md-4 text-left">
					<div class="title_condition">
						<label>
							<?php
							foreach (ConstArray::$search_allowok as $key => $value) {
							?>
								<input type='radio' name='searchAllowok' value='<?= $key ?>' <?php if ($key == $_POST['searchAllowok']) {
																									echo ' checked="checked"';
																								} ?>>
								<?= $value ?>
								</input>
							<?php
							}
							?>
						</label>
					</div>
				</div>
				<div class="col-md-3 text-left">
					<div class="title_condition">
						<label>社員名 :
							<select id="searchUid" name="searchUid" style="padding:2px; width:70%;">
								<option value="" selected="">選択なし</option>
								<?php
								foreach ($user_list as $value) {
								?>
									<option value="<?= $value['uid'] ?>" <?php if ($value['uid'] == $_POST['searchUid']) {
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
						<label>基準日 :
							<select id="searchYY" name="searchYY" style="padding:2px;">
								<option value="" selected="selected">選択なし</option>
								<?php
								foreach (ConstArray::$search_year as $key => $value) {
								?>
									<option value="<?= $key ?>" <?php if ($value == $_POST['searchYY']) {
																	echo ' selected="selected"';
																} ?>>
										<?= $value ?>
									</option>
								<?php
								}
								?>
							</select>
						</label>
					</div>
					<div class="title_btn">
						<input type="submit" id="ClearButton" name="ClearButton" value="クリア ">&nbsp;
						<input type="submit" name="btnSearchReg" value="検索 ">&nbsp;
						<input type="button" id="btnNew" value="新規 ">&nbsp;
						<input type="button" id="btnAnnt" value="お知らせ ">
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
			<table class="table table-bordered datatable" style="overflow-x: auto;">
				<thead>
					<tr class="info">
						<th style="text-align: center;">ID</th>
						<th style="text-align: center;">申請日</th>
						<th style="text-align: center;">入社年月</th>
						<th style="text-align: center;">社員名</th>
						<th style="text-align: center;">申請区分</th>
						<th style="text-align: center;">休暇区分</th>
						<th style="text-align: center;">年度算定期間</th>
						<th style="text-align: center;">申請期間</th>
						<th style="text-align: center;">申請日数(時間)</th>
						<th style="text-align: center;">総有給休暇</th>
						<th style="text-align: center;">前年度の繰越残</th>
						<th style="text-align: center;">当該年度付与</th>
						<th style="text-align: center;">使用済数</th>
						<th style="text-align: center;">使用前残</th>
						<th style="text-align: center;">今回使用</th>
						<th style="text-align: center;">使用後済</th>
						<th style="text-align: center;">使用後残</th>
						<th style="text-align: center;">事由</th>
						<th style="text-align: center;">休暇中居る場所</th>
						<th style="text-align: center;">緊急連絡先</th>
						<th style="text-align: center;">決裁</th>
						<th style="text-align: center;">詳細情報</th>
					</tr>
				</thead>
				<tbody>
					<?php if (empty($userkyuka_list)) { ?>
						<tr>
							<td colspan="22" align="center"><?php echo $data_save_no; ?></td>
						</tr>
						<?php } elseif (!empty($userkyuka_list)) {
						foreach ($userkyuka_list as $userkyuka) {
						?>
							<tr>
								<td>
									<a href="#">
										<span class="showModal">
											<span class="kyukaReg_class">
												<?= $userkyuka['kyukaid'] . ',' . $userkyuka['kyukaname'] ?>
											</span>
											<?= $userkyuka['uid'] ?>
										</span>
									</a>
								</td>
								<td><span><?= $userkyuka['kyukaymd'] ?></span></td>
								<td><span><?= substr($userkyuka['inymd'], 0, 4) ?>年<?= substr($userkyuka['inymd'], 5, 2) ?>月</span></td>
								<td><span><?= $userkyuka['name'] ?></span></td>
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
											echo "時間";
										}
										?>
									</span>
								</td>
								<td><span><?= $userkyuka['kyukaname'] ?></span></td>
								<td><span><?= $userkyuka['vacationstr'] ?>~<?= $userkyuka['vacationend'] ?></span></td>
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
												echo $userkyuka['timecnt'] . "日";
											} elseif ($user_kyukatemplate_ == "2") {
												echo $userkyuka['timecnt'] . "時間";
											}
										} elseif ($userkyuka['kyukatype'] == 1) {
											echo $userkyuka['ymdcnt'] . "日";
										}
										?>
									</span>
								</td>
								<td><span><?= $userkyuka['tothday'] ?></span></td>
								<td><span><?= $userkyuka['oldcnt'] ?></span></td>
								<td><span><?= $userkyuka['newcnt'] ?></span></td>
								<td><span><?= $userkyuka['usefinishcnt'] ?></span></td>
								<td><span><?= $userkyuka['usebeforecnt'] ?></span></td>
								<td><span><?= $userkyuka['usenowcnt'] ?></span></td>
								<td><span><?= $userkyuka['usefinishaftercnt'] ?></span></td>
								<td><span><?= $userkyuka['useafterremaincnt'] ?></span></td>
								<td><span><?= $userkyuka['reason'] ?></span></td>
								<td><span><?= $userkyuka['destplace'] ?></span></td>
								<td><span><?= $userkyuka['desttel'] ?></span></td>
								<td>
									<span>
										<?php
										if ($userkyuka['allowok'] == "0") { ?>
											<?php if ($_SESSION['auth_type'] == constant('ADMIN') || $_SESSION['auth_type'] == constant('ADMINISTRATOR') || $_SESSION['auth_type'] == constant('MAIN_ADMIN')) : ?>
												<a href="#"><span style="color:red;text-decoration-line: underline;" class="showModal2">未決裁<span class="vacationid_class"><?= ',' . $userkyuka['uid'] . ',' . $userkyuka['ymdcnt']  . ',' . $userkyuka['timecnt'] ?></span></span>
												<?php else : ?>
													<span style="color:red;">未決裁</span>
												<?php endif; ?>
											<?php } else { ?>
												<span>
													決裁完了
													<?php
													if ($userkyuka['allowdecide'] == "0") { ?>
														<span style="color:red;">NG</span>
													<?php } else { ?>
														OK
													<?php } ?>
												</span>
											<?php } ?>
									</span>
								</td>
								<td>
									<span>
										<div class="print_btn">
											<button class="btn btn-default submit-button" style="width: auto;" type="button" data-kyukaid="<?= $userkyuka['kyukaid'] ?>">
												休暇印刷
											</button>
											<button id="" class="btn btn-default" style="width: auto;" type="button">提出</button>
										</div>
									</span>
								</td>
							</tr>
					<?php }
					} ?>
				</tbody>
			</table>
		</div>
	</form>

	<!-- 新規 -->
	<div class="row">
		<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form method="post" novalidate>
					<div class="modal-content">
						<div class="modal-header">休年届登録(<span id="sname">New</span>)
							<button class="close" data-dismiss="modal">x</button>
						</div>
						<div class="modal-body" style="text-align: left; height: 600px; overflow-y: auto;">
							<div class="row one">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
									<label for="kyukaymd">申請日</label>
									<input type="text" class="form-control" name="kyukaymd" style="text-align: center" value="<?= date('Y/m/d'); ?>" readonly>
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 inymd">
									<label for="inymd">入社年月</label>
									<input type="text" class="form-control" name="inymd" style="text-align: center" value="<?= substr($user_inymd_, 0, 4) ?>年<?= substr($user_inymd_, 5, 2) ?>月" readonly>
								</div>
								<div class="col-md-6 col-sm-6 col-sx-6 kyukacompanyname">
									<label for="name">社員名</label>
									<input type="text" class="form-control" name="name" id="name" style="text-align: center" value="<?= $user_name_ ?>" readonly>
								</div>
							</div>
							<br>
							<div class="row two">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukatype">
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
								<div class="col-md-3 col-sm-3 col-sx-3 kyukacode">
									<label for="kyukacode">休暇区分</label>
									<select class="form-control" id="kyukacode" name="kyukacode">
										<option value="" disabled selected style="font-size:10px;">選択</option>
										<?php foreach ($codebase_list_kyuka as $key) : ?>
											<option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
										<?php endforeach; ?>
									</select>
									<input type="text" id="inputTag" name="inputTag" style="display: none;">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6 vacation">
									<label for="vacation">年度算定期間</label>
									<div class="groupinput">
										<input type="text" class="form-control" id="vacationstr" name="vacationstr" placeholder="開始日" required="required" maxlength="10" style="text-align: center;" value="<?= $startdate_ ?>">
										<div class="input-group-addon">~</div>
										<input type="text" class="form-control" id="vacationend" name="vacationend" placeholder="終了日" required="required" maxlength="10" style="text-align: center;" value="<?= $enddate_ ?>">
									</div>
								</div>
							</div>
							<br>
							<div class="row three">
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strymd">期間(F)</label>
									<input type="text" class="form-control" id="strymd" name="strymd" placeholder="日付" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endymd">期間(T)</label>
									<input type="text" class="form-control" id="endymd" name="endymd" placeholder="日付" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="divided">
									<span class="notice_time">業務時間(<?= $user_starttime_ ?>~<?= $user_endtime_ ?>)、休憩時間(<?= $user_breakstarttime_ ?>~<?= $user_breakendtime_ ?>)</span>
									<div class="layout">
										<div class="col-md-3 col-sm-3 col-sx-3 day">
											<label for="strtime">時間(F)</label>
											<input type="text" class="form-control" id="strtime" name="strtime" placeholder="00" required="required" maxlength="2" style="text-align: center">
										</div>
										<div class="col-md-3 col-sm-3 col-sx-3 day">
											<label for="endtime">時間(T)</label>
											<input type="text" class="form-control" id="endtime" name="endtime" placeholder="00" required="required" maxlength="2" style="text-align: center">
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row four">
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="tothday">総有給休暇</label>
									<input type="text" class="form-control" id="tothday" name="tothday" placeholder="番号" style="text-align: center" value="<?= $tothday_ ?>">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="oldcnt">前年度の繰越残</label>
									<input type="text" class="form-control" id="oldcnt" name="oldcnt" placeholder="番号" style="text-align: center" value="<?= $oldcnt_ ?>">
								</div>
								<div class=" col-md-3 col-sm-3 col-sx-3">
									<label for="newcnt">当該年度付与</label>
									<input type="text" class="form-control" id="newcnt" name="newcnt" placeholder="番号" style="text-align: center" value="<?= $newcnt_ ?>">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 no">
									<label for="usefinishcnt">使用済数</label>
									<input type="text" class="form-control" id="usefinishcnt" name="usefinishcnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row five">
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usebeforecnt">使用前残</label>
									<input type="text" class="form-control" id="usebeforecnt" name="usebeforecnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usenowcnt">今回使用</label>
									<input type="text" class="form-control" id="usenowcnt" name="usenowcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usefinishaftercnt">使用後済</label>
									<input type="text" class="form-control" id="usefinishaftercnt" name="usefinishaftercnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="useafterremaincnt">使用後残</label>
									<input type="text" class="form-control" id="useafterremaincnt" name="useafterremaincnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row six">
								<div class="col-md-8 col-sm-8 col-sx-8">
									<label for="reason">事由</label>
									<textarea class="form-control" id="reason" name="reason" rows="2"></textarea>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2">
									<label for="ymdcnt">申請日数</label>
									<input type="text" class="form-control" id="ymdcnt" name="ymdcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2">
									<label for="timecnt">
										<?php if ($user_kyukatemplate_ == "1") : ?>
											半休日数
										<?php elseif ($user_kyukatemplate_ == "2") : ?>
											申請時間
										<?php endif; ?>
									</label>
									<input type="text" class="form-control" id="timecnt" name="timecnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row seven">
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
									<input type="text" class="form-control" name="destplace" id="destplace" placeholder="国" required="required" style="text-align: left">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="desttel">緊急連絡先</label>
									<input type="text" class="form-control" name="desttel" id="desttel" placeholder="090xxxxxxxx" required="required" style="text-align: left">
								</div>
							</div>
							<br>
							<div class="modal-footer" style="text-align: center">
								<div class="col-xs-3"></div>
								<div class="col-xs-2">
									<p class="text-center">
										<input type="submit" name="SaveKyuka" class="btn btn-primary btn-ms" id="btnReg" role="button" value="登録">
									</p>
								</div>
								<div class="col-xs-2">
									<p class="text-center">
										<a class="btn btn-success btn-ms" id="btnClear" role="button">クリア</a>
									</p>
								</div>
								<div class="col-xs-2">
									<button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
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
						<div class="modal-header">休年届編集(<span id="usname"></span>)
							<button class="close" data-dismiss="modal">x</button>
						</div>
						<div class="modal-body" style="text-align: left; height: 600px; overflow-y: auto;">
							<div class="row one">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukaymd">
									<label for="kyukaymd">申請日</label>
									<input type="text" class="form-control" name="udkyukaymd" style="text-align: center" value="<?= date('Y/m/d'); ?>" readonly>
									<input type="hidden" name="udkyukaid" id="udkyukaid">
									<input type="hidden" name="udvacationid" id="udvacationid">
									<input type="hidden" name="udallowok" id="udallowok">
									<input type="hidden" name="udallowid" id="udallowid">
									<input type="hidden" name="udallowdecide" id="udallowdecide">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 inymd">
									<label for="inymd">入社年月</label>
									<input type="text" class="form-control" name="udinymd" style="text-align: center" value="<?= substr($user_inymd_, 0, 4) ?>年<?= substr($user_inymd_, 5, 2) ?>月" readonly>
								</div>
								<div class="col-md-6 col-sm-6 col-sx-6 kyukacompanyname">
									<label for="name">社員名</label>
									<input type="text" class="form-control" name="udname" id="udname" style="text-align: center" value="<?= $user_name_ ?>" readonly>
								</div>
							</div>
							<br>
							<div class="row two">
								<div class="col-md-3 col-sm-3 col-sx-3 kyukatype">
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
								<div class="col-md-3 col-sm-3 col-sx-3 kyukacode">
									<label for="kyukacode">休暇区分</label>
									<select class="form-control" id="udkyukacode" name="udkyukacode">
										<option value="" disabled selected style="font-size:10px;">選択</option>
										<?php foreach ($codebase_list_kyuka as $key) : ?>
											<option value="<?= $key["code"] ?>"><?= $key["name"] ?></option>
										<?php endforeach; ?>
									</select>
									<input type="text" id="udinputTag" name="udinputTag" style="display: none;">
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6 vacation">
									<label for="vacation">年度算定期間</label>
									<div class="groupinput">
										<input type="text" class="form-control" id="udvacationstr" name="udvacationstr" placeholder="開始日" required="required" maxlength="10" style="text-align: center;">
										<div class="input-group-addon">~</div>
										<input type="text" class="form-control" id="udvacationend" name="udvacationend" placeholder="終了日" required="required" maxlength="10" style="text-align: center;">
									</div>
								</div>
							</div>
							<br>
							<div class="row three">
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="strymd">期間(F)</label>
									<input type="text" class="form-control" id="udstrymd" name="udstrymd" placeholder="日付" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 day">
									<label for="endymd">期間(T)</label>
									<input type="text" class="form-control" id="udendymd" name="udendymd" placeholder="日付" required="required" maxlength="10" style="text-align: center">
								</div>
								<div class="divided">
									<span class="notice_time">業務時間(<?= $user_starttime_ ?>~<?= $user_endtime_ ?>)、休憩時間(<?= $user_breakstarttime_ ?>~<?= $user_breakendtime_ ?>)</span>
									<div class="layout">
										<div class="col-md-3 col-sm-3 col-sx-3 day">
											<label for="strtime">時間(F)</label>
											<input type="text" class="form-control" id="udstrtime" name="udstrtime" placeholder="00" required="required" maxlength="2" style="text-align: center">
										</div>
										<div class="col-md-3 col-sm-3 col-sx-3 day">
											<label for="endtime">時間(T)</label>
											<input type="text" class="form-control" id="udendtime" name="udendtime" placeholder="00" required="required" maxlength="2" style="text-align: center">
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row four">
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="tothday">総有給休暇</label>
									<input type="text" class="form-control" id="udtothday" name="udtothday" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="oldcnt">前年度の繰越残</label>
									<input type="text" class="form-control" id="udoldcnt" name="udoldcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class=" col-md-3 col-sm-3 col-sx-3">
									<label for="newcnt">当該年度付与</label>
									<input type="text" class="form-control" id="udnewcnt" name="udnewcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3 no">
									<label for="usefinishcnt">使用済数</label>
									<input type="text" class="form-control" id="udusefinishcnt" name="udusefinishcnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row five">
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usebeforecnt">使用前残</label>
									<input type="text" class="form-control" id="udusebeforecnt" name="udusebeforecnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usenowcnt">今回使用</label>
									<input type="text" class="form-control" id="udusenowcnt" name="udusenowcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="usefinishaftercnt">使用後済</label>
									<input type="text" class="form-control" id="udusefinishaftercnt" name="udusefinishaftercnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-3 col-sm-3 col-sx-3">
									<label for="useafterremaincnt">使用後残</label>
									<input type="text" class="form-control" id="uduseafterremaincnt" name="uduseafterremaincnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row six">
								<div class="col-md-8 col-sm-8 col-sx-8">
									<label for="reason">事由</label>
									<textarea class="form-control" id="udreason" name="udreason" rows="2"></textarea>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2">
									<label for="ymdcnt">申請日数</label>
									<input type="text" class="form-control" id="udymdcnt" name="udymdcnt" placeholder="番号" style="text-align: center" value="">
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2">
									<label for="timecnt">
										<?php if ($user_kyukatemplate_ == "1") : ?>
											半休日数
										<?php elseif ($user_kyukatemplate_ == "2") : ?>
											申請時間
										<?php endif; ?>
									</label>
									<input type="text" class="form-control" id="udtimecnt" name="udtimecnt" placeholder="番号" style="text-align: center" value="">
								</div>
							</div>
							<br>
							<div class="row seven">
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
									<input type="text" class="form-control" name="uddestplace" id="uddestplace" placeholder="国" required="required" style="text-align: left">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 address">
									<label for="desttel">緊急連絡先</label>
									<input type="text" class="form-control" name="uddesttel" id="uddesttel" placeholder="090xxxxxxxx" required="required" style="text-align: left">
								</div>
							</div>
							<br>
							<div class="modal-footer" style="text-align: center">
								<div class="col-xs-2"></div>
								<div class="col-xs-2">
									<p class="text-center">
										<input type="submit" name="UpdateKyuka" class="btn btn-primary" id="btnUpdateKyuka" role="button" value="編集">
									</p>
								</div>
								<div class="col-xs-2">
									<p class="text-center">
										<input type="submit" name="DelKyuka" class="btn btn-warning" id="btnDelKyuka" role="button" value="削除">
									</p>
								</div>
								<div class="col-xs-2">
									<p class="text-center">
										<a class="btn btn-success btn-ms" id="btnClearUpdate" role="button">クリア</a>
									</p>
								</div>
								<div class="col-xs-2">
									<button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">閉じる</button>
								</div>
								<div class="col-xs-2"></div>
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
	</form>

	<!-- お知らせ -->
	<div class="row">
		<div class="modal" id="modal3" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><span class="popup-title">お知らせ(注意)</span>
						<button class="close" data-dismiss="modal">x</button>
					</div>
					<div class="modal-body" style="text-align: left">
						<div class="row">
							<div class="col-md-12 col-ms-12">
								<div class="alert alert-warning">
									<strong>1&nbsp;</strong>事前許可が必要なので、担当者に許可の届け (休暇届) を提出すること。<br>
									<strong>&nbsp;・</strong>原則として1週間前までに、少なくとも前々日までに提出すること。<br>
									<strong>&nbsp;・</strong>連続4日以上 (所定休日が含まれる場合を含む。) の休暇を取得するときは、1ヵ月前までに提出すること。<br>
									<strong>&nbsp;・</strong>緊急・病気の場合は、その時点ですぐに提出すること。<br>
									<strong>2&nbsp;</strong>年間で5日分はその年で必ず取ること。<br>
									<strong>3&nbsp;</strong>有給休暇は1年に限って繰り越しできます (2の5日分は除外、 0.5日分は除外)。<br>
									<strong>4&nbsp;</strong>半休(5時間以内)の場合は0.5日にて表現してください。その他詳しい内容は担当者に聞いてください。
								</div>
							</div>
							<div class="col-md-12 col-ms-12 sub-middle">
								<div class="alert alert-info" style="margin-bottom: 10px;">
									<strong>※&nbsp;年次有給休暇</strong>
								</div>
							</div>
							<div class="col-md-12 col-ms-12">
								<table class="table table-bordered datatable">
									<thead>
										<tr>
											<th class="info table-notic" style="text-align: center; color: #31708f;">勤続年数</th>
											<td class="table-notic" style="text-align: center;">6ヵ月以内</td>
											<td class="table-notic" style="text-align: center;">6ヵ月</td>
											<td class="table-notic" style="text-align: center;">1年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">2年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">3年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">4年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">5年6ヵ月</td>
											<td class="table-notic" style="text-align: center;">5年6ヵ月以上</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th class="info" style="text-align: center; color: #31708f;">付与日数</th>
											<td class="table-notic" style="text-align: center;">無し</td>
											<td class="table-notic" style="text-align: center;">10日</td>
											<td class="table-notic" style="text-align: center;">11日</td>
											<td class="table-notic" style="text-align: center;">12日</td>
											<td class="table-notic" style="text-align: center;">14日</td>
											<td class="table-notic" style="text-align: center;">16日</td>
											<td class="table-notic" style="text-align: center;">18日</td>
											<td class="table-notic" style="text-align: center;">20日</td>
										</tr>
									</tbody>
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

	<!-- Decide 決裁 -->
	<div class="row">
		<div class="modal" id="modal4" tabindex="-1" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<form method="post">
					<div class="modal-content">
						<div class="modal-header">
							<span id="ustitle">決裁の決定</span>
							<button class="close" data-dismiss="modal">x</button>
						</div>

						<div class="modal-body" style="text-align: left">
							<div class="row one">
								<div class="col-md-2 col-sm-2 col-sx-2"></div>
								<div class="col-md-4 col-sm-4 col-sx-4 uid">
									<label for="uid">ID</label>
									<input type="text" class="form-control" id="duid" name="duid" style="text-align: left" readonly>
									<input type="hidden" id="allowok" name="allowok" value="1">
									<input type="hidden" id="newymdcnt" name="newymdcnt">
									<input type="hidden" id="newtimecnt" name="newtimecnt">
									<input type="hidden" id="oldusecnt" name="oldusecnt">
									<input type="hidden" id="oldusetime" name="oldusetime">
									<input type="hidden" id="oldrestcnt" name="oldrestcnt">
								</div>
								<div class="col-md-4 col-sm-4 col-sx-4 vacationstr">
									<label for="destcode">許可の決定</label>
									<div class="custom-control custom-radio">
										<input type="radio" class="decide_allowok" name="decide_allowok" value="0" style="margin-right: 5px;">NG&nbsp;&nbsp;&nbsp;&nbsp;
										<input type="radio" class="decide_allowok" name="decide_allowok" value="1" style="margin-right: 5px;">OK
									</div>
								</div>
								<div class="col-md-2 col-sm-2 col-sx-2"></div>
							</div>
						</div>
						<div class="modal-footer" style="text-align: center">
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<input type="submit" name="DecideUpdateKyuka" class="btn btn-primary btn-md" id="btnRegDecideUpdate" role="button" value="登録">
								</p>
							</div>
							<div class="col-md-2 col-sm-2 col-sx-2 btn">
								<p class="text-center">
									<a class="btn btn-default btn-md" data-dismiss="modal" role="button">閉じる </a>
								</p>
							</div>
							<div class="col-md-4 col-sm-4 col-sx-4"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	// New button(新規)
	$(document).on('click', '#btnNew', function(e) {
		// Check Receive Kyuka or Not
		var currentDate = new Date();
		var targetDate = new Date('<?php echo $user_inymd_ ?>');
		targetDate.setMonth(targetDate.getMonth() + 6);
		if (targetDate >= currentDate) {
			alert("<?php echo $kyuka_no_receive; ?>");
			return false;
		}

		$('#modal').modal('toggle');
		// In the case of a new application, it cannot be used until the application category is selected.
		$("#strymd").val("").prop('disabled', true);
		$("#endymd").val("").prop('disabled', true);

		// In the case of a new application, it cannot be used until the application category is selected.
		$("#strtime").val("").prop('disabled', true);
		$("#endtime").val("").prop('disabled', true);
	});

	// ①総有給休暇数, ②＋③＝①
	$("#oldcnt, #newcnt").on("input", function() {
		var oldcntValue = parseFloat($("#oldcnt").val()) || 0;
		var newcntValue = parseFloat($("#newcnt").val()) || 0;
		var totaly = oldcntValue + newcntValue;
		$("#tothday").val(totaly);
	});

	// ⑦使用後済数(④＋⑥)
	$("#usefinishcnt, #usenowcnt").on("input", function() {
		var usefinishcntValue = parseFloat($("#usefinishcnt").val()) || 0;
		var usenowcntValue = parseFloat($("#usenowcnt").val()) || 0;
		var totaly = usefinishcntValue + usenowcntValue;
		$("#usefinishaftercnt").val(totaly);
	});

	// ⑧使用後残日数(⑤－⑥)
	$("#usebeforecnt, #usenowcnt").on("input", function() {
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
		var regex = /\(|\)/; // Regular expression to match '(' or ')'
		// Function to calculate timecnt
		$("#endtime, #strtime").on("input", function() {
			calculateTimeDifference();
		});

		// Function to calculate timecnt
		$("#udendtime, #udstrtime").on("input", function() {
			udcalculateTimeDifference();
		});

		// Attach a change event to the kyukacode select
		$("#kyukacode").change(function() {
			var selectedText = $(this).find("option:selected").text();
			if (regex.test(selectedText)) {
				$('#inputTag').show();
			} else {
				$('#inputTag').hide();
			}
		});
	});

	function calculateTimeDifference() {
		var strTimeValue = parseInt($("#strtime").val());
		var endTimeValue = parseInt($("#endtime").val());

		function convertTimeToDecimal(timeString) {
			var timeParts = timeString.split(':');
			var hours = parseInt(timeParts[0], 10);
			var minutes = parseInt(timeParts[1], 10);
			var minutesFraction = minutes / 60;
			return hours + minutesFraction;
		}

		function convertTimeVariablesToDecimal() {
			var breaktime_ = "<?php echo $user_breaktime_ ?>";
			var user_breaktime_ = convertTimeToDecimal(breaktime_);

			var worktime_ = "<?php echo $user_worktime_ ?>";
			var user_worktime_ = convertTimeToDecimal(worktime_);

			var starttime_ = "<?php echo $user_starttime_ ?>";
			var user_starttime_ = convertTimeToDecimal(starttime_);

			var endtime_ = "<?php echo $user_endtime_ ?>";
			var user_endtime_ = convertTimeToDecimal(endtime_);

			var breakstarttime_ = "<?php echo $user_breakstarttime_ ?>";
			var user_breakstarttime_ = convertTimeToDecimal(breakstarttime_);

			var breakendtime_ = "<?php echo $user_breakendtime_ ?>";
			var user_breakendtime_ = convertTimeToDecimal(breakendtime_);

			return {
				user_breaktime_: user_breaktime_,
				user_worktime_: user_worktime_,
				user_starttime_: user_starttime_,
				user_endtime_: user_endtime_,
				user_breakstarttime_: user_breakstarttime_,
				user_breakendtime_: user_breakendtime_
			};
		}
		var times = convertTimeVariablesToDecimal();

		if (!isNaN(strTimeValue) && !isNaN(endTimeValue)) {
			if (strTimeValue <= times.user_breakstarttime_ && endTimeValue >= times.user_breakendtime_) {
				if ("<?php echo $user_kyukatemplate_ ?>" == "1") {
					var timeDifference = (endTimeValue - strTimeValue - times.user_breaktime_) / times.user_worktime_;
				} else if ("<?php echo $user_kyukatemplate_ ?>" == "2") {
					var timeDifference = endTimeValue - strTimeValue - times.user_breaktime_;
				}
				$("#timecnt").val(timeDifference);
			} else {
				if ("<?php echo $user_kyukatemplate_ ?>" == "1") {
					var timeDifference = (endTimeValue - strTimeValue) / times.user_worktime_;
				} else if ("<?php echo $user_kyukatemplate_ ?>" == "2") {
					var timeDifference = endTimeValue - strTimeValue;
				}
				$("#timecnt").val(timeDifference);
			}
		}
	}

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
		var timecnt = $("#timecnt").val();
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

		if (timecnt !== "0.5" && kyukatype === "0" && user_kyukatemplate_ === "1") {
			alert("<?php echo $kyuka_timecnt_halfday; ?>");
			$("#timecnt").focus();
			return false;
		}
	});

	// Clear Input Tag Data
	$(document).on('click', '#btnClear', function(e) {
		$('#kyukaname').val('');
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

	function udcalculateTimeDifference() {
		var udendTime = $("#udendtime").val();
		var udstartTime = $("#udstrtime").val();

		if (udendTime !== "" && udstartTime !== "") {
			udendTime = parseInt(udendTime, 10);
			udstartTime = parseInt(udstartTime, 10);

			// Check if the values are valid integers
			if (!isNaN(udendTime) && !isNaN(udstartTime)) {
				var udtimeDifference = udendTime - udstartTime;
				$("#udtimecnt").val(udtimeDifference);
			}
		}
	}

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
		var timecnt = $("#udtimecnt").val();
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

		if (timecnt !== "0.5" && kyukatype === "0" && user_kyukatemplate_ === "1") {
			alert("<?php echo $kyuka_timecnt_halfday; ?>");
			$("#timecnt").focus();
			return false;
		}
	});

	// Clear Input Tag Data
	$(document).on('click', '#btnClearUpdate', function(e) {
		$('#udkyukaname').val('');
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
		$('#modal2').modal('toggle');
		var ArrayData = $(this).text().trim();
		var SeparateArr = ArrayData.split(',');
		var Kyukaid = SeparateArr[0];
		var Kyukaname = SeparateArr[1];
		var regex = /\(|\)/;
		var match = Kyukaname.match(/\((.*?)\)/);
		var extractedText = match ? match[1] : null;
		if (regex.test(Kyukaname)) {
			$('#udinputTag').show();
			$("#udinputTag").text($('[name="udinputTag"]').val(extractedText));
		} else {
			$('#udinputTag').hide();
		}
		<?php
		foreach ($userkyuka_list as $key) {
		?>
			if ('<?php echo $key['kyukaid'] ?>' === Kyukaid) {
				$("#usname").text('<?php echo $key['name'] ?>');
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
				} else if (decide_readOnly === "1") {
					$("#udstrtime").prop('disabled', true);
					$("#udendtime").prop('disabled', true);
					$("#udtimecnt").prop('disabled', true);
				}

				$("select[name='udkyukacode']").val('<?php echo $key['kyukacode']; ?>');

				$("#udkyukacode").change(function() {
					var udselectedText = $(this).find("option:selected").text();
					if (regex.test(udselectedText)) {
						$('#udinputTag').show();
						$("#udinputTag").text($('[name="udinputTag"]').val(""));
					} else {
						$('#udinputTag').hide();
					}
				});

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
				$("#udusefinishaftercnt").text($('[name="udusefinishaftercnt"]').val("<?php echo $key['usefinishaftercnt'] ?>"));
				$("#uduseafterremaincnt").text($('[name="uduseafterremaincnt"]').val("<?php echo $key['useafterremaincnt'] ?>"));
				$("#udreason").text($('[name="udreason"]').val("<?php echo $key['reason'] ?>"));
				$("#udymdcnt").text($('[name="udymdcnt"]').val("<?php echo $key['ymdcnt'] ?>"));
				$("#udtimecnt").text($('[name="udtimecnt"]').val("<?php echo $key['timecnt'] ?>"));
				$("#udymdcnt").text($('[name="udymdcnt"]').val("<?php echo $key['ymdcnt'] ?>"));
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
	};
</script>
<?php include('../inc/footer.php'); ?>