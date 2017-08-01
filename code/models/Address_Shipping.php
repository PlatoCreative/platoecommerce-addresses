<?php

class Address_Shipping extends Address {

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$code = $this->CountryCode;
		$country = Country_Shipping::get()->filter(array('Code' => $code))->first();

		if ($country && $country->exists()) {
			$this->CountryName = $country->Title;
			$this->CountryID = $country->ID;
		}

		$code = $this->RegionCode;
		$region = Region_Shipping::get()->filter(array('Code' => $code))->first();

		if ($region && $region->exists()) {
			$this->RegionName = $region->Title;
			$this->RegionID = $region->ID;
		}

		// Reset the default values
		if($this->Default){
			$customer = Member::currentUser();
			$addresses = $customer ? $customer->ShippingAddresses()->filter(array('Default' => 1)) : null;
			foreach($addresses as $address){
				$address->Default = 0;
				$address->write();
			}
			$this->Default = 1;
		}
	}

	/**
	 * Return data in an Array with keys formatted to match the field names
	 * on the checkout form so that it can be loaded into an order form.
	 *
	 * @see Form::loadDataFrom()
	 * @return Array Data for loading into the form
	 */
	public function getCheckoutFormData() {
		$formattedData = array();

		$formattedData['ShippingFirstName'] = $this->FirstName;
		$formattedData['ShippingSurname'] = $this->Surname;
		$formattedData['ShippingCompany'] = $this->Company;
		$formattedData['ShippingAddress'] = $this->Address;
		$formattedData['ShippingAddressLine2'] = $this->AddressLine2;
		$formattedData['ShippingCity'] = $this->City;
		$formattedData['ShippingPostalCode'] = $this->PostalCode;
		$formattedData['ShippingState'] = $this->State;
		$formattedData['ShippingCountryCode'] = $this->CountryCode;
		$formattedData['ShippingRegionCode'] = $this->RegionCode;
		$formattedData['ShippingDefault'] = $this->Default;

		return $formattedData;
	}
}
