<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Api;
use Magerubik\Productslider\Model\ResourceModel\Category\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
/**
 * @api
 */
interface CategoryRepositoryInterface
{
    /**
     * Save
     *
     * @param \Magerubik\Productslider\Api\Data\CategoryInterface $category
     *
     * @return \Magerubik\Productslider\Api\Data\CategoryInterface
     */
    public function save(\Magerubik\Productslider\Api\Data\CategoryInterface $category);
    /**
     * Get by id
     *
     * @param int $categoryId
     *
     * @return \Magerubik\Productslider\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($categoryId);
    /**
     * @return \Magerubik\Productslider\Model\Categories
     */
    public function getCategory();
    /**
     * Delete
     *
     * @param \Magerubik\Productslider\Api\Data\CategoryInterface $category
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Magerubik\Productslider\Api\Data\CategoryInterface $category);
    /**
     * Delete by id
     *
     * @param int $categoryId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($categoryId);
    /**
     * Lists
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}