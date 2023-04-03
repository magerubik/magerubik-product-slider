<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Helper;


use Magento\Framework\App\Helper\AbstractHelper;
//use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;

class ConfigHelper extends AbstractHelper{
    CONST ENABLE = 'magerubik_productslider/setting/enable';
//    CONST JQUERY = 'magerubik_productslider/setting/jquery';
    CONST MAXPRODUCT = 'magerubik_productslider/setting/maxproduct';
    CONST SLIDERITEM = 'magerubik_productslider/setting/slideritem';
    CONST SHOWPRICE = 'magerubik_productslider/setting/showprice';
    CONST SHOWADDCART = 'magerubik_productslider/setting/showaddcart';
    CONST SHOWREVIEWS = 'magerubik_productslider/setting/showreviews';
    CONST AUTOPLAY = 'magerubik_productslider/setting/autoplay';
    CONST AUTOTIMEOUT = 'magerubik_productslider/setting/autotimeout';
    CONST NAVIGATION = 'magerubik_productslider/setting/navigation';
    CONST STOPONHOVER = 'magerubik_productslider/setting/stoponhover';
    CONST SLIDERSPEED = 'magerubik_productslider/setting/sliderspeed';

    public function __construct (Context $context) {
        parent::__construct ($context);
    }
/*    public function getJqueryLoad()
    {
        return $this->scopeConfig->getValue(self::JQUERY);
    }*/
    public function getMaxProduct()
    {
        $maxproduct = $this->scopeConfig->getValue(self::MAXPRODUCT);
        if($maxproduct == null || $maxproduct < 1) $maxproduct = 12;
        return $maxproduct;
    }
    public function getEnable(){
        return $this->scopeConfig->getValue(self::ENABLE);
    }
    public function getAutoplay()
    {
        if($this->scopeConfig->getValue(self::AUTOPLAY))return 'true';
        return 'false';
    }
	public function getAutotimeout()
    {
       $autoTimeout = $this->scopeConfig->getValue(self::AUTOTIMEOUT);
        return $autoTimeout;
    }
    public function getStopOnHover() {
        if($this->scopeConfig->getValue(self::STOPONHOVER)) return 'true';
        return 'false';
    }
    public function getSliderItem() {
        $slideritem = $this->scopeConfig->getValue(self::SLIDERITEM);
        if($slideritem < 1) $slideritem = 6;
        return $slideritem;
    }
    public function getSliderSpeed() {
        $speed = $this->scopeConfig->getValue(self::SLIDERSPEED);
        if($speed < 1) $speed = 250;
        return $speed;
    }
    public function getTemplateSettings() {
        $setting = new \stdClass();
        $setting->maxproduct = $this->getMaxProduct();
        $setting->showprice = $this->scopeConfig->getValue(self::SHOWPRICE);
        $setting->showreviews = $this->scopeConfig->getValue(self::SHOWREVIEWS);
        $setting->showaddcart = $this->scopeConfig->getValue(self::SHOWADDCART);
        return $setting;
    }
    public function getNavigation()	{
        $navval = $this->scopeConfig->getValue(self::NAVIGATION);
        return $navval;
    }

}