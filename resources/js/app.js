//=========== CERRAR ALERTA SUCCESS
document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar la alerta 
    const alerta = document.getElementById('alerta-success');
    
    if (alerta) {

        // Configuramos el temporizador 
        setTimeout(function () {

            // Instanciamos la alerta de Bootstrap
            const alertaBootstrap = new bootstrap.Alert(alerta);
            alertaBootstrap.close();
        }, 3000); 
    }
});

document.addEventListener('DOMContentLoaded', function () {
    // Seleccionamos la alerta por su ID
    const alerta = document.getElementById('alert-message');
    
    if (alerta) {
        // Configuramos el temporizador (ej. 3000 ms = 3 segundos)
        setTimeout(function () {
            // Instanciamos la alerta de Bootstrap y la cerramos
            const alertaBootstrap = new bootstrap.Alert(alerta);
            alertaBootstrap.close();
        }, 10000); 
    }
});



//=========== CHECKBOX MAESTRO
 // Seleccionamos el checkbox principal y todos los demás
  const masterCheckbox = document.getElementById('seleccionar-todos');
  const checkboxes = document.querySelectorAll('.opcion');

  // Escuchamos cuando cambia el estado del checkbox maestro
  masterCheckbox.addEventListener('change', function() {
    checkboxes.forEach(function(checkbox) {
      checkbox.checked = masterCheckbox.checked;
    });
  });