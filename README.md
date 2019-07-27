# dynali-php
DynAli PHP Library: allows interaction with DynAli's PHP NICE JSON Api.

Includes library which can be used in any PHP code (in the `lib/` folder) and a
simple executable which can be used to check hostname's status, update the ip,
change password or manipulate local storage of saved hostnames.

## installation (library)

To install using composer please just type:
```php
composer require dynali/dynali-php
```
in your project's folder

I suggest to use tagged releases and semantic versioning.

Later just create an instance of the main class in your project:
```php
$client = new Dynali\DynaliClient();
```

## installation (standalone)

If you want to use the repository as a standalone application just clone or
download the repository:

```bash
git clone https://github.com/dynali/dynali-php.git
```

and execute:
```bash
composer install
```

to load all vendors.

## usage (library)

TODO

## usage (standalone)

You can find the main executable in the `bin/` folder. It currently supports commands:

* `ip` which returns your external IP as detected by Dynali
* `install` which returns a command which you can add to crontab in order to make
updates automatically every minute
* `add`, inserts hostname's details into local storage (basic `dynali.csv` file
created locally in the same folder)
* `remove`, removes hostname's details from local storage
* `status`, provides details about domain's status as an instance of the
`DynaliStatus` entity
* `update`, updates the IP for the given hostname
* `update-all`, updates IPs for all of the hostnames in the local storage
* `list`, lists domains in the local storage
* `changepassword`, allows to change password for a particular hostname

Sample usage:
```bash
./dynali add myname.dynali.net bartoszp SuperSecretPasSwOrD123
```

## TODO

* improve README.md
* provide unit tests
* allow passing of manually entered IP using the cli tool

## Contribution

Please use the issues or pull requests functionalites of Github.
