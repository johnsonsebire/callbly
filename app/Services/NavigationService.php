<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
     * Get the backend navigation items.
     *
     * @return array
     */
    public function getBackendNavigation(): array
    {
        $user = Auth::user();
        $currentRouteName = Route::currentRouteName();
        
        // If user is null, return a minimal navigation
        if (!$user) {
            return [
                [
                    'label' => 'Home',
                    'icon' => 'fas fa-home',
                    'url' => '/',
                    'active' => false,
                    'order' => 1
                ]
            ];
        }
        
        $navigation = [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-home',
                'url' => route('dashboard'),
                'active' => $this->checkRoutePattern($currentRouteName, 'dashboard'),
                'order' => 1
            ],
            // Teams Navigation Item
            [
                'label' => 'Teams',
                'icon' => 'fas fa-users',
                'active' => $this->checkRoutePattern($currentRouteName, ['teams.*', 'team-invitations.*']),
                'order' => 2,
                'children' => [
                    [
                        'label' => 'My Teams',
                        'url' => route('teams.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, [
                            'teams.index',
                            'teams.show',
                            'teams.edit'
                        ])
                    ],
                    [
                        'label' => 'Create Team',
                        'url' => route('teams.create'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'teams.create')
                    ]
                ]
            ],
            [
                'label' => 'SMS',
                'icon' => 'fas fa-sms',
                'active' => $this->checkRoutePattern($currentRouteName, 'sms.*'),
                'order' => 3,
                'children' => [
                    [
                        'label' => 'Dashboard',
                        'url' => route('sms.dashboard'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'sms.dashboard')
                    ],
                    [
                        'label' => 'Compose',
                        'url' => route('sms.compose'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'sms.compose')
                    ],
                    [
                        'label' => 'Campaigns',
                        'url' => route('sms.campaigns'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['sms.campaigns', 'sms.campaign-details', 'sms.download-report', 'sms.duplicate-campaign'])
                    ],
                    [
                        'label' => 'Templates',
                        'url' => route('sms.templates'),
                        'active' => $this->checkRoutePattern($currentRouteName, [
                            'sms.templates',
                            'sms.templates.create',
                            'sms.templates.edit',
                            'sms.templates.store',
                            'sms.templates.update',
                            'sms.templates.delete',
                            'sms.templates.content'
                        ])
                    ],
                    [
                        'label' => 'Sender Names',
                        'url' => route('sms.sender-names'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'sms.sender-names*')
                    ],
                    [
                        'label' => 'Credits',
                        'url' => route('sms.credits'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'sms.credits')
                    ]
                ]
            ],
            [
                'label' => 'Contacts',
                'icon' => 'fas fa-address-book',
                'active' => $this->checkRoutePattern($currentRouteName, ['contacts.*', 'contact-groups.*']),
                'order' => 4,
                'children' => [
                    [
                        'label' => 'Manage Contacts',
                        'url' => route('contacts.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['contacts.index', 'contacts.create', 'contacts.edit', 'contacts.show'])
                    ],
                    [
                        'label' => 'Import Contacts',
                        'url' => route('contacts.import'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['contacts.import', 'contacts.upload-import', 'contacts.process-import'])
                    ],
                    [
                        'label' => 'Export Contacts',
                        'url' => route('contacts.export'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'contacts.export')
                    ],
                    [
                        'label' => 'Contact Groups',
                        'url' => route('contact-groups.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'contact-groups.*')
                    ],
                    [
                        'label' => 'Custom Fields',
                        'url' => route('custom-fields.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'custom-fields.*')
                    ]
                ]
            ],
            [
                'label' => 'USSD Services',
                'icon' => 'fas fa-phone-square',
                'active' => $this->checkRoutePattern($currentRouteName, 'ussd.*'),
                'order' => 5,
                'children' => [
                    [
                        'label' => 'Dashboard',
                        'url' => route('ussd.dashboard'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'ussd.dashboard')
                    ],
                    [
                        'label' => 'Services',
                        'url' => route('ussd.services'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['ussd.services', 'ussd.create', 'ussd.edit'])
                    ],
                    [
                        'label' => 'Analytics',
                        'url' => route('ussd.analytics'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'ussd.analytics')
                    ]
                ]
            ],
            [
                'label' => 'Contact Center',
                'icon' => 'fas fa-headset',
                'active' => $this->checkRoutePattern($currentRouteName, 'contact-center.*'),
                'order' => 6,
                'children' => [
                    [
                        'label' => 'Dashboard',
                        'url' => route('contact-center.dashboard'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'contact-center.dashboard')
                    ]
                ]
            ],
            [
                'label' => 'Virtual Numbers',
                'icon' => 'fas fa-phone',
                'active' => $this->checkRoutePattern($currentRouteName, 'virtual-numbers.*'),
                'order' => 7,
                'children' => [
                    [
                        'label' => 'My Numbers',
                        'url' => route('virtual-numbers.my-numbers'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'virtual-numbers.my-numbers')
                    ],
                    [
                        'label' => 'Browse Numbers',
                        'url' => route('virtual-numbers.browse'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'virtual-numbers.browse')
                    ]
                ]
            ],
            [
                'label' => 'Wallet',
                'icon' => 'fas fa-wallet',
                'active' => $this->checkRoutePattern($currentRouteName, 'wallet.*'),
                'order' => 8,
                'children' => [
                    [
                        'label' => 'Overview',
                        'url' => route('wallet.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'wallet.index')
                    ],
                    [
                        'label' => 'Top Up',
                        'url' => route('wallet.topup'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['wallet.topup', 'wallet.process-topup'])
                    ],
                    [
                        'label' => 'Purchase SMS',
                        'url' => route('wallet.purchase-sms'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['wallet.purchase-sms', 'wallet.process-purchase-sms'])
                    ]
                ]
            ],
            [
                'label' => 'Profile & Settings',
                'icon' => 'fas fa-user-cog',
                'active' => $this->checkRoutePattern($currentRouteName, ['profile.*', 'settings.*']),
                'order' => 9,
                'children' => [
                    [
                        'label' => 'My Profile',
                        'url' => route('profile.show'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['profile.show', 'profile.edit', 'profile.update'])
                    ],
                    [
                        'label' => 'Currency Settings',
                        'url' => route('settings.currency'),
                        'active' => $this->checkRoutePattern($currentRouteName, ['settings.currency', 'settings.currency.update'])
                    ]
                ]
            ]
        ];
        
        // Add admin menu items for admin users
        if ($user && $user->isAdmin()) {
            $navigation[] = [
                'label' => 'Admin',
                'icon' => 'fas fa-shield-alt',
                'active' => $this->checkRoutePattern($currentRouteName, 'admin.*'),
                'order' => 10,
                'children' => [
                    [
                        'label' => 'Sender Name Approval',
                        'url' => route('admin.sender-names.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'admin.sender-names.*')
                    ],
                    [
                        'label' => 'Payment Management',
                        'url' => route('admin.payments.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'admin.payments.*')
                    ],
                    [
                        'label' => 'User Management',
                        'url' => route('admin.users.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'admin.users.*')
                    ],
                    [
                        'label' => 'System Settings',
                        'url' => route('admin.settings.index'),
                        'active' => $this->checkRoutePattern($currentRouteName, 'admin.settings.*')
                    ]
                ]
            ];
        }
        
        // Sort navigation items by order
        usort($navigation, function ($a, $b) {
            return ($a['order'] ?? 999) - ($b['order'] ?? 999);
        });
        
        return $navigation;
    }
    
    /**
     * Check if the current route matches the given pattern.
     *
     * @param string $currentRoute
     * @param string|array $patterns
     * @return bool
     */
    private function checkRoutePattern(string $currentRoute, $patterns): bool
    {
        if (is_string($patterns)) {
            $patterns = [$patterns];
        }
        
        foreach ($patterns as $pattern) {
            // Exact match
            if ($pattern === $currentRoute) {
                return true;
            }
            
            // Wildcard match (e.g. 'users.*')
            if (str_ends_with($pattern, '*')) {
                $prefix = rtrim($pattern, '*');
                if (str_starts_with($currentRoute, $prefix)) {
                    return true;
                }
            }
        }
        
        return false;
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