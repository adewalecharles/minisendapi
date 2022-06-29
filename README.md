## How to Install and Run

<p>
<ul>
<li>Clone this repo</li>
<li>Run composer install</li>
<li>In your .env file change your QUEUE_CONNECTION=database</li>
</ul>
</p>

<p>This project is tested with mailtrap, so you can setuup a mailtrap account and add the credentials to your .env or you can use mailersend.</p>

## How to test

<p>You can run test using php artisan test or by using postman, if you are using postman follow the instructions below:</p>

# Quick Documentation

## Installation

First you need to create an account in other to get a token, there are 2 tokens one for accessing the send mail endpoint and the other that our vuejs front end will use to authenticate.

# Quick API Documentation

## LOGIN

REQUEST: POST

ENDPOINT: /api/vi/login

Payload:

email -> email

password -> your_sercret_password

Sample Response:

```bash
{
    "status": true,
    "message": "success",
    "data": {
        "user": {
            "id": "2ce1e375-5422-4b88-a8ec-808d236f6a81",
            "username": "adewalecharles",
            "email": "test@mail.com",
            "joined": "3 hours ago"
        },
        "token": "2|WEttRuUpAbBI91dC922c5qBgfZ4Wq59rDLtSdHLo"
    }
}
```

## REGISTER

REQUEST: POST

ENDPOINT: /api/v1/register

Payload:

username ->

email ->

password ->

password_confirmation ->

Sample Response:

```bash
{
    "status": true,
    "message": "success",
    "data": {
        "token": "3|Lcen1cJjL6c6CFJhXdpFUvqb1lbcnrZZj6z87ZVG",
        "mail-token": "4|tvgk9VSlyWGtCcW7bXCS9NmaVpv9ibJl2fP8oSc3",
        "user": {
            "id": "5af00059-eff4-4907-b6b0-cf737924ae3d",
            "username": "tester",
            "email": "test@mail.com2",
            "joined": "1 second ago"
        }
    }
}
```

## GET ALL SENT MAILS
REQUEST: GET

ENDPOINT: /api/v1/get-emails

Authorization: Bearer token

Sample Response: 
```bash
{
    "status": true,
    "message": "success",
    "data": [
        {
            "id": "468c9afd-46dc-49a4-a8cd-53499a80718d",
            "from": "test@test.com",
            "to": "test@test.com",
            "subject": "A mail from beyond",
            "text": "Hello Man",
            "html": "<h1>Hello Man</h1>",
            "attachments": [],
            "status": [
                {
                    "id": 1,
                    "name": "Posted",
                    "statusable_type": "App\\Models\\Mail",
                    "statusable_id": 1,
                    "created_at": "2022-06-29T15:39:28.000000Z",
                    "updated_at": "2022-06-29T15:39:28.000000Z"
                }
            ]
        },
        {
            "id": "32717ff3-8d5e-4920-a540-d2890789af2b",
            "from": "test@test.com",
            "to": "test@test.com",
            "subject": "A mail from beyond",
            "text": "Hello Man",
            "html": "<h1>Hello Man</h1>",
            "attachments": [],
            "status": [
                {
                    "id": 2,
                    "name": "Posted",
                    "statusable_type": "App\\Models\\Mail",
                    "statusable_id": 2,
                    "created_at": "2022-06-29T15:44:05.000000Z",
                    "updated_at": "2022-06-29T15:44:05.000000Z"
                }
            ]
        },
        {
            "id": "6dc09ee1-d17f-4f7c-a7e3-523a237f2f14",
            "from": "test@test.com",
            "to": "test@test.com",
            "subject": "A mail from beyond",
            "text": "Hello Man",
            "html": "<h1>Hello Man</h1>",
            "attachments": [],
            "status": [
                {
                    "id": 3,
                    "name": "Posted",
                    "statusable_type": "App\\Models\\Mail",
                    "statusable_id": 3,
                    "created_at": "2022-06-29T15:45:03.000000Z",
                    "updated_at": "2022-06-29T15:45:03.000000Z"
                }
            ]
        },
        {
            "id": "9f9fd538-cedf-4bb2-bb3f-b4810e38e02b",
            "from": "test@test.com",
            "to": "test@test.com",
            "subject": "A mail from beyond",
            "text": "Hello Man",
            "html": "<h1>Hello Man</h1>",
            "attachments": [],
            "status": [
                {
                    "id": 4,
                    "name": "Posted",
                    "statusable_type": "App\\Models\\Mail",
                    "statusable_id": 4,
                    "created_at": "2022-06-29T18:57:10.000000Z",
                    "updated_at": "2022-06-29T18:57:10.000000Z"
                },
                {
                    "id": 5,
                    "name": "Sent",
                    "statusable_type": "App\\Models\\Mail",
                    "statusable_id": 4,
                    "created_at": "2022-06-29T18:57:20.000000Z",
                    "updated_at": "2022-06-29T18:57:20.000000Z"
                }
            ]
        }
    ]
}
```

## SEND MAIL

REQUEST: POST

ENDPOINT: /api/v1/email

payload: 
```bash
{
    "from": "test@test.com",
    "to" : "test@test.com",
    "subject" : "A mail from beyond",
    "text_content" : "Hello Man",
    "html_content" : "<h1>Hello Man</h1>",
    "webhook_url" : "https:webhook.com", (optional),
    "attachments" : [
    
    ]
}
```

Sample Response:

```bash
{
    "status": true,
    "message": "Your mail is being sent",
    "data": []
}
```

Note: due to the fact that the mails are being run in a job, you need to run your queue by running php artisan queue:work






