<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Carbon; 

class UserAccessTimeManager extends Component
{
    public $users;
    public $userId;
    public $access_start_time;
    public $access_end_time;
    public $showModal = false;
    

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function openEditModal($id)
    {
        $user = User::findOrFail($id);

        $this->userId = $user->id;
        
       
        $this->access_start_time = $user->allowed_from ? Carbon::parse($user->allowed_from)->format('H:i') : null;
        $this->access_end_time = $user->allowed_to ? Carbon::parse($user->allowed_to)->format('H:i') : null;

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'access_start_time' => 'required',
            'access_end_time' => 'required',
        ]);

        User::findOrFail($this->userId)->update([
            'allowed_from' => $this->access_start_time,
            'allowed_to' => $this->access_end_time,
        ]);

        session()->flash('success', 'Access time updated.');

        $this->showModal = false;
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.user-access-time-manager');
    }
}