import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import TextArea from '../../Components/TextArea';
import Select from '../../Components/Select';
import ValidationErrors from '../../Components/ValidationErrors';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer } from 'recharts';

export default function UssdIndex({ auth, ussdServices = [], stats = {}, countries = [] }) {
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [activeTab, setActiveTab] = useState('active');
    const [isConfirmingDeletion, setIsConfirmingDeletion] = useState(false);
    const [serviceToDelete, setServiceToDelete] = useState(null);

    // Form for creating a USSD service
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        shortcode: '',
        country_code: countries.length > 0 ? countries[0].code : '',
        description: '',
        welcome_message: 'Welcome to our USSD service! Please select an option:',
        menu_options: [
            { id: 1, option: '1', text: 'Option 1', action: 'display_message', response: 'You selected Option 1' },
            { id: 2, option: '2', text: 'Option 2', action: 'display_message', response: 'You selected Option 2' },
            { id: 3, option: '3', text: 'Option 3', action: 'display_message', response: 'You selected Option 3' }
        ],
        is_active: true
    });

    // Add a new menu option
    const addMenuOption = () => {
        const newOption = {
            id: data.menu_options.length + 1,
            option: (data.menu_options.length + 1).toString(),
            text: `Option ${data.menu_options.length + 1}`,
            action: 'display_message',
            response: `You selected Option ${data.menu_options.length + 1}`
        };

        setData('menu_options', [...data.menu_options, newOption]);
    };

    // Remove a menu option
    const removeMenuOption = (id) => {
        const updatedOptions = data.menu_options.filter(option => option.id !== id);
        setData('menu_options', updatedOptions);
    };

    // Update a menu option
    const updateMenuOption = (id, field, value) => {
        const updatedOptions = data.menu_options.map(option => {
            if (option.id === id) {
                return { ...option, [field]: value };
            }
            return option;
        });
        setData('menu_options', updatedOptions);
    };

    // Sample usage data for charts
    const usageData = stats.usage || Array.from({ length: 30 }, (_, i) => ({
        date: `${i + 1}`,
        sessions: Math.floor(Math.random() * 1000)
    }));

    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('ussd.store'), {
            onSuccess: () => {
                setShowCreateModal(false);
                reset();
            }
        });
    };

    // Filter services based on active tab
    const filteredServices = ussdServices.filter(service => {
        if (activeTab === 'active') return service.is_active;
        if (activeTab === 'inactive') return !service.is_active;
        return true;
    });

    // Format date helper
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            dateStyle: 'medium'
        }).format(date);
    };

    // Confirm deletion of a service
    const confirmDeletion = (service) => {
        setServiceToDelete(service);
        setIsConfirmingDeletion(true);
    };

    // Delete service
    const deleteService = () => {
        if (!serviceToDelete) return;
        
        // Call the delete endpoint
        // Axios.delete(route('ussd.destroy', serviceToDelete.id))
        //     .then(() => {
        //         setIsConfirmingDeletion(false);
        //         setServiceToDelete(null);
        //     });
        
        setIsConfirmingDeletion(false);
        setServiceToDelete(null);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">USSD Services</h2>
                    <div className="mt-4 md:mt-0">
                        <Button onClick={() => setShowCreateModal(true)}>
                            Create USSD Service
                        </Button>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Usage Stats */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">USSD Service Usage</h3>
                            <div className="h-64">
                                <ResponsiveContainer width="100%" height="100%">
                                    <LineChart
                                        data={usageData}
                                        margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
                                    >
                                        <CartesianGrid strokeDasharray="3 3" />
                                        <XAxis dataKey="date" />
                                        <YAxis />
                                        <Tooltip />
                                        <Line type="monotone" dataKey="sessions" stroke="#8884d8" activeDot={{ r: 8 }} />
                                    </LineChart>
                                </ResponsiveContainer>
                            </div>
                            <div className="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div className="bg-indigo-50 p-4 rounded-lg">
                                    <h4 className="text-sm font-medium text-gray-500">Total Sessions</h4>
                                    <p className="text-2xl font-bold text-gray-900">{stats.totalSessions || '32,456'}</p>
                                    <p className="text-sm text-green-600">+12.5% from last month</p>
                                </div>
                                <div className="bg-indigo-50 p-4 rounded-lg">
                                    <h4 className="text-sm font-medium text-gray-500">Active Services</h4>
                                    <p className="text-2xl font-bold text-gray-900">{ussdServices.filter(s => s.is_active).length || '5'}</p>
                                    <p className="text-sm text-green-600">+1 from last month</p>
                                </div>
                                <div className="bg-indigo-50 p-4 rounded-lg">
                                    <h4 className="text-sm font-medium text-gray-500">Average Session Time</h4>
                                    <p className="text-2xl font-bold text-gray-900">{stats.avgSessionTime || '45s'}</p>
                                    <p className="text-sm text-green-600">-3s from last month</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* USSD Services Tabs */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="border-b border-gray-200">
                            <nav className="-mb-px flex">
                                <button
                                    onClick={() => setActiveTab('all')}
                                    className={`${
                                        activeTab === 'all'
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    } whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm`}
                                >
                                    All Services
                                </button>
                                <button
                                    onClick={() => setActiveTab('active')}
                                    className={`${
                                        activeTab === 'active'
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    } whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm`}
                                >
                                    Active
                                </button>
                                <button
                                    onClick={() => setActiveTab('inactive')}
                                    className={`${
                                        activeTab === 'inactive'
                                            ? 'border-indigo-500 text-indigo-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    } whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm`}
                                >
                                    Inactive
                                </button>
                            </nav>
                        </div>

                        {/* USSD Services List */}
                        <div className="p-6 bg-white border-b border-gray-200">
                            {filteredServices.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Name
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Shortcode
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Country
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Created
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Sessions Today
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {filteredServices.map((service) => (
                                                <tr key={service.id} className="hover:bg-gray-50">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {service.name}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        *{service.shortcode}#
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {service.country_name || countries.find(c => c.code === service.country_code)?.name || service.country_code}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                                            service.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                                        }`}>
                                                            {service.is_active ? 'Active' : 'Inactive'}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {formatDate(service.created_at)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                                        {service.daily_sessions || Math.floor(Math.random() * 100)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                                        <Link href={route('ussd.show', service.id)} className="text-indigo-600 hover:text-indigo-900">
                                                            View
                                                        </Link>
                                                        <Link href={route('ussd.edit', service.id)} className="text-blue-600 hover:text-blue-900">
                                                            Edit
                                                        </Link>
                                                        <button
                                                            type="button"
                                                            onClick={() => confirmDeletion(service)}
                                                            className="text-red-600 hover:text-red-900"
                                                        >
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="mx-auto h-12 w-12 text-gray-400">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                    </svg>
                                    <h3 className="mt-2 text-sm font-medium text-gray-900">No USSD services found</h3>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Get started by creating a new USSD service.
                                    </p>
                                    <div className="mt-6">
                                        <Button onClick={() => setShowCreateModal(true)}>
                                            Create USSD Service
                                        </Button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* USSD Features Overview */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-6">USSD Service Features</h3>
                            
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12a9.375 9.375 0 01-9.375 9.375A9.375 9.375 0 012.25 12c0-5.094 4.281-9.375 9.375-9.375A9.375 9.375 0 0121 12z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Interactive Menus</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Create multi-level interactive menus that allow users to navigate through service options with ease.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Data Collection</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Collect and validate data from users including text input, numbers, and selections for surveys, registrations, or feedback.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">API Integration</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Connect to external systems and databases via API for real-time data validation, lookups, and transactions.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.148 2.148A12.061 12.061 0 0116.5 7.605" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Analytics Dashboard</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Track usage patterns, session durations, menu selections, and more with our comprehensive analytics dashboard.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Dynamic Configuration</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Easily configure service flows, menu options, and responses without technical expertise or code changes.
                                    </p>
                                </div>
                                
                                <div className="p-6 bg-indigo-50 rounded-lg">
                                    <div className="inline-flex h-10 w-10 items-center justify-center rounded-md bg-indigo-100 text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 className="mt-4 text-base font-medium text-gray-900">Session Management</h4>
                                    <p className="mt-2 text-sm text-gray-600">
                                        Maintain user session state to create complex multi-step flows with the ability to go back, resume, or cancel.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Create USSD Service Modal */}
            {showCreateModal && (
                <div className="fixed inset-0 overflow-y-auto z-50">
                    <div className="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div className="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div className="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                            <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div className="sm:flex sm:items-start">
                                    <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900">
                                            Create USSD Service
                                        </h3>
                                        
                                        <div className="mt-4">
                                            <ValidationErrors errors={errors} />
                                            
                                            <form onSubmit={handleSubmit} className="space-y-6">
                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                                            Service Name
                                                        </label>
                                                        <div className="mt-1">
                                                            <Input
                                                                type="text"
                                                                id="name"
                                                                name="name"
                                                                value={data.name}
                                                                onChange={(e) => setData('name', e.target.value)}
                                                                className="block w-full"
                                                                required
                                                            />
                                                        </div>
                                                    </div>
                                                    
                                                    <div>
                                                        <label htmlFor="shortcode" className="block text-sm font-medium text-gray-700">
                                                            Shortcode (e.g. 123)
                                                        </label>
                                                        <div className="mt-1">
                                                            <Input
                                                                type="text"
                                                                id="shortcode"
                                                                name="shortcode"
                                                                value={data.shortcode}
                                                                onChange={(e) => setData('shortcode', e.target.value)}
                                                                className="block w-full"
                                                                required
                                                            />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                                                required
                                                            >
                                                                {countries.map((country) => (
                                                                    <option key={country.code} value={country.code}>
                                                                        {country.name}
                                                                    </option>
                                                                ))}
                                                            </Select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label htmlFor="is_active" className="block text-sm font-medium text-gray-700">
                                                            Status
                                                        </label>
                                                        <div className="mt-1">
                                                            <Select
                                                                id="is_active"
                                                                name="is_active"
                                                                value={data.is_active}
                                                                onChange={(e) => setData('is_active', e.target.value === 'true')}
                                                                className="block w-full"
                                                            >
                                                                <option value="true">Active</option>
                                                                <option value="false">Inactive</option>
                                                            </Select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label htmlFor="description" className="block text-sm font-medium text-gray-700">
                                                        Description
                                                    </label>
                                                    <div className="mt-1">
                                                        <TextArea
                                                            id="description"
                                                            name="description"
                                                            value={data.description}
                                                            onChange={(e) => setData('description', e.target.value)}
                                                            className="block w-full"
                                                            rows={3}
                                                        />
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <label htmlFor="welcome_message" className="block text-sm font-medium text-gray-700">
                                                        Welcome Message
                                                    </label>
                                                    <div className="mt-1">
                                                        <TextArea
                                                            id="welcome_message"
                                                            name="welcome_message"
                                                            value={data.welcome_message}
                                                            onChange={(e) => setData('welcome_message', e.target.value)}
                                                            className="block w-full"
                                                            rows={2}
                                                            required
                                                        />
                                                    </div>
                                                </div>
                                                
                                                <div>
                                                    <div className="flex justify-between items-center">
                                                        <label className="block text-sm font-medium text-gray-700">
                                                            Menu Options
                                                        </label>
                                                        <Button
                                                            type="button"
                                                            onClick={addMenuOption}
                                                            className="py-1 px-3 text-sm"
                                                        >
                                                            Add Option
                                                        </Button>
                                                    </div>
                                                    
                                                    <div className="mt-2 space-y-4">
                                                        {data.menu_options.map((option) => (
                                                            <div key={option.id} className="border border-gray-200 rounded-md p-4">
                                                                <div className="flex justify-between items-center mb-3">
                                                                    <h4 className="text-sm font-medium text-gray-900">Option {option.option}</h4>
                                                                    <button
                                                                        type="button"
                                                                        onClick={() => removeMenuOption(option.id)}
                                                                        className="text-sm text-red-600 hover:text-red-800"
                                                                    >
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                                
                                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                                    <div>
                                                                        <label htmlFor={`option-${option.id}-text`} className="block text-xs font-medium text-gray-700">
                                                                            Display Text
                                                                        </label>
                                                                        <Input
                                                                            type="text"
                                                                            id={`option-${option.id}-text`}
                                                                            value={option.text}
                                                                            onChange={(e) => updateMenuOption(option.id, 'text', e.target.value)}
                                                                            className="block w-full mt-1 text-sm"
                                                                        />
                                                                    </div>
                                                                    <div>
                                                                        <label htmlFor={`option-${option.id}-action`} className="block text-xs font-medium text-gray-700">
                                                                            Action
                                                                        </label>
                                                                        <Select
                                                                            id={`option-${option.id}-action`}
                                                                            value={option.action}
                                                                            onChange={(e) => updateMenuOption(option.id, 'action', e.target.value)}
                                                                            className="block w-full mt-1 text-sm"
                                                                        >
                                                                            <option value="display_message">Display Message</option>
                                                                            <option value="sub_menu">Open Submenu</option>
                                                                            <option value="collect_input">Collect Input</option>
                                                                            <option value="api_call">Call API</option>
                                                                        </Select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div className="mt-2">
                                                                    <label htmlFor={`option-${option.id}-response`} className="block text-xs font-medium text-gray-700">
                                                                        Response Text
                                                                    </label>
                                                                    <TextArea
                                                                        id={`option-${option.id}-response`}
                                                                        value={option.response}
                                                                        onChange={(e) => updateMenuOption(option.id, 'response', e.target.value)}
                                                                        className="block w-full mt-1 text-sm"
                                                                        rows={2}
                                                                    />
                                                                </div>
                                                            </div>
                                                        ))}
                                                    </div>
                                                </div>
                                                
                                                <div className="flex items-center justify-between pt-4 border-t border-gray-200">
                                                    <Button
                                                        type="button"
                                                        onClick={() => setShowCreateModal(false)}
                                                        className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700"
                                                    >
                                                        Cancel
                                                    </Button>
                                                    <Button
                                                        type="submit"
                                                        processing={processing}
                                                    >
                                                        Create Service
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
            
            {/* Delete Confirmation Modal */}
            {isConfirmingDeletion && (
                <div className="fixed inset-0 overflow-y-auto z-50">
                    <div className="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div className="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div className="absolute inset-0 bg-gray-500 opacity-75"></div>
                        </div>

                        <span className="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div className="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div className="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div className="sm:flex sm:items-start">
                                    <div className="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg className="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div className="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 className="text-lg leading-6 font-medium text-gray-900">
                                            Delete USSD Service
                                        </h3>
                                        <div className="mt-2">
                                            <p className="text-sm text-gray-500">
                                                Are you sure you want to delete this USSD service? All data associated with this service will be permanently removed. This action cannot be undone.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <Button
                                    type="button"
                                    className="bg-red-600 hover:bg-red-700 focus:ring-red-500 w-full sm:w-auto sm:ml-3"
                                    onClick={deleteService}
                                >
                                    Delete
                                </Button>
                                <Button
                                    type="button"
                                    className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700 mt-3 sm:mt-0 w-full sm:w-auto"
                                    onClick={() => {
                                        setIsConfirmingDeletion(false);
                                        setServiceToDelete(null);
                                    }}
                                >
                                    Cancel
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}