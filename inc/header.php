<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="../assets/css/bootstrap.min.css">
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="../assets/fonts/glyphicons-halflings-regular.eot">
	<link rel="stylesheet" href="../assets/fonts/glyphicons-halflings-regular.ttf">
	<link rel="stylesheet" href="../assets/fonts/glyphicons-halflings-regular.woff">
	<link rel="stylesheet" href="../assets/fonts/glyphicons-halflings-regular.woff2">
	<!-- common Javascript -->
	<script type="text/javascript" src="../assets/js/common.js"> </script>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- Datepeeker 위한 link -->
	<link rel="stylesheet" href="../assets/css/jquery-ui.min.css">
	<script src="../assets/js/jquery-ui.min.js"></script>
	<!-- loading UX  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.css">

	<!-- common CSS -->
	<link rel="stylesheet" href="../assets/css/common.css">
</head>

<style>
	/* page data loading  loading UX  */
	#overlay {
		display: none;
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background-color: rgba(255, 255, 255, 0.3);
		z-index: 9999;
	}

	.loader {
		border: 4px solid #3498db;
		border-top: 4px solid transparent;
		border-radius: 50%;
		width: 50px;
		height: 50px;
		position: absolute;
		top: 50%;
		left: 50%;
		margin-top: -25px;
		margin-left: -25px;
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		0% {
			transform: rotate(0deg);
		}

		100% {
			transform: rotate(360deg);
		}
	}
</style>


<body>
	<div id="overlay">
		<div class="loader"></div>
	</div>
	<script>
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
			}, 1000);
		}
	</script>