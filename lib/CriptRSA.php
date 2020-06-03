<?php

if ( ! defined( 'ABSPATH' ) ) exit;


class CriptRSA
{
    private $pub;

    private $key;

    /**
     * CriptRSA constructor.
     * @param $pub
     */
    public function __construct()
    {
        $this->pub = <<<SOMEDATA777
        -----BEGIN PUBLIC KEY-----
        MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALqbHeRLCyOdykC5SDLqI49ArYGYG1mq
        aH9/GnWjGavZM02fos4lc2w6tCchcUBNtJvGqKwhC5JEnx3RYoSX2ucCAwEAAQ==
        -----END PUBLIC KEY-----
        SOMEDATA777;


        $this->key = <<<SOMEDATA777
        -----BEGIN RSA PRIVATE KEY-----
        MIIBPQIBAAJBALqbHeRLCyOdykC5SDLqI49ArYGYG1mqaH9/GnWjGavZM02fos4l
        c2w6tCchcUBNtJvGqKwhC5JEnx3RYoSX2ucCAwEAAQJBAKn6O+tFFDt4MtBsNcDz
        GDsYDjQbCubNW+yvKbn4PJ0UZoEebwmvH1ouKaUuacJcsiQkKzTHleu4krYGUGO1
        mEECIQD0dUhj71vb1rN1pmTOhQOGB9GN1mygcxaIFOWW8znLRwIhAMNqlfLijUs6
        rY+h1pJa/3Fh1HTSOCCCCWA0NRFnMANhAiEAwddKGqxPO6goz26s2rHQlHQYr47K
        vgPkZu2jDCo7trsCIQC/PSfRsnSkEqCX18GtKPCjfSH10WSsK5YRWAY3KcyLAQIh
        AL70wdUu5jMm2ex5cZGkZLRB50yE6rBiHCd5W1WdTFoe
        -----END RSA PRIVATE KEY-----
        SOMEDATA777;
    }

    /**
     * @return mixed
     */
    public function getPub()
    {
        return $this->pub;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param $data
     * @return string
     */
    public function encode( $data )
    {
        if ($data) {
            $pk  = openssl_get_publickey( $this->pub );
            openssl_public_encrypt( $data, $encrypted, $pk );
            return chunk_split( base64_encode( $encrypted ) );
        } else {
            return "";
        }

    }

    /**
     * @param $encode_data
     * @return mixed
     */
    public function decode( $encode_data )
    {
        if ($encode_data) {
            $pk  = openssl_get_privatekey( $this->key );
            openssl_private_decrypt(base64_decode( $encode_data ), $out, $pk );
            return $out;
        } else {
            return "";
        }

    }

}