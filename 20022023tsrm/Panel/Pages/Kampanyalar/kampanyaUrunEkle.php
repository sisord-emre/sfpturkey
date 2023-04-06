<?php
include("../../System/Config.php");
extract($_POST);

$kartVarmi = $db->get("Urunler", "*", [
    "urunId" => $urunId,
    "urunKampanya" => 1
]);
if (!$kartVarmi) {
    $parametreler = array(
        'urunKampanya' => $urunKampanya
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
