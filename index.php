<?php
session_start();
include('./inc/dbconnect.php');
include('./model/indexmodel.php');
include('./inc/message.php');
include('./model/inactive.php');
?>
<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="./assets/js/jquery.min.js"></script>
	<script src="./assets/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="./assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="./assets/fonts/glyphicons-halflings-regular.eot">
	<link rel="stylesheet" href="./assets/fonts/glyphicons-halflings-regular.ttf">
	<link rel="stylesheet" href="./assets/fonts/glyphicons-halflings-regular.woff">
	<!-- common Javascript     -->
	<script type="text/javascript" src="./assets/js/common.js"> </script>

	<!-- Datepeeker 위한 link -->
	<link rel="stylesheet" href="./assets/css/jquery-ui.min.css">
	<script src="./assets/js/jquery-ui.min.js"></script>

	<!-- common CSS -->
	<link rel="stylesheet" href="./assets/css/common.css">

	<style>
		body {
			padding: 0px;
			font-family: helvetica, meiryo, gulim;
			/* Must be English font, Japanese font, Korean font in order */
		}

		#tile_header {
			width: 100%;
		}

		#tile_body {
			width: 100%;
			float: left;
		}

		.jumbotron {
			background-image: url('./assets/images/bk_image.jpg');
			text-shadow: black 0.2em 0.2em 0.2em;
			color: white;
			margin-bottom: 5px;
		}

/* kinmuhyo button animation add start  */
		@import url('https://fonts.googleapis.com/css?family=Poppins:900i');

* {
  box-sizing: border-box;
}

body {
 
  display: flex;
  justify-content: center;
  align-items: center;
}

.wrapper {
  display: flex;
  justify-content: center;
}

.cta {
    display: flex;
    padding: 5px 10px;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
    font-size: 35px;
    color: white;
    background: #2549e6;
    transition: 1s;
	box-shadow: 3px 2px 0 rgba(0, 0, 0, 0.8);
    transform: skewX(-15deg);
	position: absolute;
    bottom: 0;
    right: 0;
}

.cta:focus {
   outline: none; 
}

.cta:hover{
    transition: 0.5s;
    box-shadow: 5px 5px 0 #fef4da;
}


.cta span:nth-child(2) {
    transition: 0.5s;
    margin-right: 35px;
}

.cta:hover  span:nth-child(2) {
    transition: 0.5s;
    margin-right: 25px;
}

  span {
    transform: skewX(15deg) 
  }

  span:nth-child(2) {
    width: 35px;
    margin-left: 15px;
    position: relative;
    top: 12%;
  }
  
/**************SVG****************/

path.one {
    transition: 0.4s;
    transform: translateX(-60%);
}

path.two {
    transition: 0.5s;
    transform: translateX(-30%);
}

.cta:hover path.three {
    animation: color_anim 1s infinite 0.2s;
}

.cta:hover path.one {
    transform: translateX(0%);
    animation: color_anim 1s infinite 0.6s;
}

.cta:hover path.two {
    transform: translateX(0%);
    animation: color_anim 1s infinite 0.4s;
}

/* SVG animations */

@keyframes color_anim {
    0% {
        fill: white;
    }
    50% {
        fill: #386dfb;
    }
    100% {
        fill: white;
    }
}

	/* kinmuhyo button animation add end  */

	</style>

	
</head>

<body>
	<div id="tile_body">
		<title>Home</title>
		<?php include('./inc/menu.php'); ?>
		<div class="container">
			<?php if (isset($_SESSION['login_success'])) : ?>
				<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $_SESSION['login_success']; ?>
				</div>
			<?php
				unset($_SESSION['login_success']);
			elseif (isset($_SESSION['logout_success'])) :
			?>
				<div class="alert alert-success alert-dismissible" role="alert" auto-close="3000">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					<?php echo $_SESSION['logout_success']; ?>
				</div>
			<?php
				unset($_SESSION['logout_success']);
				session_destroy();
			endif;
			?>
			<div class="jumbotron" id="jumbo1">
				<h1 class="text-center">ネットDE勤怠</h1>
				<p class="text-center">ネット上の勤怠管理システム</p>
			</div>
			<div class="row">
				<div class="col-md-4 text-center" style="margin-bottom: 5px;">
					<!-- <a href="./kintai/kintaiReg.php"><img src="./assets/images/main-12.jpg" width="100%" height="200">
				
					<a class="cta" href="#">
    <span>NEXT</span>
    <span>
      <svg width="66px" height="43px" viewBox="0 0 66 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="arrow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <path class="one" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
          <path class="two" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
          <path class="three" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
        </g>
      </svg>
    </span> 
  </a>
</div>
				</a> -->

				<div style="position: relative; width: 100%; height: 200px;" id="kinmuhyoParrent">
    <a class="cta" href="./kintai/kintaiReg.php">
        <span>Go</span>
        <span>
            <svg width="72px" height="43px" viewBox="0 0 66 43" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<g id="arrow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <path class="one" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" fill="#FFFFFF"></path>
          <path class="two" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" fill="#FFFFFF"></path>
          <path class="three" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709797,42.8607841 C4.48259567,43.0519059 4.17082418,43.0519358 3.97628526,42.8608513 L0.154518591,39.1069479 C-0.0424848215,38.9134427 -0.0453206733,38.5968729 0.148184538,38.3998695 C0.150289256,38.3977268 0.152413239,38.395603 0.154556228,38.3934985 L16.9937789,21.8567812 C17.1908028,21.6632968 17.193672,21.3467273 17.0001876,21.1497035 C16.9980647,21.1475418 16.9959223,21.1453995 16.9937605,21.1432767 L0.15452076,4.60825197 C-0.0425130651,4.41477773 -0.0453986756,4.09820839 0.148075568,3.90117456 C0.150162624,3.89904911 0.152268631,3.89694235 0.154393339,3.89485454 Z" fill="#FFFFFF"></path>
        </g>
            </svg>
        </span> 
    </a>
    <a href="./kintai/kintaiReg.php" >
        <img src="./assets/images/main-12.jpg"  width="100%" height="200">
    </a>
</div>

				</div>
				<div class="col-md-4 text-center" style="margin-bottom: 5px;">
					<a href="./kyuka/kyukaReg.php"><img src="./assets/images/main-13.jpg" width="100%" height="200"></a>
				</div>
				<div class="col-md-4 text-center" style="margin-bottom: 5px;">
					<a href="./kintai/kintaiMonthly.php"><img src="./assets/images/main-15.jpg" width="100%" height="200"></a>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h1 class="panel-title">
						<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
						&nbsp;&nbsp;サイトお知らせ
					</h1>
				</div>
				<div class="panel-body">
					<?php
					if ($_SESSION['auth'] == false) {
						foreach ($notice_list as $notice) {
					?>
							<div class="media">
								<div class="media-left">
									<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);"><img class="media-object" width="150" src="./assets/uploads/notice/<?= $notice['imagefile'] ?>" alt="お知らせ"></a>
								</div>
								<div class="media-body">
									<h4 class="media-heading">
										<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);">
											<?= $notice['title']; ?>&nbsp;
											<?php
											$timestamp = strtotime($notice['reg_dt']);
											$currentTimestamp = time();
											$timeDifference = $currentTimestamp - $timestamp;
											$oneMonthInSeconds = 30 * 24 * 60 * 60;
											if ($timeDifference <= $oneMonthInSeconds) {
												echo '<span class="badge">New</span>';
											}
											?>
										</a>
									</h4>
									<?= $notice['content']; ?>
								</div>
							</div>
							<hr>
							<?php
						}
					} else {
						foreach ($notice_list as $notice) {
							if ($_SESSION['auth_type'] == constant('MAIN_ADMIN')) {
								$filename = $notice['imagefile'];
							?>
								<div class="media">
									<div class="media-left">
										<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);"><img class="media-object" width="150" src="./assets/uploads/notice/<?= $filename; ?>" alt="お知らせ"></a>
									</div>
									<div class="media-body">
										<h4 class="media-heading">
											<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);">
												<?= $notice['title']; ?>&nbsp;
												<?php
												$timestamp = strtotime($notice['reg_dt']);
												$currentTimestamp = time();
												$timeDifference = $currentTimestamp - $timestamp;
												$oneMonthInSeconds = 30 * 24 * 60 * 60;
												if ($timeDifference <= $oneMonthInSeconds) {
													echo '<span class="badge">New</span>';
												}
												?>
											</a>
										</h4>
										<?= $notice['content']; ?>
									</div>
								</div>
								<hr>
								<?php
							} else {
								$filename = $notice['imagefile'];
								if (strlen($filename) > 0 && $filename[0] === $_SESSION['auth_companyid']) {
								?>
									<div class="media">
										<div class="media-left">
											<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);"><img class="media-object" width="150" src="./assets/uploads/notice/<?= $filename; ?>" alt="お知らせ"></a>
										</div>
										<div class="media-body">
											<h4 class="media-heading">
												<a href="javascript:;" onclick="fn_showNotice(`<?= $notice['bid']; ?>`, `<?= $notice['imagenum']; ?>`, `<?= $notice['title']; ?>`, `<?= $notice['reader']; ?>`, `<?= $notice['name']; ?>`, `<?= $notice['viewcnt']; ?>`, `<?= $notice['reg_dt']; ?>`, `<?= $notice['content']; ?>`);">
													<?= $notice['title']; ?>&nbsp;
													<?php
													$timestamp = strtotime($notice['reg_dt']);
													$currentTimestamp = time();
													$timeDifference = $currentTimestamp - $timestamp;
													$oneMonthInSeconds = 30 * 24 * 60 * 60;
													if ($timeDifference <= $oneMonthInSeconds) {
														echo '<span class="badge">New</span>';
													}
													?>
												</a>
											</h4>
											<?= $notice['content']; ?>
										</div>
									</div>
									<hr>
					<?php
								}
							}
						}
					}
					?>
				</div>
			</div>
		</div>

		<!-- お知らせ   -->
		<div class="row">
			<div class="modal" id="modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
				<div class="modal-dialog">
					<form id="form_id" method="post">
						<div class="modal-content">
							<div class="modal-header">
								<div style="width:30px; float:left;"><img src="./assets/images/30_file.png" width="25" height="25"></div>
								<div style="float:left; margin-top:-7px;">
									<h4><span id="title"></span></h4>
								</div>
								<button class="close submit_viewcnt" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body">
								<div class="row">
									<div class="col-xs-12">
										<p id="content" style="background-color: #F8F9F9;"></p>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-xs-12">
										<label>確認者</label>
										<p id="reader" style="background-color: #F8F9F9;"></p>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-xs-4">
										<label>作成者</label>
										<input type="hidden" class="bid_" name="bid_">
										<input type="hidden" class="viewcnt_" name="viewcnt_">
										<p id="bid" class="bid_" style="display:none;"></p>
										<p id="name" style="background-color: #F8F9F9;"></p>
									</div>
									<div class="col-xs-4">
										<label>作成日</label>
										<p id="reg_dt" style="background-color: #F8F9F9;"></p>
									</div>
									<div class="col-xs-4">
										<label>view Cnt</label>
										<p id="viewcnt" class="viewcnt_" style="background-color: #F8F9F9;"></p>
									</div>
								</div>
								<div class="modal-footer" style="text-align: center">
									<button type="button" class="btn btn-default submit_viewcnt" data-dismiss="modal">閉じる</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>

<script>


	function fn_showNotice(bid, imagenum, title, reader, name, viewcnt, reg_dt, content) {
		var auth = '<?php echo $_SESSION['auth'] ?>';
		if (auth == "") {
			alert("<?php echo $login_is_not; ?>");
			return;
		} else {
			$('#modal').modal('toggle');
			$('#bid').text(bid);
			$('#title').text(title);
			$('#reader').text(reader);
			$('#content').text(content);
			$('#name').text(name);
			$('#reg_dt').text(reg_dt);
			$('#viewcnt').text(viewcnt);
		}
	}

	// Submit for viewcnt
	$(document).ready(function() {
		$(".submit_viewcnt").click(function() {
			var bidValue = $(".bid_").text();
			var viewcntValue = $(".viewcnt_").text();
			$(".bid_").val(bidValue);
			$(".viewcnt_").val(viewcntValue);
			$("#form_id").submit();
		});
	});
</script>

</html>