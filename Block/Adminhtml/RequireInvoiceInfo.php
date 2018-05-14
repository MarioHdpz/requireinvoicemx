<?php

namespace Pengo\RequireInvoiceMx\Block\Adminhtml;

use Magento\Framework\View\Element\Template;

class RequireInvoiceInfo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
     protected $_coreRegistry;

    /**
     * RequireInvoiceInfo constructor.
     * @param Template\Context $context
     * @param array $data
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Template\Context $context,
        array $data = [],
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
    }

    /**
     * Retrieve order model object
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('sales_order');
    }

    public function getRequiredInvoice() {
        return $this->getOrder()->getRequiredInvoice();
    }

}