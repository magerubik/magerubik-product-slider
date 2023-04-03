<?php
/**
 * Copyright © 2021 magerubik.com. All rights reserved.
 * @author Magerubik Team <info@magerubik.com>
 * @package Magerubik_Productslider
 */
namespace Magerubik\Productslider\Model\DataProvider;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magerubik\Productslider\Api\CategoryRepositoryInterface;
use Magerubik\Productslider\Api\Data\CategoryInterface;
use Magerubik\Productslider\Model\ResourceModel\Category\CollectionFactory;
class CategoryDataProvider extends AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        CategoryRepositoryInterface $categoryRepository,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->categoryRepository = $categoryRepository;
        $this->collectionFactory = $collectionFactory;
    }
    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData()
    {
        $data = parent::getData();
        if ($data['totalRecords'] > 0) {
            $CategoryId = (int)$data['items'][0]['category_id'];
            $model = $this->categoryRepository->getById($CategoryId);
            if ($model) {
                $CategoryData = $model->getData();
                $data[$CategoryId] = $CategoryData;
            }
        }
        if ($savedData = $this->dataPersistor->get('magerubik_productslider_category')) {
            $savedCategoryId = isset($savedData['category_id']) ? $savedData['category_id'] : null;
            $data[$savedCategoryId] = isset($data[$savedCategoryId])
                ? array_merge($data[$savedCategoryId], $savedData)
                : $savedData;
            $this->dataPersistor->clear('magerubik_productslider_category');
        }
        return $data;
    }

}