<?php
class Ves_Contentslider_Block_Widget_Slider extends Ves_Contentslider_Block_List implements Mage_Widget_Block_Interface
{
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
	 	parent::__construct($attributes);
		
	}

	public function _toHtml() {
		$this->_show = $this->getConfig("show");
		
		if(!$this->_show) return;
		//Override Config
		if(isset($this->_config) && $this->_config && is_array( $this->_config)) {
			foreach($this->_config as $key=>$val) {
				if($this->hasData($key)) {
					$this->setConfig($key, $this->getData($key));
				}
			}
		}

		if($this->hasData("template")) {
			$this->setTemplate($this->getData("template"));
		} else {
			$this->setTemplate("ves/contentslider/default.phtml");	
		}

        return parent::_toHtml();
	}
}