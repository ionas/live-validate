<?php
	
class JqueryFormComponent extends Component {
	
	var $controller = null;
	var $components = array('RequestHandler');
	
	function startup(&$controller) {
		$this->controller = $controller;
		if ($this->RequestHandler->isAjax() && !empty($this->controller->data) && !empty($this->controller->data['validateme'])) {
			unset($this->controller->data['validateme']);
			$this->validate($this->controller->data);
		}
	}
		
	function validate($models) {
		$validated = array();
		foreach ($models as $model => $data) {
			$class = ClassRegistry::init($model);
			$class->set($data);
			$validated[$model] = $class->invalidFields();
		}
		
		$output = array();
		foreach($validated as $model => $data) {
			foreach ($data as $k => $d) {
				$output[] = array(
					'id' => $model.Inflector::camelize($k),
					'message' => $d
				);
			}
		}
		Configure::write('debug', 0);
		print json_encode($output);exit;
	}
	
}

?>