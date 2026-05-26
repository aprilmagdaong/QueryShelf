const searchInput = document.getElementById('searchInput');
const cards = document.querySelectorAll('.book-card');

searchInput.addEventListener('keyup', function () {
    const value = searchInput.value.toLowerCase();
    cards.forEach(card => {
        const title = card.querySelector('h2').textContent.toLowerCase();
        card.style.display = title.includes(value) ? 'block' : 'none';
    });
});