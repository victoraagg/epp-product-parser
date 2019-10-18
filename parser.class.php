<?php
class Parser {

	public function curl_get_contents($url) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36');
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
	}

	public function get_price($url, $pattern) {
		$data = $this->curl_get_contents($url);
		preg_match_all($pattern, $data, $prices, PREG_PATTERN_ORDER);
		return $prices;
	}

	public function add_price($name, $url, $regex) {
		global $wpdb;
		$wpdb->insert( 
			$wpdb->prefix . 'epp_parser', 
			array( 
				'name' => $name, 
				'url' => $url, 
				'regex' => $regex, 
			) 
		);
	}

	public function update_price($price, $id) {
		global $wpdb;
		$wpdb->update( 
			$wpdb->prefix . 'epp_parser', 
			array( 
				'price' => $price,
				'date' => date('Y-m-d H:i:s')
			), 
			array( 'ID' => $id ), 
			array( 
				'%s',
				'%s'
			), 
			array('%d') 
		);
	}
	
	public function get_all_prices() {
		global $wpdb;
		$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}epp_parser WHERE 1", ARRAY_A );
		return $results;
	}

	public function parse_products() {
		//set_time_limit(900);
		//error_reporting(E_ALL);
		//ini_set('display_errors', true);
		$pricesData = $this->get_all_prices();
		foreach ($pricesData as $priceData) {
			$newPrice = $this->get_price($priceData['url'], $priceData['regex']);
			if(!empty($newPrice[0])){
				$price = strip_tags($newPrice[0][0]);
				$this->update_price($price, $priceData['id']);
			}
		}
	}
		
}