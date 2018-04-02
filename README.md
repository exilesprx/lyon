# Lyon

A small library that simply produces JWT token using artisan commands for Laravel.

## Dependencies

- Jwt-Auth: <code>tymon/jwt-auth</code> (https://github.com/tymondesigns/jwt-auth)

## Usage

### Command

Creates a new token for a user.
```
lyon:token
```

### Options

The username of the user to create the token for.
```
--u|username={VALUE}
```

The password of the user to create the token for.
```
--p|password={VALUE}
```

Lets the command know that the username is an email when attempting to authenticate.
```
--e|email
```

Set a custom TTL for the token (in minutes). Defaults to the value in the JWT configuration is not specified. Useful when testing or creating long-lived tokens.
```
--t|ttl={VALUE}
```

## Examples

Basic username and password
```
php artisan lyon:token --username=test --password=tester
```

Basic username and password with custom TTL
```
php artisan lyon:token --username=test --password=tester --ttl=24
```

Using email as username
```
php artisan lyon:token -e --username=test@test.com --password=tester
```