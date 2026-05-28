<?php

namespace App\Livewire;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class SupplierManager extends Component
{
    use WithPagination;

    // Search & pagination
    public string $search = '';
    public int $perPage = 10;

    // Modal states
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $editMode = false;

    // Form fields
    public ?int $supplierId = null;
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';

    // Print mode
    public bool $printMode = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    // DATA
    public function getSuppliersProperty()
    {
        $query = Supplier::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })
            ->latest();

        return $this->printMode
            ? $query->get()
            : $query->paginate($this->perPage);
    }

    // PRINT
    public function printAll()
    {
        $this->printMode = true;
        $this->dispatch('trigger-print');
    }

    public function afterPrint()
    {
        $this->printMode = false;
    }

    // MODAL
    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $supplier = Supplier::findOrFail($id);

        $this->supplierId = $supplier->id;
        $this->name = $supplier->name;
        $this->email = $supplier->email;
        $this->phone = $supplier->phone;
        $this->address = $supplier->address;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->supplierId = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    // SAVE
    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => [
                'required',
                'email',
                Rule::unique('suppliers', 'email')->ignore($this->supplierId)
            ],
            'phone' => 'required',
            'address' => 'required',
        ]);

        Supplier::updateOrCreate(
            ['id' => $this->supplierId],
            [
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
            ]
        );

        session()->flash('success', 'Saved Successfully');

        $this->closeModal();
        $this->resetPage();
    }

    // DELETE
    public function delete()
    {
        if ($this->supplierId) {
            Supplier::findOrFail($this->supplierId)->delete();
            session()->flash('success', 'Deleted Successfully');
        }

        $this->closeModal();
        $this->resetPage();
    }

    // RESET
    private function resetForm()
    {
        $this->supplierId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->resetErrorBag();
    }

    public function render()
    {

        return view('livewire.supplier-manager', [
        'suppliers' => $this->getSuppliersProperty() 
    ]);
    }
}
