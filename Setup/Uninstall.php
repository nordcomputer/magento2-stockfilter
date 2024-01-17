<?php
namespace Nordcomputer\Stockfilter\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UninstallInterface;

class Uninstall implements UninstallInterface
{
    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * Method __construct
     *
     * @param EavSetupFactory $eavSetupFactory
     *
     * @return void
     */

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Method uninstall
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */

    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create();

        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'filter_stock');

        $setup->endSetup();
    }
}
