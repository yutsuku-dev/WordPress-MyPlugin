# Development
## Sandbox environment

Running WordPress with this plugin locally, all changes to the plugin are instant.
To start up your local environment do the following:

* Copy `.env.dist` to `.env` and change values as necessary 
* execute following command

```
docker-compose up -d
```

By now, WordPress should be running at [wp.test:8011](http://wp.test:8011/).
You *should* edit your `/etc/host` file for this.

To stop the WordPress from running, use

```
docker-compose down
```

## Using wp-cli and composer inside the container.

Following command will drop you into container right inside plugin directory.
Use composer and wp-cli as it was installed globally: `composer require ...`

```
docker-compose run --rm cli sh
```

## Cleaning up

At some point you may want to completly remove current testing environment. To do
so execute following commands to ensure removal of presistent [Docker Volumes](https://docs.docker.com/storage/volumes/).

```
docker volume rm wp-test_db_data
docker volume rm wp-test_wordpress_data
```
