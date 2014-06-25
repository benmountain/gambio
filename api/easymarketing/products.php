<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   
   @modified_by Easymarketing AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/products.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

chdir('../../');
require_once('includes/application_top.php');

// include easymarketing configuration
require_once(DIR_FS_CATALOG.'api/easymarketing/includes/config.php');

// include easymarketing authentification
require_once('includes/auth.php');

// include easymarketing functions
require_once('includes/functions.php');

// needed functions
require_once (DIR_FS_INC.'xtc_get_tax_rate.inc.php');
require_once (DIR_WS_CLASSES.'order.php');
require_once (DIR_WS_CLASSES.'xtcPrice.php');
require_once (DIR_WS_CLASSES.'shipping.php');

// set request parameters products
$offset = (isset($_GET['offset']) ? (int) $_GET['offset'] : NULL);
$limit = (isset($_GET['limit']) ? (int) $_GET['limit'] : NULL);
$id = (isset($_GET['id']) ? (int) $_GET['id'] : NULL);
$newer_than = (isset($_GET['newer_than']) ? (int) $_GET['newer_than'] : NULL);

// set sql limit
$sql_limit = '';
$sql_sort = '';
if (isset($offset) && isset($limit)) 
{
	$sql_limit = " LIMIT ".(int) $offset.", ".(int) $limit;
    $sql_sort = " ORDER BY p.products_id ASC ";
} elseif (isset($limit)) {
    $sql_limit = " LIMIT ".(int) $limit;
}

// set sql where
$sql_where = '';
if (isset($id)) 
{
    $sql_where = " AND p.products_id = '".$id."' ";
} elseif (isset($newer_than)) {
    $sql_where = " AND p.products_date_added >= '".date("Y-m-d H:i:s", $newer_than)."' ";
    $sql_sort  = " ORDER BY p.products_id ASC ";
}

// process request
if ($sql_limit != '' || $sql_where != '') 
{
  	// init price class
  	$xtPrice = new xtcPrice(DEFAULT_CURRENCY, DEFAULT_CUSTOMERS_STATUS_ID);
  
  	// init order class
  	$order = new order();

  	//Data for shipping cost
  	$default_data_query_raw = "SELECT countries_id,
                                    countries_name,
                                    countries_iso_code_2,
                                    countries_iso_code_3,
                                    address_format_id
                               FROM ". TABLE_COUNTRIES ."
                              WHERE countries_iso_code_2 = '".strtoupper($language->language['code'])."'";
  	$default_data_query = xtc_db_query($default_data_query_raw);
  	$default_data = xtc_db_fetch_array($default_data_query);
  	$default_data['entry_postcode'] = '10000';
  	$default_data['zone_name'] = '';
  	$default_data['zone_id'] = '';

  	// set customer data
  	$order->customer = array('postcode' => $default_data['entry_postcode'],
                           'state' => $default_data['zone_name'],
                           'zone_id' => $default_data['zone_id'],
                           'format_id' => $default_data['address_format_id'],
                           'country' => array('id' => $default_data['countries_id'],
                                              'title' => $default_data['countries_name'],
                                              'iso_code_2' => $default_data['countries_iso_code_2'],
                                              'iso_code_3' => $default_data['countries_iso_code_3']
                                              ),
                            );
  	// set delivery data
  	$order->delivery = array('postcode' => $default_data['entry_postcode'],
                           'state' => $default_data['zone_name'],
                           'zone_id' => $default_data['zone_id'],
                           'format_id' => $default_data['address_format_id'],
                           'country' => array('id' => $default_data['countries_id'],
                                              'title' => $default_data['countries_name'],
                                              'iso_code_2' => $default_data['countries_iso_code_2'],
                                              'iso_code_3' => $default_data['countries_iso_code_3']
                                              ),
                            );

  	// set session for calculation shipping costs
  	$_SESSION['delivery_zone'] = $order->delivery['country']['iso_code_2'];
  
  	// include language definitions
  	include_once (DIR_WS_LANGUAGES.$language->language['directory'].'/modules/order_total/ot_shipping.php');
  
  	// init shipping class
  	$shipping = new shipping();
	
	$db_tables = mod_get_all_db_tables();
	
	if(MODULE_EASYMARKETING_ROOT_CATEGORY > 0)
	{
		$sql_join_ptc = "JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." ptc ON ptc.products_id = p.products_id AND ptc.categories_id = '".(int)MODULE_EASYMARKETING_ROOT_CATEGORY."'";
	}

  	// sql query for products
  	$products_query_raw = "SELECT p.*,
                                pd.products_name,
                                pd.products_description,
                                pd.products_short_description
                           FROM ".TABLE_PRODUCTS." p 
                           JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                ON p.products_id = pd.products_id
                                   AND pd.language_id = '".(int)$language->language['id']."'
						   ".$sql_join_ptc."
                          WHERE p.products_status = '1'
                                ".$sql_where."
						  GROUP BY p.products_id
                                ".$sql_sort."
                                ".$sql_limit;
  
  	// make sql query
  	$products_query = xtc_db_query($products_query_raw);
  
  	// check for result
  	if (xtc_db_num_rows($products_query) > 0) 
	{
    	// init products array
    	$products_array = array();
    
    	while ($products = xtc_db_fetch_array($products_query)) 
		{
      		// set some variables
      		$total_weight = $products['products_weight'];
      		$products_price = $xtPrice->xtcGetPrice($products['products_id'], false, 1, $products['products_tax_class_id'], '');

      		// reset cart
      		$_SESSION['cart']->reset(true);
      		$_SESSION['cart']->add_cart($products['products_id'], 1, '', false);
      		$_SESSION['cart']->weight = $total_weight;
      		$_SESSION['cart']->total = $products_price;
      
      		// clean description
      		$products['products_short_description'] = mod_convert($products['products_short_description']);
      		$products['products_description'] = mod_convert($products['products_description']);
	  
	  		$products_price = $xtPrice->xtcGetPrice($products['products_id'], false, 1, $products['products_tax_class_id'], '');
	  		$products_rrp =  $xtPrice->xtcFormat($products['products_price'], false, $products['products_tax_class_id'], false);
	  
	  		if($products_price != $products_rrp)
	  		{
		  		$discount_absolute = $products_rrp - $products_price;
		  		$discount_percentage = round($discount_absolute / $products_rrp * 100, 0);
	  		}
			
			$products_item_codes = mod_get_products_item_codes($products['products_id']);
			$condition = !empty($products_item_codes['google_export_condition']) ? $products_item_codes['google_export_condition'] : MODULE_EASYMARKETING_CONDITION_DEFAULT;
      
      		// build products array
      		$products_array[] = array(
										'id' => $products['products_id'],
                                		'name' => mod_convert($products['products_name']),
                                		'categories' => mod_get_categories_array($products['products_id']),
										'google_category' => mod_get_google_category($products['products_id']),
                                		'condition' => mod_get_condition($condition),
                                		'availability' => ($products['products_quantity'] > 0) ? MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1 : MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0,
                                		'currency' => DEFAULT_CURRENCY,
                                		'price' => $products_price,
										'rrp' => $products_rrp,
										'discount_absolute' => ($discount_absolute > 0) ? $discount_absolute : 0,
										'discount_percentage' => ($discount_percentage > 0) ? $discount_percentage : 0,
                                		'url' => xtc_href_link(FILENAME_PRODUCT_INFO, xtc_product_link($products['products_id'], $products['products_name']), 'NONSSL', false),
                                		'image_url' => !empty($products['products_image']) ? HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_POPUP_IMAGES.$products['products_image'] : 'null',
                                		'shipping' => mod_calculate_shipping_cost($products['products_id'], $products_price),
                                		'description' => (!empty($products['products_short_description']) ? $products['products_short_description'] : (!empty($products['products_description']) ? $products['products_description'] : 'null')),
                                		'gtin' => ($products['products_ean'] != '') ? $products['products_ean'] : 'null',
                                		'adult' => ($products['products_fsk18'] == '1') ? true : false,
										'mpn' => $products_item_codes['code_mpn'],
										'brand' => mod_convert($products_item_codes['brand_name'])
                                	);                       
    	}

    	// init response array
    	$response = array();
    
    	// normal products
    	if (isset($offset)) 
		{
      		$response['offset'] = $offset;
    	}
    
    	// new products
    	if (isset($newer_than)) 
		{
      		$response['time'] = $newer_than;
      		$response['newer_than'] = $limit;
    	}
    
    	// add products
    	$response['products'] = $products_array;
    
    	// output products
    	mod_stream_response($response);
  	}
}
