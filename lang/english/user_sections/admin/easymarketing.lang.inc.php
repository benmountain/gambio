<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       lang/english/user_sections/admin/easymarketing.lang.inc.php
   @version    v3.0.1
   @updated    23.11.2016 - 13:37
   ---------------------------------------------------------------------------------------*/

$t_language_text_section_content_array = array
(
	'modul_version' => 'v3.0.1 - 20.11.2016',
	
	'heading_title' => 'Easymarketing',
	'info_heading' => 'About Easymarketing',
	'overview_heading' => 'Overview',
	'settings_heading' => 'Settings',
	'mapping_heading' => 'Mapping',
	
	'settings_heading_general' => 'Generell settings',
	'settings_heading_tracking' => 'Tracking settings',
	
	'install' => 'Install Easymarketing',
	'install_button' => 'Install',
	'uninstall_button' => 'Deinstall',
	'uninstall_text' => 'Deinstall Easymarketing',
	
	'perform_google_site_verification_button' => 'Perform Google Site Verification',
	'perform_google_site_verification_text' => 'Do you want to perform the Google Site Verfication?',
	'destroy_google_site_verification_button' => 'Destroy Google Site Verification',
	'yes_button' => 'YES',
	'no_button' => 'No',
	'update_data_button' => 'Renew data',
	'update_button' => 'Reload overview',
	'success_heading' => 'configuration was completed successful',
	'success_text' => 'The configuration was completed successful. Now you can use Easymarketing in your store.',
	'error_heading' => 'An error encountered',
	'error_text' => 'The configuration was not completed successful. Please check the correctness of the api token.',
	'text_description' => 'Modul - Easymarketing.de',
	'text_title' => 'Easymarketing.de',
	'status_title' => 'Modul activate',
	'api_token_title' => 'API Token',
	'api_token_info' => 'This token you get from easymarketing.',
	'root_category_title' => 'Root-category',
	'root_category_info' => 'It is only transmitted data to Easymarketing, which are below the selected category.',
	'root_category_default_title' => 'Use all categories of this shop',
	'activate_google_tracking_title' => 'Activate Google Tracking',
	'activate_google_tracking_info' => 'If this is activated, the Google trackingpixel are implemented in the webshop.',
	'activate_facebook_tracking_title' => 'Activate Facebook Tracking',
	'activate_facebook_tracking_info' => 'If this is activated, the Facebook trackingpixel are implemented in the webshop.',
	'activate_remarketing_title' => 'Remarketing - activate',
	'activate_remarketing_info' => 'If this is activated, remarketing is used.',
	'products_description_default_title' => 'Default products description for Google',
	'products_description_default_info' => 'If there is no mapping of the product description, the selected product description is used.
	If the selected product description is empty, the other information is submitted to Easymarketing.',
	'condition_default_title' => 'Condition',
	'condition_default_info' => 'Please choose a condition for your products.',
	'gender_default_title' => 'Gender',
	'gender_default_info' => 'This gender is used when the gender is not maintained in the article.',
	'age_group_default_title' => 'Age group',
	'age_group_default_info' => 'This age group is used when the age group is not maintained in the article.',
	'availability_stock_0_title' => 'availability - stock <= 1',
	'availability_stock_0_info' => 'Please choose the availability for products with stock smaller or equal 0',
	'availability_stock_1_title' => 'availability - stock > 0',
	'availability_stock_1_info' => 'Please choose the availability for products with stock more than 0',
	'shipping_countries_title' => 'Countries of Delivery',
	'shipping_countries_info' => 'Please enter the ISO codes for the countries for which you want to calculate the shipping costs. (for example: DE, AT, CH)',
	'overview_setup_title' => 'Setup',
	'overview_api_status_title' => 'API Status',
	'overview_configure_endpoints_status_title' => 'Transfered api endpoints',
	'overview_google_tracking_status_title' => 'Google Tracking active',
	'overview_facebook_tracking_status_title' => 'Facebook Tracking active',
	'overview_google_site_verification_status_title' => 'Google Site Verification Status',
	'overview_remarketing_status_title' => 'Remarketing active',
	'overview_last_crawl_title' => 'Last crawl by Easymarketing',
	'overview_last_crawl_date_title' => 'Last crawl',
	'overview_last_crawl_no_date_title' => 'Data have not been been retrieved yet',
	'overview_last_crawl_categories_count_title' => 'Categories indexed',
	'overview_last_crawl_products_count_title' => 'Products indexed',
	'mappings_save_result' => 'Mapping saved successful',
	'mappings_save_button' => 'Save mapping',
	'mappings_info_text' => 'Here existing fields in Gambio can be mapped to those in Easy Marketing.
	You can also enter a default value. The default value is transmitted when no field could be mapped in the article.
	Overall, the fields in the left column you can easily pull over using drag and drop in the right field. Sorting is also done via drag and drop. If you do not want to have mapped or field has been moved versehtlich a box, so you drag the box in the right column back in the left column.',
	'mappings_field_default_value_text' => 'default value',
	'mappings_value_property_prefix' => 'Property',
	'mappings_value_option_prefix' => 'Option',
	'mappings_value_additional_field_prefix' => 'Additional field',
	'mappings_field_name_info' => 'If no mapping exists, the product name is used by default.',
	'mappings_field_description_info' => 'If no mapping exists, the short product description is used, otherwise the normal product description is used.'
);