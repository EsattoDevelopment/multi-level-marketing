<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testeLoginMaster()
    {
        $this->artisan('migrate');
        $this->artisan('db:seed');

        $this->visit('auth/login')
            ->type('admin@teste.com.br', 'username')
            ->type('teste123*', 'password')
            ->press('entrar')
            ->seePageIs('/home');
    }

    public function testeLogout()
    {
        $this->visit('auth/logout')
            ->seePageIs('auth/login');
    }

    public function testeLoginEmpresa()
    {
        $this->visit('auth/login')
            ->type('diretoria@empresa.com.br', 'username')
            ->type('teste123*', 'password')
            ->press('entrar')
            ->seePageIs('/home');

        $this->artisan('migrate:rollback');
    }
}
