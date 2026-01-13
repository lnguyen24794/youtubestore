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
        
        // Filter function (client-side for instant feedback)
        function applyFilters() {
            var priceFrom = parseFloat($('input[name="price_from"]').val()) || 0;
            var priceTo = parseFloat($('input[name="price_to"]').val()) || Infinity;
            var subscribersFrom = parseFloat($('input[name="subscribers_from"]').val()) || 0;
            var subscribersTo = parseFloat($('input[name="subscribers_to"]').val()) || Infinity;
            var searchTerm = $('#channel-search').val().toLowerCase();
            
            var visibleCount = 0;
            
            $('.channels-table tbody tr').each(function() {
                var $row = $(this);
                if ($row.find('.no-results').length > 0) {
                    return; // Skip no-results row
                }
                
                var price = parseFloat($row.data('price')) || 0;
                var subscribers = parseFloat($row.data('subscribers')) || 0;
                var category = $row.data('category').toLowerCase();
                var status = $row.data('status').toLowerCase();
                var rowText = $row.text().toLowerCase();
                
                var priceMatch = (price >= priceFrom && price <= priceTo);
                var subscribersMatch = (subscribers >= subscribersFrom && subscribers <= subscribersTo);
                var searchMatch = (searchTerm === '' || rowText.indexOf(searchTerm) !== -1 || category.indexOf(searchTerm) !== -1);
                
                if (priceMatch && subscribersMatch && searchMatch) {
                    $row.show();
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });
            
            // Show message if no results
            if (visibleCount === 0) {
                if ($('.channels-table tbody .no-results').length === 0) {
                    $('.channels-table tbody').append('<tr><td colspan="5" class="no-results"><h3>Không tìm thấy kênh nào phù hợp với bộ lọc</h3><p>Vui lòng thử lại với điều kiện khác</p></td></tr>');
                }
            } else {
                $('.channels-table tbody .no-results').closest('tr').remove();
            }
        }
        
        // Top filter form
        $('#top-filter-form').on('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
        
        // Reset filter
        $('#reset-filter').on('click', function() {
            $('#top-filter-form')[0].reset();
            $('#channel-search').val('');
            $('.channels-table tbody tr').show();
            $('.channels-table tbody .no-results').closest('tr').remove();
        });
        
        // Filter on input change
        $('#top-filter-form input').on('input', function() {
            applyFilters();
        });
        
        // Per page change
        $('#per-page').on('change', function() {
            var perPage = parseInt($(this).val());
            var url = new URL(window.location.href);
            url.searchParams.set('posts_per_page', perPage);
            url.searchParams.set('paged', 1);
            window.location.href = url.toString();
        });
        
        // Search functionality
        $('#channel-search').on('keyup', function() {
            applyFilters();
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
