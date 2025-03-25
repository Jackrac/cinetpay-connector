# CinetPay Connector (For Wordpress)

CinetPay Connector is a lightweight PHP library designed to simplify the integration of the CinetPay payment gateway into your Wordpress APP. This connector provides few easy-to-use scripts to initiate payments, verify transaction statuses, and handle payment responses, letting you focus on building your application.

## Features

- **Simple Integration:** Quickly add CinetPay payment capabilities to your Wordpress applications.
- **Flexible Configuration:** Easily set up your API credentials and customize the connector for your needs.
- **Transaction Management:** Initiate payments and verify transactions with minimal code.
- **Error Handling:** Built-in error management to help you gracefully manage issues.
- **Extensible:** Designed to be modified and extended to suit your unique application requirements.

## Requirements

- PHP 7.0 or higher
- cURL extension enabled in PHP

## Installation

Install the scripts in a folder new folder at the root of your Wordpress website :

```bash
git clone https://github.com/Jackrac/cinetpay-connector.git
```

## Configuration

Before using the connector, set up your CinetPay credentials. Open the utils.php script and add your API settings:
Then put the link to the pay.php script in your FluentForm

```php
<?php
    // Chargement de l'environnement Wordpress
    require __DIR__ . '/../wp-blog-header.php';

    // Constantes
    $apikey = "YOUR_APIKEY";
    $site_id = "YOUR_SITEID";
?>
```
