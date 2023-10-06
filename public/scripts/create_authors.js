document.addEventListener('DOMContentLoaded', function () {
    const openCreateFormButton = document.getElementById('open-create-form');
    const createAuthorForm = document.getElementById('create-author-form');
    const closeCreateFormButton = document.getElementById('close-create-form');
    const form = createAuthorForm.querySelector('form');

    openCreateFormButton.addEventListener('click', () => {
        createAuthorForm.style.display = 'block';
    });

    closeCreateFormButton.addEventListener('click', () => {
        createAuthorForm.style.display = 'none';
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        fetch('/api/authors/', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    showModal('Author created successfully.');
                } else {
                    showModal('An error occurred while creating the author.');
                }
            })
            .catch(error => {
                showModal('An error occurred while creating the author.');
            });
        createAuthorForm.style.display = 'none';
    });
});
