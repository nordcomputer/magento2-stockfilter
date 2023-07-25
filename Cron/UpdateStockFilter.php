<?php
namespace Nordcomputer\Stockfilter\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\Framework\Module\Manager;
use Magento\Framework\ObjectManagerInterface;

class UpdateStockFilter
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var ResourceConnection */
    protected $resourceConnection;

    /** @var LoggerInterface */
    protected $logger;

    /** @var StockRepositoryInterface */
    protected $stockRepository;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param ResourceConnection $resourceConnection
     * @param Manager $moduleManager
     * @param ObjectManagerInterface $objectManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResourceConnection $resourceConnection,
        Manager $moduleManager,
        ObjectManagerInterface $objectManager,
        LoggerInterface $logger
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->stockRepository = null;

        if ($moduleManager->isEnabled('Magento_InventoryApi')) {
            $this->stockRepository = $objectManager->get(StockRepositoryInterface::class);
        }
    }
    /**
     * Executes Cronjob for updating 'stock_filter' parameter
     */
    public function execute()
    {
        if ($this->scopeConfig->getValue('cataloginventory/cronjobs/is_enabled')==1) {
            $connection = $this->resourceConnection->getConnection();
            $table = $connection->getTableName('catalog_product_entity_int');
            // Update query
            if ($this->getNumberOfStocks() > 1) {
                // MSI fix in case of more than one stock. Source: https://magento.stackexchange.com/a/332557
                $query = "UPDATE " . $table . " AS t
                JOIN eav_attribute ea ON ea.attribute_id = t.attribute_id
                JOIN
                    (SELECT t.value_id,t.entity_id,t.value,MAX(a.quantity)
                    AS max_qty, MAX(a.status) AS max_status,MIN(a.status) AS min_status
                    FROM catalog_product_entity_int t
                    JOIN catalog_product_entity cpe ON cpe.entity_id = t.entity_id
                    JOIN inventory_source_item a ON a.sku = cpe.sku
                    JOIN eav_attribute ap ON ap.attribute_id = t.attribute_id
                    WHERE attribute_code = 'filter_stock'
                    GROUP BY t.entity_id)
                    AS TEMP_TABLE ON TEMP_TABLE.value_id = t.value_id SET t.value = IF(TEMP_TABLE.max_qty > 0
                    AND TEMP_TABLE.max_status = 1, 1, 0);";
            } else {
                // Non-MSI query
                $query = "UPDATE " . $table . " t
                JOIN cataloginventory_stock_status a ON a.product_id = t.entity_id
                JOIN eav_attribute ap ON ap.attribute_id = t.attribute_id
                SET value = stock_status WHERE attribute_code = 'filter_stock'";
            }
            $connection->query($query);
        }
        return $this;
    }

    /**
     * Get Number of stocks
     *
     * @return null|int
     */
    public function getNumberOfStocks(): ?int
    {
        // Is MSI enabled?
        if ($this->stockRepository == null) {
            return 0;
        }
        try {
            return $this->stockRepository->getList()->getTotalCount();
        } catch (\Exception $exception) {
            $error = 'Nordcomputer_Stockfilter: Error while getting number of stocks: ' . $exception->getMessage();
            $this->logger->error($error);
        }
        return 0;
    }
}
