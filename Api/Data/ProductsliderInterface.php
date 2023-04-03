<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved. 
 * @author Magerubik Team <info@magerubik.com> 
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Api\Data;


interface ProductsliderInterface {
    const ID = 'productslider_id';
    const TITLE = 'title';
    const DESC = 'description';
    const CATEGORY_ID = 'category_id';
    const TYPE_ID = 'type_id';
    const GROUP_ID = 'group_id';
    const POS = 'position';
    const STATUS = 'status';
    const CREATE_TIME = 'create_time';
    const UPDATE_TIME = 'update_time';

    public function getId();
    public function getTitle();
    public function getDesc();
    public function getCategoryId();
    public function getTypeId();
    public function getGroupId();
    public function getPos();
    public function getStatus();
    public function getCreateTime();
    public function getUpdateTime();

    public function setId($id);
    public function setTitle($title);
    public function setDesc($desc);
    public function setCategoryId($category_id);
    public function setTypeId($type_id);
    public function setGroupId($category_id);
    public function setPos($pos);
    public function setStatus($status);
    public function setCreateTime($create_time);
    public function setUpdateTime($update_time);
}