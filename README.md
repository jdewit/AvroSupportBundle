AvroSupportBundle
-----------------
A simple Symfony2 support bundle. 
Users can post & answer questions and mark questions as solved. 

Dependencies
------------
FOSAnswerBundle to allow for threaded answers.
TwitterBootstrap for easy styling

Status
------
This bundle is currently under development and is not even close to being usable. 
I welcome any help.


Installation
------------
This bundle is listed on packagist.

Simply add it to your apps composer.json file

``` js
    "avro/support-bundle": "dev-master"
```

Enable the bundle in the kernel:

``` php
// app/AppKernel.php

    new Avro\SupportBundle\AvroSupportBundle
```



