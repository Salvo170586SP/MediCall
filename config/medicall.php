<?php

return [
    'defaultThresholds' => [
        'severities' => [
            [
                'severity' => 'normal',
                'label' => 'Normale',
                'color' => 'bg-green-100 text-green-700 border border-green-400'
            ],
            [
                'severity' => 'info',
                'label' => 'Contattare il medico',
                'color' => 'bg-gray-100 text-gray-700 border border-gray-400'
            ],
            [
                'severity' => 'warning',
                'label' => 'Attenzione',
                'color' => 'bg-yellow-100 text-yellow-700 border border-yellow-400'
            ],
            [
                'severity' => 'critical',
                'label' => 'Critico',
                'color' => 'bg-red-100 text-red-700 border border-red-400'
            ],
        ],
    ],
   
    'appointments' => [
        'statuses' => [
            [
                'status' => 'completed',
                'label' => 'Completato',
                'color' => 'bg-sky-100 text-sky-700 border border-sky-400'
            ],
            [
                'status' => 'no_show',
                'label' => 'n/d',
                'color' => 'bg-gray-100 text-gray-700 border border-gray-400'
            ],
            [
                'status' => 'confirmed',
                'label' => 'Confermato',
                'color' => 'bg-green-100 text-green-700 border border-green-400'
            ],
            [
                'status' => 'pending',
                'label' => 'In attesa',
                'color' => 'bg-yellow-100 text-yellow-700 border border-yellow-400'
            ],
            [
                'status' => 'cancelled',
                'label' => 'Cancellato',
                'color' => 'bg-red-100 text-red-700 border border-red-400'
            ],
        ],
        'types' => [
            [
                'type' => 'video',
                'label' => 'Video',
                'color' => 'bg-sky-100 text-sky-700 border border-sky-400'
            ],
            [
                'type' => 'doctor_surgery',
                'label' => 'Studio',
                'color' => 'bg-gray-100 text-gray-700 border border-gray-400'
            ],
            [
                'type' => 'home',
                'label' => 'Domicilio',
                'color' => 'bg-green-100 text-green-700 border border-green-400'
            ] 
        ],
    ],

];