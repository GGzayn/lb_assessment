
# LEADBOOK Assessment

## About Repository

Lead Book Assessment Laravel 8 with VueJs 2 CRUD API

## Tech Specification

- Laravel 8
- Vue 2 + VueRouter + vue-progressbar + sweetalert2 + laravel-vue-pagination
- Laravel Passport
- Admin LTE 3 + Bootstrap 4 + Font Awesome 5
- PHPUnit Test Case/Test Coverage

## Features

- Modal based Create+Edit, List with Pagination, Delete with Sweetalert
- Login, Register, Forget+Reset Password as default auth
- Profile, Update Profile, Change Password, Avatar
- Company Management 
- User Management
- Frontend and Backend User ACL with Gate Policy (type: admin/user)
- Simple Static Dashboard
- Build with Docker

## Install with Docker

- `git clone https://github.com/GGzayn/lb_assessment.git`
- `cd lb_assessment/`
- `sail up -d`
- `sail composer install`
- `sail artisan key:generate`
- `sail artisan migrate`
- `sail artisan db:seed`
- `sail artisan passport:install`
- Application http://localhost/
- Mailhog Dashboard http://localhost:8025/ (for checking email locally)


## Unit Test

#### run PHPUnit

```bash
# run PHPUnit all test cases
sail test
```

#### Code Coverage Report

```bash
# reports is a directory name
sail test --coverage-html reports/
```
A `reports` directory has been created for code coverage report. Open the dashboard.html.

