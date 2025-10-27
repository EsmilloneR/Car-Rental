<?php

namespace App\Livewire\Browse;

use App\Models\Manufacturer;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

class ListCars extends Component
{
    use WithPagination;

    public $selected_slug = [];

    protected $paginationTheme = 'tailwind';

    public function updatingSelectedSlug()
    {
        $this->resetPage();
    }


    public function updatedSelectedSlug()
    {
        $this->resetPage();
    }

    public function toggleBrand($id): void
    {
        if (in_array($id, $this->selected_slug)) {
            $this->selected_slug = array_values(array_diff($this->selected_slug, [$id]));
        } else {
            $this->selected_slug[] = $id;
        }

        $this->resetPage();
    }

    public function render()
    {
        // 5. Dapat makita giyapon sa homepage ang na rentahan na nga sakynan pero naay nakabutang nga "reserved" sa mga reserved na
        $listCars = Vehicle::query()
            ->with(['rentals' => fn($q) =>
                $q->whereIn('status', ['reserved', 'ongoing', 'completed'])
            ])
            ->where('active', true);


        if (!empty($this->selected_slug)) {
            $listCars->whereIn('manufacturer_id', $this->selected_slug);
        }

        return view('livewire.browse.list-cars', [
            'listCars' => $listCars->paginate(6),
            'slugs' => Manufacturer::select('id', 'slug', 'brand')->get(),
        ]);
    }
}
