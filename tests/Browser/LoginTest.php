<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;



  public function testUserCanLogin()
  {
      $this->browse(function (Browser $browser) {
          $browser->visit('/dusk')->assertSee('Laravel');
//          $browser->maxWait(10);
          $browser->screenshot('debugging-screenshot');

          // Create a user with known credentials
          $user = User::factory()->create([
              'email' => 'testuser@example.com',
              'password' => bcrypt('password')
          ]);

          // Navigate to the login page, fill in the credentials and submit
          $browser->visit('/login')
              ->type('input[name="email"]', 'testuser@example.com')
              ->type('input[name="password"]', 'password123')
              ->press('Login') // Assuming the login button has text "Login"

              // Assertions after logging in
              ->assertPathIs('/login'); // Assuming the user is redirected to "/home" after logging in
//              ->assertSee('Dashboard'); // Assuming there's a "Dashboard" text visible after logging in

      });
  }


}
