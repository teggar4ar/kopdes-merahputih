<?php

namespace App\Livewire;

use Livewire\Component;

class GlobalAlert extends Component
{
    public $alerts = [];

    protected $listeners = ['showAlert', 'dismissAlert'];

    public function showAlert($data)
    {
        $alertId = uniqid();

        $alert = [
            'id' => $alertId,
            'type' => $data['type'] ?? 'info',
            'title' => $data['title'] ?? '',
            'message' => $data['message'] ?? '',
            'duration' => $data['duration'] ?? 5000,
        ];

        $this->alerts[] = $alert;
    }

    public function dismissAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    public function render()
    {
        return view('livewire.global-alert');
    }
}
