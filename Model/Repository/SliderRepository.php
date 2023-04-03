<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\Repository;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Ui\Api\Data\BookmarkSearchResultsInterfaceFactory;
use Magerubik\Productslider\Api\SliderRepositoryInterface;
use Magerubik\Productslider\Api\Data\SliderInterface;
use Magerubik\Productslider\Model\SliderFactory;
use Magerubik\Productslider\Model\ResourceModel\Slider as SliderResource;
use Magerubik\Productslider\Model\ResourceModel\Slider\Collection;
use Magerubik\Productslider\Model\ResourceModel\Slider\CollectionFactory;
use Magerubik\All\Model\Source\Status as SliderStatus;
class SliderRepository implements SliderRepositoryInterface
{
	/**
     * @var BookmarkSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    /**
     * @var SliderFactory
     */
    private $sliderFactory;
    /**
     * @var SliderResource
     */
    private $sliderResource;
    /**
     * Model data storage
     *
     * @var array
     */
    private $slider;
    /**
     * @var CollectionFactory
     */
    private $sliderCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magerubik\Productslider\Helper\Configuration
     */
    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        SliderFactory $sliderFactory,
        SliderResource $sliderResource,
        CollectionFactory $sliderCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->sliderFactory = $sliderFactory;
        $this->sliderResource = $sliderResource;
        $this->sliderCollectionFactory = $sliderCollectionFactory;
        $this->storeManager = $storeManager;
    }
    /**
     * @param SliderInterface $slider
     *
     * @return SliderInterface
     * @throws CouldNotSaveException
     */
    public function save(SliderInterface $slider)
    {
        try {
            if ($slider->getProductsliderId()) {
                $slider = $this->getById($slider->getProductsliderId())->addData($slider->getData());
            } else {
                $slider->setProductsliderId(null);
            }
            $this->sliderResource->save($slider);
            unset($this->slider[$slider->getProductsliderId()]);
        } catch (\Exception $e) {
            if ($slider->getProductsliderId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save sliders with ID %1. Error: %2',
                        [$slider->getProductsliderId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new sliders. Error: %1', $e->getMessage()));
        }
        return $slider;
    }
    /**
     * @param int $sliderId
     *
     * @return \Magerubik\Productslider\Model\Slider
     * @throws NoSuchEntityException
     */
    public function getById($sliderId)
    {
        if (!isset($this->sliders[$sliderId])) {
            /** @var \Magerubik\Productslider\Model\Slider $slider */
            $slider = $this->sliderFactory->create();
            $this->sliderResource->load($slider, $sliderId);
            if (!$slider->getProductsliderId()) {
                throw new NoSuchEntityException(__('Slider with specified ID "%1" not found.', $sliderId));
            }
            $this->sliders[$sliderId] = $slider;
        }
        return $this->sliders[$sliderId];
    }
	    /**
     * @return \Magerubik\Productslider\Model\Slider
     */
    public function getSlider()
    {
        return $this->sliderFactory->create();
    }
    /**
     * @param SliderInterface $slider
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(SliderInterface $slider)
    {
        try {
            $this->sliderResource->delete($slider);
            unset($this->sliders[$slider->getProductsliderId()]);
        } catch (\Exception $e) {
            if ($slider->getProductsliderId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove sliders with ID %1. Error: %2',
                        [$slider->getProductsliderId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove sliders. Error: %1', $e->getMessage()));
        }
        return true;
    }
    /**
     * @param int $sliderId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($sliderId)
    {
        $sliderModel = $this->getById($sliderId);
        $this->delete($sliderModel);
        return true;
    }
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface|\Magento\Ui\Api\Data\BookmarkSearchResultsInterface
     * @throws NoSuchEntityException
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var \Magerubik\Productslider\Model\ResourceModel\Slider\Collection $sliderCollection */
        $sliderCollection = $this->sliderCollectionFactory->create();
        // Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $sliderCollection);
        }
        $searchResults->setTotalCount($sliderCollection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            $this->addOrderToCollection($sortOrders, $sliderCollection);
        }
        $sliderCollection->setCurPage($searchCriteria->getCurrentPage());
        $sliderCollection->setPageSize($searchCriteria->getPageSize());
        $slider = [];
        /** @var SliderInterface $slider */
        foreach ($sliderCollection->getItems() as $sliderItem) {
            $slider[] = $this->getById($sliderItem->getProductsliderId());
        }
        $searchResults->setItems($slider);
        return $searchResults;
    }
    /**
     * Helper function that adds a SortOrder to the collection.
     *
     * @param SortOrder[] $sortOrders
     * @param Collection $sliderCollection
     *
     * @return void
     */
    private function addOrderToCollection($sortOrders, Collection $sliderCollection)
    {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $sliderCollection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_DESC) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC
            );
        }
    }

}