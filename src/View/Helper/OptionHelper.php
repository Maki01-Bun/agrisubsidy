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
            'Cancelled'=>'Cancelled',
            'Expired'=>'Expired'
        ];

        return $array;
    }

    public function calamity()
    {
        $array = [
            'None' => 'None',
            'Typhoon' => 'Typhoon',
            'Flood' => 'Flood',
            'Drought' => 'Drought',
            'Earthquake' => 'Earthquake',
            'Landslide' => 'Landslide',
            'El Niño' => 'El Niño',
            'La Niña' => 'La Niña',
            'Storm Surge' => 'Storm Surge',
            'Volcanic Eruption' => 'Volcanic Eruption',
            'Strong Winds' => 'Strong Winds',
            'Hailstorm' => 'Hailstorm',
            'Others' => 'Others',
        ];
    
        return $array;
    }
    public function subsidy()
    {
        $array =[
            'Seed Subsidy'=>'Seed Subsidy',
            'Fertilizer Subsidy'=>'Fertilizer Subsidy',
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
