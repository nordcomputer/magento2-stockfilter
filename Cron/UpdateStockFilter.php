<?php

namespace Nordcomputer\Stockfilter\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpdateStockFilter
{
    protected $CollectionFactory;

    private $stockRegistry;

    public function __construct(
        CollectionFactory $CollectionFactory,
        StockRegistryInterface $stockRegistry,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->CollectionFactory = $CollectionFactory;
        $this->stockRegistry = $stockRegistry;
        $this->scopeConfig = $scopeConfig;
    }
    public function execute()
    {
        if ($this->scopeConfig->getValue('cataloginventory/cronjobs/is_enabled')==1) {
            $collection = $this->CollectionFactory->create()
            ->addAttributeToSelect('*')
            ->load();
            foreach ($collection as $product) {
                $oldfilterstock=$product->getFilterStock();
                $product->setFilterStock('');
                if ($this->getStockStatus($product->getId())==true) {
                        $product->setFilterStock(1);
                }
                if ($oldfilterstock!=$product->getFilterStock()) {
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
