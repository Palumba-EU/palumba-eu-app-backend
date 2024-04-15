# Palumba Backend

[GitHub Repository](https://github.com/Palumba-EU/palumba-eu-app-backend)

Using the following packages:

- Laravel 10 with
    - Laravel Sail
    - Laravel Pint as code style fixer
    - [Laravel Filament](https://filamentphp.com/) for the Admin panel

## Roles and Permissions

Run the roles and permissions seeder to setup roles and permissions.  
This will give all existing users Admin permissions on the first run.  
Subsequent runs will not change existing users roles!

```bash
artisan db:seed --class=RolesAndPermissionsSeeder
```

## Formatting

Run `make format` or `make check-format` to run the code style fixer or 
check for code style problems respectively.


## Registration by Invite

Users can only register for the admin panel after an invitation.  
This is based on [Filament: Invite Only Registration via Email Invitations](https://filamentapps.dev/blog/filament-invite-only-registration-via-email-invitations)


## Seeding

To add the 27 EU member countries, run the CountrySeeder

```bash
artisan db:seed --class=CountrySeeder
```

or to run all seeders

```bash
artisan db:seed
```
