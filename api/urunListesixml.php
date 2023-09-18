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
        $data = $Listeleme["urunListBotDataJson"];
        print_r($data);
    }
    else 
    {
        echo "null";
    }
}