const modal = document.getElementById('modal');
const closeModalButton = document.getElementById('close-modal');
const modalMessage = document.getElementById('modal-message');

function showModal(message) {
    modalMessage.textContent = message
    modal.style.display = 'block';
}
function closeModal() {
    modal.style.display = 'none';
}

closeModalButton.addEventListener('click', () => {
    closeModal();
});