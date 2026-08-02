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
    public function pests()
    {
        $array = [
            'Brown Planthopper'      => 'Brown Planthopper',
            'Green Leafhopper'       => 'Green Leafhopper',
            'Rice Black Bug'         => 'Rice Black Bug',
            'Stem Borer'             => 'Stem Borer',
            'Rice Bug'               => 'Rice Bug',
            'Leaf Folder'            => 'Leaf Folder',
            'Armyworm'               => 'Armyworm',
            'Cutworm'                => 'Cutworm',
            'Fall Armyworm'          => 'Fall Armyworm',
            'Corn Earworm'           => 'Corn Earworm',
            'Fruit Fly'              => 'Fruit Fly',
            'Aphids'                 => 'Aphids',
            'Whiteflies'             => 'Whiteflies',
            'Thrips'                 => 'Thrips',
            'Mealybugs'              => 'Mealybugs',
            'Scale Insects'          => 'Scale Insects',
            'Spider Mites'           => 'Spider Mites',
            'Golden Apple Snail'     => 'Golden Apple Snail',
            'Rats'                   => 'Rats',
            'Birds'                  => 'Birds',
            'Blast Disease'          => 'Blast Disease',
            'Bacterial Leaf Blight'  => 'Bacterial Leaf Blight',
            'Sheath Blight'          => 'Sheath Blight',
            'Tungro Virus'           => 'Tungro Virus',
            'Others'                 => 'Others',
            'None'                   => 'None',
        ];
    
        return $array;
    }
}
