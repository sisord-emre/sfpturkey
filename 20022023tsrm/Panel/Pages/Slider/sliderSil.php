<?php
include ("../../System/Config.php");
$sil=$_POST['sil'];

$logSilme = $db->get("Slider", "*", [
  "sliderId" => $sil
]);
$fonk->logKayit(3,"Slider".' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$silSlider = $db->delete("Slider", [
  "sliderId" => $sil
]);

if ($silSlider) {
  echo "1";
}
?>
