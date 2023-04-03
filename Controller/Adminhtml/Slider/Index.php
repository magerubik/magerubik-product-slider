<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */

namespace Magerubik\Productslider\Controller\Adminhtml\Slider;

use Magerubik\Productslider\Controller\Adminhtml\Slider;

class Index extends Slider{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->getPageFactory()->create();
        $resultPage->setActiveMenu('Magerubik_Productslider::slider');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Slider'));
        $resultPage->addBreadcrumb(__('Slider'), __('Slider'));
        return $resultPage;
    }
}