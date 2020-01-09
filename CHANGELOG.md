CHANGELOG
=========

2.0.1 [09 Jan 2020]
-----

* `fix` when a user attempting to register via auth0 already exists, sync the existing account
        instead of creating a new one

2.0.0 [08 Jan 2020]
-----

* `enh` modernize auth0 libraries
* `enh` remove deprecated tenant handling
* `enh` remove auth0-lock javascript widget, use plain php authorization instead

1.11.0 [12 Apr 2015]
-----

* `enh` Added search by email manual feature

1.10.2 [10 Dec 2015]
-----

* `bug` Fixed bug to allow unique validator based on tenant id to work correctly

1.10.1 [9 Dec 2015]
-----

* `bug` Fixed bug to display auth0 lock correctly with latest version of chrome

1.10.0 [11 Nov 2015]
-----

* `bug` Fixed bug to display sidebar correctly.

1.9.0 [18 Sep 2015]
-----

* `chg` Changed rememberLastLogin settings


1.8.0 [17 Sep 2015]
-----

* `enh` Added image url function

1.7.0 [3 Sep 2015]
-----

* `bug` Fixed bug to remove tenant correctly from Auth0 AppMetadata
* `enh` Added UserQuery

1.6.1 [2 Sep 2015]
-----

* `bug` Fixed bug to use the latest stable version of yii2-metronic and yii2-helper

1.6.0 [2 Sep 2015]
-----

* `enh` Added change tenant feature
* `bug` Fixed bug to refresh service admin page correctly after tenant create or update

1.5.0 [1 Sep 2015]
-----

* `chg` Changed sidebar to web service params

1.4.0 [31 Aug 2015]
-----

* `enh` Added validate tenant function to auth0 model

1.3.0 [28 Aug 2015]
-----

* `enh` Added user select2Data function
* `enh` Added tenant function
* `chg` Changed login action with `goBack()` function
* `enh` Added access control to controller
* `enh` Added documentation on admin email configuration
* `enh` Added update user feature to admin

1.2.0 [19 Aug 2015]
-----

* `enh` Added metronic theme
* `enh` Added create action and create form with modal assets
* `enh` Added buttons to tenant
* `enh` Added login layout
* `enh` Added confirmation msg for delete all, added audit log for tenant
* `enh` Added users column to tenant portlet
* `enh` Added user portlet in tenant admin dashboard with tenants column
* `chg` Removed tick and cross in service-admin index
* `chg` Changed portlet layout similar to the old design
* `enh` Added the import all function in tenant service admin
* `enh` Added export function to tenant service admin
* `enh` Added session flash feature
* `enh` Added checkbox function to gridview in service admin

1.1.0 [12 Aug 2015]
-----

* `enh` Added Service Admin index page
* `enh` Added Tenant User model


1.0.0 [5 Aug 2015]
-----

* Initial release
