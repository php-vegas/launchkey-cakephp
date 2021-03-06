LaunchKey Plugin
================

[LaunchKey](https://launchkey.com/) implementation for CakePHP

Background
==========

A sample CakePHP Plugin for using the LaunchKey API

Requirements

 * PHP 5.3+
 * CakePHP 2.3+

Installation
===========

[Manual]

Download this: [http://github.com/lv-php/launchkey-cakephp/zipball/master](http://github.com/lv-php/launchkey-cakephp/zipball/master)

Unzip that download.

Copy the resulting folder to app/plugins

Rename the folder you just copied to LaunchKey 

[GIT Submodule]

In your app directory type:

```bash
  git submodule add git://github.com/lv-php/launchkey-cakephp.git plugins/LaunchKey
  git submodule init
  git submodule update
```

[GIT Clone]

In your plugin directory type
```bash
  git clone git://github.com/lv-php/launchkey-cakephp.git LaunchKey
```

Usage
=====

app/Config/bootstrap.php
------------------------
```php
  CakePlugin::load("LaunchKey");
```

app/Controllers/AppController
-----------------------------------
```php
  public $components = array(
    'LaunchKey' => array(
      'appid' => '123456789',
      'secretKey' => 'asdfasdfasdf',
      'privateKey' => '/my/private/key.key',
      'polling' => TRUE, // True for private IP address or development
    )
  );
```

ToDo
====

 * Setup TravisCI for Project [Travis CI Stuff](https://github.com/FriendsOfCake/travis)
 * Get LaunchKey Integration Working [LaunchKey Docs](https://launchkey.com/docs/)

License
=======
