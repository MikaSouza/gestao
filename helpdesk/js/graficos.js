var chartPie = function( params ) {
	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(function() {
		$(function() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Topping');
			data.addColumn('number', 'Slices');
            data.addRows(params.data);

			var options = {
				'title': params.title,
				'width': params.width,
				'height': params.height,
                'pieHole': params.pieHole
			};

			var chart = new google.visualization.PieChart(document.getElementById(params.div_retorno));
			chart.draw(data, options);
		});
	});
}

var chartColumn = function( params ) {
	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(function() {
		$(function() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Ano');
            data.addColumn('number', 'Receitas');
            data.addColumn('number', 'Despesas');
            data.addRows(params.data);

            var  options = {
				'title': params.title,
				'width': params.width,
				'height': params.height
			};
            var chart = new google.visualization.ColumnChart(document.getElementById(params.div_retorno));
            chart.draw(data, options);
        });
    });
}

var chartColumn2 = function( params ) {
	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(function() {
		$(function() {
			var data = new google.visualization.DataTable();
			data.addColumn('number', 'Idade');
            data.addColumn('number', 'Masculino');
            data.addColumn('number', 'Feminino');
            data.addRows(params.data);

            var  options = {
				'title': params.title,
				'width': params.width,
				'height': params.height
			};
            var chart = new google.visualization.ColumnChart(document.getElementById(params.div_retorno));
            chart.draw(data, options);
        });
    });
}

var chartTimeline = function( params ) {

	/*google.charts.load('current', {
		'packages':[
			'timeline'
		]
	});*/

	google.load('visualization', '1.0', {'packages':['timeline'],'language': 'pt'});

	google.setOnLoadCallback(function() {
		$(function() {
			var container = document.getElementById(params.div_retorno);
			var chart = new google.visualization.Timeline(container);
			var dataTable = new google.visualization.DataTable();

			dataTable.addColumn({ type: 'string', id: 'Local' });
			dataTable.addColumn({ type: 'date', id: 'Ingresso' });
			dataTable.addColumn({ type: 'date', id: 'Saída' });

			//params.data = [params.data];

			console.log("banco:");
			console.log(params.data);


			var dados = params.data.map(function(d){
				d[1] = new Date(d[1]);
				d[2] = new Date(d[2]);
				// if (d[1].getTime() > d[2].getTime()) {
				// 	d[2] = d[1];
				// }
				return d;
			});

			console.log("gerado:");
			console.log(dados);


			//dataTable.addRows(params.data);
			dataTable.addRows(dados);

			var options = {
			  'title' : params.title,
			  'width': params.width,
			  'height': params.height
			};

			chart.draw(dataTable, options);
        });
    });
	/*
	google.charts.setOnLoadCallback(drawChart);
	function drawChart() {
		var container = document.getElementById('div_timeline');
		var chart = new google.visualization.Timeline(container);
		var dataTable = new google.visualization.DataTable();

		dataTable.addColumn({ type: 'string', id: 'President' });
		dataTable.addColumn({ type: 'date', id: 'Start' });
		dataTable.addColumn({ type: 'date', id: 'End' });
		dataTable.addRows([
		  [ 'EXÉRCITO NACIONAL BRASILEIRO', new Date(1976, 1, 15), new Date(1976, 11, 16) ],
		  [ 'BRIGADA MILITAR DO ESTADO DO RS', new Date(1977, 3, 7),  new Date(1980, 1, 4) ],
		  [ 'CHURRASCARIA DONIRELLA LTDA.', new Date(1980, 1, 6),  new Date(1980, 8, 15) ],
		  [ 'RESTAURANTE E LANCHERIA ANGOLA LTDA.', new Date(1980, 8, 9),  new Date(1980, 9, 25) ],
		  [ 'CONSTRUTORA BRASILIA GUAIBA LTDA.', new Date(1980, 2, 4),  new Date(1980, 3, 18) ]
		  ]);

		chart.draw(dataTable);
	}*/
}

var chartComboChart = function( params ) {
	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(function() {
		$(function() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Mês');
            data.addColumn('number', 'Receitas Previstas');
			data.addColumn('number', 'Receitas Realizadas');
            data.addColumn('number', 'Despesas Previstas');
			data.addColumn('number', 'Despesas Realizadas');
            data.addRows(params.data);

			var options = {
			  'title' : params.title,
			  'width': params.width,
			  'height': params.height,
			  seriesType: "bars",
			  series: {5: {type: "line"}}
			};

			var chart = new google.visualization.ComboChart(document.getElementById(params.div_retorno));
			chart.draw(data, options);
        });
    });
}

/*
var chartBar = function( params ) {
	google.load('visualization', '1.0', {'packages':['corechart']});

	google.setOnLoadCallback(function() {
		$(function() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', params.column_comparar);
            data.addColumn('number', params.column_titulo1);
            data.addColumn('number', params.column_titulo2);
            data.addRows(params.data);

			var options = {
				'title': params.title,
				'width': params.width,
				'height': params.height
			};

			var chart = new google.visualization.BarChart(document.getElementById(params.div_retorno));
			chart.draw(data, options);
		});
	});
}

*/

var chartGeo = function( params ) {
	google.load('visualization', '1', {'packages': ['geomap']});

	google.setOnLoadCallback(function() {
		$(function() {

			var data = google.visualization.arrayToDataTable([
				['Cidade', 'Quantidade de Atendimentos'],
				['Porto Alegre', 200],
				['Canoas', 100],
				['São Leopoldo', 600],
				['Manaus', 800],
				['Alagoas', 300],
				['Rwanda', 500],
				['Saudi Arabia', 300],
				['Monaco', 300],
				['Libya', 300],
				['Kuwait', 300],
				['Lebanon', 300]
			]);

			var options = {
				region: 'BR',
				colors: [0xFF8747, 0xFFB581, 0xc06000],
				dataMode: 'markers',
				showZoomOut: true
			};

			var geomap = new google.visualization.GeoMap(document.getElementById(params.div_retorno));
			geomap.draw(data, options);


			/*
			var data = google.visualization.arrayToDataTable([
				['Cidade', 'Quantidade de Atendimentos'],
				['Porto Alegre', 200],
				['Canoas', 100],
				['São Leopoldo', 600],
				['Manaus', 800],
				['Alagoas', 300],

				['Rwanda', 500],
				['Saudi Arabia', 300],
				['Monaco', 300],
				['Libya', 300],
				['Kuwait', 300],
				['Lebanon', 300],

			]);

			var options = {
				region: 'BR',
				displayMode: 'markers',
				enableRegionInteractivity: true,
				magnifyingGlass: {
					enable: true,
					zoomFactor: 50.0
				},
				resolution: 'provinces'
			};

			var chart = new google.visualization.GeoChart(document.getElementById(params.div_retorno));
			chart.draw(data, options);
			*/
        });
    });
}