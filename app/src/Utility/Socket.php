<?php

namespace app\src\Utility;


class Socket
{
    /**
     * full connection TCP
     * 
     * @return void
     */
    public function tcpSend($host, $port, $msg)
    {
        $msg = is_array($msg)? json_encode($msg, true) : $msg;

        $result = 'Error: could not open socket connection'; 
        $fp = fsockopen ($host, $port, $errno, $errstr); 
        if ($fp) {  //fgets ($fp, 1024);
            fputs($fp, $msg);
            fclose ($fp);
            $result = 'Sent';
        }

        return $result;
    }

    /**
     * one way TCP/UDP
     * 
     * @return void
     */
    public function oneWaySend($host, $port, $msg, $isTcp = true)
    {
        $msg = is_array($msg)? json_encode($msg, true) : $msg;

        //initiate
        $socket = $isTcp ? socket_create(AF_INET, SOCK_STREAM, SOL_TCP) 
            : socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if($socket){
            //preparing send & recieve timeout (sec:seconds, usec:milliseconds)
            socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => 0, 'usec' => 500));
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 0, 'usec' => 500));

            //write & close
            if(socket_connect($socket, $host, $port)){
                socket_write($socket, $msg, strlen($msg)); //send to server
                socket_close($socket); //flush
            }
        }
    }

    /**
     * via UDP
     * 
     * @return void // unfinished
     */
    public function udpSend($host, $port, $msg)
    {
        if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }

        echo "Socket created \n";

        //Communication loop
        while(1)
        {
            //Take some input to send
            echo 'Enter a message to send : ';
            $input = fgets(STDIN);
            
            //Send the message to the server 0
            if( ! socket_sendto($sock, $input , strlen($input) , MSG_DONTROUTE , $host , $port))
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                
                die("Could not send data: [$errorcode] $errormsg \n");
            }
                
            //Now receive reply from server and print it
            if(socket_recv ( $sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
            {
                $errorcode = socket_last_error();
                $errormsg = socket_strerror($errorcode);
                
                die("Could not receive data: [$errorcode] $errormsg \n");
            }
            
            echo "Reply : $reply";
        }
    }
}