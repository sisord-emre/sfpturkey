<?php
//include('layouts/header.php');

//Excel Okuma
// if ($xlsx = SimpleXLSX::parse('b.xlsx')) {
//     $dim = $xlsx->dimension();
//     $num_cols = $dim[0];
//     $num_rows = $dim[1];

//  /*    echo "<pre>";
//     print_r($xlsx->rows());
//     echo "</pre>";
//  */

//     $i = 0;
//     foreach ($xlsx->rows(0) as $r) {
//         if ($i > 0) 
//         {
//             $urunSFPPort = ($r[39] == "") ? 0 : 1;
//             $urun1GSFPPort = ($r[40] == "") ? 0 : 1;
//             $urunSFPPortBirlikte = ($r[41] == "") ? 0 : 1;
//             $urunSFP28Port = ($r[42] == "") ? 0 : 1;
//             $urunQSFPPort = ($r[43] == "") ? 0 : 1;
//             $urunQSFP28Port = ($r[44] == "") ? 0 : 1;
//             $urunEndustriyelTip = ($r[45] == "") ? 0 : 1;
//             $urun100MegabitRJ45Port = ($r[46] == "") ? 0 : 1;
//             $urun1GigabitRJ45Port = ($r[47] == "") ? 0 : 1;
//             $urun10GigabitRJ45Port = ($r[48] == "") ? 0 : 1;
           

//             $productParametreler = array(
//                 'urunSFPPort' => $urunSFPPort,
//                 'urun1GSFPPort' => $urun1GSFPPort,
//                 'urunSFPPortBirlikte' => $urunSFPPortBirlikte,
//                 'urunSFP28Port' => $urunSFP28Port,
//                 'urunQSFPPort' => $urunQSFPPort,
//                 'urunQSFP28Port' => $urunQSFP28Port,
//                 'urunEndustriyelTip' => $urunEndustriyelTip,
//                 'urun100MegabitRJ45Port' => $urun100MegabitRJ45Port,
//                 'urun1GigabitRJ45Port' => $urun1GigabitRJ45Port,
//                 'urun10GigabitRJ45Port' => $urun10GigabitRJ45Port
//             );

//             $urunKontrol = $db->select("Urunler", "*", [
//                 "urunModel" => $r[50]
//             ]);

//             if ($urunKontrol) 
//             {
//                 $tableName = "Urunler";
//                 $query = $db->update($tableName, $productParametreler, [
//                     "urunModel"=> $r[50]
//                 ]);
//             }
//         }
//         $i++;
//     }

//     echo "başarılı";
// } else {
//     echo SimpleXLSX::parse_error();
// }


//include('layouts/footer.php');
