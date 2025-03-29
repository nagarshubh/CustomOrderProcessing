# Vendor CustomOrderProcessing Module

## Overview
This Magento 2 module enhances order processing workflow by providing:
- Custom API endpoint for order status updates
- Order status change logging to a custom table(vendor_order_status_log)
- Shipped order email notifications

## Features
- REST API endpoint to update order status
- Event observer to log status changes
- Email notification for shipped orders on every shipment

## Installation
1. Copy module files to `app/code/Vendor/CustomOrderProcessing`. If any folder is not present please create the folder.
2. Run `bin/magento setup:upgrade`
3. Run `bin/magento setup:di:compile`
4. Run `bin/magento setup:static-content:deploy`
5. Run `bin/magento cache:clean`

If module is not enabled enable the module by running:
`bin/magento module:enable Vendor_CustomOrderProcessing`

## API Usage
Generate Admin Token:
Endpoint: `{Your Base Url}/rest/V1/integration/admin/token`
Method: POST
Request Body:
```json
{
  "username": "admin",
  "password": "admin123"
}
```
Response:
"eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJ1aWQiOjEsInV0eXBpZCI6MiwiaWF0IjoxNzQzMjQyOTM3LCJleHAiOjE3NDMyNDY1Mzd9.PSGrG867y7Okp3sFy_mQwmL01yQmYnVIhV4QWhknY_U"

Update order Status API:

Endpoint: `{Your Base Url}/rest/V1/orders/updateStatus`
Method: POST
Authentication: Bearer Token

Request Body:
```json
{
    "increment_id": "000000001",
    "status": "custom"
}
```

Same API request with more restricted body params which could improve performace becuase of request interface

Endpoint: `{Your Base Url}/rest/V1/orders/updateStatusByRequestInterface`
Method: POST
Authentication: Bearer Token

Request Body:
```json
{
    "statusUpdate": {
        "increment_id": "000000001",
        "status": "custom"
    }
}
```

## Architectural Decisions
- Used Repository pattern for order operations
- Implemented dependency injection
- Followed Magento 2 coding standards
- Used event observers for extensibility
- Added custom logging table for status changes

## Performance Considerations
- Minimized database operations
- Used repository instead of direct SQL
- Lightweight observer implementation

## Requirements
- Magento 2.4.x
- PHP 7.4+
