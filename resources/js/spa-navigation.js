document.addEventListener('DOMContentLoaded', () => {
    // Configuration
    const mainContentSelector = '#main-content';
    const sidebarNavSelector = '#sidebar-nav';
    const progressBarSelector = '#nprogress'; // Optional: if we want to add a progress bar later

    // Function to handle navigation
    const navigate = async (url) => {
        try {
            // Optional: Start loading indicator here
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Swap Main Content
            const newContent = doc.querySelector(mainContentSelector);
            const currentContent = document.querySelector(mainContentSelector);
            
            if (newContent && currentContent) {
                currentContent.innerHTML = newContent.innerHTML;
                
                // Re-initialize scripts in the new content if necessary
                // For Alpine.js, it might handle DOM mutations seamlessly or need a kick
                // If using standard Alpine, it usually observes standard DOM. 
                // however, sometimes we might need to rescan.
            }

            // Swap Sidebar (to update active states)
            const newSidebar = doc.querySelector(sidebarNavSelector);
            const currentSidebar = document.querySelector(sidebarNavSelector);

            if (newSidebar && currentSidebar) {
                currentSidebar.innerHTML = newSidebar.innerHTML;
            }

            // Update Title
            const newTitle = doc.querySelector('title');
            if (newTitle) {
                document.title = newTitle.innerText;
            }

            // Update URL
            window.history.pushState({}, '', url);

            // Optional: Scroll to top
            window.scrollTo(0, 0);

        } catch (error) {
            console.error('Navigation error:', error);
            // Fallback to full reload on error
            window.location.href = url;
        }
    };

    // Intercept Clicks
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        
        // Validation: must be a link, must have href
        if (!link || !link.href) return;

        // Validation: must be strictly internal, not download, not target blank
        const url = new URL(link.href);
        if (url.origin !== window.location.origin) return;
        if (link.hasAttribute('download')) return;
        if (link.target === '_blank') return;
        if (link.dataset.noSpa) return; // Allow opting out

        // If it's a form submit button inside a link (unlikely but possible), ignore
        
        // Prevent default behavior
        e.preventDefault();
        navigate(link.href);
    });

    // Handle Back/Forward Browser Buttons
    window.addEventListener('popstate', () => {
        navigate(window.location.href);
    });
});
