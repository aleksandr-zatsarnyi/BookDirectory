document.addEventListener('DOMContentLoaded', function () {
    const getAllAuthorsButton = document.getElementById('get-all-authors-button');
    const resultTableModal = document.getElementById('result-table-modal');
    const authorTableBody = document.getElementById('author-table-body');
    const currentPageSpan = document.getElementById('current-page');
    const prevPageButton = document.getElementById('prev-page');
    const nextPageButton = document.getElementById('next-page');
    let currentPage = 1;

    function loadPage(page) {
        fetch(`/api/authors/get?page=${page}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                authorTableBody.innerHTML = '';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(author => {
                        const row = document.createElement('tr');
                        const idNameCell = document.createElement('td');
                        const firstNameCell = document.createElement('td');
                        const secondNameCell = document.createElement('td');
                        const lastNameCell = document.createElement('td');

                        idNameCell.textContent = author.id;
                        firstNameCell.textContent = author.firstName;
                        secondNameCell.textContent = author.secondName;
                        lastNameCell.textContent = author.lastName;

                        row.appendChild(idNameCell);
                        row.appendChild(firstNameCell);
                        row.appendChild(secondNameCell);
                        row.appendChild(lastNameCell);

                        authorTableBody.appendChild(row);
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

    getAllAuthorsButton.addEventListener('click', () => {
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
