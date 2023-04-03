<?php
namespace Magerubik\Productslider\Model;
use Magerubik\Productslider\Api\Data\CategoryInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
class Category extends AbstractModel implements CategoryInterface,IdentityInterface
{
    const ENABLE = 1;
    const DISABLE = 0;
    const CACHE_TAG = 'magerubik_productslider_category';
    protected $_cacheTag = 'magerubik_productslider_category';
    protected $_eventPrefix = 'magerubik_productslider_category';
    protected function _construct ()
    {
        $this->_init('\Magerubik\Productslider\Model\ResourceModel\Category');
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
    // Getters
    /**
     * @return int|null
     */
    public function getCategoryId()
    {
        return $this->getData (self::CATEGORY_ID);
    }
    /**
     * @return string|null
     */
    public function getTitle ()
    {
        return $this->getData(self::TITLE);
    }
    /**
     * @return int|null
     */
    public function getLayout(){
        return $this->getData(self::LAYOUT);
    }
    /**
     * @return string|null
     */
    public function getInfo(){
        return $this->getData(self::INFO);
    }
    /**
     * @return int|null
     */
    public function getStatus(){
        return $this->getData(self::STATUS);
    }
    /**
     * @return string|null
     */
    public function getCreateTime(){
        return $this->getData(self::CREATE_TIME);
    }
    /**
     * @return string|null
     */
    public function getUpdateTime(){
        return $this->getData(self::UPDATE_TIME);
    }
    // Setters
    public function setCategoryId($id){
        return $this->setData(self::CATEGORY_ID, $id);
    }
    public function setTitle($title){
        return $this->setData(self::TITLE, $title);
    }
    public function setLayout($layout){
        return $this->setData(self::LAYOUT, $layout);
    }
    public function setInfo($info){
        return $this->setData(self::INFO, $info);
    }
    public function setStatus($status){
        return $this->setData(self::STATUS, $status);
    }
    public function setCreateTime($create_time){
        return $this->setData(self::CREATE_TIME, $create_time);
    }
    public function setUpdateTime($update_time){
        return $this->setData(self::UPDATE_TIME, $update_time);
    }
}