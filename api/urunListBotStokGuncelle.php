<?php
include('../Panel/System/Config.php');
if ($_GET["ApiKey"] == "8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp") {
    $Listeleme = $db->get("UrunListBotlari", "*", [
        "urunListBotTip" => 1, //stok list
        "ORDER" => [
            "urunListBotId" => "DESC"
        ]
    ]);

    if($Listeleme["urunListBotDataJson"])
    {
        $data = json_decode($Listeleme["urunListBotDataJson"], true);

        $array_count = count($data["report"]["table"]["row"]);
        for($i=0; $i<$array_count; $i++)
        {
            $parametreler = array(
                "urunFiyat" => str_replace(",", ".", $data["report"]["table"]["row"][$i]["DSF1"]["__cdata"]),
                'urunGuncellemeTarihi' => date("Y-m-d H:i:s")
            );
            $query = $db->update("Urunler", $parametreler, [
                "urunModel" => $data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"]
            ]);

            $urunVaryant = $db->get("UrunVaryantlari",[
                "[>]Urunler" => ["UrunVaryantlari.urunVaryantUrunId" => "urunId"],
            ], "*", [
                "urunModel" => $data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"],
                "ORDER" => [
                    "UrunVaryantlari" => "ASC"
                ]
            ]);

            $urunVaryantParametreler = array(
                "urunVaryantFiyat" => str_replace(",", ".", $data["report"]["table"]["row"][$i]["DSF1"]["__cdata"])
            );
            $urunVaryantQuery = $db->update("UrunVaryantlari", $urunVaryantParametreler, [
                "urunVaryantUrunId" => $urunVaryant["urunId"]
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