<?php

class Ves_ProductCarousel5_Model_System_Config_Backend_ProductCarousel5_Checkvalue extends Mage_Core_Model_Config_Data
{

    protected function _beforeSave(){
        $value=$this->getValue();
        	if ((!is_numeric($value) && !empty($value)) || $value < 0) {				
        	    throw new Exception(Mage::helper('ves_productcarousel5')->__($this->getField_config()->label . ': Value must be numeric.'));
        	}
        return $this;
    }

}
