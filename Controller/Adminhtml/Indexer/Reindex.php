<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Paulmillband\Reindex\Controller\Adminhtml\Indexer;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Indexer\IndexerRegistry;

class Reindex extends Action
{
    /**
     * Turn view off for the given indexers
     *
     * @return void
     */
    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
            try {
                foreach ($indexerIds as $indexerId) {
                    /** @var \Magento\Framework\Indexer\IndexerInterface $model */
                    $model = $this->_objectManager->get(
                        IndexerRegistry::class
                    )->get($indexerId);
                    $model->reindexAll();
                }
                $this->messageManager->addSuccess(
                    __('%1 indexer(s) have been refreshed', count($indexerIds))
                );
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException(
                    $e,
                    __("Reindex failed")
                );
            }
        }
        $this->_redirect('*/*/list');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Paulmillband_Reindex::adminreindex');
    }
}
