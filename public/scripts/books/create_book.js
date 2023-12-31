$(document).ready(function () {
    $('#open-create-form').click(function () {
        $('#create-book-form').show();
        $.ajax({
            url: '/api/authors/get',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                var authorSelect = $('#create_authors');

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
                console.error('Error while retrieving the list of authors:', xhr);
            }
        });
    });

    $('#close-create-form').click(function () {
        $('#create-book-form').hide();
    });

    $('#bookForm').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var selectedAuthors = $('#create_authors').val();
        if (selectedAuthors) {
            var authorsJSON = JSON.stringify(selectedAuthors);
            formData.append('authors', authorsJSON);
        }

        var imageInput = $('#image')[0];
        var maxFileSize = 2 * 1024 * 1024; // 2 МБ

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
            url: '/api/books/',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                showModal('Book created successfully.');
            },
            error: function (xhr) {
                showModal('An error occurred while creating the book.');
            }
        });
        $('#create-book-form').hide();
    });
});