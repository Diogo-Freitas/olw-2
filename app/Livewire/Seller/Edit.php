<?php

namespace App\Livewire\Seller;

use App\Models\Seller;
use Livewire\Component;
use App\Livewire\Forms\SellerForm;

class Edit extends Component
{
    public SellerForm $form;

    public function mount(Seller $seller)
    {
        $this->form->setSeller($seller);
    }

    public function render()
    {
        return view('livewire.seller.edit');
    }

    public function save()
    {
        $this->form->save();
    }
}
