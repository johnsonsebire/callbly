import React from 'react';
import { Link } from '@inertiajs/react';
import Button from '../Components/Button';

export default function AppLayout({ children, title }) {
    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="flex-shrink-0 flex items-center">
                                <Link href="/">
                                    <h1 className="text-xl font-bold text-gray-900">Callbly</h1>
                                </Link>
                            </div>
                            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <Link
                                    href="/dashboard"
                                    className="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                >
                                    Dashboard
                                </Link>
                                <Link
                                    href="/campaigns"
                                    className="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                >
                                    Campaigns
                                </Link>
                                <Link
                                    href="/contacts"
                                    className="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                >
                                    Contacts
                                </Link>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <Button variant="secondary">Login</Button>
                            <Button className="ml-4">Sign Up</Button>
                        </div>
                    </div>
                </div>
            </nav>

            <main>
                {title && (
                    <header className="bg-white shadow">
                        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <h1 className="text-3xl font-bold text-gray-900">{title}</h1>
                        </div>
                    </header>
                )}
                <div className="py-6">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {children}
                    </div>
                </div>
            </main>
        </div>
    );
}