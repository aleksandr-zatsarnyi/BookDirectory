document.addEventListener('DOMContentLoaded', function () {
    const openUpdateFormButton = document.getElementById('open_update-form');
    const updateAuthorForm = document.getElementById('update-author-form');
    const closeUpdateFormButton = document.getElementById('close-update-form');
    const form = updateAuthorForm.querySelector('form');

    openUpdateFormButton.addEventListener('click', () => {
        updateAuthorForm.style.display = 'block';
    });

    closeUpdateFormButton.addEventListener('click', () => {
        updateAuthorForm.style.display = 'none';
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const authorId = formData.get('id');

        fetch(`/api/authors/${authorId}`, {
            method: 'PUT',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showModal(data.message);
                } else {
                    showModal('An error occurred while updating the author.');
                }
            })
            .catch(error => {
                showModal('An error occurred while updating the author.');
            });
        updateAuthorForm.style.display = 'none';
    });
});
