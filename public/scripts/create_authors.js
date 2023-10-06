document.addEventListener('DOMContentLoaded', function() {
    const toggleCreateFormButton = document.getElementById('open-create-form');
    const createAuthorForm = document.getElementById('create-author-form');

    let isCreateFormVisible = false;

    toggleCreateFormButton.addEventListener('click', () => {
        if (isCreateFormVisible) {
            createAuthorForm.style.display = 'none';
        } else {
            createAuthorForm.style.display = 'block';
        }
        isCreateFormVisible = !isCreateFormVisible;
    });

    createAuthorForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(createAuthorForm);

        fetch('/api/authors/', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    console.log('Author created successfully.');
                } else {
                    console.error('Произошла ошибка при создании автора');
                }
            })
            .catch(error => {
                console.error('Произошла ошибка при отправке запроса:', error);
            });
    });
});
