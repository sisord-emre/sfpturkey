<?php
include ("../../System/Config.php");

$fonk->csrfKontrol();
extract($_POST);//POST parametrelerini değişken olarak çevirir

$files = array();
foreach ($_FILES['gorseller'] as $k => $l) {
  foreach ($l as $i => $v) {
    if (!array_key_exists($i, $files))
    $files[$i] = array();
    $files[$i][$k] = $v;
  }
}
$sayac=0;
foreach ($files as $file) {//max 4 adete göre ayarlandı
  $sayac++;
  if($sayac<=20){
    $kontrol=$fonk->imageUpload($file,'../../Images/Temp/',uniqid(),jpg);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
  }else{
    break;
  }
}
echo 1;
?>
