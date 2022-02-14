# Product Recommendation Quiz for Magento

    ``revenuehunt/module-productquiz``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)


## Main Functionalities
This module/extension is a Product Recommendation Quiz for Magento 2. It's developed and maintained by [RevenueHunt](https://revenuehunt.com)

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in `app/code/Revenuehunt`
 - Enable the module by running `php bin/magento module:enable Revenuehunt_ProductQuiz`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer

 - Make the module available in a composer repository for example:
    - private repository `repo.magento.com`
    - public repository `packagist.org`
    - public github repository as vcs
 - Add the composer repository to the configuration by running `composer config repositories.repo.magento.com composer https://repo.magento.com/`
 - Install the module composer by running `composer require Revenuehunt/module-productquiz`
 - enable the module by running `php bin/magento module:enable Revenuehunt_ProductQuiz`
 - apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Api URL Test (product_quiz/general/prq_api_url_test)

 - prq_admin_url_test (product_quiz/general/prq_admin_url_test)

 - Is Test mode (product_quiz/general/prq_is_test)

 - API URL (product_quiz/general/prq_api_url)

 - Admin URL (product_quiz/general/prq_admin_url)

 - RH Domain (product_quiz/hidden/rh_domain)

 - rh_api_key (product_quiz/hidden/rh_api_key)

 - rh_token (product_quiz/hidden/rh_token)

 - rh_shop_hashid (product_quiz/hidden/rh_shop_hashid)


## Specifications

 - API Endpoint
	- POST - Revenuehunt\ProductQuiz\Api\PrqSetTokenManagementInterface > Revenuehunt\ProductQuiz\Model\PrqSetTokenManagement

 - Controller
	- adminhtml > prqfw/index/index


## Attributes



