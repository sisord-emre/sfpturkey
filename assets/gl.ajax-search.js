var AT_Search = {
 ajaxProductItems : function(){
  var result = new Array();
  //var searchURL = 'ajax/search.php?view=ajax';
  var searchURL = 'https://sfpturkey.com.tr/ajax/search.php?view=ajax';

  $.ajax({
    type: 'GET',
    url: searchURL,
    success: function (data) {

      data = '<div>' + data + '</div>';
      data = data.trim();

      var elements = $(data).find('.s-ajax');

      if( 0 < elements.length ){
        elements.each(function() {

            var title = $.trim(this.getAttribute('data-t'));
            var productcode = $.trim(this.getAttribute('data-pcode'));
            var price = $.trim(this.getAttribute('data-p'));
            var handle = $.trim(this.getAttribute('data-h'));
            var image = $.trim(this.getAttribute('data-src'));
            var sku = $.trim(this.getAttribute('data-sku'));

            var item = new Object();
            item.title = title;
            item.productcode = productcode;
            item.price = price;
            item.handle = handle;
            item.featured_image = image;
            item.sku = sku;

            result.push(item);
        });
      }else{
          //todo : return not found here
        }

      },
      dataType: 'html'
    });

  return result;
}

,ajaxSearch : function(){
  var products = AT_Search.ajaxProductItems();

  $( "#input-ajax-search" ).keyup(function() {
    var $this = $(this)
    ,keyword = $this.val().toLowerCase();

    $('#result-ajax-search').hide();

      //console.log(keyword);

      if(keyword.length >= 2){

        jQuery(this).removeClass('error warning valid').addClass('valid');

        var result = $('#result-ajax-search .search-results').empty();

        var j = 0;

        for (var i = 0; i < products.length; i++) {

          var item = products[i];

          var title = item.title;
          var productcode = item.productcode;
          var price = item.price;
          var handle = item.handle;
          var image = item.featured_image;
          var sku = item.sku;

          if(title.toLowerCase().indexOf(keyword) > -1 || productcode.toLowerCase().indexOf(keyword) > -1){

            var j = j + 1;

            var markedString = title.replace(new RegExp('(' + keyword + ')', 'gi'), '<span class="marked">$1</span>');

            var template = '<li class="search-item-wrapper"><div class="row"><div class="col-md-2"><a class="search-item-img" href="https://sfpturkey.com.tr/product/'+ handle +'">'+ '<img style="max-width: 100px; float: left; padding-right:10px;" src="' + image + '" />' +'</a></div><div class="col-md-10"><a class="search-item-title" href="https://sfpturkey.com.tr/product/'+ handle +'">'+ productcode +' <br>'+markedString +'<br>'+ price + '</a></div></div></li>';

            // if(j <= 10 ){
            //   result.append(template);
            // }
            result.append(template);

          }
        }

        if($('#result-ajax-search .search-results li').length < 1){
          result.append('<li><p>No result found for your search.</p></li>')
        }

        if($('#result-ajax-search .search-results li').length){
          $('#result-ajax-search').show();

        }

      }else{

        if(keyword.length == 1){
          jQuery(this).removeClass('error warning valid').addClass('error');
          //todo : change the place holder to notice customer

          var t = '<li><p style="color: black;"><b>En az 2 karakter girmelisiniz</b></p></li>';
          var result = $('#result-ajax-search .search-results').empty();
          result.append(t);
          $('#result-ajax-search').show();
        }
        else{
          $('#result-ajax-search').hide();
        }
      }
    });

  jQuery(document).on('click','#page-body',function(e){
    $('#result-ajax-search').hide();
  });

}

,init : function(){
  this.ajaxSearch();
}
}

jQuery(document).ready(function($) {

  AT_Search.init();

})
