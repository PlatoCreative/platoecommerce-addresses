<?php

/*
*	Addresses_Customer extends Customer
*/
class Addresses_Customer extends DataExtension {
	private static $has_many = array(
		'ShippingAddresses' => 'Address_Shipping',
		'BillingAddresses' => 'Address_Billing'
	);

	public function createAddresses($order) {
		Session::clear('ShippingAddressID');
		Session::clear('BillingAddressID');
	}

	/**
	 * Retrieve the last used billing address for this Member from their previous saved addresses.
	 * TODO make this more efficient
	 *
	 * @return Address The last billing address
	 */
	public function BillingAddress($addressID = null) {
		$addrs = $this->owner->BillingAddresses();
		if($addrs && $addrs->exists()) {
			if($addressID > 0){
				return $addrs->filter(array('ID' => $addressID))->first();
			} else {
				return $addrs->filter(array('Default' => 1))->first();
			}
		}
		return null;
	}

	/**
	 * Retrieve the last used shipping address for this Member from their previous saved addresses.
	 * TODO make this more efficient
	 *
	 * @return Address The last shipping address
	 */
	public function ShippingAddress($addressID = null) {
		$addrs = $this->owner->ShippingAddresses();
		if ($addrs && $addrs->exists()) {
			if ($addressID > 0){
				return $addrs->filter(array('ID' => $addressID))->first();
			} else {
				return $addrs->filter(array('Default' => 1))->first();
			}
		}
		return null;
	}

	public function DefaultShippingAddress(){
		return $this->owner->ShippingAddresses()->filter(array('Default' => 1))->first();
	}

	public function DefaultBillingAddress(){
		return $this->owner->BillingAddresses()->filter(array('Default' => 1))->first();
	}
}
