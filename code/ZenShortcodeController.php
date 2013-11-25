<?php


class ZenShortcodeController extends LeftAndMain {

	private static $allowed_actions = array(
		'ZenShortcodeForm',
		'doZenShortcode'
	);

	/**
	 * Helper to get all subclasses of zenshortcode
	 * @return [type] [description]
	 */
	public function getZenShortcodeSubClasses() {
		$subClasses = array_values(ClassInfo::subclassesFor('ZenShortcode'));
		$subClasses = array_diff($subClasses, array('ZenShortcode'));
		return $subClasses;
	}

	/**
	 * Return the zenshortcode form
	 */
	public function ZenShortcodeForm() {

		// Get dataobject if editing a shortcode
		$data = array();
		$do = null;
		if($this->request->getVar('content')) {
			$content = $this->request->getVar('content');
			$parser = Injector::inst()->get('ZenShortcodeParser', true);
			$tags = $parser->getTags($content);
			if(isset($tags[0]) && isset($tags[0]['attrs']) ) {
				$data = $tags[0]['attrs'];
			} 
			$do = ZenShortcode::get()->ByID(Convert::raw2sql($data['id']));
		} 

		$fields = new FieldList();

		// Main heading
		$headings = new CompositeField(
			new LiteralField('Heading', '<h3>Insert Zen Shortcode</h3>')
		);
		$headings->addExtraClass('cms-content-header');
		$fields->push($headings);

		// Get the current Page ID
		$pageID = HiddenField::create('PageID')->setValue($this->getRequest()->requestVar('pageID'));
		$fields->push($pageID);	

		// Get subclasses of ZenShortcode for the selection 
		$subClasses = $this->getZenShortcodeSubClasses();

		// If not editing (no dataobject) then show a selection of shortcodes
		// and add each shortcode fieldlist with namespaced fields to the form
		if(!$do) {

			$typeOptions = array();
			foreach($subClasses as $subClass) {
				$class = Injector::inst()->get($subClass, true);
				$typeOptions[get_class($class)] = $class::$shortcode_name;
			}

			$type = DropdownField::create('ZenShortcodeType', 'Select the type of shortcode to insert')
				->setSource($typeOptions)
				->setEmptyString('-- Select --')
				->addExtraClass('zenshortcode_select');
			
			$fields->push($type);
		
			foreach($subClasses as $subClass) {
				$class = Injector::inst()->get($subClass, true);
				$classFields = $class->getCMSFields();
				
				// namespace the fields: TODO
				// foreach($classFields as $field) {
				// 	$field->setName($subClass.'__'.$field->getName());
				// }

				$group = FieldGroup::create($subClass, $classFields)
					->setName($subClass)
					->addExtraClass('ZenShortcodeGroup ZenShortcodeGroupHidden '.$subClass)
					->setAttribute('data-zenshortcode-type', $subClass);
				
				$fields->push($group);

			}

		// If editing a zenshortcode get the object to populate the form with
		// only use the required form (ie users cannot change a shortcode type)
		} else {

			$id = HiddenField::create('ID')->setValue($do->ID);
			$fields->push($id);	

			$classFields = $do->getCMSFields();

			$group = FieldGroup::create($do->ClassName, $classFields);

			$group->setName($do->ClassName);
			$group->addExtraClass('ZenShortcodeGroup '.$do->ClassName);
			$group->setAttribute('data-zenshortcode-type', $do->ClassName);
			$fields->push($group);	

		}

		$label = ($do) ? 'Update' : 'Insert';
		$actions = new FieldList(
			FormAction::create('doZenShortcode', $label)
				->addExtraClass('ss-ui-action-constructive zenshortcode-select')
				->setAttribute('data-icon', 'accept')
				->setUseButtonTag(true)
		);

		$form = new Form(
			$this,
			"/ZenShortcodeController/ZenShortcodeForm",
			$fields,
			$actions
		);

		$form->setFormAction("/ZenShortcodeController/ZenShortcodeForm");

		if($do) {


			
			$form->loadDataFrom($do, 'CLEAR_MISSING');

			// Namespace the fields: TODO
			// foreach($classFields as $field) {
			// 	$field->setName($do->ClassName.'__'.$field->getName());
			// }

		}

		$form->unsetValidator();
		$form->addExtraClass('htmleditorfield-form htmleditorfield-zenshortcodeform cms-dialog-content');

		return $form;

	}

	/**
	 * save ths zenshortcode object
	 * @param  array $data 
	 * @param  Form $form
	 * @return json string of the shortcode model
	 *
	 * TODO proper error message handling
	 */
	public function doZenShortcode($data, Form $form) {

		if(isset($data['ID'])) {
			
			$do = ZenShortcode::get()->ByID(Convert::raw2sql($data['ID']));

			if(!$do->exists()) {
				$message = "Could not load saved shortcode";
				echo $message;
				exit;
			}

			$class = $do->ClassName;

		} else {

			$class = $data['ZenShortcodeType'];
			
			if(!class_exists($class)) {
				$message = "Shortcode $class not found";
				echo $message;
				exit;
			}

			$do = new $class();

			if(!is_subclass_of($do, 'ZenShortcode')) {
				$message = "$class is not a zen shortcode";
				echo $message;
				exit;
			}

		}

		// Un-namespace the fields: TODO
		// foreach($form->Fields() as $composite) {
		// 	//if($composite->isComposite()) {
		// 		foreach($composite->FieldList() as $field) {
		// 			if (strpos($field->getName(), $type.'__') !== false) {
		// 				 $field->setName(str_replace($type.'__', '', $field->getName()));
		// 			}
		// 		}
		// 	//}
		// }
		
		// only save the fields associated with theis object
		$fields = array_keys(DataObject::database_fields($class));

		// add in relations..  TODO
		foreach($data as $k => $v) {
			if($do->hasMethod($k)) {
				$fields[] = $k;
			}
		}

		$form->saveInto($do, $fields);

		$do->write();

		echo json_encode($do->toMap());
		exit;

	}

}
