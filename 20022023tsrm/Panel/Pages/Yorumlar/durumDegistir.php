<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];
$durum=$_POST['durum'];

$fonk->logKayit(2,"Yorumlar".' ; '.$Id.' ; '.json_encode(["yorumOnay" => $durum]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("Yorumlar",[
  "yorumOnay" => $durum
],[
  "yorumId" => $Id
]);

if ($query){
  echo '1';
}
?>
