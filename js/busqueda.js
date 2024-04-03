// Elementos de la barra de búsqueda
let txt_busqueda;
let btn_busqueda;

window.onload = function () {

	// Obtener barra de búsqueda
	txt_busqueda = document.querySelector ('#txt_busqueda');

	// Obtener botón de búsqueda
	btn_busqueda = document.querySelector ('#btn_busqueda');

	txt_busqueda.oninput = function () {

		// Activar botón de búsqueda cuando la barra de búsqueda no esté vacía
		if(txt_busqueda.value != '' && btn_busqueda.disabled) btn_busqueda.disabled = false;

		// Desactivar botón de búsqueda cuando la barra de búsqueda esté vacía
		else if(txt_busqueda.value == '') btn_busqueda.disabled = true;
	}
}