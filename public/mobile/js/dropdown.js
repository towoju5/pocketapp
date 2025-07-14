function handleDropdownClick(e) {
    const dropdown = e.target.closest('[data-dropdown]');
    if (!dropdown) return;

    const menuId = dropdown.id.replace('Dropdown', 'Menu');
    const menu = document.getElementById(menuId);
    const arrow = dropdown.querySelector('svg');
    
    menu.classList.toggle('hidden');
    arrow.style.transform = menu.classList.contains('hidden') ? '' : 'rotate(180deg)';
    e.stopPropagation();
}

function handleOptionClick(e) {
    const option = e.target.closest('.period-option');
    if (!option) return;

    const period = option.dataset.period;
    const text = option.textContent;
    const dropdown = document.getElementById('periodDropdown');
    const menu = document.getElementById('periodMenu');
    
    document.getElementById('selectedPeriod').textContent = text;
    menu.classList.add('hidden');
    dropdown.querySelector('svg').style.transform = '';
    
    // Handle period selection
    console.log('Selected period:', period);
}

function initializeDropdowns() {
    // Remove any existing listeners
    document.removeEventListener('click', handleDropdownClick);
    document.removeEventListener('click', handleOptionClick);
    
    // Add listeners
    document.addEventListener('click', handleDropdownClick);
    document.addEventListener('click', handleOptionClick);
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        const dropdowns = document.querySelectorAll('[data-dropdown]');
        dropdowns.forEach(dropdown => {
            if (!dropdown.contains(e.target)) {
                const menuId = dropdown.id.replace('Dropdown', 'Menu');
                const menu = document.getElementById(menuId);
                const arrow = dropdown.querySelector('svg');
                
                if (menu && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    arrow.style.transform = '';
                }
            }
        });
    });
}

// Initialize on page load and content changes
// document.addEventListener('DOMContentLoaded', initializeDropdowns);
// document.getElementById('main-content').addEventListener('DOMNodeInserted', () => {
//     setTimeout(initializeDropdowns, 0);
// });
