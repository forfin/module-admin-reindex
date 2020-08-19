<?php


namespace ForFin\AdminReindex\Controller\Adminhtml\Indexer;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\MessageQueue\PublisherInterface;

/**
 * Class Reindex
 * @package ForFin\AdminReindex\Controller\Adminhtml\Indexer
 */
class Reindex extends Action
{

    const ADMIN_RESOURCE = 'ForFin_AdminReindex::reindex';

    /** @var PublisherInterface */
    private $messagePublisher;

    /**
     * Reindex constructor.
     * @param Action\Context $context
     * @param PublisherInterface $messagePublisher
     */
    public function __construct(
        Action\Context $context,
        PublisherInterface $messagePublisher
    ) {
        parent::__construct($context);
        $this->messagePublisher = $messagePublisher;
    }

    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addErrorMessage(__('Please select indexers.'));
        } else {
            try {
                $this->messagePublisher->publish('forfin.reindex.topic', $indexerIds);
                $this->messageManager->addSuccessMessage(__('Total of %1 index(es) added to reindex queue.', count($indexerIds)));
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Cannot initialize the indexer process.'));
            }
        }
        $this->_redirect('*/*/list');
    }
}