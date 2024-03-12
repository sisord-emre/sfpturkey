<?php
include("../../System/Config.php");
$primaryId = $_POST['Id'];

?>
<div class="card-body">
    <form id="altBirimPost" class="form" action="" method="post">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group" style="width:100%!important">
                    <label for="userinput1"><?= $fonk->getPDil("Marka") ?></label>
                    <select class="select2 form-control block" name="urunVaryantVaryantId" style="width:100%!important">
                        <option value=""><?= $fonk->getPDil("Marka Seçiniz") ?></option>
                        <?php
                        $sartlar = [];
                        if ($_SESSION["islemDilId"] != "") {
                            $sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $_SESSION["islemDilId"]]);
                        } else {
                            $sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
                        }
                        $sorguList = $db->select("Varyantlar", [
                            "[>]VaryantDilBilgiler" => ["Varyantlar.varyantId" => "varyantDilBilgiVaryatId"]
                        ], "*", $sartlar);
                        foreach ($sorguList as $sorgu) {
                            $durum = $fonk->getPDil("Pasif");
                            if ($sorgu["varyanDurum"] == 1) {
                                $durum = $fonk->getPDil("Aktif");
                            }
                        ?>
                            <option value="<?= $sorgu['varyantId'] ?>">
                                <?= $sorgu['varyantDilBilgiBaslik'] . " (" . $durum . ")" ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="width:100%!important">
                    <label for="userinput1"><?= $fonk->getPDil("Markaya Göre Satış Fiyat/Kampanya Satış Fiyatı") ?></label>
                    <input type="text" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantFiyat" name="urunVaryantFiyat" autocomplete="off">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="width:100%!important">
                    <label for="urunVaryantKampanyasizFiyat"><?= $fonk->getPDil("Liste Fiyat") ?></label>
                    <input type="text" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantKampanyasizFiyat" name="urunVaryantKampanyasizFiyat" autocomplete="off">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="userinput1"></label>
                    <div class="row skin skin-square">
                        <div class="col-md-12">
                            <fieldset>
                                <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="urunVaryantDefaultSecim" id="urunVaryantDefaultSecim" value="1" style="position: absolute; opacity: 0;"></div>
                                <label for="urunVaryantDefaultSecim" class=""><?=$fonk->getPDil("Default Marka Olsun")?></label>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card collapse-icon accordion-icon-rotate">
                    <?php
                    $dilList = $db->select("Diller", "*");
                    foreach ($dilList as $dil) {
                    ?>
                        <div id="headingCollapse64" data-toggle="collapse" data-target="#listItem2-<?= $dil["dilId"] ?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
                            <b><?= $dil["dilAdi"] ?></b>
                        </div>
                        <div id="listItem2-<?= $dil["dilId"] ?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse" aria-expanded="false">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Adi") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" onkeyup="toSeo('urunVaryantDilBilgiAdi-<?= $dil['dilId'] ?>','urunVaryantDilBilgiSlug-<?= $dil['dilId'] ?>')" class="form-control border-primary" id="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Link") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" class="form-control border-primary" id="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Durumu") ?></label>
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" value="1">
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Etiketler") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" class="form-control border-primary" id="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group" style="text-align: center;margin-top:10px">
                    <input type="hidden" name="urunVaryantUrunId" id="urunVaryantUrunId" value="<?= $primaryId ?>" />
                    <input type="hidden" name="urunVaryantId" id="urunVaryantId" /><!-- update ise doludur -->
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
                    <button type="submit" class="btn btn-success" id="altBirimButton"><i class="la la-floppy-o"></i> <?= $fonk->getPDil("Kayıt") ?></button>
                </div>
            </div>
        </div>
    </form>
</div>
<?php include("../../Scripts/kayitJs.php"); ?>
<script type="text/javascript">
    $(".editorCk2").each(function() {
		let editorId = $(this).attr('id');
		CKEDITOR.replace(editorId, { //ckeditor kullanıldığında açılır
			height: '350px',
			extraPlugins: 'forms',
			uiColor: '#CCEAEE',
			//Dosya Yöneticisi resim gözat için
			filebrowserBrowseUrl: 'Assets/app-assets/fileman/index.html', // Öntanımlı Dosya Yöneticisi
			filebrowserImageBrowseUrl: 'Assets/app-assets/fileman/index.html?type=image', // Sadece Resim Dosyalarını Gösteren Dosya Yöneticisi
			removeDialogTabs: 'link:upload;image:upload' // Upload işlermlerini dosya Yöneticisi ile yapacağımız için upload butonlarını kaldırıyoruz
		});
	});

    ////// Alt Birim İşlemler
    $('#altBirimPost').submit(function(e) {
        document.getElementById('altBirimButton').disabled = true;
        e.preventDefault(); //submit postu kesyoruz
        var data = new FormData(this);
        $(".editorCk2").each(function() {
			let editorId = $(this).attr('id');
			data.append(editorId, CKEDITOR.instances[editorId].getData()); //ckeditor kullanılacağı zaman açılır 'ckeditor' yazan kısmı post keyidir
		});
        $('#altBirimList').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
        $.ajax({
            type: "POST",
            url: "Pages/Urunler/altBirimEkle.php",
            data: data,
            contentType: false,
            processData: false,
            success: function(res) {
                document.getElementById('altBirimButton').disabled = false;
                if (res == 1) {
                    altBirimListele(<?= $primaryId ?>);
                    altBirimEkleForm(<?= $primaryId ?>);
                } else {
                    alert('<?= $fonk->getPDil("Kayıt Esnasında Bir Hata Oluştu") ?>');
                }
            }
        });
    });

    function altBirimListele(Id) {
        $.ajax({
            type: "POST",
            url: "Pages/Urunler/altBirimList.php",
            data: {
                'Id': Id
            },
            success: function(res) {
                $('#altBirimList').html(res);
            }
        });
    }

    function altBirimEkleForm(Id) {
		$.ajax({
			type: "POST",
			url: "Pages/Urunler/altBirimEkleForm.php",
			data: {
				'Id': Id
			},
			success: function(res) {
				$('#altBirimGuncelleList').html(res);
			}
		});
	}
</script>