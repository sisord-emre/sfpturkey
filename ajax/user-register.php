<?php 
include ("../Panel/System/Config.php");
extract($_POST);

if($_POST['formdan']=="1")
{
    $secret = $sabitB['sabitBilgiPrivateRecaptcha'];
	$response=$_POST["g-recaptcha-response"];
	$verify=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}");
	$captcha_success=json_decode($verify);

	if($captcha_success->success==false)
	{
		echo '0';
		exit;
	}
    else if($captcha_success->success==true)
    {
        $uyeTcVergiNoKontrol = $db->get('Uyeler', "*", [
            'uyeTcVergiNo'=> $uyeTcVergiNo
        ]);

        $uyeMailKontrol = $db->get('Uyeler', "*", [
            'uyeMail'=> $uyeMail
        ]);

      
        if ($uyeSifre!=$uyeSifreTekrar) 
        {
            echo '4';
        }
        else if($uyeMailKontrol)
        {
            echo '5';
        }
        else if($gizlilikonay != "1")
        {
            echo '6';
        }
        else if($kvkk != "1")
        {
            echo '7';
        }
        else if(!$uyeTcVergiNoKontrol)
        {
            $uyeKodu=time();
            $parametreler=array(
                'uyeTcVergiNo' => $uyeTcVergiNo,
                'uyeAdi' => $uyeAdi,
                'uyeSoyadi' => $uyeSoyadi,
                'uyeMail' => $uyeMail,
                'uyeTel' => $uyeTel,
                'uyeSifre' => hash("sha256", md5($uyeSifre)),
                'uyeKodu' => $uyeKodu,
                'uyeFirmaAdi' => $uyeFirmaAdi,
                'uyeDurum' => 0, //pasif
                'uyeIndirimOrani' => 0, //başta 0 olacak
                'uyeTicaretSicilGazetesiBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/SicilGazetesi/",
                'uyeMukerrerImzaBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/MukerrerImza/",
                'uyeGizlilikOnay' => $gizlilikonay, 
                'uyeKvkkOnay' => $kvkk, 
                'uyeKayitTarihi' => date("Y-m-d h:i:s")
            );
    
        
            $files = array_filter($_FILES['uyeTicaretSicilGazetesi']['name']); 
            $fileName = mt_rand();
            $tmpFilePath = $_FILES['uyeTicaretSicilGazetesi']['tmp_name'];
            if ($tmpFilePath != "")
            {
                $ext = pathinfo($_FILES['uyeTicaretSicilGazetesi']['name'], PATHINFO_EXTENSION);
                $newFilePath = "../Images/SicilGazetesi/".$fileName ."." .$ext;
                if(move_uploaded_file($tmpFilePath, $newFilePath)) 
                {
                    $parametreler=array_merge($parametreler,array('uyeTicaretSicilGazetesi' => $fileName. "." .$ext));
                }		
            }

            $files3 = array_filter($_FILES['uyeMukerrerImza']['name']); 
            $fileName3 = mt_rand();
            $tmpFilePath3 = $_FILES['uyeMukerrerImza']['tmp_name'];
            if ($tmpFilePath3 != "")
            {
                $ext = pathinfo($_FILES['uyeMukerrerImza']['name'], PATHINFO_EXTENSION);
                $newFilePath3 = "../Images/MukerrerImza/".$fileName3 ."." .$ext;
                if(move_uploaded_file($tmpFilePath3, $newFilePath3)) 
                {
                    $parametreler=array_merge($parametreler,array('uyeMukerrerImza' => $fileName3. "." .$ext));
                }		
            }
        
            $query = $db->insert('Uyeler', $parametreler);
            $uyeId = $db->id();
        
           
        
            if($query)
            {
                $files2 = array_filter($_FILES['uyeVergiLevhasiDosya']['name']); 
                $total_count2 = count($_FILES['uyeVergiLevhasiDosya']['name']);
                for($i=0; $i < $total_count2; $i++) 
                {
                    $faturaAdi2 = mt_rand();
                    $tmpFilePath2 = $_FILES['uyeVergiLevhasiDosya']['tmp_name'][$i];
                    if($tmpFilePath2 != "")
                    {
                        $ext = pathinfo($_FILES['uyeVergiLevhasiDosya']['name'][$i], PATHINFO_EXTENSION);
                        $newFilePath2 = "../Images/SicilTicaret/".$faturaAdi2 ."." . $ext;
                        if(move_uploaded_file($tmpFilePath2, $newFilePath2)) 
                        {
                            $datas=array(
                                'uyeVergiLevhasiUyeId' => $uyeId,
                                'uyeVergiLevhasiBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/SicilTicaret/",
                                'uyeVergiLevhasiDosya' => $faturaAdi2. "." .$ext
                            );
                            $uyeVergiLevhasi = $db->insert('UyeVergiLevhasi', $datas);
                        }
                    }
                }

                $uyeVergiLevhasi = $db->select("Uyeler", [
                    "[>]UyeVergiLevhasi" => ["Uyeler.uyeId" => "uyeVergiLevhasiUyeId"],
                ], "*", [
                    "uyeId" => $uyeId,
                    "ORDER" => [
                        "uyeId" => "ASC"
                    ]
                ]);
              
                $uye = $db->get("Uyeler", "*", [
                    "uyeId" => $uyeId,
                    "ORDER" => [
                        "uyeId" => "ASC"
                    ]
                ]);

                echo '3';

                include ("../userInfoMailTemplate.php");
                $baslik = "Yeni Kullanici (" .$uyeId." )";
                $sonuc=$fonk->mailGonder($uyeMail,$baslik,$body);
               
            }
            else
            {
                echo '2';
            }
        }
        else
        {
            echo '1';
        }
    }
}
?>