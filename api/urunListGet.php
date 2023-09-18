<?php
include('../Panel/System/Config.php');

$Listeleme = $db->get("UrunListBotlari", "*", [
    "urunListBotTip" => 2,
    "ORDER" => [
        "urunListBotId" => "DESC"
    ]
]);

if($Listeleme["urunListBotDataJson"]){
    $data = json_decode($Listeleme["urunListBotDataJson"], true);
 
   

    $array_count = count($data["report"]["table"]["row"]);
    for($i=0; $i<$array_count; $i++)
    {
        echo "BLK KODU: ".$data["report"]["table"]["row"][$i]["BLKODU"]["__cdata"]."<br>";
        echo "STOK KODU: ".$data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"]."<br>";
        echo "STOK ADI: ".$data["report"]["table"]["row"][$i]["STOK_ADI"]["__cdata"]."<br>";
        echo "FIYAT: ".$data["report"]["table"]["row"][$i]["ENV_TUTARI"]["__cdata"]."<br>";
        echo "KDV: ".$data["report"]["table"]["row"][$i]["KDV_ORANI_SATIS_TPT"]["__cdata"]."<br>";
        echo "MIKTAR GIREN: ".$data["report"]["table"]["row"][$i]["MIKTAR_GIREN"]["__cdata"]."<br>";
        echo "MIKTAR CIKAN: ".$data["report"]["table"]["row"][$i]["MIKTAR_CIKAN"]["__cdata"]."<br>";
        echo "MIKTAR KALAN: ".$data["report"]["table"]["row"][$i]["MIKTAR_KALAN"]["__cdata"]."<br>";
        echo "-----------------------------------------------------------------------------"."<br>";
    }
    
}
else {
    echo "null";
}
