<?php
/**
 * Admin Page for Laravel Import
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('YOUTUBESTORE_DIR')) {
    define('YOUTUBESTORE_DIR', get_template_directory());
}

require_once YOUTUBESTORE_DIR . '/inc/import/class-laravel-import.php';

class YoutubeStore_Import_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_import_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_youtubestore_import_data', array($this, 'ajax_import_data'));
        add_action('wp_ajax_youtubestore_import_from_sql', array($this, 'ajax_import_from_sql'));
        add_action('wp_ajax_youtubestore_import_from_csv', array($this, 'ajax_import_from_csv'));
    }

    public function add_import_page()
    {
        add_submenu_page(
            'tools.php',
            'Import Laravel Data',
            'Import Laravel Data',
            'manage_options',
            'youtubestore-import',
            array($this, 'render_import_page')
        );
    }

    public function enqueue_scripts($hook)
    {
        if ('tools_page_youtubestore-import' !== $hook) {
            return;
        }
        wp_enqueue_script('jquery');
    }

    public function render_import_page()
    {
        ?>
        <div class="wrap">
            <h1>Import Data from Laravel Database</h1>
            
            <div class="card" style="max-width: 900px; margin-top: 20px;">
                <h2>📋 Hướng dẫn Export SQL Files từ Laravel</h2>
                <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2271b1; margin: 15px 0;">
                    <p><strong>Bạn cần export các bảng sau từ database Laravel:</strong></p>
                    <ol style="margin-left: 20px;">
                        <li><code>categories</code> - Danh mục bài viết</li>
                        <li><code>posts</code> - Bài viết</li>
                        <li><code>seos</code> - Dữ liệu SEO</li>
                    </ol>
                    <p><strong>Cách export:</strong></p>
                    <ul style="margin-left: 20px;">
                        <li>Vào phpMyAdmin hoặc MySQL client</li>
                        <li>Chọn database Laravel của bạn</li>
                        <li>Export từng bảng với format SQL (INSERT statements)</li>
                        <li>Lưu các file vào thư mục: <code><?php echo YOUTUBESTORE_DIR; ?>/laravel-database/</code></li>
                        <li>Đặt tên file: <code>categories.sql</code>, <code>posts.sql</code>, <code>seos.sql</code></li>
                    </ul>
                    <p><strong>Lệnh MySQL để export (nếu dùng command line):</strong></p>
                    <pre style="background: #fff; padding: 10px; border: 1px solid #ddd; overflow-x: auto;">mysqldump -u [username] -p [database_name] categories > categories.sql
mysqldump -u [username] -p [database_name] posts > posts.sql
mysqldump -u [username] -p [database_name] seos > seos.sql</pre>
                    <p style="margin-top: 10px;"><strong>📄 Xem hướng dẫn chi tiết:</strong> <code>laravel-database/EXPORT-GUIDE.md</code></p>
                </div>
            </div>

            <div class="card" style="max-width: 900px; margin-top: 20px;">
                <h2>🚀 Import từ CSV Files (Khuyến nghị)</h2>
                <p>Chức năng này sẽ tự động import từ file CSV:</p>
                <ul style="margin-left: 20px;">
                    <li>✅ Posts (Bài viết) → WordPress Posts</li>
                    <li>✅ Pages (Trang) → WordPress Pages</li>
                    <li>✅ SEO Data → Yoast SEO / Rank Math meta</li>
                </ul>
                <p><strong>Lưu ý:</strong></p>
                <ul style="margin-left: 20px; color: #d63638;">
                    <li>Chỉ import posts/pages có <code>language = 'vi'</code> và <code>status = 1</code> hoặc <code>'published'</code></li>
                    <li>Pages sẽ được tự động phân biệt dựa trên <code>seos.csv</code> (nếu <code>seoable_type = App\Models\Page</code>)</li>
                    <li><strong>Lưu ý:</strong> Nếu pages không có trong <code>posts.csv</code>, bạn cần export thêm bảng <code>pages</code> từ Laravel thành file <code>pages.csv</code></li>
                    <li>Images sẽ được download tự động từ URL trong CSV (nếu có)</li>
                    <li>Quá trình import có thể mất vài phút nếu có nhiều bài viết</li>
                </ul>
                <p style="margin-top: 20px;">
                    <button type="button" id="import-from-csv" class="button button-primary button-large">Bắt đầu Import từ CSV Files</button>
                </p>
                <div id="csv-import-results" style="margin-top: 20px;"></div>
            </div>

            <div class="card" style="max-width: 900px; margin-top: 20px;">
                <h2>📄 Import từ SQL Files</h2>
                <p>Chức năng này sẽ tự động import từ file SQL:</p>
                <ul style="margin-left: 20px;">
                    <li>✅ Categories (Danh mục) → WordPress Categories</li>
                    <li>✅ Posts (Bài viết) → WordPress Posts</li>
                    <li>✅ SEO Data → Yoast SEO / Rank Math meta</li>
                    <li>✅ Menu → Tạo menu WordPress từ categories</li>
                </ul>
                <p><strong>Lưu ý:</strong></p>
                <ul style="margin-left: 20px; color: #d63638;">
                    <li>Chỉ import posts có <code>language = 'vi'</code> và <code>status = 1</code> hoặc <code>'published'</code></li>
                    <li>Chỉ import categories có <code>language = 'vi'</code></li>
                    <li>Images sẽ được download tự động từ URL trong database (nếu có)</li>
                    <li>Quá trình import có thể mất vài phút nếu có nhiều bài viết</li>
                </ul>
                <p style="margin-top: 20px;">
                    <button type="button" id="import-from-sql" class="button button-secondary button-large">Bắt đầu Import từ SQL Files</button>
                </p>
                <div id="sql-import-results" style="margin-top: 20px;"></div>
            </div>
        </div>

        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Import from CSV Files
            $('#import-from-csv').on('click', function() {
                var $button = $(this);
                var $results = $('#csv-import-results');
                
                if (!confirm('Bạn có chắc chắn muốn import dữ liệu từ CSV files? Quá trình này có thể mất vài phút.')) {
                    return;
                }
                
                $button.prop('disabled', true).text('Đang import...');
                $results.html('<div class="notice notice-info"><p><strong>Đang xử lý...</strong> Vui lòng đợi, quá trình này có thể mất vài phút.</p></div>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    timeout: 300000, // 5 minutes timeout
                    data: {
                        action: 'youtubestore_import_from_csv',
                        nonce: '<?php echo wp_create_nonce("youtubestore_import_csv"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '<div class="notice notice-success"><p><strong>✅ Import hoàn tất!</strong></p><ul style="margin-left: 20px;">';
                            if (response.data.menu_pages) {
                                html += '<li><strong>Menu Pages:</strong> ' + (response.data.menu_pages.message || 'Đã tạo menu pages') + ' (' + (response.data.menu_pages.count || 0) + ' trang)</li>';
                            }
                            if (response.data.posts) {
                                var postMsg = response.data.posts.message || 'Không có dữ liệu';
                                var postCount = response.data.posts.posts_count || 0;
                                var pageCount = response.data.posts.pages_count || 0;
                                html += '<li><strong>Posts:</strong> ' + postMsg + (postCount > 0 ? ' (' + postCount + ' bài viết)' : '') + '</li>';
                                html += '<li><strong>Pages:</strong> ' + (pageCount > 0 ? pageCount + ' trang' : '0 trang') + '</li>';
                            }
                            if (response.data.seo) {
                                var seoMsg = response.data.seo.message || 'Không có dữ liệu';
                                var seoCount = response.data.seo.count || 0;
                                html += '<li><strong>SEO:</strong> ' + seoMsg + (seoCount > 0 ? ' (' + seoCount + ' bản ghi)' : '') + '</li>';
                            }
                            html += '</ul></div>';
                            $results.html(html);
                        } else {
                            $results.html('<div class="notice notice-error"><p><strong>❌ Import thất bại:</strong> ' + (response.data || 'Lỗi không xác định') + '</p></div>');
                        }
                        $button.prop('disabled', false).text('Bắt đầu Import từ CSV Files');
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = 'Connection error';
                        if (status === 'timeout') {
                            errorMsg = 'Request timeout. File CSV có thể quá lớn. Vui lòng thử lại hoặc tăng PHP timeout.';
                        } else if (xhr.responseJSON && xhr.responseJSON.data) {
                            errorMsg = xhr.responseJSON.data;
                        }
                        $results.html('<div class="notice notice-error"><p><strong>❌ Import thất bại:</strong> ' + errorMsg + '</p></div>');
                        $button.prop('disabled', false).text('Bắt đầu Import từ CSV Files');
                    }
                });
            });

            // Import from SQL Files
            $('#import-from-sql').on('click', function() {
                var $button = $(this);
                var $results = $('#sql-import-results');
                
                if (!confirm('Bạn có chắc chắn muốn import dữ liệu từ SQL files? Quá trình này có thể mất vài phút.')) {
                    return;
                }
                
                $button.prop('disabled', true).text('Đang import...');
                $results.html('<div class="notice notice-info"><p><strong>Đang xử lý...</strong> Vui lòng đợi, quá trình này có thể mất vài phút.</p></div>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    timeout: 300000, // 5 minutes timeout
                    data: {
                        action: 'youtubestore_import_from_sql',
                        nonce: '<?php echo wp_create_nonce("youtubestore_import_sql"); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            var html = '<div class="notice notice-success"><p><strong>✅ Import hoàn tất!</strong></p><ul style="margin-left: 20px;">';
                            if (response.data.categories) {
                                var catMsg = response.data.categories.message || 'Không có dữ liệu';
                                var catCount = response.data.categories.count || 0;
                                html += '<li><strong>Categories:</strong> ' + catMsg + (catCount > 0 ? ' (' + catCount + ' danh mục)' : '') + '</li>';
                            }
                            if (response.data.posts) {
                                var postMsg = response.data.posts.message || 'Không có dữ liệu';
                                var postCount = response.data.posts.count || 0;
                                html += '<li><strong>Posts:</strong> ' + postMsg + (postCount > 0 ? ' (' + postCount + ' bài viết)' : '') + '</li>';
                            }
                            if (response.data.seo) {
                                var seoMsg = response.data.seo.message || 'Không có dữ liệu';
                                var seoCount = response.data.seo.count || 0;
                                html += '<li><strong>SEO:</strong> ' + seoMsg + (seoCount > 0 ? ' (' + seoCount + ' bản ghi)' : '') + '</li>';
                            }
                            if (response.data.menu) {
                                html += '<li><strong>Menu:</strong> ' + (response.data.menu.message || 'Đã tạo menu') + '</li>';
                            }
                            html += '</ul></div>';
                            $results.html(html);
                        } else {
                            $results.html('<div class="notice notice-error"><p><strong>❌ Import thất bại:</strong> ' + (response.data || 'Lỗi không xác định') + '</p></div>');
                        }
                        $button.prop('disabled', false).text('Bắt đầu Import từ SQL Files');
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = 'Connection error';
                        if (status === 'timeout') {
                            errorMsg = 'Request timeout. File SQL có thể quá lớn. Vui lòng thử lại hoặc tăng PHP timeout.';
                        } else if (xhr.responseJSON && xhr.responseJSON.data) {
                            errorMsg = xhr.responseJSON.data;
                        }
                        $results.html('<div class="notice notice-error"><p><strong>❌ Import thất bại:</strong> ' + errorMsg + '</p></div>');
                        $button.prop('disabled', false).text('Bắt đầu Import từ SQL Files');
                    }
                });
            });
        });
        </script>
        <?php
    }

    public function ajax_import_data()
    {
        check_ajax_referer('youtubestore_import', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        $importer = new YoutubeStore_Laravel_Import();

        // Get database settings
        $db_host = get_option('youtubestore_import_db_host', 'localhost');
        $db_name = get_option('youtubestore_import_db_name', '');
        $db_user = get_option('youtubestore_import_db_user', '');
        $db_pass = get_option('youtubestore_import_db_pass', '');
        $db_prefix = get_option('youtubestore_import_db_prefix', '');

        if (empty($db_name) || empty($db_user)) {
            wp_send_json_error('Database settings are not configured');
        }

        $importer->set_database($db_host, $db_name, $db_user, $db_pass, $db_prefix);

        $results = array();

        // Import based on selected options
        if (isset($_POST['import_categories']) && $_POST['import_categories']) {
            $results['categories'] = $importer->import_categories();
        }

        if (isset($_POST['import_posts']) && $_POST['import_posts']) {
            $posts_result = $importer->import_posts();
            $results['posts'] = $posts_result;
            
            // Import SEO if posts were imported
            if (isset($_POST['import_seo']) && $_POST['import_seo'] && isset($posts_result['posts_map'])) {
                $results['seo'] = $importer->import_seo($posts_result['posts_map']);
            }
        }

        if (isset($_POST['create_menu']) && $_POST['create_menu']) {
            $results['menu'] = $importer->create_menu();
        }

        wp_send_json_success($results);
    }

    /**
     * Import from SQL files
     */
    public function ajax_import_from_sql()
    {
        check_ajax_referer('youtubestore_import_sql', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }

        // Increase execution time for large imports
        @set_time_limit(300);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '256M');

        require_once YOUTUBESTORE_DIR . '/inc/import/class-sql-import.php';
        
        try {
            $sql_importer = new YoutubeStore_SQL_Import();
            $results = $sql_importer->import_from_sql_files();
            wp_send_json_success($results);
        } catch (Exception $e) {
            wp_send_json_error('Import error: ' . $e->getMessage());
        } catch (Error $e) {
            wp_send_json_error('Import error: ' . $e->getMessage());
        }
    }

    /**
     * Import from CSV files
     */
    public function ajax_import_from_csv()
    {
        check_ajax_referer('youtubestore_import_csv', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }

        // Increase execution time for large imports
        @set_time_limit(300);
        @ini_set('max_execution_time', 300);
        @ini_set('memory_limit', '256M');

        require_once YOUTUBESTORE_DIR . '/inc/import/class-csv-import.php';
        
        try {
            $csv_importer = new YoutubeStore_CSV_Import();
            $results = $csv_importer->import_from_csv_files();
            wp_send_json_success($results);
        } catch (Exception $e) {
            wp_send_json_error('Import error: ' . $e->getMessage());
        } catch (Error $e) {
            wp_send_json_error('Import error: ' . $e->getMessage());
        }
    }
}

if (is_admin()) {
    new YoutubeStore_Import_Admin();
}
