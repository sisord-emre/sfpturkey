<?php
require '../System/Config.php';

$tipi=$_POST["tipi"];
$exportBasliklar=$_POST["exportBasliklar"];

if($sabitB['sabitBilgiLog']==1){
	///Loglama İşlemi
	$fonk->logKayit(5,$tipi.': ; '.$_SESSION["excelTablo"].' ; '.json_encode($exportBasliklar));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}
?>
