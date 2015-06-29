<?php
namespace mongosoft\soapclient;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use SoapClient;
use SoapFault;


/**
 * WebService model
 *
 * @property string $ccl
 * @property string $clave
 */



class WebService extends Component
{
    public $url;
    public $user;
    public $password;
    public $options = [];
    public $filters = [];
    private $_client;


    public function init()
    {
        parent::init();
        if ($this->url === null) {
            throw new InvalidConfigException('Propiedad "url" no especificada');
        }
        if ($this->user === null) {
            throw new InvalidConfigException('Propiedad "user" no especificada');
        }
        if ($this->password === null) {
            throw new InvalidConfigException('Propiedad "password" no especificada');
        }

        try {
            $this->_client = new SoapClient($this->url, $this->options);
        } catch (SoapFault $e) {
            throw new Exception($e->getMessage(), (int) $e->getCode(), $e);
        }

    }

    private function params(){
       return  $params = [
           'ccl' => $this->user,
           'clave' => $this->password,
       ];
    }

    private function getNameMetodo($method){
        return substr($method,strrpos($method,':')+1);
    }

    public function  getPoblaciones($cp){

        try {
           $params = self::params();
           $params['cp'] = $cp;

           $results = json_decode(json_encode($this->_client->cities_with_cp($params)), true);
           if(isset($this->filters[$this->getNameMetodo(__METHOD__)]))
               foreach($this->filters[$this->getNameMetodo(__METHOD__)] as $filtro){
                if(!empty($results[$filtro]))
                    $results = $results[$filtro];
                else
                    $results = [];
            }

            // UNA poblacion encontrada con ese cp
            if (!is_array($results)) {
                return [$results];
            }
           return $results;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }

    public function getProvincia($cp){

        try {
            $params = self::params();
            $params['cp'] = $cp;

            $results = json_decode(json_encode($this->_client->cities_with_cp($params)), true);

            if(isset($this->filters[$this->getNameMetodo(__METHOD__)]))
                foreach($this->filters[$this->getNameMetodo(__METHOD__)] as $filtro){
                    if(!empty($results[$filtro]))
                        $results = $results[$filtro];
                    else
                        $results = [];
                }


            // UNA poblacion encontrada con ese cp
            if (!is_array($results)) {
                return [$results];
            }
            return $results;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), (int) $e->getCode(), $e);
        }
    }




}
