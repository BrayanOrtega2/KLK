$(document).ready(function () {
    
    
    // send comment
    $(document).on('click', '.comment-btn', function () {
        const photoId = $(this).data('id');
        const input = $(`.comment-input[data-id="${photoId}"]`);
        const comment = input.val().trim();

        if (comment === '') {
            alert('Escribe un comentario primero');
            return;
        }

        $.ajax({
            url: '../backend/comment.php',
            type: 'POST',
            data: {
                photo_id: photoId,
                comment: comment
            },
            dataType: 'json', 
            success: function (data) {
                if (data.success) {
                    input.val('');
                    cargarComentarios(photoId);

                } else {
                    alert(data.message || 'Error al comentar.');
                }
            },
            error: function (xhr, status, error) {
                console.error(" Error en la solicitud AJAX:", xhr.responseText);
                alert('No se pudo enviar el comentario');
            }
        });
        
    });

    // see comments
    $(document).on('click', '.ver-comentarios-btn', function () {
        const photoId = $(this).data('id');
        const commentsDiv = $(`#comments-${photoId}`);

        if (commentsDiv.length === 0) {
            console.warn("No se encontró el contenedor de comentarios para la foto ID:", photoId);
            return;
        }

        if (commentsDiv.is(':visible')) {
            commentsDiv.slideUp();
        } else {
            $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
                console.log(" Comentarios recibidos:", comments);


                let html = '';
                if (comments.length === 0) {
                    html = '<div class="text-muted">No hay comentarios aún.</div>';
                } else {

                    comments.forEach(c => {
                        if (c.comment && c.comment.trim() !== "") {
                            html += `<div class="mb-1 comment-text">🗨️ ${c.comment}</div>`;
                        }
                    });

                }

                commentsDiv.html(html);
                commentsDiv.slideDown();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.error("Error al obtener comentarios:", textStatus, errorThrown);
                commentsDiv.html('<div class="text-danger">Error al cargar comentarios.</div>');
                commentsDiv.slideDown();
            });
        }
    });
    function cargarComentarios(photoId) {
        const commentsDiv = $(`#comments-${photoId}`);
    
        if (commentsDiv.length === 0) return;
    
        commentsDiv.html('<div class="text-muted">Cargando comentarios...</div>');
    
        $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
            let html = '';
            if (comments.length === 0) {
                html = '<div class="text-muted">No hay comentarios aún.</div>';
            } else {
                comments.forEach(c => {
                    if (c.comment && c.comment.trim() !== "") {
                        html += `<div class="mb-1 comment-text" >🗨️ ${c.comment}</div>`;
                    }
                });
            }
    
            commentsDiv.html(html);
            if (!commentsDiv.is(':visible')) {
                commentsDiv.slideDown();
            }
        }).fail(function () {
            commentsDiv.html('<div class="text-danger">Error al cargar comentarios.</div>');
        });
    }


    // form up photos
    $('#mostrarFormulario').click(function () {
        $('#formularioFoto').toggle();
    });

    // up img ajax
    $('#formFoto').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '../backend/up.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#respuesta').html(response);
                $('#formFoto')[0].reset();
                $('#formularioFoto').hide();
                cargarFotos(); 
            },
            error: function () {
                $('#respuesta').html('Error al subir la foto.');
            }
        });
    });
    
    function cargarFotos() {
        $.ajax({
            url: '../backend/getphotos.php',
            method: 'GET',
            dataType: 'json',
            success: function (fotos) {
                let galeria = $('#galeria');
                galeria.html('');

                fotos.forEach(foto => {
                    galeria.append(`
                       <div class="d-flex justify-content-center mb-4">
                            <div class="card w-100" style="max-width: 500px;">
                                <img src="../assets/uploads/${foto.image_path}" 
                                    class="img-fluid rounded-top" 
                                    style="width: 100%; height: auto; object-fit: contain;" 
                                    alt="foto subida">

                                <div class="card-body text-center" style="background-color: #272829;">
                                    <!-- Input Comentario -->
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control comment-input" placeholder="Escribe un comentario..." data-id="${foto.id}">
                                        <button class="btn comment-btn text-white" data-id="${foto.id}" style="background-color: #5a5e9a;">
                                            💬
                                        </button>
                                    </div>

                                    <!-- Comentarios -->
                                    <div id="comments-${foto.id}" class="mt-2 text-start" style="display:none;"></div>

                                    <!-- Botones inferiores -->
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn butt btn-sm text-white ver-comentarios-btn" data-id="${foto.id}" style="background-color: #5a5e9a;">
                                            📄 Comentarios
                                        </button>
                                        <button class="btn butt btn-sm text-white eliminar-foto" data-id="${foto.id}" style="background-color: #5a5e9a;">
                                            🗑️ Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                                            `);
                });


            },
            error: function () {
                $('#galeria').html('<p class="text-danger">Error al cargar las fotos</p>');
            }
        });
    }


    $(document).on('click', '.eliminar-foto', function () {
        const idFoto = $(this).data('id');

        if (confirm('¿Estás seguro de que deseas eliminar esta foto?')) {
            $.ajax({
                url: '../backend/deletephoto.php',
                method: 'POST',
                data: { id: idFoto },
                success: function (response) {
                    alert(response);
                    cargarFotos(); 
                },
                error: function () {
                    alert('Error al eliminar la foto.');
                }
            });
        }
    });
    cargarFotos();
});
