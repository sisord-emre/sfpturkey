<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];
$durum=$_POST['durum'];

$fonk->logKayit(2,"IletisimFormlari".' ; '.$Id.' ; '.json_encode(["iletisimFormDurum" => $durum]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("IletisimFormlari",[
  "iletisimFormDurum" => $durum
],[
  "iletisimFormId" => $Id
]);

if ($query){
  echo '1';
}
?>
