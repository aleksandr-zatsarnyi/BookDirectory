$(document).ready(function () {
    $('#open-create-form').click(function () {
        $('#create-book-form').show();
    });

    $('#close-create-form').click(function () {
        $('#create-book-form').hide();
    });

    $('#bookForm').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        formData.append('image', $('#image')[0].files[0]);
        formData.append('title', $('#title').val());
        formData.append('description', $('#description').val());
        formData.append('publicationDate', $('#publicationDate').val());

        $.ajax({
            type: 'POST',
            url: '/api/books/',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                $('#message').html('<p>Книга успішно створена.</p>');
            },
            error: function (xhr) {
                $('#message').html('<p>Сталася помилка при створенні книги.</p>');
            }
        });
    });
});