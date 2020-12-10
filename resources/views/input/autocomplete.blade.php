<div>

    <script>

        function autocomplete() {

            return {
                selected: @entangle('selected'),
                highlightedIndex: null,
                results: [],
                open: false,
                threshold: @entangle('threshold'),
                resultBoxHeight: 'auto',
                resultBoxHeightItemRange: @entangle('resultBoxHeight'),
                get searchTerm() {
                    return this.$refs.textInput.innerText
                },
                init() {
                    this.focusAfterAdding(this.$el.getAttribute('data-component-id'))
                    this.$watch('highlightedIndex', value => this.scrollToView(value))
                },
                clearSearch() {
                    this.$refs.textInput.innerHTML = ''
                    this.highlightedIndex = null;
                    this.toggleSearch()
                },
                toggleSearch() {
                    this.open = this.searchTerm !== '' && this.isTermLongEnough(this.searchTerm)
                },
                backspace(event) {

                    const uuid = this.$el.getAttribute('data-last-item-uuid')

                    if (uuid && uuid !== 'null') {
                        this.$wire.removeItem(uuid)
                    }

                },
                enter: function (event) {

                    this.stopEvent(event)

                    let uuid, name

                    if (this.highlightedIndex !== null) {
                        uuid = this.results[this.highlightedIndex].uuid
                        name = uuid !== '__add-this__' ? this.results[this.highlightedIndex].name : this.searchTerm
                    } else {
                        uuid = JSON.stringify(event.target.getAttribute('data-uuid'))
                        name = event.target.innerText
                    }

                    if (!this.isTermLongEnough(name)) {
                        this.clearSearch()
                        return
                    }

                    if (uuid !== 'null' && name) {
                        this.$wire.addItem(name, uuid)
                    } else if (name) {
                        this.$wire.addItem(name)
                    }

                    this.clearSearch()

                },
                keydown(event, limit) {

                    if (limit !== null && event.code !== 'Tab' && limit <= this.selected.length) {
                        this.stopEvent(event)
                    }

                    if (event.code === 'Enter') {
                        return this.enter(event)
                    }

                    if (event.code === 'Backspace') {
                        return this.backspace(event)
                    }

                },
                focusAfterAdding(id) {

                    Livewire.hook('message.processed', (message, component) => {

                        if (message.updateQueue[0].method === 'addItem' && component.id === id) {
                            this.$refs.textInput.focus()
                        }

                        if (message.updateQueue[0].method === 'query' && component.id === id) {
                            this.$refs.textInput.focus()
                        }
                    })
                },
                async searchForTerm(term) {

                    const termExists = this.$wire.selected.some(item => item.name.trim() === term, term)

                    if (this.isTermLongEnough(term)) {
                        this.results = await this.$wire.query(term)

                        // if there isn't a fitting result
                        if (this.results.filter(tag => tag.name.trim() === term).length === 0) {

                            if (!termExists) {
                                this.prependAddThis(term)
                            } else {
                                this.open = false
                            }
                        }

                        this.results.length && !termExists && this.toggleSearch()
                        this.$nextTick(() => this.setResultBoxHeight())
                    }
                },
                prependAddThis(name) {
                    !this.results.filter(tag => tag.uuid === '__add-this__').length && this.results.unshift({
                        'uuid': '__add-this__',
                        'name': name
                    })
                },
                isTermLongEnough(term) {
                    return term.length >= this.threshold
                },
                stopEvent(event) {
                    event.preventDefault() && event.stopPropagation()
                },
                moveCursorToEnd() {

                    const node = this.$refs.textInput, textNode = node.childNodes[0]

                    if (textNode !== undefined) {
                        const caret = textNode.length,
                            range = document.createRange(),
                            sel = window.getSelection();

                        node.focus();

                        range.setStart(textNode, caret);
                        range.setEnd(textNode, caret);

                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                },
                onArrowUp() {

                    if (this.highlightedIndex === null && this.results.length) {
                        this.highlightedIndex = this.results.length - 1;
                    } else if (this.highlightedIndex === 0 && this.results.length === 1) {
                        this.highlightedIndex = null;
                    } else {
                        if (this.results.length) {
                            this.highlightedIndex === 0 ? this.highlightedIndex = this.results.length - 1 : this.highlightedIndex--;
                        }
                    }
                },
                onArrowDown() {
                    if (this.highlightedIndex === null && this.results.length) {
                        this.highlightedIndex = 0;
                    } else {
                        if (this.results.length) {
                            this.highlightedIndex < (this.results.length - 1) ? this.highlightedIndex++ : this.highlightedIndex = 0;
                        }
                    }
                },
                addTagByUuid(event) {
                    const element = event.target
                    const tagsEl = this.results[element.getAttribute('data-span-list-key')];

                    const uuid = tagsEl.uuid
                    const name = this.results[element.getAttribute('data-span-list-key')].name

                    if (uuid && name) {
                        this.$wire.addItem(name, uuid)
                    }

                    this.clearSearch()
                },
                setResultBoxHeight() {
                    const items = this.$refs.resultList.getElementsByTagName('LI')
                    const totalItems = items.length
                    let result = 0

                    if (totalItems > this.resultBoxHeightItemRange) {
                        for (i = 0, tags = items; i < this.resultBoxHeightItemRange; i++) {
                            result += tags.item(i).offsetHeight
                        }
                    }

                    this.resultBoxHeight = result ? result + 'px' : 'auto'
                },
                scrollToView(index) {

                    const activeElement = this.$refs.resultList.querySelectorAll(`[data-list-key="${index}"]`)

                    if (activeElement.length) {
                        activeElement[0].scrollIntoView({
                            block: 'nearest',
                            inline: 'start',
                            behavior: 'smooth',
                            boundary: this.$el
                        });
                    }
                }
            }
        }

    </script>

    <div x-data="autocomplete()" x-init="init()"
         @if ($class) class="{{$class}}" @endif
         x-on:keydown.arrow-up.prevent="onArrowUp()"
         x-on:keydown.arrow-down.prevent="onArrowDown()"
         data-last-item-uuid="{{$this->selected->last() ? $this->selected->last()['uuid'] : null }}"
         data-component-id="{{$this->id}}"
    >
        <x-sunfire::input.base-input-container :labelClasses="$errors->first('selected') ? 'text-red-600' : '' "
                                               :label="$label"
                                               :id="$this->id . '_input'" :inline="$inline">

            <div x-on:click="$refs.textInput ? $refs.textInput.focus() : null"
                 x-on:click.away="clearSearch()"
                 x-on:keydown.escape="clearSearch()"
                 class="@error('selected') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror flex form-multiselect items-center justify-start pl-1 py-0 text-base text-white tracking-wider font-semibold leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">

                <div class="flex flex-wrap flex-1 overflow-hidden relative box-border min-h-8">

                    @foreach($selected as $item)
                        <div class="bg-gray-600 h-6 inline-flex items-center text-sm rounded box-border mr-1"
                             style="margin-top: 0.35rem"
                             wire:key="{{ $item['uuid'] }}">

                            <span class="ml-2 text-white leading-relaxed truncate max-w-xs">{{$item['name']}}</span>

                            <button wire:click.prevent="removeItem('{{$item['uuid']}}')"
                                    class="w-6 h-6 inline-block align-middle text-gray-500 hover:text-gray-100 focus:outline-none">
                                <x-svg.x class="w-6 h-6 fill-current mx-auto"/>
                            </button>
                        </div>
                    @endforeach


                    <div class="box-border inline-flex">
                        <span x-ref="textInput"
                              x-on:focus="moveCursorToEnd()"
                              contenteditable
                              x-on:keydown="keydown($event, $wire.limit)"
                              x-on:input.debounce="searchForTerm($event.target.innerText)"
                              wire:ignore
                              id="{{$this->id}}_textInput"
                              class="text-black box-content outline-none h-auto py-2 p-0 opacity-100 overflow-visible text-sm text-light text-neutral flex items-center"
                              autocapitalize="none"
                              spellcheck="false"
                              tabindex="0"
                              aria-autocomplete="list"></span>
                    </div>
                </div>
            </div>

            @error('selected') <p class="mt-2 text-sm text-red-600"
                                  id="{{$this->name}}-error">{{$message}}</p> @enderror

            <div x-show="open" class="absolute mt-1 w-full rounded-md bg-white shadow-lg z-10">
                <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label"
                    aria-activedescendant="listbox-item-3"
                    x-ref="resultList"
                    x-bind:style="`max-height: ${resultBoxHeight}`"
                    class="max-h-60 rounded-md text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5">

                    <template x-for="(item, index) in Object.values(results)" :key="index">

                        <li :id="'listbox-option-' + item.uuid" role="option"
                            x-on:click="addTagByUuid($event)"
                            :data-list-key="index"
                            :class="{'text-white bg-gray-600': highlightedIndex === index, 'text-gray-800': highlightedIndex !== index }"
                            class="cursor-default select-none relative py-2 pl-3 pr-9 hover:text-white hover:bg-gray-700">
                            <span class="font-normal block truncate"
                                  :class="{'font-semibold': highlightedIndex === index, 'font-normal': highlightedIndex !== index}"
                                  x-text="item.uuid !== '__add-this__' ? item.name : '{{__('Add')}} '+ item.name"
                                  :data-uuid="item.uuid"
                                  :data-span-list-key="index"></span>
                            </span>
                        </li>
                    </template>

                </ul>
            </div>
        </x-sunfire::input.base-input-container>
    </div>
</div>
