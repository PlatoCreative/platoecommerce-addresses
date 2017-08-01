<?php

/**
 * Shipping regions
 *
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2012, Frank Mullenger
 * @package swipestripe
 * @subpackage order
 */
class Region_Shipping extends Region {

	/**
	 * Fields for CRUD of shipping regions
	 *
	 * @see DataObject::getCMSFields()
	 */
	public function getCMSFields() {

		// $fields = new FieldList(
		//   $rootTab = new TabSet('Root',
		//     $tabMain = new Tab('Region',
		//       TextField::create('Code', _t('Region.CODE', 'Code')),
		//       TextField::create('Title', _t('Region.TITLE', 'Title')),
		//       DropdownField::create('CountryID', 'Country', Country_Shipping::get()->map()->toArray())
		//     )
		//   )
		// );
		// return $fields;

		$fields = parent::getCMSFields();
		$fields->replaceField('CountryID', DropdownField::create('CountryID', 'Country', Country_Shipping::get()->map()->toArray()));
		$fields->removeByName('SortOrder');
		$fields->removeByName('ShopConfigID');
		return $fields;
	}
}
