<?php

/*
*	Addresses_OrderForm extends OrderForm
*/
class Addresses_OrderForm extends Extension {

 	public function ShippingAddressFields(){
		$shippingAddSession = self::SessionAddress('shipping');
		$currentUser = Customer::currentUser();

		$shippingAddressFields = CompositeField::create(
			HeaderField::create(_t('CheckoutPage.SHIPPING_ADDRESS',"Shipping Address"), 3),
			TextField::create('ShippingFirstName', _t('CheckoutPage.FIRSTNAME',"First Name"), $shippingAddSession ? $shippingAddSession->FirstName : '')
				->addExtraClass('shipping-firstname')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_FIRSTNAME',"Please enter a first name.")),
			TextField::create('ShippingSurname', _t('CheckoutPage.SURNAME',"Surname"), $shippingAddSession ? $shippingAddSession->Surname : '')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_SURNAME',"Please enter a surname.")),
			TextField::create('ShippingCompany', _t('CheckoutPage.COMPANY',"Company"), $shippingAddSession ? $shippingAddSession->Company : ''),
			TextField::create('ShippingAddress', _t('CheckoutPage.ADDRESS',"Address"), $shippingAddSession ? $shippingAddSession->Address : '')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_ADDRESS',"Please enter an address."))
				->addExtraClass('requiredField')->addExtraClass('address-break')
				->setAttribute('required', 'required'),
			TextField::create('ShippingAddressLine2', '&nbsp;', $shippingAddSession ? $shippingAddSession->AddressLine2 : ''),
			TextField::create('ShippingCity', _t('CheckoutPage.CITY',"City"), $shippingAddSession ? $shippingAddSession->City : '')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_CITY',"Please enter a city.")),
			TextField::create('ShippingPostalCode', _t('CheckoutPage.POSTAL_CODE',"Zip / Postal Code"), $shippingAddSession ? $shippingAddSession->PostalCode : ''),
			TextField::create('ShippingState', _t('CheckouanypotPage.STATE',"State / Province"), $shippingAddSession ? $shippingAddSession->State : '')
				->addExtraClass('address-break'),
            DropdownField::create('ShippingRegionCode',
                "Region", Region_Shipping::get()->map('Code', 'Title')->toArray()
            )->setValue($shippingAddSession ? $shippingAddSession->RegionCode : ''),
			DropdownField::create('ShippingCountryCode',
					_t('CheckoutPage.COUNTRY',"Country"),
					Country_Shipping::get()->map('Code', 'Title')->toArray()
				)
				->setCustomValidationMessage(_t('CheckoutPage.PLEASE_ENTER_COUNTRY',"Please enter a country."))
				->addExtraClass('country-code')->addExtraClass('requiredField')
				->setValue($shippingAddSession ? $shippingAddSession->CountryCode : ''),
			CheckboxField::create('ShippingDefault', 'Default shipping address?')
		)->setID('ShippingAddress')->setName('ShippingAddress');

		return $shippingAddressFields;
	}

	public function BillingAddressFields(){
		$shippingAddSession = self::SessionAddress('shipping');

		if(!self::SessionAddress('billing')){
			$billingAddSession = $shippingAddSession;
		} else {
			$billingAddSession = self::SessionAddress('billing');
		}

		$billingAddressFields = CompositeField::create(
			HeaderField::create(_t('CheckoutPage.BILLINGADDRESS',"Billing Address"), 3),
			TextField::create('BillingFirstName', _t('CheckoutPage.FIRSTNAME',"First Name"), $billingAddSession ? $billingAddSession->FirstName : '')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURFIRSTNAME',"Please enter your first name."))
				->addExtraClass('address-break')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required'),
			TextField::create('BillingSurname', _t('CheckoutPage.SURNAME',"Surname"), $billingAddSession ? $billingAddSession->Surname : '')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURSURNAME',"Please enter your surname.")),
			TextField::create('BillingCompany', _t('CheckoutPage.COMPANY',"Company"), $billingAddSession ? $billingAddSession->Company : ''),
			TextField::create('BillingAddress', _t('CheckoutPage.ADDRESS',"Address"), $billingAddSession ? $billingAddSession->Address : '')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURADDRESS',"Please enter your address."))
				->addExtraClass('address-break')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required'),
			TextField::create('BillingAddressLine2', '&nbsp;', $billingAddSession ? $billingAddSession->AddressLine2 : ''),
			TextField::create('BillingCity', _t('CheckoutPage.CITY',"City"), $billingAddSession ? $billingAddSession->City : '')
				->addExtraClass('requiredField')
				->setAttribute('required', 'required')
				->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURCITY',"Please enter your city")),
			TextField::create('BillingPostalCode', _t('CheckoutPage.POSTALCODE',"Zip / Postal Code"), $billingAddSession ? $billingAddSession->PostalCode : ''),
			TextField::create('BillingState', _t('CheckoutPage.STATE',"State / Province"), $billingAddSession ? $billingAddSession->State : '')
				->addExtraClass('address-break'),
		   //DropdownField::create('BillingRegionCode',
                //"Region", Region_Billing::get()->map('Code', 'Title')->toArray()
            //),
			DropdownField::create('BillingCountryCode',
					_t('CheckoutPage.COUNTRY',"Country"),
					Country_Billing::get()->map('Code', 'Title')->toArray()
				)->setCustomValidationMessage(_t('CheckoutPage.PLEASEENTERYOURCOUNTRY', "Please enter your country."))
				->setValue($billingAddSession ? $billingAddSession->CountryCode : ''),
			CheckboxField::create('BillingDefault', 'Default billing address?')
		)->setID('BillingAddress')->setName('BillingAddress');

		return $billingAddressFields;
	}

	public function updateFields($fields) {
		Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-entwine/dist/jquery.entwine-dist.js');
		Requirements::javascript(THIRDPARTY_DIR . '/jquery-validate/jquery.validate.min.js');
		Requirements::javascript('swipestripe-addresses/javascript/Addresses_OrderForm.js');

		$shippingAddSession = self::SessionAddress('shipping');

		$currentUser = Customer::currentUser();
		if($currentUser){
			if(!self::SessionAddress('billing')){
				$billingAddSession = $shippingAddSession;
			} else {
				$billingAddSession = self::SessionAddress('billing');
			}

			// Moved CompositeField generation to separate functions
			$shippingAddressFields = $this->owner->ShippingAddressFields();
			$billingAddressFields = $this->owner->BillingAddressFields();

			$defaultBilling = $currentUser->BillingAddresses()->filter(array('Default' => 1));
			if($billingAddSession && $shippingAddSession && $billingAddSession->ID != $shippingAddSession->ID || $defaultBilling->Count() > 0){
				$sameChecked = false;
			} else {
				$sameChecked = true;
			}

			$sameAsBilling = CompositeField::create(
				CheckboxField::create('BillToShippingAddress', _t('CheckoutPage.SAME_ADDRESS', "Same as shipping address?"))
					->addExtraClass('shipping-same-address')
					// Check made here instead of updatePopulateField()
					->setValue($sameChecked)
					->addExtraClass('left')
			)->setID('SameBillingAddress')->setName('SameBillingAddress');

			$fields->push($shippingAddressFields);
			$fields->push($billingAddressFields);
			$fields->push($sameAsBilling);
		}
	}

	public function updateValidator($validator) {
		/*
		$validator->appendRequiredFields(RequiredFields::create(
			'ShippingFirstName',
			'ShippingSurname',
			'ShippingAddress',
			'ShippingCity',
			'ShippingCountryCode',
			'BillingFirstName',
			'BillingSurname',
			'BillingAddress',
			'BillingCity',
			'BillingCountryCode'
		));
		*/
	}

	public function updatePopulateFields(&$data) {
		$member = Customer::currentUser() ? Customer::currentUser() : singleton('Customer');

		$shippingAddress = $member->ShippingAddress();
		$shippingAddressData = ($shippingAddress && $shippingAddress->exists()) ? $shippingAddress->getCheckoutFormData() : array();
		unset($shippingAddressData['ShippingRegionCode']); // Not available billing address option

		$billingAddress = $member->BillingAddress();
		$billingAddressData = ($billingAddress && $billingAddress->exists()) ? $billingAddress->getCheckoutFormData() : array();

		$data = array_merge(
			$data,
			$shippingAddressData,
			$billingAddressData
		);
	}

	public function getShippingAddressFields() {
		$fields = $this->owner->Fields()->fieldByName('ShippingAddress');
		return $fields;
	}

	// Return the form fields empty
	public function EmptyShippingAddressFields() {
		$fields = $this->owner->ShippingAddressFields();
		foreach($fields->FieldList()->dataFields() as $field){
			$field->setValue('');
		}
		return $fields;
	}

	public function getBillingAddressFields() {
		$fields = $this->owner->Fields()->fieldByName('BillingAddress');
		return $fields;
	}

	// Return the form fields empty
	public function EmptyBillingAddressFields() {
		$fields = $this->owner->BillingAddressFields();
		foreach($fields->FieldList()->dataFields() as $field){
			$field->setValue('');
		}
		return $fields;
	}

	public function getSameBillingAddressFields() {
		return $this->owner->Fields()->fieldByName('SameBillingAddress');
	}

	public function SessionAddress($type = 'shipping'){
		$customer = Customer::currentUser();
		$shippingAddress = ($customer && $customer->ShippingAddresses()) ? $customer->ShippingAddresses()->filter(array('Default' => 1))->first() : null;
		$billingAddress = ($customer && $customer->BillingAddresses()) ? $customer->BillingAddresses()->filter(array('Default' => 1))->first() : null;

		$ShippingID = Session::get('ShippingAddressID') ? Session::get('ShippingAddressID') : ($shippingAddress ? $shippingAddress->ID : false);
		$BillingID = Session::get('BillingAddressID') ? Session::get('BillingAddressID') : ($billingAddress ? $billingAddress->ID : false);
		$address = false;

		if($type == 'shipping' && $ShippingID){
			$address = Address_Shipping::get()->filter(array('ID' => $ShippingID))->first();
		} elseif($type == 'billing' && $BillingID){
			$address = Address_Billing::get()->filter(array('ID' => $BillingID))->first();
		}

		return $address ? $address : false;
	}
}
