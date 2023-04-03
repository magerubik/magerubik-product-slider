<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Category;
use Magerubik\Productslider\Controller\Adminhtml\Category;
class Index extends Category
{
	public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->getPageFactory()->create();
        $resultPage->setActiveMenu('Magerubik_Productslider::category');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Group'));
        $resultPage->addBreadcrumb(__('Category'), __('Category'));
        return $resultPage;
    }
}