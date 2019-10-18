<?php

function epp_parser_install() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'epp_parser';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "
        CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		url tinytext NOT NULL,
		regex text NOT NULL,
		price tinytext NOT NULL,
		date tinytext NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	if (!wp_next_scheduled( 'check_prices_product_parser' )) {
		//hourly - 3600 seconds
		//twicedaily - 43200 seconds
		//daily - 86400 seconds
		wp_schedule_event(time(), 'daily', 'check_prices_product_parser');
	}
}

function epp_parser_deactivate() {
	wp_clear_scheduled_hook('check_prices_product_parser');
}

function epp_parser_uninstall() {
	global $wpdb;
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}epp_parser");
	wp_clear_scheduled_hook('check_prices_product_parser');
}

function register_prices_product_parser() {
    $parser = new Parser;
	$parser->parse_products();
}
add_action('check_prices_product_parser', 'register_prices_product_parser');