<?php

namespace Conkal\Columbus;


use GuzzleHttp\Client;

class Columbus
{
    private $password;
    private $username;
    private $from = "PETEKPASTAN";

    private $registerId;

    public function __construct($username, $password, $from)
    {
        $this->username = $username;
        $this->password = $password;
        $this->from = $from;
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
            <version>1.0</version>
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

    public function sendSMS($to, $message)
    {
        $xml = <<<XML
<soapenv:Envelope
	xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
	xmlns:web="http://webservice.proxy.columbus.turkcelltech.com/">
	<soapenv:Header/>
	<soapenv:Body>
		<web:sendSms>
			<msg>
				<body>
					<messages>
						<message>
							<contentList>
								<content>
									<msgContent>$message</msgContent>
								</content>
							</contentList>
							<dstMsisdnList>
								<msisdn>$to</msisdn>
							</dstMsisdnList>
							<chargingMultiplier>0</chargingMultiplier>
							<msgCode>7844</msgCode>
							<sender>$this->from</sender>
							<notificationNeeded></notificationNeeded>
							<validityPeriod>3</validityPeriod>
							<variantId>3797</variantId>
						</message>
					</messages>
				</body>
				<header>
					<registerId>$this->registerId</registerId>
				</header>
			</msg>
		</web:sendSms>
	</soapenv:Body>
</soapenv:Envelope>
XML;
        $client = new Client();
        $response = $client->post('https://sdp.kktcell.com/Columbus/sendSms', [
            'headers' => [
                'Content-Type' => 'text/xml',
            ],
            'body' => $xml,
            'verify' => false,
        ]);
        $xml = simplexml_load_string($response->getBody()->getContents());
        $xml->registerXPathNamespace('S', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xml->registerXPathNamespace('ns2', 'http://webservice.proxy.columbus.turkcelltech.com/');
        $errorCode = (string)$xml->xpath(
            '//S:Body/ns2:sendSmsResponse/return/messageResponses/messageResponse/errorCode'
        )[0];
        $smsId = (string)$xml->xpath(
            '//S:Body/ns2:sendSmsResponse/return/messageResponses/messageResponse/msgIdList'
        )[0];
        return ['errorCode' => $errorCode, 'smsId' => $smsId];
    }


}
