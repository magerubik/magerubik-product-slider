<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magerubik\Productslider\Helper\ConfigHelper;
use Magento\Framework\View\Element\Template\Context;

class LayoutGenerateBlockBefore implements ObserverInterface {
    private $pageConfig;
    public $_configHelper;
    public function __construct (Context $context,ConfigHelper $configHelper) {
        $this->pageConfig = $context->getPageConfig();
        $this->_configHelper = $configHelper;
    }

    public function execute (\Magento\Framework\Event\Observer $observer)
    {
        // TODO: Implement execute() method.
        $this->_setAssets();
    }
    public function _setAssets()
    {
        // SET CSS , JS
        $this->pageConfig->addPageAsset('Magerubik_Productslider::css/productslider.css');
        $this->pageConfig->addPageAsset('Magerubik_All::owl-carousel/owl.carousel.min.css');
        $this->pageConfig->addPageAsset('Magerubik_All::owl-carousel/owl.transitions.css');
    }
}