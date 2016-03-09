<?php
namespace MGLara\Support\Helpers;
use Carbon\Carbon;
use DateTime;

class Dates {
    /**
     * @param string $date
     *
     * @return bool
     */
  public static function isInternationalValid($date)
    {
        if (!(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date))) {
            return false;
        }
        
        $date=explode("-", $date);
        
        return checkdate($date[1], $date[2], $date[0]);
    }
    
    /**
     * @param string $date
     *
     * @return bool
     */
    public static function isBrazilianValid($date)
    {
        if (!preg_match("/^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/", $date)) {
            return false;
        }
        
        $date=explode("/", $date);
        
        return checkdate($date[1], $date[0], $date[2]);
    }
    /**
     * @param string $date
     *
     * @return bool
     */
    public static function isValidDate($date)
    {
        return (bool) (static::isInternationalValid($date) or static::isBrazilianValid($date));
    }
    /**
     * @param $value
     *
     * @return Carbon
     *
     * @throws \InvalidArgumentException
     */
    public static function toCarbonObject($value)
    {
        if($value instanceof DateTime)
        {
            return Carbon::instance($value);
        }
        if(static::isInternationalValid($value)) {
            return Carbon::createFromFormat('Y-m-d', $value);
        }
        if(static::isBrazilianValid($value))
        {
            return Carbon::createFromFormat('d/m/Y', $value);
        }
        throw new \InvalidArgumentException("[{$value}] not is valid date");
    }
}