KunstmaanAdminBundle by Kunstmaan
=================================

About
-----
The KunstmaanAdminBundle for Symfony 2 is part of the bundles we use to build custom and flexible applications at Kunstmaan.
The KunstmaanAdminBundle is a base bundle and is required by other bundles like KunstmaanAdminNodeBundle, KunstmaanAdminListBundle, etc.

View screenshots and more on our [github page](http://kunstmaan.github.com/KunstmaanAdminBundle).

[![Build Status](https://secure.travis-ci.org/Kunstmaan/KunstmaanAdminBundle.png?branch=master)](http://travis-ci.org/Kunstmaan/KunstmaanAdminBundle)

Installation requirements
-------------------------
You should be able to get Symfony 2 up and running before you can install the KunstmaanAdminBundle.

Installation instructions
-------------------------
Installation is straightforward, add the following lines to your deps file:

```
[KunstmaanAdminBundle]
    git=https://github.com/Kunstmaan/KunstmaanAdminBundle.git
    target=/bundles/Kunstmaan/AdminBundle
```

Register the Kunstmaan namespace in your autoload.php file:

```
'Kunstmaan'        => __DIR__.'/../vendor/bundles'
```

Add the KunstmaanAdminBundle to your AppKernel.php file:

```
new Kunstmaan\AdminBundle\KunstmaanAdminBundle(),
```

Contact
-------
Kunstmaan (support@kunstmaan.be)

Download
--------
You can also clone the project with Git by running:

```
$ git clone git://github.com/Kunstmaan/KunstmaanAdminBundle
```

