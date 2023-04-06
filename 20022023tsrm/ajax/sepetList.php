<?php
include("../Panel/System/Config.php");
header('Content-Type: application/json; charset=utf-8');

extract($_POST);

if($_SESSION['Sepet']=="" || $_SESSION['Sepet']=="null" || $_SESSION['Sepet']==null || !isset($_SESSION['Sepet'])){
    $_SESSION['Sepet']="[]";
}
$sepet=json_decode($_SESSION['Sepet'],true);
for ($i=0; $i <count($sepet) ; $i++) {
    //echo $sepet[$i]["urunId"]." / ".$sepet[$i]["varyantId"]." / ".$sepet[$i]["adet"]."\n";
}
$json=array("status"=>"success", "result"=>$sepet);
print_r(json_encode($json));
?>
