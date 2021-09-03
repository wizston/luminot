# Luminot (A simple HTTP notification system)

A server (or set of servers) will keep track of topics -> subscribers where a topic is a string and a subscriber is an HTTP endpoint. When a message is published on a topic, it should be forwarded to all subscriber endpoints.

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.4/installation#installation)

Alternative installation is possible without local dependencies relying on [Docker](#docker).

Fork this repository, then clone your fork, and run this in your newly created directory:

``` bash
composer install
```

Next you need to make a copy of the `.env.example` file and rename it to `.env` inside your project root.

Generate a new application key

    php artisan key:generate
Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Start the local development server

    php artisan serve

You can now access the server at http://localhost:8000

## Docker

To install with [Docker](https://www.docker.com), run following commands:

```
git clone git@github.com:gothinkster/laravel-realworld-example-app.git
cd laravel-realworld-example-app
cp .env.example.docker .env
docker run -v $(pwd):/app composer install
cd ./docker
docker-compose up -d
docker-compose exec php php artisan key:generate
docker-compose exec php php artisan migrate
docker-compose exec php php artisan serve --host=0.0.0.0
```

The api can be accessed at [http://localhost:8000/api](http://localhost:8000/api).

## Api Documentation

###Create a subscription
`POST: /subscribe/{topic}`

**Body**
````
{
    url: string
}
````
**Success Response**

````
{
    url: string, 
    topic: string
}
````
**Example Request / Response**

````
POST /subscribe/topic1 
// body
{
    url: "http://localhost:8000.test" 
}
````
**Response:**

````
{
    url: "http://localhost:8000", 
    topic: "topic1"
}
````

###Publish message to topic
`POST /publish/{topic}`

POSTing to this endpoint sends HTTP requests to any current subscribers for the specified {topic} . If there are multiple subscribers they all get notified.

**Expected Body**
````
{
    [key: string]: any
}
````

## License

This is a Take-home assignment by Pangaea. You should not use this project if you have similar task from the same company.

If the above is not the case with you then, this is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
