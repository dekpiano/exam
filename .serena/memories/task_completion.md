# Completion checks
- For PHP changes, run targeted diagnostics where available and composer test when tests are relevant.
- For DB/schema-sensitive changes, inspect/verify the affected model fields and database migration/schema before declaring completion.
- Because AdminController::checkDatabaseSchema() performs runtime compatibility ALTERs, verify new columns/field types against both models and this compatibility routine.
