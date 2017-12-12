### Nearest Schools

A application that using entering a postcode and distance will find all schools up to the distance from that postcode.

This application uses Open Data to build a full list of schools in England together with the school's eographic coordinates.

When a postcode is entered, it's coordinates are returned from an Open Data API call. The coordinates are then used to find and display all schools that are within a radius distance (default distance is 2 miles).

#### Requirements

* PHP7
* composer
* make or gmake - useful but not essential.

#### Project Setup

`make setup` runs composer, installing dependencies, and generates the schools and postcode data files used by the application.

If no make tool available run `composer install` followed by `php setupData.php`.

Once installed and a web server virtual host enviroment is directed to the folder, a browser directed to the virtual host will run the application.

### TODO
Output results in distance order.
