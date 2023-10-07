$(document).ready(function () {
    $('#open-delete-form').click(function () {
        $('#delete-book-form').show();
    });

    $('#delete-book-form').submit(function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const bookId = formData.get('id');

        $.ajax({
            type: 'DELETE',
            url: '/api/books/' + bookId,
            success: function (data) {
                showModal('Book deleted successfully.');
            },
            error: function (xhr) {
                showModal('An error occurred while deleting the book.');
            }
        });
    });
});