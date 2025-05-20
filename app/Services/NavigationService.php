<?php

namespace App\Services;

class NavigationService
{
    /**
     * Get the frontend navigation items
     *
     * @return array
     */
    public function getFrontendNavigation(): array
    {
        return [
            [
                'label' => 'Home',
                'url' => '/',
                'active' => request()->is('/'),
                'scroll_to' => 'kt_body'
            ],
            [
                'label' => 'Services',
                'url' => '#services',
                'active' => false,
                'scroll_to' => 'services'
            ],
            [
                'label' => 'Pricing',
                'url' => '#pricing',
                'active' => false,
                'scroll_to' => 'pricing'
            ],
            [
                'label' => 'Contact Us',
                'url' => '#contact',
                'active' => false,
                'scroll_to' => 'contact'
            ],
        ];
    }

    /**
     * Get the authentication navigation items
     *
     * @return array
     */
    public function getAuthNavigation(): array
    {
        return [
            [
                'label' => 'Sign In',
                'url' => '/login',
                'class' => 'btn btn-success me-2'
            ],
            [
                'label' => 'Register',
                'url' => '/register',
                'class' => 'btn btn-primary'
            ]
        ];
    }

    /**
     * Get the backend dashboard navigation items
     *
     * @return array
     */
    public function getBackendNavigation(): array
    {
        // Begin with Super Admin links if user has that role
        $navigation = [];
        if (auth()->user()->getRoleNames()->first() === 'super-admin') {
            $navigation[] = [
                'label' => 'Manage Users',
                'icon' => 'fas fa-users',
                'url' => '/admin/users',
                'active' => request()->is('admin/users*')
            ];
            $navigation[] = [
                'label' => 'Approve Sender Names',
                'icon' => 'fas fa-user-tag',
                'url' => '/admin/sender-names',
                'active' => request()->is('admin/sender-names*')
            ];
        }
        return array_merge(
            $navigation,
            [
                [
                    'label' => 'Dashboard',
                    'icon' => 'ki-outline ki-home',
                    'url' => '/dashboard',
                    'active' => request()->is('dashboard')
                ],
                [
                    'label' => 'SMS',
                    'icon' => 'ki-outline ki-message-text-2',
                    'url' => '/sms',
                    'active' => request()->is('sms*'),
                    'children' => [
                        [
                            'label' => 'Compose',
                            'url' => '/sms/compose',
                            'active' => request()->is('sms/compose*')
                        ],
                        [
                            'label' => 'Campaigns',
                            'url' => '/sms/campaigns',
                            'active' => request()->is('sms/campaigns*')
                        ],
                        [
                            'label' => 'Messages',
                            'url' => '/sms/messages',
                            'active' => request()->is('sms/messages*')
                        ],
                        [
                            'label' => 'Templates',
                            'url' => '/sms/templates',
                            'active' => request()->is('sms/templates*')
                        ],
                        [
                            'label' => 'Sender Names',
                            'url' => '/sms/sender-names',
                            'active' => request()->is('sms/sender-names*')
                        ]
                    ]
                ],
                [
                    'label' => 'Contacts',
                    'icon' => 'ki-outline ki-address-book',
                    'url' => '/contacts',
                    'active' => request()->is('contacts*') || request()->is('contact-groups*'),
                    'children' => [
                        [
                            'label' => 'Manage Contacts',
                            'url' => '/contacts',
                            'active' => request()->is('contacts') || request()->is('contacts/create') || request()->is('contacts/*/edit')
                        ],
                        [
                            'label' => 'Contact Groups',
                            'url' => '/contact-groups',
                            'active' => request()->is('contact-groups*')
                        ],
                        [
                            'label' => 'Import Contacts',
                            'url' => '/contacts-import',
                            'active' => request()->is('contacts-import*')
                        ]
                    ]
                ],
                // [
                //     'label' => 'Contact Center',
                //     'icon' => 'ki-outline ki-call',
                //     'url' => '/contact-center',
                //     'active' => request()->is('contact-center*'),
                //     'children' => [
                //         [
                //             'label' => 'Call History',
                //             'url' => '/contact-center/calls',
                //             'active' => request()->is('contact-center/calls*')
                //         ],
                //         [
                //             'label' => 'Virtual Numbers',
                //             'url' => '/contact-center/virtual-numbers',
                //             'active' => request()->is('contact-center/virtual-numbers*')
                //         ],
                //         [
                //             'label' => 'Call Settings',
                //             'url' => '/contact-center/settings',
                //             'active' => request()->is('contact-center/settings*')
                //         ]
                //     ]
                // ],
                [
                    'label' => 'USSD',
                    'icon' => 'ki-outline ki-phone',
                    'url' => '/ussd',
                    'active' => request()->is('ussd*'),
                    'children' => [
                        [
                            'label' => 'Services',
                            'url' => '/ussd/services',
                            'active' => request()->is('ussd/services*')
                        ],
                        [
                            'label' => 'Analytics',
                            'url' => '/ussd/analytics',
                            'active' => request()->is('ussd/analytics*')
                        ]
                    ]
                ],
                // [
                //     'label' => 'Virtual Numbers',
                //     'icon' => 'ki-outline ki-call',
                //     'url' => '/virtual-numbers',
                //     'active' => request()->is('virtual-numbers*')
                // ],
                [
                    'label' => 'Settings',
                    'icon' => 'ki-outline ki-setting-2',
                    'url' => '/settings/currency',
                    'active' => request()->is('settings*')
                ]
            ]
        );
    }

    /**
     * Get the footer navigation items
     *
     * @return array
     */
    public function getFooterNavigation(): array
    {
        return [
            [
                'label' => 'Privacy Policy',
                'url' => '/privacy-policy',
                'class' => 'menu-link px-2'
            ],
            [
                'label' => 'Terms of Use',
                'url' => '/terms-of-use',
                'class' => 'menu-link px-2'
            ],
            [
                'label' => 'Support',
                'url' => '/support',
                'class' => 'menu-link px-2'
            ]
        ];
    }
}