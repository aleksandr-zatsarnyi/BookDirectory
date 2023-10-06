document.addEventListener('DOMContentLoaded', function () {
    const getAllAuthorsButton = document.getElementById('get-all-authors-button');
    let currentPage = 1;

    getAllAuthorsButton.addEventListener('click', () => {
        fetch(`/api/authors/get?page=${currentPage}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Произошла ошибка при выполнении AJAX-запроса:', error);
            });
    });
});
