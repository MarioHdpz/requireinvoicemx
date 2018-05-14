<?php


namespace Pengo\RequireInvoiceMx\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

class InstallData implements InstallDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var SalesSetupFactory
     */
    private $salesSetupFactory;

    /**
     * InstallData constructor.
     * @param CustomerSetupFactory $customerSetupFactory
     * @param SalesSetupFactory $salesSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        SalesSetupFactory $salesSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        /** @var \Magento\Customer\Setup\CustomerSetup $eavSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        // Add address attributes

        $attributesInfo = [
            'rfc' => [
                'label' => __('RFC'),
                'input' => 'text',
                'required' => 0,
                'default' => '',
                'sort_order' => 50,
                'system' => false,
                'user_defined' => 1
            ],
            'business_name' => [
                'label' => __('Business Name'),
                'input' => 'text',
                'required' => 0,
                'default' => '',
                'sort_order' => 60,
                'system' => false,
                'user_defined' => 1
            ]
        ];

        foreach ($attributesInfo as $attributeCode => $attributeParams) {
            $customerSetup->addAttribute('customer_address', $attributeCode, $attributeParams);
        }

        $rfcAttribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'rfc');

        $rfcAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );

        $rfcAttribute->save();

        $businessNameAttribute = $customerSetup->getEavConfig()->getAttribute('customer_address', 'business_name');

        $businessNameAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );

        $businessNameAttribute->save();

        // Add required_invoice order attribute

        /** @var \Magento\Sales\Setup\SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['setup' => $setup]);

        $attributes = [
            'required_invoice' => [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'comment' =>__('Required Invoice')
            ]
        ];

        foreach ($attributes as $attributeCode => $attributeParams) {
            $salesSetup->addAttribute('order', $attributeCode, $attributeParams);
        }

        $setup->endSetup();
    }
}
