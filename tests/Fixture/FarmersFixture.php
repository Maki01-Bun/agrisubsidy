<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FarmersFixture
 */
class FarmersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'first_name' => 'Lorem ipsum dolor sit amet',
                'last_name' => 'Lorem ipsum dolor sit amet',
                'middle_name' => 'Lorem ipsum dolor sit amet',
                'birthdate' => '2026-06-17',
                'gender' => 'Lorem ipsum dolor sit amet',
                'address' => 'Lorem ipsum dolor sit amet',
                'contact_no' => 1,
                'user_id' => 1,
                'created' => '2026-06-17 06:53:52',
                'modified' => '2026-06-17 06:53:52',
            ],
        ];
        parent::init();
    }
}
