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

$_SESSION['Sepet']=json_encode($geciciSepet);
$json["result"]=array("sepet_adet"=>count($geciciSepet));
print_r(json_encode($json));
?>
