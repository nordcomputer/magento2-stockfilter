<?php

namespace Nordcomputer\Stockfilter\Observer;

use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;

class SetStockFilter implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        StockItemRepository $stockItemRepository,
        ProductRepositoryInterface $productrepository
    ) {
        $this->_stockItemRepository = $stockItemRepository;
        $this->productrepository = $productrepository;
    }

    public function getProductDataUsingId($productid)
    {
        return $this->productrepository->getById($productid);
    }

    public function getStockItem($productId)
    {
        return $this->_stockItemRepository->get($productId);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $stockItem=$this->getStockItem($product->getId());
        $status=null;
        $qty=null;
        if (isset($product->getData('quantity_and_stock_status')['is_in_stock'])) {
            $status=$product->getData('quantity_and_stock_status')['is_in_stock'];
        }
        if (isset($product->getData('quantity_and_stock_status')['qty'])) {
            $qty=$product->getData('quantity_and_stock_status')['qty'];
        }
        if ($status != null || $qty!=null) {

            if ($status==1) {
                $product->setFilterStock(1);
            } elseif ($status==0) {
                $product->setFilterStock(0);
            }
            if ($qty<=1 && $qty!=null) {
                $product->setFilterStock(0);
            }

        }
        return $this;
    }
}
