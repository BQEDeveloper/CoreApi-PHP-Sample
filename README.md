# CoreAPI-PHP-Sample

Sample code demonstrating OAuth and other features of Core API.

## Getting Started

1. Clone the Core API-PHP-Sample project on your local environment.
2. Go to Config.ini, insert the client secret, clientID and redirectURI of you app. Please note the redirectURI should point to the index.php file of the project
e.g. if you are running PHP on your localhost with port 1111, the redirectURI might look something like http://localhost:1111/CoreApi-PHP-Sample
3. Run the project. 

### Prerequisites

A PHP environment comprising of Apache. You can download them from [Xampp](https://www.apachefriends.org/download.html).

### What is supported?
1. Authorization 
2. Authentication
3. Activity - Retrieve, Create, Update and Delete

### Querying
We allow the following simple filters on different endpoints:

* Fields - To specify only those model properties which you want in the response body
* Where -  To specify only those records that match the query expression
* Order By - To specify by which field you want to order the item list
* Page -  To specify the page number and number of records on each page

Core API allows operators to manipulate individual data items and return a result set. To know more go to [Core Operators](https://api-explorer.bqecore.com/docs/filtering#filter-operators)

## Built With

* [PHP 5.6](http://php.net/releases/5_6_0.php)


