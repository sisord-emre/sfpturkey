<?php
include ("../../System/Config.php");

$fonk->csrfKontrol();

$tableName="Yorumlar";

extract($_POST);//POST parametrelerini değişken olarak çevirir

$yorum = $db->get("Yorumlar", "*", [
  "yorumId" => $yorumUstYorumId
]);

$parametreler=array(
  'yorumKodu' => mt_rand(100000000,999999999),
  'yorumAdSoyad' => null,
  'yorumKaynakId' => $yorum["yorumKaynakId"],
  'yorumUyeId' => 0,
  'yorumUstYorumId' => $yorum["yorumId"],
  'yorumPuan' => null,
  'yorumEmail' => null,
  'yorumIcerik' => $yorumIcerik,
  'yorumOnay' => 1,
  'yorumDilId' => $yorum["yorumDilId"],
  'yorumOnayTarihi' => date("Y-m-d H:i:s"),
  'yorumKayitTarihi' => date("Y-m-d H:i:s")
);

$fonk->logKayit(1,$tableName.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
///ekleme
$query=$db->insert($tableName, $parametreler);

if ($query){
  echo '1';
}
?>
