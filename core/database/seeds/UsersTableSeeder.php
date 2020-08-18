<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
    	/*DEVELOPER CREATION*/
        $credentials_developer = [
                    'first_name' => 'Developer',
                    'last_name' => 'Cybertech',
                    'email' => 'developer@cybertech.lk',                    
                    'username' => 'developer@cybertech.lk',
                    'password' =>'123456',
                    'confirmed'=>1
                ];
         $user_developer = Sentinel::registerAndActivate($credentials_developer);
         $user_developer->makeRoot();
         $role_developer= Sentinel::findRoleById(3);
         $role_developer->users()->attach($user_developer);

         /*SAMBOLE ADMIN CREATION*/
        $credentials_admin = [
                    'first_name' => 'Admin',
                    'last_name' => 'arcade',
                    'email' => 'admin@arcade.lk',                    
                    'username' => 'admin@arcade.lk',
                    'password' =>'123456',
                    'confirmed'=>1
                ];
         $user_admin = Sentinel::registerAndActivate($credentials_admin);
         $user_admin->makeChildOf($user_developer);
         $role_admin = Sentinel::findRoleById(4);
         $role_admin->users()->attach($user_admin);

          /*SAMBOLE AGENT CREATION*/
        $credentials_agent = [
                    'first_name' => 'Agent',
                    'last_name' => 'arcade',
                    'email' => 'agent@arcade.lk',                    
                    'username' => 'agent@arcade.lk',
                    'password' =>'123456',
                    'confirmed'=>1
                ];
         $user_agent = Sentinel::registerAndActivate($credentials_agent);
         $user_agent->makeChildOf($user_developer);
         $role_agent = Sentinel::findRoleById(6);
         $role_agent->users()->attach($user_agent);
    }
}
