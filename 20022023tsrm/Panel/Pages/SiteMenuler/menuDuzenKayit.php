<?php
include ("../../System/Config.php");
$siteMenuDuzen=json_decode($_POST["siteMenuDuzen"],true);
$sayac1=0;
foreach ($siteMenuDuzen as $key => $value1) {
  $sayac1++;
  $query = $db->update("SiteMenuler",[
    "siteMenuUstMenuId" => 0,
    'siteMenuSirasi' => $sayac1
  ],[
    "siteMenuId" => $value1["id"]
  ]);
  $fonk->logKayit(2,"SiteMenuler".' ; '.$value1["id"].' ; '.json_encode(["siteMenuUstMenuId" => 0,'siteMenuSirasi' => $sayac1]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
  if (count($value1["children"])>0) {
    $sayac2=0;
    foreach ($value1["children"] as $key => $value2) {
      $sayac2++;
      $query = $db->update("SiteMenuler",[
        "siteMenuUstMenuId" => $value1["id"],
        'siteMenuSirasi' => $sayac2
      ],[
        "siteMenuId" => $value2["id"]
      ]);
      $fonk->logKayit(2,"SiteMenuler".' ; '.$value2["id"].' ; '.json_encode(["siteMenuUstMenuId" => $value1["id"],'siteMenuSirasi' => $sayac2]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    }
  }
}

if ($query){
  echo '1';
}
?>
