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

   @file       system/controls/Easymarketing.inc.php
   @version    07.04.2014 - 20:34
   ---------------------------------------------------------------------------------------*/

// include Easymarketing configuration
require_once(DIR_FS_CATALOG.'includes/external/Easymarketing/API/APIClient.class.php');
require_once(DIR_FS_CATALOG.'includes/external/Easymarketing/Utilis/EasymarketingHelper.class.php');

class Easymarketing 
{
  	var $code, 
      	$title, 
      	$description, 
      	$shoptoken,
      	$apitoken,
      	$condition,
      	$availibility1,
      	$availibility2,
      	$enabled;


  	function Easymarketing() 
  	{
    	$this->code = 'Easymarketing';
    	$this->title = MODULE_EASYMARKETING_TEXT_TITLE;
    	$this->description = MODULE_EASYMARKETING_TEXT_DESCRIPTION;
    	$this->shoptoken = MODULE_EASYMARKETING_SHOP_TOKEN;
    	$this->apitoken = MODULE_EASYMARKETING_API_TOKEN;
    	$this->condition = MODULE_EASYMARKETING_CONDITION_DEFAULT;
    	$this->availibility1 = MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1;
    	$this->availibility2 = MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0;
    	$this->enabled = ((MODULE_EASYMARKETING_STATUS == 'True') ? true : false);
  	}

	/*
	 * save the settings
	 *
	 * @return boolean
	 */
	function process() 
  	{
    	while (list($key, $value) = each($_POST['configuration'])) {
      		xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '" . $value . "' WHERE configuration_key = '" . $key . "'");
    	}
		
		if($this->checkAPIToken($_POST['configuration']['MODULE_EASYMARKETING_API_TOKEN']))
		{
			return true;
		}
			
		return false;
  	}
  
  	/*
	 * generate the settings form
	 *
	 * @return $content (string)
	 */
  	function displaySettings() 
	{
    	$contents = xtc_draw_form('modules', 'easymarketing.php', 'content=save','post');
    
    	$module_keys = $this->keys();
    	$keys_extra = array();
    	
		for ($j = 0, $k = sizeof($module_keys); $j < $k; $j++) 
		{
     		$key_value_query = xtc_db_query("SELECT configuration_key,
                                              configuration_value,
                                              use_function,
                                              set_function
                                         FROM " . TABLE_CONFIGURATION . "
                                        WHERE configuration_key = '" . $module_keys[$j] . "'");
      		$key_value = xtc_db_fetch_array($key_value_query);
      		
			if ($key_value['configuration_key'] !='') 
			{
        		$keys_extra[$module_keys[$j]]['title'] = constant(strtoupper($key_value['configuration_key'] .'_TITLE'));
      		}
      
	  		$keys_extra[$module_keys[$j]]['value'] = $key_value['configuration_value'];
      		if ($key_value['configuration_key'] !='') 
			{
        		$keys_extra[$module_keys[$j]]['description'] = constant(strtoupper($key_value['configuration_key'] .'_DESC'));
      		}
			
      		$keys_extra[$module_keys[$j]]['use_function'] = $key_value['use_function'];
      		$keys_extra[$module_keys[$j]]['set_function'] = $key_value['set_function'];
    	}
    
		$module_info['keys'] = $keys_extra;
      
    	while (list($key, $value) = each($module_info['keys'])) 
		{
      		$contents .= '<b>' . $value['title'] . '</b><br />' .  $value['description'].'<br />';
      
	  		if ($value['set_function']) 
			{
        		eval('$contents .= ' . $value['set_function'] . "'" . $value['value'] . "', '" . $key . "');");
      		} else {
        		$contents .= xtc_draw_input_field('configuration[' . $key . ']', $value['value']);
      		}
      		
			$contents .= '<br/><br/>';
    	}
    
    	$contents .= '<br/>' . xtc_button(BUTTON_SAVE);
		
		$contents .= '<hr />' . xtc_button_link(EASYMARKETING_UNINSTALL_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=check_uninstall'));
    
    	return $contents;
  	}

	/*
	 * check the installation of this modul
	 *
	 * @return boolean
	 */
  	function check() 
	{
    	if (!isset($this->_check)) 
		{
      		$check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_EASYMARKETING_STATUS'");
      		$this->_check = xtc_db_num_rows($check_query);
    	}
    
		return $this->_check;
  	}

	/*
	 * install the modul
	 */
  	function install() 
	{
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_CONDITION_DEFAULT', 'new',  '6', '1', 'xtc_cfg_select_condition(', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_SHOP_TOKEN', '".EasymarketingHelper::generateShopToken()."',  '6', '1', '', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_API_TOKEN', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_ROOT_CATEGORY', '0',  '6', '1', 'xtc_cfg_select_root_category(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_SHOW_FACEBOOK_LIKE_BADGE', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_RETARGETING_ADSCALE_STATUS', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0', 'available for order',  '6', '1', 'xtc_cfg_select_availibility(', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1', 'in stock',  '6', '1', 'xtc_cfg_select_availibility(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_API_STATUS', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_CONFIGURE_ENDPOINTS_STATUS', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKER_STATUS', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_CODE', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_IMG', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LEAD_TRACKER_STATUS', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LEAD_TRACKING_CODE', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LEAD_TRACKING_IMG', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_STATUS', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_META_TAG', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE', '',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID', '',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LAST_CRAWL_DATE', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LAST_CRAWL_PRODUCTS_COUNT', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EASYMARKETING_LAST_CRAWL_CATEGORIES_COUNT', '0',  '6', '1', '', now())");
  	}

	/*
	 * uninstall the modul
	 */
  	function uninstall() 
	{
    	xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE 'MODULE_EASYMARKETING_%'");
  	}

	/*
	 * get the config keys for displaying in setting form
	 *
	 * @return array
	 */
  	function keys() 
	{
    	return array(
						'MODULE_EASYMARKETING_STATUS',
                 		'MODULE_EASYMARKETING_API_TOKEN',
				 		'MODULE_EASYMARKETING_ROOT_CATEGORY',
				 		'MODULE_EASYMARKETING_SHOW_FACEBOOK_LIKE_BADGE',
				 		'MODULE_EASYMARKETING_RETARGETING_ADSCALE_STATUS',
                 		'MODULE_EASYMARKETING_CONDITION_DEFAULT',
                 		'MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_1',
                 		'MODULE_EASYMARKETING_AVAILIBILLITY_STOCK_0',
                 	);
  	}
	
	/*
	 * check the api token
	 *
	 * @params $APIToken (string)
	 * @return boolean
	 */
	function checkAPIToken($APIToken)
	{	
		$APIClient = new APIClient($APIToken, MODULE_EASYMARKETING_SHOP_TOKEN, EasymarketingHelper::getWebsiteURL());
		$response = $APIClient->performRequest('extraction_status');
		
		$retval = false;
		
		if(isset($response['status']) && $response['status'] != 401)
		{
			$retval = true;
		}
		
		xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".(int)$retval."' WHERE configuration_key = 'MODULE_EASYMARKETING_API_STATUS'");	
		
		return $retval;		
	}
	
	/*
	 * update stored data for overview
	 */
	function updateOverview()
	{
		$this->getExtractionStatus();
	}
	
	/*
	 * execute the setup
	 */
	function executeSetup()
	{
		$this->resetExistingConfigs();
		
		$this->setAPIEndpoints();
		$this->getGoogleConversionTracker();
		$this->getLeadTracker();
		$this->performGoogleSiteVerification();
		$this->getExtractionStatus();
		$this->getFacebookBadge();
		$this->getRetargetingIds();
	}
	
	/* 
	 * reset the existing configuration, if the setup is called again
	 */
	function resetExistingConfigs()
	{
		$keys = array(
						'MODULE_EASYMARKETING_CONFIGURE_ENDPOINTS_STATUS',
						'MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKER_STATUS',
						'MODULE_EASYMARKETING_LEAD_TRACKER_STATUS',
						'MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_STATUS',
						'MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE',
						'MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID'
					);
					
		foreach($keys as $key)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '' WHERE configuration_key = '" . $key . "'");
		}
	}
	
	/*
	 * set the api endpoints
	 */
	function setAPIEndpoints()
	{	
		$website_url = EasymarketingHelper::getWebsiteURL();
		$website_api_url = EasymarketingHelper::getWebsiteURL(true);
		
		$test_pid = xtc_db_fetch_array(xtc_db_query("SELECT products_id FROM products LIMIT 1"));
    
		$params = array(
            'website_url' => $website_url,
            'access_token' => MODULE_EASYMARKETING_API_TOKEN,
            'shop_token' => MODULE_EASYMARKETING_SHOP_TOKEN,
            'categories_api_endpoint' => $website_api_url.'api/easymarketing/categories.php',
            'shop_category_root_id' => MODULE_EASYMARKETING_ROOT_CATEGORY,
            'products_api_endpoint' => $website_api_url.'api/easymarketing/products.php',
            'product_by_id_api_endpoint' => $website_api_url.'api/easymarketing/products.php',
            'best_products_api_endpoint' => $website_api_url.'api/easymarketing/bestseller.php',
            'new_products_api_endpoint' => $website_api_url.'api/easymarketing/products.php',
            'shopsystem_info_api_endpoint' => $website_api_url.'api/easymarketing/shopsystem_info.php',
            'api_setup_test_single_product_id' => $test_pid['products_id']
        );
		
		$response = APIClient::getInstance()->performRequest('configure_endpoints', $params, 'POST');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EASYMARKETING_CONFIGURE_ENDPOINTS_STATUS'");
		}
	}
	
	/*
	 * get the google conversion tracker
	 */
	function getGoogleConversionTracker()
	{		
		$response = APIClient::getInstance()->performRequest('conversion_tracker');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['code']."' WHERE configuration_key = 'MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_CODE'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['img']."' WHERE configuration_key = 'MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKING_IMG'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EASYMARKETING_GOOGLE_CONVERSION_TRACKER_STATUS'");
		}
	}
	
	/*
	 * get lead tracker
	 */
	function getLeadTracker()
	{		
		$response = APIClient::getInstance()->performRequest('lead_tracker');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['code']."' WHERE configuration_key = 'MODULE_EASYMARKETING_LEAD_TRACKING_CODE'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['img']."' WHERE configuration_key = 'MODULE_EASYMARKETING_LEAD_TRACKING_IMG'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EASYMARKETING_LEAD_TRACKER_STATUS'");
		}
	}
	
	/*
	 * perform google site verification
	 */
	function performGoogleSiteVerification()
	{
		$response = APIClient::getInstance()->performRequest('site_verification_data');
		
		if($response['status'] == 200)
		{	
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['meta_tag']."' WHERE configuration_key = 'MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_META_TAG'");

			$params = array(
            	'verification_type' => 'META'
        	);
			
			$response = APIClient::getInstance()->performRequest('perform_site_verification', $params, 'POST');
			
			if($response['status'] == 200)
			{
				xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EASYMARKETING_GOOGLE_SITE_VERIFICATION_STATUS'");
			}
		}
	}
	
	/*
	 * get the extraction status
	 */
	function getExtractionStatus()
	{
		$response = APIClient::getInstance()->performRequest('extraction_status');
		
		if($response['status'] == 200 || $response['status'] == 400)
		{		
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['updated_at']."' WHERE configuration_key = 'MODULE_EASYMARKETING_LAST_CRAWL_DATE'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['num_categories']."' WHERE configuration_key = 'MODULE_EASYMARKETING_LAST_CRAWL_CATEGORIES_COUNT'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['num_products']."' WHERE configuration_key = 'MODULE_EASYMARKETING_LAST_CRAWL_PRODUCTS_COUNT'");
		}
	}
	
	/*
	 * get the facebook like badge
	 */
	function getFacebookBadge()
	{
		$response = APIClient::getInstance()->performRequest('facebook_badge');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']."' WHERE configuration_key = 'MODULE_EASYMARKETING_FACEBOOK_LIKE_BADGE_CODE'");
		}
	}
	
	/*
	 * get the retargeting ids
	 */
	function getRetargetingIds()
	{
		$response = APIClient::getInstance()->performRequest('retargeting_id');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".$response['data']['adscale_id']."' WHERE configuration_key = 'MODULE_EASYMARKETING_RETARGETING_ADSCALE_ID'");
		}
	}
}

$category_dropdown = array();

/*
 * get pull down menu for condition
 *
 * @params $configuration (array), $key (string)
 * @return pull down menu (string)
 */
function xtc_cfg_select_condition($configuration, $key) 
{
  	$condition_dropdown = array(
                          array('id' => 'new', 'text' => 'Neu'),
                          array('id' => 'refurbished', 'text' => 'Erneuert'),
                          array('id' => 'used', 'text' => 'Gebraucht'),
                        );
  	return xtc_draw_pull_down_menu('configuration['.$key.']', $condition_dropdown, $configuration);
}

/*
 * get pull down menu for availibility
 *
 * @params $configuration (array), $key (string)
 * @return pull down menu (string)
 */
function xtc_cfg_select_availibility($configuration, $key) 
{
  	$availibility_dropdown = array(
                             array('id' => 'in stock', 'text' => 'Auf Lager'),
                             array('id' => 'available for order', 'text' => 'Bestellbar'),
                             array('id' => 'out of stock', 'text' => 'Nicht auf Lager'),
                             array('id' => 'preorder', 'text' => 'Vorbestellen'),
                           );
  	return xtc_draw_pull_down_menu('configuration['.$key.']', $availibility_dropdown, $configuration);
}

/*
 * get pull down menu for root category
 *
 * @params $configuration (array), $key (string)
 * @return pull down menu (string)
 */
function xtc_cfg_select_root_category($configuration, $key)
{
	global $category_dropdown;
	
	$shop_root_category_id = 0;
	
	$category_dropdown[] = array('id' => $shop_root_category_id, 'text' => 'Alle Kategorien vom Shop verwenden');
		
	getCategoryTree($shop_root_category_id, 0);
	
	return xtc_draw_pull_down_menu('configuration['.$key.']', $category_dropdown, $configuration);
}

/*
 * get the category tree
 *
 * @params $parent_id (integer), $level (integer), $sub_category (boolean)
 */
function getCategoryTree($parent_id, $level, $sub_category = false)
{		
	global $category_dropdown;
	
	if($sub_category)
	{
		$name_prefix = str_pad('', $level, '-');
	} else {
		$name_prefix = '';
	}
	
	$result = xtc_db_query("SELECT c.categories_id, cd.categories_name FROM categories c, categories_description cd, languages ls WHERE c.parent_id = '".$parent_id."' AND c.categories_status = 1 AND ls.code = '".strtolower(DEFAULT_LANGUAGE)."' AND c.categories_id = cd.categories_id AND cd.language_id = ls.languages_id ORDER BY c.sort_order");
					
	while($category = xtc_db_fetch_array($result))
	{
		if(!$sub_category)
		{
			$level = 0;
		}
			
		$category_dropdown[] = array('id' => (int)$category['categories_id'], 'text' => $name_prefix . $category['categories_name']);
			
		$check = xtc_db_query("SELECT c.categories_id FROM categories c WHERE c.parent_id = '".$category['categories_id']."' AND c.categories_status = 1");
					
		if(xtc_db_num_rows($check) > 0)
		{
			getCategoryTree($category['categories_id'], ++$level, true);
		}
	}
}

?>