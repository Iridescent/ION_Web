<?php

class Zend_Amf_Client {
    private $endPoint;
    private $encoding = 0;
    
    private $amfRequest;
    private $amfResponse;
    
    const FLEXMSG = 16;
    
    public function __construct($endPoint) {
        $this->endPoint = $endPoint;

        $this->amfRequest = new SabreAMF_Message();
        $this->amfOutputStream = new Zend_Amf_Parse_OutputStream();
    }
    
    public function sendRequest($servicePath, $data) {

        if($this->encoding & self::FLEXMSG) {
            // Setting up the message
            $message = new Zend_Amf_Value_Messaging_RemotingMessage();
            $message->body = $data;

            // We need to split serviceName.methodName into separate variables
            $service = explode('.',$servicePath);
            $method = array_pop($service);
            $service = implode('.',$service);
            $message->operation = $method; 
            $message->source = $service;

            $data = $message;
        }
        
        $this->amfRequest->addBody(array(
            'target'   => $this->encoding & self::FLEXMSG ? 'null' : $servicePath,
            'response' => '/1',
            'data'     => $data
        ));
        
        $this->amfRequest->serialize($this->amfOutputStream);
    }
}

?>
