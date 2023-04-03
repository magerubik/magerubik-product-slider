<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\ResourceModel\Category\Grid;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\Search\AggregationInterface;
class Collection extends \Magerubik\Productslider\Model\ResourceModel\Category\Collection implements SearchResultInterface
{
	/**
     * @var AggregationInterface
     */
    private $aggregations;
    /**
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        return $this;
    }
    /**
     * Set items list.
     *
     * @param \Magento\Framework\Api\Search\DocumentInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items = null)
    {
        return $this;
    }
    /**
     * @return \Magento\Framework\Api\Search\AggregationInterface
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }
    /**
     * @param \Magento\Framework\Api\Search\AggregationInterface $aggregations
     *
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
        return $this;
    }
    /**
     * Get search criteria.
     *
     * @return \Magento\Framework\Api\Search\SearchCriteriaInterface
     */
    public function getSearchCriteria()
    {
        return null;
    }
    /**
     * Set search criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return $this
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }
    /**
     * Get total count.
     *
     * @return int
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }
    /**
     * Set total count.
     *
     * @param int $totalCount
     *
     * @return $this
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }	
}