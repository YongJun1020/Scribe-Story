# Scribe & Story

A Medium-like blogging platform where users can read and publish articles, follow other writers, and interact through likes.

## Features

- Browse and read articles on the homepage
- Create, edit, and delete your own posts
- Follow/unfollow other users
- Like posts
- Public author profiles
- Category-based article filtering
- Email notifications

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Running the Application

```bash
# Start the development server
composer run dev

# Run the queue worker (in a separate terminal)
php artisan queue:listen
```

## Mailpit (Email Testing)

This project uses Mailpit for local email testing. Visit http://localhost:8025 to view emails.

For installation instructions, see: https://mailpit.axllent.org/docs/install/

After installation, run Mailpit in its folder:

```bash
mailpit
```
