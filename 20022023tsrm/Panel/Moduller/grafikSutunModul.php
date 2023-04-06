<!-- c3 line charts section start -->
<!-- Data Color Chart -->
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
					<div id="data-color"></div>
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
// Callback that creates and populates a data table, instantiates the data color chart, passes in the data and draws it.
var dataColor = c3.generate({
	bindto: '#data-color',
	size: {height:400},
	// Create the data table.
	data: {
		columns: [
			['data1', 30, 20, 50, 40, 60, 50],
			['data2', 200, 130, 90, 240, 130, 220],
			['data3', 300, 200, 160, 400, 250, 250]
		],
		type: 'bar',
		colors: {
			data1: '#673AB7',
			data2: '#E91E63',
		},
		color: function (color, d) {
			// d will be 'id' when called for legends
			return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
		}
	},
	grid: {
		y: {
			show: true
		}
	},
});

// Resize chart on sidebar width change
$(".menu-toggle").on('click', function() {
	dataColor.resize();
});
</script>
