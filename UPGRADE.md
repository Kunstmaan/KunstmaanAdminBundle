Upgrade Instructions
====================

## To v2.3 without ROLE_GUEST dependency

Since ROLE_GUEST and the guest user were superfluous - the default Symfony IS_AUTHENTICATED_ANONYMOUSLY & anon. user
cover the same use case - we decided to drop them in favor of the Symfony defaults. This is a BC breaking change, so
a migration path is provided.

To upgrade, first pull in the new version of all bundles.

Remove guest_user: true from app/config/security.yml.

And finally execute the following command :
```app/console kuma:fix:guest```

This should execute the necessary changes (you could delete/rename the guest user afterwards as well - we just leave
it in case there are items linked to it).

Note: it will no longer be possible to add extra roles to the guest/anonymous user in the back-end. You will
also have to adapt your code (in most cases replacing ROLE_GUEST with IS_AUTHENTICATED_ANONYMOUSLY should suffice
though).

## Using the Google Analytics dashboard

The Google Analytics dashboard depends on three parameters added in app/config/parameters.yml. Because these parameters are injected, there is no way to catch an exception if they are not present. This is a BC breaking change, and can be fixed be just adding the three parameters and default values '' to parameters.yml.

To upgrade, first pull in the new version of all bundles, then add these parameters:

    google.api.client_id: ''
    google.api.client_secret: ''
    google.api.client_secret: ''

Now you can run the app without any errors, follow the documentation (AdminBundle/resources/doc/SetupAnalyticsDashboard.md) to configure the dashboard.
