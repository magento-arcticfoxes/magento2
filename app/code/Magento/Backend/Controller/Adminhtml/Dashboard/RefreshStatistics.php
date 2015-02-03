<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Backend\Controller\Adminhtml\Dashboard;

class RefreshStatistics extends \Magento\Reports\Controller\Adminhtml\Report\Statistics
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     * @param \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory
     * @param array $reportTypes
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter,
        \Magento\Backend\Model\View\Result\RedirectFactory $resultRedirectFactory,
        array $reportTypes
    ) {
        $this->logger = $logger;
        parent::__construct($context, $dateFilter, $resultRedirectFactory, $reportTypes);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        try {
            $collectionsNames = array_values($this->reportTypes);
            foreach ($collectionsNames as $collectionName) {
                $this->_objectManager->create($collectionName)->aggregate();
            }
            $this->messageManager->addSuccess(__('We updated lifetime statistic.'));
        } catch (\Exception $e) {
            $this->messageManager->addError(__('We can\'t refresh lifetime statistics.'));
            $this->logger->critical($e);
        }
        return $this->resultRedirectFactory->create()->setPath('*/*');
    }
}
