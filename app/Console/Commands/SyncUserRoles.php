<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize static text roles to Spatie Permission roles for all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting role synchronization...');

        // 1. Ensure required roles exist in central database
        $defaultRoles = ['superadmin', 'admin_sekolah', 'operator'];
        foreach ($defaultRoles as $roleName) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $this->info("Ensured role exists: {$roleName}");
        }

        // 2. Loop through all existing users and assign corresponding Spatie Role
        $users = \App\Models\User::all();
        $syncedCount = 0;

        foreach ($users as $user) {
            if (!empty($user->role)) {
                // If the user's role string matches one of our Spatie roles
                $roleExists = \Spatie\Permission\Models\Role::where('name', $user->role)->exists();
                
                if ($roleExists) {
                    $user->assignRole($user->role);
                    $syncedCount++;
                } else {
                    $this->warn("User {$user->email} has unknown role: {$user->role}");
                }
            }
        }

        $this->info("Successfully synchronized roles for {$syncedCount} users!");
    }
}
