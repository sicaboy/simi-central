# CLAUDE.md - Simi Central

This file provides guidance to Claude Code when working with the **simi-central** application.

## Application Role
**simi-central** is the central management system for the Simi multi-tenant SaaS platform, handling:
- User authentication and team management
- Subscription billing via Stripe
- Tenant coordination and management
- Webhook routing for tenant payment events

## Key Architecture Points

### Multi-Tenant SaaS Structure
- **Central + Tenant Model**: This app handles user management/billing, tenants handle store operations
- **Database**: Single database for users, subscriptions, tenants
- **Shared Components**: Uses `sicaboy/shared-saas` package for subscription logic
- **Stripe Integration**: Handles subscription webhooks and routes tenant payment events

### Technology Stack
- **Backend**: Laravel 10, PHP 8.3, MySQL, Redis
- **Frontend**: Vue.js 3 + Inertia.js (admin interface)
- **Payment**: Stripe with subscription management
- **Styling**: Tailwind CSS

## Development Commands

### Testing
```bash
composer test              # Full test suite with Docker MariaDB
composer test-unit         # Unit tests only  
composer test-feature      # Feature tests only
composer test-coverage     # Tests with coverage report
```

### Migration Commands
**IMPORTANT**: Use `php artisan migrate` for central migrations in `database/migrations/`
(NOT `tenants:migrate` - that's for the tenant app)

### Code Quality (via shared-saas)
```bash
composer phpstan    # Static analysis
composer cs-fix     # Fix code style
composer cs-check   # Check code style
```

### Frontend Development
```bash
npm run dev         # Development build
npm run build       # Production build
```

## Key Directories
- `app/Models/Central/` - User, Team, Tenant, Membership models
- `app/Http/Controllers/WebhookController.php` - Stripe webhook routing
- `shared-saas/` - Shared SaaS package with subscription logic
- `database/migrations/` - Central system migrations

## Stripe Webhook Architecture
- **Central**: `/webhook/stripe` handles subscription events (billing)
- **Central Proxy**: `/webhook/stripe-connect` routes tenant payment events
- **Flow**: Stripe → Central → Tenant for payment events

### Webhook Testing
```bash
# Install Stripe CLI
stripe listen --forward-to http://localhost:8053/webhook/stripe           # Central subscriptions
stripe listen --forward-to http://localhost:8053/webhook/stripe-connect   # Tenant payments

# Trigger test events
stripe trigger customer.subscription.created
```

## Configuration
- Environment: `.env` configuration with Stripe keys
- Subscription plans: `shared-saas/config/subscription.php`

## Security Considerations
- **Webhook Security**: Stripe signature verification on all webhook endpoints
- **Multi-Tenant Security**: Proper tenant switching and domain verification

## Key Integration Points
- **Central ↔ Tenant**: User authentication, subscription status, tenant configuration
- **Stripe Integration**: Payment processing, subscription management, webhook routing

## Deployment
- Docker support with Nginx Unit
- Development: `docker-compose up -d --build`
- Production: Custom deployment scripts (`deploy.sh`, `deploy-simple.sh`)
