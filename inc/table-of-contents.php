<?php
/**
 * Table of Contents Functionality
 * Auto-generate table of contents for posts based on headings
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate table of contents from post content
 */
function youtubestore_generate_toc($content, $post_id = null)
{
    if (empty($content)) {
        return '';
    }

    // Check if TOC should be displayed
    $show_toc = get_post_meta($post_id, '_show_toc', true);
    if ($show_toc === 'no') {
        return $content;
    }

    // Extract headings (h2, h3, h4)
    preg_match_all('/<h([2-4])[^>]*>(.*?)<\/h[2-4]>/i', $content, $matches, PREG_SET_ORDER);

    if (empty($matches)) {
        return $content;
    }

    // Generate TOC HTML
    $toc_html = '<div class="youtubestore-toc-wrapper">';
    $toc_html .= '<div class="youtubestore-toc-header">';
    $toc_html .= '<h4 class="youtubestore-toc-title">';
    $toc_html .= '<span class="youtubestore-toc-icon">📑</span> ';
    $toc_html .= '<span>Danh mục bài viết</span>';
    $toc_html .= '<span class="youtubestore-toc-toggle">▼</span>';
    $toc_html .= '</h4>';
    $toc_html .= '</div>';
    $toc_html .= '<div class="youtubestore-toc-content">';
    $toc_html .= '<ul class="youtubestore-toc-list">';

    $current_h2 = null;
    $item_number = 1;
    $h3_counters = array(); // Track h3 counters per h2

    foreach ($matches as $index => $match) {
        $level = (int)$match[1];
        $heading_text = strip_tags($match[2]);
        $heading_id = 'toc-' . $post_id . '-' . $index;
        
        // Create anchor ID for heading
        $anchor = sanitize_title($heading_text);
        $heading_id = 'heading-' . $anchor;

        if ($level == 2) {
            // New main section
            if ($current_h2 !== null) {
                $toc_html .= '</ul></li>';
                // Reset h3 counter for new h2
                unset($h3_counters[$current_h2]);
            }
            $toc_html .= '<li class="youtubestore-toc-item youtubestore-toc-h2">';
            $toc_html .= '<a href="#' . esc_attr($heading_id) . '" class="youtubestore-toc-link">';
            // Check if heading already starts with a number (e.g., "1. Title" or "1 Title")
            // If it does, don't add number prefix
            $clean_text = trim($heading_text);
            if (preg_match('/^(\d+)[\.\)]\s*/', $clean_text, $num_match)) {
                // Heading already has a number, use it as-is
                $toc_html .= esc_html($heading_text);
            } else {
                // Add number prefix
                $toc_html .= '<span class="youtubestore-toc-number">' . $item_number . '.</span> ';
                $toc_html .= esc_html($heading_text);
            }
            $toc_html .= '</a>';
            $current_h2 = $item_number;
            $item_number++;
        } elseif ($level == 3 && $current_h2 !== null) {
            // Sub-item
            if (!isset($sub_items_started)) {
                $toc_html .= '<ul class="youtubestore-toc-sublist">';
                $sub_items_started = true;
            }
            // Count h3 items within current h2
            if (!isset($h3_counters[$current_h2])) {
                $h3_counters[$current_h2] = 0;
            }
            $h3_counters[$current_h2]++;
            
            $toc_html .= '<li class="youtubestore-toc-item youtubestore-toc-h3">';
            $toc_html .= '<a href="#' . esc_attr($heading_id) . '" class="youtubestore-toc-link">';
            // Check if heading already has a number
            $clean_text = trim($heading_text);
            if (preg_match('/^(\d+[\.\)]?\s*)/', $clean_text, $num_match)) {
                // Heading already has a number, use it as-is
                $toc_html .= esc_html($heading_text);
            } else {
                // Add number prefix
                $toc_html .= '<span class="youtubestore-toc-number">' . $current_h2 . '.' . $h3_counters[$current_h2] . '</span> ';
                $toc_html .= esc_html($heading_text);
            }
            $toc_html .= '</a></li>';
        } elseif ($level == 4 && $current_h2 !== null) {
            // Sub-sub-item
            if (!isset($sub_items_started)) {
                $toc_html .= '<ul class="youtubestore-toc-sublist">';
                $sub_items_started = true;
            }
            $toc_html .= '<li class="youtubestore-toc-item youtubestore-toc-h4">';
            $toc_html .= '<a href="#' . esc_attr($heading_id) . '" class="youtubestore-toc-link">';
            $toc_html .= esc_html($heading_text);
            $toc_html .= '</a></li>';
        }

        // Add ID to heading in content
        $content = preg_replace(
            '/' . preg_quote($match[0], '/') . '/',
            '<h' . $level . ' id="' . esc_attr($heading_id) . '">' . $heading_text . '</h' . $level . '>',
            $content,
            1
        );
    }

    // Close any open lists
    if (isset($sub_items_started)) {
        $toc_html .= '</ul>';
    }
    if ($current_h2 !== null) {
        $toc_html .= '</li>';
    }

    $toc_html .= '</ul>';
    $toc_html .= '</div>';
    $toc_html .= '</div>';

    // Insert TOC at the beginning of content (right after title)
    // This ensures TOC always appears below the title, regardless of content structure
    $content = $toc_html . $content;

    return $content;
}

/**
 * Filter post content to add TOC
 */
function youtubestore_add_toc_to_content($content)
{
    if (!is_singular('post')) {
        return $content;
    }

    global $post;
    return youtubestore_generate_toc($content, $post->ID);
}
add_filter('the_content', 'youtubestore_add_toc_to_content', 10);

/**
 * Add TOC toggle meta box
 */
function youtubestore_add_toc_meta_box()
{
    add_meta_box(
        'youtubestore_toc_meta',
        'Table of Contents',
        'youtubestore_toc_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'youtubestore_add_toc_meta_box');

function youtubestore_toc_meta_box_callback($post)
{
    wp_nonce_field('youtubestore_toc_meta_box', 'youtubestore_toc_meta_box_nonce');
    
    $show_toc = get_post_meta($post->ID, '_show_toc', true);
    if ($show_toc === '') {
        $show_toc = 'yes'; // Default to yes
    }
    ?>
    <p>
        <label>
            <input type="radio" name="show_toc" value="yes" <?php checked($show_toc, 'yes'); ?> />
            Show Table of Contents
        </label>
    </p>
    <p>
        <label>
            <input type="radio" name="show_toc" value="no" <?php checked($show_toc, 'no'); ?> />
            Hide Table of Contents
        </label>
    </p>
    <?php
}

function youtubestore_save_toc_meta_box($post_id)
{
    if (!isset($_POST['youtubestore_toc_meta_box_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['youtubestore_toc_meta_box_nonce'], 'youtubestore_toc_meta_box')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['show_toc'])) {
        update_post_meta($post_id, '_show_toc', sanitize_text_field($_POST['show_toc']));
    }
}
add_action('save_post', 'youtubestore_save_toc_meta_box');

/**
 * Enqueue TOC styles and scripts
 */
function youtubestore_toc_scripts()
{
    // Load TOC scripts on single posts and archive pages that might have TOC
    if (!is_singular('post') && !is_post_type_archive('youtube_channel')) {
        return;
    }
    ?>
    <style>
    .youtubestore-toc-wrapper {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin: 20px 0;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .youtubestore-toc-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e0e0e0;
        cursor: pointer;
        user-select: none;
    }
    .youtubestore-toc-title {
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .youtubestore-toc-icon {
        margin-right: 8px;
        font-size: 20px;
    }
    .youtubestore-toc-toggle {
        font-size: 12px;
        transition: transform 0.3s;
    }
    .youtubestore-toc-wrapper.collapsed .youtubestore-toc-toggle {
        transform: rotate(-90deg);
    }
    .youtubestore-toc-content {
        padding: 15px 20px;
        max-height: 600px;
        overflow-y: auto;
    }
    .youtubestore-toc-wrapper.collapsed .youtubestore-toc-content {
        display: none;
    }
    .youtubestore-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .youtubestore-toc-item {
        margin: 8px 0;
        list-style: none !important;
    }
    .youtubestore-toc-item.youtubestore-toc-h2 {
        font-weight: 600;
    }
    .youtubestore-toc-item.youtubestore-toc-h3 {
        padding-left: 20px;
        font-weight: 500;
    }
    .youtubestore-toc-item.youtubestore-toc-h4 {
        padding-left: 40px;
        font-weight: 400;
        font-size: 0.9em;
    }
    .youtubestore-toc-link {
        color: #333;
        text-decoration: none;
        display: block;
        padding: 5px 0;
        transition: color 0.2s;
    }
    .youtubestore-toc-link:hover {
        color: #2271b1;
    }
    .youtubestore-toc-number {
        margin-right: 5px;
        font-weight: bold;
    }
    .youtubestore-toc-sublist {
        list-style: none;
        padding-left: 20px;
        margin: 5px 0;
    }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $('.youtubestore-toc-header').on('click', function() {
            $(this).closest('.youtubestore-toc-wrapper').toggleClass('collapsed');
        });
        
        // Smooth scroll to anchor
        $('.youtubestore-toc-link').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 500);
            }
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'youtubestore_toc_scripts');
