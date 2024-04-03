<?php

	// Propiedades de la conexión con la API de "TMDB"
 	$KEY = "ecc2f560b23df838470d19b7aef46cb2";
 	$URL = "https://api.themoviedb.org/";
 	$IMG_URL_SMALL = "https://image.tmdb.org/t/p/w300/";
 	$IMG_URL_LARGE = "https://image.tmdb.org/t/p/w400/";

 	// Película almacenada temporalmente
 	$PELICULA_TMP;

 	// Propiedades de paginación
 	$NUM_PAGINAS = 1;
 	$PAG_ACTUAL = 1;
 	$MAX_ELEMENTOS = 5;

	function obtenerPeliculasTendencia () {

		global $URL, $KEY;

 		// Retornar películas en tendencia
 		$json_data = file_get_contents($URL."3/trending/all/week?api_key=".
		$KEY."&include_adult=false&include_video=false&language=en-US&page=1&sort_by=popularity.desc");

 		// Decodificar resultado (JSON)
 		$peliculas = json_decode($json_data, true);

 		return $peliculas["results"];

 	}

 	function obtenerResultadoPeliculas ($busqueda, $pagina) {

 		global $URL, $KEY, $NUM_PAGINAS;

 		// Retornar resultados de la búsqueda
 		$json_data = file_get_contents($URL."3/search/movie?query=".urlencode($busqueda).
		 			"&api_key=".$KEY.
		 			"&language=en-US&region=US".
		 			"&page=".$pagina);

 		// Decodificar resutado (JSON)
 		$resultados = json_decode($json_data, true);

 		// Almacenar el número total de páginas del resultado de la búsqueda
 		$NUM_PAGINAS = $resultados["total_pages"];

 		return $resultados["results"];

 	}

 	function nombrePelicula () {
 		global $URL, $KEY, $PELICULA_TMP;

 		if (!isset($_GET['ID'])) {
 			echo "No se encontró la película";
 			return;
 		}

 		$ID = $_GET['ID'];
 		$json_data = file_get_contents($URL."3/movie/".$ID."?language=es-MX&api_key=".$KEY);

 		// Almacenar temporalmente película para mostrar sus detalles
 		$PELICULA_TMP = json_decode($json_data, true);

 		// Obtener propiedad de nombre de la película almacenada
 		$nombre = $PELICULA_TMP ["original_title"];

 		echo $nombre;
 	}

 	function mostrarPeliculas ($peliculas, $pagina_principal) {
 		global $IMG_URL_SMALL;

 		for($i = 0; $i < count($peliculas); $i++) {

 			// Evitar mostrar películas sin portada o sin título
 			if($peliculas [$i]["poster_path"] == '' || !isset($peliculas [$i]["original_title"])) continue;

 			// Mapear calificación a ranto de 0 a 5 estrellas
 			$calificacion = number_format((floatval($peliculas [$i]["vote_average"]) / 10) * 5.0, 1);

 			$pelicula =  
 			"<a href='";

 			// Agregar información de la película
 			if ($pagina_principal)
				$pelicula .= "php/DetallesPelicula.php?ID=".$peliculas [$i]["id"]."'>";
			else
				$pelicula .= "../php/DetallesPelicula.php?ID=".$peliculas [$i]["id"]."'>";

 			$pelicula .= "<div class='pelicula'>
 				<img class='portada' src='".$IMG_URL_SMALL."/".
 				$peliculas [$i]["poster_path"]."' alt='".$peliculas [$i]["id"]."' >";

			$pelicula .= "<h3 class='titulo'>".$peliculas [$i]["original_title"]."</h3>";
			$pelicula .= obtenerCalificacion ($peliculas [$i]);
			$pelicula .= "</div></a>";

			// Mostrar películas en la página
			echo $pelicula;
 		}

 	}

 	function obtenerCalificacion ($PELICULA) {

 		// Mapear calificación a ranto de 0 a 5 estrellas
		$calificacion = number_format((floatval($PELICULA ["vote_average"]) / 10) * 5.0, 1);

		$rate = "<div class='rate'><h4>Calificación: <br>".$calificacion;

		// Mostrar calificación (estrellas)
		for ($estrellas = 1; $estrellas <= 5; $estrellas++) {
			if($estrellas <= intval($calificacion)) $rate .= "<span class='fa fa-star checked'></span>";
		}

		$rate .= "</h4></div>";

		// Retornar calificación (lista para ser mostrada)
		return $rate;
 			
 	}

 	function mostrarTendencias () {

 		// Recuperar películas desde la API
 		$peliculas = obtenerPeliculasTendencia ();

 		mostrarPeliculas ($peliculas, true);

 	}

 	function mostrarResultadoBusqueda () {

 		$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : NULL;

 		if (!isset($busqueda)) {
 			echo "<div><h1>No se encontró ninguna película</h1></div>";
 			return;
 		}

 		$pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

 		// Realizar búsqueda de la película, utilizando la API
 		$resultados = obtenerResultadoPeliculas ($busqueda, $pagina);

 		// No se encontraron resultados
 		if(count($resultados) == 0) {
 			mostrarMensaje ('No se encontraron resultados', 'fa fa-film');
 			return;
 		}

 		// Mostrar los resultados encontrados
 		mostrarPeliculas ($resultados, false);

 	}

 	function mostrarMensaje ($mensaje, $icono) {

 		// Imprimir mensaje en la página
 		echo 
 		"</div><div class='msg-pagina'>
 			<i class='".$icono."' style='font-size: 80px;'></i>
 			<h1>".$mensaje."</h1>
 		</div>";
 	}

 	function mostrarDetallesPelicula () {
 		global $PELICULA_TMP, $IMG_URL_LARGE;

 		// Obtener ID de película a mostrar
 		$ID = $_GET['ID'];

 		if (!isset($ID)) {
 			echo "<div><h1>No se encontró la película</h1></div>";
 			return;
 		}

 		// Agregar portada y título
 		$detalles =  
 		"<img class='poster' src='".$IMG_URL_LARGE."/".$PELICULA_TMP ["poster_path"]."' alt='".$PELICULA_TMP ["id"]."'>".
 		"<div class='informacion'><h1>".$PELICULA_TMP ["original_title"]."</h1>".
 		"<h3><b>Géneros: </b><br>";

 		// Agregar los géneros de la película
 		for ($i = 0; $i < count($PELICULA_TMP ["genres"]); $i ++) {
 			$detalles .= $PELICULA_TMP ["genres"][$i]["name"];
 			if ($i < count($PELICULA_TMP ["genres"]) - 1) $detalles .= ", ";
 		}

 		$detalles .= "</h3>";

 		// Agregar sinópsis
 		$detalles .= "<p>".$PELICULA_TMP ["overview"]."</p>";

 		// Agregar productora(s)
 		$detalles .= "<h3><b>Productora(s): </b><br>";
 		for ($i = 0; $i < count($PELICULA_TMP ["production_companies"]); $i ++) {
 			$detalles .= $PELICULA_TMP ["production_companies"][$i]["name"];
 			if ($i < count($PELICULA_TMP ["production_companies"]) - 1) $detalles .= ", ";
 		}

 		// Agregar pais(es)
 		$detalles .= "<h3><b>País: </b><br>";
 		for ($i = 0; $i < count($PELICULA_TMP ["production_countries"]); $i ++) {
 			$detalles .= $PELICULA_TMP ["production_countries"][$i]["name"];
 			if ($i < count($PELICULA_TMP ["production_countries"]) - 1) $detalles .= ", ";
 		}

 		// Agregar fecha de lanzamiento
 		$detalles .= "<h3><b>Lanzamiento:</b><br>";
 		$detalles .= $PELICULA_TMP ["release_date"];
 		$detalles .= "</h3>";

 		// Agregar calificación
 		$detalles .= obtenerCalificacion ($PELICULA_TMP);
 		$detalles .= "<button onclick='history.go(-1);'>Regresar</button>";
 		$detalles .= "</div>";

 		echo $detalles;

 	}

 	function mostrarPaginacion () {
 		global $NUM_PAGINAS, $PAG_ACTUAL, $MAX_ELEMENTOS;

 		if(!isset($_GET['buscar'])) return;

 		// Obtener página actual
 		if(isset($_GET['pagina'])) $PAG_ACTUAL = $_GET['pagina'];

 		// Mantener número de página dentro de los límites
 		if($PAG_ACTUAL > $NUM_PAGINAS) $PAG_ACTUAL = $NUM_PAGINAS;
 		else if($PAG_ACTUAL < 1) $PAG_ACTUAL = 1; 

 		// Página anterior
 		$pagina_anterior = (($PAG_ACTUAL - 1) > 1) ? ($PAG_ACTUAL - 1) : 1;

 		// Siguiente página
 		$pagina_siguiente = ($PAG_ACTUAL + 1) <= $NUM_PAGINAS ? ($PAG_ACTUAL + 1) : $NUM_PAGINAS;
 		
 		$paginacion = "<div class='paginacion'><a class='item selector' href='?buscar=".$_GET["buscar"]."&pagina=".
 					  $pagina_anterior."'>&laquo;</a>";

 		// Agregar atajo a primera página
 		if($PAG_ACTUAL > 1) 
 			$paginacion .= "<a class='item' href='?buscar=".$_GET['buscar']."&pagina=1''>1</a>...";

 		for($i = 0; $i < $MAX_ELEMENTOS; $i++) {
 			if(($PAG_ACTUAL + $i) > $NUM_PAGINAS) break;

 			if($i == 0) {

 				// El elemento corresponde con la página actual
 				$paginacion .= "<a class='item actual' href='?buscar=".$_GET['buscar']."&pagina=".
 						   	   ($PAG_ACTUAL + $i)."'>".($PAG_ACTUAL + $i)."</a>";
			   	continue;
 			}
 			$paginacion .= "<a class='item' href='?buscar=".$_GET['buscar']."&pagina=".
 						   ($PAG_ACTUAL + $i)."'>".($PAG_ACTUAL + $i)."</a>";
 		}

 		// Agregar atajo a última página
 		if($PAG_ACTUAL < $NUM_PAGINAS)
 			$paginacion .= "...<a class='item' href='?buscar=".$_GET['buscar']."&pagina=".$NUM_PAGINAS.
 						   "''>".$NUM_PAGINAS."</a>";

 		$paginacion .= "<a class='item selector' href='?buscar=".$_GET['buscar']."&pagina=".$pagina_siguiente.
 		   			   "'>&raquo;</a></div>";

 		// Mostrar paginación solo si hay más de 1 página de resultados
 		if ($NUM_PAGINAS > 1) echo $paginacion;
 	}
?>