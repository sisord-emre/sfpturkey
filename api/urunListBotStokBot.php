<?php 
include('../Panel/System/Config.php');
if ($_GET["ApiKey"] == "8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp") {
    $userLink = $fonk->akinSoftConnection('wlogin','MUHASEBE','6512bd43d9caa6e02c990b0a82652dca','202217518','535AD','60');
    $userLink=base64_encode($userLink);
    $url="http://195.174.216.24:3056/getdata.html?".$userLink;
    $data = file_get_contents($url);

    $decodeLink = base64_decode($data);
    $decodeLinkFindOne = explode("&",$decodeLink);
    $editHTTPGetLink = $fonk->akinSoftGetParametreApi($decodeLinkFindOne[1],"get_stoklist","01","2022","","","","","","","","");
    $encryptedHTTPLink=base64_encode($editHTTPGetLink);
    $getLink = "http://195.174.216.24:3056/getdata.html?".$encryptedHTTPLink;

    $returnGetLink = file_get_contents($getLink);
    $sonuc = base64_decode($returnGetLink);

    $xml = new JsonSerializer($sonuc);
    $xml_to_json = json_encode($xml, JSON_PRETTY_PRINT);
    if($sonuc)
    {
        $parametreler = array(
            "urunListBotData" => $sonuc,
            "urunListBotDataJson" => $xml_to_json,
            "urunListBotGetLink" => $getLink,
            "urunListBotTip" => 1, //get_stok_list
            'urunListBotKayitTarihi' => date("Y-m-d H:i:s")
        );
        $query = $db->insert("UrunListBotlari", $parametreler);
        if($query){
            echo "İşlem Başarılı";
        }
    }
    else 
    {
        echo "Boş değer döndürdü";
    }
}
?>