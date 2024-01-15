<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class ChangeUserRole extends Command
{
    protected $signature = 'user:changeRole {email} {newRole}';

    protected $description = 'Change user role';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email');
        $newRole = $this->argument('newRole');
        $user = User::where('email', $email)->first();
        $role = Role::findByName($newRole);
        $user->syncRoles([$role]);
        $this->info("User role changed successfully");
    }
}
