<?php

/**
 * Shipping country
 *
 * @author Frank Mullenger <frankmullenger@gmail.com>
 * @copyright Copyright (c) 2012, Frank Mullenger
 * @package swipestripe
 * @subpackage order
 */
class Country_Shipping extends Country {

	public function getCMSFields() {

		$fields = new FieldList(
			$rootTab = new TabSet('Root',
				$tabMain = new Tab('Country',
					TextField::create('Code', _t('Country.CODE', 'Code')),
					TextField::create('Title', _t('Country.TITLE', 'Title'))
				)
			)
		);

		if ($this->isInDB()) {

			$config = GridFieldConfig_BasicSortable::create();
			// $detailForm = $config->getComponentByType('GridFieldDetailForm');
			// $detailForm->setItemRequestClass('GridFieldDetailForm_HasManyItemRequest');

			$listField = new GridField(
				'Regions',
				'Regions',
				$this->Regions(),
				$config
			);

			$fields->addFieldToTab('Root.Regions', $listField);
		}

		return $fields;
	}

	public function Regions() {
		return Region_Shipping::get()
			->where("\"CountryID\" = " . $this->ID);
	}
}
