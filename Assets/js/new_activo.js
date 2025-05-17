$(document).ready(function() {
    const registroActivoModal = $('#registroActivoModal');
    const tipoActivoSelect = $('#tipoActivo');
    const formularioRegistroActivo = $('#formularioRegistroActivo');
    const botonSubirActivo = $('#botonSubirActivo');

    // Escuchar el cambio en el selector de tipo de activo (ya lo tienes)
    tipoActivoSelect.on('change', function() {
        $('.campos-tipo').hide();
        const tipoSeleccionado = $(this).val();
        switch (tipoSeleccionado) {
            case 'PC':
                $('#camposPC').show();
                break;
            case 'Camara':
                $('#camposCamara').show();
                break;
            case 'Bateria':
                $('#camposBateria').show();
                break;
            case 'Monitor':
                $('#camposMonitor').show();
                break;
        }
    });

    $('#botonSubirActivo').on('click', function() {
        var formData = $('#formularioRegistroActivo').serialize();
        $.ajax({
            url: 'Add_Activos.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#registroActivoModal').modal('hide');
                    // Aquí puedes recargar la tabla si quieres
                } else {
                    alert('Error: ' + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la petición: ' + error);
            }
        });
    });

    // Limpiar el formulario y ocultar los campos al cerrar el modal (ya lo tienes)
    registroActivoModal.on('hidden.bs.modal', function() {
        formularioRegistroActivo[0].reset();
        $('.campos-tipo').hide();
        tipoActivoSelect.val('');
    });
});