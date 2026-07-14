<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FarmersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FarmersTable Test Case
 */
class FarmersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FarmersTable
     */
    protected $Farmers;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected $fixtures = [
        'app.Farmers',
        'app.Users',
        'app.Beneficiaries',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Farmers') ? [] : ['className' => FarmersTable::class];
        $this->Farmers = $this->getTableLocator()->get('Farmers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Farmers);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\FarmersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @uses \App\Model\Table\FarmersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
