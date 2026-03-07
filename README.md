# Simi Central

[![CI](https://github.com/sicaboy/simi-central/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/sicaboy/simi-central/actions/workflows/ci.yml)

## Overview

Simi Central is the central SaaS application for `https://www.simi.com.au/`.

It handles:

- authentication and team access
- subscription and billing management
- tenant / workspace provisioning
- onboarding new businesses and franchises into Simi
- routing Stripe events for central billing and tenant payment flows

Simi itself is focused on AI-powered property marketing workflows, especially:

- virtual staging
- image enhancement
- floor plans
- workspace-based access for real estate teams, agencies, and franchises

## Product Model

This repo is the `central` app in a multi-tenant architecture.

- `Central` manages users, teams, billing, onboarding, and tenant creation
- `Tenant` apps handle the actual business workspace experience
- each customer business or franchise gets its own tenant workspace

Recent product decisions reflected in this codebase:

- the onboarding flow is described as `workspace` setup, not `project` creation
- the `/stores` area is effectively the workspace access and onboarding hub
- new workspace creation can use AU business lookup data and manual business entry
- workspace URL generation is based on workspace name, with availability checks and fallback suffixes

## Key Flows

### Workspace onboarding

Users come to Central for two primary jobs:

- onboard a new business or franchise
- choose an existing workspace and continue

Relevant routes:

- `GET /stores`
- `GET /stores/create`
- `POST /stores`
- `GET /api/business-search`
- `GET /api/business-search/{id}`
- `GET /api/subdomain-suggestion`

Business onboarding currently supports:

- searching Australian business records through the business lookup API
- selecting an official business record
- manual entry when lookup does not find the business
- automatic workspace URL suggestion and availability fallback

Tenant persistence note:

- tenant metadata like business identity should not be nested inside a manual `data` array during tenant creation
- this app relies on Stancl tenancy virtual columns, so fields like `business_name` and `business_number` should be assigned on the tenant model as top-level attributes and then saved
- `CreateTenantAction` creates tenants via mass assignment, so extra metadata passed directly in the creation payload can be ignored unless it is assigned after creation
- keep system-managed tenant fields such as `is_ready` and `tenancy_db_name` untouched and let tenancy/shared-saas manage them alongside the rest of the data payload

### Mail branding

Mail templates have been updated to better match Simi's brand and product language.

- mail copy now refers to workspaces, virtual staging, and property image workflows
- default Laravel mail templates were customized for Simi styling
- the mail theme uses the current gold Simi brand palette

Mail templates live in:

- `resources/views/mail/`
- `resources/views/vendor/mail/`

## Architecture

### Central responsibilities

- user registration and authentication
- team membership and invitations
- billing and subscriptions
- tenant provisioning
- tenant domain routing
- central Stripe webhook handling
- proxying tenant Stripe Connect events

### Tenant responsibilities

- business workspace functionality
- customer-facing tenant experience
- tenant-specific workflows and settings

## Stripe Webhooks

Simi Central uses two webhook endpoints.

### Central billing webhook

- endpoint: `POST /webhook/stripe`
- purpose: subscription and central billing events

### Tenant payment proxy webhook

- endpoint: `POST /webhook/stripe-connect`
- purpose: forwards tenant payment events to the right tenant app

Flow:

```text
Stripe -> Central -> Tenant
```

## Development

### Requirements

- PHP 8.3+
- Composer
- Node.js 18+
- npm
- Docker / Docker Compose

### Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

If you use the local Docker environment:

```bash
docker-compose up -d --build --remove-orphans
```

### Useful commands

```bash
php artisan migrate
composer test
composer test-unit
composer test-feature
composer phpstan
composer cs-check
composer cs-fix
npm run dev
npm run build
```

## AU Business Lookup

The workspace onboarding UI integrates with an Australian business lookup service.

- local source repo: `/Users/slj/work/ms-au-biz-search`
- hosted API: `https://biz-search.slj.me`

This is used to prefill:

- business name
- business number
- state
- postcode
- related business identity data stored with the tenant

## Theming

Current theme values are configured in:

- `config/theme.php`
- `vite.config.js`

Current brand colors:

- primary: `#ab8949`
- lighter: `#caa663`
- darker: `#96763c`

These colors are used across UI and mail presentation.

## Important paths

- `app/Http/Controllers/StoreController.php`
- `routes/central.php`
- `resources/js/Pages/Stores/`
- `resources/views/mail/`
- `resources/views/vendor/mail/`
- `config/theme.php`

## Notes for contributors

- prefer `workspace` terminology over `project` terminology
- think in terms of onboarding a new business or franchise
- preserve dark theme support when changing onboarding pages
- keep Central focused on access, billing, provisioning, and coordination
- tenant-specific business workflows should stay in tenant apps where possible
