<?php
include("../../System/Config.php");
$primaryId = $_POST['Id'];
$sartlar = [];
if ($_SESSION["islemDilId"] != "") {
    $sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $_SESSION["islemDilId"]]);
} else {
    $sartlar = array_merge($sartlar, ["varyantDilBilgiDilId" => $sabitB["sabitBilgiPanelVarsayilanDilId"]]);
}
$sartlar = array_merge($sartlar, [
    "urunVaryantId" => $primaryId
]);

$list = $db->get("UrunVaryantlari", [
    "[>]VaryantDilBilgiler" => ["UrunVaryantlari.urunVaryantVaryantId" => "varyantDilBilgiVaryatId"]
], "*", $sartlar);

// echo "<pre>"; 
// print_r($sartlar);
// echo "</pre>";
?>
<div class="card-body">
    <form id="altBirimGuncellePost" class="form" action="" method="post">
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
                            <option value="<?= $sorgu['varyantId'] ?>" <?php if ($sorgu['varyantId'] == $list['urunVaryantVaryantId']) { echo " selected";} ?>>
                                <?= $sorgu['varyantDilBilgiBaslik'] . " (" . $durum . ")" ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="width:100%!important">
                    <label for="userinput1"><?= $fonk->getPDil("Markaya Göre Fiyat") ?></label>
                    <input type="number" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantFiyat" name="urunVaryantFiyat" value="<?= $list['urunVaryantFiyat'] ?>" autocomplete="off">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group" style="width:100%!important">
                    <label for="urunVaryantKampanyasizFiyat"><?= $fonk->getPDil("Liste Fiyat") ?></label>
                    <input type="number" min="0" step="0.01" placeholder="<?= $fonk->getPDil("Fiyat") ?> (0.00)" class="form-control border-primary" id="urunVaryantKampanyasizFiyat" name="urunVaryantKampanyasizFiyat" value="<?= $list['urunVaryantKampanyasizFiyat'] ?>" autocomplete="off">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="userinput1"></label>
                    <div class="row skin skin-square">
                        <div class="col-md-12">
                            <fieldset>
                                <div class="icheckbox_square-green" style="position: relative;"><input type="checkbox" name="urunVaryantDefaultSecim" id="urunVaryantDefaultSecim" value="1" style="position: absolute; opacity: 0;" <?php if($list['urunVaryantDefaultSecim']==1){echo "checked";}?>></div>
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
                        $item = $db->get("UrunVaryantDilBilgiler", "*", [
                            "urunVaryantDilBilgiUrunId" => $list['urunVaryantUrunId'],
                            "urunVaryantDilBilgiVaryantId" => $primaryId,
                            "urunVaryantDilBilgiDilId" => $dil["dilId"]
                        ]);
                    ?>
                        <input type="hidden" name="urunVaryantDilBilgiId-<?= $dil["dilId"] ?>" id="urunVaryantDilBilgiId-<?= $dil["dilId"] ?>" value="<?= $item["urunVaryantDilBilgiId"] ?>">
                        <input type="hidden" name="urunVaryantDilBilgiDilId-<?= $dil["dilId"] ?>" id="urunVaryantDilBilgiDilId-<?= $dil["dilId"] ?>" value="<?= $dil["dilId"] ?>" />

                        <div id="headingCollapse64" data-toggle="collapse" data-target="#listItem2-<?= $dil["dilId"] ?>" class="card-header mt-1 border-info pointer" aria-expanded="true">
                            <b><?= $dil["dilAdi"] ?></b>
                        </div>
                        <div id="listItem2-<?= $dil["dilId"] ?>" role="tabpanel" aria-labelledby="headingCollapse64" class="border-info no-border-top card-collapse collapse <?php if ($item["urunVaryantDilBilgiDurum"] == 1) {echo "show";} ?>" aria-expanded="false">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Adi") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" onkeyup="toSeo('urunVaryantDilBilgiAdi-<?= $dil['dilId'] ?>','urunVaryantDilBilgiSlug-<?= $dil['dilId'] ?>')" class="form-control border-primary" id="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiAdi-<?= $dil["dilId"] ?>" value="<?= $item['urunVaryantDilBilgiAdi'] ?>" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Link") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" class="form-control border-primary" id="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiSlug-<?= $dil["dilId"] ?>" value="<?= $item['urunVaryantDilBilgiSlug'] ?>" autocomplete="off" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Durumu") ?></label>
                                                <fieldset>
                                                    <div class="float-left">
                                                        <input type="checkbox" class="switch hidden" data-on-label="<?= $fonk->getPDil("Aktif") ?>" data-off-label="<?= $fonk->getPDil("Pasif") ?>" id="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiDurum-<?= $dil["dilId"] ?>" value="1" <?php if ($item['urunVaryantDilBilgiDurum'] == 1) { echo 'checked';} ?>>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>"><?= $fonk->getPDil("Etiketler") ?><small style="color:red;margin-left:1rem">*</small></label>
                                                <input type="text" class="form-control border-primary" id="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" name="urunVaryantDilBilgiEtiketler-<?= $dil["dilId"] ?>" value="<?= $item['urunVaryantDilBilgiEtiketler'] ?>" autocomplete="off">
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
                    <input type="hidden" name="urunVaryantUrunId" id="urunVaryantUrunId" value="<?= $list['urunVaryantUrunId'] ?>" />
                    <input type="hidden" name="urunVaryantId" id="urunVaryantId" value="<?=$primaryId?>" />
                    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
                    <button type="submit" class="btn btn-success" id="altBirimButton"><i class="la la-floppy-o"></i> <?= $fonk->getPDil("Güncelle") ?></button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php include("../../Scripts/kayitJs.php"); ?>
<script type="text/javascript">
    $(".editorCk3").each(function() {
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
    $('#altBirimGuncellePost').submit(function(e) {
        document.getElementById('altBirimButton').disabled = true;
        e.preventDefault(); //submit postu kesyoruz
        var data = new FormData(this);
        $(".editorCk3").each(function() {
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
                    altBirimListele(<?= $list['urunVaryantUrunId'] ?>);
                    altBirimEkleForm(<?= $list['urunVaryantUrunId'] ?>);
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