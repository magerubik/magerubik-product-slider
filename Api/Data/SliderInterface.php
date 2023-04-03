<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */

namespace Magerubik\Productslider\Api\Data;


interface SliderInterface {

    const PRODUCTSLIDER_ID = 'productslider_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const CATEGORY_ID = 'category_id';
    const TYPE_ID = 'type_id';
    const GROUP_ID = 'group_id';
    const POSITION = 'position';
    const STATUS = 'status';
    const CREATED_TIME = 'created_time';
    const UPDATE_TIME = 'update_time';

    /**
     * setters
     */
    public function getProductsliderId();
    public function getTitle();
    public function getDescription();
    public function getCategoryId();
    public function getTypeId();
    public function getGroupId();
    public function getPosition();
    public function getStatus();
    public function getCreateTime();
    public function getUpdateTime();
    /**
     * Setters
     */
    public function setProductsliderId($id);
    public function setTitle($title);
    public function setDescription($description);
    public function setCategoryId($categoryId);
    public function setTypeId($typeId);
    public function setGroupId($groupId);
    public function setPosition($position);
    public function setStatus($status);
    public function setCreateTime($create_time);
    public function setUpdateTime($update_time);
}