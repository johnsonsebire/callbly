import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import { PieChart, Pie, Cell, ResponsiveContainer, LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, Legend } from 'recharts';

export default function CampaignDetails({ auth, campaign = {}, deliveryStats = {}, recipients = [] }) {
    const [currentTab, setCurrentTab] = useState('overview');
    const [currentPage, setCurrentPage] = useState(1);
    const [itemsPerPage] = useState(10);
    
    // Prepare data for charts
    const preparePieChartData = () => {
        const stats = deliveryStats || {};
        return [
            { name: 'Delivered', value: stats.delivered || 0, color: '#10B981' }, // green
            { name: 'Failed', value: stats.failed || 0, color: '#EF4444' }, // red
            { name: 'Pending', value: stats.pending || 0, color: '#F59E0B' }, // yellow
            { name: 'Processing', value: stats.processing || 0, color: '#6366F1' } // indigo
        ];
    };
    
    // Line chart data for delivery over time (sample data if not provided)
    const deliveryTimeData = campaign.delivery_timeline || [
        { time: '00:00', delivered: 0 },
        { time: '01:00', delivered: 5 },
        { time: '02:00', delivered: 12 },
        { time: '03:00', delivered: 25 },
        { time: '04:00', delivered: 40 },
        { time: '05:00', delivered: 60 },
        { time: '06:00', delivered: 70 }
    ];

    // Format date/time helper
    const formatDate = (dateString, includeTime = true) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            dateStyle: 'medium',
            ...(includeTime && { timeStyle: 'short' })
        }).format(date);
    };
    
    // Helper to determine status badge color
    const getStatusColor = (status) => {
        switch (status.toLowerCase()) {
            case 'completed':
            case 'delivered':
                return 'bg-green-100 text-green-800';
            case 'pending':
            case 'processing':
            case 'sending':
                return 'bg-yellow-100 text-yellow-800';
            case 'failed':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };
    
    // Pagination for recipients list
    const indexOfLastItem = currentPage * itemsPerPage;
    const indexOfFirstItem = indexOfLastItem - itemsPerPage;
    const currentRecipients = recipients.slice(indexOfFirstItem, indexOfLastItem);
    const totalPages = Math.ceil(recipients.length / itemsPerPage);
    
    const paginate = (pageNumber) => {
        if (pageNumber > 0 && pageNumber <= totalPages) {
            setCurrentPage(pageNumber);
        }
    };
    
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        Campaign Details
                    </h2>
                    <div className="mt-4 md:mt-0 flex space-x-3">
                        <Link href={route('sms.campaigns')}>
                            <Button className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700">
                                Back to Campaigns
                            </Button>
                        </Link>
                        <Link href={route('sms.compose', { type: 'campaign' })}>
                            <Button>
                                New Campaign
                            </Button>
                        </Link>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Campaign Header */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div>
                                    <h1 className="text-2xl font-bold text-gray-900">{campaign.name || 'Campaign'}</h1>
                                    <div className="mt-1 flex items-center">
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(campaign.status || 'pending')}`}>
                                            {campaign.status || 'Pending'}
                                        </span>
                                        <span className="ml-3 text-sm text-gray-500">
                                            Created on {formatDate(campaign.created_at)}
                                        </span>
                                        {campaign.scheduled_at && (
                                            <span className="ml-3 text-sm text-gray-500">
                                                Scheduled for {formatDate(campaign.scheduled_at)}
                                            </span>
                                        )}
                                    </div>
                                </div>
                                <div className="mt-4 md:mt-0">
                                    {['pending', 'scheduled'].includes(campaign.status?.toLowerCase()) && (
                                        <Link href={route('sms.campaign.cancel', campaign.id)}>
                                            <Button className="bg-white border-red-300 hover:bg-red-50 text-red-700">
                                                Cancel Campaign
                                            </Button>
                                        </Link>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {/* Campaign Tabs */}
                    <div className="mb-6">
                        <nav className="flex space-x-4 border-b border-gray-200 overflow-x-auto" aria-label="Tabs">
                            <button
                                onClick={() => setCurrentTab('overview')}
                                className={`px-3 py-2 text-sm font-medium ${
                                    currentTab === 'overview'
                                        ? 'border-b-2 border-indigo-500 text-indigo-600'
                                        : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }`}
                                aria-current={currentTab === 'overview' ? 'page' : undefined}
                            >
                                Overview
                            </button>
                            <button
                                onClick={() => setCurrentTab('recipients')}
                                className={`px-3 py-2 text-sm font-medium ${
                                    currentTab === 'recipients'
                                        ? 'border-b-2 border-indigo-500 text-indigo-600'
                                        : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }`}
                                aria-current={currentTab === 'recipients' ? 'page' : undefined}
                            >
                                Recipients ({recipients.length})
                            </button>
                            <button
                                onClick={() => setCurrentTab('message')}
                                className={`px-3 py-2 text-sm font-medium ${
                                    currentTab === 'message'
                                        ? 'border-b-2 border-indigo-500 text-indigo-600'
                                        : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                }`}
                                aria-current={currentTab === 'message' ? 'page' : undefined}
                            >
                                Message Content
                            </button>
                        </nav>
                    </div>
                    
                    {/* Tab Content */}
                    <div className="space-y-6">
                        {currentTab === 'overview' && (
                            <>
                                {/* Stats Cards */}
                                <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-blue-600">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                                    </svg>
                                                </div>
                                                <div className="ml-5 w-0 flex-1">
                                                    <dl>
                                                        <dt className="text-sm font-medium text-gray-500 truncate">Total Recipients</dt>
                                                        <dd className="text-lg font-semibold text-gray-900">{campaign.recipients_count || recipients.length || 0}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0 bg-green-100 rounded-md p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-green-600">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                                <div className="ml-5 w-0 flex-1">
                                                    <dl>
                                                        <dt className="text-sm font-medium text-gray-500 truncate">Delivered</dt>
                                                        <dd className="text-lg font-semibold text-gray-900">{deliveryStats.delivered || 0}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0 bg-red-100 rounded-md p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-red-600">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                                    </svg>
                                                </div>
                                                <div className="ml-5 w-0 flex-1">
                                                    <dl>
                                                        <dt className="text-sm font-medium text-gray-500 truncate">Failed</dt>
                                                        <dd className="text-lg font-semibold text-gray-900">{deliveryStats.failed || 0}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <div className="flex items-center">
                                                <div className="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-purple-600">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                                    </svg>
                                                </div>
                                                <div className="ml-5 w-0 flex-1">
                                                    <dl>
                                                        <dt className="text-sm font-medium text-gray-500 truncate">Delivery Rate</dt>
                                                        <dd className="text-lg font-semibold text-gray-900">
                                                            {campaign.delivery_rate || 
                                                                ((deliveryStats.delivered && campaign.recipients_count) ? 
                                                                    `${Math.round((deliveryStats.delivered / campaign.recipients_count) * 100)}%` : 
                                                                    '0%'
                                                                )
                                                            }
                                                        </dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {/* Charts */}
                                <div className="grid grid-cols-1 gap-5 lg:grid-cols-2">
                                    {/* Pie Chart */}
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <h3 className="text-lg font-medium text-gray-900">Delivery Status</h3>
                                            <div className="mt-6" style={{ height: '300px' }}>
                                                <ResponsiveContainer width="100%" height="100%">
                                                    <PieChart>
                                                        <Pie
                                                            data={preparePieChartData()}
                                                            cx="50%"
                                                            cy="50%"
                                                            labelLine={false}
                                                            label={({ name, percent }) => `${name}: ${(percent * 100).toFixed(0)}%`}
                                                            outerRadius={80}
                                                            fill="#8884d8"
                                                            dataKey="value"
                                                        >
                                                            {preparePieChartData().map((entry, index) => (
                                                                <Cell key={`cell-${index}`} fill={entry.color} />
                                                            ))}
                                                        </Pie>
                                                        <Legend />
                                                        <Tooltip />
                                                    </PieChart>
                                                </ResponsiveContainer>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {/* Line Chart */}
                                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                        <div className="p-5">
                                            <h3 className="text-lg font-medium text-gray-900">Delivery Timeline</h3>
                                            <div className="mt-6" style={{ height: '300px' }}>
                                                <ResponsiveContainer width="100%" height="100%">
                                                    <LineChart
                                                        data={deliveryTimeData}
                                                        margin={{ top: 5, right: 30, left: 20, bottom: 5 }}
                                                    >
                                                        <CartesianGrid strokeDasharray="3 3" />
                                                        <XAxis dataKey="time" />
                                                        <YAxis />
                                                        <Tooltip />
                                                        <Legend />
                                                        <Line type="monotone" dataKey="delivered" stroke="#8884d8" activeDot={{ r: 8 }} />
                                                    </LineChart>
                                                </ResponsiveContainer>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {/* Campaign Details */}
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="px-4 py-5 sm:px-6">
                                        <h3 className="text-lg font-medium leading-6 text-gray-900">Campaign Information</h3>
                                        <p className="mt-1 max-w-2xl text-sm text-gray-500">Details about the campaign configuration.</p>
                                    </div>
                                    <div className="border-t border-gray-200">
                                        <dl>
                                            <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt className="text-sm font-medium text-gray-500">Sender ID</dt>
                                                <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{campaign.sender_name || '-'}</dd>
                                            </div>
                                            <div className="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt className="text-sm font-medium text-gray-500">Message Type</dt>
                                                <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    {campaign.message_type === 'unicode' ? 'Unicode (supports special characters)' : 'Text (GSM)'}
                                                </dd>
                                            </div>
                                            <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt className="text-sm font-medium text-gray-500">Message Length</dt>
                                                <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    {campaign.message_length || 0} characters ({campaign.segments || 1} segment{campaign.segments > 1 ? 's' : ''})
                                                </dd>
                                            </div>
                                            <div className="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt className="text-sm font-medium text-gray-500">Created By</dt>
                                                <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{campaign.created_by || auth.user.name}</dd>
                                            </div>
                                            <div className="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                                <dt className="text-sm font-medium text-gray-500">Cost</dt>
                                                <dd className="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                                    {campaign.cost ? 
                                                        new Intl.NumberFormat('en-US', {
                                                            style: 'currency',
                                                            currency: campaign.currency || 'USD'
                                                        }).format(campaign.cost)
                                                        : '-'
                                                    }
                                                    {' '}({campaign.credits_used || 0} credits)
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </>
                        )}
                        
                        {currentTab === 'recipients' && (
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                                    <h3 className="text-lg font-medium leading-6 text-gray-900">Campaign Recipients</h3>
                                    <p className="mt-1 max-w-2xl text-sm text-gray-500">
                                        List of all recipients and their message status.
                                    </p>
                                </div>
                                
                                <div className="overflow-x-auto">
                                    <table className="min-w-full divide-y divide-gray-200">
                                        <thead className="bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Phone Number
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Delivered At
                                                </th>
                                                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Error (if any)
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {currentRecipients.length > 0 ? (
                                                currentRecipients.map((recipient, index) => (
                                                    <tr key={recipient.id || index}>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            {recipient.phone_number}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap">
                                                            <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(recipient.status)}`}>
                                                                {recipient.status}
                                                            </span>
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {recipient.delivered_at ? formatDate(recipient.delivered_at) : '-'}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {recipient.error || '-'}
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td colSpan="4" className="px-6 py-4 text-center text-sm text-gray-500">
                                                        No recipient data available
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                                
                                {/* Pagination Controls */}
                                {recipients.length > itemsPerPage && (
                                    <div className="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                                        <nav className="flex items-center justify-between">
                                            <div className="flex-1 flex justify-between sm:hidden">
                                                <button
                                                    onClick={() => paginate(currentPage - 1)}
                                                    disabled={currentPage === 1}
                                                    className={`relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                                                        currentPage === 1 ? 'text-gray-300' : 'text-gray-700 hover:bg-gray-50'
                                                    }`}
                                                >
                                                    Previous
                                                </button>
                                                <button
                                                    onClick={() => paginate(currentPage + 1)}
                                                    disabled={currentPage === totalPages}
                                                    className={`ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md ${
                                                        currentPage === totalPages ? 'text-gray-300' : 'text-gray-700 hover:bg-gray-50'
                                                    }`}
                                                >
                                                    Next
                                                </button>
                                            </div>
                                            <div className="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                                <div>
                                                    <p className="text-sm text-gray-700">
                                                        Showing <span className="font-medium">{indexOfFirstItem + 1}</span> to{' '}
                                                        <span className="font-medium">{Math.min(indexOfLastItem, recipients.length)}</span> of{' '}
                                                        <span className="font-medium">{recipients.length}</span> results
                                                    </p>
                                                </div>
                                                <div>
                                                    <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                                        <button
                                                            onClick={() => paginate(1)}
                                                            disabled={currentPage === 1}
                                                            className={`relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium ${
                                                                currentPage === 1 ? 'text-gray-300' : 'text-gray-500 hover:bg-gray-50'
                                                            }`}
                                                        >
                                                            <span className="sr-only">First</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5" />
                                                            </svg>
                                                        </button>
                                                        <button
                                                            onClick={() => paginate(currentPage - 1)}
                                                            disabled={currentPage === 1}
                                                            className={`relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium ${
                                                                currentPage === 1 ? 'text-gray-300' : 'text-gray-500 hover:bg-gray-50'
                                                            }`}
                                                        >
                                                            <span className="sr-only">Previous</span>
                                                            <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fillRule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clipRule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        
                                                        {/* Page Numbers */}
                                                        {Array.from({ length: Math.min(5, totalPages) }, (_, i) => {
                                                            let pageNum;
                                                            if (totalPages <= 5) {
                                                                pageNum = i + 1;
                                                            } else if (currentPage <= 3) {
                                                                pageNum = i + 1;
                                                            } else if (currentPage >= totalPages - 2) {
                                                                pageNum = totalPages - 4 + i;
                                                            } else {
                                                                pageNum = currentPage - 2 + i;
                                                            }
                                                            
                                                            if (pageNum > 0 && pageNum <= totalPages) {
                                                                return (
                                                                    <button
                                                                        key={pageNum}
                                                                        onClick={() => paginate(pageNum)}
                                                                        className={`relative inline-flex items-center px-4 py-2 border ${
                                                                            currentPage === pageNum
                                                                                ? 'bg-indigo-50 border-indigo-500 z-10 text-indigo-600'
                                                                                : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50'
                                                                        } text-sm font-medium`}
                                                                    >
                                                                        {pageNum}
                                                                    </button>
                                                                );
                                                            }
                                                            return null;
                                                        })}
                                                        
                                                        <button
                                                            onClick={() => paginate(currentPage + 1)}
                                                            disabled={currentPage === totalPages}
                                                            className={`relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium ${
                                                                currentPage === totalPages ? 'text-gray-300' : 'text-gray-500 hover:bg-gray-50'
                                                            }`}
                                                        >
                                                            <span className="sr-only">Next</span>
                                                            <svg className="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                                <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                            </svg>
                                                        </button>
                                                        <button
                                                            onClick={() => paginate(totalPages)}
                                                            disabled={currentPage === totalPages}
                                                            className={`relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium ${
                                                                currentPage === totalPages ? 'text-gray-300' : 'text-gray-500 hover:bg-gray-50'
                                                            }`}
                                                        >
                                                            <span className="sr-only">Last</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-5 h-5">
                                                                <path strokeLinecap="round" strokeLinejoin="round" d="M11.25 4.5l7.5 7.5-7.5 7.5m-6-15l7.5 7.5-7.5 7.5" />
                                                            </svg>
                                                        </button>
                                                    </nav>
                                                </div>
                                            </div>
                                        </nav>
                                    </div>
                                )}
                            </div>
                        )}
                        
                        {currentTab === 'message' && (
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="px-4 py-5 sm:px-6 border-b border-gray-200">
                                    <h3 className="text-lg font-medium leading-6 text-gray-900">Message Content</h3>
                                </div>
                                <div className="p-6">
                                    <div className="bg-gray-100 p-4 rounded-lg">
                                        <p className="whitespace-pre-wrap text-gray-900">{campaign.message}</p>
                                    </div>
                                    <div className="mt-4 text-sm text-gray-500">
                                        <p>Character count: {campaign.message_length || campaign.message?.length || 0} characters</p>
                                        <p>Message segments: {campaign.segments || 1}</p>
                                        <p>Encoding: {campaign.message_type === 'unicode' ? 'Unicode (supports special characters)' : 'GSM 03.38 (standard encoding)'}</p>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}