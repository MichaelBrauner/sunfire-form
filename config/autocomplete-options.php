<?php

return [

    'label' => [

        'error' => [
            'style' => [
                'text' => 'text-red-600'
            ]
        ],

    ],

    'input' => [

        'error' => [

            'text' => [
                'style' => [
                    'text' => 'text-sm text-red-600',
                    'spacing' => 'mt-2'
                ]
            ],

            'style' => [
                'border' => 'border-red-300 focus:border-red-500',
                'text' => 'text-red-900',
                'placeholder' => 'placeholder-red-300',
                'outline' => 'focus:outline-none',
                'ring' => 'focus:ring-red-500'
            ]

        ],

        'container' => [
            'style' => [
                'display' => 'flex flex-wrap flex-1',
                'overflow' => 'overflow-hidden ',
                'position' => 'relative',
                'border' => 'box-border',
                'sizing' => 'min-h-8'
            ]

        ],

        'item' => [

            'container' => [
                'style' => [
                    'background' => 'bg-gray-600',
                    'sizing' => 'h-6',
                    'display' => 'inline-flex items-center',
                    'text' => 'text-sm',
                    'border' => 'rounded box-border',
                    'spacing' => 'mr-1 mt-1.5'
                ]
            ],

            'span' => [
                'style' =>
                    [
                        'spacing' => 'ml-2 truncate',
                        'text' => 'text-white leading-relaxed',
                        'sizing' => 'max-w-xs'
                    ]
            ],

            'removeButton' => [

                'x-svg' => [
                    'style' => [
                        'sizing' => 'w-6 h-6',
                        'color' => 'fill-current',
                        'spacing' => 'mx-auto'
                    ]
                ],

                'style' => [
                    'sizing' => 'w-6 h-6',
                    'display' => 'inline-block align-middle',
                    'text' => 'text-gray-500 hover:text-gray-100',
                    'outline' => 'focus:outline-none'
                ]

            ]
        ],

        'typeBox' => [

            'span' => [
                'style' => [
                    'text' => 'text-black text-sm text-light text-neutral',
                    'box' => 'box-content',
                    'outline' => 'outline-none',
                    'sizing' => 'h-auto',
                    'spacing' => 'py-2 p-0',
                    'opacity' => 'opacity-100',
                    'overflow' => 'overflow-visible',
                    'display' => 'flex items-center'
                ]
            ],

            'loadingSpinner' => [
                'style' => [
                    'spacing' => 'ml-1',
                    'sizing' => 'w-6 h-6'
                ]
            ],

            'style' => [
                'box' => 'box-border',
                'text' => 'inline-flex text-gray-500'
            ]
        ],

        'style' => [
            'text' => 'text-base text-white tracking-wider font-semibold leading-6 sm:text-sm sm:leading-5',
            'box' => 'box-content',
            'outline' => 'focus:outline-none focus:shadow-outline-blue',
            'spacing' => 'pl-1 py-0',
            'display' => 'flex items-center justify-start',
            'extra' => 'form-multiselect',
            'border' => 'focus:border-blue-300',
        ]

    ],

    'results' => [

        'ul' => [
            'style' => [
                'sizing' => 'max-h-60',
                'border' => 'rounded-md',
                'text' => 'text-base leading-6 sm:text-sm sm:leading-5',
                'shadow' => 'shadow-xs',
                'overflow' => 'overflow-auto',
                'outline' => 'focus:outline-none',
            ]
        ],

        'item' => [

            'span' => [
                'style' => [
                    'text' => 'font-normal truncate',
                    'display' => 'block'
                ]
            ],

            'highlighted' => [
                'style' => ['text' => 'text-white', 'background' => 'bg-gray-600']
            ],

            'notHighlighted' => [
                'style' => ['text' => 'text-gray-800']
            ],

            'style' => [
                'cursor' => 'cursor-default',
                'select' => 'select-none',
                'position' => 'relative',
                'spacing' => 'py-2 pl-3 pr-9',
                'text' => 'hover:text-white',
                'background' => 'hover:bg-gray-700'
            ]
        ],

        'style' => [
            'position' => 'absolute',
            'spacing' => 'mt-1',
            'sizing' => 'w-full',
            'border' => 'rounded-md',
            'background' => 'bg-white',
            'shadow' => 'shadow-lg',
            'z-index' => 'z-10'
        ]
    ]

];