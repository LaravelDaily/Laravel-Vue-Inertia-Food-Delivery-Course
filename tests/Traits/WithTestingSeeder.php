<?php

namespace Tests\Traits;

use App\Providers\AuthServiceProvider;

trait WithTestingSeeder
{
    public function setUpWithTestingSeeder()
    {
        $this->artisan('db:seed', ['--class' => 'TestingSeeder']);
        (new AuthServiceProvider(app()))->boot();
    }
}
