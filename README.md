launchkey-cakephp
=================
LaunchKey implementation for CakePHP

Installation
============
Use git to get code and install

LaunchKey Configuration
=======================
Set up for API and configure callback for {base URL}/LaunchKey/controller/callback

Configuration
=============
app/Config/bootstrap.php
------------------------
```php
  CakePlugin::load("LaunchKey");
```

plugins/LaunchKey/Config/config.php
-----------------------------------
```php
  Configure::write("LaunchKey.AppId", 1234567890);
  Configure::write("LaunchKey.SecretKey", "MySecretKey");
  Configure::write("LaunchKey.PrivateKey", "/my/private/keyfile.key");
  Configure::write("LaunchKey.polling", true); // True for private IP address or development
```

Example
=======
