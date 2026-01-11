<?php
/**
 * Admin Page for YouTube Channel Import
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Channel_Import_Admin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_import_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_post_youtubestore_import_channels', array($this, 'handle_import'));
    }

    public function add_import_page()
    {
        add_submenu_page(
            'edit.php?post_type=youtube_channel',
            'Import Channels',
            'Import Channels',
            'manage_options',
            'youtubestore-channel-import',
            array($this, 'render_import_page')
        );
    }

    public function enqueue_scripts($hook)
    {
        // Fix hook name - WordPress uses edit.php?post_type=youtube_channel&page=youtubestore-channel-import
        // The hook format is: {post_type}_page_{page_slug}
        if (strpos($hook, 'youtubestore-channel-import') === false) {
            return;
        }
        wp_enqueue_script('jquery');
    }

    public function render_import_page()
    {
        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }
        require_once YOUTUBESTORE_DIR . '/inc/import/class-youtube-channel-import.php';
        ?>
        <div class="wrap">
            <h1>Import YouTube Channels</h1>
            
            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>Upload CSV or Excel File</h2>
                <p>Upload a CSV or Excel file with the following columns:</p>
                <ul>
                    <li><strong>Lượng subscribers</strong> - Number of subscribers</li>
                    <li><strong>Link Kênh</strong> - YouTube channel URL</li>
                    <li><strong>Chủ Đề</strong> - Channel topic/category</li>
                    <li><strong>Giá bán (VND)</strong> - Price in VND</li>
                    <li><strong>Tình trạng kênh</strong> - Monetization status (Đã bật kiếm tiền / Chưa bật kiếm tiền)</li>
                    <li><strong>Mua hàng</strong> - Status (optional)</li>
                </ul>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" enctype="multipart/form-data" id="channel-import-form">
                    <?php wp_nonce_field('youtubestore_channel_import', 'import_nonce'); ?>
                    <input type="hidden" name="action" value="youtubestore_import_channels" />
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="import_file">Select File</label></th>
                            <td>
                                <input type="file" id="import_file" name="import_file" accept=".csv,.xlsx,.xls" required />
                                <p class="description">Supported formats: CSV, XLSX, XLS</p>
                                <p id="file-name" style="margin-top: 5px; color: #2271b1; font-weight: bold; display: none;"></p>
                            </td>
                        </tr>
                        <tr>
                            <th><label>Import Options</label></th>
                            <td>
                                <label>
                                    <input type="checkbox" name="update_existing" value="1" checked />
                                    Update existing channels (matched by URL)
                                </label><br>
                                <label>
                                    <input type="checkbox" name="skip_duplicates" value="1" checked />
                                    Skip duplicates if not updating
                                </label>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <input type="submit" name="submit" id="submit-import" class="button button-primary button-large" value="Import Channels" />
                        <span id="import-loading" style="display: none; margin-left: 10px; color: #2271b1;">⏳ Đang xử lý...</span>
                    </p>
                </form>
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Show selected file name
                    $('#import_file').on('change', function() {
                        var fileName = $(this).val().split('\\').pop();
                        if (fileName) {
                            $('#file-name').text('Selected: ' + fileName).show();
                        } else {
                            $('#file-name').hide();
                        }
                    });
                    
                    // Form submission handler
                    $('#channel-import-form').on('submit', function(e) {
                        var fileInput = $('#import_file')[0];
                        if (!fileInput.files || !fileInput.files[0]) {
                            alert('Vui lòng chọn file để import!');
                            e.preventDefault();
                            return false;
                        }
                        
                        var fileName = fileInput.files[0].name;
                        var fileExt = fileName.split('.').pop().toLowerCase();
                        if (!['csv', 'xlsx', 'xls'].includes(fileExt)) {
                            alert('File không đúng định dạng! Vui lòng chọn file CSV, XLSX hoặc XLS.');
                            e.preventDefault();
                            return false;
                        }
                        
                        if (!confirm('Bạn có chắc chắn muốn import file "' + fileName + '"?')) {
                            e.preventDefault();
                            return false;
                        }
                        
                        $('#submit-import').prop('disabled', true).val('Đang import...');
                        $('#import-loading').show();
                    });
                });
                </script>
            </div>

            <div class="card" style="max-width: 800px; margin-top: 20px;">
                <h2>Sample CSV Format</h2>
                <p>Download a sample CSV file to see the expected format:</p>
                <a href="<?php echo admin_url('admin-post.php?action=youtubestore_download_sample_csv'); ?>" class="button">Download Sample CSV</a>
            </div>

            <?php
            // Show import results if available
            if (isset($_GET['import_result'])) {
                $result = get_transient('youtubestore_import_result_' . $_GET['import_result']);
                if ($result) {
                    delete_transient('youtubestore_import_result_' . $_GET['import_result']);
                    ?>
                    <div class="notice notice-<?php echo $result['success'] ? 'success' : 'error'; ?>" style="margin-top: 20px;">
                        <h3>Import Results</h3>
                        <?php if ($result['success']): ?>
                            <ul>
                                <li><strong>Imported:</strong> <?php echo $result['imported']; ?> channels</li>
                                <li><strong>Updated:</strong> <?php echo $result['updated']; ?> channels</li>
                                <li><strong>Skipped:</strong> <?php echo $result['skipped']; ?> channels</li>
                                <li><strong>Total processed:</strong> <?php echo $result['total']; ?> rows</li>
                            </ul>
                            <?php if (!empty($result['errors'])): ?>
                                <h4>Errors:</h4>
                                <ul>
                                    <?php foreach ($result['errors'] as $error): ?>
                                        <li><?php echo esc_html($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        <?php else: ?>
                            <p><?php echo esc_html($result['message']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }

    public function handle_import()
    {
        check_admin_referer('youtubestore_channel_import', 'import_nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Permission denied');
        }

        if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
            $error_msg = 'No file uploaded';
            if (isset($_FILES['import_file']['error'])) {
                $error_msg .= ' (Error code: ' . $_FILES['import_file']['error'] . ')';
            }
            $this->redirect_with_error($error_msg);
            return;
        }

        $file = $_FILES['import_file'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, array('csv', 'xlsx', 'xls'))) {
            $this->redirect_with_error('Invalid file format. Please upload CSV or Excel file.');
            return;
        }

        // Move uploaded file
        $upload_dir = wp_upload_dir();
        if (!file_exists($upload_dir['path'])) {
            wp_mkdir_p($upload_dir['path']);
        }
        $upload_path = $upload_dir['path'] . '/import_' . time() . '_' . basename($file['name']);
        
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            $this->redirect_with_error('Failed to save uploaded file. Check upload directory permissions.');
            return;
        }

        if (!defined('YOUTUBESTORE_DIR')) {
            define('YOUTUBESTORE_DIR', get_template_directory());
        }
        require_once YOUTUBESTORE_DIR . '/inc/import/class-youtube-channel-import.php';
        $importer = new YoutubeStore_Channel_Import();

        // Parse file
        if ($file_ext === 'csv') {
            $channels = $importer->parse_csv($upload_path);
        } else {
            $channels = $importer->parse_excel($upload_path);
        }

        if (is_wp_error($channels)) {
            @unlink($upload_path);
            $this->redirect_with_error($channels->get_error_message());
            return;
        }

        // Import options
        $options = array(
            'update_existing' => isset($_POST['update_existing']),
            'skip_duplicates' => isset($_POST['skip_duplicates']),
        );

        // Import channels
        $result = $importer->import_channels($channels, $options);

        // Clean up
        @unlink($upload_path);

        // Store result and redirect
        $result_id = time();
        set_transient('youtubestore_import_result_' . $result_id, $result, 300);

        wp_redirect(admin_url('edit.php?post_type=youtube_channel&page=youtubestore-channel-import&import_result=' . $result_id));
        exit;
    }

    private function redirect_with_error($message)
    {
        $result_id = time();
        set_transient('youtubestore_import_result_' . $result_id, array(
            'success' => false,
            'message' => $message
        ), 300);

        wp_redirect(admin_url('edit.php?post_type=youtube_channel&page=youtubestore-channel-import&import_result=' . $result_id));
        exit;
    }
}

// Handle sample CSV download
add_action('admin_post_youtubestore_download_sample_csv', function() {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=sample-channels.csv');
    
    // Add BOM for UTF-8
    echo "\xEF\xBB\xBF";
    
    $fp = fopen('php://output', 'w');
    
    // Headers
    fputcsv($fp, array('Lượng subscribers', 'Link Kênh', 'Chủ Đề', 'Giá bán (VND)', 'Tình trạng kênh', 'Mua hàng'));
    
    // Sample data
    fputcsv($fp, array('18.000', 'https://www.youtube.com/@ydyd1234/featured', 'Âm nhạc', '10.000.000', 'Chưa bật kiếm tiền', ''));
    fputcsv($fp, array('2.800.000', 'https://www.youtube.com/channel/UC-E1JQSSrC9G7P6MtCMtbpQ', 'Ô tô', '222000000', 'Chưa bật kiếm tiền', ''));
    fputcsv($fp, array('490.000', 'https://www.youtube.com/@tiemcuanang', 'Idol Kpop', '180000000', 'Đã bật kiếm tiền', ''));
    
    fclose($fp);
    exit;
});

if (is_admin()) {
    new YoutubeStore_Channel_Import_Admin();
}
