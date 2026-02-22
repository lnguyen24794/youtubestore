<?php
/* Template Name: SEO Settings - Youtube Channels */
// This file is used as a placeholder template. 
// Assign this template to a Page so that the archive-youtube_channel.php
// can dynamically find it and use its SEO settings from SiteSEO/Yoast/RankMath.

// Redirect to the actual archive if someone tries to view this page directly
wp_redirect(get_post_type_archive_link('youtube_channel'));
exit;
