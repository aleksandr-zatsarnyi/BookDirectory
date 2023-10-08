$(document).ready(function () {
    const getAllBooksButton = document.getElementById('get-all-books-button');
    const resultTableModal = document.getElementById('result-table-modal');
    const booksTableBody = document.getElementById('book-table-body');
    const currentPageSpan = document.getElementById('current-page');
    const prevPageButton = document.getElementById('prev-page');
    const nextPageButton = document.getElementById('next-page');
    let currentPage = 1;

    function loadPage(page) {
        $.ajax({
            type: 'GET',
            url: `/api/books/get?page=${page}`,
            dataType: 'json',
            success: function (data) {
                booksTableBody.innerHTML = '';

                data.forEach(function (book) {
                    var authors = book.authors.map(function (author) {
                        return author.firstName + " " + (author.secondName ? author.secondName + " " : "") + author.lastName;
                    }).join(', ');

                    var newRow = $('<tr>');
                    newRow.append($('<td>').text(book.id));
                    newRow.append($('<td>').text(book.title));
                    newRow.append($('<td>').text(book.description));
                    newRow.append($('<td>').text(book.imagePath));
                    newRow.append($('<td>').text(book.publicationDate));
                    newRow.append($('<td>').text(authors));
                    $('#book-table-body').append(newRow);
                });

                currentPage = page;
                currentPageSpan.textContent = `Page ${page}`;
            },
            error: function (xhr) {
                console.error('Something goes wrong:', xhr);
            }
        });
    }

    getAllBooksButton.addEventListener('click', function () {
        loadPage(currentPage);
        resultTableModal.style.display = 'block';
    });

    prevPageButton.addEventListener('click', function () {
        if (currentPage > 1) {
            loadPage(currentPage - 1);
            nextPageButton.disabled = false;
        }
    });

    nextPageButton.addEventListener('click', function () {
        loadPage(currentPage + 1);
    });

    document.getElementById('close-result-table-modal').addEventListener('click', function () {
        resultTableModal.style.display = 'none';
    });
});