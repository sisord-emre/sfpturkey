<?php
include ("../../System/Config.php");
$sil=$_POST['sil'];

$logSilme = $db->get("Kategoriler", "*", [
  "Kategoriler" => $sil
]);
$fonk->logKayit(3,"Kategoriler".' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

unlink('../../../Images/Kategoriler/'.$logSilme["kategoriGorsel"]);

$silAltItem = $db->delete("KategoriDilBilgiler", [
  "kategoriDilBilgiId" => $sil
]);

$silItem = $db->delete("Kategoriler", [
  "kategoriId" => $sil
]);

if ($silItem) {
  echo "1";
}
?>
