<?php
include ("../../System/Config.php");

extract($_POST);//POST parametrelerini değişken olarak çevirir

$parametreler=array(
  'urunFiyat' => $fiyat,
  'urunKdv' => $kdv
);

$fonk->logKayit(2,"Urunler".' ; '.$Id.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
///güncelleme
$query = $db->update("Urunler", $parametreler, [
  "urunId" => $Id
]);
if($query){
  echo 1;
}
else {
  echo 2;
}
?>
