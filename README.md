[![Build Status](https://api.travis-ci.org/Wisembly/intercom-php.svg)](http://travis-ci.org/Wisembly/intercom-php)

# Intercom-php

This little library allows you to use Intercom API more easily. It provides clients to manage your users or your events fluently.

The curl client used is [Guzzle](https://github.com/guzzle/guzzle). Instanciate Guzzle with your configuration and give it to your Intercom client with you credentials and it's ready go !

[Intercom API documentation](http://doc.intercom.io/api/)

# Versioning

For the moment the library is in "Work In Progress". Master move fast and I don't guarantee BC before announcing a stable version in 1.1.0.

# Installation

1. Install composer : `curl -s http://getcomposer.org/installer | php`
(more info at getcomposer.org)

2. Create a `composer.json` file in your project root :
(or add only the excelant line in your existing composer file)

```yml
  {
    "require": {
      "wisembly/intercom-php": "*",
    }
  }
```

3. Install via composer : `php composer.phar install`

# Use Intercom-php

## Create the client for manage Users

```php
use GuzzleHttp\Client as Guzzle;
use Intercom\Client\User as Intercom;

$guzzleHttp = new Guzzle;
$intercom = new Intercom('APP_ID', 'API_KEY', $guzzle);
```

## Actions

Now you can do all the requests that the Intercom User API allows. For each actions, you need to create a User object that represents your Intercom User.

### Create

```php
use Intercom\Object\User;

$user = new User(1, 'foo@company.com'); // user_id or email
$intercom->create($user);
```

### Update

```php
use Intercom\Object\User;

$user = new User(1, 'foo@company.com'); // user_id or email
$intercom->update($user);
```

### Delete

```php
use Intercom\Object\User;

$user = new User(1, 'foo@company.com'); // user_id or email
$intercom->delete($user);
```

### Get

```php
$user = $intercom->get(1, 'foo@company.com'); // user_id or email
```

### Search

To search users through all your Intercom database, use an instance of ``UserSearch`` that allows you to find users with specified attributes.

```php
use Intercom\Request\Search\UserSearch;

// Retrieve the first ten users with tag name "premium"
$search = new UserSearch(1, 10, null, 'premium');

$users = $intercom->search($search);
```

See the complete documentation of this [search API](https://github.com/Wisembly/intercom-php/blob/master/lib/Intercom/Request/Search/UserSearch.php#L31).

### Use case : How to retrieve all your Intercom users ?

By default the Intercom API allows you to retrieve 500 entities per request.

```php
use GuzzleHttp\Client as Guzzle;
use Intercom\Request\UserSearch;
use Intercom\Client\User as Intercom;

// Create the client
$guzzle = new Guzzle;
$intercom = new Intercom('APP_ID', 'API_KEY', $guzzle);

// Create a search with defaut parameters
$search = new UserSearch;

$users = [];

// Fetch all users
do {
    $response = $intercom->search($search);
    $users = array_merge($users, $response->getContent());
    $search->setPage($response->getNextPage());
} while ($response->hasPageToBeFetch());
```

# TODO

- Tagging
- Notes
- Impressions
- Messages threads
