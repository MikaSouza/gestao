$(function(){

	grafico1();
	grafico2();
	grafico3();
});

function grafico1()
{

	var options = {
	  chart: {
		  height: 320,
		  type: 'pie',
	  }, 
	  series: [44, 55, 41, 17, 15],
	  labels: ["Comercial", "Juridico", "Series 3", "Series 4", "Series 5"],
	  colors: ["#a3cae0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
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

	var chart = new ApexCharts(
	  document.querySelector("#apex_pie1"),
	  options
	);

	chart.render();
}	

function grafico2()
{

	var options = {
	  chart: {
		  height: 320,
		  type: 'pie',
	  }, 
	  series: [44, 55, 41, 17, 15],
	  labels: ["Comercial", "Juridico", "Series 3", "Series 4", "Series 5"],
	  colors: ["#a3cae0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
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

	var chart = new ApexCharts(
	  document.querySelector("#apex_pie2"),
	  options
	);

	chart.render();
}	

function grafico3()
{

	var options = {
	  chart: {
		  height: 320,
		  type: 'pie',
	  }, 
	  series: [44, 55, 41, 17, 15],
	  labels: ["Comercial", "Juridico", "Series 3", "Series 4", "Series 5"],
	  colors: ["#a3cae0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
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

	var chart = new ApexCharts(
	  document.querySelector("#apex_pie3"),
	  options
	);

	chart.render();
}	
