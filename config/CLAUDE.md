# Configuration Files

## Overview
Laravel configuration files defining application behavior, service connections, and environment settings.

## Key Configuration Files

### app.php
- Application name, environment, debug mode
- Timezone and locale settings
- Service provider registration
- Class aliases

### auth.php
- Authentication guards configuration
- JWT authentication for API
- User provider settings
- Password reset configuration

### database.php
- MySQL connection settings
- SQLite configuration for testing
- Redis connection settings
- Migration table configuration

### cors.php
- CORS headers configuration
- Allowed origins, methods, headers
- API access control

### filesystems.php
- Disk configurations (local, public)
- File storage paths
- Cloud storage settings

### jwt.php
- JWT token configuration
- Token TTL and refresh settings
- Signing algorithm
- Blacklist configuration

### logging.php
- Log channels configuration
- Log levels and handlers
- Stack logging setup
- Debug and error logging

### mail.php
- SMTP configuration
- Mail driver settings
- From address and name
- Markdown mail settings

### queue.php
- Queue connection settings
- Job processing configuration
- Failed job handling
- Queue workers setup

### scout.php
- Algolia search configuration
- Model searchable settings
- Search index configuration

### services.php
- Third-party service credentials
- Xero API configuration
- External API settings

### session.php
- Session driver configuration
- Session lifetime
- Cookie settings
- Session security

### ide-helper.php
- IDE helper generator settings
- Model documentation
- PHPDoc generation

### debugbar.php
- Laravel Debugbar settings
- Enabled collectors
- Storage settings

## Environment Variables
Configuration files reference `.env` for:
- Database credentials
- API keys and secrets
- Mail server settings
- Application URLs
- Debug and environment modes
- Cache and session drivers
- Queue connections
- Third-party service credentials