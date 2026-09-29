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
            'admin'=>'Admin',
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
    public function location()
    {
        $array =[
            'Abra' => 'Abra',
                'Ambalatungan' => 'Ambalatungan',
                'Balintocatoc' => 'Balintocatoc',
                'Baluarte' => 'Baluarte',
                'Bannawag Norte' => 'Bannawag Norte',
                'Batal' => 'Batal',
                'Buenavista' => 'Buenavista',
                'Cabulay' => 'Cabulay',
                'Calao East' => 'Calao East',
                'Calao West' => 'Calao West',
                'Calaocan' => 'Calaocan',
                'Villa Gonzaga' => 'Villa Gonzaga',
                'Centro East' => 'Centro East',
                'Centro West' => 'Centro West',
                'Divisoria' => 'Divisoria',
                'Dubinan East' => 'Dubinan East',
                'Dubinan West' => 'Dubinan West',
                'Luna' => 'Luna',
                'Mabini' => 'Mabini',
                'Malvar' => 'Malvar',
                'Nabbuan' => 'Nabbuan',
                'Naggasican' => 'Naggasican',
                'Patul' => 'Patul',
                'Plaridel' => 'Plaridel',
                'Rizal' => 'Rizal',
                'Rosario' => 'Rosario',
                'Sagana' => 'Sagana',
                'Salvador' => 'Salvador',
                'San Andres' => 'San Andres',
                'San Isidro' => 'San Isidro',
                'San Jose' => 'San Jose',
                'Sinili' => 'Sinili',
                'Sinsayon' => 'Sinsayon',
                'Santa Rosa' => 'Santa Rosa',
                'Victory Norte' => 'Victory Norte',
                'Victory Sur' => 'Victory Sur',
                'Villasis' => 'Villasis'
        ];

        return $array;
    }
}
