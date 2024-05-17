//dile Değerleri
var dil;
function getDil(key) {
    if (dil != null && dil != "null" && dil != "") {
        key = key
            .replace(/İ/g, "i")
            .replace(/ı/g, "i")
            .toLowerCase()
            .replace(/ç/g, "c")
            .replace(/ö/g, "o")
            .replace(/ğ/g, "g")
            .replace(/ü/g, "u")
            .replace(/ş/g, "s")
            .replace(/[^\w\s]/gi, "")
            .replace(/[^a-zA-Z0-9]/g, "-")
            .substring(0, 100);
        if (dil[key] != null && dil[key] != "" && typeof dil[key] !== "undefined") {
            return dil[key];
        } else {
            return key;
        }
    } else {
        return key;
    }
}
//!dile Değerleri

function git(link) {
    window.location = link;
}

function emailKontrol(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function tirnakSil(e) {
    e.value = e.value.replaceAll('"', "'");
}

function ButtonDisabled(submitButton) {
    setTimeout(function () {
        document.getElementById("submitButton").disabled = true;
    }, 50);
    setTimeout(function () {
        document.getElementById("submitButton").disabled = false;
    }, 5000);
}

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN((c = Math.abs(c))) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt((n = Math.abs(+n || 0).toFixed(c))) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return (
        s +
        (j ? i.substr(0, j) + t : "") +
        i.substr(j).replace(/(d{3})(?=d)/g, "$1" + t) +
        (c
            ? d +
            Math.abs(n - i)
                .toFixed(c)
                .slice(2)
            : "")
    );
};

//sayfadaki tüm  leri sıfırlar => resetRecaptchas()
function resetRecaptchas() {
    var recList = document.getElementsByClassName("g-recaptcha");
    for (var i = 0; i < recList.length; i++) {
        var widgetId;
        var onloadCallback = function () {
            widgetId = grecaptcha.render(recList[i], {
                sitekey: recList[i].getAttribute("data-sitekey"),
            });
        };
        grecaptcha.reset(i);
    }
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
            } else {
                buttonYazi = inputs[i].innerHTML;
                inputs[i].innerHTML = "<i class='fa fa-circle-o-notch fa-spin'></i>";
                inputs[i].disabled = true;
            }
        }
    }
}

function FavoriKayit(urunId,uyeId) {
    if (document.getElementById("sepetButton_" + urunId) != null) {
        document.getElementById("sepetButton_" + urunId).disabled = true;
    }
    var data = new FormData();
    data.append("urunId", urunId);
    data.append("uyeId", uyeId);
    $.ajax({
        type: "POST",
        url: "ajax/favoriKayit.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res == "1") {
                swal(getDil("Başarılı"), getDil("Ürün favori listesine eklendi."), "success", {
                    button: getDil("OK"),
                });
            }
            else if(res == "2"){
                swal(getDil("Başarılı"), getDil("Ürün favori listesinden çıkarıldı."), "success", {
                    button: getDil("OK"),
                });
            }
            else {
                alert(res);
            }
            if (document.getElementById("sepetButton_" + urunId) != null) {
                document.getElementById("sepetButton_" + urunId).disabled = false;
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetKayit(urunId, varyantId, adet) {
    if (document.getElementById("sepetButton_" + urunId) != null) {
        document.getElementById("sepetButton_" + urunId).disabled = true;
    }
    var data = new FormData();
    data.append("urunId", urunId);
    data.append("varyantId", varyantId);
    data.append("adet", adet);
    $.ajax({
        type: "POST",
        url: "ajax/sepetKayit.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == "success") {
                document.getElementById("sepet_adet").innerHTML = res.result.sepet_adet;
                swal(getDil("Başarılı"), getDil("Sepete eklendi."), "success", {
                    button: getDil("OK"),
                });
            }
            else if(res.status=="stockNotFound"){
                swal(getDil("Uyarı"), getDil("Stokta Olmayan Ürün."), "error", { button: 'OK' });
            } 
            else {
                alert(res);
            }
            if (document.getElementById("sepetButton_" + urunId) != null) {
                document.getElementById("sepetButton_" + urunId).disabled = false;
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetTekrarKayit(urunId, varyantId, adet) {
    if (document.getElementById("sepetButton_" + urunId) != null) {
        document.getElementById("sepetButton_" + urunId).disabled = true;
    }
    var data = new FormData();
    data.append("urunId", urunId);
    data.append("varyantId", varyantId);
    data.append("adet", adet);
    $.ajax({
        type: "POST",
        url: "ajax/sepetKayit.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == "success") {
                document.getElementById("sepet_adet").innerHTML = res.result.sepet_adet;
            }
            else if(res.status=="stockNotFound"){
                swal(getDil("Uyarı"), getDil("Stokta Olmayan Ürün."), "error", { button: 'OK' });
            } 
            else {
                alert(res);
            }
            if (document.getElementById("sepetButton_" + urunId) != null) {
                document.getElementById("sepetButton_" + urunId).disabled = false;
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetUrunAdet(sira,birimFiyat,paraIcon){
    var adet = document.getElementById("adet-" + sira).value;
    var data = new FormData();
    data.append("sira", sira);
    data.append("adet", adet);
    data.append("birimFiyat", birimFiyat);
    $.ajax({
        type: "POST",
        url: "ajax/sepetUrunAdet.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == "success") {
                document.getElementById("araToplam-" + sira).innerHTML = paraIcon + parseFloat(res.result.araToplam).formatMoney(2, ",", ".");
            } else {
                alert(res);
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetAdet(sira, birimFiyat, paraIcon,uyeIndirimOrani) {
    var adet = document.getElementById("adet-" + sira).value;
    var data = new FormData();
    data.append("sira", sira);
    data.append("adet", adet);
    data.append("uyeIndirimOrani", uyeIndirimOrani);
    $.ajax({
        type: "POST",
        url: "ajax/sepetAdet.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == "success") {
                SepetUrunAdet(sira,birimFiyat,paraIcon)
                document.getElementById("toplamTutar").innerHTML =paraIcon + parseFloat(res.result.toplamTutar).formatMoney(2, ",", ".");
                document.getElementById("araTutar").innerHTML =paraIcon + parseFloat(res.result.araTutar).formatMoney(2, ",", ".");
                document.getElementById("kdvTutar").innerHTML =paraIcon + parseFloat(res.result.kdvTutar).formatMoney(2, ",", ".");
                document.getElementById("siparisKargoUcreti").value = res.result.siparisKargoUcreti;
                window.location.reload();
            } else {
                alert(res);
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetList() {
    var data = new FormData();
    $.ajax({
        type: "POST",
        url: "ajax/sepetList.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            //console.log(res);
            if (res.status == "success") {
                if (document.getElementById("sepet_adet") != null) {
                    document.getElementById("sepet_adet").innerHTML = res.result.length;
                }
            } else {
                alert(res);
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

function SepetSil(sira, isCart) {
    var data = new FormData();
    data.append("sira", sira);
    $.ajax({
        type: "POST",
        url: "ajax/sepetSil.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            console.log(res);
            if (res.status == "success") {
                if (document.getElementById("sepet_adet") != null) {
                    document.getElementById("sepet_adet").innerHTML =
                        res.result.sepet_adet;
                }
                if (isCart == 1) {
                    window.location.href = window.location;
                }
                SepetList();
            } else {
                alert(res);
            }
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

$(document).ready(function () {
    SepetList();
});

function AdresModal(uyeAdresId, link) {
    var data = new FormData();
    data.append("uyeAdresId", uyeAdresId);
    data.append("link", link);
    $.ajax({
        type: "POST",
        url: "ajax/adresModal.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            $("#modalDiv").html(res);
            $("#fadeIn").modal("show");
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

$("#adresKayitForm").submit(function (e) {
    e.preventDefault(); //submit postu kesyoruz
    var data = new FormData(this);
    var formId = this.id;
    submitButKontrol(formId, 0);
    $.ajax({
        type: "POST",
        url: "ajax/adresKayit.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res == 1) {
                $("#fadeIn").modal("hide");
                var link = document.getElementById("link").value;
                if (link != "") {
                    setTimeout(function () {
                        window.location.href = link;
                    }, 700);
                }
                document.getElementById(formId).reset();
            } else {
                swal(getDil("Error!"), res, "warning", { button: getDil("OK") });
            }
            submitButKontrol(formId, 1);
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
});

function AdresBilgi(kaynak, hedef) {
    var e = document.getElementById(kaynak);
    var uyeAdresId = e.value;
    if (uyeAdresId == "") {
        document.getElementById(hedef).innerHTML = "";
        return false;
    }
    var data = new FormData();
    data.append("uyeAdresId", uyeAdresId);
    $.ajax({
        type: "POST",
        url: "ajax/adresBilgi.php",
        data: data,
        contentType: false,
        processData: false,
        success: function (res) {
            document.getElementById(hedef).innerHTML = res;
        },
        error: function (jqXHR, status, errorThrown) {
            // alert("Result: " + status + " Status: " + jqXHR.status);
        },
    });
}

////ulke İl fonksiyonu
function ulkeIl(ulke, il) {
    var ulke = $("#" + ulke).val();
    if (ulke == "") {
        return false;
    }
    $.ajax({
        type: "POST",
        url: "ajax/ulkeil.php",
        data: {
            ulke: ulke,
        },
        success: function (data) {
            $("#" + il).empty();
            $("#" + il).append(data);
        },
    });
}
////!ulke İl fonksiyonu

////il illçe fonksiyonu
function ilIlce(il, ilce) {
    var il = $("#" + il).val();
    if (il == "") {
        return false;
    }
    $.ajax({
        type: "POST",
        url: "ajax/ililce.php",
        data: {
            il: il,
        },
        success: function (data) {
            $("#" + ilce).empty();
            $("#" + ilce).append(data);
        },
    });
}
////!il illçe fonksiyonu

function call(id) {
    const nodeList = document.getElementsByName('show-' + id); 
    const elementArray = Array.from(nodeList);

    // Iterate over the array using forEach
    elementArray.forEach(element => {
        const id = element.id;
        if (document.getElementById(id).style.display === "block") {
            document.getElementById(id).style.display = "none";
        } 
        else {
            document.getElementById(id).style.display = "block";
        }
    });
}