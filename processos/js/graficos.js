var chartPie = function( params ) {

	var options = {
	  series: params.series,
	  chart: {
		  width: params.width,
		  height: params.height,
		  type: 'pie',
		},
		labels: params.labels,
		legend: {
		  show: true,
		  position: 'bottom',
		  horizontalAlign: 'center',
		  verticalAlign: 'middle',
		  floating: false,
		  fontSize: '14px',
		  offsetX: 0,
		  offsetY: -10
		},
		responsive: [{
		  breakpoint: 600,
		  options: {
			  chart: {
				  height: 240
			  },
			  legend: {
				  show: false
			  },
		  }
		}]
	}
	var chart = new ApexCharts(document.querySelector(params.div_retorno), options);
	chart.render();
	
}	