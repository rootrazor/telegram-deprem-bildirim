<?php

//coded by rootRaZoR


date_default_timezone_set("Europe/Istanbul");


$sunucu = "http://www.koeri.boun.edu.tr/scripts/lst9.asp";
$tarih = date("Y-m-d", strtotime("yesterday"));
 
$dosya       = file_get_contents($sunucu);
$baslama   = stristr($dosya,"--------------");
$bitir       = strpos($baslama,"</pre>");
$depremal   = substr($baslama,0,$bitir);
$depremal   = str_replace("--------------", "", $depremal);
file_put_contents("depremverileri/" . date("Y-m-d") . "-sondepremler.json", $depremal);
$veriler = file("depremverileri/" . date("Y-m-d") . "-sondepremler.json");

$dosyaismi = "depremverileri/" . $tarih . "-sondepremler.txt";

$devam = false;

foreach($veriler as $deprem) {   
    $deprem = preg_replace('!\s+!', ' ', $deprem);

    if($deprem != ""){
        $parcala = explode(" ", $deprem);
        if(count($parcala) == 12){
            if($parcala[0] == date("Y.m.d", strtotime("yesterday"))){               
                $icerik = "Tarih: " . $parcala[0] . " " . $parcala[1] . "\t Şiddet: " . $parcala[6] . "\t Şehir: " . str_replace(array("(", ")"),"",$parcala[9]) . " {" . $parcala[8] . "} \n";
                file_put_contents($dosyaismi, $icerik, FILE_APPEND);
                $devam = true;
            }
        }
    }
}

if($devam){
    
   $linkimiz = "https://api.telegram.org/bot" . $anahtarapi . "/sendMessage?chat_id=" . $sohbetnumarasi;
   $linkimiz = $linkimiz . "&text=" . urlencode($mesaj);
   $verigonder = curl_init();
   $deger = array(
            CURLOPT_URL => $linkimiz,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($verigonder, $deger);
    $sonuc = curl_exec($verigonder);
    curl_close($verigonder);
	
$anahtarapi = "telegram api anahtariniz";
$sohbetnumarasi = "numaranizi girin";
$mesaj = "Merhaba Son Depremler Listesi Günlük $devam";
	
}

