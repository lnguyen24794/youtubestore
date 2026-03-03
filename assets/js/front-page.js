/**
 * Front page: welcome modal + YouTube facade. Deferred to reduce main-thread work.
 */
(function () {
    function initModal() {
        var modal = document.getElementById('custom-welcome-modal');
        if (!modal) return;
        modal.style.display = 'flex';
        modal.addEventListener('click', function (e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    }

    function initFacades() {
        var facades = document.querySelectorAll('.youtube-facade');
        function loadYoutubeVideo(facade) {
            var videoId = facade.getAttribute('data-id');
            if (!videoId || facade.classList.contains('loaded')) return;
            var iframe = document.createElement('iframe');
            iframe.src = 'https://www.youtube-nocookie.com/embed/' + videoId + '?autoplay=1&rel=0&playsinline=1';
            iframe.setAttribute('frameborder', '0');
            iframe.setAttribute('style', 'position: absolute; top: 0; left: 0; width: 100%; height: 100%;');
            iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');
            iframe.setAttribute('allowfullscreen', 'true');
            facade.innerHTML = '';
            facade.appendChild(iframe);
            facade.classList.add('loaded');
        }
        facades.forEach(function (facade) {
            var overlay = facade.querySelector('.play-button-overlay');
            facade.addEventListener('mouseenter', function () {
                if (overlay) {
                    overlay.style.background = 'rgba(220, 53, 69, 1)';
                    overlay.style.transform = 'translate(-50%, -50%) scale(1.1)';
                }
            });
            facade.addEventListener('mouseleave', function () {
                if (overlay) {
                    overlay.style.background = 'rgba(220, 53, 69, 0.9)';
                    overlay.style.transform = 'translate(-50%, -50%) scale(1)';
                }
            });
            facade.addEventListener('click', function () { loadYoutubeVideo(facade); });
            if (window.innerWidth > 768) loadYoutubeVideo(facade);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initModal();
            initFacades();
        });
    } else {
        initModal();
        initFacades();
    }
})();
