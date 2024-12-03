document.querySelector('.dropdown-toggle').addEventListener('click', () => {
    const menu = document.querySelector('.hamburger-menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
});