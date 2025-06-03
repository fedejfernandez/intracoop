<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.admin.users.edit', [
            'userId' => $this->user->id,
        ])->layout('layouts.app');
    }
}
