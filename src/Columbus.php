<?php

namespace Conkal\Columbus;


use GuzzleHttp\Client;

class Columbus
{
    private $password;
    private $username;
    private $version = "1.0";

    private $registerId;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function register()
    {
        $xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://webservice.proxy.columbus.turkcelltech.com/">
   <soapenv:Header/>
   <soapenv:Body>
      <web:register>
         <reg>
            <parameters>
               <entry>
                  <key>?</key>
                  <value>?</value>
               </entry>
            </parameters>
            <password>$this->password</password>
            <username>$this->username</username>
            <version>$this->version</version>
         </reg>
      </web:register>
   </soapenv:Body>
</soapenv:Envelope>
XML;

        $client = new Client();
        $response = $client->post('https://sdp.kktcell.com/Columbus/register', [
            'headers' => [
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
            'verify' => false, // You may need to change this based on your server's SSL configuration
        ]);
        $xml = simplexml_load_string($response->getBody()->getContents());
        $xml->registerXPathNamespace('S', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns2', 'http://webservice.proxy.columbus.turkcelltech.com/');
        $this->registerId = (string)$xml->xpath('//S:Body/ns2:registerResponse/return/registerId')[0];
        return $this->registerId;
    }

    public function getRegisterId()
    {
        return $this->registerId;
    }


}
