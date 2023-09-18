<?php
include ("../../System/Config.php");

$tableName="SiparisSiparisDurumlari";
$Id=$_POST['Id'];

if($sabitB['sabitBilgiLog']==1){
    $logSilme = $db->get($tableName, "*", [
        "siparisSiparisDurumId" => $Id
    ]);
    $fonk->logKayit(3,$tableName.' ; '.json_encode($logSilme));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}

$sil = $db->delete($tableName, [
    "siparisSiparisDurumId" => $Id
]);

if ($sil){
    echo '1';
}else{
    echo '0';
}
?>
