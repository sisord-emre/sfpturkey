<!-- c3 line charts section start -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title"><?=$fonk->getPDil("baslik")?></h4>
        <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
        <div class="heading-elements">
          <ul class="list-inline mb-0">
          </ul>
        </div>
      </div>
      <div class="card-content collapse show">
        <div class="card-body">
          <div id="line-chart"></div>
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
// Callback that creates and populates a data table, instantiates the line chart, passes in the data and draws it.
var lineChart = c3.generate({
  bindto: '#line-chart',
  size: { height: 400 },
  point: {
    r: 4
  },
  color: {
    pattern: ['#673AB7', '#E91E63']
  },
  data: {
    columns: [
      ['data1', 30, 200, 100, 400, 150, 250],
      ['data2', 50, 20, 10, 40, 15, 25]
    ]
  },
  grid: {
    y: {
      show: true,
      stroke: '#ff0'
    }
  }
});

// Instantiate and draw our chart, passing in some options.
setTimeout(function () {
  lineChart.load({
    columns: [
      ['data1', 230, 190, 300, 500, 300, 400]
    ]
  });
}, 1000);

setTimeout(function () {
  lineChart.load({
    columns: [
      ['data3', 130, 150, 200, 300, 200, 100]
    ]
  });
}, 1500);

setTimeout(function () {
  lineChart.unload({
    ids: 'data1'
  });
}, 2000);

// Resize chart on sidebar width change
$(".menu-toggle").on('click', function() {
  lineChart.resize();
});
</script>
