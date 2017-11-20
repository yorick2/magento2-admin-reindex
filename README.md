# magento 2 manual reindex module
Adds a reindex option to the reindex page in the magento admin.

system > tools > index management

![](index_page_screenshot.png)

## Installation
php bin/magento module:enable Paulmillband_Reindex

php bin/magento setup:upgrade

php bin/magento setup:di:compile;

