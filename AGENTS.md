# AGENTS

## Product context

This repository is the central application for Simi.

Simi is a multi-tenant SaaS product for property marketing workflows, especially:

- AI virtual staging
- image enhancement
- floor plans

The central app exists mainly to:

- onboard new businesses and franchises
- let existing users choose their workspace
- manage auth, billing, and tenant provisioning

## Terminology

Use these terms consistently:

- say `workspace`, not `project`
- think of each tenant as a business or franchise workspace
- Central is the onboarding and management layer
- tenant apps are where operational business workflows live

## Current UX expectations

- `/stores` is the workspace selection and onboarding entry point
- `Create Workspace` is for onboarding a new business or franchise
- workspace URL is generated from workspace name and validated for availability
- AU business lookup is part of onboarding
- manual business entry remains available when lookup fails

## Brand expectations

- use Simi language, not generic shared-saas language
- avoid references to gift cards, bookings, online stores, local business toolkits, or similar legacy copy
- keep copy aligned with real estate marketing, virtual staging, image workflows, and workspace access
- preserve the current gold theme palette unless explicitly changed

## Mail expectations

- email templates live in `resources/views/mail/` and `resources/views/vendor/mail/`
- mail copy should sound like Simi
- avoid heavy divider lines in the email layout
- keep email structure clean, premium, and simple

## Frontend expectations

- dark theme must be considered for onboarding pages
- desktop onboarding pages should not feel like narrow auth forms
- when a list has a single workspace card, avoid awkward empty multi-column layouts

## Implementation notes

- store onboarding logic currently lives in `app/Http/Controllers/StoreController.php`
- business lookup uses `https://biz-search.slj.me`
- theme values live in `config/theme.php`

## Documentation rule

When product direction changes, update both:

- `README.md`
- `AGENTS.md`
