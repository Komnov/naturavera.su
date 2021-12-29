<?php
/**
 * Created by PhpStorm.
 * User: writ3it
 * Date: 19.10.19
 * Time: 21:48
 */

class tbwpf_OptFloatProperties extends tbwpf_OptProperties
{
    public $type = tbwpf_OptPropertyTypes::FLOAT;
    public $sqlCast = 'DECIMAL(10,2)';
    public $sqlType = 'DECIMAL(10,2) NOT NULL';


}