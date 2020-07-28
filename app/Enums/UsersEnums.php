<?php
namespace App\Enums;


abstract class UsersEnums
{
    const  supperAdminType = "1",
        emloyeeType = "2",
        clientType = "3",
        accountOwnerType = "4",
        activeStatus = "1",
        blockedStats= "0";


    public static $systemIds = array(
        1,
        );

    public static $allAuthIds = array(
        '4'
    );
    public static $normalAuthIds = array(
        '2',//Employee
    );
    public static $normalIds = array(
        '3',//Client
    );
    public static $usersType = array(
        1,2,3,4
    );
    public static $usersAdminType = array(
        1,2,3,4
    );
    public static $usersAccountType = array(
        3,2
    );
}