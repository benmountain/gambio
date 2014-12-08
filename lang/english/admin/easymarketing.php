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

   @file       lang/english/admin/easymarketing.php
   @version    30.10.2014 - 18:45
   ---------------------------------------------------------------------------------------*/

define('MODULE_EM_HEADING_TITLE', 'Easymarketing');

define('MODULE_EM_INFO_HEADING', 'Info');
define('MODULE_EM_OVERVIEW_HEADING', 'Overview');
define('MODULE_EM_SETTINGS_HEADING', 'Settings');
define('MODULE_EM_MAPPINGS_HEADING', 'Mappings');

define('MODULE_EM_INSTALL', 'Install Easymarketing<br/><br/>');
define('MODULE_EM_INSTALL_BUTTON', 'Install');
define('MODULE_EM_UNINSTALL_BUTTON', 'Deinstall');
define('MODULE_EM_UNINSTALL_TEXT', 'Do you want to deinstall the modul?<br />All settings will be removed!');
define('MODULE_EM_PEFORM_GOOGLE_SITE_VERIFICATION_BUTTON', 'Perform Google Site Verification');
define('MODULE_EM_PEFORM_GOOGLE_SITE_VERIFICATION_TEXT', 'Do you want to perform the Google Site Verfication?');
define('MODULE_EM_DESTROY_GOOGLE_SITE_VERIFICATION_BUTTON', 'Destroy Google Site Verification');
define('MODULE_EM_YES_BUTTON', 'YES');
define('MODULE_EM_NO_BUTTON', 'No');
define('MODULE_EM_UNINSTALL_TEXT', 'Deinstall Easymarketing<br/><br/>');
define('MODULE_EM_UPDATE_DATA_BUTTON', 'Renew data');
define('MODULE_EM_UPDATE_BUTTON', 'Reload overview');

define('MODULE_EM_SUCCESS_HEADING', 'configuration was completed successful');
define('MODULE_EM_SUCCESS_TEXT', 'The configuration was completed successful. Now you can use Easymarketing in your store.');
define('MODULE_EM_ERROR_HEADING', 'An error encountered');
define('MODULE_EM_ERROR_TEXT', 'The configuration was not completed successful. Please check the correctness of the api token.');

define('MODULE_EM_TEXT_DESCRIPTION', 'Modul - Easymarketing.de');
define('MODULE_EM_TEXT_TITLE', 'Easymarketing.de');
define('MODULE_EM_STATUS_TITLE','Modul active');
define('MODULE_EM_STATUS_DESC','') ;
define('MODULE_EM_API_TOKEN_TITLE','API Token') ;
define('MODULE_EM_API_TOKEN_DESC','This token you get from easymarketing.');
define('MODULE_EM_ROOT_CATEGORY_TITLE', 'Root-category');
define('MODULE_EM_ROOT_CATEGORY_DESC', 'It is only transmitted data to Easymarketing, which are below the selected category.');
define('MODULE_EM_ROOT_CATEGORY_DEFAULT_TITLE', 'Use all categories of this shop');
define('MODULE_EM_ACTIVATE_GOOGLE_TRACKING_TITLE', 'Activate Google Tracking');
define('MODULE_EM_ACTIVATE_GOOGLE_TRACKING_DESC', 'If this is activated, the Google trackingpixel are implemented in the webshop.');
define('MODULE_EM_ACTIVATE_FACEBOOK_TRACKING_TITLE', 'Activate Facebook Tracking');
define('MODULE_EM_ACTIVATE_FACEBOOK_TRACKING_DESC', 'If this is activated, the Facebook trackingpixel are implemented in the webshop.');
define('MODULE_EM_REMARKETING_STATUS_TITLE', 'Remarketing - activate');
define('MODULE_EM_REMARKETING_STATUS_DESC', 'If this is activated, remarketing is used.');
define('MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT_TITLE', 'Default products description for Google');
define('MODULE_EM_PRODUCTS_DESCRIPTION_DEFAULT_DESC', 'If there is no mapping of the product description, the selected product description is used.<br />If the selected product description is empty, the other information is submitted to Easymarketing.');
define('MODULE_EM_CONDITION_DEFAULT_TITLE','Condition') ;
define('MODULE_EM_CONDITION_DEFAULT_DESC','Please choose a condition for your products.');
define('MODULE_EM_GENDER_DEFAULT_TITLE','Gender') ;
define('MODULE_EM_GENDER_DEFAULT_DESC','This gender is used when the gender is not maintained in the article.');
define('MODULE_EM_AGE_GROUP_DEFAULT_TITLE','Age group') ;
define('MODULE_EM_AGE_GROUP_DEFAULT_DESC','This age group is used when the age group is not maintained in the article.');
define('MODULE_EM_AVAILIBILLITY_STOCK_0_TITLE','Availability - stock < 1') ;
define('MODULE_EM_AVAILIBILLITY_STOCK_0_DESC','Please choose the Availability for products with stock smaller 0');
define('MODULE_EM_AVAILIBILLITY_STOCK_1_TITLE','Availability - stock > 0') ;
define('MODULE_EM_AVAILIBILLITY_STOCK_1_DESC','Please choose the Availability for products with stock more than 0');

define('MODULE_EM_OVERVIEW_SETUP_TITLE', 'Setup');
define('MODULE_EM_OVERVIEW_API_STATUS_TITLE', 'API Status');
define('MODULE_EM_OVERVIEW_CONFIGURE_ENDPOINTS_STATUS_TITLE', 'Transfered api endpoints');
define('MODULE_EM_OVERVIEW_GOOGLE_TRACKING_STATUS_TITLE', 'Google Tracking active');
define('MODULE_EM_OVERVIEW_FACEBOOK_TRACKING_STATUS_TITLE', 'Facebook Tracking active');
define('MODULE_EM_OVERVIEW_GOOGLE_SITE_VERIFICATION_STATUS_TITLE', 'Google Site Verification Status');
define('MODULE_EM_OVERVIEW_REMARKETING_STATUS_TITLE', 'Remarketing active');
define('MODULE_EM_OVERVIEW_LAST_CRAWL_TITLE', 'Last crawl by Easymarketing');
define('MODULE_EM_OVERVIEW_LAST_CRAWL_DATE_TITLE', 'Last crawl');
define('MODULE_EM_OVERVIEW_LAST_CRAWL_NO_DATE_TITLE', 'Data have not been been retrieved yet');
define('MODULE_EM_OVERVIEW_LAST_CRAWL_CATEGORIES_COUNT_TITLE', 'Categories indexed');
define('MODULE_EM_OVERVIEW_LAST_CRAWL_PRODUCTS_COUNT_TITLE', 'Products indexed');

define('MODULE_EM_MAPPINGS_SAVE_BUTTON', 'Save mapping');
define('MODULE_EM_MAPPINGS_INFO_TEXT', 'Here existing fields in Gambio can be mapped to those in Easy Marketing.<br/>You can also enter a default value. The default value is transmitted when no field could be mapped in the article.<br /><br />Overall, the fields in the left column you can easily pull over using drag and drop in the right field. Sorting is also done via drag and drop. If you do not want to have mapped or field has been moved versehtlich a box, so you drag the box in the right column back in the left column.');
define('MODULE_EM_MAPPINGS_FIELD_DEFAULT_VALUE_TEXT', 'default value');
define('MODULE_EM_MAPPINGS_VALUE_PROPERTY_PREFIX', 'Property');
define('MODULE_EM_MAPPINGS_VALUE_OPTION_PREFIX', 'Option');
define('MODULE_EM_MAPPINGS_VALUE_ADDITIONAL_FIELD_PREFIX', 'Additional field');
define('MODULE_EM_MAPPINGS_FIELD_NAME_DESC', 'If no mapping exists, the product name is used by default.');
define('MODULE_EM_MAPPINGS_FIELD_DESCRIPTION_DESC', 'If no mapping exists, the short product description is used, otherwise the normal product description is used.');
?>