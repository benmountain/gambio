<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       GXEngine/Modules/EasymarketingModuleCenterModule.inc.php
   @version    v3.0.1
   @updated    23.11.2016 - 13:37
   ---------------------------------------------------------------------------------------*/

require_once(DIR_FS_CATALOG.'includes/external/easymarketing/classes/EasymarketingHelper.class.php');

/**
 * Class EasymarketingModuleCenterModule
 *
 * @extends    AbstractModuleCenterModule
 * @category   System
 * @package    Modules
 */
class EasymarketingModuleCenterModule extends AbstractModuleCenterModule
{
	/**
	 * @var array $configurationKeys
	 */
	protected $configurationKeys = array();


	protected function _init()
	{
		$this->easymarketingText = MainFactory::create('LanguageTextManager', 'easymarketing', $_SESSION['language_id']);
		
		$this->title       = $this->easymarketingText->get_text('text_title');
		$this->description = $this->easymarketingText->get_text('text_description');
		$this->sortOrder   = 20;
	}

	/**
	 * Installs the module
	 */
	public function install()
	{
		parent::install();
		
		$this->db->query("DELETE FROM `configuration` WHERE configuration_key LIKE 'MODULE_EM_%'");

		foreach($this->_getDefaultConfigurationData() as $configuration)
		{
			$this->db->insert('configuration', $configuration);
		}
		
		$this->db->query("CREATE TABLE IF NOT EXISTS `easymarketing_mappings` (
				`id` int(2) NOT NULL,
				  `mapping_field` varchar(30) NOT NULL,
				  `mapping_field_values` varchar(255) NOT NULL,
				  `mapping_field_default_value` varchar(255) NOT NULL,
				  `disabled_shop_fields` varchar(200) NOT NULL,
				  `disable_default_value` int(1) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9");
		
		$this->db->query("INSERT INTO `easymarketing_mappings` (`id`, `mapping_field`, `disabled_shop_fields`, `disable_default_value`) VALUES
				(1, 'name', 'p', 1),
				(2, 'description', 'p', 1),
				(3, 'color', '', 0),
				(4, 'size', '', 0),
				(5, 'size_type', '', 0),
				(6, 'size_system', '', 0),
				(7, 'material', '', 0),
				(8, 'pattern', '', 0)");
	}

	/**
	 * Uninstalls the module
	 */
	public function uninstall()
	{
		parent::uninstall();

		$this->db->query('DELETE FROM configuration WHERE configuration_key LIKE "MODULE_EM_%"');
		$this->db->query('DROP TABLE IF EXISTS easymarketing_mappings');
	}


	/**
	 * Get array of default easymarketing configuration
	 *
	 * @return array
	 */
	protected function _getDefaultConfigurationData()
	{
		return array(
			array(
				'configuration_key'      => 'MODULE_EM_STATUS',
				'configuration_value'    => 'False',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_SHOP_TOKEN',
				'configuration_value'    => EasymarketingHelper::generateShopToken(),
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_API_TOKEN',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_ACTIVATE_GOOGLE_TRACKING',
				'configuration_value'    => 'False',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_ACTIVATE_FACEBOOK_TRACKING',
				'configuration_value'    => 'False',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_ACTIVATE_REMARKETING',
				'configuration_value'    => 'False',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_ROOT_CATEGORY',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_root_category',
				'date_added'             => 'NOW()'
			),
			array(
				'configuration_key'      => 'MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT',
				'configuration_value'    => 'products_description',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_products_description',
				'date_added'             => 'NOW()'
			),		
			array(
				'configuration_key'      => 'MODULE_EM_CONDITION_DEFAULT',
				'configuration_value'    => 'new',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_conditions',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_GENDER_DEFAULT',
				'configuration_value'    => 'empty',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_gender',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_AGE_GROUP_DEFAULT',
				'configuration_value'    => 'empty',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_age_groups',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_AVAILABILITY_STOCK_0',
				'configuration_value'    => 'available for order',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_availabilities',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_AVAILABILITY_STOCK_1',
				'configuration_value'    => 'in stock',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => 'get_availabilities',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_SHIPPING_COUNTRIES',
				'configuration_value'    => 'DE,AT,CH',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_API_STATUS',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_CONFIGURE_ENDPOINTS_STATUS',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_GOOGLE_CONVERSION_TRACKING_CODE',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_FACEBOOK_CONVERSION_TRACKING_CODE',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_GOOGLE_LEAD_TRACKING_CODE',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_FACEBOOK_LEAD_TRACKING_CODE',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_GOOGLE_SITE_VERIFICATION_STATUS',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_GOOGLE_SITE_VERIFICATION_META_TAG',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_REMARKETING_USER_ID',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_REMARKETING_CODE',
				'configuration_value'    => '',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_LAST_CRAWL_DATE',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_LAST_CRAWL_PRODUCTS_COUNT',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			),			
			array(
				'configuration_key'      => 'MODULE_EM_LAST_CRAWL_CATEGORIES_COUNT',
				'configuration_value'    => '0',
				'configuration_group_id' => '6',
				'sort_order'             => '1',
				'set_function'           => '',
				'date_added'             => 'NOW()'
			)
		);
	}
}