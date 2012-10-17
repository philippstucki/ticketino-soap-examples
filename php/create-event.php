<?php
/*

How to use this example:
1) Adjust the values in $authFields according to your credentials
2) Run from the CLI using 'php create-event.php'

This example creates one event with id 12345 and then deletes it again.


Copyright (c) 2012 Stucki & Stucki GmbH
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met: 

1. Redistributions of source code must retain the above copyright notice, this
   list of conditions and the following disclaimer. 
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the documentation
   and/or other materials provided with the distribution. 

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/

// holds username and password
$authFields = array(
    'Username' => 'Your Username',
    'Password' => 'Your Password',
);

// helper function to dump soap calls for debugging
function dumpSoapError($sc)
{
    $d = '';

    $d.= "\n---Request Headers---\n";
    $d.= $sc->__getLastRequestHeaders();

    $d.= "\n\n---Request ---\n";
    $d.= $sc->__getLastRequest();

    $d.= "\n---Response Headers---\n";
    $d.= $sc->__getLastResponseHeaders();

    $d.= "\n\n---Response ---\n";
    $d.= $sc->__getLastResponse();

    return $d."\n\n";        
}

// create instance of SoapClient
$ticketinoSoap = new SoapClient(
    // wsdl url as given by service provider
    'https://www.ticketino.com/partnerservice/default.asmx?WSDL',

    array(
        // enables tracing of requests, options are documented here:
        // http://php.net/manual/en/soapclient.soapclient.php
        'trace' => TRUE,
    )
);

$defaultEventFields = array(
    'Username' => '',
    'Password' => '',
    'Version' => '1',
    'Action' => '',
    'EventNumber' => '',
    'MaxNumberEvent' => '',
    'MaxNumberOrder' => '',
    'ActivateSale' => 'no',
    'SaleCreditcard' => '',
    'SaleInvoice' => '',
    'SaleReservation' => '',
    'SaleNotification' => '',
    'SaleEmail' => '',
    'Googlemap' => '',
    'QuickOrderFlow' => '',
    'CompanyMandatory' => '',
    'EventPassword' => '',
    'AdvancebookingAfter' => '2',
    'ListPosition' => '',
    'EventName' => '',
    'EventStart' => '',
    'EventOpening' => '',
    'EventEnd' => '',
    'EventShortdescription' => '',
    'EventDescription' => '',
    'Artist' => '',
    'EmailRemark' => '',
    'Url' => '',
    'Zip' => '8005',
    'City' => 'Zürich',
    'District' => '',
    'Street' => 'Hardstrasse 245',
    'Country' => 'CH',
    'Location' => 'EXIL',
    'Genre' => '140',
    'OrganizerName' => 'EXIL',
    'TicketContingentWarning' => '',
    'TicketThreshold' => '',
    'OrganizerVat' => '',
    'HideEventinfoOnSoldOut' => '',
);

$createEventFields = array_merge(
    $defaultEventFields,

    // merge fields unique to a event
    array(
        // 1 ≃ 'Create'
        'Action' => '1',

        // event id in partner's database
        'EventNumber' => '12345',

        // set this to 'yes' for production
        'ActivateSale' => 'no',

        'MaxNumberEvent' => 20,
        'EventName' => 'Event Name',
        'EventStart' => '2012-11-01 10:00',
        'EventOpening' => '2012-11-01 09:00',
        'EventEnd' => '2012-11-01 09:00',
        'EventShortdescription' => 'Short text describing the event.',
        'EventDescription' => 'Long text describing the event.',
        'Artist' => 'Artist\'s Name',
        'Url' => 'http://exil.cl',
        'EventImage' => 'http://exil.cl/dynimage/tto/files/images/events/201210/Gigamesh.jpg',
    ),

    // merge auth fields
    $authFields
);

// do the soap call to create an event and check the result
try {
    echo "Creating Event...";
    $res = $ticketinoSoap->createEvent($createEventFields);

    if ($res->createEventResult != 0) {
        echo "FAILED (code: {$res->createEventResult})";
        echo dumpSoapError($ticketinoSoap);
    } else {
        echo "OK\n";
    }

} catch(Exception $e) {
    echo("FAILED\n");
    echo dumpSoapError($ticketinoSoap);
}

$deleteEventFields = array_merge(
    $defaultEventFields,

    // merge fields unique to a event
    array(
        // 3 ≃ 'Delete'
        'Action' => '3',

        // event id in partner's database
        'EventNumber' => '12345',
    ),

    // merge auth fields
    $authFields
);

// do a soap call to delete the very same event we created with the first call
try {
    echo "Deleting Event...";
    $res = $ticketinoSoap->createEvent($deleteEventFields);

    if ($res->createEventResult != 0) {
        echo "FAILED (code: {$res->createEventResult})";
        echo dumpSoapError($ticketinoSoap);
    } else {
        echo "OK\n";
    }

} catch(Exception $e) {
    echo("FAILED\n");
    echo dumpSoapError($ticketinoSoap);
}

