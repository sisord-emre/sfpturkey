<?php
class Fonksiyonlar
{
	protected $db;
	protected $sabitB;
	protected $kullaniciId;
	function __construct($db, $sabitB)
	{
		$this->db = $db;
		$this->sabitB = $sabitB;
	}

	function setKullanici($kullaniciId)
	{
		$this->kullaniciId = $kullaniciId;
	}

	function yonlendir($link)
	{
		header('Location: ' . $link);
		echo '<script>window.location.href="' . $link . '";</script>';
	}

	function sqlToDateTime($tarih)
	{
		if ($tarih != "") {
			return (new DateTime($tarih))->format('d.m.Y H:i:s');
		}
	}

	function sqlToDateTimeSaniyesiz($tarih)
	{
		if ($tarih != "") {
			return (new DateTime($tarih))->format('d.m.Y H:i');
		}
	}

	function sqlToDate($tarih)
	{
		if ($tarih != "") {
			return (new DateTime($tarih))->format('d.m.Y');
		}
	}

	function sqlToDateTimeTiresiz($tarih)
	{
		if ($tarih != "") {
			return (new DateTime($tarih))->format('YmdHis');
		}
	}

	function toSeo($yazi)
	{
		$tr = array('ş', 'Ş', 'ı', 'I', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ö', 'Ö', 'Ç', 'ç', '(', ')', '/', ':', ',', '?', '!', '...', '..', '“', '”', ';', '…', '&', '|', '=', '+', '*', '’', '’');
		$eng = array('s', 's', 'i', 'i', 'i', 'g', 'g', 'u', 'u', 'o', 'o', 'c', 'c', '', '', '-', '-', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
		$yazi = str_replace($tr, $eng, $yazi);
		$yazi = strtolower($yazi);
		$yazi = preg_replace('/&amp;amp;amp;amp;amp;amp;amp;amp;amp;.+?;/', '', $yazi);
		$yazi = preg_replace('/\s+/', '-', $yazi);
		$yazi = preg_replace('|-+|', '-', $yazi);
		$yazi = preg_replace('/#/', '', $yazi);
		$yazi = str_replace('.', '', $yazi);
		$yazi = str_replace('"', '', $yazi);
		$yazi = str_replace('\'', '', $yazi);
		$yazi = str_replace('’', '', $yazi);
		$yazi = preg_replace("/[^a-zA-Z0-9\-]+/", '', $yazi); //sadece yazı, sayı ve "-"
		$yazi = trim($yazi, '-');
		return $yazi;
	}

	/*
	//çoklu görsel yükleme örn=>ilk olarak file inputunda name array yapıyoruz gorsel[] gibi, sonunada multiple ekliyoruz
	$files = array();
	foreach ($_FILES['gorseller'] as $k => $l) {
		foreach ($l as $i => $v) {
			if (!array_key_exists($i, $files))
			$files[$i] = array();
			$files[$i][$k] = $v;
		}
	}
	$sayac=0;
	foreach ($files as $file) {//max 4 adete göre ayarlandı
		$sayac++;
		$gorselAd=$urunlerSeo."_".$sayac;
		if($sayac<=4){
			$kontrol=$fonk->imageResizeUpload($file,'../../../Images/Urunler/',$gorselAd,800,800,jpg);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
			if($kontrol==1){
				$goselEkle = $db->insert("DergiSayfalari", [
				'dergiSayfaDergiId' => $primaryId,
				'dergiSayfaGorsel' => $gorselAd.".jpg",
				'dergiSayfaSirasi' => $sayac
				]);
			}
		}else{
			break;
		}
	}
	*/
	//ornek: $fonk->imageUpload($_FILES['siteFavicon'],'../../Images/Ayarlar/','favicon',jpg);//doğrudan yükleme jpg yükleme başarılı ise 1 döner
	function imageUpload($dosya, $adresi, $adi, $tipi)
	{
		$image = new upload($dosya);
		if ($image->uploaded) {
			// save uploaded image with a new name
			$image->file_new_name_body = $adi;
			$image->image_convert = $tipi;
			$image->file_overwrite = true;
			$image->file_max_size = '12582912';
			$image->process($adresi);
			if ($image->processed) {
				return true;
			} else {
				//echo 'error : ' . $image->error;
				return false;
			}
		}
	}

	//örnek: $fonk->imageResizeUpload($_FILES['siteLogo'],'../../Images/Ayarlar/','logo',128,128,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
	function imageResizeUpload($dosya, $adresi, $adi, $x, $y, $tipi)
	{
		$image = new upload($dosya);
		if ($image->uploaded) {
			// save uploaded image with a new name,
			$image->file_new_name_body = $adi;
			$image->image_convert = $tipi;
			if ($x != 0 && $y != 0) {
				$image->image_resize = true;
				$image->image_x = $x;
				$image->image_y = $y;
			}
			//$image->image_ratio_y = true;//orantılı küçültmek için x te kullanılabilir
			$image->file_overwrite = true;
			$image->file_max_size = '12582912';
			$image->process($adresi);
			if ($image->processed) {
				return true;
				$image->clean();
			} else {
				//echo 'error : ' . $image->error;
				return false;
			}
		}
	}

	//örnek: $fonk->imageUploadCenterCrop($_FILES['siteLogo'],'../../Images/Ayarlar/','logo',128,128,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
	function imageUploadCenterCrop($dosya, $adresi, $adi, $x, $y, $tipi)
	{
		$image = new upload($dosya);
		if ($image->uploaded) {
			// save uploaded image with a new name
			$image->file_new_name_body = $adi;
			$image->image_convert = $tipi;
			$image->file_overwrite = true;
			$image->file_max_size = '12582912';
			$image->image_resize          = true;
			$image->image_ratio_crop      = true;
			$image->image_y               = $y;
			$image->image_x               = $x;
			$image->process($adresi);
			if ($image->processed) {
				return true;
			} else {
				//echo 'error : ' . $image->error;
				return false;
			}
		}
	}

	//$fonk->imageCropSave('../../Images/Temp/crop-pic.jpg','../../Images/Ayarlar/','test',250,81,425,239,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner(left,top,width,height)
	function imageCropSave($dosya, $adresi, $adi, $left, $top, $width, $height, $tipi)
	{
		$image_info = getimagesize($dosya);
		if ($image_info[0] - $left - $width < 0 || $image_info[1] - $top - $height < 0) {
			return $this->getPDil("Kesim Boyutları Görselden Büyüktür.");
		} else {
			$right = $image_info[0] - $left - $width;
			$bot = $image_info[1] - $top - $height;
			$image = new upload($dosya);
			if ($image->uploaded) {
				// save uploaded image with a new name,
				$image->file_new_name_body = $adi;
				$image->image_convert = $tipi;
				$image->image_crop = array($top, $right, $bot, $left);
				$image->file_overwrite = true;
				$image->file_max_size = '12582912';
				$image->process($adresi);
				if ($image->processed) {
					return true;
					$image->clean();
				} else {
					//echo 'error : ' . $image->error;
					return false;
				}
			}
		}
	}

	//Örnek=  dosyaUpload($_FILES['file'],"upload/","test2",6,array("mp4","avi","3gp","mov","mpeg"));//başarılı ise 1 döner değilse hata mesajı
	function dosyaUpload($dosya, $hedef, $dosyaAdi, $maxSizeMb, $desteklenenler)
	{
		$maxsize = $maxSizeMb * 1048576;
		// Select file type
		$dosyaUzanti = strtolower(pathinfo($hedef . $dosya["name"], PATHINFO_EXTENSION));
		$target_file = $hedef . $dosyaAdi . '.' . $dosyaUzanti;
		// Check extension
		if (in_array($dosyaUzanti, $desteklenenler)) {
			// Check file size
			if (($dosya['size'] >= $maxsize) || ($dosya["size"] == 0)) {
				return $fonk->getPDil("Dosya Boyutu Çok Yüksek.");
			} else {
				// Upload
				if (move_uploaded_file($dosya['tmp_name'], $target_file)) {
					return 1;
				}
			}
		} else {
			return $fonk->getPDil("Desteklenmeyen Dosya Tipi.");
		}
	}

	//$kontrol=$fonk->dosyaUploadArr($_FILES['teknikYapilabilirlikCadData'],$konum,$cadDataAdi,50,array("pdf","jpg","jpeg"));//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
	//if($kontrol[0]==1){$parametreler=array_merge($parametreler,array('teknikYapilabilirlikCadData' => $kontrol[1]));}
	function dosyaUploadArr($dosya, $hedef, $dosyaAdi, $maxSizeMb, $desteklenenler)
	{
		// Select file type
		$dosyaUzanti = strtolower(pathinfo($hedef . $dosya["name"], PATHINFO_EXTENSION));
		if ($dosyaUzanti != "") {
			$maxsize = $maxSizeMb * 1048576;
			$target_file = $hedef . $dosyaAdi . '.' . $dosyaUzanti;
			$dosyaAdi = $dosyaAdi . '.' . $dosyaUzanti;
			// Check extension
			if (in_array($dosyaUzanti, $desteklenenler)) {
				// Check file size
				if (($dosya['size'] >= $maxsize) || ($dosya["size"] == 0)) {
					return array(0, $fonk->getPDil("Dosya Boyutu Çok Yüksek."));
				} else {
					// Upload
					if (move_uploaded_file($dosya['tmp_name'], $target_file)) {
						return array(1, $dosyaAdi);
					}
				}
			} else {
				return array(0, $fonk->getPDil("Desteklenmeyen Dosya Tipi."));
			}
		} else {
			return array(2, $fonk->getPDil("Lütfen yüklenecek dosya seçiniz."));
		}
	}

	function fileUploadArr($dosya, $hedef, $dosyaAdi)
	{
		$files = array_filter($dosya['name']);
		$dosyaUzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));
		$dosyaAdi = strtolower($dosyaAdi . "." . $dosyaUzanti);
		$tmpFilePath = $dosya['tmp_name'];
		if ($tmpFilePath != "") {
			$newFilePath = $hedef . "" . $dosyaAdi;
			if (move_uploaded_file($tmpFilePath, $newFilePath)) {
				return array(1, $dosyaAdi);
			}
		}
	}

	//Mail Örnek Şablon: mailGonder('emre.arig@sisord.com','başlık','icerik');
	function mailGonder($mailadres, $subject, $body)
	{
		$mailConfig = $this->sabitB;
		$gondericiMail = explode(';', $mailConfig['sabitBilgiMail']);
		$mail             = new PHPMailer();
		$mail->Host       = $mailConfig['sabitBilgiHost'];
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = $mailConfig['sabitBilgiGuvenlik'];
		$mail->Username   = $gondericiMail[0];
		$mail->Password   = $mailConfig['sabitBilgiSifre'];
		$mail->Port   	  = $mailConfig['sabitBilgiPort'];
		$mail->IsHTML(true);
		$mail->IsSMTP();
		$mail->CharSet = 'utf-8';
		$mail->From       = $gondericiMail[0];
		$mail->FromName   = $mailConfig['sabitBilgiBaslik'];
		$mail->Subject    = $subject;
		$mail->Body    = $body;
		$mailler = explode(';', $mailadres);
		foreach ($mailler as $mail1) {
			$mail->AddAddress($mail1);
		}
		//$mail->AddAddress($mailadres);
		if (!$mail->Send()) {
			$s = 0;
		} else {
			$s = 1;
		}
		return $s;
	}

	//$fonk->bildirimGonder($uye['uyeCihazTipi'],$uye['uyeRegistrationId'],"Winz",$bildirimYazi,"varsa paramaetre arrayi");
	function bildirimGonder($cihazTipi, $rekKey, $baslik, $body, $parametre)
	{ //tipi 1 ise android 2 ise ios || bildirim array olarak gönderilecek
		// API access key from Google FCM App Console (Sunucu anahtarı)
		define('API_ACCESS_KEY', 'AAAAYt4lDjQ:APA91bHrHSx29F5X0cnJYu-3DeoxqRkqOgSLDWVaAY2B1RKqnp-8Z0GasrWS-iJVIfq_Ko9gctp0WekJAH4ZYqktGv5J9qp9PU_pLCM_1xEr1p4n0i8rKS-xVmMHEfqomPhk10wZ3KIx');
		if ($cihazTipi == 1) {
			$bildirim = array('title' => $baslik, 'body' => $body, 'parametre' => $parametre);
			$gonder = array(
				'to' => $rekKey, //rekkey
				'data' => $bildirim
			);
		} else {
			$bildirim = array('title' => $baslik, 'body' => $body, 'parametre' => $parametre, 'sound' => 'default');
			$gonder = array(
				'to' => $rekKey, //rekkey
				'priority' => 'high',
				'content_available' => true,
				'notification' => $bildirim
			);
		}
		$headers = array(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($gonder));
		$result = curl_exec($ch);
		curl_close($ch);
		//echo $result . "\n\n";
	}

	function sifreUret($karakterSayisi)
	{
		$karakterler = "1234567890abcdefghijKLMNOPQRSTuvwxyzABCDEFGHIJklmnopqrstUVWXYZ0987654321";
		$sifre = '';
		for ($i = 0; $i < $karakterSayisi; $i++) //Oluşturulacak şifrenin karakter sayısı 8'dir.
		{
			$sifre .= $karakterler[rand() % 72]; //$karakterler dizisinden ilk 72 karakter kullanılacak, yani hepsi.
		}
		return strtoupper($sifre); //Oluşturulan şifre gönderiliyor.
	}

	//verimor sms gönderme
	function smsGonder($message, $phones)
	{
		$sms_msg = array(
			"username" => "902122434555",
			"password" => "xxxxx",
			"source_addr" => "xxxxxx",
			//"valid_for" => "48:00",
			//"send_at" => "2015-02-20 16:06:00",
			//"datacoding" => "0",
			"custom_id" => "1424441160.9331344",
			"messages" => array(
				array(
					"msg" => $message,
					"dest" => $phones
				)
			)
		);
		$ch = curl_init('http://sms.verimor.com.tr/v2/send.json');
		curl_setopt_array($ch, array(
			CURLOPT_POST => TRUE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
			CURLOPT_POSTFIELDS => json_encode($sms_msg),
		));
		$http_response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code != 200) {
			echo "$http_code $http_response\n";
			return false;
		}
		return $http_response;
	}

	//verimor sms kalam miktar
	function smsBakiye()
	{
		$username = "902122430000"; // https://oim.verimor.com.tr/sms_settings/edit adresinden öğrenebilirsiniz.
		$password = urlencode("xxxxxx"); // https://oim.verimor.com.tr/sms_settings/edit adresinden belirlemeniz gerekir.
		$url = "http://sms.verimor.com.tr/v2/balance?username=$username&password=$password";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$http_response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code != 200) {
			echo "$http_code $http_response\n";
			return false;
		}
		echo $http_response;
	}

	/*
	$files=$fonk->repeaterFileMap($_FILES['repeater-list'],"gorsel");
	foreach ($files as  $file) {
		$gorselAdi="gorsel-".rand(1000,9999);
		$kontrol=$fonk->imageResizeUpload($file,'../../Images/Ayarlar/',$gorselAdi,128,128,png);//boyutlandırmalı resim yükleme yükleme başarılı ise 1 döner
	}
	*/
	function repeaterFileMap($fileList, $inputName)
	{
		$filesNewArray = array();
		$files = array();
		foreach ($fileList as $k => $l) {
			foreach ($l as $i => $v) {
				if (!array_key_exists($i, $files))
					$files[$i] = array();
				$files[$i][$k] = $v;
			}
		}
		foreach ($files as $file) {
			array_push($filesNewArray, array(
				"name" => $file["name"][$inputName],
				"type" => $file["type"][$inputName],
				"tmp_name" => $file["tmp_name"][$inputName],
				"error" => $file["error"][$inputName],
				"size" => $file["size"][$inputName]
			));
		}
		return $filesNewArray;
	}

	//sql injections için
	function injKontrol($mVar, $panelUrl)
	{
		$searchVal = array("--", "/*", "*/");
		$replaceVal = array("_", " ", " ");
		if (is_array($mVar)) {
			foreach ($mVar as $gVal => $gVar) {
				if (!is_array($gVar)) {
					if (!strstr($_SERVER['REQUEST_URI'], $panelUrl)) {
						$gVar = htmlspecialchars(strip_tags($gVar));
					}
					$mVar[$gVal] = str_replace($searchVal, $replaceVal, stripslashes(trim(htmlspecialchars_decode($gVar))));  // -> Dizi olmadığını fark edip temizledik.
				} else {
					$mVar[$gVal] = $this->injKontrol($gVar, $panelUrl);
				}
			}
		} else {
			if (!strstr($_SERVER['REQUEST_URI'], $panelUrl)) {
				$mVar = htmlspecialchars(strip_tags($mVar));
			}
			$mVar = str_replace($searchVal, $replaceVal, stripslashes(trim(htmlspecialchars_decode($mVar)))); // -> Dizi olmadığını fark edip temizledik.
		}
		return $mVar;
	}

	//word donuşturucude kullandık
	function charset_decode_utf_8($string)
	{
		/* Only do the slow convert if there are 8-bit characters */
		/* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
		if (!preg_match("/[\200-\237]/", $string) && !preg_match("/[\241-\377]/", $string)) {
			return $string;
		}
		// decode three byte unicode characters
		$string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e", "'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'", $string);
		// decode two byte unicode characters
		$string = preg_replace("/([\300-\337])([\200-\277])/e", "'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'", $string);
		return $string;
	}

	//$result=$fonk->listedeAra($list["cariKaynakId"],$musteriler,"musteriId"); // array döner
	function listedeAra($ara, $liste, $kolon)
	{
		return $liste[array_search($ara, array_column($liste, $kolon))];
	}

	//youtube link to embetlink
	function ytLinkToEmbedLink($url)
	{
		$urlParts = explode('/', $url);
		$vidid = explode('&', str_replace('watch?v=', '', end($urlParts)));
		return 'https://www.youtube.com/embed/' . $vidid[0];
	}

	//csrf güvenlik kontrolunu yapar
	function csrfKontrol()
	{
		if (!hash_equals($_SESSION['token'], $_POST['token'])) {
			session_regenerate_id(true); //sessionId sıfırlamak için
			session_destroy();
			session_start();
			$this->yonlendir("/");
			exit;
		}
	}

	//log kayıt fonksiyonu
	//$fonk->logKayit(2,$tableName.' ; '.$primaryId.' ; '.json_encode($parametreler));//1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
	function logKayit($logIslemTipi, $logIslem)
	{
		if ($this->sabitB['sabitBilgiLog'] == 1) {
			$log = $this->db->insert("Log", [
				"logKullaniciId" => intval($this->kullaniciId), //oturum id
				"logIslemTipi" => $logIslemTipi, //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
				"logIslem" => $logIslem, //yapılan işlme parametreleri
				"logTarih" => date("Y-m-d H:i:s") //yapılan zaman
			]);
		}
	}

	//formlardaki recaptcha kontrolleri yapılır başarılı ise 1 değilse 0 doner
	function recaptchaKontrol($captcha)
	{
		if ($captcha == "") {
			return 0;
		} else {
			$secretKey = $this->sabitB['sabitBilgiPrivateRecaptcha'];
			$ip = $_SERVER['REMOTE_ADDR'];
			// post request to server
			$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
			$response = file_get_contents($url);
			$responseKeys = json_decode($response, true);
			// should return JSON with success as true
			if ($responseKeys["success"]) {
				return 1;
			} else {
				return 0;
			}
		}
	}

	//Site dil değerleri ve dil değişimi
	function dilOturum($dilSecim)
	{
		$_SESSION["dilId"] = $dilSecim["dilId"];
		$_SESSION["dilKodu"] = $dilSecim["dilKodu"];
		$_SESSION["dilAdi"] = $dilSecim["dilAdi"];
		$_SESSION["dilGorsel"] = $dilSecim["dilGorsel"];
		$dilDegerleri = $this->db->select("DilDegerleri", [
			"[>]Diller" => ["DilDegerleri.dilDegerDilId" => "dilId"],
			"[>]DilKeyler" => ["DilDegerleri.dilDegerKeyId" => "dilKeyId"]
		], ["dilKeyKodu", "dilDegerYazi"], [
			"dilDegerDilId" => $_SESSION["dilId"],
			"dilDurumu" => 1
		]);
		$dilArray = array();
		foreach ($dilDegerleri as $dilDeger) {
			$dilArray = array_merge($dilArray, array($dilDeger["dilKeyKodu"] => $dilDeger["dilDegerYazi"]));
		}
		$_SESSION["Dil"] = $dilArray;
	}
	function getDil($key)
	{

		$kontrol = $this->db->get("DilKeyler", "*", [
			"dilKeyKodu" => mb_substr($this->toSeo($key), 0, 100, 'UTF-8')
		]);
		if (!$kontrol) {
			$ekleKey = $this->db->insert("DilKeyler", [
				'dilKeyKodu' => mb_substr($this->toSeo($key), 0, 100, 'UTF-8')
			]);
			$ekle = $this->db->insert("DilDegerleri", [
				'dilDegerDilId' => $this->sabitB["sabitBilgiVarsayilanDilId"],
				'dilDegerKeyId' => $this->db->id(),
				'dilDegerYazi' => str_replace("\"", "'", trim($key))
			]);
		}

		if ($this->sabitB['sabitBilgiDilGosterim'] == 1) {
			$key = mb_substr($this->toSeo($key), 0, 100, 'UTF-8');
			if ($_SESSION["Dil"][$key] != "") {
				return $_SESSION["Dil"][$key];
			} else {
				return $key;
			}
		} else {
			return $key;
		}
	}
	//!Site dil değerleri ve dil değişimi

	//Panel dil değerleri ve dil değişimi
	function dilPOturum($dilSecim)
	{
		$_SESSION["panelDilId"] = $dilSecim["dilId"];
		$_SESSION["panelDilKodu"] = $dilSecim["dilKodu"];
		$_SESSION["panelDilAdi"] = $dilSecim["dilAdi"];
		$_SESSION["panelDilGorsel"] = $dilSecim["dilGorsel"];
		$dilDegerleri = $this->db->select("PanelDilDegerleri", [
			"[>]Diller" => ["PanelDilDegerleri.panelDilDegerDilId" => "dilId"],
			"[>]PanelDilKeyler" => ["PanelDilDegerleri.panelDilDegerKeyId" => "panelDilKeyId"]
		], ["panelDilKeyKodu", "panelDilDegerYazi"], [
			"panelDilDegerDilId" => $_SESSION["panelDilId"],
			"dilPanelDurumu" => 1
		]);
		$dilArray = array();
		foreach ($dilDegerleri as $dilDeger) {
			$dilArray = array_merge($dilArray, array($dilDeger["panelDilKeyKodu"] => $dilDeger["panelDilDegerYazi"]));
		}
		$_SESSION["panelDil"] = $dilArray;
	}
	function getPDil($key)
	{
		/*
		$kontrol = $this->db->get("PanelDilKeyler","*",[
			"panelDilKeyKodu" => mb_substr($this->toSeo($key), 0, 100, 'UTF-8')
		]);
		if (!$kontrol) {
			$ekleKey = $this->db->insert("PanelDilKeyler", [
			'panelDilKeyKodu' => mb_substr($this->toSeo($key), 0, 100, 'UTF-8')
			]);
			$ekle = $this->db->insert("PanelDilDegerleri", [
			'panelDilDegerDilId' => $this->sabitB["sabitBilgiPanelVarsayilanDilId"],
			'panelDilDegerKeyId' => $this->db->id(),
			'panelDilDegerYazi' => str_replace("\"","'",trim($key))
			]);
		}
		*/
		if ($this->sabitB['sabitBilgiPanelDilGosterim'] == 1) {
			$key = mb_substr($this->toSeo($key), 0, 100, 'UTF-8');
			if ($_SESSION["panelDil"][$key] != "") {
				return $_SESSION["panelDil"][$key];
			} else {
				return $key;
			}
		} else {
			return $key;
		}
	}
	//!Panel dil değerleri ve dil değişimi

	//Sayfamalar
	private $page;
	private $totalRecord;
	private $paginationLimit;
	private $html;
	public $paginationItem = '<li class="page-item [active]"><a href="[url]" class="page-link">[text]</a></li>';
	//Ajax Sayfalama
	public function paginationAjax($totalRecord, $paginationLimit, $pageParamName)
	{
		$this->paginationLimit = $paginationLimit;
		$this->page = isset($_POST[$pageParamName]) && is_numeric($_POST[$pageParamName]) ? $_POST[$pageParamName] : 1;
		$this->totalRecord = $totalRecord;
		$this->pageCount = ceil($this->totalRecord / $this->paginationLimit);
		$start = ($this->page * $this->paginationLimit) - $this->paginationLimit;
		return [
			'start' => $start,
			'limit' => $this->paginationLimit
		];
	}

	//Ajax Sayfalama
	public function showPaginationAjax($url, $class = 'active')
	{
		if ($this->totalRecord > $this->paginationLimit) {
			for ($i = $this->page - 5; $i < $this->page + 5 + 1; $i++) {
				if ($i > 0 && $i <= $this->pageCount) {
					$this->html .= str_replace(
						['[active]', '[text]', '[url]'],
						[($i == $this->page ? $class : null), $i, str_replace('[page]', $i, $url)],
						$this->paginationItem
					);
				}
			}
			return $this->html;
		}
	}

	//Normal Sayfalama
	public function paginationNormal($totalRecord, $paginationLimit, $pageParamName)
	{
		$this->paginationLimit = $paginationLimit;
		$this->page = isset($_GET[$pageParamName]) && is_numeric($_GET[$pageParamName]) ? $_GET[$pageParamName] : 1;
		$this->totalRecord = $totalRecord;
		$this->pageCount = ceil($this->totalRecord / $this->paginationLimit);
		$start = ($this->page * $this->paginationLimit) - $this->paginationLimit;
		return [
			'start' => $start,
			'limit' => $this->paginationLimit
		];
	}
	//Normal Sayfalama
	public function showPaginationNormal($url, $class = 'active')
	{
		if ($this->totalRecord > $this->paginationLimit) {
			for ($i = $this->page - 5; $i < $this->page + 5 + 1; $i++) {
				if ($i > 0 && $i <= $this->pageCount) {
					$this->html .= str_replace(
						['[active]', '[text]', '[url]'],
						[($i == $this->page ? $class : null), $i, str_replace('[page]', $i, $url)],
						$this->paginationItem
					);
				}
			}
			return $this->html;
		}
	}
	public function nextPage()
	{
		return ($this->page + 1 < $this->pageCount ? $this->page + 1 : $this->pageCount);
	}
	public function prevPage()
	{
		return ($this->page - 1 > 0 ? $this->page - 1 : 1);
	}
	//!Sayfamalar

	public function paraCevir($para, $mevcutBirim, $hedefBirim)
	{
		if ($mevcutBirim == "TRY" && $hedefBirim == "TRY") {
			return $para;
		} else if ($mevcutBirim == "USD" && $hedefBirim == "USD") {
			return $para;
		} else if ($mevcutBirim == "EUR" && $hedefBirim == "EUR") {
			return $para;
		} else if ($mevcutBirim == "TRY" && $hedefBirim == "USD") {
			return round($para / $this->sabitB["sabitBilgiDolar"], 2);
		} else if ($mevcutBirim == "TRY" && $hedefBirim == "EUR") {
			return round($para / $this->sabitB["sabitBilgiEuro"], 2);
		} else if ($mevcutBirim == "USD" && $hedefBirim == "TRY") {
			return round($para * $this->sabitB["sabitBilgiDolar"], 2);
		} else if ($mevcutBirim == "EUR" && $hedefBirim == "TRY") {
			return round($para * $this->sabitB["sabitBilgiEuro"], 2);
		} else if ($mevcutBirim == "USD" && $hedefBirim == "EUR") {
			return round($para * ($this->sabitB["sabitBilgiDolar"] / $this->sabitB["sabitBilgiEuro"]), 2);
		} else if ($mevcutBirim == "EUR" && $hedefBirim == "USD") {
			return round($para * ($this->sabitB["sabitBilgiEuro"] / $this->sabitB["sabitBilgiDolar"]), 2);
		} else {
			return $this->getPDil("Hesap Dışı Para Birimi");
		}
	}


	public function Hesapla($urunId, $varyantId = 0, $uyeIndirimOrani = 0)
	{
		$veri = array();
		if ($urunId != 0) {
			$urun = $this->db->get("Urunler", [
				"[>]UrunDilBilgiler" => ["Urunler.urunId" => "urunDilBilgiUrunId"],
				"[>]UrunVaryantlari" => ["Urunler.urunId" => "urunVaryantUrunId"],
				"[>]UrunVaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantId" => "urunVaryantDilBilgiVaryantId"],
			], [
				"urunVaryantFiyat",
				"urunKampanya",
			], [
				"urunVaryantDilBilgiDilId" => $_SESSION["dilId"],
				"urunDurum" => 1,
				"urunVaryantDilBilgiDurum" => 1,
				"urunVaryantId" => $urunId
			]);
			$veri = array_merge($veri, $urun);

		}

		if ($varyantId != 0) {
			$varyant = $this->db->get("UrunVaryantlari", [
				"[<]Varyantlar" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantId"],
				"[<]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
			], [
				"urunVaryantFiyat",
			], [
				"varyantDilBilgiDilId" => $_SESSION["dilId"],
				"varyanDurum" => 1,
				"urunVaryantVaryantId" => $varyantId
			]);
			$veri = array_merge($veri, $varyant);
		}

		$birimFiyat = 0;
		if ($veri["urunKampanya"] == 1) // eğer urunde kampanya varsa indirim yapma
		{
			$birimFiyat += floatval($veri["urunVaryantFiyat"]);
		}
		else if ($veri["urunVaryantFiyat"] != "") ///eger varyant varsa onu hesapla
		{
			if ($uyeIndirimOrani != 0) {
				$birimFiyat += floatval($veri["urunVaryantFiyat"] - ($veri["urunVaryantFiyat"] / 100 * $uyeIndirimOrani));
			} else {
				$birimFiyat += floatval($veri["urunVaryantFiyat"]);
			}
		} 
		else if ($veri["urunFiyat"] != "") {
			$birimFiyat += floatval($veri["urunFiyat"]);
		}
		$veri = array_merge($veri, array("birimFiyat" => $birimFiyat));
		return $veri;
	}

	public function KargoUcreti($tutar)
	{
		if($tutar > $this->sabitB["sabitBilgiMinSepetUcret"])
		{
			return 0;
		}
		else 
		{
			return $this->sabitB["sabitBilgiKargoUcret"];
		}
	}

	public function KargoKdvUcreti($tutar)
	{
		if($tutar > $this->sabitB["sabitBilgiMinSepetUcret"])
		{
			return 0;
		}
		else 
		{
			$kdv = $this->sabitB["sabitBilgiKargoUcret"] * 0.20;
			return $kdv;
		}
	}

	public function akinSoftConnection($command, $username, $password, $devCode, $devPass, $timeOut)
	{
		$userLink="command=".$command."&username=".$username."&password=".$password."&devCode=".$devCode."&devPass=".$devPass."&timeOut=".$timeOut."";
		return $userLink;
	}

	public function akinSoftGetParametreApi($tpwd, $command, $sirketKodu, $calismaYili, $envHesabi, $maliyetTipi, $tarih1, $tarih2, $doviziDahilEt, $sadeceMikEnv, $StokEkSart, $envSubeSart)
	{
		$editHTTPGetLink = "tpwd=".$tpwd."&command=".$command."&sirketKodu=".$sirketKodu."&calismaYili=".$calismaYili."&envHesabi=".$envHesabi."&maliyetTipi=".$maliyetTipi."&tarih1=".$tarih1."&tarih2=".$tarih2."&doviziDahilEt=".$doviziDahilEt."&sadeceMikEnv=".$sadeceMikEnv."&StokEkSart=".$StokEkSart."&envSubeSart=".$envSubeSart."";
		return $editHTTPGetLink;
	}

	public function akinSoftPostApi($url,$data)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => $data,
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded'
		),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	public function akinSoftPostParametreApi($tpwd, $command, $sirketKodu, $calismaYili, $xmlValue)
	{
		$editHTTPLink = '&tpwd='.$tpwd.'&command='.$command.'&sirketKodu='.$sirketKodu.'&calismaYili='.$calismaYili.'&xmlValue='.$xmlValue.'&';
		return $editHTTPLink;
	}

	function parseToXML($htmlStr)
	{
		$xmlStr=str_replace('&lt;','<',$htmlStr);
		$xmlStr=str_replace('&gt;','>',$xmlStr);
		$xmlStr=str_replace('<KARACA>','',$xmlStr);
		$xmlStr=str_replace('</KARACA>','',$xmlStr);
		return $xmlStr;
	}

	public function arrayToXml($array, $xml = null)
	{
		
		if ($xml === null) {
			$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><WSH></WSH>');
		}

		foreach ($array as $key => $value) {
			if (is_numeric($key)) {
                $key = 'KARACA';
            }
			if (is_array($value)) {
				$this->arrayToXml($value, $xml->addChild($key));
			} 
			else {
				$xml->addChild($key, $value);
			}
		}

		return $xml->asXML();
	}

	public function findCategoryById($categories, $id, &$result) 
	{
		foreach ($categories as $category) {
			if ($category['kategoriId'] == $id) {
				$result[] = $category;
				if ($category['kategoriUstMenuId'] != 0) {
					$this->findCategoryById($categories, $category['kategoriUstMenuId'], $result);
				}
				break;
			}
		}
	}
}


class JsonSerializer extends SimpleXmlElement implements JsonSerializable
{
    function jsonSerialize()
    {
        if (count($this)) {
            // serialize children if there are children
            foreach ($this as $tag => $child) {
                // child is a single-named element -or- child are multiple elements with the same name - needs array
                if (count($child) > 1) {
                    $child = [$child->children()->getName() => iterator_to_array($child, false)];
                }
                $array[$tag] = $child;
            }
        } else {
            // serialize attributes and text for a leaf-elements
            foreach ($this->attributes() as $name => $value) {
                $array["_$name"] = (string) $value;
            }
            $array["__cdata"] = (string) $this;
        }

        if ($this->xpath('/*') == array($this)) {
            // the root element needs to be named
            $array = [$this->getName() => $array];
        }

        return $array;
    }
}