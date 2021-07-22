<?php

namespace Nordcomputer\Stockfilter\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;

class UpdateStockFilter
{
    protected $CollectionFactory;

    private $stockRegistry;

    public function __construct(
        CollectionFactory $CollectionFactory,
        StockRegistryInterface $stockRegistry
    ) {
        $this->CollectionFactory = $CollectionFactory;
        $this->stockRegistry = $stockRegistry;
    }
    public function execute()
    {
        $collection = $this->CollectionFactory->create()
        ->addAttributeToSelect('*')
        ->load();
        foreach ($collection as $product) {
            if ($this->getStockStatus($product->getId())==true) {
                if ($product->getFilterStock()==0 || $product->getFilterStock()==false) {
                    $product->setFilterStock(1);
                    $product->save();
                }
            } else {
                if ($product->getFilterStock()==1) {
                    $product->setFilterStock(false);
                    $product->save();
                }
            }

        }
          return $this;
    }

    public function getStockStatus($productId)
    {
        /** @var StockItemInterface $stockItem */
        $stockItem = $this->stockRegistry->getStockItem($productId);
        $isInStock = $stockItem ? $stockItem->getIsInStock() : false;
        return $isInStock;
    }
}
