<?php include ('php/NavBar.php'); include('php/ConexionAPI.php') ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/msstyle.css">
	<title>MovieStream</title>
</head>
<body>
	<!-- BARRA DE NAVEGACIÓN -->
	<?php crearBarraNav (1) ?>

	<!-- PELÍCULAS RECIENTES -->
	<div class="contenedor-principal">
		<div class="titulo"><b>Tendencias</b> de la semana</div>
		<div class="contenedor-peliculas">
			<?php mostrarTendencias () ?>
		</div>
	</div>
</body>

<!-- FOOTER -->
<?php crearFooter() ?>
<script type="text/javascript" src="js/busqueda.js"></script>
</html>