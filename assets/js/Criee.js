document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.querySelector('.menu-btn');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');
    const closeBtn = document.querySelector('.close-btn');
    
    let isOpen = false;
    
    function toggleSidebar(e) {
        e.preventDefault();
        isOpen = !isOpen;
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }
    
    menuBtn.addEventListener('click', toggleSidebar);
    closeBtn.addEventListener('click', toggleSidebar);
    overlay.addEventListener('click', toggleSidebar);
});