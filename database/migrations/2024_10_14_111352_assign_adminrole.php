<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;
use Spatie\Permission\Models\Role;

class AssignAdminrole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //



// Start the database transaction
DB::transaction(function () {
    // Find the user with username 'admin'
    $user = User::where('username', 'admin')->first();
    
    if ($user) {
        // Check if the 'admin' role exists
        $role = Role::where('name', 'admin')->first();
        
        if ($role) {
            // Assign the 'admin' role to the user
            $user->assignRole('admin');
        } else {
            // Create the 'admin' role if it doesn't exist and assign it
            $role = Role::create(['name' => 'admin']);
            $user->assignRole($role);
        }
    } else {
        // Optional: Handle case where no user is found with the 'admin' username
        throw new \Exception("User with username 'admin' not found.");
    }
});









    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
