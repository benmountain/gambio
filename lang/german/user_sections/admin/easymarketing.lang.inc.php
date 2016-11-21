<?php
/* -----------------------------------------------------------------------------------------
   Easymarketing Modul

   Copyright (c) 2016 [www.easymarketing.de]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License (Version 2)
   [http://www.gnu.org/licenses/gpl-2.0.html]
   -----------------------------------------------------------------------------------------
   
   @author		Florian Ressel <florian.ressel@easymarketing.de>

   @file       lang/german/user_sections/admin/easymarketing.lang.inc.php
   @version    v3.0.0
   @updated    20.11.2016 - 19:31
   ---------------------------------------------------------------------------------------*/

$t_language_text_section_content_array = array
(
	'modul_version' => 'v3.0.0 - 20.11.2016',
	
	'heading_title' => 'Easymarketing',
	'info_heading' => 'Über Easymarketing',
	'overview_heading' => 'Übersicht',
	'settings_heading' => 'Einstellungen',
	'mapping_heading' => 'Mapping',
	
	'settings_heading_general' => 'Allgemeine Einstellungen',
	'settings_heading_tracking' => 'Tracking Einstellungen',
	
	'install_text' => 'Easymarketing installieren.',
	'install_button' => 'Installieren',
	'uninstall_button' => 'Deinstallieren',
	'uninstall_text' => 'Easymarketing deinstallieren',
	
	'perform_google_site_verification_button' => 'Google Site Verifikation durchführen',
	'perform_google_site_verification_text' => 'Ich stimme zu, dass Google easymarketing bei meiner URL-Verifikation als weiteren "Inhaber" einträgt,<br />damit easymarketing meine Daten für Google Shopping über die Schnittstelle pflegen und auslesen kann.<br /><br />Ich kann diese Zustimmung natürlich jederzeit widerrufen.<br /><br />easymarketing wird meine Daten unter keinen Umständen zu anderen Zwecken als meiner Kampagnen-Steuerung verwenden, weitergeben oder bei sich speichern.',
	'destroy_google_site_verification_button' => 'Google Site Verifikation aufheben',
	'yes_button' => 'JA',
	'no_button' => 'Nein',
	'update_data_button' => 'Daten erneut abrufen',
	'update_button' => 'Übersicht aktualisieren',
	'success_heading' => 'Konfiguration erfolgreich abgeschlossen',
	'success_text' => 'Die Konfiguration wurde erfolgreich abgeschlossen. Sie können nun Easymarketing in Ihrem Shop verwenden.',
	'error_heading' => 'Es ist ein Fehler aufgetreten',
	'error_text' => 'Die Konfiguration konnte nicht erfolgreich abgeschlossen werden. Bitte überprüfen Sie, ob der API Token korrekt eingegeben wurde.',
	'text_description' => 'Modul - Easymarketing.de',
	'text_title' => 'Easymarketing.de',
	'status_title' => 'Modul aktivieren',
	'api_token_title' => 'API Token',
	'api_token_info' => 'Diesen Token erhalten Sie von Easymarketing.',
	'root_category_title' => 'Root-Kategorie',
	'root_category_info' => 'Es werden nur Daten an Easymarketing übermittelt, welche unterhalb der ausgewählten Kategorie liegen.',
	'root_category_default_title' => 'Alle Kategorien vom Shop verwenden',
	'activate_google_tracking_title' => 'Google Tracking aktivieren',
	'activate_google_tracking_info' => 'Ist dies aktiviert, so werden die Google Trackingpixel im Webshop implementiert.',
	'activate_facebook_tracking_title' => 'Facebook Tracking aktivieren',
	'activate_facebook_tracking_info' => 'Ist dies aktiviert, so werden die Facebook Trackingpixel im Webshop implementiert.',
	'activate_remarketing_title' => 'Remarketing aktivieren',
	'activate_remarketing_info' => 'Ist dies aktiviert, so wird Remarketing verwendet.',
	'products_description_default_title' => 'Standard Produktbeschreibung',
	'products_description_default_info' => 'Existiert für die Produktbeschreibung kein Mapping, wird die ausgewählte Produktbeschreibung verwendet.
	Ist die ausgewählte Produktbeschreibung leer, so wird die andere Produktbeschreibung an Easymarketing übermittelt.',
	'condition_default_title' => 'Artikelzustand',
	'condition_default_info' => 'Dieser Zustand wird verwendet, wenn der Zustand beim Artikel nicht gepflegt ist.',
	'gender_default_title' => 'Geschlecht',
	'gender_default_info' => 'Dieses Geschlecht wird verwendet, wenn das Geschlecht beim Artikel nicht gepflegt ist.',
	'age_group_default_title' => 'Altersgruppe',
	'age_group_default_info' => 'Diese Altersgruppe wird verwendet, wenn die Altersgruppe beim Artikel nicht gepflegt ist.',
	'availability_stock_0_title' => 'Verfügbarkeit - Lagerbestand <= 0',
	'availability_stock_0_info' => 'Bitte geben sie die Verfügbarkeit der Artikel an, welche im Shop einen negativen Lagerbestand oder einen Lagerbestand von 0 haben',
	'availability_stock_1_title' => 'Verfügbarkeit - Lagerbestand > 0',
	'availability_stock_1_info' => 'Bitte geben sie die Verfügbarkeit der Artikel an, welche im Shop einen positiven Lagerbestand haben',
	'shipping_countries_title' => 'Lieferländer',
	'shipping_countries_info' => 'Bitte tragen Sie hier die ISO-Codes der Lieferländer kommagetrennt ein, für welche Versandkosten berechnet werden sollen. (z.B. DE, AT, CH)',
	'overview_setup_title' => 'Setup',
	'overview_api_status_title' => 'API Status',
	'overview_configure_endpoints_status_title' => 'Endpunkte an Easymarketing übermittelt',
	'overview_google_tracking_status_title' => 'Google Tracking aktiv',
	'overview_facebook_tracking_status_title' => 'Facebook Tracking aktiv',
	'overview_google_site_verification_status_title' => 'Google Site Verifikation Status',
	'overview_remarketing_status_title' => 'Remarketing aktiv',
	'overview_last_crawl_title' => 'Letzter Abruf durch Easymarketing',
	'overview_last_crawl_date_title' => 'Letzter Abruf',
	'overview_last_crawl_no_date_title' => 'Daten wurden bisher noch nicht abgerufen',
	'overview_last_crawl_categories_count_title' => 'Kategorien indexiert',
	'overview_last_crawl_products_count_title' => 'Produkte indexiert',
	'mappings_save_result' => 'Mapping erfolgreich speichern',
	'mappings_save_button' => 'Mapping speichern',
	'mappings_info_text' => 'Hier können bestehende Felder in Gambio mit denen bei Easymarketing gemappt werden.
	Sie können auch einen Standardwert eintragen. Der Standardwert wird übertragen, wenn kein Feld beim Artikel gemappt werden konnte.
	Die Felder in der linken Spalte können Sie ganz einfach mittels Drag and Drop in das rechte Feld rüberziehen. Die Sortierung erfolgt ebenfalls über Drag and Drop. Sollten Sie ein Feld nicht mehr gemappt haben wollen oder ein Feld wurde versehtlich verschoben, so ziehen Sie das gewünschte Feld in der rechten Spalte wieder zurück in die linke Spalte.',
	'mappings_field_default_value_text' => 'Standardwert',
	'mappings_value_property_prefix' => 'Eigenschaft',
	'mappings_value_option_prefix' => 'Attribut',
	'mappings_value_additional_field_prefix' => 'Zusatzfeld',
	'mappings_field_name_info' => 'Falls kein Mapping existiert, wird standardmäßig der Produktname verwendet.',
	'mappings_field_description_info' => 'Falls kein Mapping existiert, wird standardmäßig die kurze Produktbeschreibung, ansonsten die normale Produktbeschreibung verwendet falls die kurze Produktbeschreibung nicht existiert.'
);
