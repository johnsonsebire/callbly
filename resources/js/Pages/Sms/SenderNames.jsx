import React, { useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';
import ValidationErrors from '../../Components/ValidationErrors';

export default function SenderNames({ auth, senderNames = [] }) {
    const { errors, flash } = usePage().props;
    const [showRegistrationForm, setShowRegistrationForm] = useState(false);
    
    // Form state
    const { data, setData, post, processing, reset } = useForm({
        name: '',
        description: '',
        usage_type: 'promotional',
    });
    
    // Usage types for sender names
    const usageTypes = [
        { value: 'promotional', label: 'Promotional' },
        { value: 'transactional', label: 'Transactional' },
    ];
    
    // Handle form submission
    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('sms.sender-names.store'), {
            onSuccess: () => {
                reset();
                setShowRegistrationForm(false);
            }
        });
    };
    
    // Helper to determine status badge color
    const getStatusColor = (status) => {
        switch (status.toLowerCase()) {
            case 'active':
            case 'approved':
                return 'bg-green-100 text-green-800';
            case 'pending':
            case 'under review':
                return 'bg-yellow-100 text-yellow-800';
            case 'rejected':
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
            dateStyle: 'medium',
        }).format(date);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Sender IDs</h2>
                    <div className="mt-4 md:mt-0">
                        <Button
                            onClick={() => setShowRegistrationForm(!showRegistrationForm)}
                        >
                            {showRegistrationForm ? 'Cancel' : 'Register New Sender ID'}
                        </Button>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {flash.success && (
                        <div className="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <svg className="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm text-green-700">{flash.success}</p>
                                </div>
                            </div>
                        </div>
                    )}
                    
                    {flash.error && (
                        <div className="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <svg className="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm text-red-700">{flash.error}</p>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Registration Form */}
                    {showRegistrationForm && (
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div className="p-6 bg-white border-b border-gray-200">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Register New Sender ID</h3>
                                
                                <ValidationErrors errors={errors} />
                                
                                <form onSubmit={handleSubmit}>
                                    <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                        <div className="sm:col-span-3">
                                            <Label forInput="name" value="Sender Name" />
                                            <div className="mt-1">
                                                <Input
                                                    type="text"
                                                    name="name"
                                                    value={data.name}
                                                    className="block w-full"
                                                    isFocused={true}
                                                    onChange={e => setData('name', e.target.value)}
                                                    required
                                                    maxLength={11}
                                                />
                                            </div>
                                            <p className="mt-2 text-sm text-gray-500">
                                                Maximum 11 characters, alphanumeric only
                                            </p>
                                        </div>
                                        
                                        <div className="sm:col-span-3">
                                            <Label forInput="usage_type" value="Usage Type" />
                                            <div className="mt-1">
                                                <select
                                                    id="usage_type"
                                                    name="usage_type"
                                                    value={data.usage_type}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    onChange={e => setData('usage_type', e.target.value)}
                                                    required
                                                >
                                                    {usageTypes.map(type => (
                                                        <option key={type.value} value={type.value}>
                                                            {type.label}
                                                        </option>
                                                    ))}
                                                </select>
                                            </div>
                                            <p className="mt-2 text-sm text-gray-500">
                                                Choose the type of messages you'll send with this sender ID
                                            </p>
                                        </div>
                                        
                                        <div className="sm:col-span-6">
                                            <Label forInput="description" value="Description / Purpose" />
                                            <div className="mt-1">
                                                <textarea
                                                    id="description"
                                                    name="description"
                                                    rows={3}
                                                    value={data.description}
                                                    onChange={e => setData('description', e.target.value)}
                                                    className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    required
                                                />
                                            </div>
                                            <p className="mt-2 text-sm text-gray-500">
                                                Briefly describe how you intend to use this sender ID
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div className="mt-6 flex justify-end">
                                        <Button
                                            type="button"
                                            onClick={() => setShowRegistrationForm(false)}
                                            className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700 mr-3"
                                        >
                                            Cancel
                                        </Button>
                                        <Button
                                            type="submit"
                                            processing={processing}
                                        >
                                            Register Sender ID
                                        </Button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    )}

                    {/* Sender Names Table */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Your Sender IDs</h3>
                            
                            {senderNames.length > 0 ? (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Sender Name
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Usage Type
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Created On
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Last Updated
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {senderNames.map((sender) => (
                                                <tr key={sender.id} className="hover:bg-gray-50">
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {sender.name}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                                        {sender.usage_type}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(sender.status)}`}>
                                                            {sender.status}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {formatDate(sender.created_at)}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {formatDate(sender.updated_at)}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <div className="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="mx-auto h-12 w-12 text-gray-400">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                                    </svg>
                                    <h3 className="mt-2 text-sm font-medium text-gray-900">No sender IDs registered</h3>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Register a sender ID to start sending messages with your brand name.
                                    </p>
                                    <div className="mt-6">
                                        <Button onClick={() => setShowRegistrationForm(true)}>
                                            Register Sender ID
                                        </Button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                    
                    {/* Additional Info */}
                    <div className="mt-6 bg-blue-50 rounded-lg p-6">
                        <div className="flex">
                            <div className="flex-shrink-0">
                                <svg className="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            </div>
                            <div className="ml-3">
                                <h3 className="text-sm font-medium text-blue-800">About Sender IDs</h3>
                                <div className="mt-2 text-sm text-blue-700">
                                    <p>
                                        A Sender ID is the name or number that appears as the sender of an SMS message. Registration is required by telecom regulations in most countries.
                                    </p>
                                    <ul className="list-disc pl-5 mt-2 space-y-1">
                                        <li>Sender IDs are limited to 11 alphanumeric characters</li>
                                        <li>Approval can take 1-7 business days depending on the country</li>
                                        <li>Some countries have additional requirements for sender ID registration</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}