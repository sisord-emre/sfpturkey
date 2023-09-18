<!-- c3 line charts section start -->

<!-- Pie Chart -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title"><?=$fonk->getPDil("baslik")?></h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
        <div class="heading-elements">
        </div>
      </div>
      <div class="card-content collapse show">
        <div class="card-body">
          <div id="pie-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- charts section end -->

<!-- BEGIN: Page Vendor JS-->
<script src="Assets/app-assets/vendors/js/charts/d3.min.js"></script>
<script src="Assets/app-assets/vendors/js/charts/c3.min.js"></script>
<!-- END: Page Vendor JS-->

<script type="text/javascript">
// Callback that creates and populates a data table, instantiates the pie chart, passes in the data and draws it.
var pieChart = c3.generate({
  bindto: '#pie-chart',
  color: {
    pattern: ['#99B898','#FECEA8', '#FF847C']
  },

  // Create the data table.
  data: {
    // iris data from R
    columns: [
      ['data1', 530],
      ['data2', 120],
    ],
    type : 'pie',
    onclick: function (d, i) { console.log("onclick", d, i); },
    onmouseover: function (d, i) { console.log("onmouseover", d, i); },
    onmouseout: function (d, i) { console.log("onmouseout", d, i); }
  }
});

// Instantiate and draw our chart, passing in some options.
setTimeout(function () {
  pieChart.load({
    columns: [
      ["setosa", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
      ["versicolor", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
      ["virginica", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8],
    ]
  });
}, 1500);

setTimeout(function () {
  pieChart.unload({
    ids: 'data1'
  });
  pieChart.unload({
    ids: 'data2'
  });
}, 2500);

// Resize chart on sidebar width change
$(".menu-toggle").on('click', function() {
  pieChart.resize();
});
</script>
