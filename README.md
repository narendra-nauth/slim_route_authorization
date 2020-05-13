# Slim Route Authorization

This is an example for authorization and authentication via Middleware in Slim 3 PHP. 
In this example, there are two users with a concept of an integer access level as follows:
- Administrator : **Access Level 1**
- Member : **Access Level 1**

There is also a sample database export in *db_export/api_server.sql* containing a user table for authentication & authorization with details below:
- Administrator:
    - Username: admin
    - Password: password

- Member:
    - Username: member
    - Password: password
    
##Installation Instructions
1. Copy files to web host
2. Import database and modify *models/database.php*
3. Run *composer install* in cmd

This can be done more elegantly and I intend to have this done at a later time. If a user would like to have this done and submit a pull request, that would be appreciated.
