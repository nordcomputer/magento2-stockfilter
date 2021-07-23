# Magento 2 Module Nordcomputer Stockfilter

    ``nordcomputer/module-stockfilter``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)


## Main Functionalities
Enabels filtering by stock status

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Nordcomputer`
 - Enable the module by running `php bin/magento module:enable Nordcomputer_Stockfilter`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Add the composer repository to the configuration by running `composer config repositories.nordcomputer/module-stockfilter git "git@github.com:nordcomputer/magento2-stockfilter.git"`
 - Install the module composer by running `composer require nordcomputer/module-stockfilter`
 - enable the module by running `php bin/magento module:enable Nordcomputer_Stockfilter`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

You can find the configuration for this extension in `Stores -> Configuration -> Catalog -> Inventory -> Stock Filter Cronjob Configuration`

The Cronjob iterates over all simple products and sets the newly created attribute "filter-stock" according to the stock status of the product.
