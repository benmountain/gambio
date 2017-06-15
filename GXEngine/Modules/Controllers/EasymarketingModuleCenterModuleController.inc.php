<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016-2017 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       GXEngine/Modules/Controllers/EasymarketingModuleCenterModuleController.inc.php
   @version    v3.0.2
   @updated    15.06.2017 - 22:45
   ---------------------------------------------------------------------------------------*/

require_once(DIR_FS_CATALOG.'includes/external/easymarketing/classes/EasymarketingHelper.class.php');
require_once(DIR_FS_CATALOG.'includes/external/easymarketing/classes/APIClient.class.php');

/**
 * Class EasymarketingModuleCenterModuleController
 * @extends    AbstractModuleCenterModuleController
 * @category   System
 * @package    Modules
 * @subpackage Controllers
 */
class EasymarketingModuleCenterModuleController extends AbstractModuleCenterModuleController
{
	protected $categoryDropdown = array();
	
	
	protected function _init()
	{		
		$this->easymarketingText = MainFactory::create('LanguageTextManager', 'easymarketing', $_SESSION['language_id']);
		
		$this->pageTitle = $this->easymarketingText->get_text('heading_title');
	}
	
	
	public function actionDefault()
	{
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');

		$html = $this->_render('easymarketing_info.html', array('page_links' => $this->_getPageLinks()));

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}
	
	public function actionInfo()
	{
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');

		$html = $this->_render('easymarketing_info.html', array('page_links' => $this->_getPageLinks()));

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}
	
	public function actionOverview()
	{
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');
		
		$formData = array(
							'page_links' => $this->_getPageLinks(),
							'config' => $this->_getConfig()
		);
		
		$html = $this->_render('easymarketing_overview.html', $formData);

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}
	
	public function actionCheckGoogleSiteVerification()
	{
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');
		
		$formData = array(
							'page_links' => $this->_getPageLinks(),
							'config' => $this->_getConfig()
		);
		
		$html = $this->_render('easymarketing_check_google_site_verification.html', $formData);

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}
	
	public function actionPerformGoogleSiteVerification()
	{
		$response = APIClient::getInstance()->performRequest('site_verification_data');
		
		if($response['status'] == 200)
		{
			$this->_setConfig(array('MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG' => $response['data']['meta_tag']));

			$params = array(
            	'verification_type' => 'META'
        	);
			
			$response = APIClient::getInstance()->performRequest('perform_site_verification', $params, 'POST');
			
			$status = 'False';
			
			if($response['status'] == 200)
			{
				$status = 'True';
			}
			
			$this->_setConfig(array('MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS' => $status));
		}

		$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Overview');

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}
	
	public function actionDestroyGoogleSiteVerification()
	{
		$configKeys = array(
								'MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG' => '',
								'MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS' => ''
		);
		
		$this->_setConfig($configKeys);

		$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Overview');

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}
	
	public function actionUpdateOverview()
	{
		$this->_getExtractionStatus();
		
		$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Overview');

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}	
	
	public function actionConfig()
	{
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');
		
		$formData = array(
							'page_links' => $this->_getPageLinks(),
							'config' => $this->_getConfig()
		);
		
		$html = $this->_render('easymarketing_config.html', $formData);

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}	
	
	public function actionStore()
	{
		$postData = $this->_getPostDataCollection()->getArray();
		
		$this->_setConfig($postData);
		
		$api_token_check_result = false;
		
		if($this->_checkAPIToken($postData['MODULE_EM_API_TOKEN']))
		{
			$api_token_check_result = true;
			
			$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/UpdateEasymarketingData');
		} else {		
			$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Config&result=0');
		}

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}
	
	public function actionUpdateEasymarketingData()
	{
		$this->_updateEasymarketingData();
		
		$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Config&result=1');

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}
	
	public function actionMapping()
	{		
		$this->contentView->set_template_dir(DIR_FS_ADMIN . 'html/content/module_center/');
		
		$formData = array(
							'page_links' => $this->_getPageLinks(),
							'config' => $this->_getConfig(),
							'mapping_fields' => $this->_getMappingFields(),
							'mapping_field_values' => $this->_getMappingFieldValues(),
							'mapping_entries' => $this->_getMappingEntries()
		);
		
		$html = $this->_render('easymarketing_mapping.html', $formData);

		return MainFactory::create('AdminPageHttpControllerResponse', $this->pageTitle, $html);
	}
	
	public function actionStoreMapping()
	{
		$postData = $this->_getPostDataCollection()->getArray();
		
		foreach($postData['mappingFields'] as $mappingField => $mappingFieldValues)
		{
			xtc_db_query("UPDATE easymarketing_mappings SET mapping_field_values = '".$mappingFieldValues."', mapping_field_default_value = '".xtc_db_prepare_input($postData['mappingDefaultFields'][$mappingField])."' WHERE mapping_field = '".$mappingField."'");
		}
		
		$url = xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Mapping&result=1');

		return MainFactory::create('RedirectHttpControllerResponse', $url);
	}	
	
	private function _getPageLinks()
	{
		return array(
						'website_page_link' => EasymarketingHelper::getWebsiteURL(),
						'info_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule'),
						'overview_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Overview'),
						'config_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Config'),
						'mapping_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/Mapping'),
						'update_overview_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/UpdateOverview'),
						'check_google_site_verification_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/CheckGoogleSiteVerification'),
						'perform_google_site_verification_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/PerformGoogleSiteVerification'),
						'destroy_google_site_verification_page_link' => xtc_href_link('admin.php', 'do=EasymarketingModuleCenterModule/DestroyGoogleSiteVerification')
		);
	}
	
	
	private function _getConfig()
	{
		$configs = array();
		
		$select = xtc_db_query("SELECT * FROM configuration WHERE configuration_key LIKE 'MODULE_EM_%'");
		
		while($row = xtc_db_fetch_array($select))
		{
			$key = str_replace('MODULE_EM_', '', $row['configuration_key']);
			$key = strtolower($key);
			
			$availableValues = array();
			
			if(!empty($row['set_function']))
			{
				if(method_exists($this, $row['set_function']))
				{
					$availableValues = $this->{$row['set_function']}();
				}
			}
			
			$configs[$key] = array('value' => $row['configuration_value'], 'available_values' => $availableValues);
		}
		
		return $configs;		
	}
	
	private function _setConfig($configs = array())
	{
		if(!empty($configs))
		{
			foreach($configs as $key => $value)
			{
				xtc_db_query("UPDATE " . TABLE_CONFIGURATION . " SET configuration_value = '".xtc_db_input($value)."' WHERE configuration_key = '" . $key . "'");
			}
		}
	}
	
	/*
	 * get pull down menu for root category
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_root_category()
	{	
		$shop_root_category_id = 0;

		$this->categoryDropdown[] = array('id' => $shop_root_category_id, 'text' => $this->easymarketingText->get_text('root_category_default_title'));

		$this->_getCategoryTree($shop_root_category_id, 0);

		return $this->categoryDropdown;
	}
	
	/*
	 * get pull down menu for products description
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_products_description() 
	{
		return array(
						array('id' => 'products_description', 'text' => 'Artikelbeschreibung'),
						array('id' => 'products_short_description', 'text' => 'Kurzbeschreibung'),
		);
	}

	/*
	 * get pull down menu for condition
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_conditions() 
	{
		return array(
						array('id' => 'new', 'text' => 'Neu'),
						array('id' => 'refurbished', 'text' => 'Erneuert'),
						array('id' => 'used', 'text' => 'Gebraucht'),
		);
	}
	
	/*
	 * get pull down menu for gender
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_gender() 
	{
		return array(
						array('id' => 'empty', 'text' => 'keine Angabe'),
						array('id' => 'Male', 'text' => 'Herren'),
						array('id' => 'Female', 'text' => 'Damen'),
						array('id' => 'Unisex', 'text' => 'Unisex'),
		);
	}

	/*
	 * get pull down menu for age_group
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_age_groups() 
	{
		return array(
						array('id' => 'empty', 'text' => 'keine Angabe'),
						array('id' => 'adult', 'text' => 'Erwachsene'),
						array('id' => 'kids', 'text' => 'Kinder'),
						array('id' => 'toddler', 'text' => 'Kleinkinder'),
						array('id' => 'newborn', 'text' => 'Neugeborene'),
						array('id' => 'infant', 'text' => 'Säuglinge'),
		);
	}

	/*
	 * get pull down menu for availibility
	 *
	 * @params $configuration (array), $key (string)
	 * @return pull down menu (string)
	 */
	private function get_availabilities() 
	{
		return array(
						array('id' => 'in stock', 'text' => 'Auf Lager'),
						array('id' => 'not in stock', 'text' => 'Nicht auf Lager'),
						array('id' => 'preorder', 'text' => 'Vorbestellbar'),
						array('id' => 'no order', 'text' => 'Nicht bestellbar')
		);
	}

	/*
	 * get the category tree
	 *
	 * @params $parent_id (integer), $level (integer), $sub_category (boolean)
	 */
	private function _getCategoryTree($parent_id, $level, $sub_category = false)
	{	
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

			$this->categoryDropdown[] = array('id' => (int)$category['categories_id'], 'text' => $name_prefix . $category['categories_name']);

			$check = xtc_db_query("SELECT c.categories_id FROM categories c WHERE c.parent_id = '".$category['categories_id']."' AND c.categories_status = 1");

			if(xtc_db_num_rows($check) > 0)
			{
				$this->_getCategoryTree($category['categories_id'], ++$level, true);
			}
		}
	}
	
	/*
	 * check the api token
	 *
	 * @params $APIToken (string)
	 * @return boolean
	 */
	private function _checkAPIToken($APIToken = '')
	{
		if($APIToken == '')
		{
			return false;
		}	
		
		$APIClient = new APIClient($APIToken, MODULE_EM_SHOP_TOKEN, EasymarketingHelper::getWebsiteURL());
		$response = $APIClient->performRequest('extraction_status');
		
		$retval = false;
		
		if(isset($response['status']) && $response['status'] != 401)
		{
			$retval = true;
		}
		
		$this->_setConfig(array('MODULE_EM_API_STATUS' => (int)$retval));
		
		return $retval;		
	}
	
	/*
	 * execute the setup
	 */
	private function _updateEasymarketingData()
	{
		$configs = array(
								'MODULE_EM_CONFIGURE_ENDPOINTS_STATUS' => '',
								'MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE' => '',
								'MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE' => '',
								'MODULE_EM_GOOGLE_LEAD_TRACKING_CODE' => '',
								'MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE' => '',
								'MODULE_EM_REMARKETING_USER_ID' => '',
								'MODULE_EM_REMARKETING_CODE' => '',
								'MODULE_EM_LAST_CRAWL_DATE' => '',
								'MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT' => '',
								'MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT' => ''
		);
		
		$this->_setConfig($configs);		
		
		$this->_setAPIEndpoints();
		$this->_getTrackingPixel();
		$this->_getExtractionStatus();
		$this->_getRemarketingData();
	}
	
	private function _setAPIEndpoints()
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
            'categories_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=categories',
            'shop_category_root_id' => MODULE_EM_ROOT_CATEGORY,
            'products_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=products',
            'product_by_id_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=products',
            'best_products_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=bestseller',
            'new_products_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=products',
            'shopsystem_info_api_endpoint' => $website_api_url.'api/easymarketing/request.php?action=shopsystem_info',
            'api_setup_test_single_product_id' => $test_pid['products_id']
        );
		
		$response = APIClient::getInstance()->performRequest('configure_endpoints', $params, 'POST');
		
		if($response['status'] == 200)
		{
			$value = 'True';
		} else {
			$value = 'False';
		}
		
		$this->_setConfig(array('MODULE_EM_CONFIGURE_ENDPOINTS_STATUS' => $value));
	}
	
	/*
	 * get the tracking pixel
	 */
	private function _getTrackingPixel()
	{		
		$response_ct = APIClient::getInstance()->performRequest('conversion_tracker');
		
		if($response_ct['status'] == 200)
		{
			$configs = array(
								'MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE' => $response_ct['data']['code'],
								'MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE' => $response_ct['data']['fb_code']
			);
			
			$this->_setConfig($configs);
		}
		
		$response_lt = APIClient::getInstance()->performRequest('lead_tracker');
		
		if($response_lt['status'] == 200)
		{
			$configs = array(
								'MODULE_EM_GOOGLE_LEAD_TRACKING_CODE' => $response_lt['data']['code'],
								'MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE' => $response_lt['data']['fb_code']
			);
			
			$this->_setConfig($configs);
		}
	}
	
	/*
	 * get the extraction status
	 */
	private function _getExtractionStatus()
	{
		$response = APIClient::getInstance()->performRequest('extraction_status');
		
		if($response['status'] == 200 || $response['status'] == 400)
		{
			$configs = array(
								'MODULE_EM_LAST_CRAWL_DATE' => $response['data']['updated_at'],
								'MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT' => $response['data']['num_categories'],
								'MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT' => $response['data']['num_products']
			);
			
			$this->_setConfig($configs);
		}
	}
	
	/*
	 * get the remarketing data
	 */
	private function _getRemarketingData()
	{
		$response = APIClient::getInstance()->performRequest('google_remarketing_code');
		
		if($response['status'] == 200)
		{
			$configs = array(
								'MODULE_EM_REMARKETING_USER_ID' => $response['data']['user_id'],
								'MODULE_EM_REMARKETING_CODE' => $response['data']['code']
			);
			
			$this->_setConfig($configs);
		}
	}
	
	/*
	 * get the mapping fields
	 *
	 * @return array
	 */
	private function _getMappingFields()
	{
		$mapping_fields = array();
		
		$select = xtc_db_query("SELECT * FROM easymarketing_mappings ORDER BY id");
		
		while($row = xtc_db_fetch_array($select))
		{
			$description_txt_field = 'mappings_field_' . $row['mapping_field'] . '_desc';
			$description = $this->easymarketingText->get_text($description_txt_field);
			$data = $row;			
			$data['description'] = ($description == $description_txt_field) ? '' : $description;
			
			$mapping_fields[] = $data;
		}
		
		return $mapping_fields;
	}
	
	/*
	 * get the mapping field values by fieldName
	 *
	 * @params $fieldName
	 * @return array
	 */
	private function _getMappingFieldValues()
	{
		$mapping_field_values = array();
		
		$select = xtc_db_query("SELECT mapping_field FROM easymarketing_mappings ORDER BY id");
		
		while($row = xtc_db_fetch_array($select))
		{		
			$mapping_field_values_result = xtc_db_query("SELECT mapping_field_values FROM easymarketing_mappings WHERE mapping_field = '".$row['mapping_field']."'");
			$row_mapping_field_values = xtc_db_fetch_array($mapping_field_values_result);

			$mapping_field_values[$row['mapping_field']] = explode(',', $row_mapping_field_values['mapping_field_values']);
		}
		
		return $mapping_field_values;
	}
	
	/*
	 * get the mapping entries by fieldName
	 *
	 * @params $fieldName
	 * @return array
	 */
	private function _getMappingEntries()
	{
		$mapping_entries = array();
		
		$select = xtc_db_query("SELECT mapping_field, disabled_shop_fields FROM easymarketing_mappings ORDER BY id");
		
		while($row = xtc_db_fetch_array($select))
		{
			$mapping_entries[$row['mapping_field']] = array();

			$disabled_shop_fields = explode(',', $row['disabled_shop_fields']);

			if(!in_array('p', $disabled_shop_fields))
			{
				$select_properties_query_result = xtc_db_query("SELECT pd.properties_id, pd.properties_name FROM properties_description pd LEFT JOIN properties p ON pd.properties_id = p.properties_id WHERE language_id = '".(int)$_SESSION['languages_id']."' GROUP BY pd.properties_id ORDER BY p.sort_order");

				while($row_property = xtc_db_fetch_array($select_properties_query_result))
				{
					$mapping_entries[$row['mapping_field']]['p-' . $row_property['properties_id']] = $this->easymarketingText->get_text('mappings_value_property_prefix') . ': ' . $row_property['properties_name'];
				}
			}

			if(!in_array('af', $disabled_shop_fields))
			{
				$select_additional_fields_query_result = xtc_db_query("SELECT additional_field_id, name FROM additional_field_descriptions WHERE language_id = '".(int)$_SESSION['languages_id']."' GROUP BY additional_field_id ORDER BY name");

				while($row_additional_field = xtc_db_fetch_array($select_additional_fields_query_result))
				{
					$mapping_entries[$row['mapping_field']]['af-' . $row_additional_field['additional_field_id']] = $this->easymarketingText->get_text('mappings_value_additional_field_prefix') . ': ' . $row_additional_field['name'];
				}
			}
		}
		
		return $mapping_entries;
	}
}