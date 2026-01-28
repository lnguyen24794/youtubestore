/**
 * Archive Channels JavaScript
 * Handles filtering, sorting, and interactions for YouTube channel archive page
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Get current sort from URL
        var urlParams = new URLSearchParams(window.location.search);
        var currentOrderby = urlParams.get('orderby') || 'subscribers';
        var currentOrder = urlParams.get('order') || 'DESC';
        
        // Set initial sort indicator
        $('.channels-table th').each(function() {
            var sortField = $(this).find('.sort-indicator').data('sort');
            if (sortField === currentOrderby) {
                var indicator = currentOrder === 'ASC' ? '↑' : '↓';
                $(this).find('.sort-indicator').text(indicator);
            }
        });
        
        // Copy URL functionality
        $('.btn-copy-url').on('click', function() {
            var url = $(this).data('url');
            var $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();
            
            // Show notification
            var $btn = $(this);
            var originalText = $btn.text();
            $btn.text('Đã sao chép!');
            setTimeout(function() {
                $btn.text(originalText);
            }, 2000);
        });
        
        // Range Slider Functionality
        function updateRangeTrack(type) {
            var fromSlider = $('#' + type + '-slider-from');
            var toSlider = $('#' + type + '-slider-to');
            var fromValue = parseInt(fromSlider.val());
            var toValue = parseInt(toSlider.val());
            var min = parseInt(fromSlider.attr('min'));
            var max = parseInt(fromSlider.attr('max'));
            
            // Ensure from doesn't exceed to
            if (fromValue > toValue) {
                fromValue = toValue;
                fromSlider.val(fromValue);
            }
            
            // Ensure to doesn't go below from
            if (toValue < fromValue) {
                toValue = fromValue;
                toSlider.val(toValue);
            }
            
            // Update input fields
            $('#' + type + '-input-from').val(fromValue);
            $('#' + type + '-input-to').val(toValue);
            
            // Update track color
            var $wrapper = fromSlider.closest('.range-slider-wrapper');
            var $track = $wrapper.find('.range-track');
            var fromPercent = ((fromValue - min) / (max - min)) * 100;
            var toPercent = ((toValue - min) / (max - min)) * 100;
            
            $track.css({
                'background': 'linear-gradient(to right, #ddd ' + fromPercent + '%, #dc3545 ' + fromPercent + '%, #dc3545 ' + toPercent + '%, #ddd ' + toPercent + '%)'
            });
            
            // Update value display
            var fromFormatted = fromValue.toLocaleString('vi-VN');
            var toFormatted = toValue.toLocaleString('vi-VN');
            $('#' + type + '-value').text(fromFormatted + ' - ' + toFormatted);
        }
        
        // Initialize range tracks
        updateRangeTrack('price');
        updateRangeTrack('subscribers');
        
        // Debounce function
        function debounce(func, wait) {
            var timeout;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    func.apply(context, args);
                }, wait);
            };
        }
        
        // Handle slider changes - update track in real-time, apply filter on change end
        $('.range-slider').on('input', function() {
            var type = $(this).data('type');
            updateRangeTrack(type);
        });
        
        $('.range-slider').on('change', function() {
            applyFilters();
        });
        
        // Handle input field changes with debounce
        var debouncedApplyFilters = debounce(applyFilters, 500);
        
        $('.range-input').on('input', function() {
            var type = $(this).data('type');
            var side = $(this).data('side');
            var value = parseInt($(this).val()) || 0;
            var min = parseInt($(this).attr('min')) || 0;
            var max = parseInt($(this).attr('max')) || 1000000;
            
            // Clamp value
            value = Math.max(min, Math.min(max, value));
            $(this).val(value);
            
            // Update corresponding slider
            $('#' + type + '-slider-' + side).val(value);
            updateRangeTrack(type);
        });
        
        $('.range-input').on('change', function() {
            applyFilters();
        });
        
        // Filter function - Update URL like sort
        function applyFilters() {
            var priceFrom = parseInt($('#price-input-from').val()) || 0;
            var priceTo = parseInt($('#price-input-to').val()) || 0;
            var subscribersFrom = parseInt($('#subscribers-input-from').val()) || 0;
            var subscribersTo = parseInt($('#subscribers-input-to').val()) || 0;
            var searchTerm = $('#channel-search').val();
            
            // Get min/max values
            var priceMin = parseInt($('#price-slider-from').attr('min')) || 0;
            var priceMax = parseInt($('#price-slider-from').attr('max')) || 1000000;
            var subscribersMin = parseInt($('#subscribers-slider-from').attr('min')) || 0;
            var subscribersMax = parseInt($('#subscribers-slider-from').attr('max')) || 1000000;
            
            // Build URL with filter parameters
            var url = new URL(window.location.href);
            
            // Only add filter params if they're different from min/max
            if (priceFrom > priceMin || priceTo < priceMax) {
                url.searchParams.set('price_from', priceFrom);
                url.searchParams.set('price_to', priceTo);
            } else {
                url.searchParams.delete('price_from');
                url.searchParams.delete('price_to');
            }
            
            if (subscribersFrom > subscribersMin || subscribersTo < subscribersMax) {
                url.searchParams.set('subscribers_from', subscribersFrom);
                url.searchParams.set('subscribers_to', subscribersTo);
            } else {
                url.searchParams.delete('subscribers_from');
                url.searchParams.delete('subscribers_to');
            }
            
            if (searchTerm) {
                url.searchParams.set('search', searchTerm);
            } else {
                url.searchParams.delete('search');
            }
            
            // Reset to page 1 when filtering
            url.searchParams.set('paged', 1);
            
            // Redirect to new URL (server-side filtering)
            window.location.href = url.toString();
        }
        
        // Reset filter
        $('#reset-filter').on('click', function() {
            var url = new URL(window.location.href);
            url.searchParams.delete('price_from');
            url.searchParams.delete('price_to');
            url.searchParams.delete('subscribers_from');
            url.searchParams.delete('subscribers_to');
            url.searchParams.delete('search');
            url.searchParams.set('paged', 1);
            window.location.href = url.toString();
        });
        
        // Per page change
        $('#per-page').on('change', function() {
            var perPage = parseInt($(this).val());
            var url = new URL(window.location.href);
            url.searchParams.set('posts_per_page', perPage);
            url.searchParams.set('paged', 1);
            window.location.href = url.toString();
        });
        
        // Search functionality with debounce
        var debouncedSearch = debounce(applyFilters, 800);
        $('#channel-search').on('keyup', function() {
            debouncedSearch();
        });
        
        // Sort functionality - Server-side via URL
        $('.channels-table th').on('click', function() {
            var sortField = $(this).find('.sort-indicator').data('sort');
            if (!sortField) return;
            
            var url = new URL(window.location.href);
            var currentOrderbyParam = url.searchParams.get('orderby');
            var currentOrderParam = url.searchParams.get('order') || 'DESC';
            
            // Determine new order
            var newOrder = 'DESC';
            if (currentOrderbyParam === sortField) {
                newOrder = currentOrderParam === 'DESC' ? 'ASC' : 'DESC';
            }
            
            // Set URL parameters
            url.searchParams.set('orderby', sortField);
            url.searchParams.set('order', newOrder);
            url.searchParams.set('paged', 1); // Reset to page 1 when sorting
            
            // Redirect to new URL
            window.location.href = url.toString();
        });
    });
})(jQuery);
