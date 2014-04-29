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
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

class EasymarketingApplicationBottomExtender extends EasymarketingApplicationBottomExtender_parent
{
	function proceed() 
	{
		global $cPath, $current_category_id, $actual_products_id, $last_order;
		
		parent::proceed();
		
		if (defined('MODULE_EASYMARKETING_STATUS') && MODULE_EASYMARKETING_STATUS == 'True') 
		{	
			if(basename($_SERVER['PHP_SELF']) == FILENAME_CHECKOUT_SUCCESS && MODULE_EASYMARKETING_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EASYMARKETING_GOOGLE_TRACKING_STATUS == 1)
			{
				$t_order = new order($last_order);
				$t_amount = round($t_order->info['pp_total'], 2);
				
				$output = MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_CODE . MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_IMG;
				
				if($t_amount)
				{
					$output = str_replace('google_conversion_value = 0', 'google_conversion_value = "'.$t_amount.'"', $output);
					$output = str_replace('value=0', 'value='.$t_amount, $output);
				}
				
				echo $output;
			}
			
			if(MODULE_EASYMARKETING_ACTIVATE_GOOGLE_TRACKING == 'True' && MODULE_EASYMARKETING_GOOGLE_TRACKING_STATUS == 1)
			{
				$isContactPage = (isset($_GET['coID']) && $_GET['coID'] == 7) ? true : false;
				
				if(basename($_SERVER['PHP_SELF']) == FILENAME_SHOPPING_CART || $isContactPage)
				{
					echo MODULE_EASYMARKETING_LEAD_TRACKING_CODE . MODULE_EASYMARKETING_LEAD_TRACKING_IMG;
				}
			}
		
			if(MODULE_EASYMARKETING_RETARGETING_ADSCALE_STATUS == 'True' && MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID != '')
			{
				if(!empty($cPath))
				{
					$_categoryIds = explode('_', $cPath);
	
					$category_data = xtc_db_query("SELECT cd.categories_name FROM categories_description cd WHERE cd.categories_id IN (".implode(',', $_categoryIds).") AND cd.language_id = '".$_SESSION['languages_id']."'");
					$categoryIds = array();
					
					while($row_category_data = xtc_db_fetch_array($category_data))
					{
						$categoryIds[] = '"'. $row_category_data['categories_name'] . '"';
					}
					$categoryIds = implode(',', $categoryIds);
				} else {
					$categoryIds = '""';
				}
				
				$productIds = array();
				
				if(basename($_SERVER['PHP_SELF']) == FILENAME_DEFAULT && $current_category_id <= 0)
				{
					$pageType = 'homepage';
				} elseif(basename($_SERVER['PHP_SELF']) == FILENAME_DEFAULT && isset($_GET['cat']) && $_GET['cat'] != 'c0' && !isset($actual_products_id)) {
					$pageType = 'categories';
				} elseif(basename($_SERVER['PHP_SELF']) == FILENAME_PRODUCT_INFO && isset($actual_products_id) && $actual_products_id > 0) {
					$productIds[] = '"'.$actual_products_id.'"';
					$pageType = 'products';
				} elseif(basename($_SERVER['PHP_SELF']) == FILENAME_SHOPPING_CART) {
					
					if($_SESSION['cart']->count_contents() > 0)
					{
						$products = $_SESSION['cart']->get_products();
						
						foreach($products as $product)
						{
							$productIds[] = '"' . (int)$product['id'] . '"';
						}
					}
					
					$pageType = 'basket';
				} elseif(basename($_SERVER['PHP_SELF']) == FILENAME_CHECKOUT_SUCCESS) {
					$t_order = new order($last_order);
					
					foreach($t_order->products as $product)
					{
						$productIds[] = '"'. (int)$product['id'] . '"';
					}
					
					$pageType = 'transactions';
				}
				
				if(empty($productIds))
				{
					$productIds = '""';
				} else {
					$productIds = implode(',', $productIds);
				}
				
				if(isset($pageType))
				{
					$tp_adscale_code = '
					<script type="text/javascript">
					window.adscaleProductViews = window.adscaleProductViews ? window.adscaleProductViews : [];
					window.adscaleProductViews.push({
					"aid":"'.MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID.'", 
					"productIds": ['.$productIds.'],
					"categoryIds": ['.$categoryIds.'],
					"pageTypes": ["'.$pageType.'"],
					});
					</script>
					<script type="text/javascript" src="//js.adscale.de/pbr-a.js"></script>
					';
					echo str_replace(chr(0x0009), '', $tp_adscale_code);
				}
				
				if($pageType == 'transactions')
				{
					$tp_adscale_pixel = '
					<!-- adscale pixel -->
					<ins style="display: none;" class="adscale-rt" data-accountId="'.MODULE_EASYMARKETING_RETARGETING_ADSCALE_CONVERSION_ID.'" data-pixelName="Transactions"></ins> 
					<script async defer type="text/javascript" src="//js.adscale.de/rt-a.js"></script>
					
					<!-- adscale conversion tracking -->
					<ins style="display: none;" class="adscale-cpx" data-accountId="'.MODULE_EASYMARKETING_RETARGETING_ADSCALE_CONVERSION_ID.'" data-pixelName="1"></ins>
					<script async defer type="text/javascript" src="//js.adscale.de/cpx-a.js"></script>
					';
					echo str_replace(chr(0x0009), '', $tp_adscale_pixel);
				}
			}
		}
	}
}