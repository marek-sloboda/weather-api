### Summary
App takes temperature from two separate WeatherAPI's. 

Then counts average temperature for provided location.

TODO: If provided location is in cache, then api request in unecessary, same when asking for /api/location{id}/weather

### Setup
1) App based on docker:

`docker compose build`

`docker compose up`

2) App is build on API Platform, Postgresql, Redis as cache, so next step is:

`composer install` - for apply vendors

3) Then in .env provide Your ApiKeys for 

https://openweathermap.org = OWMAPIKEY

https://rapidapi.com/category/Weather = RAPIDAPIKEY

### Usage
Swagger documentation is ready, just go to api webbrowser and provide localhost/api address

1) Navigate to POST /api/locations :

``` json
{
    "country": "pl",
    "locality": "Szczecin"
}
```
in return You will get {id} of location resource

2) Navigate to GET /api/locations/{id}/weather and provide id of location.

In return You will get JSON with two temperatures from both services and average temperature of them. 

