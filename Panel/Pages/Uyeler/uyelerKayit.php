<?php
include("../../System/Config.php");

$menuId = $_POST['menuId']; //menu id alınıyor

///menu bilgileri alınıyor
$hangiMenu = $db->get("Menuler", "*", [
    "menuUstMenuId" => $menuId,
    "menuOzelGorunuruk" =>    1,
    "menuTipi" =>    1 //kayıt için 1 listeleme için 2 diğer sayfalar içim 3 yazılmalı****
]);

for ($i = 0; $i < Count($kullaniciYetkiler); $i++) { //kullanıcının yetkilerini sorguluyoruz
    $kullaniciYetki = json_decode($kullaniciYetkiler[$i], true);

    if ($kullaniciYetki['menuYetkiID'] == $menuId) { //menu id

        if ($kullaniciYetki['listeleme'] == "on") {
            $listelemeYetki = true;
        } //listeleme

        if ($kullaniciYetki['ekleme'] == "on") {
            $eklemeYetki = true;
        } //ekleme

        if ($kullaniciYetki['silme'] == "on") {
            $silmeYetki = true;
        } //silme

        if ($kullaniciYetki['duzenleme'] == "on") {
            $duzenlemeYetki = true;
        } //duzenleme

    }
}
if (!$eklemeYetki && !$duzenlemeYetki) {
    //yetki yoksa gözükecek yazi
    echo '<div class="alert alert-icon-right alert-warning alert-dismissible mb-2" role="alert">
	<span class="alert-icon"><i class="la la-warning"></i></span>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
	<span aria-hidden="true">×</span>
	</button>
	<strong>' . $fonk->getPDil("Yetki!") . ' </strong> ' . $fonk->getPDil("Bu Menüye Erişim Yetkiniz Bulunmamaktadır.") . '
	</div>';
} else { //Listeleme Yetkisi Var

    $tableName = $hangiMenu['menuTabloAdi']; //tabloadı istenirse burdan değiştirilebilir

    $tabloPrimarySutun = $hangiMenu['menuTabloPrimarySutun']; //primarykey sutunu

    $baslik = $hangiMenu['menuAdi']; //başlıkta gözükecek yazı menu adi

    $duzenlemeSayfasi = $tableName . '/' . strtolower($tableName) . 'Kayit.php';
    $listelemeSayfasi = $tableName . "/" . strtolower($tableName) . "Listeleme.php";

    $primaryId = $_POST['update']; //düzenle isteği ile gelen

    if ($_POST['formdan'] != "1") {
        //sayfayı görüntülenme logları
        $fonk->logKayit(6, $_SERVER['REQUEST_URI'] . "?primaryId=" . $primaryId); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
    }

    ////güncllenecek parametreler***
    //Forumdan gelenler
    extract($_POST); //POST parametrelerini değişken olarak çevirir
    ////güncllenecek parametreler***

    if ($_POST['formdan'] == "1") {
        $fonk->csrfKontrol();

        if ($primaryId != "") 
        {
            //günclelemedeki parametreler
            $parametreler = array(
                'uyeTcVergiNo' => $uyeTcVergiNo,
                'uyeAdi' => $uyeAdi,
                'uyeSoyadi' => $uyeSoyadi,
                'uyeMail' => $uyeMail,
                'uyeTel' => $uyeTel,
                'uyeFirmaAdi' => $uyeFirmaAdi,
                'uyeDurum' => $uyeDurum, //pasif
                'uyeIndirimOrani' => $uyeIndirimOrani
            );

            $files = array_filter($_FILES['uyeTicaretSicilGazetesi']['name']); 
            $fileName = mt_rand();
            $tmpFilePath = $_FILES['uyeTicaretSicilGazetesi']['tmp_name'];
            if ($tmpFilePath != "")
            {
                $ext = pathinfo($_FILES['uyeTicaretSicilGazetesi']['name'], PATHINFO_EXTENSION);
                $newFilePath = "../../../Images/TicaretSicilGazetesi/".$fileName ."." .$ext;
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
                $newFilePath3 = "../../../Images/ImzaSirkuleri/".$fileName3 ."." .$ext;
                if(move_uploaded_file($tmpFilePath3, $newFilePath3)) 
                {
                    $parametreler=array_merge($parametreler,array('uyeMukerrerImza' => $fileName3. "." .$ext));
                }		
            }

            $files2 = array_filter($_FILES['uyeVergiLevhasiDosya']['name']);  
            $faturaAdi2 = mt_rand();
            $tmpFilePath2 = $_FILES['uyeVergiLevhasiDosya']['tmp_name'];
            if($tmpFilePath2 != "")
            {
                $ext = pathinfo($_FILES['uyeVergiLevhasiDosya']['name'], PATHINFO_EXTENSION);
                $newFilePath2 = "../../../Images/VergiLevhasi/".$faturaAdi2 ."." . $ext;
                if(move_uploaded_file($tmpFilePath2, $newFilePath2)) 
                {
                    $silUyeVergiLevhasi = $db->delete("UyeVergiLevhasi", [
                        "uyeVergiLevhasiUyeId" => $primaryId
                    ]);	

                    $datas=array(
                        'uyeVergiLevhasiUyeId' => $primaryId,
                        'uyeVergiLevhasiBaseUrl' => $sabitB["sabitBilgiSiteUrl"]."Images/VergiLevhasi//",
                        'uyeVergiLevhasiDosya' => $faturaAdi2. "." .$ext
                    );
                    $uyeVergiLevhasi = $db->insert('UyeVergiLevhasi', $datas);
                }
            }

            $fonk->logKayit(2, $tableName . ' ; ' . $primaryId . ' ; ' . json_encode($parametreler)); //1=>ekleme,2=>güncelleme,3=>silme,4=>oturum açma,5=>diğer
            ///güncelleme
            $query = $db->update($tableName, $parametreler, [
                $tabloPrimarySutun => $primaryId
            ]);
        } 
       
        if ($query) 
        { 
            //uyarı metinleri
            echo '
            <div class="alert alert-success alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <strong>' . $fonk->getPDil("Başarılı!") . '</strong> ' . $fonk->getPDil("Kayıt İşlemi Başarıyla Gerçekleşmiştir.") . '
            </div>';
        } 
        else 
        {
            echo '
            <div class="alert alert-danger alert-dismissible mb-2" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
            <strong>' . $fonk->getPDil("Hata!") . '</strong> ' . $fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu. Lütfen Tekrar Deneyiniz.") . '(' . $db->error . ')
            </div>';
        }
    }
    echo "<script>$('#ustYazi').html('&nbsp;-&nbsp;'+'" . $fonk->getPDil($baslik) . "');</script>"; //Başlık Güncelleniyor
    //update ise bilgiler getiriliyor
    if ($primaryId != "") {
        $Listeleme = $db->get($tableName, "*", [
            $tabloPrimarySutun => $primaryId
        ]);
    }
?>
    <!-- Basic form layout section start -->
    <section id="basic-form-layouts">
        <div class="row match-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-colored-form-control"><?= $fonk->getPDil($baslik) ?></h4>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                          <?php if ($listelemeYetki) { ?><button type="button" onclick="SayfaGetir('<?= $menuId ?>','<?= $listelemeSayfasi ?>');" class="btn mr-1 btn-primary btn-sm"><i class="la la-th-list"></i> <?= $fonk->getPDil("Listeleme") ?></button><?php } ?>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">

                            <form id="formpost" class="form" action="" method="post">
                                <div class="form-body">

                                    <!-- Güncellenecek Kısımlar -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeAdi"><?= $fonk->getPDil("Adınız") ?></label>
                                                <input type="text" class="form-control border-primary" id="uyeAdi" name="uyeAdi" value="<?= $Listeleme['uyeAdi'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeSoyadi"><?= $fonk->getPDil("Soyadınız") ?></label>
                                                <input type="text" class="form-control border-primary" id="uyeSoyadi" name="uyeSoyadi" value="<?= $Listeleme['uyeSoyadi'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeMail"><?= $fonk->getPDil("Email") ?></label>
                                                <input type="email" class="form-control border-primary" id="uyeMail" name="uyeMail" value="<?= $Listeleme['uyeMail'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeTel"><?= $fonk->getPDil("Telefon") ?></label>
                                                <input type="tel" class="form-control border-primary" id="uyeTel" name="uyeTel" value="<?= $Listeleme['uyeTel'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeFirmaAdi"><?= $fonk->getPDil("Şirket Adı") ?></label>
                                                <input type="text" class="form-control border-primary" id="uyeFirmaAdi" name="uyeFirmaAdi" value="<?= $Listeleme['uyeFirmaAdi'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="uyeTcVergiNo"><?= $fonk->getPDil("Vergi Numarası") ?></label>
                                                <input type="text" class="form-control border-primary" id="uyeTcVergiNo" name="uyeTcVergiNo" value="<?= $Listeleme['uyeTcVergiNo'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
											<div class="form-group">
												<label for="uyeMukerrerImza"><?= $fonk->getPDil("Mükerrer İmza") ?></label>
                                                <a href="<?=$Listeleme['uyeMukerrerImzaBaseUrl']?><?=$Listeleme['uyeMukerrerImza']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="uyeMukerrerImza" id="uyeMukerrerImza" accept=".png, .jpg, .jpeg, .pdf">
													<label class="custom-file-label" name="uyeMukerrerImza" id="uyeMukerrerImza" for="uyeMukerrerImza" aria-describedby="uyeMukerrerImza"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

                                        <div class="col-md-6">
											<div class="form-group">
												<label for="uyeTicaretSicilGazetesi"><?= $fonk->getPDil("Ticaret sicil gazetesi") ?></label>
                                                <a href="<?=$Listeleme['uyeTicaretSicilGazetesiBaseUrl']?><?=$Listeleme['uyeTicaretSicilGazetesi']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
                                                <div class="custom-file">
													<input type="file" class="custom-file-input" name="uyeTicaretSicilGazetesi" id="uyeTicaretSicilGazetesi" accept=".png, .jpg, .jpeg, .pdf">
													<label class="custom-file-label" name="uyeTicaretSicilGazetesi" id="uyeTicaretSicilGazetesi" for="uyeTicaretSicilGazetesi" aria-describedby="uyeTicaretSicilGazetesi"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

                                        <div class="col-md-6">
											<div class="form-group">
												<label for="uyeVergiLevhasiDosya"><?= $fonk->getPDil("Vergi Levhası") ?> </label>
                                                <?php
                                                $vergiLevhasi = $db->select("UyeVergiLevhasi","*",[
                                                    "uyeVergiLevhasiUyeId" => $primaryId
                                                ]);
                                                foreach ($vergiLevhasi as $value) {?>
                                                    <a href="<?=$value['uyeVergiLevhasiBaseUrl']?><?=$value['uyeVergiLevhasiDosya']?>" class="btn btn-info btn-sm" style="padding:0.1rem 0.3rem;" target="_blank"><i class="la la-search"></i></a>
									
                                                <?php } ?>
												<div class="custom-file">
													<input type="file" class="custom-file-input" name="uyeVergiLevhasiDosya" id="uyeVergiLevhasiDosya" accept=".png, .jpg, .jpeg, .pdf">
													<label class="custom-file-label" name="uyeVergiLevhasiDosya" id="uyeVergiLevhasiDosya" for="uyeVergiLevhasiDosya" aria-describedby="uyeVergiLevhasiDosya"><?= $fonk->getPDil("Dosya Seçiniz") ?></label>
												</div>
											</div>
										</div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="uyeIndirimOrani"><?= $fonk->getPDil("İndirim Oranı") ?></label>
                                                <input type="text" class="form-control border-primary" id="uyeIndirimOrani" name="uyeIndirimOrani" value="<?= $Listeleme['uyeIndirimOrani'] ?>" autocomplete="off" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="uyeDurum"><?= $fonk->getPDil("Durumu") ?></label>
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="uyeDurum" name="uyeDurum" value="1" <?php if ($Listeleme['uyeDurum'] == 1) {  echo 'checked'; } ?>>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Güncellenecek Kısımlar -->

                                </div>
                                <div class="form-group" style="text-align: center;margin-top:15px">
                                    <input type="hidden" name="update" value="<?= $Listeleme[$tabloPrimarySutun] ?>" />
                                    <input type="hidden" name="menuId" value="<?= $menuId ?>" />
                                    <input type="hidden" name="formdan" value="1" />
                                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
                                    <button type="submit" class="btn mb-1 btn-success"><i class="la la-floppy-o"></i> 
                                    <?php 
                                    if ($primaryId != "") { echo $fonk->getPDil("Güncelle");} 
                                    else {echo $fonk->getPDil("Kayıt"); } 
                                    ?>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- // Basic form layout section end -->

<?php }
include("../../Scripts/kayitJs.php"); ?>
<script type="text/javascript">
    

    $('#formpost').submit(function(e) {
        e.preventDefault(); //submit postu kesyoruz
        var data = new FormData(this);
        $('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
        $.ajax({
            type: "POST",
            url: "<?= $_SERVER['REQUEST_URI'] ?>",
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                $('#Sayfalar').html(res);
            },
            error: function(jqXHR, status, errorThrown) {
                alert("Result: " + status + " Status: " + jqXHR.status);
            }
        });
    });
</script>