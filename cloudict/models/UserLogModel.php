<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Goran
 * Date: 6/14/2015
 * Time: 6:24 PM
 */

class UserLogModel extends CI_Model {



    public function login($IdUser)
    {
        if($IdUser != null){

            $LoggedIn = time();
            $query = "
                INSERT INTO `UserLog`(`IdUser`,`UserLogLoggedIn`)

                VALUES (?,?);
            ";
            $result = $this->db->query($query, [$IdUser,$LoggedIn]);
            return $result;

        }

        return null;
    }

    /**Get all logged in users
     *
     * @return mixed
     */
    public function getUsersLoggedIn()
    {

        $query = "

            SELECT IdUser FROM `UserLog`
            WHERE UserLogLoggedOut IS NULL
        ";

        $result = $this->db->query($query)->result();

        return $result;
    }

}


