# restapi
author: Anders RM
email: anders.rojas@pucp.pe

RESTAPI ARTICLES

GENERAL CONFIGURATION
1. DB configuration
- Make sure you have installed SQL
- There is no migration implemented so, if you would like to build the sample DB to test:
    sql  'CREATE DATABASE `restapi`

    sql  'CREATE TABLE `users` (\n  `id` int NOT NULL AUTO_INCREMENT,\n  `name` varchar(200) DEFAULT NULL,\n  `email` varchar(200) DEFAULT NULL,\n  `password` varchar(1024) DEFAULT NULL,\n  `token` varchar(255) DEFAULT NULL,\n  `token_expiration` varchar(45) DEFAULT NULL,\n  `created_at` int DEFAULT NULL,\n  `updated_at` int DEFAULT NULL,\n  `status` tinyint(1) DEFAULT NULL,\n  PRIMARY KEY (`id`)\n) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci'

2. PHP configuration 
    Tested with PHP>7.0
    Apache >2.2
3. Composer configuration
    No gitignore, but in any case run `composer install`


LINUX CONFIGURATION
This configuration can be applied also to other distributions. The difference with windows are only the folder paths.
1. cd /etc/apache2/sites-available
2. sudo nano Rest-Api.conf
    VirtualHost *:80>
        ServerName apirest.com
        DocumentRoot /var/www/Rest-API
    </VirtualHost>
2. sudo a2ensite Rest-Api.conf
3. Check sites-enabled
4. sudo systemctl reload apache2
5. sudo apachectl configtest
6. sudo systemctl restart apache2
7. sudo nano /etc/hosts and add under localhost: 127.0.0.1 apirest.com

UNDERSTANDING THE PROJECT
1. Project has no frameworks.
2. The global variables are written in config.php.
3. You will find in config.php the DB values. If your DB has extra configuration, please add extra parameters to models/connection.
4. Token variables available at config.php.
5. Cache Redis vairables available at config.php.
6. GnewAPI variables available at config.php
7. Language spelled variables available at libraries/language.php
8. Helpers functions available at libraries/hepers.php

CRUD FUNCTIONS
1. POST apirest.com/create=true:
    Body:
    * @param email
    * @param password
    * @param name
    Validations implemented:
    * Email must be not registered.
    * Email must be valid according REGEX.
    * Password validation in char long, lowercase, uppercase, number, special chars.
    * Name length validation < 255.

2. POST apirest.com?readAll=true
    Body:
    * No params

3. POST apirest.com?updateByEmail=true
    Body:
    * @param email
    Validations implemented:
    * Email validations.
    * Password validations.
    * Name validations.

4. POST apirest.com?deleteByEmail=true
    Body:
    * @param email
    Validations implemented:
    * Email validations.

LOGIN FUNCTIONS
1. POST apirest.com?login=true:
    Body:
    * @param email
    * @param password
    Validations implemented:
    * Email validation.
    * Password validation.

GET ARTICLES
GENERAL -> cache after second same search 
1. POST apirest.com?getArticles=true:
    Body:
    * @param query
    * @param limit
    * @param searchby
    * @param token
    Validations implemented:
    * Token validation.

FETCHING N LATEST ARTICLES WITH KEYBOARD Q
POST apirest.com?getArticles=true:
    Body:
    * @param query  Q
    * @param limit  N
    * @param token
    Validations implemented:
    * Token validation.

FETCHING LATEST ARTICLES WITH KEYBOARDS Q AND Q2 ...
POST apirest.com?getArticles=true:
    Body:
    * @param query  Q,Q2,Q3 SEPARATED BY COMMAS
    * @param token
    Validations implemented:
    * Token validation.

FETCHING N LATEST ARTICLES WITH KEYBOARD Q IN TITLE OR CONTENT OR DESCRIPTION
POST apirest.com?getArticles=true: 
    Body:
    * @param query  Q
    * @param limit  N
    * @param searchby   "title" || "title,description" || "title,description,content" || in other order || "xxtitlexx"
    * @param token
    Validations implemented:
    * Token validation.

Example OUTPUT CRUD:
{
    "status": 200,
    "message": "Succesfully",
    "result": [
        {
            "id": "3",
            "name": "ande2d23d21xw",
            "email": "ande22rs.rojas@pucp.pe",
            "created_at": "1662536113",
            "updated_at": "1662536154"
        }
    ],
    "errors": []
}
Example OUTPUT API:
{
    "status": 200,
    "message": "cache",
    "result": [
        {
            "title": "The 2022 Hugo Award Winners Are Here",
            "description": "As Gizmodo co-founders Annalee Newitz and Charlie Jane Anders acted as Toastmasters during the Hugo
                awards ceremony at Chicon, the 80th World Science",
            "content": "The 2022 Hugo Award Winners Are Here\nAs Gizmodo co-founders Annalee Newitz and Charlie Jane Anders acted as
                Toastmasters during the Hugo awards ceremony at Chicon, the 80th World Science Fiction and Fantasy convention, they were
                also awarded with som... [10518 chars]",
            "url": "https://www.gizmodo.com.au/2022/09/the-2022-hugo-award-winners-are-here/",
            "image":
                "https://www.gizmodo.com.au/wp-content/uploads/sites/2/2022/09/07/0ca6294590fe676ab68ec10dae58588e.png?quality=80&resize=1280,720",
            "publishedAt": "2022-09-06T16:40:00Z",
            "source": {
                "name": "Gizmodo Australia",
                "url": "https://www.gizmodo.com.au"
            }
        }
    ],
    "errors": []
}