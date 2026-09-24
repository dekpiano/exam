# Windows/Docker commands
- Project uses Docker container exam_app.
- Check container: docker ps | findstr exam_app.
- DB smoke check: docker exec exam_app php spark db:table settings.
- Logs: docker logs -f exam_app (long-running; use only when needed).
- Tests: composer test or php vendor/bin/phpunit.
- App URLs documented: student http://localhost:8200/ and teacher http://localhost:8200/teacher.
