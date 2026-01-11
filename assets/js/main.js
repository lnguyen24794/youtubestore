// Main JS
document.addEventListener('DOMContentLoaded', function () {

    /**
     * Youtube Facade
     */
    const facades = document.querySelectorAll('.youtube-facade');
    facades.forEach(facade => {
        facade.addEventListener('click', function () {
            const vidId = this.getAttribute('data-id');
            if (vidId) {
                const iframe = document.createElement('iframe');
                iframe.setAttribute('src', 'https://www.youtube.com/embed/' + vidId + '?autoplay=1&rel=0');
                iframe.setAttribute('frameborder', '0');
                iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
                iframe.setAttribute('allowfullscreen', true);
                this.innerHTML = '';
                this.appendChild(iframe);
            }
        });
    });

    /**
     * AJAX Filter
     */
    const filterForm = document.getElementById('filter-form');
    const channelGrid = document.getElementById('channel-grid');
    const loader = document.getElementById('loader');

    if (filterForm && channelGrid) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            runFilter();
        });

        // Also trigger on change for checkboxes/radios
        const inputs = filterForm.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('change', function () {
                runFilter();
            });
        });
    }

    function runFilter() {
        if (!filterForm) return;

        // Show loader
        channelGrid.style.opacity = '0.5';
        if (loader) loader.style.display = 'block';

        const formData = new FormData(filterForm);
        formData.append('action', 'filter_channels');

        // Convert to URLSearchParams
        const params = new URLSearchParams(formData);

        // Fetch
        // Note: WordPress defines 'ajaxurl' if we localize script. 
        // We didn't localize 'main.js' yet in functions.php. We need to fix that.
        // Assuming /wp-admin/admin-ajax.php for now or get from window variable

        const ajaxUrl = window.youtubestore_vars ? window.youtubestore_vars.ajaxurl : '/wp-admin/admin-ajax.php';

        fetch(ajaxUrl, {
            method: 'POST',
            body: params
        })
            .then(response => response.text())
            .then(html => {
                channelGrid.innerHTML = html;
                channelGrid.style.opacity = '1';
                if (loader) loader.style.display = 'none';
            })
            .catch(err => {
                console.error(err);
                channelGrid.style.opacity = '1';
            });
    }
});
