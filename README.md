# Autodesk Forge PHP Basic Sample
Autodesk Forge PHP Basic Sample is a simple php application to connect to Autodesk Forge APIs. 

Demo: http://forge-php-basic-sample.herokuapp.com/

Autodesk Forge Platform: https://developer.autodesk.com/

## Create an Autodesk Forge Application
Before getting started with the Forge Platform, you need to set up an app and get your client ID and secret.
https://developer.autodesk.com/en/docs/oauth/v2/tutorials/create-app/

## Application setting
Once you set up an application, you will see a Client ID and Client Secret in your newly created app page.
You will need to set these to the project configuration file.
The configuration file is in the application/config/config.php.

## Install library through Composer
The application uses OAuth 2.0 Client and Guzzle.
If you install OAuth 2.0 Client through Composer, dependent package will be installed including Guzzle.

OAuth 2.0 Client: https://github.com/thephpleague/oauth2-client

Guzzle: https://github.com/guzzle/guzzle

## Deploy the app to a server
To use the app, you need to deploy it to a web server, because Forge OAuth 3-Legged Authentication will redirect to callback URL which is defined on creating app.

(Heroku) To deploy the app on Heroku, the article is be a step by step guide. 
https://devcenter.heroku.com/articles/deploying-php

## Description
Client-side calls serveral ajax requests to its server with RESTful API.

HTML view is composed with layout.php and views/main.php.
Ajax requests are defined in controller.js to catch submit event of each forms.

Server-side calls Autodesk Forge APIs and manage token on a session.

index.php calls bootstrap.php and BasicApplication.php, and instanciate app.
bootstrap.php instantiate core classes through ClassManager.
BasicApplication.php defines routes to call controller and its method.
MainController.php is a mainly call Autodesk Forge APIs and return its response to client.

## License
This software is released under the MIT License, see LICENSE.txt.
