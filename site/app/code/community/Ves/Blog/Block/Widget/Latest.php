<?php
class Ves_Blog_Block_Widget_Latest extends Ves_Blog_Block_Latest implements Mage_Widget_Block_Interface
{

	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{
		if(is_array($attributes)) {
			$attributes['block_type'] = "widget_latest";
		}

		parent::__construct( $attributes );

		$my_template = "";
        if(isset($attributes['template']) && $attributes['template']) {
            $my_template = $attributes['template'];
        }elseif($this->hasData("template")) {
        	$my_template = $this->getData("template");
        }else {
 			$my_template = "ves/blog/block/latest.phtml";
 		}

        $this->setTemplate($my_template);
	}
	
	public function _toHtml(){
		return parent::_toHtml();
	}
}