PHP 8.5
MySQL 8.4

Steps to start the project:

1. Clone the project to yourself
2. Copy .env.example to .env and rename variables on yours except DB_SERVERNAME
3. Open a terminal in the project's root directory
4. Start docker if you didn't it before
5. Enter command docker compose up --build
6. The app will be available at `http://localhost:8000` or with your port if you changed SERVER_PORT in .env

Database migrations will execute automatically
