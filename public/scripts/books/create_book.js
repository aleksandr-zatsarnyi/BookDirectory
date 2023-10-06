document.addEventListener('DOMContentLoaded', function() {
    const createForm = document.getElementById('create-book-form');
    const openFormButton = document.getElementById('open-create-form');
    const closeFormButton = document.getElementById('close-create-form');

    openFormButton.addEventListener('click', function () {
        createForm.style.display = 'block';
    });

    closeFormButton.addEventListener('click', function () {
        createForm.style.display = 'none';
    });
});