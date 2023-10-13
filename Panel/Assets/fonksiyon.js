if (!(typeof CKEDITOR === 'undefined' || CKEDITOR === null)) {
	CKEDITOR.config.allowedContent = true;
	CKEDITOR.config.autoParagraph = false;
	CKEDITOR.dtd.$removeEmpty['i'] = false;
}
//hangi inputa yazılacak ise o input id parametre gönserilir ör:onkeyup="toSeo('kategorilerAdi','kategorilerSeo')"
function toSeo(metininput, seoinput) {
	document.getElementById(metininput).value = document.getElementById(metininput).value.replace(/^\s+/, "");
	yazi = document.getElementById(metininput).value.replace(/İ/g, 'i').replace(/ı/g, 'i').toLowerCase().replace(/ç/g, 'c').replace(/ö/g, 'o').replace(/ğ/g, 'g').replace(/ü/g, 'u').replace(/ş/g, 's').replace(/[^\w\s]/gi, '');
	yazi = boslukAl(yazi);
	yazi = yazi.replace(/[^a-zA-Z0-9]/g, '-')
	yazi = cokluTireAl(yazi);
	document.getElementById(seoinput).value = yazi;
}

function boslukAl(yazi) {
	for (var i = 0; i < yazi.length; i++) {
		if (yazi.charAt(i) == " " && yazi.charAt(i + 1) == " ") {
			yazi = yazi.substr(0, i) + "" + yazi.substr(i + 1);
			yazi = boslukAl(yazi);
		}
	}
	if (yazi.charAt(yazi.length - 1) == " ") {
		yazi = yazi.substr(0, yazi.length - 1)
	}
	return yazi;
}

function cokluTireAl(yazi) {
	for (var i = 0; i < yazi.length; i++) {
		if (yazi.charAt(i) == "-" && yazi.charAt(i + 1) == "-" && yazi.charAt(i + 2) == "-") {
			yazi = yazi.substr(0, i) + "" + yazi.substr(i + 2);
			yazi = boslukAl(yazi);
		}
		if (yazi.charAt(i) == "-" && yazi.charAt(i + 1) == "-") {
			yazi = yazi.substr(0, i) + "" + yazi.substr(i + 1);
			yazi = boslukAl(yazi);
		}
	}
	if (yazi.charAt(yazi.length - 1) == "-") {
		yazi = yazi.substr(0, yazi.length - 1)
	}
	return yazi;
}
//////!toSeo

function TirnakSil(e) {
	e.value = e.value.replaceAll('\"', '\'');
}

////parent child category fonksiyonu
function parentChildCategory(urunId, parent, kategoriIdList) {
	var urunId = $('#' + urunId).val();
	var parent = $('#' + parent).val();
	if (parent == "") {
		return false;
	}
	
	$.ajax({
		type: "POST",
		url: "Scripts/parentChildCategory.php",
		data: {
			'parent': parent,
			'urunId': urunId
		},
		success: function (data) {
			$('#' + kategoriIdList).empty();
			$('#' + kategoriIdList).append(data);
		}
	});
}
////!parent child category fonksiyonu

////ulke İl fonksiyonu
function ulkeIl(ulke, il) {
	var ulke = $('#' + ulke).val();
	if (ulke == "") {
		return false;
	}
	$.ajax({
		type: "POST",
		url: "Scripts/ulkeil.php",
		data: {
			'ulke': ulke
		},
		success: function (data) {
			$('#' + il).empty();
			$('#' + il).append(data);
		}
	});
}
////!ulke İl fonksiyonu

////il illçe fonksiyonu
function ilIlce(il, ilce) {
	var il = $('#' + il).val();
	if (il == "") {
		return false;
	}
	$.ajax({
		type: "POST",
		url: "Scripts/ililce.php",
		data: {
			'il': il
		},
		success: function (data) {
			$('#' + ilce).empty();
			$('#' + ilce).append(data);
		}
	});
}
////!il illçe fonksiyonu

////il illçe fonksiyonu standart olan
$('#il').change(function () {
	var il = $('#il').val();
	if (il == "") {
		return false;
	}
	$('#ilce').empty();
	$.ajax({
		type: "POST",
		url: "Scripts/ililce.php",
		data: {
			'il': il
		},
		success: function (data) {
			$('#ilce').append(data);
		}
	});
});
////il illçe fonksiyonu  standart olan


//ilk harfi büyük yapma
function toCapitalize(Id) {
	var input = document.getElementById(Id);
	var string = input.value;
	input.value = string[0].toUpperCase() + string.slice(1);
}

//file inputlarını temizleme
function fileInputTemizle(Id) {
	document.getElementById(Id).value = "";
	document.getElementsByName(Id)[1].innerText = getDil("Dosya Seçiniz");
}

//file inputlarını temizleme
function fileInputTemizleMulti(Id) {
	document.getElementById(Id).value = "";
	document.getElementsByName(Id)[0].innerText = getDil("Dosya Seçiniz");
}

//onchange="youtubeConvert('etkinlikAlbumVideo','540','350');"
function youtubeLinkToEmbed(Id, width, height) {
	url = document.getElementById(Id).value;
	if (url == "") {
		return false;
	}
	var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
	var match = url.match(regExp);
	if (match && match[2].length == 11) {
		document.getElementById(Id).value = '<iframe width="' + width + '" height="' + height + '" src="//www.youtube.com/embed/' + match[2] + '" frameborder="0" allowfullscreen></iframe>';
	} else {
		document.getElementById(Id).value = 'error';
	}
}

//onkeyup="IframeToLink(id);"
function IframeToLink(id) {
	var text = document.getElementById(id).value;
	if (text.indexOf("iframe") != -1) {
		var src = text.split('src=')[1].split(/[ >]/)[0];
		document.getElementById(id).value = src.replace(/\"/g, "");;
	}
}

//tipi 1 ise input,2 ise html
function kopyala(kopyaId, tipi) {
	if (tipi == 1) {
		var text = $('#' + kopyaId).val();
	} else if (tipi == 2) {
		var text = $('#' + kopyaId).text();
	}
	navigator.clipboard.writeText(text);
	document.getElementById(kopyaId).setAttribute('style', 'border: 3px solid #1ce589!important;');
}

//formId görnderilir ve durumda ise 1,0 gönderilir. 0 disabled,1 enabled ( submitButKontrol(this.id,0); ),( submitButKontrol("prosesPost",1) );
var buttonYazi = "";
function submitButKontrol(formId, durum) {
	var inputs = document.getElementById(formId).elements;
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].type.toLowerCase() == "submit") {
			if (durum == 1) {
				inputs[i].disabled = false;
				inputs[i].innerHTML = buttonYazi;
			}
			else {
				buttonYazi = inputs[i].innerHTML;
				inputs[i].innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";
				inputs[i].disabled = true;
			}
		}
	}
}

//date-time inputunu istenen miktarda saat ekleyip hedef inputa yazdırır
function SaatEkle(kaynak, hedef, saat) {
	var basTarih = document.getElementById(kaynak).value;
	var d = new Date(basTarih);
	d.setHours(d.getHours() + saat);
	d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
	document.getElementById(hedef).value = d.toISOString().slice(0, 16);
}

//sayfadaki tüm  leri sıfırlar => resetRecaptchas()
function resetRecaptchas() {
	var recList = document.getElementsByClassName("g-recaptcha");
	for (var i = 0; i < recList.length; i++) {
		var widgetId;
		var onloadCallback = function () {
			widgetId = grecaptcha.render(recList[i], {
				'sitekey': recList[i].getAttribute("data-sitekey")
			});
		};
		grecaptcha.reset(i);
	}
}

//datatable export loglama
$(document).ready(function () {
	$('.buttons-copy').click(function () {
		dataTableExport("Kopyala");
	});
});
$(document).ready(function () {
	$('.buttons-excel').click(function () {
		dataTableExport("Excel");
	});
});
$(document).ready(function () {
	$('.buttons-pdf').click(function () {
		dataTableExport("Pdf");
	});
});
$(document).ready(function () {
	$('.buttons-print').click(function () {
		dataTableExport("Print");
	});
});
function dataTableExport(tipi) {
	var exportBasliklar = [];
	var basliklar = document.querySelector("#listTable > thead > tr").cells;
	for (var i = 0; i < basliklar.length; i++) {
		exportBasliklar.push(basliklar[i].innerHTML);
	}
	$.ajax({
		type: "POST",
		url: "Pages/dataTableExport.php",
		data: { 'tipi': tipi, 'exportBasliklar': exportBasliklar },
		success: function (res) {

		},
		error: function (jqXHR, status, errorThrown) {
			alert("Result: " + status + " Status: " + jqXHR.status);
		}
	});
}

//görsel crop işlemleri başlangıç
//ImageCrop('img-crop',"img-preview",150,50);
//CropImageInfo();
var $imageCrop = null;
function ImageCrop(className, cropClassName, cropWidth, cropHeight) {
	$imageCrop = $('.' + className);
	var $dataX = $('.main-demo-dataX');
	var $dataY = $('.main-demo-dataY');
	var $dataHeight = $('.main-demo-dataHeight');
	var $dataWidth = $('.main-demo-dataWidth');
	var $dataRotate = $('.main-demo-dataRotate');
	var $dataScaleX = $('.main-demo-dataScaleX');
	var $dataScaleY = $('.main-demo-dataScaleY');
	var options = {
		viewMode: 1,
		dragMode: 'move',
		autoCropArea: 0.65,
		restore: false,
		guides: false,
		center: false,
		highlight: false,
		cropBoxMovable: true,
		cropBoxResizable: false,
		zoomOnWheel: false,
		toggleDragModeOnDblclick: false,
		data: { //define cropbox size
			width: cropWidth,
			height: cropHeight,
		},
		preview: '.' + cropClassName,
		crop: function (e) {
			$dataX.val(Math.round(e.detail.x));
			$dataY.val(Math.round(e.detail.y));
			$dataHeight.val(Math.round(e.detail.height));
			$dataWidth.val(Math.round(e.detail.width));
			$dataRotate.val(e.detail.rotate);
			$dataScaleX.val(e.detail.scaleX);
			$dataScaleY.val(e.detail.scaleY);
		}
	};
	// Cropper
	$imageCrop.cropper(options);
}

function CropImageInfo() {
	result = $imageCrop.cropper("getData");
	//console.log(result);
	return JSON.stringify(result);
}
//görsel crop işlemleri bitiş
/*Emre ARIĞ*/


////akinSoftStokDusurme fonksiyonu
function akinSoftStokDusurme(siparisid) {
	if (siparisid == "") { return false; }
	$.ajax({
		type: "POST",
		url: "../api/urunListBotPost.php?ApiKey=8bYuhtCv5997aGgCxzsLpXgJuCRMFqEp",
		data: { 'siparisid': siparisid },
		success: function (data) {
			if (data == 1) {
				alert("Stok güncelleme işlemi başarılı.");
			}
			else {
				alert("Stok güncelleme işleminde hata oluştu.");
			}
		}
	});
}
////!akinSoftStokDusurme fonksiyonu