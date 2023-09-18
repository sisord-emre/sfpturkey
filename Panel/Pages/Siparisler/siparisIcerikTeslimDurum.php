<?php
include ("../../System/Config.php");

$siparisIcerikId=$_POST['siparisIcerikId'];
$durumId=$_POST['durumId'];

$fonk->logKayit(2,"SiparisIcerikleri".' ; '.$siparisIcerikId.' ; '.json_encode(["siparisIcerikId" => $durumId]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer

$query = $db->update("SiparisIcerikleri",[
    "siparisIcerikTeslimatDurumu" => $durumId
],[
    "siparisIcerikId" => $siparisIcerikId
]);

if ($query){
    echo '1';
}
?>
