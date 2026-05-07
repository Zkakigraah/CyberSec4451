# CyberSec4451
Cyber Security class
# Social Network Web Application

## Tech Stack
* Linux (Ubuntu)
* Nginx
* MySQL
* PHP (PHP-FPM)

## Installation Instructions
1. Clone this repository into your Nginx web root directory (e.g., `/var/www/html/`).
2. Import the database structure using the provided `db.sql` file:
   `mysql -u root -p < db.sql`
3. Update the database connection credentials (`$host`, `$user`, `$pass`, `$db`) located at the top of the PHP files in both the `/admin/` and `/socialnet/` directories to match your local MySQL setup.
4. Ensure Nginx is configured to serve PHP files via PHP-FPM.
5. Set appropriate permissions for the web server to read the files:
   `sudo chown -R www-data:www-data /var/www/html/your-repo-folder`

## Usage
1. First, navigate to `/admin/newuser.php` to create user accounts.
2. Next, navigate to `/socialnet/signin.php` to log in with the created accounts.
