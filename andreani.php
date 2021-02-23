<?php

//namespace AndreaniCDS;

class Andreani
{
    const BASE_URL_DEV = 'https://api.qa.andreani.com';
    const BASE_URL_PROD = 'https://api.andreani.com';

    public $user = null;
    public $password = null;
    public $debug = true;
    public $cliente = null;
    public $response = null;
    public $token = null;

    public function __construct($user, $password, $debug = true){
        if (empty($user) || empty($password)) {
            throw new Exception('Faltan las credenciales');
        }

        $this->user = $user;
        $this->password = $password;
        $this->debug = $debug;
        //$this->cliente = $cliente;
        //$this->response = null;
        //$this->token = null;

    }

    private function getBaseUrl(){
        $base = $this->debug ? self::BASE_URL_DEV : self::BASE_URL_PROD;
        return $base;
    }

    private function encodeAuthKey(){
        $key = base64_encode($this->user.":".$this->password);
        return $key;
    }

    public function getToken(){
        
        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl().'/login');

        $headers = array();

        $headers[] = 'Authorization: Basic '.$this->encodeAuthKey();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            # En caso de haber un error curl lo muestro
            echo '<pre>';
            echo 'Error:' . curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($httpcode!=200){
            # Emito el error en caso que el login este mal
            echo 'Error:' .$result;
        }else{

            $headers=explode("\n",$result);
            foreach($headers as $header){
                
                $dato=explode(":",$header);
                
                if($dato[0]=='X-Authorization-token'){
                    $token=trim($dato[1]);
                    break;
                }
            }
            # El token filtrado en $token
            return $token;
        }
    }

    public function cotizarEnvio($cpDestino, $contrato, $cliente, $sucursalOrigen, $bultos){
        
        $params = array(
            'cpDestino' => $cpDestino,
            'contrato' => $contrato,
            'bultos' => $bultos,
            'cliente' => $cliente,
        );

        $params = http_build_query($params);

        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl().'/v1/tarifas?'.$params);

        //curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            # En caso de haber un error curl lo muestro
            echo '<pre>';
            echo 'Error:' . curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        //$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        //$headerstring = substr($result, 0, $header_size);
        //$result = substr($result, $header_size);

        if($httpcode!=200){
            //echo 'Error:' .$result;
        }else{
            $return = array();
            $return[] = $result;
            $return["success"] = true;

            return $return;
        }  

    }

    public function crearEnvio($datos){
        
        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl().'/v2/ordenes-de-envio');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'x-authorization-token: '.$this->getToken();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datos);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            # En caso de haber un error curl lo muestro
            echo '<pre>';
            echo 'Error:' . curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerstring = substr($result, 0, $header_size);
        $result = substr($result, $header_size);

        if($httpcode!=202){
            echo 'Error:' .$result;
        }else{
            
            $return = array();
            $return[] = $result;
            $return["success"] = true;

            return $return;
        }
    }

    public function getEtiqueta($numeroAndreani){

        $ch = curl_init(); 

        curl_setopt($ch, CURLOPT_URL, $this->getBaseUrl().'/v2/ordenes-de-envio/'.$numeroAndreani.'/etiquetas');

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'x-authorization-token: '.$this->getToken();

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            # En caso de haber un error curl lo muestro
            echo '<pre>';
            echo 'Error:' . curl_error($ch);
        }

        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headerstring = substr($result, 0, $header_size);
        $result = substr($result, $header_size);

        if($httpcode!=200){
            echo 'Error:' .$result;
        }else{
            $return = array();
            $return[] = $result;
            $return["success"] = true;

            return $return;
        }  

    }

       
    
} //fin class
