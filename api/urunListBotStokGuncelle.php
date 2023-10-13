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
			if($data["report"]["table"]["row"][$i]["DSF1"]["__cdata"]==""){
				$fiyat=0;
			}else{
				$fiyat=$data["report"]["table"]["row"][$i]["DSF1"]["__cdata"];
			}
            $fiyat = str_replace(",", ".", $fiyat);
            $fiyat = floatval($fiyat);
            $parametreler = array(
                "urunFiyat" => $fiyat,
                'urunGuncellemeTarihi' => date("Y-m-d H:i:s")
            );
            $query = $db->update("Urunler", $parametreler, [
                "urunModel" => trim($data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"])
            ]);

            $urunVaryant = $db->get("UrunVaryantlari",[
                "[>]Urunler" => ["UrunVaryantlari.urunVaryantUrunId" => "urunId"],
            ], "*", [
                "urunModel" => trim($data["report"]["table"]["row"][$i]["STOKKODU"]["__cdata"]),
                "ORDER" => [
                    "UrunVaryantlari" => "ASC"
                ]
            ]);

            $urunVaryantParametreler = array(
                "urunVaryantFiyat" => $fiyat
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