<?php
include('../Panel/System/Config.php');
if ($_GET["ApiKey"] == "8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp") {
    $Listeleme = $db->get("UrunListBotlari", "*",[
        "urunListBotTip" => 2, //stok envanter
        "ORDER" => [
            "urunListBotId" => "DESC",
        ]
    ]);

    if($Listeleme["urunListBotDataJson"])
    {
        $data = json_decode($Listeleme["urunListBotDataJson"], true);

        $array_count = count($data["report"]["table"]["row"]);
        for($i=0; $i<$array_count; $i++)
        {
            $parametreler = array(
                "urunStok" => $data["report"]["table"]["row"][$i]["MIKTAR_KALAN"]["__cdata"],
                "urunKdv" => $data["report"]["table"]["row"][$i]["KDV_ORANI_SATIS_TPT"]["__cdata"],
                'urunGuncellemeTarihi' => date("Y-m-d H:i:s")
            );
            $query = $db->update("Urunler", $parametreler, [
                "urunModel" => $data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"]
            ]);
        }
        if($query){
            echo "başarılı";
        }
    }
    else 
    {
        echo "null";
    }
}