<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model;
use Magerubik\Productslider\Api\Data\SliderInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magerubik\Productslider\Model\ResourceModel\Slider as ResourceModel;
use Magento\Catalog\Model\Category;
class Slider extends AbstractModel implements SliderInterface,IdentityInterface
{
    const ENABLE = 1;
    const DISABLE = 0;
    const CACHE_TAG = 'magerubik_productslider_slider';
    protected $_cacheTag = 'magerubik_productslider_slider';
    protected $_eventPrefix = 'magerubik_productslider_slider';
    protected function _construct ()
    {
        $this->_init('\Magerubik\Productslider\Model\ResourceModel\Slider');
    }
    /**
     * Prepare item's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::ENABLE => __('Enabled'), self::DISABLE => __('Disabled')];
    }
    /**
     * Return unique ID(s) for each object in system
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    public function beforeSave ()
    {
        return parent::beforeSave (); // TODO: Change the autogenerated stub
    }
    ///////////////////////////
    /// GETTERS IMPLEMENTS ///
    /////////////////////////
    /**
     * @return int|null
     */
    public function getProductsliderId()
    {
        return $this->getData (self::PRODUCTSLIDER_ID);
    }
    /**
     * @return string|null
     */
    public function getTitle ()
    {
        return $this->getData(self::TITLE);
    }
    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }
    /**
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->getData(self::CATEGORY_ID);
    }
    /**
     * @return int|null
     */
    public function getTypeId()
    {
        return $this->getData(self::TYPE_ID);
    }
    /**
     * @return int|null
     */
    public function getGroupId()
    {
        return $this->getData(self::GROUP_ID);
    }
    /**
     * @return int|null
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }
    /**
     * @return int|null
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
    /**
     * @return string|null
     */
    public function getCreateTime()
    {
        return $this->getData(self::CREATE_TIME);
    }
    /**
     * @return string|null
     */
    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }
    /////////////////////////
    // SETTERS IMPLEMENTS //
    ////////////////////////
    /**
     * @param int $id
     */
    public function setProductsliderId($id)
    {
        return $this->setData(self::PRODUCTSLIDER_ID, $id);
    }
    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }
    /**
     * @param $desc string
     */
    public function setDescription($desc)
    {
        return $this->setData(self::DESCRIPTION, $desc);
    }
    /**
     * @param $category_id int
     */
    public function setCategoryId($category_id)
    {
        return $this->setData(self::CATEGORY_ID, $category_id);
    }
    /**
     * @param $type_id int
     */
    public function setTypeId($type_id)
    {
        return $this->setData(self::TYPE_ID, $type_id);
    }
    /**
     * @param $category_id int
     */
    public function setGroupId($category_id)
    {
        return $this->setData(self::GROUP_ID, $category_id);
    }
    /**
     * @param $pos int
     */
    public function setPosition($pos)
    {
        return $this->setData(self::POSITION, $pos);
    }
    /**
     * @param $status int
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
    /**
     * @param $create_time string
     */
    public function setCreateTime($create_time)
    {
        return $this->setData(self::CREATE_TIME, $create_time);
    }
    /**
     * @param $update_time string
     */
    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME, $update_time);
    }
}