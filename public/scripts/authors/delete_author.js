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
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showModal(data.message);
                } else {
                    showModal('An error occurred while deleting the author.');
                }
            })
            .catch(error => {
                showModal('An error occurred while deleting the author.');
            });
        deleteForm.style.display = 'none';
    });
});