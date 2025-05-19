import React from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../Layouts/AuthenticatedLayout';

export default function Dashboard({ auth, stats = {}, recentActivity = [], balanceInfo = {} }) {
    const services = [
        {
            name: 'SMS Campaigns',
            icon: (
                <svg className="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            ),
            route: 'sms.compose',
            description: 'Send bulk SMS messages',
            linkText: 'Send SMS',
            credits: auth.user.sms_credits
        },
        {
            name: 'Contact Center',
            icon: (
                <svg className="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            ),
            route: 'contact-center',
            description: 'Manage customer calls',
            linkText: 'Open Contact Center',
            credits: auth.user.call_credits
        },
        {
            name: 'USSD Services',
            icon: (
                <svg className="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                </svg>
            ),
            route: 'ussd.services',
            description: 'Manage USSD menus',
            linkText: 'Configure USSD',
            credits: auth.user.ussd_credits
        },
        {
            name: 'Virtual Numbers',
            icon: (
                <svg className="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
            ),
            route: 'virtual-numbers',
            description: 'Manage virtual numbers',
            linkText: 'Browse Numbers',
            credits: null
        }
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Welcome Section */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6">
                            <h1 className="text-2xl font-bold text-gray-900">
                                Welcome back, {auth.user.name}!
                            </h1>
                            <p className="mt-1 text-gray-600">
                                Here's an overview of your Callbly services
                            </p>
                        </div>
                    </div>

                    {/* Services Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        {services.map((service) => (
                            <div key={service.name} className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <div className="flex items-center mb-4">
                                        {service.icon}
                                        <h3 className="ml-3 text-lg font-semibold text-gray-900">
                                            {service.name}
                                        </h3>
                                    </div>
                                    <p className="text-gray-600 mb-4">
                                        {service.description}
                                    </p>
                                    {service.credits !== null && (
                                        <div className="mb-4">
                                            <p className="text-sm text-gray-600">Available Credits</p>
                                            <p className="text-2xl font-bold text-gray-900">
                                                {service.credits.toLocaleString()}
                                            </p>
                                        </div>
                                    )}
                                    <Link
                                        href={route(service.route)}
                                        className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        {service.linkText}
                                    </Link>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Recent Activity */}
                        <div className="lg:col-span-2">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                        Recent Activity
                                    </h3>
                                    <div className="divide-y divide-gray-200">
                                        {recentActivity.map((activity, index) => (
                                            <div key={index} className="py-4">
                                                <div className="flex items-center justify-between">
                                                    <div>
                                                        <p className="text-sm font-medium text-gray-900">
                                                            {activity.description}
                                                        </p>
                                                        <p className="text-sm text-gray-500">
                                                            {activity.timestamp}
                                                        </p>
                                                    </div>
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        activity.status === 'success' ? 'bg-green-100 text-green-800' :
                                                        activity.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                        'bg-red-100 text-red-800'
                                                    }`}>
                                                        {activity.status}
                                                    </span>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Quick Actions */}
                        <div className="lg:col-span-1">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                        Quick Actions
                                    </h3>
                                    <nav className="space-y-3">
                                        <Link
                                            href={route('sms.credits')}
                                            className="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow"
                                        >
                                            <span className="flex-1">Buy SMS Credits</span>
                                            <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                            </svg>
                                        </Link>
                                        <Link
                                            href={route('profile')}
                                            className="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow"
                                        >
                                            <span className="flex-1">Update Profile</span>
                                            <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                            </svg>
                                        </Link>
                                        <Link
                                            href={route('settings.currency')}
                                            className="flex items-center p-3 text-base font-medium text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow"
                                        >
                                            <span className="flex-1">Change Currency</span>
                                            <svg className="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                            </svg>
                                        </Link>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}