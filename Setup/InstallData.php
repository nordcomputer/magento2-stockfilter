<?php
namespace Nordcomputer\Stockfilter\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    /**
     *
     * @param Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs filter_stock attribute
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'filter_stock');

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'filter_stock',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'In Stock',
                'input' => 'select',
                'class' => '',
                'source' => \Nordcomputer\Stockfilter\Model\Config\Source\Options::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'default' => 1,
                'searchable' => false,
                'filterable' => true,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'is_filterable_in_grid'=> true,
                'unique' => false,
                'apply_to' => 'simple',
                'is_user_defined' => true
            ]
        );
    }
}
