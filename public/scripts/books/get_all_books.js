document.addEventListener('DOMContentLoaded', function () {
    const getAllBooksButton = document.getElementById('get-all-books-button');
    const resultTableModal = document.getElementById('result-table-modal');
    const booksTableBody = document.getElementById('book-table-body');
    const currentPageSpan = document.getElementById('current-page');
    const prevPageButton = document.getElementById('prev-page');
    const nextPageButton = document.getElementById('next-page');
    let currentPage = 1;

    function loadPage(page) {
        fetch(`/api/books/get?page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                booksTableBody.innerHTML = '';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(book => {
                        var authorNames = book.authors.map(function(author) {
                            return author.firstName + " " + (author.secondName ? author.secondName + " " : "") + author.lastName;
                        });
                        var concatenatedNames = authorNames.join(", ");

                        const row = document.createElement('tr');
                        const idCell = document.createElement('td');
                        const titleCell = document.createElement('td');
                        const descriptionNameCell = document.createElement('td');
                        const imagePathCell = document.createElement('td');
                        const publicationDateCell = document.createElement('td');
                        const authorsCell = document.createElement('td');

                        idCell.textContent = book.id;
                        titleCell.textContent = book.title;
                        descriptionNameCell.textContent = book.description;
                        imagePathCell.textContent = book.imagePath;
                        publicationDateCell.textContent = book.publicationDate
                        authorsCell.textContent = concatenatedNames

                        row.appendChild(idCell);
                        row.appendChild(titleCell);
                        row.appendChild(descriptionNameCell);
                        row.appendChild(imagePathCell);
                        row.appendChild(publicationDateCell);
                        row.appendChild(authorsCell);

                        booksTableBody.appendChild(row);
                    });

                    currentPage = page;
                    currentPageSpan.textContent = `Page ${page}`;
                } else {
                    nextPageButton.disabled = true;
                }
            })
            .catch(error => {
                console.error('Something goes wrong:', error);
            });
    }

    getAllBooksButton.addEventListener('click', () => {
        loadPage(currentPage);
        resultTableModal.style.display = 'block';
    });

    prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
            loadPage(currentPage - 1);
            nextPageButton.disabled = false;
        }
    });

    nextPageButton.addEventListener('click', () => {
        loadPage(currentPage + 1);
    });

    document.getElementById('close-result-table-modal').addEventListener('click', () => {
        resultTableModal.style.display = 'none';
    });
});
