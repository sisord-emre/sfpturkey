<?php
include ("../../System/Config.php");

$dilDegerDilId=$_POST['dilDegerDilId'];

$sil = $db->delete("DilDegerleri", [
  "dilDegerDilId" => $dilDegerDilId
]);

$DilKeyler = $db->select("DilKeyler", "*");
$sayac=0;
foreach($DilKeyler as $list){
  $dilKeyId=$list["dilKeyId"];
  $deger= str_replace('"', '\'', $_POST['dilDegerYazi-'.$dilKeyId]);
  $ekle = $db->insert("DilDegerleri", [
    'dilDegerDilId' => $dilDegerDilId,
    'dilDegerKeyId' => $dilKeyId,
    'dilDegerYazi' => str_replace("\"","'",trim($deger))
  ]);
  if ($ekle) {
    $sayac++;
  }
}

if (Count($DilKeyler)==$sayac) {
  echo 1;
}else {
  echo (Count($DilKeyler)-$sayac).$fonk->getPDil(" Adet Eksik Girdi Bulunmaktadır.");
}
?>
