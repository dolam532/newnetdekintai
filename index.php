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
					<a href="./kintai/kintaiReg.php"><img src="./assets/images/main-12.jpg" width="100%" height="200"></a>
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
					if (!empty($notice_list)) {
						foreach ($notice_list as $notice) {
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
											<?= $notice['title']; ?>&nbsp;<span class="badge">New</span>
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