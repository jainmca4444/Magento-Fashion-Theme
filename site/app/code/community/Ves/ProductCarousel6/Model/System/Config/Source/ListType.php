<?php

class Ves_ProductCarousel6_Model_System_Config_Source_ListType
{
    public function toOptionArray()
    {
        return array(
        	array('value'=>'', 'label'=>Mage::helper('ves_productcarousel6')->__('-- Please select --')),
            array('value'=>'latest', 'label'=>Mage::helper('ves_productcarousel6')->__('Latest')),
            array('value'=>'sale', 'label'=>Mage::helper('ves_productcarousel6')->__('On Sales')),
            array('value'=>'best_buy', 'label'=>Mage::helper('ves_productcarousel6')->__('Best Buy')),
            array('value'=>'most_viewed', 'label'=>Mage::helper('ves_productcarousel6')->__('Most Viewed')),
            array('value'=>'top_rated', 'label'=>Mage::helper('ves_productcarousel6')->__('Top Rated')),
            array('value'=>'featured', 'label'=>Mage::helper('ves_productcarousel6')->__('Featured Product'))
        );
    }    
}
