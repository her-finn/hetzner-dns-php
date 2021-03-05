# Inofficial Hetzner DNS-API implementation with PHP

## Get Started:
```
<?php
  require 'api.php';
  $apiToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
  $api = new hetznerDns($apiToken);
  ```
## Get all Zones:
```
  $zones = $api->getAllZones();
  print_r($zones);
```
## Get all Records for a Zone:
```
  $zoneId = "xxxx";
  $records = $api->getRecordsForDomain($zoneId);
  print_r($records);
```
## Create an Record in a Zone:
```
  $zoneId = "xxxx";
  $record['name'] = "www";
  $record['type'] = "A";
  $record['dest'] = "192.168.1.1";
  $api->createRecordForDomain($zoneId,$record['name'],$record['type'],$record['dest']);
```
## Update an Record in a Zone:
```
  $zoneId = "xxxx";
  $record['id'] = "xxxx";
  $record['name'] = "www";
  $record['type'] = "A";
  $record['dest'] = "192.168.1.1";
  $api->updateRecordForDomain($zoneId,$record['id'],$record['name'],$record['type'],$record['dest']);
```
## Delete Record by ID:
```
  $recordId = "xxxx";
  $api->deleteRecordForDomain($recordId);
```
