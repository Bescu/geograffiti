<?php
	// Connexion, sélection de la base de données
	$dbconn = pg_connect("host=localhost port=5434 dbname=test user=pgis password=pgis")
		or die('Connexion impossible : ' . pg_last_error());
	
	// Exécution de la requête SQL
	$query = "SELECT row_to_json(fc) FROM (SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features FROM (SELECT 'Feature' As type, ST_AsGeoJSON(lg.geom)::json As geometry,	row_to_json(lp) As properties FROM points As lg INNER JOIN (SELECT id, comment FROM points) As lp ON lg.id = lp.id  ) As f )  As fc;"; // requête inspirée de ce site : http://www.postgresonline.com/journal/archives/267-Creating-GeoJSON-Feature-Collections-with-JSON-and-PostGIS-functions.html
	$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());
	
	// Affichage des résultats en HTML
	
	$arr = pg_fetch_all($result);
	echo $arr[0]['row_to_json'];
	
	// Libère le résultat
	pg_free_result($result);

	// Ferme la connexion
	pg_close($dbconn);
?>