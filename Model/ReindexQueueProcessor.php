<?php


namespace ForFin\AdminReindex\Model;


use Magento\Framework\Console\Cli;
use Magento\Indexer\Console\Command\IndexerReindexCommand;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ReindexQueueProcessor
{

    /**
     * @var Cli
     */
    private $cli;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * ReindexQueueProcessor constructor.
     * @param Cli $cli
     * @param LoggerInterface $logger
     */
    public function __construct(Cli $cli, LoggerInterface $logger)
    {
        $this->cli = $cli;
        $this->logger = $logger;
    }

    /**
     * @param array $indexIds
     * @throws \Exception
     */
    public function processQueue(array $indexIds)
    {
        $time = \microtime(true);
        $this->logger->debug('Start reindex');
        $this->cli->run(
            new ArrayInput([
                'command' => 'indexer:reindex',
                IndexerReindexCommand::INPUT_KEY_INDEXERS => $indexIds
            ]),
            new NullOutput()
        );
        $this->logger->debug('Reindex finished');
        $this->logger->debug('Time: ' . (\microtime(true) - $time));
    }

}
