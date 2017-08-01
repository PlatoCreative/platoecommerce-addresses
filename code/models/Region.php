<?php
/**
 * Regions for countries
 *
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2012, Frank Mullenger
 * @package swipestripe
 * @subpackage order
 */
class Region extends DataObject {

	/**
	 * Singular name
	 *
	 * @var String
	 */
	private static $singular_name = 'Region';

	/**
	 * Plural name
	 *
	 * @var String
	 */
	private static $plural_name = 'Regions';

	/**
	 * Fields
	 *
	 * @var Array
	 */
	private static $db = array(
		'Code' => "Varchar",
		'Title' => 'Varchar',
		'SortOrder' => 'Int'
	);

	/**
	 * Managed via the SiteConfig, regions are related to Countries
	 *
	 * @var Array
	 */
	private static $has_one = array (
		'ShopConfig' => 'ShopConfig',
		'Country' => 'Country'
	);

	/**
	 * Summary fields
	 *
	 * @var Array
	 */
	private static $summary_fields = array(
		'Title' => 'Title',
		'Code' => 'Code',
		'Country.Title' => 'Country'
	);

	private static $default_sort = 'SortOrder';

	public function onBeforeWrite(){
		parent::onBeforeWrite();
		$shopConfig = ShopConfig::current_shop_config();

		$this->ShopConfigID = $shopConfig->ID;
	}

	/**
	 * Convenience function to prevent errors thrown
	 */
	public function forTemplate() {
		return;
	}

	/**
	 * Retrieve map of shipping regions including Country code
	 *
	 * @return Array
	 */
	public static function shipping_map() {

		$countryRegions = array();
		$regions = Region_Shipping::get();
		if ($regions && $regions->exists()) {

			foreach ($regions as $region) {
				$country = $region->Country();
				$countryRegions[$country->Code][$region->Code] = $region->Title;
			}
		}
		return $countryRegions;
	}
}
