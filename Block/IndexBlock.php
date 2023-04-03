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
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory as ReportFactory;
use Magento\Reports\Model\ResourceModel\Product\Downloads\CollectionFactory as DownloadsFactory;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
class IndexBlock extends Template{
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
    protected $_productVisible;
    public $_productsliderHelper;
    public $absblock;
    public $_configHelper;
    public $tempSetting;
    public function __construct (Template\Context $context,
                                 Registry $registry,
                                 ProductFactory $productFactory,
                                 ReportFactory $reportFactory,
                                 DownloadsFactory $downloadsFactory,
                                 CategoryFactory $categoryFactory,
                                 Configurable $productType,
                                 Visibility $visibility,
                                 ListProduct $abstractProduct,
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
        $this->_productVisible = $visibility;
        $this->_sliderCategoryFactory = $sliderCategoryFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_productModelFactory = $productFactory;
        $this->_reportModelFactory = $reportFactory;
        $this->_downloadModelFactory = $downloadsFactory;
        $this->_productType = $productType;
        $this->absblock = $abstractProduct;
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
    public function isElementOfSet($product_id)
    {
        $setId = $this->_productType->getParentIdsByChild($product_id);
        if(count($setId) > 0)
        {
            return $setId;
        }
        return false;
    }
    public function getProductDetail($id)
    {
        $model = $this->_productModelFactory->create();
        $collection = $model->getCollection()->addAttributeToSelect('*')->getItemById($id);
        return $collection;
    }
    public function getTopProducts()
    {
    }
    public function getTopDownloadProducts($categoryID = null)
    {
        $model = $this->_downloadModelFactory->create();
        $storeId = $model->getStoreId();
        $collection = $model->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection = $model->addAttributeToSelect(
            '*'
        )
            ->addSummary()
            ->setStoreId(
                $storeId
            )->addStoreFilter(
                $storeId
            )->setOrder('downloads')
            ->setPageSize($this->tempSetting->maxproduct);
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection->load();
    }
    public function getSaleProducts($categoryID = null)
    {
        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $model = $this->_productModelFactory->create();
        $collection = $model->getCollection();
        $collection->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection->addAttributeToSelect('*')
            ->addStoreFilter()
            ->addAttributeToFilter('special_price',['is' => new \Zend_Db_Expr('not null')])
            ->addAttributeToFilter(
                'special_from_date',
                [
                    'or' => [
                        0 => ['date' => true, 'to' => $todayEndOfDayDate],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )->addAttributeToFilter(
                'special_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $todayStartOfDayDate],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )->addAttributeToFilter(
                [
                    ['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
                    ['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
                ]
            )->addAttributeToSort(
                'special_from_date',
                'desc'
            )->setPageSize(
                $this->tempSetting->maxproduct
            )->setCurPage(
                1
            );
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection/*->load()*/;
    }
    public function getLastetProducts($categoryID = null)
    {
        $collection = $this->_productModelFactory->create()
            ->getCollection();
        $collection->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection->addAttributeToSelect('*')
            ->setOrder('created_at','DESC')
            ->setPageSize($this->tempSetting->maxproduct);
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection->load();
    }
    public function getTopRatedProducts($categoryID = null)
    {
        $orderfeild='rating_summary';
        $order='desc';
        $model = $this->_productModelFactory->create();
        $storeId = $model->getStoreId();
        $collection = $model->getCollection();
        $collection->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection->setStoreId($storeId)
            ->addAttributeToSelect('*')
            ->addStoreFilter($storeId);
        $collection->getSelect()->joinLeft(
            array('_reviewed_order_table'=>$collection->getTable('review_entity_summary')),
            "_reviewed_order_table.store_id=$storeId AND _reviewed_order_table.entity_pk_value=e.entity_id",
            array()
        );
        $collection->getSelect()->order("_reviewed_order_table.$orderfeild $order");
        $collection->getSelect()->group('e.entity_id');
        $collection->setPageSize($this->tempSetting->maxproduct);
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection->load();
    }
    public function getNewProducts ($categoryID = null)
    {
        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $prodcutModel = $this->_productModelFactory->create();
        $collection = $prodcutModel->getCollection();
        $collection->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection->addAttributeToSelect('*')
            ->addAttributeToFilter(
                'news_from_date',
                [
                    'or' => [
                        0 => ['date' => true, 'to' => $todayEndOfDayDate],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )->addAttributeToFilter(
                'news_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $todayStartOfDayDate],
                        1 => ['is' => new \Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )->addAttributeToFilter(
                [
                    ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                    ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
                ]
            )->addAttributeToSort(
                'news_from_date',
                'desc'
            )->setPageSize(
                $this->tempSetting->maxproduct
            )->setCurPage(
                1
            );
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection->load();
    }
    public function getMostviewProducts ($categoryID = null)
    {
        $model = $this->_reportModelFactory->create();
        $storeId = $model->getStoreId();
        $collection = $model->setVisibility($this->_productVisible->getVisibleInCatalogIds());
            $collection = $collection->addAttributeToSelect(
                '*'
            )
            ->addViewsCount()
            ->setStoreId(
                $storeId
            )->addStoreFilter(
                $storeId
            )->setPageSize($this->tempSetting->maxproduct);
        if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
        return $collection->load();
    }
    public function getBestSellerProducts($categoryID = null)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();    
		$collection = $objectManager->get('\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory')->create()->setModel('Magento\Catalog\Model\Product');
        $producIds = array();
        foreach ($collection as $product) {
            $producIds[] = $product->getProductId();
        }
		$model = $this->_productModelFactory->create();
        $storeId = $model->getStoreId();
        $collection = $model->getCollection();
        $collection->setVisibility($this->_productVisible->getVisibleInCatalogIds());
        $collection->setStoreId($storeId)
            ->addAttributeToSelect('*')
            ->addStoreFilter($storeId);
        $collection = $collection->addStoreFilter()->addAttributeToFilter('entity_id', array('in' => $producIds));
		if($categoryID && $categoryID != 0){
            $catModel = $this->_categoryFactory->create();
            $catCollection = $catModel->load($categoryID);
            $collection->addCategoryFilter($catCollection);
        }
		$collection->setPageSize($this->tempSetting->maxproduct);
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
    public function getItemByType($category = null,$type=1)
    {
        if($type == 7) return $this->getTopDownloadProducts($category);
        if($type == 6) return $this->getSaleProducts($category);
        if($type == 5) return $this->getNewProducts($category);
        if($type == 3) return $this->getMostviewProducts($category);
        if($type == 4) return $this->getLastetProducts($category);
        if($type == 2) return $this->getTopRatedProducts($category);
        return $this->getBestSellerProducts($category);
    }
}