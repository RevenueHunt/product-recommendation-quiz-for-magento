> ⚠️ **WARNING:** Support for the Product Recommendation Quiz for the Magento module has been discontinued. If you want to add the Product Recommendation Quiz to your store, we highly recommend using our [standalone solution](https://revenuehunt.com/product-recommendation-quiz-standalone-google-product-feed/) or migrating your store to [Shopify](https://revenuehunt.com/product-recommendation-quiz-shopify/) or [WooCommerce](https://revenuehunt.com/product-recommendation-quiz-woocommerce/).

# Product Recommendation Quiz for Magento

A quiz that does the selling for you. Recover abandoned carts, capture leads and grow your audience.

## Main Functionalities
This module is a Product Recommendation Quiz for Magento 2. It's developed and maintained by [RevenueHunt](https://revenuehunt.com). At RevenueHunt we build tools which help merchants grow their eCommerce by driving traffic, conversions and sales.

**Your quiz will help you achieve four goals:**

1. **Boost sales** on the first visit to your store by giving your customers a personalized product recommendation.
2. **Recover abandoned carts**. Quiz results are sent to your customer's inbox so they can come back later and finish the purchase.
3. **Grow your audience**. Captured leads are sent to your mailing list, so you can segment them based on their responses and follow up with targeted campaigns.
4. **Reduce Support costs** by educating your customers about your products (the quiz does the heavy lifting in terms of support and sales for you).

## Description

Your Product Recommendation Quiz does the selling for you. It's like having **a personal shopper on your Magento store**, guiding your customers from start to cart and helping them find the products that best match their needs.

[![Product Recommendation Quiz for Magento](https://img.youtube.com/vi/38niHET5cAU/0.jpg)](http://www.youtube.com/watch?v=38niHET5cAU)

### A PERSONAL SHOPPER FOR YOUR MAGENTO SHOP

We believe that being engaged by a salesperson is key to a successful shopping experience. After all, your customers might not be experts in the products you're selling and they might not know which ones best match their needs.

Online shoppers need guidance on finding what they want, just like in real shops.

### HOW OUR PRODUCT RECOMMENDATION QUIZ WORKS

A fresh alternative to the obsolete "Search Bar" in Magento stores, our digital personal shopper engages your customers just like a salesperson would do, guiding them from start to cart and ensuring they find exactly what they're looking for.

Our plugin allows the customers to play an active role in the buying experience. The quiz asks your customers a series of questions, analyzes their responses and returns a selection of recommended products that match their needs along with an explanation to why they're the right fit for them.

### CUSTOMIZING THE QUIZ TO YOUR PARTICULAR NEEDS

Just like every customer is unique, we believe every brand has its own personality. Our plugin allows you to customize multiple aspects of the quiz. With our easy-to-use quiz builder you can:

* Define the questions you ask
* Personalize the feedback you give
* Choose within multiple fonts and color palettes
* Choose between multiple display modes (popup, inline or automatic)

### SOME OF OUR KEY FEATURES INCLUDE

* **Plug and play**. Easy to install and configure. No coding required. Sell more effectively within minutes, not weeks.
* **Conditional Logic**. Your customers never have to skip irrelevant questions because with conditional logic rules, they’ll never even see them. This makes the experience easier, with higher completion rates, through a more personal, human interaction.
* **Lead capture**. Incentivize your customers to leave their contact details (for following-up or retargeting).

### WHEN IS A PRODUCT RECOMMENDATION QUIZ USEFUL?

Shops where their customers are overloaded with choices are a great fit, a few cases where our plugin can provide value are:

* When people want to make a quick an easy choice (e.g.: gifts)
* The product is complex or it's difficult to compare alternatives
* When buyers do not have clear preferences about what they want

There are several shopping verticals that are inherently more suited for a Product Recommendation Quiz. These include:

* Skincare, Cosmetics & Beauty
* Nutrition, Vitamins & Supplements
* Clothing, Jewelry & Accessories
* Sports & Outdoor Goods
* Specialized Equipment & Supplies

### INTEGRATION WITH THIRD PARTY APPS

* Google Analytics
* Facebook Pixel
* Mailchimp
* Klaviyo
* HubSpot
* Omnisend
* ActiveCampaign
* Webhooks
* And many more third-party apps via [Zapier](https://zapier.com/ "Connect your apps and automate workflows")

### See the plugin in action

Explore how the plugin works in our [demo store](https://skincarequiz.myshopify.com/ "Demo Skincare Store with Quiz")


### WALKTHROUGH

* Install and activate the module if you haven’t already done so
* In your Magento dashboard, navigate to **STORES > Stores > Settings > Configuration > SERVICES > Magento Web API > Web API Security > Allow Anonymous Guest Access : Yes** [See Devdocs Reference](https://devdocs.magento.com/guides/v2.3/rest/anonymous-api-security.html)
* In your Magento dashboard, navigate to **Marketing > Product Recommendation Quiz**
* Grant permissions to connect our plugin to your Magento store
* Follow the **Success Checklist** to create and publish your quiz
* Drive traffic to your quiz and start getting sales and leads!

![Product Recommendation Quiz for Magento](https://revenuehunt.com/wp-content/uploads/2022/02/magento-modules-dashboard.png)


## Installation

First, download the latest version of our module [here](https://github.com/RevenueHunt/product-recommendation-quiz-for-magento/archive/refs/heads/master.zip "Product Recommendation Quiz for Magento").

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


## Technical Specifications

* You website must have a valid HTTPS/SSL certificate installed.
* Does not work on local/development environments.

   `revenuehunt/module-productquiz`

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Attributes](#markdown-header-attributes)

 - API Endpoint
	- POST - Revenuehunt\ProductQuiz\Api\PrqSetTokenManagementInterface > Revenuehunt\ProductQuiz\Model\PrqSetTokenManagement

 - Controller
	- adminhtml > prqfw/index/index

## MiniCart integration

The MiniCart integration is disabled by default. If your theme uses MiniCart, you can uncomment the code in this file:

[view/frontend/layout/default.xml](https://github.com/RevenueHunt/product-recommendation-quiz-for-magento/blob/master/view/frontend/layout/default.xml)

`<block class="Magento\Framework\View\Element\Template" name="revenuehunt-script" template="Revenuehunt_ProductQuiz::head/js.phtml" />`

## Frequently Asked Questions

Please refer to our [FAQs page](https://revenuehunt.com/faqs/ "Frequently Asked Questions") for more information.