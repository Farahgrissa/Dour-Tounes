document.addEventListener('DOMContentLoaded', function() {

    const destinations = document.querySelectorAll('.destination');
    destinations.forEach((dest, index) => {
        setTimeout(() => {
            dest.style.opacity = '1';
            dest.style.transform = 'translateY(0)';
        }, 100 * index);
    });

    document.querySelectorAll('.destination').forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.transform = 'scale(1.03)';
        });
        card.addEventListener('mouseout', () => {
            card.style.transform = 'scale(1)';
        });
    });

    // 3. Feedback des boutons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!this.getAttribute('href')) {
                e.preventDefault();
                this.textContent = 'Bientôt disponible!';
                setTimeout(() => {
                    this.textContent = 'Voir Plus';
                }, 1000);
            }
        });
    });

    const yearElement = document.querySelector('.footer-bottom p');
    if (yearElement) {
        yearElement.textContent = yearElement.textContent.replace('2025', new Date().getFullYear());
    }
});