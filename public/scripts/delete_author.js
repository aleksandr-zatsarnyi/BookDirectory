document.addEventListener('DOMContentLoaded', function () {
    const openDeleteFormButton = document.getElementById('open-delete-form');
    const deleteForm = document.getElementById('delete-author-form');

    let isSearchFormVisible = false;

    openDeleteFormButton.addEventListener('click', () => {
        if (isSearchFormVisible) {
            deleteForm.style.display = 'none';
        } else {
            deleteForm.style.display = 'block';
        }
        isSearchFormVisible = !isSearchFormVisible;
    });

    deleteForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(deleteForm);
        const authorId = formData.get('id');

        fetch(`/api/authors/${authorId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
            }
        })
            .then(response => {
                if (response.ok) {
                    showModal('Author updated successfully.');
                } else {
                    showModal('An error occurred while updating the author.');
                }
            })
            .catch(error => {
                showModal('An error occurred while updating the author.');
            });
        deleteForm.style.display = 'none';
    });
});