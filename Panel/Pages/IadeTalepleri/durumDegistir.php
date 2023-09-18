<?php
include ("../../System/Config.php");

$Id=$_POST['Id'];
$durum=$_POST['durum'];

$fonk->logKayit(2,"IadeTalepleri".' ; '.$Id.' ; '.json_encode(["iadeTalepDurumu" => $durum]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("IadeTalepleri",[
  "iadeTalepDurumu" => $durum
],[
  "iadeTalepId" => $Id
]);

if ($query){
  echo '1';
}
?>
