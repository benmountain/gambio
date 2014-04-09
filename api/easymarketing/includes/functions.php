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

   @file       api/easymarketing/includes/functions.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
  die('Direct Access to this location is not allowed.');
}

function mod_get_all_db_tables()
{
	$tables = array();
	
	$tables_query_raw = "SHOW TABLES FROM " . DB_DATABASE;
	
	$tables_query = xtc_db_query($tables_query_raw);
	
	while($tables_row = xtc_db_fetch_array($tables_query))
	{
		$tables[] = $tables_row['Tables_in_' . DB_DATABASE];
	}
	
	return $tables;
}

function mod_convert($string) 
{
	global $language;
	
  	// convert string
  	$string = html_entity_decode($string, ENT_COMPAT, $language->language['language_charset']);
  	$string = strip_tags($string);
  	$string = str_replace(array("\r", "\n", "\t"), " ", $string);
  	$string = trim(preg_replace("/\s+/i", " ", $string));
	
  	if ($string == chr(160)) 
	{
    	$string = '';
  	}
  	$string = utf8_encode($string);
    
  	return $string;
}

function mod_get_categories_array($products_id) 
{
  	// init categories array
  	$categories_array = array();
  
  	// sql query for categories
  	$categories_query_raw = "SELECT categories_id
                             FROM ".TABLE_PRODUCTS_TO_CATEGORIES."
                            WHERE products_id = '".$products_id."'";
  
  	// make sql query
  	$categories_query = xtc_db_query($categories_query_raw);
  
  	while ($categories = xtc_db_fetch_array($categories_query)) 
	{
     	// build categories array
    	$categories_array[] = $categories['categories_id'];
  	}
  
  	return $categories_array;
}

function mod_get_sub_categories($categories_id) 
{
	$subcategories_array = array();
  	
	// sql query for subcategories
 	$subcategories_query_raw = "SELECT categories_id
                                FROM ".TABLE_CATEGORIES."
                               WHERE parent_id = '".$categories_id."'";
  
  	// make sql query
  	$subcategories_query = xtc_db_query($subcategories_query_raw);
  
  	// check for result
  	if (xtc_db_num_rows($subcategories_query) > 0) 
	{
    	// init subcategories array
    	$subcategories_array = array();
    
    	while ($subcategories = xtc_db_fetch_array($subcategories_query)) 
		{
      		// build subcategories array
      		$subcategories_array[] = $subcategories['categories_id'];
    	}
	}
      
    // return comma separated list
    return $subcategories_array;
}

function mod_get_google_category($products_id)
{
	global $db_tables;
	
	$google_category = '';
	
	if(!in_array('products_google_categories', $db_tables))
	{
		return $google_category;
	}
	
	// sql query for google category of a product
	$google_category_query_raw = "SELECT google_category FROM products_google_categories WHERE products_id = '".$products_id."' ORDER BY products_google_categories_id LIMIT 1";
	
	// make sql query
	$google_category_query = xtc_db_query($google_category_query_raw);

	// check for result
	if (xtc_db_num_rows($google_category_query) > 0)
	{
		$_google_category = xtc_db_fetch_array($google_category_query);
		$google_category = $_google_category['google_category'];
	}
	
	// return google category
	return mod_convert($google_category);
}

function mod_get_condition($condition)
{
	$_condition = '';
	
	switch($condition)
	{
		case 'neu':
			$_condition = 'new';
			break;
		case 'gebraucht':
			$_condition = 'used';
			break;
		case 'erneuert':
			$_condition = 'refurbished';
			break;
		default:
			$_condition = MODULE_EASYMARKETING_CONDITION_DEFAULT;
			break;
	}
	
	return $_condition;
}

function mod_get_products_item_codes($products_id)
{
	global $db_tables;
	
	if(!in_array('products_item_codes', $db_tables))
	{
		return array();
	}
	
	// sql query for products item codes
	$products_item_codes_query_raw = "SELECT * FROM products_item_codes WHERE products_id = '".$products_id."' LIMIT 1";
	
	// make sql query
	$products_item_codes_query = xtc_db_query($products_item_codes_query_raw);

	// check for result
	if (xtc_db_num_rows($products_item_codes_query) > 0)
	{
		// return result
		return xtc_db_fetch_array($products_item_codes_query);
	}
	
	// return an empty array, if result is empty
	return array();
}

function mod_calculate_shipping_cost($products_id, $products_price) 
{
  	// set globals
  	global $xtPrice, $order, $shipping, $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;
  
  	// init shipping modules
  	$quotes = $shipping->quote();

	$free_shipping = false;
	  
	if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true')) 
	{
    	switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) 
		{
      		case 'national':
        		if ($order->delivery['country']['id'] == STORE_COUNTRY)
            	$pass = true;
        		break;
      		case 'international':
        		if ($order->delivery['country']['id'] != STORE_COUNTRY)
            	$pass = true;
        		break;
      		case 'both':
        		$pass = true;
        		break;
      		default:
        	$pass = false;
        	break;
    	}
		
    	if (($pass == true) && ($products_price >= $xtPrice->xtcFormat(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER, false, 0, true))) 
		{
      		$free_shipping = true;
    	}
  	}

  	$has_freeamount = false;
  	$free_shipping_freeamount = false;
	
  	foreach ($quotes as $quote) 
  	{
      	if ($quote['id'] == 'freeamount') 
		{
          	$has_freeamount = true;
          	if (isset($quote['methods'])) 
			{
              	$free_shipping_freeamount = true;
              	break;
          	}
      	}
  	}
  
  	// init shipping content array
  	$shipping_content = array ();
  
  	if ($free_shipping == true) 
	{
    	$shipping_content[] = array(
									'country' => $order->delivery['country']['iso_code_2'],
                                	'service' => mod_convert(FREE_SHIPPING_TITLE),
                                	'price' => floatval(0)
                               		);
  	} elseif ($free_shipping_freeamount) {
    	$shipping_content[] = array(
									'country' => $order->delivery['country']['iso_code_2'],
                                	'service' => mod_convert($quote['module']),
                                	'price' => floatval(0)
                                	);
  	} else {
    
    	foreach ($quotes AS $quote) 
		{
      		if ($quote['id'] != 'freeamount') 
	  		{
        		$quote['methods'][0]['cost'] = $xtPrice->xtcCalculateCurr($quote['methods'][0]['cost']);
        		$value = ((isset($quote['tax']) && $quote['tax'] > 0) ? $xtPrice->xtcAddTax($quote['methods'][0]['cost'],$quote['tax']) : (!empty($quote['methods'][0]['cost']) ? $quote['methods'][0]['cost'] : '0'));
        		$value = $xtPrice->xtcFormat($value, false);
        		$shipping_content[] = array(
											'country' => $order->delivery['country']['iso_code_2'],
                                    		'service' => mod_convert($quote['module'] . (!empty($quote['methods'][0]['title']) ? ' - '.$quote['methods'][0]['title'] : '')), 
                                    		'price' => floatval($value),
                                    		);
      		}
    	}
  	}
  
  	// unset used variables and objects
  	unset($quotes);
  	unset($shipping);
  	unset($order);
  	unset($_SESSION['delivery_zone']);
	unset($_SESSION['shipping']);

  	// return cheapest Shipping module
  	return $shipping_content;
}

function mod_stream_response($response) 
{
  	if (defined('MODULE_EASYMARKETING_DEBUG') && MODULE_EASYMARKETING_DEBUG === true) 
  	{
    	// print out formatted array
    	echo '<pre>'.print_r($response, true).'</pre>';  
  	} else {
    	// output json header
    	header('Content-type: application/json');
  
    	// output json response
    	echo json_encode($response);  
  	}
}
