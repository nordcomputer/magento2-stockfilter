<?php

namespace Nordcomputer\Stockfilter\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;

class UpdateStockFilter
{
    private $logger;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
    }
    public function execute()
    {
        if ($this->scopeConfig->getValue('cataloginventory/cronjobs/is_enabled')==1) {
            $connection = $this->resourceConnection->getConnection();
            $table = $connection->getTableName('catalog_product_entity_int');
            // Update query
            $query = "UPDATE " . $table . " t
            JOIN cataloginventory_stock_status a ON a.product_id = t.entity_id
            JOIN eav_attribute ap ON ap.attribute_id = t.attribute_id
            SET value = stock_status WHERE attribute_code = 'filter_stock'";
            $connection->query($query);
        }
        return $this;
    }

}
