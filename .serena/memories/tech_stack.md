# Stack
- PHP ^8.2, CodeIgniter 4 ^4.7, Composer.
- MySQL/MariaDB; CI models use native query builder plus direct SQL where needed.
- Frontend is server-rendered PHP views with substantial JavaScript; README describes TailwindCSS, HTML5 Canvas and Google Gemini 2.0 Flash.
- Docker service: container exam_app, HTTPS host port 8200 -> container 443; compose joins external skj2025_default network.
- PHPUnit ^10.5.16 is configured via composer script test.
