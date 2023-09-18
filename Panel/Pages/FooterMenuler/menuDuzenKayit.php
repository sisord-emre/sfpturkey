<?php
include ("../../System/Config.php");
$footerMenuDuzen=json_decode($_POST["footerMenuDuzen"],true);
$sayac1=0;
foreach ($footerMenuDuzen as $key => $value1) {
  $sayac1++;
  $query = $db->update("FooterMenuler",[
    "footerMenuUstMenuId" => 0,
    'footerMenuSirasi' => $sayac1
  ],[
    "footerMenuId" => $value1["id"]
  ]);
  $fonk->logKayit(2,"FooterMenuler".' ; '.$value1["id"].' ; '.json_encode(["footerMenuUstMenuId" => 0,'footerMenuSirasi' => $sayac1]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
  if (count($value1["children"])>0) {
    $sayac2=0;
    foreach ($value1["children"] as $key => $value2) {
      $sayac2++;
      $query = $db->update("FooterMenuler",[
        "footerMenuUstMenuId" => $value1["id"],
        'footerMenuSirasi' => $sayac2
      ],[
        "footerMenuId" => $value2["id"]
      ]);
      $fonk->logKayit(2,"FooterMenuler".' ; '.$value2["id"].' ; '.json_encode(["footerMenuUstMenuId" => $value1["id"],'footerMenuSirasi' => $sayac2]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    }
  }
}

if ($query){
  echo '1';
}
?>
