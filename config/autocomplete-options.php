<?php

 return [

     'label' => [

         'error' => [
             'style' => 'text-red-600'
         ],

     ],

     'input' => [

         'error' => [

             'text' => [
                 'style' => 'mt-2 text-sm text-red-600'
             ],

             'style' => 'border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500',
         ],

         'container' => [
             'style' => 'flex flex-wrap flex-1 overflow-hidden relative box-border min-h-8'
         ],

         'item' => [

             'container' => [
                 'style' => 'bg-gray-600 h-6 inline-flex items-center text-sm rounded box-border mr-1 mt-1.5'
             ],

             'span' => [
                 'style' => 'ml-2 text-white leading-relaxed truncate max-w-xs'
             ],

             'removeButton' => [

                 'x-svg' => [
                     'style' => 'w-6 h-6 fill-current mx-auto'
                 ],

                 'style' => 'w-6 h-6 inline-block align-middle text-gray-500 hover:text-gray-100 focus:outline-none'

             ]
         ],

         'typeBox' => [

             'span' => [
                 'style' => 'text-black box-content outline-none h-auto py-2 p-0 opacity-100 overflow-visible text-sm text-light text-neutral flex items-center'
             ],

             'loadingSpinner' => [
                 'style' => 'ml-1 w-6 h-6'
             ],

             'style' => 'box-border inline-flex text-gray-500 items-center'
         ],

         'style' => 'flex form-multiselect items-center justify-start pl-1 py-0 text-base text-white tracking-wider font-semibold leading-6 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5'

     ],

     'results' => [

         'ul' => [
             'style' => 'max-h-60 rounded-md text-base leading-6 shadow-xs overflow-auto focus:outline-none sm:text-sm sm:leading-5'
         ],

         'item' => [

             'span' => [
                 'style' => 'font-normal block truncate'
             ],

             'highlighted' => [
                 'style' => 'text-white bg-gray-600'
             ],

             'notHighlighted' => [
                 'style' => 'text-gray-800'
             ],

             'style' => 'cursor-default select-none relative py-2 pl-3 pr-9 hover:text-white hover:bg-gray-700'
         ],

         'style' => 'absolute mt-1 w-full rounded-md bg-white shadow-lg z-10'
     ]

 ];