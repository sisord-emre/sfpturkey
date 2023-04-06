<?php
include ("../../System/Config.php");

$menuId=$_POST['menuId'];//menu id alınıyor
$footerMenuDilId=$_POST['footerMenuDilId'];
$eklemeYetki=$_POST['eklemeYetki'];
$duzenlemeYetki=$_POST['duzenlemeYetki'];
$silmeYetki=$_POST['silmeYetki'];
?>
<div class="cf nestable-lists">
  <div class="dd" id="nestable">
    <ol class="dd-list">
      <?php
      $footerMenuler = $db->select("FooterMenuler", [
        "[>]Diller" => ["FooterMenuler.footerMenuDilId" => "dilId"]
      ],"*",[
        "footerMenuDilId" => $footerMenuDilId,
        "footerMenuUstMenuId" => 0,
        "ORDER" => [
          "footerMenuSirasi" => "ASC"
        ]
      ]);
      foreach($footerMenuler as $item){
        ?>
        <li class="dd-item dd3-item" data-id="<?=$item["footerMenuId"]?>" id="menuSatir-<?=$item["footerMenuId"]?>">
          <div class="dd-handle dd3-handle"></div><div class="dd3-content"><?=$item["footerMenuBaslik"]?> (<?=$item["footerMenuLink"]?>)</div>
          <?php if ($duzenlemeYetki) { ?>
            <button type="button" onclick="SayfaGetir('<?=$menuId?>','FooterMenuler/footermenulerKayit.php','<?=$item["footerMenuId"];?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
          <?php } if ($silmeYetki) { ?>
            <button type="button" onclick="menuSil('<?=$item["footerMenuId"];?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
          <?php }
          $footerAltMenuler = $db->select("FooterMenuler", [
            "[>]Diller" => ["FooterMenuler.footerMenuDilId" => "dilId"]
          ],"*",[
            "footerMenuDilId" => $footerMenuDilId,
            "footerMenuUstMenuId" => $item["footerMenuId"],
            "ORDER" => [
              "footerMenuSirasi" => "ASC"
            ]
          ]);
          if (Count($footerAltMenuler)>0) {
            ?>
            <ol class="dd-list">
              <?php foreach($footerAltMenuler as $itemAlt){ ?>
                <li class="dd-item dd3-item" data-id="<?=$itemAlt["footerMenuId"]?>" id="menuSatir-<?=$itemAlt["footerMenuId"]?>">
                  <div class="dd-handle dd3-handle"></div><div class="dd3-content"><?=$itemAlt["footerMenuBaslik"]?> (<?=$itemAlt["footerMenuLink"]?>)</div>
                  <?php if ($duzenlemeYetki) { ?>
                    <button type="button" onclick="SayfaGetir('<?=$menuId?>','FooterMenuler/footermenulerKayit.php','<?=$itemAlt["footerMenuId"];?>');" class="btn btn-warning net-button" style="margin-top: -2.3rem;margin-right: 4.5rem;"><i class="la la-edit"></i></button>
                  <?php } if ($silmeYetki) { ?>
                    <button type="button" onclick="menuSil('<?=$itemAlt["footerMenuId"];?>')" class="btn btn-danger net-button" style="margin-top: -2.3rem;margin-right: 2rem;"><i class="la la-trash-o"></i></button>
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
<?php if (count($footerMenuler)>0 && $duzenlemeYetki) { ?>
  <div class="col-md-12" style="text-align: center;padding: 2rem;">
    <button type="button" class="btn btn-success" onclick="menuDuzenKayit()"><i class="la la-floppy-o"></i> <?php echo $fonk->getPDil("Menü Düzenini Kaydet");?></button>
  </div>
<?php } ?>
