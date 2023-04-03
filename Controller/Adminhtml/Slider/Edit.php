<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Controller\Adminhtml\Slider;
use Magerubik\Productslider\Controller\Adminhtml\Slider;
class Edit extends Slider
{
    const CURRENT_PRODUCTSLIDER = 'magerubik_productslider_slider';
    /**
     * Dispatch request
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface|void
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->getSliderRepository()->getSlider();
        if ($id) {
            try {
                $model = $this->getSliderRepository()->getById($id);
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                return $this->_redirect('*/*');
            }
        }
        $data = $this->_getSession()->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->getRegistry()->register(self::CURRENT_PRODUCTSLIDER, $model);
        $this->initAction();
        if ($model->getId()) {
            $title = __('Edit Slider `%1`', $model->getTitle());
        } else {
            $title = __('Add New Slider');
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
        $this->_setActiveMenu('Magerubik_Productslider::slider')->_addBreadcrumb(
            __('Magerubik Productslider Slider'),
            __('Magerubik Productslider Slider')
        );
        return $this;
    }
}