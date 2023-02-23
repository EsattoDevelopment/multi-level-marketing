<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MigrateTest extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMigrateRun()
    {
        $this->artisan('migrate');
    }

    public function testSeed()
    {
        $this->artisan('db:seed');
    }

    public function testRollBack()
    {
        $this->artisan('migrate:rollback');
    }
}
