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

   @file       system/overloads/ApplicationBottomExtenderComponent/EasymarketingApplicationBottomExtender.inc.php
   @version    05.10.2014 - 00:04
   ---------------------------------------------------------------------------------------*/

class EasymarketingApplicationBottomExtender extends EasymarketingApplicationBottomExtender_parent
{
	function proceed() 
	{
		global $cPath, $current_category_id, $product, $last_order;
		
		parent::proceed();
		
		if (defined('MODULE_EM_STATUS') && MODULE_EM_STATUS == 'True') 
		{	
			if(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_CHECKOUT_SUCCESS) !== false)
			{
				$t_order = new order($last_order);
				$t_amount = number_format(floatval($t_order->info['pp_total']), 2, '.', '');
					
				if(MODULE_EM_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE != '')
				{
					$output = MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE;
						
					if($t_amount)
					{
						$output = str_replace('google_conversion_value = 0', 'google_conversion_value = '.$t_amount, $output);
						$output = str_replace('value=0', 'value='.$t_amount, $output);
					}
						
					echo $output;
				}
					
				if(MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True' && MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE != '')
				{
					$output = MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE;
					
					if($t_amount)
					{
						$output = str_replace("'value':'0.00'", "'value':'".$t_amount."'", $output);
					}
					
					echo $output;
				}
			}
			
			$isContactPage = (isset($_GET['coID']) && $_GET['coID'] == 7) ? true : false;
					
			if(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_SHOPPING_CART) !== false || $isContactPage)
			{
				$t_amount = (!$isContactPage ? number_format(floatval($_SESSION['cart']->show_total()), 2, '.', '') : false);
				
				if(MODULE_EM_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EM_GOOGLE_LEAD_TRACKING_CODE != '')
				{
					$output = MODULE_EM_GOOGLE_LEAD_TRACKING_CODE;
					
					if($t_amount)
					{
						$output = str_replace('google_conversion_value = 1.000000', 'google_conversion_value = '.$t_amount, $output);
						$output = str_replace('value=1.000000', 'value='.$t_amount, $output);
					}
					
					echo $output;
				}
					
				if(MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True' && MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE != '')
				{
					$output = MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE;
					
					if($t_amount)
					{
						$output = str_replace("'value':'0.00'", "'value':'".$t_amount."'", $output);
						$output = str_replace("cd[value]=0.00", "cd[value]=".$t_amount, $output);
					}
					
					echo $output;
				}
			}
		
			if(MODULE_EM_REMARKETING_STATUS == 'True' && MODULE_EM_REMARKETING_CODE != '')
			{
				$ecomm_prodid = '';
				$ecomm_pagetype = '';
				$ecomm_totalvalue = '';
				$additional_parameters = array();
				
				$t_amount = 0.00;
				
				if(!empty($cPath))
				{
					$_categoryIds = explode('_', $cPath);
	
					$category_data_query = xtc_db_query("SELECT cd.categories_name FROM categories_description cd WHERE cd.categories_id = '".end($_categoryIds)."' AND cd.language_id = '".$_SESSION['languages_id']."'");
					$category_data_result = xtc_db_fetch_array($category_data_query);
					
					$additional_parameters[] = array('ecomm_category', $category_data_result['categories_name']);
				}
				
				if(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_DEFAULT) !== false && $current_category_id <= 0)
				{
					$ecomm_pagetype = 'home';
				} elseif(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_DEFAULT) !== false && isset($_GET['cat']) && $_GET['cat'] != 'c0' && !isset($actual_products_id)) {
					$ecomm_pagetype = 'searchresults';
				} elseif(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_PRODUCT_INFO) !== false && is_object($product) && $product->isProduct) {
					$ecomm_pagetype = 'product';
					$ecomm_prodid = $product->data['products_id'];
					
					$t_xtPrice = new xtcPrice(DEFAULT_CURRENCY, DEFAULT_CUSTOMERS_STATUS_ID);
					
					$products_price = $t_xtPrice->xtcGetPrice($product->data['products_id'], false, 1, $product->data['products_tax_class_id'], '');
				
					$ecomm_totalvalue = number_format(floatval($products_price), 2, '.', '');
				} elseif(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_SHOPPING_CART) !== false) {
					$ecomm_pagetype = 'cart';
					
					$t_productIds = array();
					$t_productQtys = array();
					
					if($_SESSION['cart']->count_contents() > 0)
					{
						$products = $_SESSION['cart']->get_products();
						
						foreach($products as $product)
						{
							$t_productIds[] = (int)$product['id'];
							$t_productQtys[] = $product['quantity'];
						}
					}
					
					if(floatval($_SESSION['cart']->show_total()) > 0)
					{
						$t_amount = number_format(floatval($_SESSION['cart']->show_total()), 2, '.', '');
					}
					
					$ecomm_prodid = implode(',', $t_productIds);
					$ecomm_totalvalue = number_format(floatval($_SESSION['cart']->show_total()), 2, '.', '');
					
					$additional_parameters[] = array('ecomm_quantity', implode(',', $t_productQtys));
				} elseif(strpos(gm_get_env_info('SCRIPT_NAME'), FILENAME_CHECKOUT_SUCCESS) !== false) {
					$ecomm_pagetype = 'checkout';
					
					$t_productIds = array();
					$t_productQtys = array();
					
					$t_order = new order($last_order);
					
					foreach($t_order->products as $product)
					{
						$t_productIds[] = (int)$product['id'];
						$t_productQtys[] = $product['qty'];
					}
					
					if(floatval($t_order->info['pp_total']) > 0)
					{
						$t_amount = number_format(floatval($t_order->info['pp_total']), 2, '.', '');
					}
					
					$ecomm_prodid = implode(',', $t_productIds);
					$ecomm_totalvalue = $t_amount;
							
					$additional_parameters[] = array('ecomm_quantity', implode(',', $t_productQtys));
				}
				
				if(!empty($ecomm_pagetype))
				{
					$remarketing_code = MODULE_EM_REMARKETING_CODE;
					$remarketing_code = str_replace("ecomm_prodid: 'REPLACE_WITH_VALUE'", "ecomm_prodid: '".$ecomm_prodid."'", $remarketing_code);
					$remarketing_code = str_replace("ecomm_pagetype: 'REPLACE_WITH_VALUE'", "ecomm_pagetype: '".$ecomm_pagetype."'", $remarketing_code);
					$remarketing_code = str_replace("ecomm_totalvalue: 'REPLACE_WITH_VALUE'", "ecomm_totalvalue: '".$ecomm_totalvalue."'", $remarketing_code);
					$remarketing_code = str_replace("value=0", "value=".$t_amount, $remarketing_code);
					
					if(count($additional_parameters) > 0)
					{
						$t_additional_tags = array();
						
						foreach($additional_parameters as $key => $entry)
						{
							array_push($t_additional_tags, $entry[0] . ": '" . $entry[1] . "'");
						}
						
						$remarketing_code = str_replace('};', implode(',', $t_additional_tags) . ',};', $remarketing_code);
					}
				
					echo $remarketing_code;
				}	
			}
		}
	}
}
