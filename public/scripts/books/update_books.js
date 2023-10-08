$(document).ready(function () {
    $('#open-update-form').click(function () {
        $('#update-book-form').show();
        $.ajax({
            url: '/api/authors/get',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var authorSelect = $('#update_authors');

                authorSelect.empty();

                data.forEach(function (author) {
                    var fullName = author.firstName + " " +
                        (author.secondName ? author.secondName + " " : "") +
                        author.lastName;
                    authorSelect.append($('<option>', {
                        value: author.id,
                        text: fullName
                    }));
                });
            },
            error: function (xhr) {
                console.error('Ошибка при получении списка авторов:', xhr);
            }
        });
    });

    $('#close-update-form').click(function () {
        $('#update-book-form').hide();
    });

    $('#updateBookForm').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        const bookId = formData.get('id');

        var selectedAuthors = $('#update_authors').val();
        if (selectedAuthors) {
            var authorsJSON = JSON.stringify(selectedAuthors);
            formData.append('authors', authorsJSON);
        }

        var imageInput = $('#update_image')[0];
        var maxFileSize = 2 * 1024 * 1024;

        if (imageInput.files.length > 0) {
            var imageFile = imageInput.files[0];

            if (imageFile.size > maxFileSize) {
                alert('Image size exceeds 2 MB. Please select a file smaller than 2 MB.');
                return;
            }

            var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;
            if (!allowedExtensions.exec(imageFile.name)) {
                alert('Please select a .jpg or .png image file for upload.');
                return;
            }
        }

        $.ajax({
            type: 'POST',
            url: '/api/books/update/' + bookId,
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                showModal('Book updated successfully.');
            },
            error: function (xhr) {
                showModal('An error occurred while creating the book.');
            }
        });
        $('#update-book-form').hide();
    });
});