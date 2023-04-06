<?php
include ("../../System/Config.php");
$sil=$_POST['sil'];

$logSilme = $db->get("FooterMenuler", "*", [
  "footerMenuId" => $sil
]);
$fonk->logKayit(3,"FooterMenuler".' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$silAltMenu = $db->delete("FooterMenuler", [
  "footerMenuUstMenuId" => $sil
]);

$silMenu = $db->delete("FooterMenuler", [
  "footerMenuId" => $sil
]);

if ($silMenu) {
  echo "1";
}
?>
