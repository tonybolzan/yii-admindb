# AdminDbModule for Yii

This is a module to backup and restore the current database application.

## Currently supports databases:
- MySQL

## Example:
```php
    ...
    array(
        'modules' => array(
            ...
            'admindb' => array(
                'password' => '123456',
                'passwordHashAlgo' => false, // sha1, md5... @see hash_algos()
                'ipFilters' => array('127.0.0.1', '::1'),
                'path' => null, // null = Yii Runtime Path = protected/runtime/admindb/
            ),
            ...
        ),
    ),
    ...
```
Or this configuration

```php
    ...
    array(
        'modules' => array(
            ...
            'admindb' => array(
                'password' => '7c4a8d09ca3762af61e59520943dc26494f8941b', // 123456
                'passwordHashAlgo' => 'sha1',
                'ipFilters' => array('127.0.0.1', '::1'),
                'path' => dirname(__FILE__) . '/../data/admindb/',
            ),
            ...
        ),
    ),
    ...
```