<?php
include ("../../System/Config.php");
$sil=$_POST['sil'];

$logSilme = $db->get("UrunGorselleri", "*", [
  "urunGorselId" => $sil
]);
$fonk->logKayit(3,"UrunGorselleri".' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

unlink("../../../Images/Urunler/".$logSilme["urunGorselLink"]);

$silItem = $db->delete("UrunGorselleri", [
  "urunGorselId" => $sil
]);

if ($silItem) {
  echo "1";
}
?>
