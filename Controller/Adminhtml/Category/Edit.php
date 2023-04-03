<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Category;
use Magento\Framework\App\ResponseInterface;
use Magerubik\Productslider\Controller\Adminhtml\Category;
class Edit extends Category
{
	const CURRENT_BLOG_PRODUCTSLIDER = 'magerubik_productslider_category';
    /**
     * Dispatch request
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface|void
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->getCategoryRepository()->getCategory();
        if ($id) {
            try {
                $model = $this->getCategoryRepository()->getById($id);
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                return $this->_redirect('*/*');
            }
        }
        $data = $this->_getSession()->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->getRegistry()->register(self::CURRENT_BLOG_PRODUCTSLIDER, $model);
        $this->initAction();
        if ($model->getId()) {
            $title = __('Edit Group `%1`', $model->getTitle());
        } else {
            $title = __('Add New Group');
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }
    /**
     * @return $this
     */
    private function initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Magerubik_Productslider::category')->_addBreadcrumb(
            __('Magerubik Productslider Group'),
            __('Magerubik Productslider Group')
        );
        return $this;
    }
	
}