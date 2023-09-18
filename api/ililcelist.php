<?php 
include('../Panel/System/Config.php');

$iller = $db->select("Iller", "*", [
    "ilUlkeId" => 223,
    "ORDER" => [
        "ilAdi" => "ASC"
    ]
]);

foreach ($iller as $key => $value) {
    echo $value["ilAdi"]."<br>";
    $ilceler = $db->select("Ilceler", "*", [
        "ilceIlId" => $value["ilId"],
        "ORDER" => [
            "ilceAdi" => "ASC"
        ]
    ]);

    foreach ($ilceler as $key => $value) {
        echo "----------".$key.": ".$value["ilceAdi"]."<br>";
    }
}
?>