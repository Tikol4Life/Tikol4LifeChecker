<?php
//Script Author: ᴛɪᴋᴏʟ4ʟɪғᴇ https://t.me/Tikol4Life
	include 'config.php';
	
	if ($forceHttps) {
		if (!(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))	{
			$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			header('HTTP/1.1 301 Moved Permanently');
			header('Location: ' . $redirect);
			exit();
		}
	}

	if($forceAuth){
		session_start();
		if (isset($_SESSION["Auth"])) { 
			header("location: ./");
			exit();
		}
	}

	if (!isset($_COOKIE['checker_theme'])) {
		setcookie('checker_theme', 'dark', time() + (86400 * 30), "/");
		$theme_background = '#212121';
		$theme_text = '#FFFFFF';
		$theme_background_opp = '#FFFFFF';
		$theme_text_opp = '#000000';
	}else{
		if ($_COOKIE['checker_theme'] == 'dark') {
			$theme_background = '#212121';
			$theme_text = '#FFFFFF';
			$theme_background_opp = '#FFFFFF';
			$theme_text_opp = '#000000';
		}else{
			$theme_background = '#FFFFFF';
			$theme_text = '#000000';
			$theme_background_opp = '#212121';
			$theme_text_opp = '#FFFFFF';
		}
	}

	if(isset($_POST['auth'])){
		$authpass = $_POST['authpass'];
		if ($authpass == $AuthPass) {
			session_start();
			$_SESSION['Auth'] = 'allowed';
			header("location: ./");
			exit();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- BASIC DATA -->
	<meta charset="utf-8">
	<title><?php echo $site_name;?></title>
	<meta name="author" content="<?php echo $owner ?>">
	<link rel="icon" href="<?php echo $site_icon; ?>" type="image/png">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="<?php echo $owner ?> CC Checker">
	<!-- BOOTSTRAP -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css2?family=Gruppo&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="./assets/css/jquery.ambiance.css"/>
	<style type="text/css">
		.ambiance-custom {
			background: <?php echo $theme_background_opp;?>;
			color: <?php echo $theme_text_opp;?>;
			padding: 10px;
			border-radius: 5px;
			-moz-border-radius: 5px; /* Firefox 3.6 and earlier. */
			margin: 10px;
		}
		.ambiance:hover {
			border: 3px solid <?php echo $theme_background;?>;
		}
		.ambiance-close:hover {
			color: <?php echo $theme_background;?>;
			cursor: pointer;
		}
		.ambiance-close {
			display: block;
			position: relative;
			top: -2px;
			right: 0px;
			color: <?php echo $theme_text_opp;?>;
			float: right;
			font-size: 18px;
			font-weight: bold;
			filter: alpha(opacity=20);
			text-decoration: none;
			position: relative;
			line-height: 14px;
			margin-left: 5px;
			font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		}
		input[type=text]:focus,select[type=text]:focus,input[type=number]:focus,textarea[type=text]:focus{
			border: 1px solid #dc3545;
			-webkit-box-shadow: none;
			-moz-box-shadow: none;
			box-shadow: none;
		}
	</style>
</head>
<body style="background: <?php echo $theme_background ?>;">
	<div class="container" id="container">
		<!-- START OF IMAGE HEADER -->
		<div class="row justify-content-md-center">
			<div class="col-md">
				<center>
					<img class="rounded-circle" src="<?php echo $site_icon; ?>" width="200" height="200" style="margin-top: 40px;">
				</center>
			</div>
		</div>
		<!-- END OF IMAGE HEADER -->
		<!-- START OF FORMS -->
		<div class="row justify-content-md-center" style="margin-top: 40px;">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<form method="POST">
					<div class="form-group">
						<div class="input-group mb-3 ">
							<input type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>;" id="authpass" name="authpass" placeholder="Auth Pass">
						</div>
					</div>
					<button style="margin-top: 20px" type="submit" name="auth" class="btn btn-outline-danger btn-block">ACCESS</button>
				</form>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>
	
	<!-- BOOTSTRAP PLUGIN SCRIPTS-->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<!-- CHECKER PLUGIN SCRIPTS-->
	<script src="./assets/js/jquery.ambiance.js"></script>
	<script type="text/javascript">
		function lightmode(){
			setCookie('checker_theme', 'light', '30');
			location.reload();
		}
		function darkmode(){
			setCookie('checker_theme', 'dark', '30');
			location.reload();
		}
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays*24*60*60*1000));
			var expires = "expires="+ d.toUTCString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
	</script>
</body>
</html>
