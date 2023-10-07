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