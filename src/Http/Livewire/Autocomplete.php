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
    public $inline = true;
    public $class;
    public $limit;
    public $name;

    public $params;
    public $threshold = 2;
    public $resultBoxHeight = 4;
    public $errorEvent = null;

    abstract public function query(string $term);

    protected $listeners = ['valueChanged'];

    public function mount($limit = null)
    {
        $this->results = collect();
        $this->selected = collect();
        $this->limit = !$limit ? null : intval($limit);
    }

    public function updatedSelected()
    {
        $this->emitSelf('valueChanged', $this->selected);
    }

    public function valueChanged($data)
    {
        $this->validateOnly('selected');
        $this->emitUp(strtolower($this->name) . 'Changed', $data);
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

        if ($this->itemShouldBeAdded($uuid)) {
            $uuid = Str::uuid()->toString();
        }

        if ($this->itemIsNotSelectedYet($uuid)) {
            $this->selected = $this->selected->push($this->makeItem([
                'uuid' => $uuid,
                'name' => $string
            ]));
        }

        $this->updatedSelected();
        return true;
    }


    public function makeItem(array $attributes): array
    {
        return $attributes;
    }

    protected function itemIsNotSelectedYet($uuid)
    {
        return $this->selected->filter(fn($item) => $item['uuid'] === $uuid)->isEmpty();
    }

    protected function itemShouldBeAdded($uuid): bool
    {
        return !$uuid || $uuid === '__add-this__';
    }

    public function dehydrate()
    {
        $error = $this->getErrorBag()->first('selected');

        if (!$error) {
            return;
        }

        if ($this->errorEvent) {
            $this->emitUp($this->errorEvent, $this->name,  $error);
            return;
        }

        $this->emitUp($this->name . 'HasError', $error);
    }


    public function render()
    {
        return view('sunfire::input.autocomplete');
    }
}

