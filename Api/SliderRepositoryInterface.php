<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Api;
use Magerubik\Productslider\Model\ResourceModel\Slider\Collection;
use Magento\Framework\Exception\NoSuchEntityException;
/**
 * @api
 */
interface SliderRepositoryInterface
{
    /**
     * Save
     *
     * @param \Magerubik\Productslider\Api\Data\CategoryInterface $slider
     *
     * @return \Magerubik\Productslider\Api\Data\CategoryInterface
     */
    public function save(\Magerubik\Productslider\Api\Data\SliderInterface $slider);
    /**
     * Get by id
     *
     * @param int $sliderId
     *
     * @return \Magerubik\Productslider\Api\Data\CategoryInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($sliderId);
    /**
     * @return \Magerubik\Productslider\Model\Categories
     */
    public function getSlider();
    /**
     * Delete
     *
     * @param \Magerubik\Productslider\Api\Data\CategoryInterface $slider
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Magerubik\Productslider\Api\Data\SliderInterface $slider);
    /**
     * Delete by id
     *
     * @param int $sliderId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($sliderId);
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