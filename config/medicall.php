<?php

return [
    'defaultThresholds' => [
        'severities' => [
            [
                'severity' => 'normal',
                'label' => 'Normale',
                'color' => 'bg-green-100 text-green-700',
            ],
            [
                'severity' => 'info',
                'label' => 'Contattare il medico',
                'color' => 'bg-gray-100 text-gray-700',
            ],
            [
                'severity' => 'warning',
                'label' => 'Attenzione',
                'color' => 'bg-yellow-100 text-yellow-700',
            ],
            [
                'severity' => 'critical',
                'label' => 'Critico',
                'color' => 'bg-red-100 text-red-700',
            ],
        ],
    ],

    'appointments' => [
        'statuses' => [
            [
                'status' => 'completed',
                'label' => 'Completato',
                'color' => 'bg-sky-100 text-sky-700',
            ],
            [
                'status' => 'no_show',
                'label' => 'n/d',
                'color' => 'bg-gray-100 text-gray-700',
            ],
            [
                'status' => 'confirmed',
                'label' => 'Confermato',
                'color' => 'bg-green-100 text-green-700',
            ],
            [
                'status' => 'pending',
                'label' => 'In attesa',
                'color' => 'bg-yellow-100 text-yellow-700',
            ],
            [
                'status' => 'cancelled',
                'label' => 'Cancellato',
                'color' => 'bg-red-100 text-red-700',
            ],
        ],
        'types' => [
            [
                'type' => 'video',
                'label' => 'Video',
                'color' => 'bg-sky-100 text-sky-700',
            ],
            [
                'type' => 'doctor_surgery',
                'label' => 'Studio',
                'color' => 'bg-gray-100 text-gray-700',
            ],
            [
                'type' => 'home',
                'label' => 'Domicilio',
                'color' => 'bg-green-100 text-green-700',
            ],
        ],
    ],
];
