# magento 2 manual reindex module
Adds a reindex option to the reindex page in the magento admin.

## Installation
- composer config repositories.paulmillband-m2-admin-reindex vcs git@github.com:yorick2/magento2-admin-reindex.git
- composer require paulmillband/m2-admin-reindex:dev-master
- composer update
- php bin/magento module:enable Paulmillband_Reindex
- php bin/magento setup:upgrade
- php bin/magento setup:di:compile

## Instruction

- go to system > tools > index management
- select the indexes to refresh
- select reindex from the dropdown
- select submit

![](index_page_screenshot.png)



