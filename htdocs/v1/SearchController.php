<?php

use \Jacwright\RestServer\RestException;

class SearchController
{
    /**
     * Limite por defecto para las ultimas busquedas vigentes
     */
    static $last_default_limit = 5;

    /**
     * Returns a JSON string object to the browser when hitting the root of the domain
     *
     * @url GET /search
     * @url GET /search/
     * @url GET /search/$id
     */
    public function search($id = null)
    {
        $search = $this->getSearch($id);

        if($search['id'] == '') {
            $result['status'] = 'notfound';
        }

        $result['data'] = $search;
        return $result;
    }

    /**
     * Returns a list of last missing children in JSON format
     *
     * @url GET /last
     * @url GET /last/
     * @url GET /last/$limit
     */
    public function last($limit = null)
    {
        $last = $this->getLast($limit);

        if(count($last) == 0) {
            $result['status'] = 'notfound';
        }

        $result['data'] = $last;
        return $result;
    }

    /**
     * Gets the user by id or current user
     *
     * @url GET /users/$id
     * @url GET /users/current
     */
    public function getUser($id = null)
    {
        // if ($id) {
        //     $user = User::load($id); // possible user loading method
        // } else {
        //     $user = $_SESSION['user'];
        // }

        return array("id" => $id, "name" => null); // serializes object into JSON
    }

    /**
     * Saves a user to the database
     *
     * @url POST /users
     * @url PUT /users/$id
     */
    public function saveUser($id = null, $data)
    {
        // ... validate $data properties such as $data->username, $data->firstName, etc.
        // $data->id = $id;
        // $user = User::saveUser($data); // saving the user to the database
        $user = array("id" => $id, "name" => null);
        return $user; // returning the updated or newly created user object
    }

    /**
     * Get Charts
     * 
     * @url GET /charts
     * @url GET /charts/$id
     * @url GET /charts/$id/$date
     * @url GET /charts/$id/$date/$interval/
     * @url GET /charts/$id/$date/$interval/$interval_months
     */
    public function getCharts($id=null, $date=null, $interval = 30, $interval_months = 12)
    {
        echo "$id, $date, $interval, $interval_months";
    }

    /**
     * Throws an error
     * 
     * @url GET /error
     */
    public function throwError() {
        throw new RestException(401, "Empty password not allowed");
    }

    /**
     * Devuelve una busqueda de un chico
     */
    function getSearch($id = null) {

        $busquedas = json_decode(file_get_contents('../../missing.json'), TRUE);

        foreach($busquedas as $key => $busqueda) {
            if($busqueda['id'] == '') {
                $busquedas[$key] = array_merge(
                    array( 'id' => md5($busqueda['nombre'].' '.$busqueda['apellido'].' '.$busqueda['fecha_desaparicion']) ),
                    $busqueda
                );
            }

            // Calcular Edad desde fecha de nacimiento
            if($busqueda["fecha_nacimiento"] != '') {
                if($busqueda['edad'] == '')
                $dt_now = new DateTime("now");
                $dt_nacimiento = new DateTime($busqueda["fecha_nacimiento"]);
                $dt_foto = new DateTime($busqueda["fecha_foto"]);
                $edad = $dt_nacimiento->diff($dt_now); 
                $edad_foto = $dt_nacimiento->diff($dt_foto); 

                if($busqueda["edad"] == '')
                    $busquedas[$key]["edad"] = intval($edad->format("%y"));
                if($busqueda["edad_foto"] == '')
                    $busquedas[$key]["edad_foto"] = intval($edad_foto->format("%y"));
            }
        }

        $result = array();
        if($id != '') {
            foreach($busquedas as $search)
                if($search["id"] == $id) {
                    $result = $search;
                    break;
                }

            return $result;
        }

        return $busquedas[array_rand($busquedas,1)];
    }

    /**
     * Devuelve las ultimas busquedas vigentes
     */
    function getLast($limit = null) {

        if ($limit == '') {
            $limit = static::$last_default_limit;
        }

        $busquedas = (array) json_decode(file_get_contents('../../missing.json'), TRUE);

        // Oredenamos las busquedas por fecha de desaparicion descendente
        usort($busquedas, function($a, $b){
            return -strcmp($a['fecha_desaparicion'], $b['fecha_desaparicion']);
        });

        $last = array_slice($busquedas, 0, $limit, true);

        foreach($last as $key => $busqueda) {
            if($busqueda['id'] == '') {
                $last[$key] = array_merge(
                    array( 'id' => md5($busqueda['nombre'].' '.$busqueda['apellido'].' '.$busqueda['fecha_desaparicion']) ),
                    $busqueda
                );
            }

            // Calcular Edad desde fecha de nacimiento
            if($busqueda["fecha_nacimiento"] != '') {
                if($busqueda['edad'] == '')
                $dt_now = new DateTime("now"); 
                $dt_nacimiento = new DateTime($busqueda["fecha_nacimiento"]);
                $dt_foto = new DateTime($busqueda["fecha_foto"]);
                $edad = $dt_nacimiento->diff($dt_now); 
                $edad_foto = $dt_nacimiento->diff($dt_foto); 

                if($busqueda['edad'] == '')
                    $last[$key]['edad'] = intval($edad->format("%y"));
                if($busqueda['edad_foto'] == '')
                    $last[$key]['edad_foto'] = intval($edad_foto->format("%y"));
            }
        }

        return $last;
    }
}
