$(document).ready(function () {
    // Obtener el ID del usuario actual desde el atributo data-user-id del body
    const currentUserId = $('body').data('user-id');

    // Enviar comentario al presionar Enter en el input
    $(document).on('keydown', '.comment-input', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // evitar submit
            const photoId = $(this).data('id');
            $(`.comment-btn[data-id="${photoId}"]`).click();
        }
    });

    // Enviar comentario al hacer clic
    $(document).on('click', '.comment-btn', function () {
        const photoId = $(this).data('id');
        const input = $(`.comment-input[data-id="${photoId}"]`);
        const comment = input.val().trim();

        if (comment === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Empty comment',
                text: 'Write a comment before sending.'
            });
            return;
        }

        $.ajax({
            url: '../backend/comment.php',
            type: 'POST',
            data: { photo_id: photoId, comment: comment },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    input.val('');
                    updateCountComments(photoId);
                    loadComments(photoId);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Comment error',
                        text: data.message || 'Try again.'
                    });
                }
            },
            error: function (xhr) {
                console.error("AJAX error:", xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Comment not sent.'
                });
            }
        });
    });

    // Alternar visibilidad de comentarios
    $(document).on('click', '.see-comments-btn', function () {
        const photoId = $(this).data('id');
        const commentsDiv = $(`#comments-${photoId}`);

        if (commentsDiv.length === 0) {
            console.warn("Comments container not found for photo ID:", photoId);
            return;
        }

        if (commentsDiv.is(':visible')) {
            commentsDiv.slideUp();
        } else {
            $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
                let html = '';
                if (comments.length === 0) {
                    html = '<div class="text-muted">No comments yet.</div>';
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
                console.error("Error loading comments:", textStatus, errorThrown);
                commentsDiv.html('<div class="text-danger">Error loading comments.</div>');
                commentsDiv.slideDown();
            });
        }
    });

    // Cargar comentarios sin toggle
    function loadComments(photoId) {
        const commentsDiv = $(`#comments-${photoId}`);
        if (commentsDiv.length === 0) return;

        $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
            let html = '';
            if (comments.length === 0) {
                html = '<div class="text-muted">No comments yet.</div>';
            } else {
                comments.forEach(c => {
                    if (c.comment && c.comment.trim() !== "") {
                        html += `<div class="mb-1 comment-text">🗨️ ${c.comment}</div>`;
                    }
                });
            }
            commentsDiv.html(html);
        }).fail(function () {
            commentsDiv.html('<div class="text-danger">Error loading comments.</div>');
        });
    }

    // Actualizar contador de comentarios en el botón
    function updateCountComments(photoId) {
        $.getJSON(`../backend/get_comments.php?photo_id=${photoId}`, function (comments) {
            const button = $(`.see-comments-btn[data-id="${photoId}"]`);
            const count = comments.length;
            button.html(`📄 Comments (${count})`);
        });
    }


    // Subir foto con AJAX
    $('#trigger-upload').click(function(e) {
        e.preventDefault();
        $('#photoInput').click();
    });

    // Al seleccionar archivo, subirlo automáticamente por AJAX
    $('#photoInput').on('change', function () {
        var fileInput = this;
        if (fileInput.files && fileInput.files[0]) {
            var formData = new FormData();
            formData.append('photo', fileInput.files[0]);

            $.ajax({
                url: '../backend/up.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#answer').html(response);
                    $('#photoInput').val(''); // reset input
                    loadPhotos(); // recarga galería
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Photo not uploaded.'
                    });
                }
            });
        }
    });


    // Cargar fotos y construir cards
    function loadPhotos() {
        $.ajax({
            url: '../backend/getphotos.php',
            method: 'GET',
            dataType: 'json',
            success: function (photos) {
                let gallery = $('#gallery');
                gallery.html('');

                photos.forEach(photo => {
                    // Solo mostrar botón eliminar si el dueño es el usuario actual
                    let deleteButton = '';
                    if (photo.user_id == currentUserId) {
                        deleteButton = `
                            <button class="btn butt btn-sm text-white delete-photo" data-id="${photo.id}" style="background-color: #7494ec;">
                                🗑️ Delete
                            </button>
                        `;
                    }

                    gallery.append(`
                        <div class="d-flex justify-content-center mb-4">
                            <div class="card w-100" style="max-width: 400px;">
                                <div class="card-header d-flex justify-content-between align-items-center" style="color: #000; background-color:#fff;">
                                    <div class="d-flex align-items-center">
                                        <img src="../assets/icon.jpeg" 
                                             alt="${photo.full_name} ${photo.last_name}" 
                                             class="rounded-circle" 
                                             style="width: 30px; height: 30px; margin-right: 10px;">
                                        <span class="text-black">${photo.full_name} ${photo.last_name}</span>
                                    </div>
                                </div>
                                <img src="../assets/uploads/${photo.image_path}" 
                                     class="img-fluid rounded-top" 
                                     style="width: 100%; height: auto; max-height: 400px; object-fit: contain;" 
                                     alt="photo subida">
                                <div class="card-body text-center" style="background-color:#fff;">
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control comment-input" placeholder="Write a comment..." data-id="${photo.id}">
                                        <button class="btn comment-btn text-white" data-id="${photo.id}" style="background-color: #7494ec;">
                                            💬
                                        </button>
                                    </div>
                                    <div id="comments-${photo.id}" class="mt-2 text-start" style="display:none;"></div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <button class="btn butt btn-sm text-white see-comments-btn" data-id="${photo.id}" style="background-color: #7494ec;">
                                            📄 Comments (${photo.comments})
                                        </button>
                                        ${deleteButton}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            },
            error: function () {
                $('#gallery').html('<p class="text-danger">Error loading photos</p>');
            }
        });
    }

    // Eliminar foto con confirmación
    $(document).on('click', '.delete-photo', function () {
        const photoId = $(this).data('id');

        Swal.fire({
            title: 'Delete this photo?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../backend/deletephoto.php',
                    method: 'POST',
                    data: { id: photoId },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Photo deleted',
                            text: response
                        });
                        loadPhotos();
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Could not delete photo.'
                        });
                    }
                });
            }
        });
    });

    // Carga inicial de fotos
    loadPhotos();


    // toggle sidebar (if needed)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.querySelector('.toggle-btn');
        sidebar.classList.toggle('collapsed');
        toggleBtn.classList.toggle('collapsed');
    }
});
