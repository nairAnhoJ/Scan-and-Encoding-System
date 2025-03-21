<div align="center">
<h1>Scan and Encoding System</h1>
</div>

The Scan and Encoding System is a tool used to store, organize, and manage company documents. Users can upload files and encode important details for easy tracking and retrieval. It ensures secure storage and quick access to essential documents.
<br/>

## Installation

#### Prerequisites
Before installing, ensure you have the following installed on your system:
- [Node.js](https://nodejs.org/en)
- [Git](https://git-scm.com/downloads)
- [PHP >= 8.1](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- Web Server (e.g., Apache or Nginx)
- Database (e.g., MySQL or MariaDB)

#### Steps to Install
1. Clone the repository
~~~
git clone https://github.com/nairAnhoJ/Scan-and-Encoding-System.git
cd Scan-and-Encoding-System
~~~

2. Install PHP Dependencies
~~~
composer install
~~~

3. Set up environment variables
~~~
cp .env.example .env
# Update the environment variables as needed.
~~~

4. Generate Application Key
~~~
php artisan key:generate
~~~

5. Run Migrations and Seeders
~~~
php artisan migrate --seed
~~~

6. Install Node Dependencies
~~~
npm install
~~~

7. Start the frontend dev server
~~~
npm run dev
~~~

8. Set File Permissions (Linux)
~~~
chmod -R 775 storage bootstrap/cache
~~~

9. Start the Development Server
~~~
php artisan serve
~~~

<br/>