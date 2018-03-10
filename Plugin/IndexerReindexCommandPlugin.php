<?php
namespace Paulmillband\Reindex\Plugin;

use Magento\Framework\Indexer\StateInterface;
use Magento\Indexer\Model\Indexer\CollectionFactory;
use Magento\Indexer\Model\IndexerFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class IndexerReindexCommandPlugin
{
    /**
     * @var IndexerFactory
     */
    protected $indexerFactory;

    /**
     * @var CollectionFactory
     */
    protected $indexerCollectionFactory;

    /**
     * IndexerReindexCommandPlugin constructor.
     * @param IndexerFactory $indexerFactory
     * @param CollectionFactory $indexerCollectionFactory
     */
    public function __construct(
        IndexerFactory $indexerFactory,
        CollectionFactory $indexerCollectionFactory
    ) {
        $this->indexerFactory = $indexerFactory;
        $this->indexerCollectionFactory = $indexerCollectionFactory;
    }

    /**
     * echos a warning if magento has an index described as working after the reindex has finished. This is likely to be
     * a failed process
     *
     * @param Command $subject
     * @param callable $proceed
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    public function aroundRun(Command $subject, callable $proceed, InputInterface $input, OutputInterface $output)
    {
        $returnValue = $proceed($input, $output); // run original code
        $workingIndexers = [];

        if ($input->getArgument('command') !== "indexer:reindex") {
            return $returnValue;
        }

        $indexers = $this->indexerCollectionFactory->create();
        foreach ($indexers as $indexer) {
            if ($indexer->getStatus() == StateInterface::STATUS_WORKING) {
                $workingIndexers[] = $indexer->getTitle();
            }
        }
        $var = implode(', ', $workingIndexers);
        echo <<<EOT

==== WARNING: Not all indexes were run ====
Indexes marked as working found. These indexes may still be running with another process or have had a fatal
error before (e.g. time-out error). If this issue persists update the index_state table in your magento database.
For the relevant row set the status to valid or failed and try again.

Indexes not indexed: $var

EOT;
        return $returnValue;
    }
}
