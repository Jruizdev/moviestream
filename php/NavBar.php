<?php

	// Instancia global de la clase BarraNavegación
	$bNav;

	function crearBarraNav ($tipo) {
		global $bNav;

		// Instanciar nueva barra de navegación
		$bNav = new BarraNavegacion();

		switch ($tipo) {
			case 1:
				// Barra de navegación de la página principal (index)
				$bNav->barraIndex();
				break;
			
			default:
				// Barra de navegación por defecto
				$bNav->barraDefault();
				break;
		}
	}

	function crearFooter () {
		global $bNav;

		// Footer aplicable para todas las páginas
		if(isset($bNav)) $bNav->footer();
	}

	class BarraNavegacion {

		public function barraDefault() {

			// Mostrar barra de navegación por defecto
			echo 
			"<div class='nav-bar'>

				<div class='contenedor'>
					<a href='../index.php'>
						<div class='nav-var titulo item'>
							<font class='resaltar'><font class='mayus'>M</font>OVIE</font><font class='mayus'>S</font>TREAM
						</div>
					</a>

					<!-- BARRA DE BÚSQUEDA -->
					<form class='item' method='GET' action='BuscarPelicula.php'>
						<input id='txt_busqueda' type='text' placeholder='Buscar película..' name='buscar'>
						<button id='btn_busqueda' disabled><i type='submit' class='fa fa-search'></i></button>
					</form>
				</div>

			</div>";
		}

		public function barraIndex() {

			// Mostrar barra de navegación de la página principal
			echo 
			"<div class='nav-bar'>

				<div class='contenedor'>
					<a href='index.php'>
						<div class='nav-var titulo item'>
							<font class='resaltar'><font class='mayus'>M</font>OVIE</font><font class='mayus'>S</font>TREAM
						</div>
					</a>

					<!-- BARRA DE BÚSQUEDA -->
					<form class='item' method='GET' action='php/BuscarPelicula.php'>
						<input id='txt_busqueda' type='text' placeholder='Buscar película..' name='buscar'>
						<button id='btn_busqueda' disabled><i type='submit' class='fa fa-search'></i></button>
					</form>
				</div>

			</div>";
		}

		public function footer() {

			// Obtener año actual
			$año = date("Y");

			// Mostrar footer
			echo 
			"<footer>
				<div class='leyenda'>
					This Website uses TMDB and the TMDB APIs but is not endorsed, certified, or otherwise approved by TMDB.
				</div>
				<div>
					Powered by 
					<a href='https://www.themoviedb.org/'>
						<img class='attr-logo' src='https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg'>
					</a>
				</div>
				<div class='atribucion'>Jonathan Ruiz, ".$año.".</div>
			</footer>";
		}
	}

?>