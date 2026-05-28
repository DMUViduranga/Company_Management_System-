<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Contact;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    // Search & filter
    public string $search      = '';
    public string $categoryFilter = '';
    public int    $perPage     = 10;

    // Modal states
    public bool $showModal       = false;
    public bool $showDeleteModal = false;
    public bool $editMode        = false;

    // Form fields
    public ?int   $contactId  = null;
    public int|string $categoryId = '';
    public string $name       = '';
    public string $email      = '';
    public string $phone      = '';

    public function updatingSearch()        { $this->resetPage(); }
    public function updatingCategoryFilter() { $this->resetPage(); }
    public function updatingPerPage()       { $this->resetPage(); }

    //  DATA 
    public function getContactsProperty()
    {
        return Contact::with('category')
            ->when($this->search, fn($q) =>
                $q->where(fn($sub) =>
                    $sub->where('name',  'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%")
                )
            )
            ->when($this->categoryFilter, fn($q) =>
                $q->where('category_id', $this->categoryFilter)
            )
            ->latest()
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    //  MODALS 
    public function openCreateModal()
    {
        $this->resetForm();
        $this->editMode  = false;
        $this->showModal = true;
    }

    public function openEditModal(int $id)
    {
        $contact = Contact::findOrFail($id);

        $this->contactId  = $contact->id;
        $this->categoryId = $contact->category_id;
        $this->name       = $contact->name;
        $this->email      = $contact->email;
        $this->phone      = $contact->phone;

        $this->editMode  = true;
        $this->showModal = true;
    }

    public function confirmDelete(int $id)
    {
        $this->contactId      = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showModal       = false;
        $this->showDeleteModal = false;
        $this->resetForm();
    }

    // SAVE 
    public function save()
    {
        $this->validate([
            'categoryId' => 'required|exists:categories,id',
            'name'       => 'required|min:3',
            'email'      => [
                'required',
                'email',
                Rule::unique('contacts', 'email')->ignore($this->contactId),
            ],
            'phone' => 'required',
        ]);

        Contact::updateOrCreate(
            ['id' => $this->contactId],
            [
                'category_id' => $this->categoryId,
                'name'        => $this->name,
                'email'       => $this->email,
                'phone'       => $this->phone,
            ]
        );

        session()->flash('success', 'Contact saved successfully.');
        $this->closeModal();
        $this->resetPage();
    }

    // DELETE 
    public function delete()
    {
        if ($this->contactId) {
            Contact::findOrFail($this->contactId)->delete();
            session()->flash('success', 'Contact deleted successfully.');
        }
        $this->closeModal();
        $this->resetPage();
    }

    // RESET 
    private function resetForm()
    {
        $this->contactId  = null;
        $this->categoryId = '';
        $this->name       = '';
        $this->email      = '';
        $this->phone      = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.contact-manager', [
            'contacts'   => $this->getContactsProperty(),
            'categories' => $this->getCategoriesProperty(),
        ]);
    }
}