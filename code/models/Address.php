<?php
/**
 * Represents a shipping or billing address which are both attached to {@link Order}.
 *
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2011, Frank Mullenger
 * @package swipestripe
 * @subpackage order
 */
class Address extends DataObject {

	/**
	 * DB fields for an address
	 *
	 * @var Array
	 */
	private static $db = array(
		'Default' => 'Boolean',
		'FirstName' => 'Varchar',
		'Surname' => 'Varchar',
		'Company' => 'Varchar',
		'Address' => 'Varchar(255)',
		'AddressLine2' => 'Varchar(255)',
		'City' => 'Varchar(100)',
		'PostalCode' => 'Varchar(30)',
		'State' => 'Varchar(100)',

		//De-normalise these values in case region or country is deleted
		'CountryName' => 'Varchar',
		'CountryCode' => 'Varchar(2)', //ISO 3166
		'RegionName' => 'Varchar',
		'RegionCode' => 'Varchar(2)'
	);

	/**
	 * Relations for address
	 *
	 * @var Array
	 */
	private static $has_one = array(
		'Member' => 'Customer',
		'Country' => 'Country',
		'Region' => 'Region'
	);

	public function onAfterWrite() {
		parent::onAfterWrite();

		//Make sure there is only one default address
		if ($this->Default == true) {

			$addrs = Address::get()
				->where("\"ClassName\" = '" . get_class($this) . "' AND \"MemberID\" = '{$this->MemberID}' AND \"Default\" = 1 AND \"ID\" != {$this->ID}");

			if ($addrs && $addrs->exists()) foreach ($addrs as $addr) {
				$addr->Default = 0;
				$addr->write();
			}
		}
	}
}
