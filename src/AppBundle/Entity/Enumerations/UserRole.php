<?php
namespace AppBundle\Entity\Enumerations;

class UserRole
{

    /**
     * Constants keys for DB persists
     */
    const ADMIN = "admin";
    const USER = "user";

    /**
     * String to display by cont key
     *
     * @var array
     */
    private static $values = [
        self::ADMIN => "ROLE_ADMIN",
        self::USER => "ROLE_USER"
    ];

    /**
     * Return the differents availables keys
     *
     * @return array
     */
    public static function getAvailableRoles() : array
    {
        return [
            self::ADMIN,
            self::USER
        ];
    }

    /**
     * Permit to get a value related to a key
     *
     * @param $key string
     *
     * @return mixed|string
     */
    public static function getValue(string $key) : string
    {
        if (!isset(static::$values[$key])) {
            return "Unknow " . get_called_class();
        } else {
            return static::$values[$key];
        }
    }

}
