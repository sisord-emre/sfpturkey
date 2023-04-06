<?php
if ($_POST) {
  include "../System/Config.php";
  $db->insert("JsHatalar", [
    'jsHataUrl' => $_POST["url"],
    'jsHataSatir' => $_POST["satir"],
    'jsHatasi' => $_POST["hata"],
    'jsHataKullaniciId' => intval($kulBilgi['kullaniciId']),
    'jsHataKayitTarihi' => date("Y-m-d H:i:s")
  ]);
}
?>
