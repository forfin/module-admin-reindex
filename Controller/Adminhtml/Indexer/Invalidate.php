<?php

namespace ForFin\AdminReindex\Controller\Adminhtml\Indexer;


use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Indexer\IndexerRegistry;

/**
 * Class Reindex
 * @package ForFin\AdminReindex\Controller\Adminhtml\Indexer
 */
class Invalidate extends Action
{

    const ADMIN_RESOURCE = 'ForFin_AdminReindex::reindex';

    /**
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * Invalidate constructor.
     * @param Action\Context $context
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        Action\Context $context,
        IndexerRegistry $indexerRegistry
    ) {
        parent::__construct($context);
        $this->indexerRegistry = $indexerRegistry;
    }

    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addErrorMessage(__('Please select indexers.'));
        } else {
            try {
                foreach ($indexerIds as $indexerId) {
                    $this->indexerRegistry->get($indexerId)->invalidate();
                }
                $this->messageManager->addSuccessMessage(__('Total of %1 index(es) have been invalidate.', count($indexerIds)));
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Cannot initialize the indexer process.'));
            }
        }
        $this->_redirect('*/*/list');
    }
}