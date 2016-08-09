<?php
require_once('vendor/autoload.php');

$client = new Everyman\Neo4j\Client('akrdb.sb10.stations.graphenedb.com', 24789);
$client->getTransport()->setAuth('akrdb', '2oIheSXVcHlSd2oGQ09q');

?>