<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript" src="js/jquery.min.js"></script>

    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Klasifikasi</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<?php
// LOGIC
    set_include_path(get_include_path() . PATH_SEPARATOR . './lib/easyrdf/lib');
    require_once "EasyRdf.php";
    require_once "html_tag_helpers.php";
    // Setup some additional prefixes for DBpedia
	EasyRdf_Namespace::set('konsep', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    EasyRdf_Namespace::set('gov', 'http://rizza.com/gov#');
    EasyRdf_Namespace::set('skos', 'https://www.w3.org/2009/08/skos-reference/skos.html#');
    $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/final/query');
    $query = ' PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix skos: <https://www.w3.org/2009/08/skos-reference/skos.html#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix konsep:<http://rizza.com/konsep#> 
prefix dimensi: 		<http://rizza.com/dimensi#> 
prefix rizz: 			<http://rizza.com/datastatistik#> 
prefix vocab: 			<http://rizza.com/vocab> 
prefix kategori: 		<http://rizza.com/kategori#> 
prefix subkategori: 	<http://rizza.com/subkategori#> 
prefix gov: 			<http://rizza.com/gov#> 

select distinct ?label3 (SUM(?jml) as ?jml2) where {
  ?label dimensi:subkategori ?halo .
  ?label dimensi:kategori ?kategori .
  ?kategori rdfs:label ?label3 .
  ?halo rdfs:label ?label2 .
  
  ?label rizz:jmlh_posting ?jml .
}
GROUP BY ?label3

';
    $result = $sparql->query(
        $query
    );
    $no = 1;


?>
		<script type="text/javascript">
$(function () {
    // Set up the chart
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: 'overall',
            type: 'column',
            options3d: {
                enabled: true,
                alpha: 15,
                beta: 15,
                depth: 50,
                viewDistance: 25
            }
        }, plotOptions: {
            xAxis: {
                colorByPoint: true
            }
        },
        title: {
            text: 'Jumlah Posting Instagram Berdasarkan Kategori'
        },
        subtitle: {
            text: 'Grafik ini menjelaskan Jumlah Posting Instagram berdasarkan kategorinya'
        },
		xAxis: {
                        categories: [
						<?php
        foreach ($result as $row) {
                                echo "'".str_replace('http://localhost/vayvis/ns/gov/','',$row->label3)."',";
                            }
        ?>
						
						]
                    },
        plotOptions: {
            column: {
                depth: 25
            }
        },
        series: [{name:'Total Posting',
            data: [<?php
        foreach ($result as $row) {
            echo str_replace(',','.',$row->jml2).',';
        }
        ?>]
        }]
    });

    function showValues() {
        $('#alpha-value').html(chart.options.chart.options3d.alpha);
        $('#beta-value').html(chart.options.chart.options3d.beta);
        $('#depth-value').html(chart.options.chart.options3d.depth);
    }

    // Activate the sliders
    $('#sliders input').on('input change', function () {
        chart.options.chart.options3d[this.id] = this.value;
        showValues();
        chart.redraw(false);
    });

    showValues();
});
		</script>
  </head>
  <body>
 <script src="js/highcharts.js"></script>
	<script src="js/highcharts-3d.js"></script>
	<script src="js/modules/exporting.js"></script>
	<script src="js/grouped-categories.js"></script>
	<script type="text/javascript"> document.getElementById("dash").setAttribute("class","active");</script> 
   <div class="container-fluid" style="margin-top:20px">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs">
				<li>
					<a>Klasifikasi Posting Instagram</a>
				</li>
				<li class="active">
					<a href="index.php">Kategorikal</a>
				</li>
				<li>
					<a href="sub_index.php">Subkategorikal</a>
				</li>
				</li>
			</ul>
		</div>
	</div>
	</div>
		<div class="row" style="margin:20px 0px 0px 0px">
			<div class="col-md-3">
				<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						Legend
					</h3>
				</div>
				<div class="panel-body">
					 <ul class="nav nav-pills nav-stacked">
                        <div id="sliders">
							<table>
							<tr>
								<td>Alpha Angle  </td>
								<td><input id="alpha" type="range" min="0" max="45" value="15"/> <span id="alpha-value" class="value"></span></td>
							</tr>
							<tr>
								<td>Beta Angle  </td>
								<td><input id="beta" type="range" min="-45" max="45" value="15"/> <span id="beta-value" class="value"></span></td>
							</tr>
							<tr>
								<td>Depth  </td>
								<td><input id="depth" type="range" min="20" max="100" value="50"/> <span id="depth-value" class="value"></span></td>
							</tr>
						</table>
						</div>
					</ul>
				</div>
			</div>
			<div class="col-md-1">
				<div class="tab-content">
				</div>
			</div>
			<div class="col-md-8">
				<div class="tab-content">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8">
						<br></br>
							<div id="container"></div>
								<div class="col-md-10">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="cost">
						<div class="jumbotron" id="overall">
						</div>
						<br></br>
						<h3>Data Kategorikal</h3>
						<h4>Berikut merupakan laporan Posting Instagram dari akun e-goverment Jakarta berdasarkan kategori</h4>
						<br></br>
						<table class="table table-striped table-bordered table-hover" id="dataTables-example">
							<thead>
								<tr>
									<th>Kategori</th>
									<th>Jumlah</th>
								   
								</tr>
							</thead>
							<tbody>
								  <?php foreach ($result as $row) { 
									echo "<tr>";
										echo "<td>" . str_replace('http://rizza.com/','',$row->label3) . "</td>";
										echo "<td>" . str_replace('http://rizza.com/','',$row->jml2) . "</td>";
									echo "</tr>" ;
								  }?>
							</tbody>
						</table>
						<br></br>
					</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
		<div class="panel panel-default">	
		<div class="panel-heading">
					<h3 class="panel-title">
						Total klasifikasi
					</h3>
				</div>
				<div class="panel-body">
					 <ul class="nav nav-pills nav-stacked">
					 <li>jumlah yang terklasifikasi : 1136 </li>
					 <li> jumlah yang tidak terklasifikasi : 1566 </li>
					</ul>
				</div>
		</div>
		</div>
		<div class="col-md-4">
		</div>
		</div>
  </body>
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="api/js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  
  
</html>