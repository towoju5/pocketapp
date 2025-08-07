// Initialize state object
let navigationState = {
    wasOnForexPage: false,
    currentPage: 'forex'
};

function handleNavigation(navItem) {
    const targetPage = navItem.getAttribute('data-target');
    const isCurrentlyActive = navItem.classList.contains('text-blue-500');

    // Remove active state from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('text-blue-500', 'bg-gray-700');
        item.classList.add('text-gray-400');
    });

    // If clicking active item, deactivate it and show welcome template
    if (isCurrentlyActive) {
        // if currentUrl is set in localStorage retrieve it
        const currentUrl = localStorage.getItem('currentUrl');
        if (currentUrl) {
            // If currentUrl is set, navigate to it
            window.location.href = currentUrl;
        } else {
            loadWelcomeTemplate();
        }
        return;
    }

    // Otherwise, activate clicked item and load content
    navItem.classList.remove('text-gray-400');
    navItem.classList.add('text-blue-500', 'bg-gray-700');
    // log current url to Storage
    localStorage.setItem('currentUrl', window.location.href);
    loadPage(targetPage);
}

function loadWelcomeTemplate() {
    const template = document.getElementById('welcome-template');
    if (template) {
        document.getElementById('main-content').innerHTML = template.innerHTML;
        // Reinitialize chart
        if (window.forexChart && typeof window.forexChart.initialize === 'function') {
            setTimeout(() => window.forexChart.initialize(), 100);
        }
    }
}

// async function loadPage(pageName) {
//     const mainContent = document.getElementById('main-content');

//     try {
//         // Update navigation state
//         navigationState.wasOnForexPage = navigationState.currentPage === 'forex';
//         navigationState.currentPage = pageName;

//         if (pageName === 'forex') {
//             loadWelcomeTemplate();
//         } else {
//             try {
//                 const response = await fetch(`pages/${pageName}.html`);
//                 mainContent.innerHTML = await response.text();
//             } catch (error) {
//                 console.error('Error loading page:', error);
//                 mainContent.innerHTML = '<div class="p-4 text-center text-gray-400">Page not found</div>';
//             }
//         }
//     } catch (error) {
//         console.error('Error in loadPage:', error);
//         mainContent.innerHTML = '<div class="p-4 text-center text-gray-400">Error loading page</div>';
//     }
// }

async function loadPage(pageName) {
    const mainContent = document.getElementById('main-content');

    try {
        // Update navigation state
        navigationState.wasOnForexPage = navigationState.currentPage === 'forex';
        navigationState.currentPage = pageName;

        if (pageName === 'forex') {
            loadWelcomeTemplate();
        } else {
            try {
                // This assumes a Laravel route like Route::get('/pages/{page}', ...)
                const response = await fetch(`/pages/${pageName}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const html = await response.text();
                mainContent.innerHTML = html;
            } catch (error) {
                console.error('Error loading Laravel page:', error);
                mainContent.innerHTML = '<div class="p-4 text-center text-gray-400">Page not found</div>';
            }
        }
    } catch (error) {
        console.error('Error in loadPage:', error);
        mainContent.innerHTML = '<div class="p-4 text-center text-gray-400">Error loading page</div>';
    }
}


// Reset state when page loads
window.addEventListener('load', () => {
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('text-blue-500', 'bg-gray-700');
        item.classList.add('text-gray-400');
    });
    loadWelcomeTemplate();
});

// Export functions
window.loadPage = loadPage;
window.handleNavigation = handleNavigation;
