<?php

namespace Sunfire\Form\Http\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;

abstract class Autocomplete extends Component
{
    public $results;

    /** @var $selected Collection */
    public $selected;

    public $label;
    public $inline;
    public $class;
    public $limit;
    public $name;

    public $params;
    public $threshold = 2;
    public $resultBoxHeight = 4;

    abstract public function query(string $term);
    protected $listeners = ['valueChanged'];

    public function mount($limit = null)
    {
        $this->results = collect();
        $this->selected = collect();
        $this->inline = true;
        $this->limit = !$limit ? null : intval($limit);
    }

    public function updatedSelected()
    {
        $this->emitSelf('valueChanged', $this->selected);
    }

    public function valueChanged($data)
    {
        $this->emitUp(strtolower($this->name). 'Changed', $data);
    }


    public function removeItem(string $uuid)
    {
        if (!$this->selected->where('uuid', $uuid)) {
            return null;
        }

        $this->selected = $this->selected->filter(fn($item) => $item['uuid'] !== $uuid);
        $this->updatedSelected();
    }

    public function addItem(string $string, ?string $uuid = null)
    {
        $string = trim($string);

        if (!$string) {
            return false;
        }

        if (!$uuid || $uuid === '__add-this__') {
            $uuid = Str::uuid()->toString();
        }

        if ($this->selected->filter(fn($item) => $item['name'] === $string)->isEmpty()) {
            $this->selected = $this->selected->push($this->makeItem($uuid, $string));
        }

        $this->updatedSelected();
        return true;
    }

    public function render()
    {
        return view('sunfire::input.autocomplete');
    }

    public function makeItem(string $uuid, string $name)
    {
        return [
            'uuid' => $uuid,
            'name' => $name
        ];
    }
}

