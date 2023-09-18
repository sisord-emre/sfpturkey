<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor
$sliderDilId=$_POST['sliderDilId'];
$eklemeYetki=$_POST['eklemeYetki'];
$duzenlemeYetki=$_POST['duzenlemeYetki'];
$silmeYetki=$_POST['silmeYetki'];
?>
<div class="row" id="card-drag-area">
  <?php
  $slider = $db->select("Slider", [
    "[>]Diller" => ["Slider.sliderDilId" => "dilId"]
  ],"*",[
    "sliderDilId" => $sliderDilId,
    "ORDER" => [
      "sliderSirasi" => "ASC"
    ]
  ]);
  foreach($slider as $item){
    ?>
    <div class="col-md-2" id="sliderSatir-<?=$item["sliderId"];?>">
      <div class="card grab" style="border: 1px solid #cacfe7;">
        <div class="card-content">
          <div class="card-content">
            <img class="card-img-top img-fluid" src="<?=$item['sliderBaseUrl'].$item["sliderGorsel"]?>">
            <div class="card-body" style="height: 150px;">
              <h4 class="card-title"><?=$item["sliderBaslik"]?></h4>
              <p class="card-text"><?=$item["sliderAciklama"]?></p>
              <?php if ($item["sliderButtonYazi"]!="") { ?>
                <a href="<?=$item["sliderButtonLink"]?>" target="_blank" class="btn btn-outline-amber"><?=$item["sliderButtonYazi"]?></a>
              <?php } ?>
            </div>
            <div class="card-footer" style="text-align: right;padding: 0.5rem 0.5rem;">
              <?php if($item['sliderDurum']==1){?><div class="badge badge-success" style="float: left;margin-top: 0.75rem;"><?=$fonk->getPDil("Aktif")?></div><?php } else{ ?><div class="badge badge-danger" style="float: left;margin-top: 0.75rem;"><?=$fonk->getPDil("Pasif")?></div><?php } ?>
              <?php if ($duzenlemeYetki) { ?>
                <button type="button" onclick="SayfaGetir('<?=$menuId?>','Slider/sliderKayit.php','<?=$item["sliderId"];?>');" class="btn btn-warning net-button"><i class="la la-edit"></i></button>
              <?php } if ($silmeYetki) { ?>
                <button type="button" onclick="sliderSil('<?=$item["sliderId"];?>')" class="btn btn-danger net-button"><i class="la la-trash-o"></i></button>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
<?php if (count($slider)>0 && $duzenlemeYetki) { ?>
  <div class="col-md-12" style="text-align: center;padding: 2rem;">
    <button type="button" class="btn btn-success" onclick="sliderDuzenKayit()"><i class="la la-floppy-o"></i> <?php echo $fonk->getPDil("Slider Düzenini Kaydet");?></button>
  </div>
<?php } ?>
<script src="Assets/app-assets/vendors/js/extensions/dragula.min.js"></script>
<script src="Assets/app-assets/js/scripts/extensions/drag-drop.js"></script>
