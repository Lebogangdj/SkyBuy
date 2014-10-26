# Dynamic Tables

Dynamic tables is a project started to provide cutting edge HTML tables that support a plethora of features:

## Authors

* Dylan Vorster

## Features

* Pagination
* Multiple Column ordering
* Page Row limits
* Selective column searching
* Export to CSV
* Print view
* Plugin based extensions

## Installation

To install the table system, put this in your global autoloader:

```php
require_once(__DIR__ . '/module-access/UI-engines/table-system/autoloader.php');


namespace tm {

//!----------- OPTIONS -----------
define('TM_PSQL_NAMESPACE', '\eezipay\launchpad\PSQL');

$TABLE_SYSTEM_OPTIONS['PATH_TO_PSQL'] = __DIR__.'/../../../launchpad-library/classes-toolbox/PSQL.class.php';

//styles
define('TM_BUTTON_CLASS','ESmallButton');
define('TM_CONTAINER_CLASS','ETablePanel');
define('TM_TABLE_CLASS','ETable');
define('TM_FIELD_CLASS', 'MField');

//JS
define('TM_JS_NAMESPACE','TM.');

//other
define('TM_SESSION_NAME','STORED_TABLE_MODELS');
}
```