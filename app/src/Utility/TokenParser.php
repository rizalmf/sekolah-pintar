<?php

namespace app\src\Utility;

use Firebase\JWT\JWT;

class TokenParser
{
    private $private_key = array(
        'admin' => 'This key belongs t0 you :3',
        'guru'  => 'T4npa pamrih. siapa dia',
        'siswa' => 'kas1h ibu kepada alpha romero'
    );
    
    private $alg = 'HS256';

    public function generateAccessAPI($scope, $key)
    {
        $raw = json_encode(array(
            'scope' => $scope,
            'dt' => strtotime(date('Y-m-d'))
        ));

        $chiper = $this->basicChiper(
            base64_encode($raw), $key
        );

        return JWT::encode($chiper, $this->private_key[$key], $this->alg);
    }

    public function parseAccessAPI($jwt, $key)
    {
        $decode = null;
        try {
            $decode = $this->basicChiper(
                JWT::decode($jwt, $this->private_key[$key], array($this->alg)),
                $key
            );
        } catch (\Exception $e) {
            // print_r($e->getMessage());
        }

        return json_decode(base64_decode($decode));
    }

    /**
     * Based on XOR Cipher encryption
     */
    private function basicChiper($string, $key)
    {
        // define key
        $key = $this->private_key[$key];

        // Our plaintext/ciphertext
        $text = $string;

        // Our output text
        $outText = '';

        // Iterate through each character
        for($i=0; $i<strlen($text); )
        {
            for($j=0; ($j<strlen($key) && $i<strlen($text)); $j++,$i++)
            {
                $outText .= $text[$i] ^ $key[$j];
                //echo 'i=' . $i . ', ' . 'j=' . $j . ', ' . $outText[$i] . '<br />'; // Debugging purpose
            }
        }
        return $outText;
    }
}