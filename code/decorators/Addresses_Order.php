<?php
/*
*	Addresses_Order extends Order
*/
class Addresses_Order extends DataExtension {

	private static $db = array(
		//Address fields
		'ShippingFirstName' => 'Varchar',
		'ShippingSurname' => 'Varchar',
		'ShippingCompany' => 'Varchar',
		'ShippingAddress' => 'Varchar(255)',
		'ShippingAddressLine2' => 'Varchar(255)',
		'ShippingCity' => 'Varchar(100)',
		'ShippingPostalCode' => 'Varchar(30)',
		'ShippingState' => 'Varchar(100)',
		'ShippingCountryName' => 'Varchar',
		'ShippingCountryCode' => 'Varchar(2)', //ISO 3166
		'ShippingRegionName' => 'Varchar',
		'ShippingRegionCode' => 'Varchar(2)',

		'BillingFirstName' => 'Varchar',
		'BillingSurname' => 'Varchar',
		'BillingCompany' => 'Varchar',
		'BillingAddress' => 'Varchar(255)',
		'BillingAddressLine2' => 'Varchar(255)',
		'BillingCity' => 'Varchar(100)',
		'BillingPostalCode' => 'Varchar(30)',
		'BillingState' => 'Varchar(100)',
		'BillingCountryName' => 'Varchar',
		'BillingCountryCode' => 'Varchar(2)', //ISO 3166
		'BillingRegionName' => 'Varchar',
		'BillingRegionCode' => 'Varchar(2)'
	);

	public function onBeforeWrite() {
		//Update address names
		$country = Country_Shipping::get()->filter(array('Code' => $this->owner->ShippingCountryCode))->first();
		if ($country && $country->exists()){
			$this->owner->ShippingCountryName = $country->Title;
		}

		$region = Region_Shipping::get()->filter(array('Code' => $this->owner->ShippingRegionCode))->first();
		if ($region && $region->exists()){
			$this->owner->ShippingRegionName = $region->Title;
		}

		$country = Country_Billing::get()->filter(array('Code' => $this->owner->BillingCountryCode))->first();
		if ($country && $country->exists()){
			$this->owner->BillingCountryName = $country->Title;
		}
	}

	public function onBeforePayment() {
		//Save the addresses to the Customer
		$customer = $this->owner->Member();
		if ($customer && $customer->exists()) {
			$customer->createAddresses($this->owner);
		}
	}
}
