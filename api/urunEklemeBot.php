<?php
include('../Panel/System/Config.php');

//Excel Okuma
// if ($xlsx = SimpleXLSX::parse('a.xlsx')) {
//     // echo "<pre>";
//     // print_r($xlsx->rows());
//     // echo "</pre>";

//     $dim = $xlsx->dimension();
//     $num_cols = $dim[0];
//     $num_rows = $dim[1];

//     $i = 0;
//     foreach ($xlsx->rows(0) as $r) {
//         if ($i > 0) 
//         {
//             //varyant ekleme oluşacak
//             $varyantDilKontrol = $db->get("VaryantDilBilgiler", "*", [
//                 "varyantDilBilgiBaslik" => trim($r[3]),
//                 //"urunDilBilgiDilId" => $dil["dilId"]
//             ]);

//             if (!$varyantDilKontrol) 
//             {
//                 $varyanKodu = mt_rand(100000000, 999999999);
//                 $varyantEkle = $db->insert("Varyantlar", [
//                     'varyanKodu' => $varyanKodu,
//                     'varyanDurum' => 1
//                 ]);

//                 $varyantId = $db->id();

//                 $itemTableName = "VaryantDilBilgiler";
//                 $dilList = $db->select("Diller", "*");
//                 foreach ($dilList as $dil) {
//                     $itemPar = array(
//                         'varyantDilBilgiVaryatId' => $varyantId,
//                         'varyantDilBilgiDilId' => $dil["dilId"],
//                         'varyantDilBilgiBaslik' => trim($r[3]),
//                         'varyantDilBilgiSlug' => $fonk->toSeo(trim($r[3]))
//                     );

//                     $varyantDilKontrol = $db->get("VaryantDilBilgiler", "*", [
//                         "varyantDilBilgiBaslik" => trim($r[3]),
//                         //"urunDilBilgiDilId" => $dil["dilId"]
//                     ]);

//                     if (!$varyantDilKontrol) {
//                         $fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
//                         ///ekleme
//                         $queryAlt = $db->insert($itemTableName, $itemPar);
//                     }
//                 }
//             }
//              //varyant ekleme oluşacak

//             $imageYolu = $r[1];
//             $imageYolu = str_replace(".", ",", $imageYolu);
//             $imageYolu = str_replace(",", "/", $imageYolu);

//             $imageCount = $r[6];
//             $imageCount = array_pop(explode("-", $imageCount));

//             // $imageBul = $r[6];
//             // $imageBul = explode("-", $imageBul);

//             $urunKodu = mt_rand(100000000, 999999999);
//             $urunSFPPort = ($r[7] == "") ? 0 : 1;
//             $urunSFPPortBirlikte = ($r[9] == "") ? 0 : 1;
//             $urunSFP28Port = ($r[10] == "") ? 0 : 1;
//             $urunQSFPPort = ($r[11] == "") ? 0 : 1;
//             $urunQSFP28Port = ($r[12] == "") ? 0 : 1;
//             $urunEndustriyelTip = ($r[13] == "") ? 0 : 1;
//             $urun100MegabitRJ45Port = ($r[14] == "") ? 0 : 1;
//             $urun1GigabitRJ45Port = ($r[15] == "") ? 0 : 1;
//             $urun10GigabitRJ45Port = ($r[16] == "") ? 0 : 1;
//             $urun1GSFPPort = ($r[8] == "") ? 0 : 1;
//             $urun1Metre = 0;
//             $urun2Metre = 0;
//             $urun3Metre = 0;
//             $urun510Metre = 0;
//             $urun1020Metre = 0;
//             $urun2030Metre = 0;

//             $productParametreler = array(
//                 'urunKodu' => $urunKodu,
//                 'urunFiyat' => 0,
//                 'urunParaBirimId' => 2, //dolar
//                 'urunKdv' => 18,
//                 'urunBegeni' => 0,
//                 'urunSepetMiktar' => 0,
//                 'urunSatisMiktar' => 0,
//                 'urunStok' => 0,
//                 'urunDurum' => 1,
//                 'urunModel' => $r[2],
//                 'urunGorsel' => trim($r[2]) . "-1.jpg",
//                 'urunBaseUrl' => $sabitB["sabitBilgiSiteUrl"] . "Images/Urunler/" . $imageYolu . "/",
//                 'urunSFPPort' => $urunSFPPort,
//                 'urunSFPPortBirlikte' => $urunSFPPortBirlikte,
//                 'urunSFP28Port' => $urunSFP28Port,
//                 'urunQSFPPort' => $urunQSFPPort,
//                 'urunQSFP28Port' => $urunQSFP28Port,
//                 'urunEndustriyelTip' => $urunEndustriyelTip,
//                 'urun100MegabitRJ45Port' => $urun100MegabitRJ45Port,
//                 'urun1GigabitRJ45Port' => $urun1GigabitRJ45Port,
//                 'urun10GigabitRJ45Port' => $urun10GigabitRJ45Port,
//                 'urun1GSFPPort' => $urun1GSFPPort,
//                 'urun1Metre' => $urun1Metre,
//                 'urun2Metre' => $urun2Metre,
//                 'urun3Metre' => $urun3Metre,
//                 'urun510Metre' => $urun510Metre,
//                 'urun1020Metre' => $urun1020Metre,
//                 'urun2030Metre' => $urun2030Metre,
//                 'urunKayitTarihi' => date("Y-m-d H:i:s")
//             );

//             $urunKontrol = $db->select("Urunler", "*", [
//                 "urunModel" => $r[2]
//             ]);

//             if (!$urunKontrol) {
//                 $tableName = "Urunler";
//                 $query = $db->insert($tableName, $productParametreler);

//                 $urunId = $db->id();

//                 $kategoriIdList = $r[1];
//                 $kategoriIdList = str_replace(".", ",", $kategoriIdList);
//                 $kategoriIdList = explode(",", $kategoriIdList);

//                 //ürün kategorileri
//                 foreach ($kategoriIdList as $key => $value) {
//                     $kategoriEkle = $db->insert("UrunKategoriler", [
//                         'urunKategoriUrunId' => $urunId,
//                         'urunKategoriKategoriId' => $value
//                     ]);
//                 }

//                 //ürün detay görselleri
//                 for ($n = 1; $n <= $imageCount; $n++) {
//                     $goselEkle = $db->insert("UrunGorselleri", [
//                         'urunGorselUrunId' => $urunId,
//                         'urunGorselLink' => trim($r[2]) . "-" . $n . ".jpg",
//                         'urunGorselBaseUrl' =>  $sabitB["sabitBilgiSiteUrl"] . "Images/Urunler/" . $imageYolu . "/",
//                         'urunGorselSirasi' => $n
//                     ]);
//                 }

//                 //dile göre değerlerin kayıt edilmesi
//                 $itemTableName = "UrunDilBilgiler";
//                 $dilList = $db->select("Diller", "*");
//                 foreach ($dilList as $dil) {
//                     $itemPar = array(
//                         'urunDilBilgiUrunId' => $urunId,
//                         'urunDilBilgiDilId' => $dil["dilId"],
//                         'urunDilBilgiAdi' => $r[0],
//                         'urunDilBilgiSlug' => $fonk->toSeo($r[0]),
//                         'urunDilBilgiDescription' => $r[4],
//                         'urunDilBilgiAciklama' => "",
//                         'urunDilBilgiEtiketler' => $r[5],
//                         'urunDilBilgiDurum' => 1
//                     );

//                     $urunDilKontrol = $db->select("UrunDilBilgiler", "*", [
//                         "urunDilBilgiUrunId" => $urunId,
//                         //"urunDilBilgiDilId" => $dil["dilId"]
//                     ]);

//                     if (!$urunDilKontrol) {
//                         $fonk->logKayit(1, $itemTableName . ' ; ' . json_encode($itemPar)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
//                         ///ekleme
//                         $queryAlt = $db->insert($itemTableName, $itemPar);
//                     }
//                 }
//                 //!dile göre değerlerin kayıt edilmesi
//             }

//             // varyant oluşacak
//             if (trim($r[3]) == "") {
//                 $r[3] = "GENERIC";
//                 $urunVaryantDefaultSecim = 1;
//             }

//             //$urunVaryantDefaultSecim = (trim($r[3]) == "GENERIC") ? 1 : 0;
          

//             $varyantIdBul = $db->get("VaryantDilBilgiler", "*", [
//                 "varyantDilBilgiDilId" =>  1,
//                 "varyantDilBilgiBaslik" => trim($r[3])
//             ]);

//             $urunModelKontrol = $db->get("Urunler", "*", [
//                 "urunModel" => $r[2],
//             ]);

//             //aynı markadan birden fazla default olmasın
//             $kontrolUrunVaryantDefaultSecim = $db->get("UrunVaryantlari", "*", [
//                 "urunVaryantUrunId" => $urunModelKontrol["urunId"],
//                 "urunVaryantDefaultSecim" => 1
//             ]);

//             if(!$kontrolUrunVaryantDefaultSecim)
//             {
//                 $urunVaryantDefaultSecim = 1;
//             }
//             else {
//                 $urunVaryantDefaultSecim = 0;
//             }

//             $kontrolUrunVaryant = $db->get("UrunVaryantlari", "*", [
//                 "urunVaryantUrunId" => $urunModelKontrol["urunId"],
//                 "urunVaryantVaryantId" => $varyantIdBul["varyantDilBilgiVaryatId"]
//             ]);

//             if(!$kontrolUrunVaryant) {
//                 $varyantTableName = "UrunVaryantlari";
//                 $itemPar2 = array(
//                     'urunVaryantUrunId' => $urunModelKontrol["urunId"],
//                     'urunVaryantVaryantId' => $varyantIdBul["varyantDilBilgiVaryatId"],
//                     'urunVaryantFiyat' => 0,
//                     'urunVaryantDefaultSecim' => $urunVaryantDefaultSecim
//                 );
//                 $urunVaryantKodu = mt_rand(100000000, 999999999);
//                 $itemPar2 = array_merge($itemPar2, array('urunVaryantKodu' => $urunVaryantKodu));
//                 $queryAlt = $db->insert($varyantTableName, $itemPar2);
//                 $urunVaryantId = $db->id();
    
//                 $itemTableName2 = "UrunVaryantDilBilgiler";
//                 $dilList = $db->select("Diller", "*");
//                 foreach ($dilList as $dil) {
//                     $itemPar3 = array(
//                         'urunVaryantDilBilgiUrunId' => $urunModelKontrol["urunId"],
//                         'urunVaryantDilBilgiVaryantId' => $urunVaryantId,
//                         'urunVaryantDilBilgiDilId' => $dil["dilId"],
//                         'urunVaryantDilBilgiAdi' => $r[0],
//                         'urunVaryantDilBilgiSlug' => $fonk->toSeo($r[0]),
//                         'urunVaryantDilBilgiDescription' => $r[4],
//                         'urunVaryantDilBilgiEtiketler' =>  $r[5],
//                         'urunVaryantDilBilgiAciklama' => "",
//                         'urunVaryantDilBilgiDurum' => 1
//                     );
//                     $fonk->logKayit(1, $itemTableName2 . ' ; ' . json_encode($itemPar3)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
//                     $queryAlt = $db->insert($itemTableName2, $itemPar3);
//                 }
//             }

//             // varyant oluşacak

//         }
//         $i++;
//     }

//     echo "başarılı";
// } else {
//     echo SimpleXLSX::parse_error();
// }
