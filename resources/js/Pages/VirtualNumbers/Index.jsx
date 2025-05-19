import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Select from '../../Components/Select';
import ValidationErrors from '../../Components/ValidationErrors';

export default function VirtualNumbers({ auth, virtualNumbers = [], countries = [], filters = {} }) {
    const [showPurchaseModal, setShowPurchaseModal] = useState(false);
    const [selectedNumber, setSelectedNumber] = useState(null);
    const [currentView, setCurrentView] = useState('current');

    // Form for purchasing new numbers
    const { data, setData, post, processing, errors, reset } = useForm({
        country_code: countries.length > 0 ? countries[0].code : '',
        number_type: 'voice',
        quantity: 1
    });

    // Form for searching available numbers
    const [searchResults, setSearchResults] = useState([]);
    const [searchLoading, setSearchLoading] = useState(false);
    
    const numberTypes = [
        { value: 'voice', label: 'Voice' },
        { value: 'sms', label: 'SMS' },
        { value: 'both', label: 'Voice & SMS' }
    ];
    
    // Format phone number for display
    const formatPhoneNumber = (phoneNumber) => {
        if (!phoneNumber) return '';
        
        // Handle international format
        if (phoneNumber.startsWith('+')) {
            // Format based on country code
            if (phoneNumber.startsWith('+1')) { // US/Canada
                return phoneNumber.replace(/(\+\d{1})(\d{3})(\d{3})(\d{4})/, '$1 ($2) $3-$4');
            }
            // General international format
            return phoneNumber.replace(/(\+\d{1,3})(\d{3,4})(\d{3,4})(\d{3,4})/, '$1 $2 $3 $4');
        }
        
        return phoneNumber;
    };
    
    // Search for available numbers
    const searchAvailableNumbers = () => {
        setSearchLoading(true);
        
        // Simulate API call for available numbers
        setTimeout(() => {
            const results = Array.from({ length: 5 }, (_, i) => ({
                id: `search-${i}`,
                phone_number: `+${data.country_code === '1' ? '1' : data.country_code}${Math.floor(1000000000 + Math.random() * 9000000000)}`,
                country_code: data.country_code,
                type: data.number_type,
                monthly_cost: Math.floor(Math.random() * 5) + 2,
                setup_fee: Math.floor(Math.random() * 3),
                features: ['SMS', 'Voice'].filter(() => Math.random() > 0.3)
            }));
            setSearchResults(results);
            setSearchLoading(false);
        }, 1500);
    };
    
    // Handle number selection
    const handleNumberSelected = (number) => {
        setSelectedNumber(number);
    };
    
    // Handle purchase form submission
    const handlePurchaseSubmit = (e) => {
        e.preventDefault();
        
        if (currentView === 'search' && !selectedNumber) {
            alert('Please select a number first');
            return;
        }
        
        if (currentView === 'search') {
            // Purchase specific number
            post(route('virtual-numbers.purchase-selected'), {
                onSuccess: () => {
                    setShowPurchaseModal(false);
                    reset();
                    setSelectedNumber(null);
                    setCurrentView('current');
                    setSearchResults([]);
                }
            });
        } else {
            // Purchase new number(s)
            post(route('virtual-numbers.purchase-new'), {
                onSuccess: () => {
                    setShowPurchaseModal(false);
                    reset();
                }
            });
        }
    };

    // Helper to determine status badge color
    const getStatusColor = (status) => {
        switch (status?.toLowerCase()) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'pending':
            case 'processing':
                return 'bg-yellow-100 text-yellow-800';
            case 'disabled':
            case 'suspended':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };
    
    // Format date helper
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            dateStyle: 'medium'
        }).format(date);
    };
    
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Virtual Numbers</h2>
                    <div className="mt-4 md:mt-0">
                        <Button
                            onClick={() => {
                                setShowPurchaseModal(true);
                                setCurrentView('current');
                                setSelectedNumber(null);
                                setSearchResults([]);
                            }}
                        >
                            Get New Number
                        </Button>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Virtual Numbers Table */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Your Virtual Numbers</h3>
                            
                            {virtualNumbers.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Phone Number
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Country
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Type
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Acquired On
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Monthly Cost
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {virtualNumbers.map((number) => (
                                                <tr key={number.id} className="hover:bg-gray-50">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {formatPhoneNumber(number.phone_number)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {number.country_name || countries.find(c => c.code === number.country_code)?.name || number.country_code}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                                        {number.type}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(number.status)}`}>
                                                            {number.status}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {formatDate(number.created_at)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        ${number.monthly_cost || '0.00'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <Link href={route('virtual-numbers.show', number.id)} className="text-indigo-600 hover:text-indigo-900 mr-3">
                                                            Configure
                                                        </Link>
                                                        {number.status !== 'disabled' && (
                                                            <Link href={route('virtual-numbers.disable', number.id)} className="text-red-600 hover:text-red-900">
                                                                Disable
                                                            </Link>
                                                        )}
                                                        {number.status === 'disabled' && (
                                                            <Link href={route('virtual-numbers.enable', number.id)} className="text-green-600 hover:text-green-900">
                                                                Enable
                                                            </Link>
                                                        )}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="mx-auto h-12 w-12 text-gray-400">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                    </svg>
                                    <h3 className="mt-2 text-sm font-medium text-gray-900">No virtual numbers</h3>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Get started by purchasing a virtual phone number.
                                    </p>
                                    <div className="mt-6">
                                        <Button onClick={() => setShowPurchaseModal(true)}>
                                            Get New Number
                                        </Button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                    
                    {/* Features Overview */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-6">What You Can Do With Virtual Numbers</h3>
                            
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">SMS Messaging</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Send and receive SMS messages using your virtual number. Great for customer service, notifications, and two-way communication.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Voice Calls</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Make and receive voice calls. Set up custom greetings, call forwarding, voicemail, and interactive voice menus.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Contact Center</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Build a full-featured contact center with call distribution, queues, agent management, and call analytics.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Two-Factor Authentication</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Set up secure two-factor authentication for your applications using SMS or voice verification codes.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Local Presence</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Establish a local business presence in new markets without physical offices. Improve answer rates with local numbers.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">API Integration</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Build custom communication workflows by integrating our phone capabilities with your applications using our robust API.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {/* Purchase Modal */}
                    {showPurchaseModal && (
                        <div className="fixed inset-0 overflow-y-auto z-50">
                            <div className="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <div className="fixed inset-0 transition-opacity" aria-hidden="true">
                                    <div className="absolute inset-0 bg-gray-500 opacity-75"></div>
                                </div>

                                <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                    <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div className="sm:flex sm:items-start">
                                            <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 className="text-lg leading-6 font-medium text-gray-900">
                                                    Get Virtual Number
                                                </h3>
                                                
                                                <div className="mt-4">
                                                    {/* Tabs */}
                                                    <div className="border-b border-gray-200 mb-4">
                                                        <nav className="-mb-px flex" aria-label="Tabs">
                                                            <button
                                                                onClick={() => setCurrentView('current')}
                                                                className={`${
                                                                    currentView === 'current'
                                                                        ? 'border-indigo-500 text-indigo-600'
                                                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                                                } whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm`}
                                                            >
                                                                Quick Purchase
                                                            </button>
                                                            <button
                                                                onClick={() => setCurrentView('search')}
                                                                className={`${
                                                                    currentView === 'search'
                                                                        ? 'border-indigo-500 text-indigo-600'
                                                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                                                } whitespace-nowrap py-2 px-4 border-b-2 font-medium text-sm`}
                                                            >
                                                                Search Numbers
                                                            </button>
                                                        </nav>
                                                    </div>
                                                    
                                                    <ValidationErrors errors={errors} />
                                                    
                                                    <form onSubmit={handlePurchaseSubmit} className="space-y-6">
                                                        {currentView === 'current' && (
                                                            <>
                                                                <div>
                                                                    <label htmlFor="country_code" className="block text-sm font-medium text-gray-700">
                                                                        Country
                                                                    </label>
                                                                    <div className="mt-1">
                                                                        <Select
                                                                            id="country_code"
                                                                            name="country_code"
                                                                            value={data.country_code}
                                                                            onChange={(e) => setData('country_code', e.target.value)}
                                                                            className="block w-full"
                                                                        >
                                                                            {countries.map((country) => (
                                                                                <option key={country.code} value={country.code}>
                                                                                    {country.name} (+{country.code})
                                                                                </option>
                                                                            ))}
                                                                        </Select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <label htmlFor="number_type" className="block text-sm font-medium text-gray-700">
                                                                        Number Type
                                                                    </label>
                                                                    <div className="mt-1">
                                                                        <Select
                                                                            id="number_type"
                                                                            name="number_type"
                                                                            value={data.number_type}
                                                                            onChange={(e) => setData('number_type', e.target.value)}
                                                                            className="block w-full"
                                                                        >
                                                                            {numberTypes.map((type) => (
                                                                                <option key={type.value} value={type.value}>
                                                                                    {type.label}
                                                                                </option>
                                                                            ))}
                                                                        </Select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div>
                                                                    <label htmlFor="quantity" className="block text-sm font-medium text-gray-700">
                                                                        Quantity
                                                                    </label>
                                                                    <div className="mt-1">
                                                                        <Input
                                                                            type="number"
                                                                            min="1"
                                                                            max="10"
                                                                            id="quantity"
                                                                            name="quantity"
                                                                            value={data.quantity}
                                                                            onChange={(e) => setData('quantity', e.target.value)}
                                                                            className="block w-full"
                                                                        />
                                                                    </div>
                                                                    <p className="mt-1 text-sm text-gray-500">
                                                                        You can purchase up to 10 numbers at once.
                                                                    </p>
                                                                </div>
                                                            </>
                                                        )}
                                                        
                                                        {currentView === 'search' && (
                                                            <>
                                                                <div className="space-y-4">
                                                                    <div className="sm:flex sm:space-x-4 space-y-4 sm:space-y-0">
                                                                        <div className="sm:w-1/2">
                                                                            <label htmlFor="search_country_code" className="block text-sm font-medium text-gray-700">
                                                                                Country
                                                                            </label>
                                                                            <div className="mt-1">
                                                                                <Select
                                                                                    id="search_country_code"
                                                                                    name="country_code"
                                                                                    value={data.country_code}
                                                                                    onChange={(e) => setData('country_code', e.target.value)}
                                                                                    className="block w-full"
                                                                                >
                                                                                    {countries.map((country) => (
                                                                                        <option key={country.code} value={country.code}>
                                                                                            {country.name} (+{country.code})
                                                                                        </option>
                                                                                    ))}
                                                                                </Select>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div className="sm:w-1/2">
                                                                            <label htmlFor="search_number_type" className="block text-sm font-medium text-gray-700">
                                                                                Number Type
                                                                            </label>
                                                                            <div className="mt-1">
                                                                                <Select
                                                                                    id="search_number_type"
                                                                                    name="number_type"
                                                                                    value={data.number_type}
                                                                                    onChange={(e) => setData('number_type', e.target.value)}
                                                                                    className="block w-full"
                                                                                >
                                                                                    {numberTypes.map((type) => (
                                                                                        <option key={type.value} value={type.value}>
                                                                                            {type.label}
                                                                                        </option>
                                                                                    ))}
                                                                                </Select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div className="flex justify-center">
                                                                        <Button 
                                                                            type="button" 
                                                                            processing={searchLoading} 
                                                                            onClick={searchAvailableNumbers}
                                                                        >
                                                                            {searchLoading ? 'Searching...' : 'Search Available Numbers'}
                                                                        </Button>
                                                                    </div>
                                                                </div>
                                                                
                                                                {searchResults.length > 0 && (
                                                                    <div className="mt-4">
                                                                        <h4 className="font-medium text-gray-900">Available Numbers</h4>
                                                                        <div className="mt-2 border rounded-md overflow-hidden">
                                                                            <ul className="divide-y divide-gray-200">
                                                                                {searchResults.map((number) => (
                                                                                    <li 
                                                                                        key={number.id} 
                                                                                        className={`p-4 flex justify-between items-center cursor-pointer hover:bg-gray-50 ${
                                                                                            selectedNumber?.id === number.id ? 'bg-indigo-50' : ''
                                                                                        }`}
                                                                                        onClick={() => handleNumberSelected(number)}
                                                                                    >
                                                                                        <div>
                                                                                            <p className="text-sm font-medium text-gray-900">
                                                                                                {formatPhoneNumber(number.phone_number)}
                                                                                            </p>
                                                                                            <p className="text-sm text-gray-500">
                                                                                                {number.features.join(' • ')}
                                                                                            </p>
                                                                                        </div>
                                                                                        <div className="text-right">
                                                                                            <p className="text-sm font-medium text-gray-900">
                                                                                                ${number.monthly_cost}/mo
                                                                                            </p>
                                                                                            {number.setup_fee > 0 && (
                                                                                                <p className="text-xs text-gray-500">
                                                                                                    ${number.setup_fee} setup fee
                                                                                                </p>
                                                                                            )}
                                                                                        </div>
                                                                                    </li>
                                                                                ))}
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                )}
                                                            </>
                                                        )}
                                                        
                                                        <div className="flex items-center justify-between pt-4 border-t border-gray-200">
                                                            <Button
                                                                type="button"
                                                                onClick={() => setShowPurchaseModal(false)}
                                                                className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700"
                                                            >
                                                                Cancel
                                                            </Button>
                                                            <Button
                                                                type="submit"
                                                                disabled={(currentView === 'search' && !selectedNumber) || processing}
                                                                processing={processing}
                                                            >
                                                                Purchase Number{data.quantity > 1 && currentView === 'current' ? 's' : ''}
                                                            </Button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}