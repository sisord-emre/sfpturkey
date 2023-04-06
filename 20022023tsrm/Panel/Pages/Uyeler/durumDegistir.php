<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];
$durum=$_POST['durum'];

$fonk->logKayit(2,"Uyeler".' ; '.$Id.' ; '.json_encode(["uyeDurum" => $durum]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("Uyeler",[
  "uyeDurum" => $durum
],[
  "uyeId" => $Id
]);

if ($query){
  echo '1';
}
?>
