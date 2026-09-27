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
            'Not Received'=>'Not Received'
        ];

        return $array;
    }
    public function subsidy()
    {
        $array =[
            'Corn Seeds'=>'Corn Seeds',
            'Rice Seeds'=>'Rice Seeds',
        ];

        return $array;
    }

    public function filterEffectiveness()
    {
        $array =[
            'Effective'=>'Effective',
            'Not Effective'=>'Not Effective',
            'Moderately Effective'=>'Moderately Effective'
        ];

        return $array;
    }

    public function filterStatus()
    {
        return [
            'Received' => 'Received',
            'Cancelled' => 'Cancelled',
            'Not Received' => 'Not Received'
        ];
        return $array;
    }
}
