<?php
/**
 * bb functions and definitions
 *
 * @package bb
 */

if ( ! defined( 'WPINC' ) )
	die();

define('KND_VERSION', '1.3.1');
define('KND_DOC_URL', 'https://github.com/Teplitsa/kandinsky/wiki/');
define('KND_OFFICIAL_WEBSITE_URL', 'https://knd.te-st.ru/');
define('KND_SOURCES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/');
define('KND_SOURCES_ISSUES_PAGE_URL', 'https://github.com/Teplitsa/kandinsky/issues/');
define('TST_OFFICIAL_WEBSITE_URL', 'https://te-st.ru/');
define('TST_PASEKA_OFFICIAL_WEBSITE_URL', 'https://paseka.te-st.ru/');
define('KND_SUPPORT_EMAIL', 'support@te-st.ru');
define('KND_SUPPORT_TELEGRAM', 'https://t.me/joinchat/AAAAAENN3prSrvAs7KwWrg');
define('KND_SETUP_WIZARD_URL', admin_url('themes.php?page=knd-setup-wizard'));
define('KND_DISTR_ARCHIVE_URL', 'https://github.com/Teplitsa/kandinsky/archive/master.zip');
#define('KND_DISTR_ARCHIVE_URL', 'https://github.com/Teplitsa/kandinsky/archive/dev.zip');
define('KND_MIN_PHP_VERSION', '5.6.0');
define('KND_PHP_VERSION_ERROR_MESSAGE', '<strong>Внимание:</strong> версия PHP ниже <strong>5.6.0</strong>. Кандинский нуждается в PHP хотя бы <strong>версии 5.6.0</strong>, чтобы работать корректно.<br /><br />Пожалуйста, направьте вашему хостинг-провайдеру запрос на повышение версии PHP для этого сайта.');

if( !defined('PHP_VERSION') || version_compare(PHP_VERSION, KND_MIN_PHP_VERSION, '<') ) {

  function sample_admin_notice__success() {
    ?>
    <div class="notice notice-error">
        <p><?php echo KND_PHP_VERSION_ERROR_MESSAGE;?></p>
    </div>
    <?php
  }
  add_action( 'admin_notices', 'sample_admin_notice__success' );
}

if( !isset($content_width) ) {
	$content_width = 800; /* pixels */
}

function knd_setup() {

	// Inits
	load_theme_textdomain('knd', get_template_directory().'/lang');
	//add_theme_support( 'automatic-feed-links' );	
	add_theme_support('title-tag');
	add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption',));

	

	// Menus
	$menus = array(
		'primary'   => __('Primary menu', 'knd'),
		//'social'    => 'Социальные кнопки',
		//'sitemap'   => 'Карта сайта'
	);

	register_nav_menus($menus);

	// Editor style
    add_editor_style(array('assets/css/editor.css'));

    // Support automatic feed links
	add_theme_support( 'automatic-feed-links' );

}
add_action( 'after_setup_theme', 'knd_setup', 9 ); // Theme wizard initialize at 10, this init should occur befure it

/* Function for init setting that should be runned at init hook */
function knd_content_init() {
    add_post_type_support('page', 'excerpt');
}
add_action( 'init', 'knd_content_init', 30 );

/**
 * Includes
 */

// WP libs to operate with files and media
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// enqueue CSS and JS and compose inline CSS to set vars from settings

get_template_part('/core/class-cssjs');

get_template_part('/core/media'); // customize media behavior and add images sizes

get_template_part('/core/cards'); // layout of cards, list items etc.

get_template_part('/core/extras'); // default WP behavior customization

get_template_part('/core/shortcodes'); // shortcodes core
get_template_part('/core/shortcodes-ui'); // shortcodes layout

get_template_part('/core/template-tags'); // independent pages parts layout

get_template_part('/core/widgets'); // setup widgets
get_template_part('/core/customizer'); // WP theme customizer setup

// import data utils
get_template_part('/core/class-mediamnt'); // tools for work with files

get_template_part('/core/class-import'); // import files into site media lib
get_template_part('/core/import'); // import files into site media lib

// Include modules
foreach (glob(get_template_directory() . '/modules/*') as $module_file) {
    if(is_dir($module_file)) {
        $php_filename = basename($module_file) . '.php';
        $php_file = $module_file . '/' . $php_filename;
    } else {
        $php_file = $module_file;
    }

    if(is_file($php_file) && preg_match('/.*\.php$/', $php_file)) {
        require( $php_file );
    }
}

if(is_admin() || current_user_can('manage_options')) {
    get_template_part('/core/admin-update-theme');
    get_template_part('/core/admin');
    get_template_part('/vendor/class-tgm-plugin-activation');
}

if((is_admin() && !empty($_GET['page']) && $_GET['page'] == 'knd-setup-wizard' ) || wp_doing_ajax()) {
    get_template_part('/vendor/envato_setup/envato_setup'); // Run the wizard after all modules included
}

// Service lines (to localize):
__('Kandinsky', 'knd');
__('Teplitsa', 'knd');
__('The beautiful design and useful features for nonprofit website', 'knd');

# ikruglov
# https://geekflare.com/wordpress-performance-optimization-without-plugin/

## Remove RSD Links
remove_action( 'wp_head', 'rsd_link' ) ;

## Disable Emoticons
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

## Remove Shortlink
remove_action('wp_head', 'wp_shortlink_wp_head');

## Disable Embeds
function disable_embed(){
    wp_dequeue_script( 'wp-embed' );
}

add_action( 'wp_footer', 'disable_embed' );

# Hide WordPress Version
remove_action( 'wp_head', 'wp_generator' ) ;

# Remove WLManifest Link
remove_action( 'wp_head', 'wlwmanifest_link' ) ;

## Disable Self Pingback
function disable_pingback( &$links ) {
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}

add_action( 'pre_ping', 'disable_pingback' );

# https://crunchify.com/how-to-clean-up-wordpress-header-section-without-any-plugin/

## Remove api.w.org relation link
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);


# Debugging wp_mail
#add_action('wp_mail_failed', 'action_wp_mail_failed', 10, 1);
#function action_wp_mail_failed($wp_error) {
#    return error_log(print_r($wp_error, true));
#}

###########################
# this function is an attempt to modify logic of leyka_revo_template_campaign_page
# by removing leyka_inline_campaign_small() which adds a small donation form after post content.

add_action('wp_head', 'remove_leyka_revo_template_campaign_page', 99);
function remove_leyka_revo_template_campaign_page() {
    remove_filter('the_content', 'leyka_revo_template_campaign_page', 10);

    if (!leyka_options()->opt_template('do_not_display_donation_form')) {
        add_filter('the_content', 'leyka_revo_template_campaign_page_without_small_form');
    }
}

# almost one-to-one copy from leyka_revo_template_campaign_page
function leyka_revo_template_campaign_page_without_small_form($content) {
    if( !is_singular(Leyka_Campaign_Management::$post_type) ) {
        return $content;
    }

    $campaign_id = get_queried_object_id();

    $before = leyka_inline_campaign(array('id' => $campaign_id, 'template' => 'revo'));
    $after = ''; #leyka_inline_campaign_small($campaign_id);

    return $before.$content.$after;
}

###########################
# this logic add ability to not show leyka's engagement banner button
add_filter('leyka_engb_banner_template', 'knd_leyka_engb_banner_template', 20);
function knd_leyka_engb_banner_template($template) {
    return get_template_directory() . '/templates/template-banner.php';
}
