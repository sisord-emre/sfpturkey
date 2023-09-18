<?php
include("../../System/Config.php");
extract($_POST);


if ($sil != "") 
{
    $parametreler = array(
        'urunKampanya' => 0
    );
    $query = $db->update("Urunler", $parametreler, [
        "urunId" => $sil
    ]);

    if ($query) 
    {
        echo 1;
    } 
    else 
    {
        echo 0;
    }
}
