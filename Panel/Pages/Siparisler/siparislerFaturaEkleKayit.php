<?php
include ("../../System/Config.php");

$fonk->csrfKontrol();

$tableName="Siparisler";

extract($_POST);//POST parametrelerini değişken olarak çevirir

$parametreler=array(
    'siparisFaturaBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/Fatura/"
);

$files = array_filter($_FILES['siparisFatura']['name']); 
$fileName = mt_rand();
$tmpFilePath = $_FILES['siparisFatura']['tmp_name'];
if ($tmpFilePath != "")
{
    $newFilePath = "../../../Images/Fatura/".$fileName .".pdf";
    if(move_uploaded_file($tmpFilePath, $newFilePath)) 
    {
        $parametreler=array_merge($parametreler,array('siparisFatura' => $fileName. ".pdf"));
    }		
}

$fonk->logKayit(2,$tableName.' ; '.$siparisId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
///güncelleme
$query = $db->update($tableName, $parametreler, [
    "siparisId" => $siparisId
]);


if ($query){
    echo '1';
}
?>
