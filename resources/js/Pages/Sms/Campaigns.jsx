import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Select from '../../Components/Select';
import Pagination from '../../Components/Pagination';

export default function SmsCampaigns({ auth, campaigns = [], filters = {}, pagination = {} }) {
    // Campaigns data with default empty array
    const campaignsList = campaigns.length > 0 ? campaigns : [];
    
    // Form filters state
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [statusFilter, setStatusFilter] = useState(filters.status || '');
    const [dateFilter, setDateFilter] = useState(filters.date || '');
    const [sortField, setSortField] = useState(filters.sort_field || 'created_at');
    const [sortDirection, setSortDirection] = useState(filters.sort_direction || 'desc');
    
    // Status options for filtering
    const statusOptions = [
        { value: '', label: 'All Statuses' },
        { value: 'pending', label: 'Pending' },
        { value: 'processing', label: 'Processing' },
        { value: 'sending', label: 'Sending' },
        { value: 'completed', label: 'Completed' },
        { value: 'failed', label: 'Failed' },
    ];
    
    // Date filter options
    const dateFilterOptions = [
        { value: '', label: 'All Time' },
        { value: 'today', label: 'Today' },
        { value: 'yesterday', label: 'Yesterday' },
        { value: 'last_7_days', label: 'Last 7 Days' },
        { value: 'last_30_days', label: 'Last 30 Days' },
        { value: 'this_month', label: 'This Month' },
        { value: 'last_month', label: 'Last Month' },
    ];

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
    
    // Format date helper
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('en-US', {
            dateStyle: 'medium',
            timeStyle: 'short'
        }).format(date);
    };
    
    // Handle sort column click
    const handleSort = (field) => {
        if (sortField === field) {
            setSortDirection(sortDirection === 'asc' ? 'desc' : 'asc');
        } else {
            setSortField(field);
            setSortDirection('asc');
        }
    };
    
    // Handle filter form submission
    const handleFilterSubmit = (e) => {
        e.preventDefault();
        const query = new URLSearchParams();
        
        if (searchTerm) query.append('search', searchTerm);
        if (statusFilter) query.append('status', statusFilter);
        if (dateFilter) query.append('date', dateFilter);
        if (sortField) query.append('sort_field', sortField);
        if (sortDirection) query.append('sort_direction', sortDirection);
        
        window.location.href = `${route('sms.campaigns')}?${query.toString()}`;
    };
    
    // Handle filter reset
    const handleResetFilters = () => {
        setSearchTerm('');
        setStatusFilter('');
        setDateFilter('');
        setSortField('created_at');
        setSortDirection('desc');
    };
    
    // Render sort icon
    const renderSortIcon = (field) => {
        if (sortField !== field) {
            return (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4 ml-1 text-gray-400">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                </svg>
            );
        }
        
        return sortDirection === 'asc' ? (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4 ml-1 text-indigo-600">
                <path strokeLinecap="round" strokeLinejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        ) : (
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-4 h-4 ml-1 text-indigo-600">
                <path strokeLinecap="round" strokeLinejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        );
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">SMS Campaigns</h2>
                    <div className="mt-4 md:mt-0">
                        <Link href={route('sms.compose', { type: 'campaign' })}>
                            <Button>
                                Create Campaign
                            </Button>
                        </Link>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        {/* Filters */}
                        <div className="p-6 border-b border-gray-200">
                            <form onSubmit={handleFilterSubmit}>
                                <div className="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6">
                                    <div className="sm:col-span-2">
                                        <label htmlFor="search" className="block text-sm font-medium text-gray-700">
                                            Search
                                        </label>
                                        <div className="mt-1">
                                            <Input
                                                type="text"
                                                name="search"
                                                id="search"
                                                placeholder="Campaign name or recipient"
                                                value={searchTerm}
                                                onChange={(e) => setSearchTerm(e.target.value)}
                                                className="block w-full"
                                            />
                                        </div>
                                    </div>
                                    
                                    <div className="sm:col-span-2">
                                        <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                                            Status
                                        </label>
                                        <div className="mt-1">
                                            <Select
                                                name="status"
                                                id="status"
                                                value={statusFilter}
                                                onChange={(e) => setStatusFilter(e.target.value)}
                                                className="block w-full"
                                            >
                                                {statusOptions.map((option) => (
                                                    <option key={option.value} value={option.value}>
                                                        {option.label}
                                                    </option>
                                                ))}
                                            </Select>
                                        </div>
                                    </div>
                                    
                                    <div className="sm:col-span-2">
                                        <label htmlFor="date-filter" className="block text-sm font-medium text-gray-700">
                                            Date Range
                                        </label>
                                        <div className="mt-1">
                                            <Select
                                                name="date-filter"
                                                id="date-filter"
                                                value={dateFilter}
                                                onChange={(e) => setDateFilter(e.target.value)}
                                                className="block w-full"
                                            >
                                                {dateFilterOptions.map((option) => (
                                                    <option key={option.value} value={option.value}>
                                                        {option.label}
                                                    </option>
                                                ))}
                                            </Select>
                                        </div>
                                    </div>
                                    
                                    <div className="sm:col-span-6 flex justify-end space-x-3">
                                        <Button
                                            type="button"
                                            onClick={handleResetFilters}
                                            className="bg-white border-gray-300 hover:bg-gray-50 text-gray-700"
                                        >
                                            Reset
                                        </Button>
                                        <Button type="submit">
                                            Filter Results
                                        </Button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        {/* Campaigns Table */}
                        <div className="overflow-x-auto">
                            {campaignsList.length > 0 ? (
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th
                                                onClick={() => handleSort('name')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Campaign Name
                                                    {renderSortIcon('name')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('status')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Status
                                                    {renderSortIcon('status')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('recipients_count')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Recipients
                                                    {renderSortIcon('recipients_count')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('delivered_count')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Delivered
                                                    {renderSortIcon('delivered_count')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('delivery_rate')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Delivery Rate
                                                    {renderSortIcon('delivery_rate')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('scheduled_at')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Scheduled
                                                    {renderSortIcon('scheduled_at')}
                                                </div>
                                            </th>
                                            <th
                                                onClick={() => handleSort('created_at')}
                                                className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
                                            >
                                                <div className="flex items-center">
                                                    Created
                                                    {renderSortIcon('created_at')}
                                                </div>
                                            </th>
                                            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {campaignsList.map((campaign) => (
                                            <tr key={campaign.id} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {campaign.name}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(campaign.status)}`}>
                                                        {campaign.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.recipients_count || 0}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.delivered_count || 0}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.delivery_rate || '0%'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.scheduled_at ? formatDate(campaign.scheduled_at) : 'Immediate'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {formatDate(campaign.created_at)}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link href={route('sms.campaign-details', campaign.id)} className="text-indigo-600 hover:text-indigo-900 mr-4">
                                                        Details
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            ) : (
                                <div className="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="mx-auto h-12 w-12 text-gray-400">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 9v.906a2.25 2.25 0 01-1.183 1.981l-6.478 3.488M2.25 9v.906a2.25 2.25 0 001.183 1.981l6.478 3.488m8.839 2.51l-4.66-2.51m0 0l-1.023-.55a2.25 2.25 0 00-2.134 0l-1.022.55m0 0l-4.661 2.51m16.5 1.615a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V8.844a2.25 2.25 0 011.183-1.98l7.5-4.04a2.25 2.25 0 012.134 0l7.5 4.04a2.25 2.25 0 011.183 1.98V19.5z" />
                                    </svg>
                                    <h3 className="mt-2 text-sm font-medium text-gray-900">No campaigns found</h3>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Get started by creating a new campaign.
                                    </p>
                                    <div className="mt-6">
                                        <Link href={route('sms.compose', { type: 'campaign' })}>
                                            <Button>
                                                Create Campaign
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Pagination */}
                        {pagination && pagination.total > 0 && (
                            <div className="px-6 py-3 border-t border-gray-200">
                                <Pagination pagination={pagination} />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}