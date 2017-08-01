<?php

class Addresses_CountriesAdmin extends ShopAdmin {

	private static $tree_class = 'ShopConfig';

	private static $allowed_actions = array(
		'Countries',
		'CountriesForm'
	);

	private static $url_rule = 'ShopConfig/Countries';
	protected static $url_priority = 70;
	private static $menu_title = 'Shop Countries';

	private static $url_handlers = array(
		'ShopConfig/Countries/CountriesForm' => 'CountriesForm',
		'ShopConfig/Countries' => 'Countries'
	);

	public function init() {
		parent::init();
		if (!in_array(get_class($this), self::$hidden_sections)) {
			$this->modelClass = 'ShopConfig';
		}
	}

	public function Breadcrumbs($unlinked = false) {
		$request = $this->getRequest();
		$items = parent::Breadcrumbs($unlinked);

		if ($items->count() > 1){
			$items->remove($items->pop());
		}

		$items->push(new ArrayData(array(
			'Title' => 'Countries',
			'Link' => $this->Link(Controller::join_links($this->sanitiseClassName($this->modelClass), 'Countries'))
		)));

		return $items;
	}

	public function SettingsForm($request = null) {
		return $this->CountriesForm();
	}

	public function Countries($request) {
		if ($request->isAjax()) {
			$controller = $this;
			$responseNegotiator = new PjaxResponseNegotiator(
				array(
					'CurrentForm' => function() use(&$controller) {
						return $controller->CountriesForm()->forTemplate();
					},
					'Content' => function() use(&$controller) {
						return $controller->renderWith('ShopAdminSettings_Content');
					},
					'Breadcrumbs' => function() use (&$controller) {
						return $controller->renderWith('CMSBreadcrumbs');
					},
					'default' => function() use(&$controller) {
						return $controller->renderWith($controller->getViewer('show'));
					}
				),
				$this->response
			);
			return $responseNegotiator->respond($this->getRequest());
		}

		return $this->renderWith('ShopAdminSettings');
	}

	public function CountriesForm() {
		$shopConfig = ShopConfig::get()->First();

		$fields = new FieldList(
			$rootTab = new TabSet("Root",
				$tabMain = new Tab('Shipping',
					new HiddenField('ShopConfigSection', null, 'Countries'),
					new GridField(
						'ShippingCountries',
						'Shipping Countries',
						$shopConfig->ShippingCountries(),
						GridFieldConfig_RecordEditor::create()
							->removeComponentsByType('GridFieldFilterHeader')
							->removeComponentsByType('GridFieldAddExistingAutocompleter')
					)
				),
				new Tab('Billing',
					new GridField(
						'BillingCountries',
						'Billing Countries',
						$shopConfig->BillingCountries(),
						GridFieldConfig_RecordEditor::create()
							->removeComponentsByType('GridFieldFilterHeader')
							->removeComponentsByType('GridFieldAddExistingAutocompleter')
					)
				)
			)
		);

		$actions = new FieldList();

		$form = new Form(
			$this,
			'EditForm',
			$fields,
			$actions
		);

		$form->setTemplate('ShopAdminSettings_EditForm');
		$form->setAttribute('data-pjax-fragment', 'CurrentForm');
		$form->addExtraClass('cms-content cms-edit-form center ss-tabset');
		if($form->Fields()->hasTabset()){
			$form->Fields()->findOrMakeTab('Root')->setTemplate('CMSTabSet');
		}
		$form->setFormAction(Controller::join_links($this->Link($this->sanitiseClassName($this->modelClass)), 'Countries/CountriesForm'));

		$form->loadDataFrom($shopConfig);

		return $form;
	}

	public function getSnippet() {
		if (!$member = Customer::currentUser()){
			return false;
		}
		if (!Permission::check('CMS_ACCESS_' . get_class($this), 'any', $member)){
			return false;
		}

		return $this->customise(array(
			'Title' => 'Countries and Regions',
			'Help' => 'Shipping and billing countries and regions.',
			'Link' => Controller::join_links($this->Link('ShopConfig'), 'Countries'),
			'LinkTitle' => 'Edit Countries and Regions'
		))->renderWith('ShopAdmin_Snippet');
	}
}
