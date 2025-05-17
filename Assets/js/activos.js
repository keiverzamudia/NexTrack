$(document).ready(function() {
    // Función para cargar los datos
    function loadData(searchTerm = '') {
        $.ajax({
            url: 'get_activos.php',
            type: 'POST',
            data: { search: searchTerm },
            success: function(response) {
                $('#dataTable tbody').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición AJAX:', {
                    status: status,
                    error: error,
                    response: xhr.responseText
                });
                $('#dataTable tbody').html(
                    '<tr><td colspan="4" class="text-center text-danger">' +
                    'Error al cargar los datos: ' + error + '<br>' +
                    'Detalles: ' + xhr.responseText +
                    '</td></tr>'
                );
            }
        });
    }

    // Cargar datos iniciales
    loadData();

    // Búsqueda en tiempo real
    $('#searchInput').on('keyup', function() {
        var searchTerm = $(this).val();
        loadData(searchTerm);
    });
});