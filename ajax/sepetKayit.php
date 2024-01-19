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

// echo "<pre>"; 
// print_r($sepet);
// echo"</pre>";

$isStockControl = $db->get("Urunler", [
    "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
    "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
    "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
],"*",[
    "urunVaryantDilBilgiVaryantId" => $urunId,
    "ORDER" => [
        "urunId" => "ASC"
    ]
]);


$kontrol = ($adet <= $isStockControl["urunStok"] ? '1' : '0');

for ($i=0; $i <count($sepet) ; $i++)
{
    $urunIdBulma = $db->get("Urunler", [
        "[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
        "[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
        "[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
    ],"*",[
        "urunVaryantDilBilgiVaryantId" => $urunId,
        "ORDER" => [
            "urunId" => "ASC"
        ]
    ]);

    if($urunIdBulma["urunId"] == $isStockControl["urunId"])
    {
        $adetSayisi=$sepet[$i]["adet"]+$adet;
    }
    else 
    {
        $adetSayisi=$sepet[$i]["adet"]+$adet;
    }
    $kontrol = ($adetSayisi <= $isStockControl["urunStok"] ? '1' : '0');
    if($kontrol == 1)
    {
        if ($sepet[$i]["urunId"]==$urunId && $sepet[$i]["varyantId"]==$varyantId)
        {
            $sepet[$i]["adet"]=$sepet[$i]["adet"]+$adet;
            $varmi=true;
            $json["status"]="success";
        }
    }
}


if($isStockControl["urunStok"] > 0) 
{
    if($kontrol == 1)
    {
        if (!$varmi)
        {
            array_push($sepet,array("urunId"=>$urunId,"varyantId"=>$varyantId,"adet"=>$adet));
            $json["status"]="success";
        }
    }
    else 
    {
        $json["status"]="stockNotFound";
    }
}
else 
{
    $json["status"]="stockNotFound";
}

$_SESSION['Sepet']=json_encode($sepet);
$json["result"]=array("sepet_adet"=>count($sepet));
print_r(json_encode($json));
?>
