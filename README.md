# phpdesktop-wordpress-sqlite
A portable version of WordPress using PHPDesktop and SQLite

Using PHP Desktop desktop GUI framework for IE, a complete working version of WordPress using the SQLite database

# Usage

1. Download PHPDesktop from https://github.com/cztomczak/phpdesktop/wiki/Download-MSIE
2. Download these files or clone in Git.
3. Extract the files and copy phpdesktop-msie.exe to this folder
4. Double click phpdesktop-msie.exe to launch
3. Scroll down to Log In link and click link.
4. Use the userid: admin and password: password

# References

PHP Desktop -> https://github.com/cztomczak/phpdesktop

SQLite -> https://wordpress.org/plugins/sqlite-integration/

# Additional Tips

php.ini: max_execution_time must be increased to 90 (default was 30)

settings.json: port number must be defined to 8080 (default was 0), otherwise WordPress will not run on subsequent launches

wordpress.db: place outside the root path in ../data and specify in the wp-config.php

# Deploy on LAMP

1. Copy the entire www folder to your Linux server (www -> public_html or htdocs, NOT public_html/www)

2. Copy the entire data folder to your Linux server, outside the root

3. Ensure Linux file permissions are set properly

# Example of Live WordPress-SQLite site

http://wordpress-sqlite.netne.net/

# Donations Appreciated & Accepted

Click (or copy & paste) here: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=AY6K2EV5VH9ZN