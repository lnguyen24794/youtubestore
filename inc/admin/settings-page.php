<?php
/**
 * Admin Settings Page for Google Sheet Sync
 */

if (!defined('ABSPATH')) {
    exit;
}

class YoutubeStore_Admin_Settings
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function enqueue_admin_scripts($hook)
    {
        if ('settings_page_youtubestore-settings' !== $hook) {
            return;
        }
        wp_enqueue_script('youtubestore-admin-script', get_template_directory_uri() . '/inc/admin/admin-script.js', array('jquery'), '1.0', true);
    }

    public function add_plugin_page()
    {
        add_options_page(
            'Youtube Store Settings',
            'Youtube Store',
            'manage_options',
            'youtubestore-settings',
            array($this, 'create_admin_page')
        );
    }

    public function create_admin_page()
    {
        ?>
        <div class="wrap">
            <h1>Youtube Store Configuration</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('youtubestore_option_group');
                do_settings_sections('youtubestore-settings');
                submit_button();
                ?>
            </form>

            <hr>

            <h2>Sync Data</h2>
            <p>Click below to manually sync data from Google Sheet.</p>
            <button id="youtubestore-manual-sync" class="button button-primary">Sync Now</button>
            <div id="youtubestore-sync-status" style="margin-top: 10px;"></div>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting(
            'youtubestore_option_group',
            'youtubestore_sheet_id',
            array($this, 'sanitize')
        );

        register_setting(
            'youtubestore_option_group',
            'youtubestore_tab_name',
            array($this, 'sanitize')
        );

        add_settings_section(
            'youtubestore_setting_section',
            'Google Sheet Settings',
            array($this, 'section_info'),
            'youtubestore-settings'
        );

        add_settings_field(
            'youtubestore_sheet_id',
            'Spreadsheet ID',
            array($this, 'sheet_id_callback'),
            'youtubestore-settings',
            'youtubestore_setting_section'
        );

        add_settings_field(
            'youtubestore_tab_name',
            'Tab Name (GID or Name)',
            array($this, 'tab_name_callback'),
            'youtubestore-settings',
            'youtubestore_setting_section'
        );
    }

    public function sanitize($input)
    {
        if (isset($input)) {
            return sanitize_text_field($input);
        }
        return '';
    }

    public function section_info()
    {
        echo 'Enter your Google Sheet details below. Make sure the sheet is "Published to the web" as CSV/JSON.';
    }

    public function sheet_id_callback()
    {
        $val = get_option('youtubestore_sheet_id');
        printf(
            '<input type="text" id="youtubestore_sheet_id" name="youtubestore_sheet_id" value="%s" class="regular-text" />',
            !empty($val) ? esc_attr($val) : ''
        );
    }

    public function tab_name_callback()
    {
        $val = get_option('youtubestore_tab_name');
        printf(
            '<input type="text" id="youtubestore_tab_name" name="youtubestore_tab_name" value="%s" class="regular-text" description="E.g. Sheet1 or 0" />',
            !empty($val) ? esc_attr($val) : ''
        );
    }
}

if (is_admin())
    $youtubestore_settings = new YoutubeStore_Admin_Settings();
