<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016-2017 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       user_classes/overloads/ApplicationBottomExtenderComponent/EasymarketingApplicationBottomExtender.inc.php
   @version    v3.0.2
   @updated    15.06.2017 - 22:45
   ---------------------------------------------------------------------------------------*/

class EasymarketingApplicationBottomExtender extends EasymarketingApplicationBottomExtender_parent
{
	function proceed() 
	{
		global $cPath, $current_category_id, $product;
		
		parent::proceed();
		
		if (defined('MODULE_EM_STATUS') && MODULE_EM_STATUS == 'True') 
		{	
			// BOF GM_MOD
			$t_script_name = '';
	
			if(strpos($_SERVER['SCRIPT_NAME'], '.php') !== false && strpos($_SERVER['SCRIPT_NAME'], DIR_WS_CATALOG) !== false)
			{
				$t_script_name = $_SERVER['SCRIPT_NAME'];
			}
			elseif(strpos($_SERVER["PHP_SELF"], '.php') !== false && strpos($_SERVER['PHP_SELF'], DIR_WS_CATALOG) !== false)
			{
				$t_script_name = $_SERVER["PHP_SELF"];
			}
			elseif(strpos($_SERVER["SCRIPT_FILENAME"], '.php') !== false && strpos($_SERVER['SCRIPT_FILENAME'], DIR_WS_CATALOG) !== false)
			{
				$t_script_name = $_SERVER['SCRIPT_FILENAME'];
			}
			else
			{
				$t_script_name = $PHP_SELF;
			}
			
			$t_page = $this->get_page();
			
			$isContactPage = (isset($_GET['coID']) && $_GET['coID'] == 7 && $_GET['action'] == 'success') ? true : false;
					
			if($t_page == 'Cart' || $isContactPage)
			{
				$t_amount = (!$isContactPage ? number_format(floatval($_SESSION['cart']->show_total()), 2, '.', '') : false);
				
				if(MODULE_EM_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EM_GOOGLE_LEAD_TRACKING_CODE != '')
				{
					$output = MODULE_EM_GOOGLE_LEAD_TRACKING_CODE;
					
					if($t_amount)
					{
						$output = preg_replace('/google_conversion_value*\s=*\s(\d.*\d|\d)/', 'google_conversion_value = ' . $t_amount, $output);
						$output = preg_replace('/value=(\d.*\d|\d)&/', 'value='.$t_amount.'&', $output);
					}
					
					$this->v_output_buffer['EasymarketingApplicationBottomExtender'] .= $output;
				}
					
				if(MODULE_EM_ACTIVATE_FACEBOOK_TRACKING == 'True' && MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE != '')
				{
					$output = MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE;
					
					if($t_amount)
					{
						$output = preg_replace("/\'value\':\'(\d.*\d|\d)\'/", "'value':'".$t_amount."'", $output);
						$output = preg_replace("/cd\[value\]=(\d.*\d|\d)&/", "cd[value]=".$t_amount."&", $output);
					}
					
					$this->v_output_buffer['EasymarketingApplicationBottomExtender'] .= $output;
				}
			}
		
			if(MODULE_EM_ACTIVATE_REMARKETING == 'True' && MODULE_EM_REMARKETING_CODE != '')
			{
				$ecomm_prodids = array();
				$ecomm_pagetype = '';
				$ecomm_totalvalue = 0;
				$t_additional_parameters = array();
				
				$t_amount = 0.00;
				
				if(!empty($cPath))
				{
					$_categoryIds = explode('_', $cPath);
	
					$category_data_query = xtc_db_query("SELECT cd.categories_name FROM categories_description cd WHERE cd.categories_id = '".end($_categoryIds)."' AND cd.language_id = '".$_SESSION['languages_id']."'");
					$category_data_result = xtc_db_fetch_array($category_data_query);
					
					$t_additional_parameters[] = "ecomm_category: '".$category_data_result['categories_name']."'";
				}
				
				if($t_page == 'Index')
				{
					$ecomm_pagetype = 'home';
				} elseif($t_page == 'Cat' && substr_count($t_script_name, 'advanced_search_result.php') == 0) {
					$ecomm_pagetype = 'category';
					
					$prod_ids = array();
					
					if(isset($_categoryIds))
					{
						$products_to_categories_query = xtc_db_query("SELECT products_id FROM products_to_categories WHERE categories_id IN (".implode(',', $_categoryIds).") GROUP BY products_id");
					
						while($product = xtc_db_fetch_array($products_to_categories_query))
						{
							$ecomm_prodids[] = (int)$product['products_id'];
						}
					}
				} elseif($t_page == 'Cat' && substr_count($t_script_name, 'advanced_search_result.php') > 0) {
					$ecomm_pagetype = 'searchresults';
				} elseif($t_page == 'ProductInfo') {
					$ecomm_pagetype = 'product';
					$ecomm_prodids[] = (int)$product->data['products_id'];
					
					$t_xtPrice = new xtcPrice(DEFAULT_CURRENCY, DEFAULT_CUSTOMERS_STATUS_ID);
					
					$products_price = $t_xtPrice->xtcGetPrice($product->data['products_id'], true, 1, $product->data['products_tax_class_id'], '', 1, 0, true, false, 0);
					
					$ecomm_totalvalue = number_format(floatval($products_price['plain']), 2, '.', '');
				} elseif($t_page == 'Cart') {
					$ecomm_pagetype = 'cart';
					
					$t_productIds = array();
					$t_productQtys = array();
					
					if($_SESSION['cart']->count_contents() > 0)
					{
						$products = $_SESSION['cart']->get_products();
						
						foreach($products as $product)
						{
							$ecomm_prodids[] = (int)$product['id'];
							$t_productQtys[] = $product['quantity'];
						}
					}
					
					if(floatval($_SESSION['cart']->show_total()) > 0)
					{
						$t_amount = number_format(floatval($_SESSION['cart']->show_total()), 2, '.', '');
					}
					
					$ecomm_totalvalue = number_format($t_amount, 2, '.', '');
					
					$t_additional_parameters[] = "ecomm_quantity: [".implode(',', $t_productQtys)."]";
				}
				
				if(!empty($ecomm_pagetype))
				{
					$ecomm_prodids = (count($ecomm_prodids) > 0) ? implode(',', $ecomm_prodids) : '';
					
					$remarketing_code = MODULE_EM_REMARKETING_CODE;
					$remarketing_code = str_replace("ecomm_prodid: 'REPLACE_WITH_VALUE'", "ecomm_prodid: [".$ecomm_prodids."]", $remarketing_code);
					$remarketing_code = str_replace("ecomm_pagetype: 'REPLACE_WITH_VALUE'", "ecomm_pagetype: '".$ecomm_pagetype."'", $remarketing_code);
					$remarketing_code = str_replace("ecomm_totalvalue: 'REPLACE_WITH_VALUE'", "ecomm_totalvalue: ".$ecomm_totalvalue, $remarketing_code);
					$remarketing_code = str_replace("value=0", "value=".$t_amount, $remarketing_code);
					
					if(count($t_additional_parameters) > 0)
					{
						$remarketing_code = str_replace('};', implode(',', $t_additional_parameters) . ',};', $remarketing_code);
					}
				
					$this->v_output_buffer['EasymarketingApplicationBottomExtender'] .= $remarketing_code;
				}
			}
		}
	}
}
