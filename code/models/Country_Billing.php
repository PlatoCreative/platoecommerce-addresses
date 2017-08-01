<?php

/**
 * Billing country
 *
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2012, Frank Mullenger
 * @package swipestripe
 * @subpackage order
 */
class Country_Billing extends Country {

	/**
	 * Build default list of billing countries
	 *
	 * @see Country::$iso_3166_countryCodes
	 * @see DataObject::requireDefaultRecords()
	 */
	public function requireDefaultRecords() {

		parent::requireDefaultRecords();
		singleton('ShopConfig')->requireDefaultRecords();

		if (!DataObject::get_one('Country_Billing')) {

			$shopConfig = ShopConfig::current_shop_config();

			foreach (self::$iso_3166_countryCodes as $code => $title) {
				$country = new Country_Billing();
				$country->Code = $code;
				$country->Title = $title;
				$country->ShopConfigID = $shopConfig->ID;
				$country->write();
			}
			DB::alteration_message('Billing countries created', 'created');
		}
	}

	public function Regions() {
		return Region_Billing::get()
			->where("\"CountryID\" = " . $this->ID);
	}
}
