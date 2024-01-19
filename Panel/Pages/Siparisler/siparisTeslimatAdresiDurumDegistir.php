<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];
$durum=$_POST['durum'];

$fonk->logKayit(2,"Siparisler".' ; '.$Id.' ; '.json_encode(["siparisTeslimatUyeAdresId" => $durum]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("Siparisler",[
  "siparisTeslimatUyeAdresId" => $durum
],[
  "siparisId" => $Id
]);

if ($query){
  echo '1,'.$Id;
}
?>
