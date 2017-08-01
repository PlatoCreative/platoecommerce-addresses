<?php

class Address_Billing extends Address {

	public function onBeforeWrite() {
		parent::onBeforeWrite();

		$code = $this->CountryCode;
		$country = Country_Billing::get()->filter(array('Code' => $code))->first();

		if ($country && $country->exists()) {
			$this->CountryName = $country->Title;
			$this->CountryID = $country->ID;
		}

		// Reset the default values
		if($this->Default){
			$customer = Member::currentUser();
			$addresses = $customer ? $customer->BillingAddresses()->filter(array('Default' => 1)) : null;
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

		$formattedData['BillingFirstName'] = $this->FirstName;
		$formattedData['BillingSurname'] = $this->Surname;
		$formattedData['BillingCompany'] = $this->Company;
		$formattedData['BillingAddress'] = $this->Address;
		$formattedData['BillingAddressLine2'] = $this->AddressLine2;
		$formattedData['BillingCity'] = $this->City;
		$formattedData['BillingPostalCode'] = $this->PostalCode;
		$formattedData['BillingState'] = $this->State;
		$formattedData['BillingCountryCode'] = $this->CountryCode;
		$formattedData['BillingDefault'] = $this->Default;

		return $formattedData;
	}
}
