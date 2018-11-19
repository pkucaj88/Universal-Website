Lightweight and responsive website template.
Website has multiple functions implemented in PHP with the use of MySQL.


Demo: 
http://vps604032.ovh.net/site/

(when registering on the demo site, please do not use the gmail email address, since google is rejecting emails from this domain)



Available functions: 
- news system
- gallery
- account registration with email validation
- email validation re-send function
- account recovery via password reset
- account log in and log off
- password change
- account details update (name, surname, city, etc.)
- product purchase with PayPal integration
- product purchase history
- ban system


TODO:
- replace the mysqli database functions with the PDO
- user/admin login permission system
- interface for adding and editing the entries in the news system, gallery and product list



How to install: 
1. Upload all the files on your webserver directory
2. Use the database.sql file to create needed database tables.
3. Edit the config.php file (in includes directory) to change the site name, description, email and database connection settings.
