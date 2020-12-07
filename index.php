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
		if (!isset($_SESSION["Auth"])) { 
			header("location: ./auth");
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
<body onload="ccgen();  onLoadChks();"style="background: <?php echo $theme_background ?>;">
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
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form>
					<div class="form-group">
						<label for="cards" style="color: <?php echo $theme_text ?>; margin-left: 20px">Generated Cards</label>
						<textarea type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>;overflow:hidden" id="cards" rows="5" placeholder="xxxxxxxxxxxxxxxx|xx|xxxx|xxx" required></textarea>
					</div>
					<div class="form-group">
						<label for="sk" style="color: <?php echo $theme_text ?>; margin-left: 20px">API</label>
						<div class="input-group mb-3 ">
							<select class="form-control" type="text" id="api" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>;" onchange="showDiv('hidden_div', this)">
								<?php for ($i=0; $i < count($api_file); $i++) {
								echo '<option style="background:'.$theme_background.'" value="'.$api_file[$i].'">'.$api_name[$i].'</option>';
								}?>	
							</select>
							<div class="input-group-append">
								<button class="btn btn-outline-danger" type="button" onclick="Settings();">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" d="M8.837 1.626c-.246-.835-1.428-.835-1.674 0l-.094.319A1.873 1.873 0 0 1 4.377 3.06l-.292-.16c-.764-.415-1.6.42-1.184 1.185l.159.292a1.873 1.873 0 0 1-1.115 2.692l-.319.094c-.835.246-.835 1.428 0 1.674l.319.094a1.873 1.873 0 0 1 1.115 2.693l-.16.291c-.415.764.42 1.6 1.185 1.184l.292-.159a1.873 1.873 0 0 1 2.692 1.116l.094.318c.246.835 1.428.835 1.674 0l.094-.319a1.873 1.873 0 0 1 2.693-1.115l.291.16c.764.415 1.6-.42 1.184-1.185l-.159-.291a1.873 1.873 0 0 1 1.116-2.693l.318-.094c.835-.246.835-1.428 0-1.674l-.319-.094a1.873 1.873 0 0 1-1.115-2.692l.16-.292c.415-.764-.42-1.6-1.185-1.184l-.291.159A1.873 1.873 0 0 1 8.93 1.945l-.094-.319zm-2.633-.283c.527-1.79 3.065-1.79 3.592 0l.094.319a.873.873 0 0 0 1.255.52l.292-.16c1.64-.892 3.434.901 2.54 2.541l-.159.292a.873.873 0 0 0 .52 1.255l.319.094c1.79.527 1.79 3.065 0 3.592l-.319.094a.873.873 0 0 0-.52 1.255l.16.292c.893 1.64-.902 3.434-2.541 2.54l-.292-.159a.873.873 0 0 0-1.255.52l-.094.319c-.527 1.79-3.065 1.79-3.592 0l-.094-.319a.873.873 0 0 0-1.255-.52l-.292.16c-1.64.893-3.433-.902-2.54-2.541l.159-.292a.873.873 0 0 0-.52-1.255l-.319-.094c-1.79-.527-1.79-3.065 0-3.592l.319-.094a.873.873 0 0 0 .52-1.255l-.16-.292c-.892-1.64.902-3.433 2.541-2.54l.292.159a.873.873 0 0 0 1.255-.52l.094-.319z"/>
										<path fill-rule="evenodd" d="M8 5.754a2.246 2.246 0 1 0 0 4.492 2.246 2.246 0 0 0 0-4.492zM4.754 8a3.246 3.246 0 1 1 6.492 0 3.246 3.246 0 0 1-6.492 0z"/>
										</svg>
								</button>
								<button class="btn btn-outline-danger" type="button" onclick="modalCCGEN();">CC Gen</button>
							</div>
						</div>
						
					</div>
					<div class="form-group" id="hidden_div" style="display:none;">
						<label for="sk" style="color: <?php echo $theme_text ?>; margin-left: 20px">Stripe Secret Key (SK)</label>
						<div class="input-group mb-3">
							<input type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>;" id="sk" aria-describedby="sk" placeholder="sk_live_xxxxxxxxxxxxxxxxxx" value="<?php if(isset($_COOKIE['sk_stored'])){echo $_COOKIE['sk_stored'];}?>">
							<div class="input-group-append">
								<button class="btn btn-outline-danger" type="button" onclick="copySK();">
									<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-clipboard" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  										<path fill-rule="evenodd" d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
  										<path fill-rule="evenodd" d="M9.5 1h-3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
									</svg>
								</button>
								<button class="btn btn-outline-danger" type="button" onclick="checkSK();">CHECK SK</button>
							</div>
						</div>
					</div>
					<button style="margin-top: 20px" type="button" class="btn btn-outline-danger btn-block" onclick="checkCards();">CHECK CARDS</button>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row justify-content-md-center" style="margin-top: 40px;">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form>
					<div class="form-group">
						<div class="row">
							<div class="col-4">
								<button id="cardsLiveCVV" name="cardsLiveCVV" type="button" class="btn btn-outline-primary btn-block">CVV : <span id="approved_counter_cvv">0</span></button>
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-12 col-sm-8">
										<button type="button" style="margin-top: 10px;" name="clear_cvv" id="clear_cvv" class="btn btn-danger btn-sm btn-block" onclick="clearCVV();">Clear CVV</button>
									</div>
								</div>
							</div>
							<div class="col-4">
								<button id="cardsLiveCCN" name="cardsLiveCCN" type="button" class="btn btn-outline-warning btn-block">CCN : <span id="approved_counter_ccn">0</span></button>
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-12 col-sm-8">
										<button type="button" style="margin-top: 10px;" name="clear_ccn" id="clear_ccn" class="btn btn-danger btn-sm btn-block" onclick="clearCCN();">Clear CCN</button>
									</div>
								</div>
							</div>
							<div class="col-4">
								<button id="cardsDead" name="cardsDead" type="button" class="btn btn-outline-danger btn-block">DEAD : <span id="decline_counter">0</span></button>
								<div class="row">
									<div class="col-sm-4"></div>
									<div class="col-12 col-sm-8">
										<button type="button" style="margin-top: 10px;" name="clear_dead" id="clear_dead" class="btn btn-danger btn-sm btn-block" onclick="clearDead();">Clear DEAD</button>
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</form>
			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row justify-content-md-center" >
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<form>
					<div class="form-group" style="margin-left: 40px;margin-right: 40px;">
						<div class="results" id="results">
						</div>
					</div>
				</form>

			</div>
			<div class="col-md-2"></div>
		</div>
		<div class="row justify-content-md-center">
			<div class="col-md-2"></div>
			<div class="col-md-8 col-12">
				<div class="row">
					<div class="col-md-3"></div>
					<div class="col-md-6 col-12">
						<center>
							<?php 
								if (!isset($_COOKIE['checker_theme'])) {
									echo '<button type="button" onclick="lightmode();" class="btn btn-outline-light">Light Mode</button>';
								}else{
									if($_COOKIE['checker_theme'] == 'dark'){
										echo '<button type="button" onclick="lightmode();" class="btn btn-outline-light">Light Mode</button>';
									}else{
										echo '<button type="button" onclick="darkmode();" class="btn btn-outline-dark">Dark Mode</button>';
									}
								}
							?>
						</center>
						
					</div>
					<div class="col-md-3"></div>
				</div>
				
			</div>
			<div class="col-md-2"></div>
		</div>
		<!-- END OF FORMS -->
		
		<div class="footer" id="footer"><center><p style="color: <?php echo $theme_text ?>">Tikol4Life</p></center></div>
	</div>
	<!-- START OF CCGEN MODAL -->
	<div class="modal fade" id="ccGEN" role="dialog" aria-hidden="true" >
		<div class="modal-dialog modal-dialog-centered"  style="background: transparent;">
			<div class="modal-content" style="background: transparent;">
				<div class="modal-body" style="background: <?php echo $theme_background ?>">
					<center style="margin-bottom: 20px">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true" style="color: <?php echo $theme_text ?>">&times;</span>
						</button>
						<img class="rounded-circle" src="<?php echo $site_icon; ?>" width="200" height="200" style="margin-top: 10px;margin-bottom: 20px;" >
						<h5 class="modal-title" id="exampleModalCenterTitle" style="color: <?php echo $theme_text ?>">CC Generator</h5>
					</center>
					<form name="console" id="console" role="form" method="POST">
						<div>
							<div class="row">
								<div class="col-8 col-lg-8">
									<div class="form-group">
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="inputbin">BIN</label>
										<input id="ccpN" name="ccp" maxlength="19" type="text" id="inputbin" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" placeholder="xxxx xxxx xxxx xxxx">
									</div>
								</div>
								<div class="col-4 col-lg-4">
									<div class="form-group">
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="inputcvv">CVV</label>
										<input type="text" id="eccv" name="eccv" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" class="form-control" placeholder="rnd" value="rnd">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-4 col-lg-4">
									<div class="form-group">
										<select type="text" name="ccoudatfmt" class="input_text" style="display:none;">
											<option value="CHECKER" selected="selected">CHK</option>
											<option value="CSV">CSV</option>
											<option value="XML">XML</option>
											<option value="JSON">JSON</option>
										</select>
										<input type="hidden" name="tr" value="2000">
										<input type="hidden" name="L" style="width: 20px" value="1L">
										<div type="hidden" id="bininfo" align="center"></div>
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="inputmonth">MONTH</label>
										<select type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" name="emeses">
											<option style="background: <?php echo $theme_background ?>" value="rnd">RANDOM  </option>
											<option style="background: <?php echo $theme_background ?>" value="01">01 - JAN</option>
											<option style="background: <?php echo $theme_background ?>" value="02">02 - FEB</option>
											<option style="background: <?php echo $theme_background ?>" value="03">03 - MAR</option>
											<option style="background: <?php echo $theme_background ?>" value="04">04 - APR</option>
											<option style="background: <?php echo $theme_background ?>" value="05">05 - MAY</option>
											<option style="background: <?php echo $theme_background ?>" value="06">06 - JUN</option>
											<option style="background: <?php echo $theme_background ?>" value="07">07 - JUL</option>
											<option style="background: <?php echo $theme_background ?>" value="08">08 - AUG</option>
											<option style="background: <?php echo $theme_background ?>" value="09">09 - SEP</option>
											<option style="background: <?php echo $theme_background ?>" value="10">10 - OCT</option>
											<option style="background: <?php echo $theme_background ?>" value="11">11 - NOV</option>
											<option style="background: <?php echo $theme_background ?>" value="12">12 - DEC</option>
										</select>
									</div>
								</div>
								<div class="col-4 col-lg-4">
									<div class="form-group">
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="inputyear">YEAR</label>
										<select type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" name="eyear">
											<option style="background: <?php echo $theme_background ?>; " value="rnd">RANDOM</option>
											<option style="background: <?php echo $theme_background ?>" value="2021">2021</option>
											<option style="background: <?php echo $theme_background ?>" value="2022">2022</option>
											<option style="background: <?php echo $theme_background ?>" value="2023">2023</option>
											<option style="background: <?php echo $theme_background ?>" value="2024">2024</option>
											<option style="background: <?php echo $theme_background ?>" value="2025">2025</option>
											<option style="background: <?php echo $theme_background ?>" value="2026">2026</option>
											<option style="background: <?php echo $theme_background ?>" value="2027">2027</option>
											<option style="background: <?php echo $theme_background ?>" value="2028">2028</option>
											<option style="background: <?php echo $theme_background ?>" value="2029">2029</option>
											<option style="background: <?php echo $theme_background ?>" value="2030">2030</option>
										</select>
									</div>
								</div>
								<div class="col-4  col-lg-4">
									<div class="form-group">
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="inputquantity">QUANTITY</label>
										<input type="number" name="ccghm" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" maxlength="4" class="form-control" value="10">
										<select type="text" name="ccnsp" class="input_text" style="display:none;">
											<option selected="selected">None</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<button type="button" style="margin-right: 20px;margin-left: 20px;" class="btn btn-outline-danger btn-block"  name="gerar" id="gerar" onclick="playClick();">GENERATE</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- END OF CCGEN MODAL -->
	<!-- START OF TEMPLATE MODAL -->
	<div class="modal fade" id="Modal" data-keyboard="false" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered">
			<div class="modal-content" style="background: <?php echo $theme_background ?>">
				<div class="modal-body" style="background: <?php echo $theme_background ?>">
					<center style="margin-bottom: 20px">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true" style="color: <?php echo $theme_text ?>">&times;</span>
						</button>
						<img class="rounded-circle" src="<?php echo $site_icon; ?>" width="200" height="200" style="margin-top: 10px;margin-bottom: 20px;" >
						<h5 class="modal-title" id="ModalTitle" style="color: <?php echo $theme_text ?>"></h5>
						<span id="ModalMsg" style="color: <?php echo $theme_text ?>;margin-top: 20px"></span>
					</center>
				</div>
			</div>
		</div>
	</div>
	<!-- END OF TEMPLATE MODAL -->
	<!-- START OF SETTINGS MODAL -->
	<div class="modal fade" id="settingsModal" role="dialog" aria-hidden="true" >
		<div class="modal-dialog modal-dialog-centered"  style="background: transparent;">
			<div class="modal-content" style="background: transparent;">
				<div class="modal-body" style="background: <?php echo $theme_background ?>">
					<center style="margin-bottom: 20px">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true" style="color: <?php echo $theme_text ?>">&times;</span>
						</button>
						<img class="rounded-circle" src="<?php echo $site_icon; ?>" width="200" height="200" style="margin-top: 10px;margin-bottom: 20px;" >
						<h5 class="modal-title" id="exampleModalCenterTitle" style="color: <?php echo $theme_text ?>">Additional Settings</h5>
					</center>

					<form name="settingForm" id="settingForm" role="form" method="POST">
						<div>
							<div class="row">
								<div class="col-12">
									<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="telebot">TELEGRAM FORWARDER</label>
									<div class="input-group mb-3">
										<input name="telebot" type="text" id="telebot" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" placeholder="Chat ID" value="<?php if(isset($_COOKIE['chatID_stored'])){echo $_COOKIE['chatID_stored'];}?>">
										<div class="input-group-append">
											<button class="btn btn-outline-danger" type="button" onclick="howto();" >
												<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-question" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
													<path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM8.05 9.6c.336 0 .504-.24.554-.627.04-.534.198-.815.847-1.26.673-.475 1.049-1.09 1.049-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.71 1.71 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745z"/>
													<path fill-rule="evenodd" d="M10.273 2.513l-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/>
												</svg>
											</button>
											<button class="btn btn-outline-danger" type="button" onclick="testBot();" >
												<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-patch-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" d="M10.273 2.513l-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/>
													<path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
												</svg>
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6 col-lg-6">
									<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="tele_msg">MESSAGE</label>
									<select type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" id="tele_msg" >
										<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="3">CVV & CCN</option>
										<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="1">CVV Only</option>
										<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="2">CCN Only</option>
									</select>
								</div>
								<div class="col-6 col-lg-6">
									<div class="form-group">
										<label class="form-control-label" style="margin-left: 10px;color: <?php echo $theme_text ?>" for="delay">DELAY</label>
										<select type="text" class="form-control" style="border-color: #dc3545;background: transparent;color: <?php echo $theme_text ?>" id="delay" >
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="0">No Delay</option>
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="200">0.2 Sec</option>
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="500">0.5 Sec</option>
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="1000" selected> 1  Sec</option>
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="2000"> 2  Sec</option>
											<option style="background: <?php echo $theme_background ?>;color: <?php echo $theme_text ?>" value="3000"> 3  Sec</option>
										</select>
									</div>
								</div>
							</div>
						</div>
					</form>
					<center>
						<h6 id="TeleMsg" style="color: <?php echo $theme_text ?>;margin-top: 20px"></h6>
					</center>
					<div name="howto" id="howto">
						<center >
							<h5 class="modal-title" id="exampleModalCenterTitle" style="color: <?php echo $theme_text ?>">How To Use:</h5>
							<h6 style="color: <?php echo $theme_text ?>;">[1] Open our Telegram Bot: <a href="https://t.me/OppaTikoleroBot" target="_blank">@OppaTikoleroBot</a>.

							</h6>
							<h6 style="color: <?php echo $theme_text ?>;">[2] Copy-Paste the Chat ID given by the bot.</h6>
							<h6 style="color: <?php echo $theme_text ?>;">[3] Test the forwarder to check if it works.</h6>
						</center>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END OF SETTINGS MODAL -->
	<!-- BOOTSTRAP PLUGIN SCRIPTS-->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<!-- CHECKER PLUGIN SCRIPTS-->
	<script src="./assets/js/Tikol4Life.js"></script>
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
