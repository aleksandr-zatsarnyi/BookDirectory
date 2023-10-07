document.addEventListener('DOMContentLoaded', function () {
    const openSearchFormButton = document.getElementById('open-search-form');
    const searchForm = document.getElementById('search-book-form');

    let isSearchFormVisible = false;

    openSearchFormButton.addEventListener('click', () => {
        if (isSearchFormVisible) {
            searchForm.style.display = 'none';
        } else {
            searchForm.style.display = 'block';
        }
        isSearchFormVisible = !isSearchFormVisible;
    });

    searchForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(searchForm);

        fetch('/api/books/search?' + new URLSearchParams(formData), {
            method: 'GET'
        })
            .then(response => response.text())
            .then(data => {
                document.open();
                document.write(data);
                document.close();
            })
            .catch(error => {
                console.error('Something wrong with request:', error);
            });
    });
});