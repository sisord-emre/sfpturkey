<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor
$siteMenuDilId=$_POST['siteMenuDilId'];
$eklemeYetki=$_POST['eklemeYetki'];
$duzenlemeYetki=$_POST['duzenlemeYetki'];
$silmeYetki=$_POST['silmeYetki'];
?>
<div class="cf nestable-lists">
  <div class="dd" id="nestable">
    <ol class="dd-list">
      <?php
      $siteMenuler = $db->select("SiteMenuler", [
        "[>]Diller" => ["SiteMenuler.siteMenuDilId" => "dilId"]
      ],"*",[
        "siteMenuDilId" => $siteMenuDilId,
        "siteMenuUstMenuId" => 0,
        "ORDER" => [
          "siteMenuSirasi" => "ASC"
        ]
      ]);
      foreach($siteMenuler as $item){
        ?>
        <li class="dd-item dd3-item" data-id="<?=$item["siteMenuId"]?>" id="menuSatir-<?=$item["siteMenuId"]?>">
          <div class="dd-handle dd3-handle"></div><div class="dd3-content"><?=$item["siteMenuBaslik"]?> (<?=$item["siteMenuLink"]?>)</div>
          <?php if ($duzenlemeYetki) { ?>
            <button type="button" onclick="SayfaGetir('<?=$menuId?>','SiteMenuler/sitemenulerKayit.php','<?=$item["siteMenuId"];?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
          <?php } if ($silmeYetki) { ?>
            <button type="button" onclick="menuSil('<?=$item["siteMenuId"];?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
          <?php }
          $siteAltMenuler = $db->select("SiteMenuler", [
            "[>]Diller" => ["SiteMenuler.siteMenuDilId" => "dilId"]
          ],"*",[
            "siteMenuDilId" => $siteMenuDilId,
            "siteMenuUstMenuId" => $item["siteMenuId"],
            "ORDER" => [
              "siteMenuSirasi" => "ASC"
            ]
          ]);
          if (Count($siteAltMenuler)>0) {
            ?>
            <ol class="dd-list">
              <?php foreach($siteAltMenuler as $itemAlt){ ?>
                <li class="dd-item dd3-item" data-id="<?=$itemAlt["siteMenuId"]?>" id="menuSatir-<?=$itemAlt["siteMenuId"]?>">
                  <div class="dd-handle dd3-handle"></div><div class="dd3-content"><?=$itemAlt["siteMenuBaslik"]?> (<?=$itemAlt["siteMenuLink"]?>)</div>
                  <?php if ($duzenlemeYetki) { ?>
                    <button type="button" onclick="SayfaGetir('<?=$menuId?>','SiteMenuler/sitemenulerKayit.php','<?=$itemAlt["siteMenuId"];?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
                  <?php } if ($silmeYetki) { ?>
                    <button type="button" onclick="menuSil('<?=$itemAlt["siteMenuId"];?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
                  <?php } ?>
                </li>
              <?php } ?>
            </ol>
          <?php } ?>
        </li>
      <?php } ?>
    </ol>
  </div>
</div>
<?php if (count($siteMenuler)>0 && $duzenlemeYetki) { ?>
  <div class="col-md-12" style="text-align: center;padding: 2rem;">
    <button type="button" class="btn btn-success" onclick="menuDuzenKayit()"><i class="la la-floppy-o"></i> <?php echo $fonk->getPDil("Menü Düzenini Kaydet");?></button>
  </div>
<?php } ?>
