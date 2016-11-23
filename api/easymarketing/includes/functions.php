<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul
   
   modified eCommerce Shopsoftware
   http://www.modified-shop.org
   Copyright (c) 2009 - 2014 [www.modified-shop.org]
   
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @modified_by Easymarketing AG, Florian Ressel <florian.ressel@easymarketing.de>

   @file       api/easymarketing/includes/functions.php
   @version    v3.1.1
   @updated    23.11.2016 - 13:41
   ---------------------------------------------------------------------------------------*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) {
  die('Direct Access to this location is not allowed.');
}

function mod_convert_encoding_single_string($string)
{
	if(function_exists('mb_detect_encoding'))
	{
		if(mb_detect_encoding($string, 'UTF-8', true) === 'UTF-8')
		{
			// do nothing
		} else {
			$string = mb_convert_encoding($string, 'UTF-8');
		}
	}
	
	return $string;
}

function mod_convert_string($string) 
{
	global $oLanguage;
	
	if(in_array(gettype($string), array('string', 'unknown type')))
	{
		// convert string
		$string = html_entity_decode($string, ENT_COMPAT, $oLanguage->language['language_charset']);
		$string = strip_tags($string);
		$string = str_replace(array("\r", "\n", "\t"), " ", $string);
		$string = trim(preg_replace("/\s+/i", " ", $string));
		
		if ($string == chr(160)) 
		{
			$string = '';
		}
	}
    
  	return trim($string);
}

function mod_convert_encoding_response($array)
{
	$t_array = array();
	
    foreach($array as $key => $value)
    {
        if(is_array($value))
        {
            $t_array[$key] = mod_convert_encoding_response($value);
        } else {
            $t_array[$key] = mod_convert_encoding_single_string($value);
        }
    }

    return $t_array;
}

function mod_is_empty($string)
{	
	// convert string
	$string = html_entity_decode($string, ENT_COMPAT, $oLanguage->language['language_charset']);
	$string = strip_tags($string);
	$string = str_replace(array("\r", "\n", "\t"), " ", $string);
	$string = trim(preg_replace("/\s+/i", " ", $string));
		
	if ($string == chr(160)) 
	{
		$string = '';
	}
	
	return empty($string);
}

function mod_get_description($product_descriptions)
{
	$t_description = 'null';
	
	if(!defined('MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT'))
	{
		define('MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT', 'products_description');
	}
	
	if(!mod_is_empty($product_descriptions[MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT]))
	{
		if(MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT == 'products_description')
		{
			$gmTabTokenizer = MainFactory::create_object('GMTabTokenizer', array(stripslashes($product_descriptions['products_description'])));
		
			if($gmTabTokenizer->get_tabs_count() > 0)
			{
				$t_description = $gmTabTokenizer->panel_content[0];
			} else {
				$t_description = $product_descriptions['products_description'];
			}
		} elseif(MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT == 'products_short_description') {
			$t_description = $product_descriptions['products_short_description'];
		}
		
	} else {
		if(MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT == 'products_description' && !mod_is_empty($products['products_short_description']))
		{
			$t_description = $product_descriptions['products_short_description'];
		} elseif(MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT == 'products_short_description' && !mod_is_empty($product_descriptions['products_description'])) {
			$gmTabTokenizer = MainFactory::create_object('GMTabTokenizer', array(stripslashes($product_descriptions['products_description'])));
		
			if($gmTabTokenizer->get_tabs_count() > 0)
			{
				$t_description = $gmTabTokenizer->panel_content[0];
			} else {
				$t_description = $product_descriptions['products_description'];
			}
		}	
	}
	
	return trim($t_description);
}

function mod_get_categories_array($products_id) 
{
  	// init categories array
  	$categories_array = array();
  
  	// make sql query
  	$categories_query_result = xtc_db_query("SELECT ptc.categories_id FROM ".TABLE_PRODUCTS_TO_CATEGORIES." ptc LEFT JOIN ".TABLE_CATEGORIES." c ON ptc.categories_id = c.categories_id WHERE ptc.products_id = '".$products_id."' AND c.categories_status = 1");
  
  	while ($categories = xtc_db_fetch_array($categories_query_result)) 
	{
     	// build categories array
    	$categories_array[] = $categories['categories_id'];
  	}
  
  	return $categories_array;
}

function mod_get_sub_categories($categories_id) 
{
	$subcategories_array = array();
  
  	// make sql query
  	$subcategories_query_result = xtc_db_query("SELECT categories_id FROM ".TABLE_CATEGORIES." WHERE parent_id = '".$categories_id."' AND categories_status = 1");
  
  	// check for result
  	if (xtc_db_num_rows($subcategories_query_result) > 0) 
	{
    	// init subcategories array
    	$subcategories_array = array();
    
    	while ($subcategories = xtc_db_fetch_array($subcategories_query_result)) 
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
	$google_category = '';
	
	// make sql query
	$google_category_query_result = xtc_db_query("SELECT google_category FROM products_google_categories WHERE products_id = '".$products_id."' ORDER BY products_google_categories_id LIMIT 1");

	// check for result
	if (xtc_db_num_rows($google_category_query_result) > 0)
	{
		$_google_category = xtc_db_fetch_array($google_category_query_result);
		$google_category = mod_convert_string($_google_category['google_category']);
	}
	
	// return google category
	return $google_category;
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
			$_condition = MODULE_EM_CONDITION_DEFAULT;
			break;
	}
	
	return $_condition;
}

function mod_get_availability($availability, $products_quantity)
{
	$_availability = 0;

	switch($availability)
	{
		case '1':
			$_availability = 'in stock';
			break;
		case '3':
			$_availability = 'not in stock';
			break;
		case '4':
			$_availability = 'preorder';
			break;
		default:
			$_availability = ($products_quantity > 0) ? MODULE_EM_AVAILABILITY_STOCK_1 : MODULE_EM_AVAILABILITY_STOCK_0;
			break;
	}
	
	return $_availability;
}

function mod_get_gender($gender)
{
	$_gender = '';
	
	switch($gender)
	{
		case 'Herren':
			$_gender = 'Male';
			break;
		case 'Damen':
			$_gender = 'Female';
			break;
		case 'Unisex':
			$_gender = 'Unisex';
			break;
		default:
			$_gender = (MODULE_EM_GENDER_DEFAULT != 'empty') ? MODULE_EM_GENDER_DEFAULT : '';
			break;
	}
	
	return $_gender;
}

function mod_get_age_group($age_group)
{
	$_age_group = '';
	
	switch($age_group)
	{
		case 'Erwachsene':
			$_age_group = 'adult';
			break;
		case 'Kinder':
			$_age_group = 'kids';
			break;
		case 'Kleinkinder':
			$_age_group = 'toddler';
			break;
		case 'Neugeborene':
			$_age_group = 'newborn';
			break;
		case 'Säuglinge':
			$_age_group = 'infant';
			break;
		default:
			$_age_group = (MODULE_EM_AGE_GROUP_DEFAULT != 'empty') ? MODULE_EM_AGE_GROUP_DEFAULT : '';
			break;
	}
	
	return $_age_group;
}

function mod_get_products_item_codes($products_id)
{
	// make sql query
	$products_item_codes_query_result = xtc_db_query("SELECT * FROM products_item_codes WHERE products_id = '".$products_id."' LIMIT 1");

	// check for result
	if (xtc_db_num_rows($products_item_codes_query_result) > 0)
	{
		// return result
		return xtc_db_fetch_array($products_item_codes_query_result);
	}
	
	// return an empty array, if result is empty
	return array();
}

function mod_calculate_shipping_cost($products_id, $products_price) 
{
  	// set globals
  	global $xtPrice, $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes, $em_shipping_countries;
	
	// init shipping content array
	$shipping_content = array();
	
	foreach($em_shipping_countries as $country)
	{
		// init order class for dummy order
		$order = new order();
	
		//Data for shipping cost
		$default_data_query_raw = "SELECT countries_id,
										countries_name,
										countries_iso_code_2,
										countries_iso_code_3,
										address_format_id
								   FROM ". TABLE_COUNTRIES ."
								  WHERE countries_iso_code_2 = '".strtoupper(trim($country))."'";
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
								
		$GLOBALS['order'] = $order;
	
		// set session for calculation shipping costs
		$_SESSION['delivery_zone'] = $order->delivery['country']['iso_code_2'];
		
		// init shipping class
  		$shipping = new shipping();
  
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
	  
		if ($free_shipping == true) 
		{
			$shipping_content[] = array(
										'country' => $order->delivery['country']['iso_code_2'],
										'service' => mod_convert_string(FREE_SHIPPING_TITLE),
										'price' => floatval(0)
										);
		} elseif ($free_shipping_freeamount) {
			$shipping_content[] = array(
										'country' => $order->delivery['country']['iso_code_2'],
										'service' => mod_convert_string($quote['module']),
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
												'service' => mod_convert_string($quote['module'] . (!empty($quote['methods'][0]['title']) ? ' - '.$quote['methods'][0]['title'] : '')), 
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
	}

  	// return cheapest Shipping module
  	return $shipping_content;
}

function mod_stream_response($response) 
{
  	if (defined('MODULE_EM_DEBUG') && MODULE_EM_DEBUG === true) 
  	{
    	// print out formatted array
    	echo '<pre>'.print_r($response, true).'</pre>';  
  	} else {
    	// output json header
    	header('Content-type: application/json;charset=utf-8');
  
  		// encode response
		$reponse = mod_convert_encoding_response($response);
  
    	// output json response
    	echo json_encode($response);  
  	}
}

function mod_stream_invalid_request()
{
	die('Invalid request. Please check all input parameters.');
}
