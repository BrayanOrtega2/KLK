$(document).ready(function () {
    // send comment
    $(document).on('click', '.comment-btn', function () {
        const photoId = $(this).data('id');
        const input = $(`.comment-input[data-id="${photoId}"]`);
        const comment = input.val().trim();

        if (comment === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Comentario vacío',
                text: 'Escribe un comentario antes de enviarlo.'
            });
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
                    updateCountComments(photoId);
                    loadComments(photoId);

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al comentar',
                        text: data.message || 'Intenta nuevamente.'
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error(" Error en la solicitud AJAX:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo enviar el comentario.'
                });
            }
        });

    });

    // see comments
    $(document).on('click', '.see-comments-btn', function () {
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
                    html = '<div class="text-muted">No hay comments aún.</div>';
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
                console.error("Error al obtener comenatarios:", textStatus, errorThrown);
                commentsDiv.html('<div class="text-danger">Error al cargar comentarios.</div>');
                commentsDiv.slideDown();
            });
        }
    });
    function loadComments(photoId) {
        const commentsDiv = $(`#comments-${photoId}`);
    
        if (commentsDiv.length === 0) return;
    
       
        $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
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
        }).fail(function () {
            commentsDiv.html('<div class="text-danger">Error al cargar comentarios.</div>');
        });
    }
    

    function updateCountComments(photoId) {
        $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
            const boton = $(`.see-comments-btn[data-id="${photoId}"]`);
            const cantidad = comments.length;
            boton.html(`📄 Comentarios (${cantidad})`);
        });
    }
    

    // form up photos
    $('#showForm').click(function () {
        $('#formsPhotos').toggle();
    });

    // up img ajax
    $('#formPhoto').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: '../backend/up.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $('#answer').html(response);
                $('#formPhoto')[0].reset();
                $('#formsPhotos').hide();
                loadPhotos();
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo subir la photo.'
                });
                
            }
        });
    });

    function loadPhotos() {
        $.ajax({
            url: '../backend/getphotos.php',
            method: 'GET',
            dataType: 'json',
            success: function (photos) {
                let gallery = $('#gallery');
                gallery.html('');

                photos.forEach(photo => {
                    gallery.append(`
                       <div class="d-flex justify-content-center mb-4">
                            <div class="card w-100" style="max-width: 500px;">
                                <img src="../assets/uploads/${photo.image_path}" 
                                    class="img-fluid rounded-top " 
                                    style="width: 100%; height: auto; object-fit: contain;" 
                                    alt="photo subida">
                                
                                <div class="card-body text-center" style="background-color: #272829;">
                                    <!-- Input Comentario -->
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control comment-input" placeholder="Escribe un comentario..." data-id="${photo.id}">
                                        <button class="btn comment-btn text-white" data-id="${photo.id}" style="background-color: #5a5e9a;">
                                            💬
                                        </button>
                                    </div>

                                    <!-- Comentarios -->
                                    <div id="comments-${photo.id}" class="mt-2 text-start" style="display:none;"></div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn butt btn-sm text-white see-comments-btn" data-id="${photo.id}" style="background-color: #5a5e9a;">
                                            📄 Comentarios (${photo.comments})
                                        </button>



                                        <button class="btn butt btn-sm text-white delete-photo" data-id="${photo.id}" style="background-color: #5a5e9a;">
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
                $('#gallery').html('<p class="text-danger">Error al cargar las photos</p>');
            }
        });
    }


    $(document).on('click', '.delete-photo', function () {
        const idFoto = $(this).data('id');

        Swal.fire({
            title: '¿Eliminar esta photo?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../backend/deletephoto.php',
                    method: 'POST',
                    data: { id: idFoto },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Foto eliminada',
                            text: response
                        });
                        loadPhotos();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No se pudo delete la photo.'
                        });
                    }
                });
            }
        });
        
    });
    loadPhotos();
});
