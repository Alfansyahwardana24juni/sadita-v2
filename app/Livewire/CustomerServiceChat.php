<?php

namespace App\Livewire;

use App\Models\CustomerServiceCategory;
use App\Models\CustomerService;
use Livewire\Component;

class CustomerServiceChat extends Component
{
    public $categories;
    public $activeCategoryId = null;

    public function mount()
    {
        $this->categories = CustomerServiceCategory::where('is_active', true)->orderBy('sort_order')->get();
        if ($this->categories->isNotEmpty()) {
            $this->activeCategoryId = $this->categories->first()->id;
        }
    }

    public function setCategory($id)
    {
        $this->activeCategoryId = $id;
    }

    public function getAgentsProperty()
    {
        if (!$this->activeCategoryId) return collect();

        return CustomerService::where('customer_service_category_id', $this->activeCategoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function render()
    {
        return view('livewire.customer-service-chat');
    }
}
