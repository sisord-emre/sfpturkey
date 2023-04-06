<?php
include("../Panel/System/Config.php");
header('Content-Type: application/json; charset=utf-8');

extract($_POST);

if($_SESSION['Sepet']=="" || $_SESSION['Sepet']=="null" || $_SESSION['Sepet']==null || !isset($_SESSION['Sepet']))
{
    $_SESSION['Sepet']="[]";
}
$sepet=json_decode($_SESSION['Sepet'],true);
$varmi=false;
$json=array("status"=>"error","result"=>"");
for ($i=0; $i <count($sepet) ; $i++)
{
    if ($sepet[$i]["urunId"]==$urunId && $sepet[$i]["varyantId"]==$varyantId)
    {
        $sepet[$i]["adet"]=$sepet[$i]["adet"]+$adet;
        $varmi=true;
        $json["status"]="success";
    }
}

if (!$varmi)
{
    array_push($sepet,array("urunId"=>$urunId,"varyantId"=>$varyantId,"adet"=>$adet));
    $json["status"]="success";
}
$_SESSION['Sepet']=json_encode($sepet);
$json["result"]=array("sepet_adet"=>count($sepet));
print_r(json_encode($json));
?>
