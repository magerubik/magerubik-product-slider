<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
declare(strict_types=1);
namespace Magerubik\Productslider\Controller\Adminhtml\Category;
use Magerubik\All\Controller\Adminhtml\Base\SaveBase;
use Magerubik\Productslider\Api\CategoryRepositoryInterface;
use Magerubik\Productslider\Controller\Adminhtml\Category;
class Save extends Category
{
	use SaveBase;
    public function execute ()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = (int)$this->getRequest()->getParam('category_id');
			try {
                $inputFilter = new \Zend_Filter_Input([], [], $data);
                $data = $inputFilter->getUnescaped();
                if ($id) {
                    $model = $this->getCategoryRepository()->getById($id);
                } else {
                    $model = $this->getCategoryRepository()->getCategory();
                }
                $model->addData($data);
                $this->_getSession()->setPageData($data);
                $this->getCategoryRepository()->save($model);
                $this->getCategoryRepository()->getById($model->getCategoryId())->setInfo($this->_productsliderHelper->installGuide($model->getCategoryId(),$model->getLayout()))->save();
                $this->getMessageManager()->addSuccessMessage(__('You saved the item.'));
                $this->_getSession()->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getCategoryId()]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->getMessageManager()->addErrorMessage($e->getMessage());
                $this->getDataPersistor()->set('magerubik_productslider_category', $data);
                $this->addRedirect($id);
                return;
            } catch (\Exception $e) {
                $this->getMessageManager()->addErrorMessage(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->getLogger()->critical($e);
                $this->_getSession()->setPageData($data);
                $this->_redirect('*/*/edit', ['id' => $id]);
                return;
            }
        }
		$this->_redirect('*/*/');
    }
	protected function getRepository(): CategoryRepositoryInterface
    {
        return $this->getCategoryRepository();
    }
}