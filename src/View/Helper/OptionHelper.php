<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Options helper
 */
class OptionHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected $_defaultConfig = [];

    public function roles()
    {
        $array =[
            'farmer'=>'Farmer',
            'staff'=>'Staff'
        ];

        return $array;
    }
    public function gender()
    {
        $array =[
            'Male'=>'Male',
            'Female'=>'Female',
        ];

        return $array;
    }
    public function status()
    {
        $array =[
            'Pending'=>'Pending',
            'Received'=>'Received',
            'Expired'=>'Expired',
            'Cancelled'=>'Cancelled',
        ];

        return $array;
    }
}
