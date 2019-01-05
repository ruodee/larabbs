<?php

namespace App\Observers;

use App\Models\User;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class UserObserver
{
    public function creating(User $user)
    {
        //
    }

    public function updating(User $user)
    {
        //
    }

   	public function saving(User $user)
   	{
   		$user->avatar ? : $user->avatar =  'https://iocaffcdn.phphub.org/uploads/avatars/76_1451276555.png!/both/200x200';
   	}
}