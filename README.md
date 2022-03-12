## About Project
In this project, I have used Laravel Framework Version is 8.75

For API I have used Laravel Passport Version is 10.3

## Installation

We need to install composer for all packages.

Here is the complete instruction for how to install the composer.

**[https://getcomposer.org/doc/00-intro.md](https://getcomposer.org/doc/00-intro.md)**

Let's install all packages, by running this command from Terminal

```
composer install
```

## Configuration

For configuring the project please follow the below steps,

Copy .env from the .env.example file using below command

```bash
cp .env.example .env
```

Go to .ENV File, Set DB_USERNAME & DB_PASSWORD of your phpmyadmin login credentials & set Database name in DB_DATABASE

```bash
DB_DATABASE=api_auth
DB_USERNAME=root
DB_PASSWORD=
```

Open your terminal OR command prompt and run bellow command in project directory

```bash
php artisan migrate
```

You need to run the following command to run a single seeder for Admin User:

```bash
php artisan db:seed
```

After that you need to run below command for passport API, it will create token keys for security.

```bash
php artisan passport:install
```

Now we are ready to to run full restful api and also passport api in laravel. so let's run our example so run bellow command for quick run:

```bash
php artisan serve
```


## API Document

- **Login API (Method=POST)**

```bash
URL: http://localhost:8000/api/login
```

BODY Parameter as a formdata

```bash
email:
password:
```

For ```Admin Login``` there is a predefined credentails, I have create ```database seeder``` for admin user. Please find below credentials for admin login from login API.

```bash
email:admin@gmail.com
password:12345678
```


- **Logout API (Method=POST)**
```bash
URL: http://localhost:8000/api/logout
```
HEADERS
```bash
Accept:application/json
Authorization: Bearer {{token_value}}
```

- **Register API (Method=POST)**

```bash
URL: http://localhost:8000/api/register
```

BODY Parameter as a formdata

```bash
name:
email:
password:
c_password:
```

- **Apply Loan API (Method=POST)**
```bash
URL: http://localhost:8000/api/loan_store
```
HEADERS
```bash
Accept:application/json
Authorization: Bearer {{token_value}}
```

BODY Parameter as a formdata

```bash
amount:
loan_term:
```

- **Get Loan Details API (Method=GET)**
```bash
URL: http://localhost:8000/api/show_loan_details/{{loan_id}}
```
HEADERS
```bash
Accept:application/json
Authorization: Bearer {{token_value}}
```

- **Approval Loan By Admin API (Method=POST)**
```bash
URL: http://localhost:8000/api/approval_loan_by_admin
```
HEADERS
```bash
Accept:application/json
Authorization: Bearer {{token_value}}
```

BODY Parameter as a formdata

```bash
loan_id:
```

- **Paid Loan API (Method=POST)**
```bash
URL: http://localhost:8000/api/paid_loan
```
HEADERS
```bash
Accept:application/json
Authorization: Bearer {{token_value}}
```

BODY Parameter as a formdata

```bash
loan_id:
amount:
```