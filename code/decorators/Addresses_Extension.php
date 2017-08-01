<?php

/*
*	Addresses_Extension extends ShopConfig
*/
class Addresses_Extension extends DataExtension {
	private static $has_many = array(
		'ShippingCountries' => 'Country_Shipping',
		'BillingCountries' => 'Country_Billing',
		'ShippingRegions' => 'Region_Shipping',
		'BillingRegions' => 'Region_Billing'
	);
}
