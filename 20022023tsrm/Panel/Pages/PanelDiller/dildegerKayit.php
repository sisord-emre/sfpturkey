<?php
include ("../../System/Config.php");

$panelDilDegerDilId=$_POST['panelDilDegerDilId'];

$sil = $db->delete("PanelDilDegerleri", [
  "panelDilDegerDilId" => $panelDilDegerDilId
]);

$PanelDilKeyler = $db->select("PanelDilKeyler", "*");
$sayac=0;
foreach($PanelDilKeyler as $list){
  $panelDilKeyId=$list["panelDilKeyId"];
  $deger= str_replace('"', '\'', $_POST['panelDilDegerYazi-'.$panelDilKeyId]);
  $ekle = $db->insert("PanelDilDegerleri", [
    'panelDilDegerDilId' => $panelDilDegerDilId,
    'panelDilDegerKeyId' => $panelDilKeyId,
    'panelDilDegerYazi' => str_replace("\"","'",trim($deger))
  ]);
  if ($ekle) {
    $sayac++;
  }
}

if (Count($PanelDilKeyler)==$sayac) {
  echo 1;
}else {
  echo (Count($PanelDilKeyler)-$sayac).$fonk->getPDil(" Adet Eksik Girdi Bulunmaktadır.");
}
?>
