<div>

    <script>

        function autocomplete() {

            return {
                selected: @entangle('selected'),
                highlightedIndex: null,
                results: [],
                open: false,
                waiting: false,
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
                toggleWaiting() {
                    this.waiting = !this.waiting;
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

                        this.results = await this.handleWaiting(this.$wire.query(term), 500);

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
                },
                handleWaiting(promise, timeout) {

                    const timeoutInstance = setTimeout(() => {
                        this.waiting = true;
                    }, timeout);

                    return promise.finally(() => {
                        this.waiting = false;
                        clearTimeout(timeoutInstance);
                    });

                },
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
        <x-sunfire::input.base-input-container
                :labelClasses="$errors->first('selected') ? $this->getOption('label.error.style') : '' "
                :label="$label"
                :id="$this->id . '_input'" :inline="$inline">

            <div x-on:click="$refs.textInput ? $refs.textInput.focus() : null"
                 x-on:click.away="clearSearch()"
                 x-on:keydown.escape="clearSearch()"
                 class="@error('selected') {{ $this->getOption('input.error.style') }} @enderror {{$this->getOption('input.style')}} ">

                <div class="{{$this->getOption('input.container.style')}}">

                    @foreach($selected as $item)
                        <div class="{{$this->getOption('input.item.container.style')}}"
                             wire:key="{{ $item['uuid'] }}">

                            <span class="{{$this->getOption('input.item.span.style')}}">{{$item['name']}}</span>

                            <button wire:click.prevent="removeItem('{{$item['uuid']}}')"
                                    class="{{$this->getOption('input.item.removeButton.style')}}">
                                <x-svg.x class="{{$this->getOption('input.item.removeButton.x-svg.style')}}"/>
                            </button>
                        </div>
                    @endforeach


                    <div class="{{$this->getOption('input.typeBox.style')}}">
                        <span x-ref="textInput"
                              x-on:focus="moveCursorToEnd()"
                              contenteditable
                              x-on:keydown="keydown($event, $wire.limit)"
                              x-on:input.debounce="searchForTerm($event.target.innerText)"
                              wire:ignore
                              id="{{$this->id}}_textInput"
                              class="{{$this->getOption('input.typeBox.span.style')}}"
                              autocapitalize="none"
                              spellcheck="false"
                              tabindex="0"
                              aria-autocomplete="list"></span>
                        <x-sunfire::svg.spinner x-cloak x-show="waiting"
                                                class="{{$this->getOption('input.typeBox.loadingSpinner.style')}}"/>
                    </div>
                </div>
            </div>

            @error('selected') <p class="{{$this->getOption('input.error.text.style')}}"
                                  id="{{$this->name}}-error">{{$message}}</p> @enderror

            <div x-show="open" class="{{$this->getOption('results.style')}}">
                <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label"
                    aria-activedescendant="listbox-item-3"
                    x-ref="resultList"
                    x-bind:style="`max-height: ${resultBoxHeight}`"
                    class="{{$this->getOption('results.ul.style')}}">

                    <template x-for="(item, index) in Object.values(results)" :key="index">

                        <li :id="'listbox-option-' + item.uuid" role="option"
                            x-on:click="addTagByUuid($event)"
                            :data-list-key="index"
                            :class="{ '{{$this->getOption('results.item.highlighted.style')}}' : highlightedIndex === index, '{{$this->getOption('results.item.notHighlighted.style')}}': highlightedIndex !== index }"
                            class="{{$this->getOption('results.item.style')}}">
                            <span class="{{$this->getOption('results.item.span.style')}}"
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
