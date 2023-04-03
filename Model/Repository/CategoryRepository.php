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
use Magerubik\Productslider\Api\CategoryRepositoryInterface;
use Magerubik\Productslider\Api\Data\CategoryInterface;
use Magerubik\Productslider\Model\CategoryFactory;
use Magerubik\Productslider\Model\ResourceModel\Category as CategoryResource;
use Magerubik\Productslider\Model\ResourceModel\Category\Collection;
use Magerubik\Productslider\Model\ResourceModel\Category\CollectionFactory;
use Magerubik\All\Model\Source\Status as CategoryStatus;
class CategoryRepository implements CategoryRepositoryInterface
{
	/**
     * @var BookmarkSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var CategoryResource
     */
    private $categoryResource;
    /**
     * Model data storage
     *
     * @var array
     */
    private $categories;
    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magerubik\Productslider\Helper\Configuration
     */
    public function __construct(
        BookmarkSearchResultsInterfaceFactory $searchResultsFactory,
        CategoryFactory $categoryFactory,
        CategoryResource $categoryResource,
        CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->categoryFactory = $categoryFactory;
        $this->categoryResource = $categoryResource;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
    }
    /**
     * @param CategoryInterface $categories
     *
     * @return CategoryInterface
     * @throws CouldNotSaveException
     */
    public function save(CategoryInterface $categories)
    {
        try {
            if ($categories->getCategoryId()) {
                $categories = $this->getById($categories->getCategoryId())->addData($categories->getData());
            } else {
                $categories->setCategoryId(null);
            }
            $this->categoryResource->save($categories);
            unset($this->category[$categories->getCategoryId()]);
        } catch (\Exception $e) {
            if ($categories->getCategoryId()) {
                throw new CouldNotSaveException(
                    __(
                        'Unable to save categories with ID %1. Error: %2',
                        [$categories->getCategoryId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotSaveException(__('Unable to save new categories. Error: %1', $e->getMessage()));
        }
        return $categories;
    }
    /**
     * @param int $categoryId
     *
     * @return \Magerubik\Productslider\Model\Category
     * @throws NoSuchEntityException
     */
    public function getById($categoryId)
    {
        if (!isset($this->categories[$categoryId])) {
            /** @var \Magerubik\Productslider\Model\Category $categories */
            $categories = $this->categoryFactory->create();
            $this->categoryResource->load($categories, $categoryId);
            if (!$categories->getCategoryId()) {
                throw new NoSuchEntityException(__('Category with specified ID "%1" not found.', $categoryId));
            }
            $this->categories[$categoryId] = $categories;
        }
        return $this->categories[$categoryId];
    }
	    /**
     * @return \Magerubik\Productslider\Model\Category
     */
    public function getCategory()
    {
        return $this->categoryFactory->create();
    }
    /**
     * @param CategoryInterface $categories
     *
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(CategoryInterface $categories)
    {
        try {
            $this->categoryResource->delete($categories);
            unset($this->categories[$categories->getCategoryId()]);
        } catch (\Exception $e) {
            if ($categories->getCategoryId()) {
                throw new CouldNotDeleteException(
                    __(
                        'Unable to remove categories with ID %1. Error: %2',
                        [$categories->getCategoryId(), $e->getMessage()]
                    )
                );
            }
            throw new CouldNotDeleteException(__('Unable to remove categories. Error: %1', $e->getMessage()));
        }
        return true;
    }
    /**
     * @param int $categoryId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($categoryId)
    {
        $categoriesModel = $this->getById($categoryId);
        $this->delete($categoriesModel);
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
        /** @var \Magerubik\Productslider\Model\ResourceModel\Category\Collection $categoriesCollection */
        $categoriesCollection = $this->categoryCollectionFactory->create();
        // Add filters from root filter group to the collection
        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $categoriesCollection);
        }
        $searchResults->setTotalCount($categoriesCollection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            $this->addOrderToCollection($sortOrders, $categoriesCollection);
        }
        $categoriesCollection->setCurPage($searchCriteria->getCurrentPage());
        $categoriesCollection->setPageSize($searchCriteria->getPageSize());
        $categories = [];
        /** @var CategoryInterface $categories */
        foreach ($categoriesCollection->getItems() as $categoryItem) {
            $categories[] = $this->getById($categoryItem->getCategoryId());
        }
        $searchResults->setItems($categories);
        return $searchResults;
    }
    /**
     * Helper function that adds a SortOrder to the collection.
     *
     * @param SortOrder[] $sortOrders
     * @param Collection $categoriesCollection
     *
     * @return void
     */
    private function addOrderToCollection($sortOrders, Collection $categoriesCollection)
    {
        /** @var SortOrder $sortOrder */
        foreach ($sortOrders as $sortOrder) {
            $field = $sortOrder->getField();
            $categoriesCollection->addOrder(
                $field,
                ($sortOrder->getDirection() == SortOrder::SORT_DESC) ? SortOrder::SORT_DESC : SortOrder::SORT_ASC
            );
        }
    }

}