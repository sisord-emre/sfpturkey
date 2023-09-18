<?php
include ("../../System/Config.php");
$duzenList=explode(",",$_POST["duzenList"]);
$sayac=0;
foreach ($duzenList as $key => $value) {
  $sayac++;
  $fonk->logKayit(2,"UrunGorselleri".' ; '.$value.' ; '.json_encode(['urunGorselSirasi' => $sayac]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

  $query = $db->update("UrunGorselleri",[
    'urunGorselSirasi' => $sayac
  ],[
    "urunGorselId" => $value
  ]);
}

if ($query){
  echo '1';
}
?>
