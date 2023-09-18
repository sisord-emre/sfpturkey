<?php
include ("../../System/Config.php");
$sil=$_POST['sil'];

$logSilme = $db->get("SiteMenuler", "*", [
  "siteMenuId" => $sil
]);
$fonk->logKayit(3,"SiteMenuler".' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$silAltMenu = $db->delete("SiteMenuler", [
  "siteMenuUstMenuId" => $sil
]);

$silMenu = $db->delete("SiteMenuler", [
  "siteMenuId" => $sil
]);

if ($silMenu) {
  echo "1";
}
?>
