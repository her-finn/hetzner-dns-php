<?php
    Class hetznerDns{
        var $secret;
        var $url;
        /*
            Init Secret and url
        */
        function __construct($secret,$url="https://dns.hetzner.com/api/v1"){
            $this->secret = $secret;
            $this->url = $url;
        }
        /*
            Send new Request to Dns-Api
                @path: dir in Dns-Api
                @params: Query-Params (Optional) [Array]
        */
        private function request($path,$params=null,$method=null,$postfields=null){
            $ch = curl_init();
            switch($method){
                case "POST":
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                break;
                case null:
                    # Do nothing
                break;
                default:
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
            }
            curl_setopt($ch, CURLOPT_URL,$this->url.$path."?".http_build_query(array_filter($params)));
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                "Auth-API-Token: {$this->secret}"
            ));
            $response = curl_exec($ch);
            $responseArray = json_decode($response,true);
            if($responseArray['message'] == "Invalid authentication credentials"){
                return(false);
            }
            return($responseArray);
        }
        /*
            Get all DNS-Zones
        */
        function getAllZones(){
            $return = $this->request("/zones");
            return($return);
        }
        /*
            Get Zone by ID
                @domain-id: ID of Domain-Zone
        */
        function getZoneById($domainId){
            $return = $this->request("/zones/$domainId");
            return($return);
        }
        /*
            Get all DNS-Records for Domain
                @domain-id: ID of Domain-Zone
        */
        function getRecordsForDomain($domainId){
            $return = $this->request("/records",array("zone_id"=>$domainId));
            return($return);
        }
        /*
            Get Record by ID
                @record-id: ID of Record
        */
        function getRecordById($recordId){
            $return = $this->request("/records/$recordId");
            return($return);
        }
        /*
            Create Record for Domain
                @domain-id: ID of Domain-Zone
                @name: Name of record 
                @type: Type of Record ("A" "AAAA" "NS" "MX" "CNAME" "RP" "TXT" "SOA" "HINFO" "SRV" "DANE" "TLSA" "DS" "CAA")
                @value: Value of record (e.g. 127.0.0.1, 1.1.1.1)
                @ttl: TTL of Record (Default: 86400)
        */
        function createRecordForDomain($domainId,$name,$type,$value,$ttl=86400){
            $postfields = json_encode([
                "zone_id" => $domainId,
                "name" => $name,
                "type" => $type,
                "value" => $value,
                "ttl" => $ttl,
            ]);
            $return = $this->request("/records",null,"POST",$postfields);
            return($return);
        }
        /*
            Update Record for Domain
                @domain-id: ID of Domain-Zone
                @record-id: ID of Record
                @name: Name of record 
                @type: Type of Record ("A" "AAAA" "NS" "MX" "CNAME" "RP" "TXT" "SOA" "HINFO" "SRV" "DANE" "TLSA" "DS" "CAA")
                @value: Value of record (e.g. 127.0.0.1, 1.1.1.1)
                @ttl: TTL of Record (Default: 86400)
        */
        function updateRecordForDomain($domainId,$recordId,$name,$type,$value,$ttl=86400){
            $postfields = json_encode([
                "zone_id" => $domainId,
                "name" => $name,
                "type" => $type,
                "value" => $value,
                "ttl" => $ttl,
            ]);
            $return = $this->request("/records/$recordId",null,"PUT",$postfields);
            return($return);
        }
        /*
            Delete Record for Domain
                @record-id: ID of Record
        */
        function deleteRecordForDomain($recordId){
            $return = $this->request("/records/$recordId",null,"DELETE");
            return($return);
        }
    }
