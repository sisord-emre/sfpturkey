<?php
require '../System/Config.php';

$ExportData=$_SESSION["excel"];

if($sabitB['sabitBilgiLog']==1 && $_SESSION["excel"]!=""){
	///Loglama İşlemi
	$fonk->logKayit(5,'Excel: ; '.$_SESSION["excelTablo"].' ; '.json_encode($ExportData[0]));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
}

$xls = new Excel_XML('UTF-8', false, 'Excel');
$xls->addArray($ExportData);
$xls->generateXML($_SESSION["excelTablo"].'_'.date("Y-m-d H:i:s"));

$_SESSION["excel"]="";
$_SESSION["excelTablo"]="";
?>
