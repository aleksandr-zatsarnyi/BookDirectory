document.addEventListener('DOMContentLoaded', function() {
    const getAllAuthorsButton = document.getElementById('get-all-authors-button');
    const authorsList = document.getElementById('authors-list');
    let currentPage = 1; // Начальная страница

    getAllAuthorsButton.addEventListener('click', () => {
        fetch(`/api/authors/get?page=${currentPage}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json()) // Ожидать JSON-ответ
            .then(data => {
                authorsList.innerHTML = '';
                data.items.forEach(author => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${author.firstName} ${author.lastName}`;
                    authorsList.appendChild(listItem);
                });

                currentPage = data.currentPage; // Обновить номер текущей страницы
            })
            .catch(error => {
                console.error('Произошла ошибка при выполнении AJAX-запроса:', error);
            });
    });
});
