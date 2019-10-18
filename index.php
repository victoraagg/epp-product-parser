<?php
/**
 * @wordpress-plugin
 * Plugin Name: Ecommerce Product Parser
 * Plugin URI: https://www.bthebrand.es
 * Description: Scrape products from URL
 * Version: 2.0.0
 * Author: Víctor Alonso
 * Author URI: https://www.bthebrand.es
 * License: GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 * Text Domain: epp
 */

$plugin_folder = plugin_dir_path(__FILE__);

include_once($plugin_folder.'/setup.php');
include_once($plugin_folder.'/parser.class.php');

register_activation_hook( __FILE__, 'epp_parser_install' );
register_deactivation_hook(__FILE__, 'epp_parser_deactivate');
register_uninstall_hook(__FILE__, 'epp_parser_uninstall');

add_action( 'admin_menu', 'epp_register_product_parser_page' );
function epp_register_product_parser_page() {
    add_menu_page(
        __( 'Analizador', 'epp' ),
        __( 'Analizador', 'epp' ),
        'manage_woocommerce',
        'epp-scrape',
        'epp_scrape_html_admin',
        get_template_directory_uri().'/assets/img/icon-menu.png',
        2
    );
}

function epp_scrape_html_admin() {
    $parser = new Parser;
    if (isset($_POST['parser_hidden']) && $_POST['parser_hidden'] == 'Y') {
        $parser->add_price($_POST['name-parser'],$_POST['url-parser'],$_POST['regex-parser']);
        echo '<div class="updated"><p><strong>'.__("Guardado", "epp").'</strong></p></div>';
    }
    $prices = $parser->get_all_prices();
    include(plugin_dir_path( __FILE__ ) . 'admin/scrape-admin-style.php');
    include(plugin_dir_path( __FILE__ ) . 'admin/scrape-admin.php');
}