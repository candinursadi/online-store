## Case

Based on these facts, it is known that the stock misreported was due to the race conditions at 12.12 event.

## Solution

The solution is to implement a queuing system for purchases or payments.

## Technical Solution

### Product Controller
API controller : /Http/Controllers/ProductController

| Service Name  | Service API |
| ------------- | ------------- |
| get product | {{baseUrl}}/product  |

### Get Product (GET Method)
**Request :**

**Response Success :**
``` 
{
    "responseCode": "00",
    "responseMessage": "Success",
    "data": [
        {
            "id": 1, | Product ID
            "name": "Apple",
            "price": 1500,
            "stock": 4
        },
        {
            "id": 2,
            "name": "Banana",
            "price": 2000,
            "stock": 4
        },
        {
            "id": 3,
            "name": "Cherry",
            "price": 2500,
            "stock": 11
        }
    ]
}
```

### Cart Controller
API controller : /Http/Controllers/ProductController

| Service Name  | Service API |
| ------------- | ------------- |
| get cart | {{baseUrl}}/cart  |
| add to cart | {{baseUrl}}/cart/add  |
| payment | {{baseUrl}}/cart/payment  |

### Get Cart (POST Method)
**Request :**
``` 
{
    "user_id" : 1
}
```
**Response Success :**
``` 
{
    "responseCode": "00",
    "responseMessage": "Success",
    "data": {
        "cart_id": 14,
        "items": [
            {
                "id": 1, | Product ID
                "name": "Apple",
                "price": 1500,
                "qty": 2,
                "total": 3000
            },
            {
                "id": 2,
                "name": "Banana",
                "price": 2000,
                "qty": 1,
                "total": 2000
            }
        ]
    }
}
```
**Response failed :**
``` 
{
    "responseCode": "05",
    "responseMessage": "Cart not found"
}
```

### Add to Cart (POST Method)
**Request :**
``` 
{
    "user_id" : 1,
    "data": [
        {
            "id": 1, | Product ID
            "qty": 2 | Quantity Product
        },
        {
            "id": 2,
            "qty": 1
        }
    ]
}
```
**Response Success :**
``` 
{
    "responseCode": "00",
    "responseMessage": "Success",
    "data": {
        "cart_id": 14,
        "items": [
            {
                "id": 1, | Product ID
                "name": "Apple",
                "price": 1500,
                "qty": 2,
                "total": 3000
            },
            {
                "id": 2,
                "name": "Banana",
                "price": 2000,
                "qty": 1,
                "total": 2000
            }
        ]
    }
}
```
**Response failed :**
``` 
{
    "responseCode": "04",
    "responseMessage": "Product quantity exceeds available stock"
}
```

### Payment (POST Method)
**Request :**
``` 
{
    "user_id" : 2,
    "cart_id" : 14
}
```
**Response Success :**
``` 
{
    "responseCode": "00",
    "responseMessage": "Success",
    "data": {
        "order_id": 14,
        "status": "PENDING",
        "invoice": "1612170468",
        "items": [
            {
                "id": 1, | Product ID
                "name": "Apple",
                "price": 1500,
                "qty": 2,
                "total": 3000
            },
            {
                "id": 2,
                "name": "Banana",
                "price": 2000,
                "qty": 1,
                "total": 2000
            }
        ]
    }
}
```
**Response failed :**
``` 
{
    "responseCode": "07",
    "responseMessage": "Bill already paid"
}
```

### Mapping Error
| Response Code | HTTP Code | Response Message |
| ------------- | ------------- | ------------- |
| 00  | 200 | Success |
| 01  | 200 | User not found |
| 02  | 200 | Product not found |
| 03  | 200 | Product quantity exceeds available stock |
| 04  | 200 | Cart not found |
| 05  | 200 | No product found in cart |
| 06  | 200 | No product found in cart |
| 07  | 200 | Bill already paid |
| 99  | 200 | An error has occurred |

## Note

The queue system or job queue is applied to payments using an asynchronous system with the initial status of payment is PENDING.
To run the queue, please do the following command:
``` 
php artisan queue:work --queue=payment
```

## Database

Please import the following file:
``` 
online_store.sql
```
Schema database, please import the following file:
``` 
schema_online_store.pdf
```

## Logging

Logging request response is stored in the following table:
``` 
log
```
Jobs Queue is stored in the following table:
``` 
jobs, failed_jobs
```
