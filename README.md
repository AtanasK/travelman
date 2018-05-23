# Travelman backend project

## Run
- Make sure you have a db running and set in .env
- run `composer install`
- run `php artisan jwt:secret` to generate jwt secret
- serve with `php artisan serve`

## Endpoints

### Auth
- POST `/auth/login`
  - Body:
    - `email`
    - `password`
  - Response:
    - `access_token`
    - `token_type`
    - `expires_in`

### User
- GET `/user`

#### Password reset
- POST `/forgotpassword`
  - Body:
    - `email`
  - Response:
    - `success:true|error:errorMsg`
- POST `/forgotpassword/check`
  - Body:
    - `id` - user id
    - `token`
  - Response:
    - `status` - `true` if `id`-`token` combo is right
- POST `/forgotpassword/new`
  - Body:
    - `id` - user id
    - `token`
    - `password` - new password
  - Response:
    - `status` - `true/false`

### Locations
...