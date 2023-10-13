<?php
include "../System/Config.php";
$parent = intval($_POST["parent"]);
$urunId = intval($_POST["urunId"]);

$sartlar = [];
if ($_SESSION["islemDilId"] != "") {
    $sartlar = array_merge($sartlar, ["kategoriDilBilgiDilId" => $_SESSION["islemDilId"]]);
} else {
    $sartlar = array_merge($sartlar, ["kategoriDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
}
$sartlar = array_merge($sartlar, [
    "kategoriUstMenuId" => $parent
]);
$kategoriAltList = $db->select("Kategoriler", [
    "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
], "*", $sartlar);
foreach ($kategoriAltList as $val) {
    $check = "";
    $kontrol = $db->get("UrunKategoriler", "*", [
        "urunKategoriUrunId" => $urunId,
        "urunKategoriKategoriId" => $val["kategoriId"]
    ]);
    if ($kontrol) {
        $check = "selected";
    }
?>
    <option value="<?= $val['kategoriId'] ?>" <?= $check ?>><?= $val['kategoriDilBilgiBaslik'] ?></option>
    <?php
    $sartlar = array_merge($sartlar, [
        "kategoriUstMenuId" => $val["kategoriId"]
    ]);
    $kategoriAltList2 = $db->select("Kategoriler", [
        "[>]KategoriDilBilgiler" => ["Kategoriler.kategoriId" => "kategoriDilBilgiKategoriId"]
    ], "*", $sartlar);
    foreach ($kategoriAltList2 as $val) {
        $check = "";
        $kontrol = $db->get("UrunKategoriler", "*", [
            "urunKategoriUrunId" => $urunId,
            "urunKategoriKategoriId" => $val["kategoriId"]
        ]);
        if ($kontrol) {
            $check = "selected";
        }
    ?>
        <option value="<?= $val['kategoriId'] ?>" <?= $check ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--> <?= $val['kategoriDilBilgiBaslik'] ?></option>
<?php }
}  ?>