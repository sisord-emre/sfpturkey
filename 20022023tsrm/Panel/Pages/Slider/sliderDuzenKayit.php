<?php
include ("../../System/Config.php");
$sliderDuzenList=explode(",",$_POST["sliderDuzenList"]);
$sayac=0;
foreach ($sliderDuzenList as $key => $value) {
  $sayac++;
  $fonk->logKayit(2,"Slider".' ; '.$value.' ; '.json_encode(['sliderSirasi' => $sayac]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

  $query = $db->update("Slider",[
    'sliderSirasi' => $sayac
  ],[
    "sliderId" => $value
  ]);
}

if ($query){
  echo '1';
}
?>
