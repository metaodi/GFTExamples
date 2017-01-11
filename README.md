GFTExamples
===========

Examples for Google Fusion Tables. This repository is based on the ["Getting Started" guide of Google's PHP API Client Library](https://developers.google.com/api-client-library/php/start/get_started).

This repository is an updated, slimmer version of the code that was previously [part of GFTPrototype repository](https://github.com/metaodi/GFTPrototype/tree/master/examples/php).

Please contact me, if you run into any problems with these examples.

## Installation

0. Clone this repository
0. Install [Composer](https://getcomposer.org/) - the dependency manager for PHP
0. Run `php composer.phar install` in the repository directory
0. Provide the credentials of a service account or create a new one (see the [service account page](https://developers.google.com/identity/protocols/OAuth2ServiceAccount) for details, use the JSON format for the private key), create a symlink or rename your key to `credentials.json`

## Run the examples

To run the examples, you need an installation of PHP, then you can simply run the PHP scripts on the command line.

E.g.:

```bash
$ php list_tables.php
Table ID: 1KY8mVr3zSNvj8-MnLN9o34aORtuKYRv_dn_o0HR9, Name: myTableName
Table ID: 1eyzqmp_12tdaLDdJAo4_M346vqpyRGNip90vaRUN, Name: Testtable
Table ID: 1ccBFqrId647BHsA1D2hNSnTZDoaZHS9KsOS_9EoQ, Name: waste_collection_zurich_2017
```

Of course you can run the scripts on a PHP-enabled webserver and call them from a browser, the output however is not optimized for this usage.
