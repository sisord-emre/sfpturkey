//dile Değerleri
var panelDil;
function getDil(key){
  if(panelDil!=null && panelDil!="null" && panelDil!=""){
    key=key.replace(/İ/g, 'i').replace(/ı/g, 'i').toLowerCase().replace(/ç/g, 'c').replace(/ö/g, 'o').replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ş/g, 's').replace(/[^\w\s]/gi, '').replace(/[^a-zA-Z0-9]/g, '-').substring(0,100);
    return panelDil[key];
  }else{
    return key;
  }
}
//!dile Değerleri

function PanelDilSecim(dil){
  window.location=location.protocol+"//"+window.location.hostname+window.location.pathname+"?panelDil="+dil;
}

window.onload = setInterval(SunucuZamani,1000);
function SunucuZamani()
{
  var d = new Date();
  var date = String("0" + d.getDate()).slice(-2);
  var month = String("0" + (parseInt(d.getMonth())+1)).slice(-2);
  var year = d.getFullYear();
  var hour =String("0" + d.getHours()).slice(-2);
  var min =String("0" + d.getMinutes()).slice(-2);
  var sec = String("0" + d.getSeconds()).slice(-2);
  document.getElementById("time").innerHTML=date+"."+month+"."+year+" "+hour+":"+min+":"+sec;
}

window.onerror=function (hata,url,satir){
  url="/";
  if(sessionStorage.getItem("sayfa")!=null && sessionStorage.getItem("sayfa")!="" && sessionStorage.getItem("sayfa")!="null"){
    url=sessionStorage.getItem("sayfa");
  }
  $.ajax({
    type: "POST",
    url: 'Scripts/jsHataKayit.php',
    async: true, // NO LONGER ALLOWED TO BE FALSE BY BROWSER
    cache:false,
    data:{'hata':hata,'url':url,'satir':satir}
  });
}

$(".menu-item").mousedown(function(ev){//menüdeki linki kopyalama
  var sayfaBilgiler=location.protocol+"//"+window.location.hostname+window.location.pathname+"?sayfaBilgi="+ev.currentTarget.outerHTML.split("SayfaGetir('")[1].split("')")[0].replaceAll("','", ",");
  if(ev.which == 1){//eğer datatable kayıtından farklı bir sayfaya geçiş yapıldıysa datatable setlemeleri sıfırlansın
    if(ev.currentTarget.outerHTML.split("SayfaGetir('")[1].split("')")[0].replaceAll("','", ",").split(",")[1]!=sessionStorage.getItem("dLink")){//eğer tıklanan menü linki ile data table linki farklı ise sıfırlıyor
      sessionStorage.setItem("dPage","");
      sessionStorage.setItem("dSearch","");
      sessionStorage.setItem("dLink","");
      sessionStorage.setItem("editId","");
      sessionStorage.setItem("orderDt","");
    }
  }
  else if(ev.which == 3)//sağ tık
  {
    var input = document.createElement("input");
    input.type = "text";
    input.value = sayfaBilgiler;
    document.body.appendChild(input);
    input.focus();
    input.select();
    input.setSelectionRange(0, 99999); // Mobil cihazlar için.
    document.execCommand("copy");
    document.body.removeChild(input);
    toastr.success(getDil("Menü Linki Kopyalandı."));
    return false;
  }else if (ev.which == 2) {//orta tık
    window.open(sayfaBilgiler, "_blank");
  }
});

$(window).on('mousedown', function(e) {
  if (e.which == 1) {//sol tık // data table ise table setleme bilgilerini alıyor
    if(e.target.className.indexOf("edit-button")>0){
      if(typeof table !== 'undefined' && table!=null && table!="" && table!="null"){//düzenlemeye tıklandığında eğer data table ise table setleme bilgilerini alır
        var pageInfo = table.page.info();
        var dSearch = table.search();
        sessionStorage.setItem("dPage",pageInfo["page"]);
        sessionStorage.setItem("dSearch",dSearch);
        sessionStorage.setItem("orderDt",dtOrder());
      }
      sessionStorage.setItem("dLink",_sayfa);
      sessionStorage.setItem("editId",e.target.onclick.toString().split("SayfaGetir('")[1].split("')")[0].replaceAll("','", ",").split(",")[2]);
    }
  }
  else if (e.which == 2) {//orta tık
    if(e.target.className.indexOf("edit-button")>0){
      var sayfaBilgiler=location.protocol+"//"+window.location.hostname+window.location.pathname+"?sayfaBilgi="+e.target.onclick.toString().split("SayfaGetir('")[1].split("')")[0].replaceAll("','", ",");
      window.open(sayfaBilgiler, "_blank");
    }
  }
});

function dtOrder(){
	var orderDt=[];
  if(document.querySelector("#listTable > thead > tr")!=null){
    var basliklar=document.querySelector("#listTable > thead > tr").cells;
    for (var i = 0; i < basliklar.length; i++) {
      if(basliklar[i].className=="sorting_asc"){
        orderDt=[i, 'asc'];
      }else if(basliklar[i].className=="sorting_desc"){
        orderDt=[i, 'desc'];
      }
    }
  }
	return orderDt;
}

$(document).ready(function() {
  var sayfaBilgi = new URLSearchParams(window.location.search).get('sayfaBilgi');
  if (sayfaBilgi!=null && sayfaBilgi!="null" && sayfaBilgi!="") {
    SayfaGetir(sayfaBilgi.split(",")[0],sayfaBilgi.split(",")[1],sayfaBilgi.split(",")[2]);
    return false;
  }
  if (sessionStorage.getItem("menuId")!=null && sessionStorage.getItem("menuId")!="" && sessionStorage.getItem("menuId")!="null" && sessionStorage.getItem("sayfa")!=null && sessionStorage.getItem("sayfa")!="" && sessionStorage.getItem("sayfa")!="null") {
    SayfaGetir(sessionStorage.getItem("menuId"),sessionStorage.getItem("sayfa"),sessionStorage.getItem("duzenleId"));
  }else {
    SayfaGetir("63",'GostergePaneli/ozet.php','');//varsayılan anasayfa
  }
});

$('#menuAra').keyup(function() {
  var that = this,$allListElements = $('ul.navigation-main > li');
  var $matchingListElements = $allListElements.filter(function(i, li) {
    var listItemText = $(li).text().toLocaleUpperCase('tr-TR'),
    searchText = that.value.toLocaleUpperCase('tr-TR');
    return ~listItemText.indexOf(searchText);
  });
  $allListElements.hide();
  $matchingListElements.show();
  $allListElements.parents('.navigation-main').hide();
  $matchingListElements.parents('.navigation-main').show();
});

var stackMenu = [];
var stackSayfa = [];
var stackDuzenleId = [];
stackMenu.shift();
stackSayfa.shift();
stackDuzenleId.shift();
function SayfaGetir(menuId,sayfa,duzenleId){
  if (menuId=="undefined" || menuId=="null" || menuId==null) {
    menuId="";
  }
  if (sayfa=="undefined" || sayfa=="null" || sayfa==null) {
    sayfa="";
  }
  if (duzenleId=="undefined" || duzenleId=="null" || duzenleId==null) {
    duzenleId="";
  }
  if (sayfa=="") {
    return false;
  }
  $('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
  _menuId=menuId;
  _sayfa=sayfa;
  _duzenleId=duzenleId;
  sessionStorage.setItem("menuId",menuId);//son sayfa oturuma aktarılıyor
  sessionStorage.setItem("sayfa",sayfa);//son sayfa oturuma aktarılıyor
  sessionStorage.setItem("duzenleId",duzenleId);//son sayfa oturuma aktarılıyor
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && document.getElementsByClassName('sidenav-overlay d-block').length==1) {
    $('#mobilmenu').click();//mobilde menu açık kalmasın diye
  }
  $.ajax({
    type: "POST",
    url: 'Pages/'+sayfa,
    async: true, // NO LONGER ALLOWED TO BE FALSE BY BROWSER
    cache:false,
    data:{'menuId':menuId,'update':duzenleId},
    success: function(res){
      $('#Sayfalar').html(res);
    },
    error: function (jqXHR, status, errorThrown) {
      alert("Result: "+status+" Status: "+jqXHR.status);
    }
  });
  stackPush(menuId,sayfa,duzenleId);
}

function geriback(){
  var i1 = stackMenu[stackMenu.length-2];
  var i2 = stackSayfa[stackSayfa.length-2];
  var i3 = stackDuzenleId[stackDuzenleId.length-2];
  if(stackSayfa.length=="1" || stackSayfa.length=="0"){
    window.location.href = 'index.php';
  }else{
    stackMenu.pop();
    stackSayfa.pop();
    stackDuzenleId.pop();
    SayfaGetir(i1,i2,i3);
  }
}

function stackPush(menuStack,sayfaStack,duzenleIdStack){
  if(sayfaStack!=stackSayfa[stackSayfa.length-1]){
    stackMenu.push(menuStack);
    stackSayfa.push(sayfaStack);
    stackDuzenleId.push(duzenleIdStack);
  }
}

function SayfaYenile(){
  if (typeof _menuId === 'undefined') {
    return false;
  }
  $('#Sayfalar').html('<img src="Images/loading.gif" style="position:relative;left:50%;margin-top:10%;width:64px">');
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)  && document.getElementsByClassName('sidenav-overlay d-block').length==1) {
    $('#mobilmenu').click();//mobilde menu açık kalmasın diye
  }
  SayfaGetir(_menuId,_sayfa,_duzenleId);
}

function YeniEkle(yeniEkleMenuId,yeniEkleSayfa){
  SayfaGetir(yeniEkleMenuId,yeniEkleSayfa,"");
}
