<?php
include("../Panel/System/Config.php");
header('Content-Type: application/json; charset=utf-8');

extract($_POST);

if($_SESSION['Sepet']=="" || $_SESSION['Sepet']=="null" || $_SESSION['Sepet']==null || !isset($_SESSION['Sepet'])){
    $_SESSION['Sepet']="[]";
}
$sepet=json_decode($_SESSION['Sepet'],true);
$json=array("status"=>"error","result"=>"");
$geciciSayac=0;
for ($i=0; $i <count($sepet) ; $i++) {
    if ($i!=$sira) {
        $geciciSepet[$geciciSayac]=$sepet[$i];
        $geciciSayac++;
    }else{
        $json["status"]="success";
    }
}

if ($_SESSION['uyeSessionKey'] != "") 
{
    $uye = $db->get("Uyeler", "*", [
        "uyeSessionKey" => $_SESSION['uyeSessionKey']
    ]);

	$enSonSiparis = $db->select("Siparisler","*",[
		"siparisOdemeTipiId" => 0,//yani herhangi bir ödeme tipi olmayan
        "siparisUyeId" =>$uye["uyeId"]
	]);
    foreach ($enSonSiparis as $key => $value) 
    { 
        $sil = $db->delete("Siparisler", [
            "siparisId" => $value["siparisId"]
        ]);
    
        $silIcerik = $db->delete("SiparisIcerikleri", [
            "siparisIcerikSiparisId" => $value["siparisId"]
        ]);
    }
    $_SESSION['SiparisKodu'] = "";
}

$_SESSION['Sepet']=json_encode($geciciSepet);
$json["result"]=array("sepet_adet"=>count($geciciSepet));
print_r(json_encode($json));
?>
