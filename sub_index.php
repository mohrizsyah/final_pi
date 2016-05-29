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
	<?php
// LOGIC
    set_include_path(get_include_path() . PATH_SEPARATOR . './lib/easyrdf/lib');
    require_once "EasyRdf.php";
    require_once "html_tag_helpers.php";
    // Setup some additional prefixes for DBpedia
    EasyRdf_Namespace::set('owl', 'http://www.w3.org/2002/07/owl#');
    EasyRdf_Namespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
    EasyRdf_Namespace::set('gov', 'http://rizza.com/gov#');
    EasyRdf_Namespace::set('skos', 'https://www.w3.org/2009/08/skos-reference/skos.html#');
    $sparql = new EasyRdf_Sparql_Client('http://localhost:3030/final/query');
    $query2 = 'PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
prefix skos: <https://www.w3.org/2009/08/skos-reference/skos.html#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

prefix konsep:<http://rizza.com/konsep#> 
prefix dimensi: 		<http://rizza.com/dimensi#> 
prefix rizz: 			<http://rizza.com/datastatistik#> 
prefix vocab: 			<http://rizza.com/vocab> 
prefix kategori: 		<http://rizza.com/kategori#> 
prefix subkategori: 	<http://rizza.com/subkategori#> 
prefix gov: 			<http://rizza.com/gov#> 

select distinct ?label3 ?label2 ?jml where {
  ?label dimensi:subkategori ?halo .
  ?label dimensi:kategori ?kategori .
  ?kategori rdfs:label ?label3 .
  ?halo rdfs:label ?label2 .
  
  ?label rizz:jmlh_posting ?jml .
}

';
    $result2 = $sparql->query(
        $query2
    );
    $no = 1;


?>

<script type="text/javascript">
$(function () {
    var chart = new Highcharts.Chart({
        chart: {
            renderTo: "overall2",
            type: "column",
            borderWidth: 5,
            borderColor: '#e8eaeb',
            borderRadius: 0,
            backgroundColor: '#f7f7f7'
        },
        title: {
            style: {
                'fontSize': '1em'
            },
            useHTML: true,
            x: -27,
            y: 8,
            text: 'Jumlah Posting Instagram berdasarkan kategori dan subkategorinya'
        },
        series: [{name:'Total Posting',
            data: [<?php
        foreach ($result2 as $row) {
            echo str_replace(',','.',$row->jml).',';
        }
        ?>]
        }],
        xAxis: {
			            categories: [ <?php foreach ($result as $row) {
                                echo "{name:'".str_replace('Kategori','',$row->label3)."',categories:[";
								foreach ($result2 as $row2){
									$r1=$row->label3;
									$r2=$row2->label3;
									if((string)$r2==(string)$r1){
										echo "'".str_replace('Sub Kategori','',$row2->label2)."',";
									}
										
								}
								
								echo "]},";
                            }?>
							]
        }
    });
});
		</script>
	
  </head>
  <body>
  <script src="js/highcharts.js"></script>
	<script src="js/highcharts-3d.js"></script>
	<script src="js/modules/exporting.js"></script>
	<script src="js/grouped-categories.js"></script>
   <div class="container-fluid" style="margin-top:20px">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs">
				<li>
					<a>Klasifikasi Posting Instagram</a>
				</li>
				<li>
					<a href="index.php">Kategorikal</a>
				</li>
				<li  class="active">
					<a href="sub_index.php">Subkategorikal</a>
				</li>
				</li>
			</ul>
		</div>
	</div>
	</div>
	<div class="row" style="margin:20px 0px 0px 0px">
	</div>
	<div class="row">
			<div class="col-md-12">
				<div role="tabpanel" >		
					<div class="container" id="overall2">						
					</div>
			</div>
			<div class="col-md-10">
				<div role="tabpanel" class="tab-pane fade" id="total">		
					<div class="container" id="overall2">						
						</div>
						<br>
						<br>
						<br>
					</div>
					</div>
					<div class="container" >
						<div class="row" >
							<div class="col-md-10">
								<h3>Data Subkategorikal</h3>
								<h4>Berikut data mengenai Posting Instagram yang dilakukan oleh akun e-goverment di Jakarta berdasarkan </h4>
								<br></br>
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">
									<thead>
										<tr>
											<th>Kategori</th>
											<th>Sub-Kategori</th>
											<th>Jumlah Posting</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($result2 as $row) { 
										echo "<tr>";
											echo "<td>" . str_replace('http://rizza.com/','',$row->label3) . "</td>";
											echo "<td>" . str_replace('http://rizza.com/','',$row->label2) . "</td>";
											echo "<td>" . str_replace('http://rizza.com/','',$row->jml) . "</td>";
											echo "</tr>" ;
									}?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
		</div>
	</div>
  </body>
  
  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="api/js/jquery-1.11.3.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  
  
</html>