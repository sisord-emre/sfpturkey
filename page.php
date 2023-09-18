<?php 
include('layouts/header.php');
$seo = $_GET['seo'];

$sayfalar = $db->get("Sayfalar",[
"[<]SayfaDilBilgiler" => ["Sayfalar.sayfaId" => "sayfaDilBilgiSayfaId"],
],"*",[
"sayfaDilBilgiDilId" => $_SESSION['dilId'],
"sayfaDilBilgiSlug" => $seo,
]);
?>
<div id="nt_content">
      <!--shop banner-->
      <div class="kalles-section page_section_heading">
        <div class="page-head pr oh cat_bg_img page_head_">
            <div class="parallax-inner nt_parallax_false lazyload nt_bg_lz pa t__0 l__0 r__0 b__0" data-bgset="assets/img/banner.jpg"></div>
            <div class="container pr z_100">
                <h1 class="mb__5 cw">
                    <?=$sayfalar["sayfaDilBilgiBaslik"]; ?>
                </h1>
            </div>
        </div>
    </div>
    <!--end shop banner-->

    <!--page content-->
    <div class="kalles-section container mt__50 mb__50">
        <div class="terms cb">
            <?=$sayfalar["sayfaDilBilgiIcerik"]; ?>
        </div>
    </div>
    <!--end page content-->
</div>

<?php include('layouts/footer.php') ?>