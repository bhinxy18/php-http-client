# php-http-client

## Requirements

### 1.
Using vanilla PHP, build a functioning light weight HTTP client capable of the following:

- Send HTTP requests to the given URL using different methods, such as GET, POST, etc.
- Send JSON payloads
- Send custom HTTP headers
- Retrieve HTTP response payloads
- Retrieve HTTP response headers
- All JSON payloads must be passed in as associative arrays
- All JSON payloads must be returned as associative arrays
- Any JSON conversion errors must throw an exception
- Erroneous HTTP response codes (e.g. 4xx, 5xx) must throw an exception
- No external libraries allowed! All code must be hand-written.
- Explicit use of CURL (e.g. curl_exec()) is not allowed!


### 2.
Using your HTTP client application submit your name, email address and the public URL of repository with your code to the endpoint below:

https://www.coredna.com/assessment-endpoint.php
Sample payload:
```
{
  "name": "John Doe",
  "email": "spamwelcomedhere@gmail.com",
  "url": "https://github.com/john-doe/http-client"
} 
```
The endpoint requires Bearer HTTP authentication. To get authentication token, it is required to send OPTIONS request to the endpoint URL.



## Assessment Criteria
- The code is clear and easy to read.
- PSR-12 coding style guidelines are followed.
- Classes, properties and methods are annotated with PhpDoc.
- Methods and classes respect single-responsibility principle.
- HTTP client does not need to comply with PSR-7 requirements, but compliance is a bonus.

## Install

#### POST request
- An example of POST request to endpoint provided when calling the ServiceGateway.
- Since the endpoint needs Authorization, a OPTION request is called everytime there's a request to endpoint
```
$gateway = new ServiceGateway('https://www.coredna.com/assessment-endpoint.php');
$users = new Users($gateway);

// Send JSON payload to endpoint
$user = new User();
$user->setName('Ruby Lamadora');
$user->setEmail('ruby.lamadora@gmail.com');
$user->setUrl('https://github.com/bhinxy18/php-http-client');

try {
    $response = $users->create($user);
} catch (\Exception $e) {
    echo "Error while posting User data with Exception message: " . $e->getMessage();
}

if ($response === true) {
    echo 'Woohoo, data posted successfully!';
}
```

#### GET request
- An example of GET request 
- The return should be a User model when request is successful
```
try {
    $response = $users->fetch(['name' => 'Ruby Lamadora']);
} catch(\Exception $e) {
    echo "Error while retrieving User data with Exception message: " . $e->getMessage();
}
```