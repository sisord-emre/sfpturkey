<?php
include("../../System/Config.php");
extract($_POST);

$kartVarmi = $db->get("Urunler", "*", [
    "urunId" => $urunId,
    "urunEnCokSatan" => 1
]);
if (!$kartVarmi) {
    $parametreler = array(
        'urunEnCokSatan' => $urunEnCokSatan
    );
    $query = $db->update("Urunler", $parametreler, [
        "urunId" => $urunId
    ]);
}

if ($query) {
    echo '1';
} 
else {
    echo '0';
}
