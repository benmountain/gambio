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

   @file       api/easymarketing/actions/products.php
   @version    v3.0.2
   @updated    15.06.2017 - 22:45
   ---------------------------------------------------------------------------------------*/

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
    $sql_where = " AND p.products_id = '".xtc_db_prepare_input($id)."' ";
} elseif (isset($newer_than)) {
    $sql_where = " AND p.products_date_added >= '".xtc_db_prepare_input(date("Y-m-d H:i:s", $newer_than))."' ";
    $sql_sort  = " ORDER BY p.products_id ASC ";
}

// process request
if ($sql_limit != '' || $sql_where != '') 
{
  	// init price class
  	$xtPrice = new xtcPrice(DEFAULT_CURRENCY, DEFAULT_CUSTOMERS_STATUS_ID);
	
	// get all shipping countries
	$em_shipping_countries = array();
	
	if(defined('MODULE_EM_SHIPPING_COUNTRIES'))
	{
		$_countries = explode(',', MODULE_EM_SHIPPING_COUNTRIES);
		
		if(count($_countries) > 0)
		{
			foreach($_countries as $country)
			{
				$country = trim($country);
				
				if(strlen($country) == 2)
				{
					$em_shipping_countries[] = $country;
				}
			}
		}
	}
	
	if(count($em_shipping_countries) <= 0)
	{	
		$em_shipping_countries[] = 'DE';
	}
	
	if(MODULE_EM_ROOT_CATEGORY > 0)
	{
		$sql_join_ptc = "JOIN ".TABLE_PRODUCTS_TO_CATEGORIES." ptc ON ptc.products_id = p.products_id AND ptc.categories_id = '".(int)MODULE_EM_ROOT_CATEGORY."'";
	}

  	// sql query for products
  	$products_query_raw = "SELECT p.*,
                                pd.products_name,
                                pd.products_description,
                                pd.products_short_description
                           FROM ".TABLE_PRODUCTS." p 
                           JOIN ".TABLE_PRODUCTS_DESCRIPTION." pd
                                ON p.products_id = pd.products_id
                                   AND pd.language_id = '".(int)$oLanguage->language['id']."'
						   ".$sql_join_ptc."
                          WHERE p.products_status = '1'
                                ".$sql_where."
						  GROUP BY p.products_id
                                ".$sql_sort."
                                ".$sql_limit;
  
  	// make sql query
  	$products_query = xtc_db_query($products_query_raw);
  
  	// init products array
    $products_array = array();
  
  	// check for result
  	if (xtc_db_num_rows($products_query) > 0) 
	{	
		$mappings = array();
		
		$select_mappings_query_result = xtc_db_query("SELECT * FROM easymarketing_mappings ORDER BY id");
		
		while($row_mapping = xtc_db_fetch_array($select_mappings_query_result))
		{
			$mappings[$row_mapping['mapping_field']] = array('values' => explode(',', $row_mapping['mapping_field_values']), 'default_value' => $row_mapping['mapping_field_default_value']);
		}		
    
    	while ($products = xtc_db_fetch_array($products_query)) 
		{
      		// set some variables
      		$total_weight = $products['products_weight'];
  
      		// reset cart
      		$_SESSION['cart']->reset(true);
      		$_SESSION['cart']->add_cart($products['products_id'], 1, '', false);
      		$_SESSION['cart']->weight = $total_weight;
      		$_SESSION['cart']->total = $products_price;
			
			$products_item_codes = mod_get_products_item_codes($products['products_id']);
			$condition = !empty($products_item_codes['google_export_condition']) ? $products_item_codes['google_export_condition'] : MODULE_EM_CONDITION_DEFAULT;
			
			if(!isset($id))
			{
				$sql_limit = '';
			}
			
			$select_properties_combis = xtc_db_query("SELECT * FROM products_properties_combis WHERE products_id = '".$products['products_id']."' ORDER BY products_properties_combis_id ". $sql_limit);
			
			if(xtc_db_num_rows($select_properties_combis) > 0)
			{
				while($row_property_combi = xtc_db_fetch_array($select_properties_combis))
				{
					$products_price = $xtPrice->xtcGetPrice($products['products_id'], true, 1, $products['products_tax_class_id'], '', 1, 0, true, true, $row_property_combi['products_properties_combis_id']);
	  				$products_rrp =  $xtPrice->xtcGetPrice($products['products_id'], true, 1, $products['products_tax_class_id'], '', 1, 0, false, true, $row_property_combi['products_properties_combis_id']);
	
					if($products_price['plain'] != $products_rrp['plain'])
					{
						$discount_absolute = $products_rrp['plain'] - $products_price['plain'];
						$discount_percentage = round($discount_absolute / $products_rrp['plain'] * 100, 0);
					}
			
					$products_mapping = array();
					$product_details = array();
					
					$select_property_values = xtc_db_query("SELECT properties_id, values_name FROM products_properties_index WHERE products_id = '".$products['products_id']."' AND products_properties_combis_id = '".$row_property_combi['products_properties_combis_id']."' AND language_id = '".(int)$oLanguage->language['id']."' ORDER BY properties_sort_order");
		
					while($row_property_value = xtc_db_fetch_array($select_property_values))
					{
						$product_details['p-' . $row_property_value['properties_id']] = $row_property_value['values_name'];
					}
					
					$select_additional_fields = xtc_db_query("SELECT afv.additional_field_id, afvd.language_id, afvd.value FROM additional_field_values afv LEFT JOIN additional_field_value_descriptions afvd ON afv.additional_field_value_id = afvd.additional_field_value_id WHERE item_id = '".$products['products_id']."' AND afvd.language_id IN (0,".(int)$oLanguage->language['id'].") ORDER BY afv.additional_field_id, afvd.language_id ");
					
					while($row_additional_field = xtc_db_fetch_array($select_additional_fields))
					{
						$product_details['af-' . $row_additional_field['additional_field_id']] = $row_additional_field['value'];
					}
					
					foreach($mappings as $key => $data)
					{
						foreach($data['values'] as $value)
						{
							if(isset($product_details[$value]))
							{
								$products_mapping[$key] = $product_details[$value];
								break 1;
							}
						}
						
						if(!isset($products_mapping[$key]) or empty($products_mapping[$key]))
						{
							$products_mapping[$key] = $data['default_value'];
						}
					}
					
					if(empty($products_mapping['name']))
					{
						$products_mapping['name'] = $products['products_name'];
					}
					
					if(empty($products_mapping['description']))
					{
						$products_mapping['description'] = mod_get_description(array('products_description' => $products['products_description'], 'products_short_description' => $products['products_short_description']));
					}
					
					$image_url = '';
					$image_name = '';
					
					if(!empty($row_property_combi['combi_image']))
					{
						$image_name = $row_property_combi['combi_image'];
					} else {
						$image_name = $products['products_image'];
					}
					
					if(!empty($image_name))
					{
						if(ENABLE_SSL)
						{
							$image_url = HTTPS_SERVER;
						} else {
							$image_url = HTTP_SERVER;
						}

						$image_url .= DIR_WS_CATALOG.DIR_WS_POPUP_IMAGES.$image_name;
					}
			
					// build products array
      				$products_array[] = array(
										'id' => $products['products_id'] . '-' . $row_property_combi['products_properties_combis_id'],
										'parent_id' => $products['products_id'],
                                		'name' => mod_convert_string($products_mapping['name']),
                                		'categories' => mod_get_categories_array($products['products_id']),
										'google_category' => mod_get_google_category($products['products_id']),
                                		'condition' => mod_get_condition($condition),
                                		'availability' => mod_get_availability($products_item_codes['google_export_availability_id'], $row_property_combi['combi_quantity']),
                                        'stock_quantity' => ($row_property_combi['combi_quantity'] > 0) ? round($row_property_combi['combi_model']) : 0,
                                		'currency' => DEFAULT_CURRENCY,
                                		'price' => $products_price['plain'],
										'rrp' => $products_rrp['plain'],
										'discount_absolute' => ($discount_absolute > 0) ? $discount_absolute : 0,
										'discount_percentage' => ($discount_percentage > 0) ? $discount_percentage : 0,
                                		'url' => xtc_href_link(FILENAME_PRODUCT_INFO, xtc_product_link($products['products_id'], $products['products_name']), 'SSL', false),
                                		'image_url' => !empty($image_url) ? $image_url : 'null',
                                		'shipping' => mod_calculate_shipping_cost($products['products_id'], $products_price),
                                		'description' => mod_convert_string($products_mapping['description']),
										'age_group' => mod_get_age_group($products_item_codes['age_group']),
										'gender' => mod_get_gender($products_item_codes['gender']),
										'color' => mod_convert_string((!empty($products_mapping['color'])) ? $products_mapping['color'] : ''),
										'size' => mod_convert_string((!empty($products_mapping['size'])) ? $products_mapping['size'] : ''),
										'size_type' => mod_convert_string((!empty($products_mapping['size_type'])) ? $products_mapping['size_type'] : ''),
										'size_system' => mod_convert_string((!empty($products_mapping['size_system'])) ? $products_mapping['size_system'] : ''),
										'material' => mod_convert_string((!empty($products_mapping['material'])) ? $products_mapping['material'] : ''),
										'pattern' => mod_convert_string((!empty($products_mapping['pattern'])) ? $products_mapping['pattern'] : ''),
                                		'gtin' => ($row_property_combi['combi_ean'] != '') ? $row_property_combi['combi_ean'] : 'null',
                                		'adult' => ($products['products_fsk18'] == '1') ? true : false,
										'mpn' => mod_convert_string((!empty($row_property_combi['combi_model']) ? $row_property_combi['combi_model'] : $products_item_codes['code_mpn'])),
										'brand' => mod_convert_string($products_item_codes['brand_name'])
                                	);
				}
			} else {
				$products_mapping = array();
				$product_details = array();
					
				$products_price = $xtPrice->xtcGetPrice($products['products_id'], true, 1, $products['products_tax_class_id'], '', 1, 0, true, false, 0);
	  			$products_rrp =  $xtPrice->xtcGetPrice($products['products_id'], true, 1, $products['products_tax_class_id'], '', 1, 0, false, false, 0);
				
				if($products_price['plain'] != $products_rrp['plain'])
				{
					$discount_absolute = $products_rrp['plain'] - $products_price['plain'];
					$discount_percentage = round($discount_absolute / $products_rrp['plain'] * 100, 0);
				}
				
				$select_additional_fields = xtc_db_query("SELECT afv.additional_field_id, afvd.language_id, afvd.value FROM additional_field_values afv LEFT JOIN additional_field_value_descriptions afvd ON afv.additional_field_value_id = afvd.additional_field_value_id WHERE item_id = '".$products['products_id']."' AND afvd.language_id IN (0,".(int)$oLanguage->language['id'].") ORDER BY afv.additional_field_id, afvd.language_id ");
					
				while($row_additional_field = xtc_db_fetch_array($select_additional_fields))
				{
					$product_details['af-' . $row_additional_field['additional_field_id']] = $row_additional_field['value'];
				}
					
				foreach($mappings as $key => $data)
				{
					foreach($data['values'] as $value)
					{
						if(isset($product_details[$value]))
						{
							$products_mapping[$key] = $product_details[$value];
							break 1;
						}
					}
				}
					
				if(empty($products_mapping['name']))
				{
					$products_mapping['name'] = $products['products_name'];
				}
					
				if(empty($products_mapping['description']))
				{
					$products_mapping['description'] = mod_get_description(array('products_description' => $products['products_description'], 'products_short_description' => $products['products_short_description']));
				}
				
				$image_url = '';
				
				if(!empty($products['products_image']))
				{
					if(ENABLE_SSL)
					{
						$image_url = HTTPS_SERVER;
					} else {
						$image_url = HTTP_SERVER;
					}
					
					$image_url .= DIR_WS_CATALOG.DIR_WS_POPUP_IMAGES.$products['products_image'];
				}
					
      			// build products array
      			$products_array[] = array(
										'id' => $products['products_id'],
                                		'name' => mod_convert_string($products_mapping['name']),
                                		'categories' => mod_get_categories_array($products['products_id']),
										'google_category' => mod_get_google_category($products['products_id']),
                                		'condition' => mod_get_condition($condition),
                                		'availability' => mod_get_availability($products_item_codes['google_export_availability_id'], $products['products_quantity']),
                                        'stock_quantity' => ($products['products_quantity'] > 0) ? round($products['products_quantity']) : 0,
                                		'currency' => DEFAULT_CURRENCY,
                                		'price' => $products_price['plain'],
										'rrp' => $products_rrp['plain'],
										'discount_absolute' => ($discount_absolute > 0) ? $discount_absolute : 0,
										'discount_percentage' => ($discount_percentage > 0) ? $discount_percentage : 0,
                                		'url' => xtc_href_link(FILENAME_PRODUCT_INFO, xtc_product_link($products['products_id'], $products['products_name']), 'SSL', false),
                                		'image_url' => !empty($image_url) ? $image_url : 'null',
                                		'shipping' => mod_calculate_shipping_cost($products['products_id'], $products_price),
                                		'description' => mod_convert_string($products_mapping['description']),
										'age_group' => mod_get_age_group($products_item_codes['age_group']),
										'gender' => mod_get_gender($products_item_codes['gender']),
                                		'gtin' => ($products['products_ean'] != '') ? $products['products_ean'] : 'null',
                                		'adult' => ($products['products_fsk18'] == '1') ? true : false,
										'mpn' => mod_convert_string($products_item_codes['code_mpn']),
										'brand' => mod_convert_string($products_item_codes['brand_name'])
                                	);   
			}
		}
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
} else {
	mod_stream_invalid_request();
}
?>