<?php
 /*------------------------------------------------------------------------
  # VenusTheme Brand Module 
  # ------------------------------------------------------------------------
  # author:    VenusTheme.Com
  # copyright: Copyright (C) 2012 http://www.venustheme.com. All Rights Reserved.
  # @license: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
  # Websites: http://www.venustheme.com
  # Technical Support:  http://www.venustheme.com/
-------------------------------------------------------------------------*/
class Ves_Layerslider_Helper_Slider extends Mage_Core_Helper_Abstract {
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
	
	public function resizeImage( $image, $width, $height ){
		$image = str_replace("ves_layerslider/upload/", "", $image);
		$image= str_replace("/",DS, $image);
		$_imageUrl = Mage::helper("ves_layerslider")->getImageBaseDir().$image;
		$imageResized = Mage::getBaseDir('media').DS."resized".DS."{$width}x{$height}".DS.$image;
		$quality = $this->getConfig("resize_quality");
		
		if (!file_exists($imageResized) && file_exists($_imageUrl)) {
			$imageObj = new Varien_Image($_imageUrl);

			if($quality) {
		    	$imageObj->quality($quality);
		    } else {
		    	$imageObj->quality(100);
			}
			$imageObj = new Varien_Image($_imageUrl);
			$imageObj->constrainOnly(true);
			$imageObj->keepAspectRatio(true);
			$imageObj->keepFrame(false);
			$imageObj->keepTransparency(true);
			$imageObj->resize( $width, $height);
			$imageObj->save($imageResized);
			
		}
		return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'resized/'."{$width}x{$height}/".str_replace(DS,"/",$image);
	}

	public function getConfig( $val ){ 
		return Mage::getStoreConfig( "ves_layerslider/general_setting/".$val );
	}
	
	public function renderBannerElements( $banners = array(), $options = array() ) {
		$html = "";

		if($banners){
			$slider_data = array();
			if(isset($banners['slide'])) {
				$slider_data = $banners['slide'];
				unset($banners['slide']);
				/*If slider was disable, it don't show up on slideshow*/
				if(isset($slider_data['slider_status']) && !$slider_data['slider_status'])
					return "";

				$link = isset($slider_data['slider_link']) && $slider_data['slider_link'] ?' data-link="'.$slider_data['slider_link'].'"':'';
				$sliderDelay = (isset($slider_data['slider_delay']) && (int)$slider_data['slider_delay'])?'data-delay="'.(int)$slider_data['slider_delay'].'"':"";
				$lazyLoad = isset($options['lazy_load'])?$options['lazy_load']:0;
				$sliderDuration = isset($slider_data['slider_duration'])?$slider_data['slider_duration']:0;
				$sliderTransition = isset($slider_data['slider_transition'])?$slider_data['slider_transition']:0;
				$sliderSlot = isset($slider_data['slider_slot'])?$slider_data['slider_slot']:0;

				$slider_data['slider_videoid'] = isset($slider_data['slider_videoid'])?$slider_data['slider_videoid']:"";

				$main_image = isset($slider_data['main_image'])?$slider_data['main_image']:"";

				if(!$main_image) {
					/*Get main Slider Image*/
					$tmp_images = array();
					foreach($banners as $key=>$banner) {
						$item_data = isset($banner['itemData'])?$banner['itemData']:array();
						$ignore = (isset($item_data['ignore']) && $item_data['ignore'])?true:false;
						if($item_data['type'] == "image") {
							$tmp_images[] = $item_data['src'];
						}
						if($item_data['type'] == "image" && $ignore) {
							$main_image = $item_data['src'];
							break;
						}

					}
					if(!$main_image && $tmp_images) {
						$main_image = $tmp_images[0];
					}

				}
				if(!$main_image) 
					return "";

				$thumbnail = $main_image;
				
				$base_layerslider_url = Mage::helper("ves_layerslider")->getImageBaseUrl();
				$base_layerslider_url = str_replace("/upload/","/", $base_layerslider_url);
				$dummy_image = $base_layerslider_url."icons/dummy.png";

				if( $main_image && isset($options['image_cropping']) && $options['image_cropping']) { 
					$main_image = $this->resizeImage($main_image, $options['width'], $options['height']);
				} else {
					$main_image = Mage::helper("ves_layerslider")->getImage( $main_image );
				}

				if( isset($options['thumbnail_cropping']) && $options['thumbnail_cropping'] ) {
					$thumbnail = $this->resizeImage($thumbnail, $options['thumbnail_width'], $options['thumbnail_height']);
				} else {
					$thumbnail = Mage::helper("ves_layerslider")->getImage( $thumbnail );
				}
				

				$html .= "\n";
				$html .= '<li '.$link.' ' .$sliderDelay.' data-masterspeed="'.$sliderDuration.'" data-transition="'.$sliderTransition.'" data-slotamount="'.$sliderSlot.'" data-thumb="'.$thumbnail.'">';

				$html .= "\n";

				if($main_image) {
					if($lazyLoad) {
						$html .= '<img src="'.$dummy_image.'" data-lazyload="'.$main_image.'"  alt="Image" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat"/>';
					} else {
						$html .= '<img src="'.$main_image.'"  alt="Image" data-bgfit="cover" data-bgposition="left top" data-bgrepeat="no-repeat"/>';
					}
					
				}
				if( isset($slider_data['slider_usevideo']) && ($slider_data['slider_usevideo'] == 'youtube' || $slider_data['slider_usevideo'] == 'vimeo' ) && $slider_data['slider_videoid'] ) {

					$vurl  = '//player.vimeo.com/video/'.$slider_data['slider_videoid'].'/';
					if(  $slider_data['slider_usevideo'] == 'youtube' ){
					 	$vurl  = '//www.youtube.com/embed/'.$slider_data['slider_videoid'].'/';
					}
					$html .= '<div class="caption fade fullscreenvideo" data-autoplay="true" data-autoplayonlyfirsttime="false" data-x="0" data-y="0" data-speed="700" data-start="500" data-easing="Power4.easeInOut" data-endspeed="700"><iframe src="'.$vurl.'?title=0&amp;byline=0&amp;portrait=0;api=1" width="100%" height="100%"></iframe></div>';
				}

				$html .= "\n";

				/*Render layers*/
				foreach($banners as $key=>$banner) {
					
					$html .= $this->renderBannerElement( $banner, $options );

				}
				$html .= "\n";
				$html .= '</li>';
			}
			
		}
		return $html;
	}
	public function renderBannerElement( $banner = array(), $options = array() ) {
		$item_data = isset($banner['itemData'])?$banner['itemData']:array();
		

		//Duration in/out
		$startAnimation = isset($item_data['in'])?$item_data['in']:array();
		$endAnimation = isset($item_data['out'])?$item_data['out']:array();


		$startAnimation['from'] = isset($startAnimation['from'])?$startAnimation['from']:"auto";
		$startAnimation['use'] = isset($startAnimation['use'])?$startAnimation['use']:"easeOutExpo";

		if(  $startAnimation['from'] == 'auto' ){
			$startAnimation['from'] = '' ;
		}

		$endAnimation['to'] = isset($endAnimation['to'])?$endAnimation['to']:"auto";
		$endAnimation['use'] = isset($endAnimation['use'])?$endAnimation['use']:"easeOutExpo";


		$endeffect = '';
		if(  $endAnimation['to'] == 'auto' ){
			$endAnimation['to'] = '' ;
		}
		if( isset($endAnimation['at']) && (int)$endAnimation['at'] ){
			$endeffect = ' data-end="'.(int)$endAnimation['at'].'"';
			$endeffect .= ' data-endspeed="'.(isset($endAnimation['during'])?$endAnimation['during']:"1000").'"';
			if( $endAnimation['use'] != 'nothing') {
				$endeffect .= ' data-endeasing="'.$endAnimation['use'].'" ';	
			}
		}else {
			$endAnimation['to'] = '' ;
		}

		//Element Setting
		//In
		$splitIn = isset($startAnimation['split'])?$startAnimation['split']:'none';
		$splitIn = !$splitIn?'none':$splitIn;
		$speedIn = isset($startAnimation['speed']) && $startAnimation['speed']?(float)$startAnimation['speed']:100;
		$speedIn = $speedIn / 1000;
		$customin = "";
		if($startAnimation['from'] == "customin") {
			$transitionx = isset($startAnimation['transitionx'])?$startAnimation['transitionx']:50;
			$transitiony = isset($startAnimation['transitiony'])?$startAnimation['transitiony']:150;
			$transitionz = isset($startAnimation['transitionz'])?$startAnimation['transitionz']:0;
			$rotationx = isset($startAnimation['rotationx'])?$startAnimation['rotationx']:0;
			$rotationy = isset($startAnimation['rotationy'])?$startAnimation['rotationy']:0;
			$rotationz = isset($startAnimation['rotationz'])?$startAnimation['rotationz']:0;
			$scalex = isset($startAnimation['scalex'])?(float)$startAnimation['scalex']:0;
			$scalex = $scalex / 100;
			$scaley = isset($startAnimation['scaley'])?(float)$startAnimation['scaley']:0;
			$scaley = $scaley / 100;
			$skewx = isset($startAnimation['skewx'])?(float)$startAnimation['skewx']:0;
			$skewx = $skewx / 100;
			$skewy = isset($startAnimation['skewy'])?(float)$startAnimation['skewy']:0;
			$skewy = $skewy / 100;
			$opacity = isset($startAnimation['opacity'])?(float)$startAnimation['opacity']:0;
			$opacity = $opacity / 100;
			$perspective = isset($startAnimation['perspective'])?$startAnimation['perspective']:0;
			$originx = isset($startAnimation['originx'])?(float)$startAnimation['originx']:0;
			$originy = isset($startAnimation['originy'])?(float)$startAnimation['originy']:0;

			$customin = 'data-customin="x:'.(int)$transitionx.';y:'.(int)$transitiony.';z:'.(int)$transitionz.';rotationX:'.(int)$rotationx.';rotationY:'.(int)$rotationy.';rotationZ:'.(int)$rotationz.';scaleX:'.$scalex.';scaleY:'.$scaley.';skewX:'.$skewx.';skewY:'.$skewx.';opacity:'.$opacity.';transformPerspective:'.$perspective.';transformOrigin:'.$originx.'% '.$originy.'%;"';
		}
		
		//Out
		$splitOut = isset($endAnimation['split'])?$endAnimation['split']:'none';
		$splitOut = !$splitOut?'none':$splitOut;
		$speedOut = isset($endAnimation['speed']) && $endAnimation['speed']?(float)$endAnimation['speed']:100;
		$speedOut = $speedOut / 1000;
		$customout = "";
		if($endAnimation['from'] == "customout") {
			$transitionx = isset($startAnimation['transitionx'])?$startAnimation['transitionx']:50;
			$transitiony = isset($startAnimation['transitiony'])?$startAnimation['transitiony']:150;
			$transitionz = isset($startAnimation['transitionz'])?$startAnimation['transitionz']:0;
			$rotationx = isset($startAnimation['rotationx'])?$startAnimation['rotationx']:0;
			$rotationy = isset($startAnimation['rotationy'])?$startAnimation['rotationy']:0;
			$rotationz = isset($startAnimation['rotationz'])?$startAnimation['rotationz']:0;
			$scalex = isset($startAnimation['scalex'])?(float)$startAnimation['scalex']:0;
			$scalex = $scalex / 100;
			$scaley = isset($startAnimation['scaley'])?(float)$startAnimation['scaley']:0;
			$scaley = $scaley / 100;
			$skewx = isset($startAnimation['skewx'])?(float)$startAnimation['skewx']:0;
			$skewx = $skewx / 100;
			$skewy = isset($startAnimation['skewy'])?(float)$startAnimation['skewy']:0;
			$skewy = $skewy / 100;
			$opacity = isset($startAnimation['opacity'])?(float)$startAnimation['opacity']:0;
			$opacity = $opacity / 100;
			$perspective = isset($startAnimation['perspective'])?$startAnimation['perspective']:0;
			$originx = isset($startAnimation['originx'])?(float)$startAnimation['originx']:0;
			$originy = isset($startAnimation['originy'])?(float)$startAnimation['originy']:0;

			$customout = 'data-customout="x:'.(int)$transitionx.';y:'.(int)$transitiony.';z:'.(int)$transitionz.';rotationX:'.(int)$rotationx.';rotationY:'.(int)$rotationy.';rotationZ:'.(int)$rotationz.';scaleX:'.$scalex.';scaleY:'.$scaley.';skewX:'.$skewx.';skewY:'.$skewx.';opacity:'.$opacity.';transformPerspective:'.$perspective.';transformOrigin:'.$originx.'% '.$originy.'%;"';
		}
		

		//Basic config
		$top = isset($item_data['top'])?$item_data['top']:'0';
		$left = isset($item_data['left'])?$item_data['left']:'0';
		$clase = isset($item_data['clase'])?$item_data['clase']:"";
		$customclase = isset($item_data['customclase'])?$item_data['customclase']:"";
		$style = isset($item_data['style'])?$item_data['style']:"";
		$content = isset($item_data['content'])?$item_data['content']:"";
		$href = isset($item_data['href'])?$item_data['href']:"";
		$target = isset($item_data['target'])?$item_data['target']:"";
		$opacity = isset($item_data['opacity'])?$item_data['opacity']:"";
		$videosrc = isset($item_data['videosrc'])?$item_data['videosrc']:"";
		$videotype = isset($item_data['videotype'])?$item_data['videotype']:"youtube";
		$videoid = isset($item_data['videoid'])?$item_data['videoid']:"";

		$base_dir = Mage::helper("ves_layerslider")->getImageBaseDir();

		if($style ) {
			$style = htmlspecialchars($style);
			$style = str_replace('"','\"', $style);
		}
				
		$html = "";
		if($item_data) {
			$ignore = (isset($item_data['ignore']) && $item_data['ignore'])?true:false;
			if(!$ignore) {
				$custom_style = "";
				if($$item_data['type'] == "text" && ($splitIn != "none" || $splitOut != "none") ) {
					$custom_style = ' style="'.$style.'"';
				}
				$html .= '<div class="caption '.$clase.' '.$customclase.' '.$startAnimation['from'].' '.$endAnimation['to'].'"
							 data-x="'.(float)$left.'"
							 data-y="'.(float)$top.'"
							 data-splitin="'.$splitIn.'"
							 data-splitout="'.$splitOut.'"
						     data-elementdelay="'.$speedIn.'"
						     data-endelementdelay="'.$speedOut.'"
							 data-speed="'.$startAnimation['during'].'"
							 data-start="'.$startAnimation['at'].'"
							 data-easing="'.$startAnimation['use'].'" '.$endeffect.' '.$customin.' '.$customout.$custom_style.'>';

				$html .= "\n";
							 
				switch($item_data['type']) {

					case 'text': 

					 	$width = isset($item_data['width'])?$item_data['width']:"";
					 	$height = isset($item_data['height'])?$item_data['height']:"";

					 	$css = "";
					 	/*
					 	if(isset($item_data['width']) && $item_data['width']){
					 		$css .= " width:".$item_data['width'].";";
					 	}
					 	if(isset($item_data['height']) && $item_data['height']){
					 		$css .= " height:".$item_data['height'].";";
					 	}*/
					 	$tag = isset($item_data['tag'])?$item_data['tag']:"h3";
					 	$tag = 'div';
					 	$html .= '<'.$tag.' style="'.$css.$style.'">'.$content.'</'.$tag.'>';

					 
					break;
					case 'image':
						if($item_data['src'] && (file_exists($base_dir.$item_data['src']) || file_exists(Mage::getBaseDir('media')."/".$item_data['src']))) {
						 	$width = isset($item_data['width'])?$item_data['width']:"";
						 	$height = isset($item_data['height'])?$item_data['height']:"";

						 	$ignore = (isset($item_data['ignore']) && $item_data['ignore'])?true:false;

						 	if(!$ignore) {
						 		$css = "";
						 		
							 	if(isset($item_data['width']) && $item_data['width']){
							 		$item_data['width'] = is_numeric($item_data['width'])?$item_data['width']."px":$item_data['width'];
							 		$css .= " width:".$item_data['width'].";";
							 	}
							 	if(isset($item_data['height']) && $item_data['height']){
							 		$item_data['height'] = is_numeric($item_data['height'])?$item_data['height']."px":$item_data['height'];
							 		$css .= " height:".$item_data['height'].";";
							 	}
								
								$html .= '<img src="'.Mage::helper("ves_layerslider")->getImage( $item_data['src'] ).'" style="'.$css.$style.'" alt="image" />';

						 	}
						}
					 
					break;
					case 'video':
						$width = isset($item_data['width'])?$item_data['width']:"";
					 	$height = isset($item_data['height'])?$item_data['height']:"";

					 	$css = "";
					 	
					 	if(isset($item_data['width']) && $item_data['width']){
					 		$css .= " width:".$item_data['width'].";";
					 	}
					 	if(isset($item_data['height']) && $item_data['height']){
					 		$css .= " height:".$item_data['height'].";";
					 	}

					 	$video_link = "";

					 	if($videotype == "youtube") {
					 		$video_link = "//www.youtube.com/embed/".$videoid;
					 	} elseif($videotype == "vimeo") {
					 		$video_link = "//player.vimeo.com/video/".$videoid;
					 	}

					 	$html .= '<iframe src="'.$video_link.'"
					 					width="'.$width.'" height="'.$height.'"
					 					frameborder="0" 
						                webkitallowfullscreen="webkitAllowFullScreen" 
						                mozallowfullscreen="mozallowfullscreen" 
						                allowfullscreen="allowFullScreen"
				                  		style="'.$css.$style.'"></iframe>';
					break;
					case 'imglink':
						if($item_data['src'] && (file_exists($base_dir.$item_data['src']) || file_exists(Mage::getBaseDir('media')."/".$item_data['src']))) {
						 	$width = isset($item_data['width'])?$item_data['width']:"";
						 	$height = isset($item_data['height'])?$item_data['height']:"";

						 	$css = "";
						 	
						 	if(isset($item_data['width']) && $item_data['width']){
						 		$css .= " width:".$item_data['width'].";";
						 	}
						 	if(isset($item_data['height']) && $item_data['height']){
						 		$css .= " height:".$item_data['height'].";";
						 	}
							
						 	$html .= '<a href="'.$href.'" target="'.$target.'" style="'.$style.'"><img src="'.Mage::helper("ves_layerslider")->getImage( $item_data['src'] ).'" style="'.$css.'"/></a>';

						}
					break;
					case 'link':
						$width = isset($item_data['width'])?$item_data['width']:"";
					 	$height = isset($item_data['height'])?$item_data['height']:"";

					 	$css = "";

					 	$html .= '<a href="'.$href.'" target="'.$target.'" style="'.$css.$style.'">'.$content.'</a>';
					break;
					case 'block':

					 	$width = isset($item_data['width'])?$item_data['width']:"";
					 	$height = isset($item_data['height'])?$item_data['height']:"";

					 	$css = "";
					 	if(isset($item_data['width']) && $item_data['width']){
					 		$css .= " width:".$item_data['width'].";";
					 	}
					 	if(isset($item_data['height']) && $item_data['height']){
					 		$css .= " height:".$item_data['height'].";";
					 	}
					 	$css .= ' opacity:'.$opacity.';';

					 	$html .= '<div style="'.$css.$style.'"></div>';

					break;
				}

				$html .= "\n";

				$html .= '</div>';

			}
		}
		return $html;
	}
}
