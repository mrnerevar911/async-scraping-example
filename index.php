<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

$client = new Client();

for ($i = 1; $i <= 10; $i++) { // let's scrape 10 users
    $promise = $client->requestAsync('GET', "https://jsonplaceholder.typicode.com/users/$i", ['delay' => rand(1000, 5000)]); // simulation of network delays
    $promise->then(
        function (ResponseInterface $res) {
            $data = json_decode($res->getBody()); // decoding response
            saveUser($data); // saving data
        },
        function (RequestException $e) {
            echo "Error while scraping user \n";
            echo $e->getMessage() . "\n";
        }
    );
}

/**
 * Establish MySQL connection
 * @param $host
 * @param $username
 * @param $passwd
 * @param $dbname
 * @return mysqli connection
 */
function connectToDb($host, $username, $passwd, $dbname)
{
    $mysqli = new mysqli($host, $username, $passwd, $dbname);
    if ($mysqli->connect_errno) {
        echo "Could not connect to database: \n";
        echo "Error: " . $mysqli->connect_error . "\n";
        exit;
    }
    return $mysqli;
}

/**
 * Data saving example (via MySQL)
 * @param $data
 * @return bool
 */
function saveUser($data)
{
    $database = connectToDb('127.0.0.1', 'root', '', 'async-fun');

    $name = isset($data->name) ? (string)$data->name : NULL;
    $username = isset($data->username) ? (string)$data->username : NULL;
    $email = isset($data->email) ? (string)$data->email : NULL;

    $existUserQuery = $database->query("SELECT username FROM `users` WHERE `username` = '$username'");
    if (!$existUserQuery) {
        echo "Error: " . $database->error . "\n";
        return false;
    }
    $existUser = $existUserQuery->fetch_array();

    if (!$existUser) {
        $database->query("INSERT INTO `users` (`id`, `name`, `username`, `email`) VALUES ( NULL, '$name', '$username', '$email')");
        echo "Added " . $username . "\n";
        return true;
    } else {
        echo "$username already exists! Skipping... \n";
        return true;
    }
}

echo "I am async!\n";

$promise->wait();

echo "Done!";
