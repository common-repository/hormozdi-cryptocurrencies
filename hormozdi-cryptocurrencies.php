<?php

/**
 * @link                  https://hormozdi.ir
 * @since                 1.0.0
 * @package               Hormozdi_Cryptocurrencies
 *
 * @wordpress-plugin
 * Plugin Name:           Hormozdi Cryptocurrencies
 * Plugin URI:            http://hormozdi.ir/hcrypto
 * Description:           Show and convert Cryptocurrencies price to Iran’s currency (IRR). for show Cryptocurrencies table use [hcc_table_price] shortcode.
 * Version:               1.0.8
 * Author:                Hormozdi
 * Author URI:            http://hormozdi.ir/
 * Text Domain:           hormozdi-cryptocurrencies
 * Domain Path:           /languages
 * 
 * Copyright:             © 2018-2019 Alopeyk.
 * License:               GNU General Public License v3.0
 * License URI:           http://www.gnu.org/licenses/gpl-3.0.html
 */
 
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once dirname( __FILE__ ) . '/vendor/autoload.php';
use \Curl\Curl;

class Hormozdi_Cryptocurrencies {
	
	protected $curl;
	
	public function __construct() {
		add_shortcode( 'hcc_table_price', array( $this, 'hcc_table_price_func' ) );
		$this->curl = new Curl();
		$this->curl->setHeader( 'X-CMC_PRO_API_KEY', '123456' );
	}
	
	public function hcc_table_price_func(){
		$this->curl->get( 'https://api.hormozdi.ir/production/listings/latest' );
		if ($this->curl->error) {
			echo 'Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage . "\n";
		} else {
			$return_string = '';
			ob_start();
		?>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>نام ارز</th>
						<th>قیمت به دلار</th>
						<th>قیمت به تومان</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$response = json_decode($this->curl->response);
						$dollar   = $response->dollar;
					?>
					<?php $i = 1; foreach( $response->data as $data ): ?>
						<tr>
							<th scope="row"><?=$i?></th>
							<td><?=$data->name?></td>
							<td><?=round( $data->quote->USD->price, 4 )?></td>
							<td><?=round( $data->quote->USD->price * $dollar )?></td>
						</tr>
					<?php $i++; endforeach; ?>
				</tbody>
			</table>
		<?php
			$return_string .= ob_get_clean();
			return $return_string;
		}
	}
	
}
new Hormozdi_Cryptocurrencies;








