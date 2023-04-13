<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Block;
use Magerubik\Productslider\Model\SliderFactory;
use Magerubik\Productslider\Model\CategoryFactory as SliderCategoryFactory;
use Magerubik\Productslider\Helper\Data;
use Magerubik\Productslider\Helper\ConfigHelper;
use Magento\Framework\Registry;
class IndexBlock extends \Magento\Catalog\Block\Product\AbstractProduct {
    /**
     * Core Registry
     * @var Registry
     */
    protected $_coreRegistry;
    private $_isHaveItems = false;
    protected $_productModelFactory;
    protected $_productType;
    protected $_reportModelFactory;
    protected $_downloadModelFactory;
    protected $_categoryFactory;
    protected $_saleCollection;
    protected $_sliderFactory;
    protected $_sliderCategoryFactory;
    public $_productsliderHelper;
    public $absblock;
    public $_configHelper;
    public $tempSetting;
    public function __construct (\Magento\Catalog\Block\Product\Context $context,
                                 Registry $registry,
                                 \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
                                 ConfigHelper $configHelper,
                                 SliderFactory $sliderFactory,
                                 SliderCategoryFactory $sliderCategoryFactory,
                                 Data $helper,
                                 array $data) {
        parent::__construct ($context, $data);
        $this->_coreRegistry = $registry;
        $this->_configHelper = $configHelper;
        $this->_sliderFactory = $sliderFactory;
        $this->_productsliderHelper = $helper;
        $this->_sliderCategoryFactory = $sliderCategoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->tempSetting = $this->_configHelper->getTemplateSettings();
    }
    public function _prepareLayout ()
    {
        if(!$this->_configHelper->getEnable()) return ;
        $id = $this->getDataByKey('group_id');
        $this->_initBlock($id);
        if($this->_isHaveItems)
        {
            $group = $this->_coreRegistry->registry('productslider_current_group_'.$id);
            if($group)
            {
                if($group->getLayout() == 2)
                {
                    $this->setTemplate('list.phtml');
                }
				elseif($group->getLayout() == 3)
                {
                    $this->setTemplate('tab_vertical.phtml');
                }
                else{
                    $this->setTemplate('index.phtml');
                }
            }
			//$this->setTemplate('debug.phtml');
        }
        return parent::_prepareLayout ();
    }
    public function _initBlock($groupid)
    {
        $sliderModel = $this->_sliderCategoryFactory->create();
        $slider = $sliderModel->getCollection()->getItemById($groupid);
        if($slider && $slider->getStatus() != 0)
        { 
            $this->_coreRegistry->register('productslider_current_group_'.$groupid,$slider);
            $model = $this->_sliderFactory->create();
            $list = $model->getCollection()->addFilter('group_id',$groupid)->addFilter('status',1)->setOrder('position','ASC')->load();	
			if(count($list) > 0)
            {
				$this->_isHaveItems = true;
				$this->_coreRegistry->register('productslider_current_slider_list_'.$groupid,$list);
				$this->setData('sliderlist',$list);	
				$this->setData('slidertitle',$slider->getTitle());
            }
        }
    }
    public function getProductDetail($id)
    {
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->getItemById($id);
        return $collection;
    }
    public function getList()
    {
        return $this->getData('sliderlist');
    }
	public function getTitle()
    {
        return $this->getData('slidertitle');
    }
}