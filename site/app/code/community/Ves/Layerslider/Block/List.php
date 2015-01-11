<?php
/*------------------------------------------------------------------------
 # VenusTheme Layer slider Module 
 # ------------------------------------------------------------------------
 # author:    VenusTheme.Com
 # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
 # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 # Websites: http://www.venustheme.com
 # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Layerslider_Block_List extends Mage_Core_Block_Template 
{
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_config = '';
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_listDesc = array();
	
	/**
	 * @var string $_config
	 * 
	 * @access protected
	 */
	protected $_show = 0;
	protected $_theme = "";

	protected $_banner = null;
	
	/**
	 * Contructor
	 */
	public function __construct($attributes = array())
	{	
		$this->_show = $this->getConfig("show");
 		
		if(!$this->_show) return;
		parent::__construct($attributes);
		/*End init meida files*/
		$banner_id = $this->getConfig("bannerId");
		$banner_id = $banner_id?$banner_id:0;
		$this->_banner  = Mage::getModel('ves_layerslider/banner')->load( $banner_id );

		$this->_show = $this->getConfig("show");
 		$banner  = $this->getSliderBanner();
		if(!$this->_show || empty($banner)) return;

		$is_flexslider = $this->getConfig("is_flexslider");

		$is_active =  $banner->getData("is_active");

		if($is_active) {
			$banners = array();
			$setting = array();
			$params = $banner->getData("params");
			$params = unserialize(base64_decode($params) );

			$options = $banner->getData("options");
			$setting = unserialize($options);
			$setting['width'] = isset($setting['width'])?$setting['width']:1070;
			$setting['height'] = isset($setting['height'])?$setting['height']:460;

			if($params) {
				foreach($params as $key => $slider) {
					if(strpos($key, "slide-container-") !== false && $slider) {
						if(isset($slider['type']) && $slider['type'] == 'image' && $slider['src']) {
							$slider['src'] = Mage::helper("ves_layerslider")->getImage( $slider['src'] );
						}
						$banners[] = $slider;
						
					}
				}
				
				$setting['general'] = isset($params['bg'])?$params['bg']:array();

				if(isset($setting['general']['src']) && $setting['general']['src']) {
					$setting['general']['src'] = Mage::helper("ves_layerslider")->getImage( $setting['general']['src'] );
				}
				$setting['width'] = isset($params['ss']['width'])?(int)$params['ss']['width']:$setting['width'];
				$setting['height'] = isset($params['ss']['height'])?(int)$params['ss']['height']:$setting['height'];
			}

			$this->assign("sliderParams", $setting);
			$this->assign("setting", $setting);
			$this->assign("params", $params);
			$this->assign("banners", $banners);

			if($this->hasData("template")) {
	        	$my_template = $this->getData("template");
	        }else{
	 			$my_template = "ves/layerslider/default.phtml";
	 		}
 			$this->setTemplate($my_template);
		}

			
	}

	public function getSliderBanner() {
		return $this->_banner;
	}
	/**
     * Rendering block content
     *
     * @return string
     */
	function _toHtml() 
	{
		return parent::_toHtml();
    }

	public function getConfig( $val ){ 
		return Mage::getStoreConfig( "ves_layerslider/general_setting/".$val );
	}

	public function renderBannerElements( $banners = array(), $options = array()) {
		$html = Mage::helper("ves_layerslider/slider")->renderBannerElements( $banners, $options );
		return $html;
	}
}
