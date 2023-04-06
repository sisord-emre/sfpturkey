<?php 
include ("../Panel/System/Config.php");
extract($_POST);

if($_POST['formdan']=="3")
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
        $mailKontrol = $db->get('Uyeler', "*", [
            'uyeMail'=> $uyeMail
        ]);

		if($mailKontrol)
		{
            $tkn =hash("sha256", md5(time()));
            $parametreler=array(
                'sifreUnuttumEmail' =>$uyeMail,
                'sifreUnuttumToken' => $tkn,
                'sifreUnuttumTarihi' => date("Y-m-d H:i:s")
            );
            $query = $db->insert('SifreUnuttum', $parametreler);

            if( $query)
            {
                include("reset-password-mail-template.php");
                $sonuc=$fonk->mailGonder($uyeMail,'SFP Turkey-Reset Password-Form',$body,$sabitB);
                if($sonuc=='1')
                {
                    echo "3";
                }
                else
                {
                    echo "2";
                }
            }
            else
            {
                echo '4';
            }
		}
		else
		{
			echo '1';
		}
	}
}
?>