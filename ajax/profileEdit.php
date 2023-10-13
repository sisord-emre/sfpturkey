<?php
include("../Panel/System/Config.php");
extract($_POST);

$uyeBilgi = $db->get("Uyeler", "*", [
	"uyeSessionKey" => $_SESSION['uyeSessionKey']
]);

if ($_POST['formdan'] == "1") 
{
	$pass = "0";
	if ($change == "on") 
	{
		$sifreli = hash("sha256", md5($uyeSifreOld));
		if ($sifreli == $uyeBilgi["uyeSifre"]) {
			$pass = "1"; //güncelleme olsun
		} 
		else {
			$pass = "2"; //eski şifre yanlış
		}
	}

	$parametreler = array(
		'uyeTcVergiNo' => $uyeTcVergiNo,
		'uyeAdi' => $uyeAdi,
		'uyeSoyadi' => $uyeSoyadi,
		'uyeMail' => $uyeMail,
		'uyeTel' => $uyeTel,
		'uyeFirmaAdi' => $uyeFirmaAdi,
		'uyeTicaretSicilGazetesiBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/TicaretSicilGazetesi/",
		'uyeMukerrerImzaBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/ImzaSirkuleri/"
	);

	if ($pass == "1") 
	{
		$parametreler=array_merge($parametreler,array('uyeSifre' => hash("sha256", md5($uyeSifre))));
	}

	$files = array_filter($_FILES['uyeTicaretSicilGazetesi']['name']); 
	$fileName = mt_rand();
	$tmpFilePath = $_FILES['uyeTicaretSicilGazetesi']['tmp_name'];
	if ($tmpFilePath != "")
	{
		$ext = pathinfo($_FILES['uyeTicaretSicilGazetesi']['name'], PATHINFO_EXTENSION);
		$newFilePath = "../Images/TicaretSicilGazetesi/".$fileName ."." .$ext;
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
		$newFilePath3 = "../Images/ImzaSirkuleri/".$fileName3 ."." .$ext;
		if(move_uploaded_file($tmpFilePath3, $newFilePath3)) 
		{
			$parametreler=array_merge($parametreler,array('uyeMukerrerImza' => $fileName3. "." .$ext));
		}		
	}

	$query = $db->update('Uyeler', $parametreler, [
		"uyeSessionKey" => $uyeBilgi['uyeSessionKey']
	]);


	$files2 = array_filter($_FILES['uyeVergiLevhasiDosya']['name']); 
	$total_count2 = count($_FILES['uyeVergiLevhasiDosya']['name']);
	if($total_count2 > 0)
	{
		$silUyeVergiLevhasi = $db->delete("UyeVergiLevhasi", [
			"uyeVergiLevhasiUyeId" => $uyeBilgi["uyeId"]
		]);	
	}

	for($i=0; $i < $total_count2; $i++) 
	{
		$faturaAdi2 = mt_rand();
		$tmpFilePath2 = $_FILES['uyeVergiLevhasiDosya']['tmp_name'][$i];
		if($tmpFilePath2 != "")
		{
			$ext = pathinfo($_FILES['uyeVergiLevhasiDosya']['name'][$i], PATHINFO_EXTENSION);
			$newFilePath2 = "../Images/VergiLevhasi//".$faturaAdi2 ."." . $ext;
			if(move_uploaded_file($tmpFilePath2, $newFilePath2)) 
			{
				$datas=array(
					'uyeVergiLevhasiUyeId' => $uyeBilgi["uyeId"],
					'uyeVergiLevhasiBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/VergiLevhasi//",
					'uyeVergiLevhasiDosya' => $faturaAdi2. "." .$ext
				);
				$uyeVergiLevhasi = $db->insert('UyeVergiLevhasi', $datas);
			}
		}
	}
	
	if ($pass == "2") {
		echo '1';
	} 
	else if ($query) {
		echo '2';
	} 
	else {
		echo '3';
	}
}
