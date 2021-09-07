# Magento 2 Module Nordcomputer Stockfilter
<img src="https://img.shields.io/github/v/release/nordcomputer/magento2-stockfilter"> <img src="https://img.shields.io/badge/magento-v2.4.2-green?style=plastic&logo=magento"> <img src="https://img.shields.io/codacy/grade/98f24256a6aa4cb683aa6652b4370d77"> <img src="https://img.shields.io/github/issues/nordcomputer/magento2-stockfilter"> <img src="https://img.shields.io/github/forks/nordcomputer/magento2-stockfilter"> <img src="https://img.shields.io/github/stars/nordcomputer/magento2-stockfilter">

    ``nordcomputer/module-stockfilter``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)


## Main Functionalities
Enabels filtering by stock status

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Nordcomputer` and rename the directory to `Stockfilter`
 - Enable the module by running `php bin/magento module:enable Nordcomputer_Stockfilter`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Add the composer repository to the configuration by running `composer config repositories.nordcomputer/module-stockfilter git "git@github.com:nordcomputer/magento2-stockfilter.git"`
 - Install the module composer by running `composer require nordcomputer/module-stockfilter`
 - enable the module by running `php bin/magento module:enable Nordcomputer_Stockfilter`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

## Steps after installation (IMPORTANT)

- Since this extension does not set the attribute for existing products (the value stays empty after installation), you need to set the      attribute to `Yes` for every simple product (you can do so by mass-action).
- Also every new created product needs to be set to `Yes` (Default value) for at least one time, otherwise, the attribute for that specific product does not get created.

Once the attribute was set to `Yes` at least one time, the cronjob does his thing and sets the value automatically with every run.

## Configuration

You can find the configuration for this extension in `Stores -> Configuration -> Catalog -> Inventory -> Stock Filter Cronjob Configuration`

The Cronjob iterates over all simple products and sets the newly created attribute "filter-stock" according to the stock status of the product.


## Uninstalling

As this extension creates an attribute, the attribute needs to be removed when the extension get uninstalled.

# Method 1 (installed via composer)
You can uninstall this extension by running 'bin/magento module:uninstall Nordcomputer_Stockfilter --remove-data'

# Method 2 (installed via Download)
- delete the `Stockfilter` directory in `/app/code/Nordcomputer`
- delete `filter_stock` from the `eav_attribute` table in your database
- run following commands:

  ```bin/magento setup:upgrade
  bin/magento setup:di:compile
  bin/magento setup:static-content:deploy -f
  bin/magento indexer:reindex
  bin/magento c:f
  ```
