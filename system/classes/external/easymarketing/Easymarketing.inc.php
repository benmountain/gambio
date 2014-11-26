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

   @file       system/classes/external/easymarketing/Easymarketing.inc.php
   @version    30.10.2014 - 17:24
   ---------------------------------------------------------------------------------------*/

// include Easymarketing configuration
require_once(DIR_FS_CATALOG.'includes/external/easymarketing/classes/APIClient.class.php');
require_once(DIR_FS_CATALOG.'includes/external/easymarketing/classes/EasymarketingHelper.class.php');

class Easymarketing 
{
  	var $code, 
      	$title, 
      	$description, 
      	$enabled;

	/*
	 * inital function
	 */
  	function Easymarketing() 
  	{
    	$this->code = 'Easymarketing';
    	$this->title = MODULE_EM_TEXT_TITLE;
    	$this->description = MODULE_EM_TEXT_DESCRIPTION;
    	$this->enabled = ((MODULE_EM_STATUS == 'True') ? true : false);
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
		
		if($this->checkAPIToken($_POST['configuration']['MODULE_EM_API_TOKEN']))
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
		
		$contents .= '<hr />' . xtc_button_link(MODULE_EM_UNINSTALL_BUTTON, xtc_href_link('easymarketing.php', xtc_get_all_get_params(array('content')) . 'content=check_uninstall'));
    
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
      		$check_query = xtc_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_EM_STATUS'");
      		$this->_check = xtc_db_num_rows($check_query);
    	}
    
		return $this->_check;
  	}

	/*
	 * install the modul
	 */
  	function install() 
	{
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_STATUS', 'True',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_SHOP_TOKEN', '".EasymarketingHelper::generateShopToken()."',  '6', '1', '', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_API_TOKEN', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_ACTIVATE_GOOGLE_TRACKING', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_ACTIVATE_FACEBOOK_TRACKING', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_REMARKETING_STATUS', 'False',  '6', '1', 'xtc_cfg_select_option(array(\'True\', \'False\'), ', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_ROOT_CATEGORY', '0',  '6', '1', 'xtc_cfg_select_root_category(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_CONDITION_DEFAULT', 'new',  '6', '1', 'xtc_cfg_select_condition(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_GENDER_DEFAULT', 'unisex',  '6', '1', 'xtc_cfg_select_gender(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_AGE_GROUP_DEFAULT', 'adult',  '6', '1', 'xtc_cfg_select_age_group(', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_AVAILIBILLITY_STOCK_0', 'available for order',  '6', '1', 'xtc_cfg_select_availibility(', now())");
    	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_AVAILIBILLITY_STOCK_1', 'in stock',  '6', '1', 'xtc_cfg_select_availibility(', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_API_STATUS', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_CONFIGURE_ENDPOINTS_STATUS', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_GOOGLE_LEAD_TRACKING_CODE', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE', '',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS', '0',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_REMARKETING_USER_ID', '',  '6', '1', '', now())");
		xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_REMARKETING_CODE', '',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_LAST_CRAWL_DATE', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT', '0',  '6', '1', '', now())");
	 	xtc_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT', '0',  '6', '1', '', now())");
		
		$sql = "CREATE TABLE IF NOT EXISTS `easymarketing_mappings` (
				`id` int(2) NOT NULL,
				  `mapping_field` varchar(30) NOT NULL,
				  `mapping_field_values` varchar(255) NOT NULL,
				  `mapping_field_default_value` varchar(255) NOT NULL,
				  `disabled_shop_fields` varchar(200) NOT NULL,
				  `disable_default_value` int(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9";
		xtc_db_query($sql);
		
		$sql = "INSERT INTO `easymarketing_mappings` (`id`, `mapping_field`, `disabled_shop_fields`, `disable_default_value`) VALUES
				(1, 'name', 'p,o', 1),
				(2, 'description', 'p,o', 1),
				(3, 'color', '', 0),
				(4, 'size', '', 0),
				(5, 'size_type', '', 0),
				(6, 'size_system', '', 0),
				(7, 'material', '', 0),
				(8, 'pattern', '', 0)";
		xtc_db_query($sql);
  	}

	/*
	 * uninstall the modul
	 */
  	function uninstall() 
	{
    	xtc_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key LIKE 'MODULE_EM_%'");
		xtc_db_query("DROP TABLE easymarketing_mappings");
  	}

	/*
	 * get the config keys for displaying in setting form
	 *
	 * @return array
	 */
  	function keys() 
	{
    	return array(
						'MODULE_EM_STATUS',
                 		'MODULE_EM_API_TOKEN',
				 		'MODULE_EM_ROOT_CATEGORY',
						'MODULE_EM_ACTIVATE_GOOGLE_TRACKING',
						'MODULE_EM_ACTIVATE_FACEBOOK_TRACKING',
				 		'MODULE_EM_REMARKETING_STATUS',
                 		'MODULE_EM_CONDITION_DEFAULT',
						'MODULE_EM_GENDER_DEFAULT',
						'MODULE_EM_AGE_GROUP_DEFAULT',
                 		'MODULE_EM_AVAILIBILLITY_STOCK_1',
                 		'MODULE_EM_AVAILIBILLITY_STOCK_0',
                 	);
  	}
	
	/*
	 * get the mapping fields
	 *
	 * @return array
	 */
	function getMappingFields()
	{
		$mapping_fields = array();
		
		$select = xtc_db_query("SELECT * FROM easymarketing_mappings ORDER BY id");
		
		while($row = xtc_db_fetch_array($select))
		{
			$mapping_fields[] = $row;
		}
		
		return $mapping_fields;
	}
	
	/*
	 * get the mapping entries by fieldName
	 *
	 * @params $fieldName
	 * @return array
	 */
	function getMappingEntries($fieldName)
	{
		$mapping_entries = array();
		
		$field_data_query_result = xtc_db_query("SELECT disabled_shop_fields FROM easymarketing_mappings WHERE mapping_field = '".$fieldName."'");
		$field_data = xtc_db_fetch_array($field_data_query_result);
		
		$disabled_shop_fields = explode(',', $field_data['disabled_shop_fields']);
		
		if(!in_array('p', $disabled_shop_fields))
		{
			$select_properties_query_result = xtc_db_query("SELECT pd.properties_id, pd.properties_name FROM properties_description pd LEFT JOIN properties p ON pd.properties_id = p.properties_id WHERE language_id = '".(int)$_SESSION['languages_id']."' GROUP BY pd.properties_id ORDER BY p.sort_order");
						
			while($row_property = xtc_db_fetch_array($select_properties_query_result))
			{
				$mapping_entries['p-' . $row_property['properties_id']] = MODULE_EM_MAPPINGS_VALUE_PROPERTY_PREFIX. ': ' . $row_property['properties_name'];
			}
		}
		
		if(!in_array('o', $disabled_shop_fields))
		{
			$select_options_query_result = xtc_db_query("SELECT products_options_id, products_options_name FROM products_options WHERE language_id = '".(int)$_SESSION['languages_id']."' GROUP BY products_options_id ORDER BY products_options_name");
						
			while($row_option = xtc_db_fetch_array($select_options_query_result))
			{
				$mapping_entries['o-' . $row_option['products_options_id']] = MODULE_EM_MAPPINGS_VALUE_OPTION_PREFIX . ': ' . $row_option['products_options_name'];
			}
		}
		
		if(!in_array('af', $disabled_shop_fields))
		{
			$select_additional_fields_query_result = xtc_db_query("SELECT additional_field_id, name FROM additional_field_descriptions WHERE language_id = '".(int)$_SESSION['languages_id']."' GROUP BY additional_field_id ORDER BY name");
						
			while($row_additional_field = xtc_db_fetch_array($select_additional_fields_query_result))
			{
				$mapping_entries['af-' . $row_additional_field['additional_field_id']] = MODULE_EM_MAPPINGS_VALUE_ADDITIONAL_FIELD_PREFIX . ': ' . $row_additional_field['name'];
			}
		}	
		
		return $mapping_entries;
	}
	
	/*
	 * get the mapping field values by fieldName
	 *
	 * @params $fieldName
	 * @return array
	 */
	function getMappingFieldValuesByFieldName($fieldName)
	{
		$mapping_field_values_result = xtc_db_query("SELECT mapping_field_values FROM easymarketing_mappings WHERE mapping_field = '".$fieldName."'");
		$row_mapping_field_values = xtc_db_fetch_array($mapping_field_values_result);
		
		$mapping_field_values = explode(',', $row_mapping_field_values['mapping_field_values']);
		
		return $mapping_field_values;
	}
	
	/*
	 * save mappings
	 *
	 * @params $fieldName
	 * @return array
	 */
	function saveMappings()
	{
		foreach($_POST['mappingFields'] as $mappingField => $mappingFieldValues)
		{
			xtc_db_query("UPDATE easymarketing_mappings SET mapping_field_values = '".$mappingFieldValues."', mapping_field_default_value = '".xtc_db_prepare_input($_POST['mappingDefaultFields'][$mappingField])."' WHERE mapping_field = '".$mappingField."'");
		}
	}
	
	/*
	 * check the api token
	 *
	 * @params $APIToken (string)
	 * @return boolean
	 */
	function checkAPIToken($APIToken)
	{	
		$APIClient = new APIClient($APIToken, MODULE_EM_SHOP_TOKEN, EasymarketingHelper::getWebsiteURL());
		$response = $APIClient->performRequest('extraction_status');
		
		$retval = false;
		
		if(isset($response['status']) && $response['status'] != 401)
		{
			$retval = true;
		}
		
		xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".(int)$retval."' WHERE configuration_key = 'MODULE_EM_API_STATUS'");	
		
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
	function updateEasymarketingData()
	{
		$this->resetExistingConfigs();
		
		$this->setAPIEndpoints();
		$this->getTrackingPixel();
		$this->getExtractionStatus();
		$this->getRemarketingData();
	}
	
	/* 
	 * reset the existing configuration, if the setup is called again
	 */
	function resetExistingConfigs()
	{
		$keys = array(
						'MODULE_EM_CONFIGURE_ENDPOINTS_STATUS'
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
		
		if(MODULE_EM_ROOT_CATEGORY > 0)
		{
			$test_pid = xtc_db_fetch_array(xtc_db_query("SELECT ptc.products_id FROM ".TABLE_PRODUCTS_TO_CATEGORIES." ptc LEFT JOIN ".TABLE_PRODUCTS." p ON p.products_id = ptc.products_id WHERE ptc.categories_id = '".(int)MODULE_EM_ROOT_CATEGORY."' AND p.products_status = 1 ORDER BY ptc.products_id LIMIT 1"));
		} else {
			$test_pid = xtc_db_fetch_array(xtc_db_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE products_status = 1 ORDER BY products_id LIMIT 1"));
		}
    
		$params = array(
            'website_url' => $website_url,
            'access_token' => MODULE_EM_API_TOKEN,
            'shop_token' => MODULE_EM_SHOP_TOKEN,
            'categories_api_endpoint' => $website_api_url.'api/easymarketing/categories.php',
            'shop_category_root_id' => MODULE_EM_ROOT_CATEGORY,
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
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EM_CONFIGURE_ENDPOINTS_STATUS'");
		}
	}

	/*
	 * get the tracking pixel
	 */
	function getTrackingPixel()
	{		
		$response_ct = APIClient::getInstance()->performRequest('conversion_tracker');
		
		if($response_ct['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response_ct['data']['code'])."' WHERE configuration_key = 'MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE'");	
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response_ct['data']['fb_code'])."' WHERE configuration_key = 'MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE'");
		}
		
		$response_lt = APIClient::getInstance()->performRequest('lead_tracker');
		
		if($response_lt['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response_lt['data']['code'])."' WHERE configuration_key = 'MODULE_EM_GOOGLE_LEAD_TRACKING_CODE'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response_lt['data']['fb_code'])."' WHERE configuration_key = 'MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE'");
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
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['meta_tag'])."' WHERE configuration_key = 'MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG'");

			$params = array(
            	'verification_type' => 'META'
        	);
			
			$response = APIClient::getInstance()->performRequest('perform_site_verification', $params, 'POST');
			
			if($response['status'] == 200)
			{
				xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '1' WHERE configuration_key = 'MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS'");
			}
		}
	}
	
	/*
	 * destory google site verification
	 */
	function destroyGoogleSiteVerification()
	{
		xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '' WHERE configuration_key = 'MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG'");
		xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '0' WHERE configuration_key = 'MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS'");
	}
	
	/*
	 * get the extraction status
	 */
	function getExtractionStatus()
	{
		$response = APIClient::getInstance()->performRequest('extraction_status');
		
		if($response['status'] == 200 || $response['status'] == 400)
		{		
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['updated_at'])."' WHERE configuration_key = 'MODULE_EM_LAST_CRAWL_DATE'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['num_categories'])."' WHERE configuration_key = 'MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['num_products'])."' WHERE configuration_key = 'MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT'");
		}
	}
	
	/*
	 * get the remarketing data
	 */
	function getRemarketingData()
	{
		$response = APIClient::getInstance()->performRequest('google_remarketing_code');
		
		if($response['status'] == 200)
		{
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['user_id'])."' WHERE configuration_key = 'MODULE_EM_REMARKETING_USER_ID'");
			xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($response['data']['code'])."' WHERE configuration_key = 'MODULE_EM_REMARKETING_CODE'");
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
 * get pull down menu for gender
 *
 * @params $configuration (array), $key (string)
 * @return pull down menu (string)
 */
function xtc_cfg_select_gender($configuration, $key) 
{
  	$gender_dropdown = array(
                          array('id' => 'Male', 'text' => 'Herren'),
                          array('id' => 'Female', 'text' => 'Damen'),
                          array('id' => 'Unisex', 'text' => 'Unisex'),
                        );
  	return xtc_draw_pull_down_menu('configuration['.$key.']', $gender_dropdown, $configuration);
}

/*
 * get pull down menu for age_group
 *
 * @params $configuration (array), $key (string)
 * @return pull down menu (string)
 */
function xtc_cfg_select_age_group($configuration, $key) 
{
  	$age_group_dropdown = array(
                          array('id' => 'adult', 'text' => 'Erwachsene'),
                          array('id' => 'kids', 'text' => 'Kinder'),
                          array('id' => 'toddler', 'text' => 'Kleinkinder'),
						  array('id' => 'newborn', 'text' => 'Neugeborene'),
						  array('id' => 'infant', 'text' => 'Säuglinge'),
                        );
  	return xtc_draw_pull_down_menu('configuration['.$key.']', $age_group_dropdown, $configuration);
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
							 array('id' => 'not in stock', 'text' => 'Nicht auf Lager'),
							 array('id' => 'preorder', 'text' => 'Vorbestellbar'),
                             array('id' => 'no order', 'text' => 'Nicht bestellbar')
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
	
	$category_dropdown[] = array('id' => $shop_root_category_id, 'text' => MODULE_EM_ROOT_CATEGORY_DEFAULT_TITLE);
		
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
	
	$result = xtc_db_query("SELECT c.categories_id, cd.categories_name FROM categories c, categories_description cd WHERE c.parent_id = '".$parent_id."' AND c.categories_status = 1 AND cd.language_id = '".(int)$_SESSION['languages_id']."' AND c.categories_id = cd.categories_id ORDER BY c.sort_order");
					
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