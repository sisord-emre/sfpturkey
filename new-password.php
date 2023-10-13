<?php
include("layouts/header.php");
extract($_POST);

$tkn = $_GET['tkn'];
$kontrol = $db->get('SifreUnuttum', "*", [
    'sifreUnuttumToken' => $tkn
]);

$islemTarihi = $kontrol["sifreUnuttumTarihi"];
$simdikiZaman = date("Y-m-d Y-m-d H:i:s");
$gecerlilikSuresi = date('Y-m-d H:i:s', strtotime('+48 hour', strtotime($islemTarihi)));

if ($gecerlilikSuresi < $simdikiZaman) {
    echo '<script>
	swal("Hata!", "Şifre yineleme süresi bitti lütfen tekrar istekte bulunun.", "error")
   	.then((value) => {
 		window.location.href="account.php";
  	});
  	</script>';
    exit;
}
?>

<div id="nt_content">
    <div class="container cb">
        <div class="row">
            <div class="col-12 col-md-6 login-form mt__60 mb-0 mb-md-5">
                <div id="CustomerLoginForm" class="kalles-wrap-form">
                    <h2>Yeni Parola Oluştur</h2>
                    <form id="newpasswordpost" method="post" action="">
                        <p class="form">
                            <label for="uyeSifre">Parola
                                <span class="required">*</span>
                                <span id="uyeSifreHata" class="span-color-red"></span>
                            </label>
                            <input type="password" class="form-control" name="uyeSifre" id="uyeSifre" aria-required="true">
                        </p>
                        
                        <input type="hidden" class="form-control" name="sifreUnuttumEmail" id="sifreUnuttumEmail" value="<?= $kontrol['sifreUnuttumEmail'] ?>" aria-required="true">

                        <p class="form">
                            <label for="uyeSifreTekrar">Parola tekrar
                                <span class="required">*</span>
                                <span id="uyeSifreTekrarHata" class="span-color-red"></span>
                            </label>
                            <input type="password" class="form-control" name="uyeSifreTekrar" id="uyeSifreTekrar" aria-required="true">
                        </p>

                       

                    <input type="hidden" name="formdan" value="4" />
                    <input type="submit" value="Kaydet" class="btn btn-sm">
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>


<?php include('layouts/footer.php') ?>

<script>
    $("#newpasswordpost").submit(function(e) {
        e.preventDefault();
        var error = 0;
        var data = new FormData(this);
        var uyeSifre = data.get("uyeSifre");
        if (uyeSifre == "") {
            document.getElementById("uyeSifreHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
            error++;
        }
        var uyeSifreTekrar = data.get("uyeSifreTekrar");
        if (uyeSifreTekrar == "") {
            document.getElementById("uyeSifreTekrarHata").innerHTML = "Bu alan boş bırakılmamalıdır";;
            error++;
        }
        if (error <= 0) {
            $.ajax({
                type: "POST",
                url: "ajax/user-new-password.php",
                data: data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(gelenSayfa) {
                    if (gelenSayfa == "0") {
                        swal("Hata!", "Lütfen robot olmadığınızı doğrulayın", "error");
                    } else if (gelenSayfa == "2") {
                        swal("Hata!", "Bir hata oluştu tekrar deneyin.", "error");
                    } else if (gelenSayfa == "3") {
                        swal("Başarılı", "İşlem başarılı", "success")
                            .then((value) => {
                                window.location.href = "<?= $sabitBilgiler['sabitBilgiSiteUrl']; ?>account";
                            });
                    } else if (gelenSayfa == "4") {
                        swal("Hata!", "Parolalar uyuşmuyor", "error");
                    }
                },
            });
        }
    });
</script>