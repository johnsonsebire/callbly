import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';

export default function Campaigns({ auth, campaigns = [], filters = {} }) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [dateRange, setDateRange] = useState({
        start: filters.start_date || '',
        end: filters.end_date || ''
    });
    const [selectedStatus, setSelectedStatus] = useState(filters.status || '');

    const statuses = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800'
    };

    const handleSearch = (e) => {
        e.preventDefault();
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (dateRange.start) params.append('start_date', dateRange.start);
        if (dateRange.end) params.append('end_date', dateRange.end);
        if (selectedStatus) params.append('status', selectedStatus);
        
        window.location.href = `${route('sms.campaigns')}?${params.toString()}`;
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    SMS Campaigns
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {/* Header with Create Button */}
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-lg font-semibold text-gray-900">
                                    All Campaigns
                                </h3>
                                <Link
                                    href={route('sms.compose')}
                                    className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Create Campaign
                                </Link>
                            </div>

                            {/* Filters */}
                            <form onSubmit={handleSearch} className="mb-6">
                                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <Input
                                            type="text"
                                            placeholder="Search campaigns..."
                                            value={searchTerm}
                                            onChange={e => setSearchTerm(e.target.value)}
                                            className="w-full"
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            type="date"
                                            value={dateRange.start}
                                            onChange={e => setDateRange({ ...dateRange, start: e.target.value })}
                                            className="w-full"
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            type="date"
                                            value={dateRange.end}
                                            onChange={e => setDateRange({ ...dateRange, end: e.target.value })}
                                            className="w-full"
                                        />
                                    </div>
                                    <div>
                                        <select
                                            value={selectedStatus}
                                            onChange={e => setSelectedStatus(e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">All Statuses</option>
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                            <option value="completed">Completed</option>
                                            <option value="failed">Failed</option>
                                        </select>
                                    </div>
                                </div>
                                <div className="mt-4 flex justify-end">
                                    <Button type="submit">
                                        Apply Filters
                                    </Button>
                                </div>
                            </form>

                            {/* Campaigns Table */}
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Campaign Name
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sender ID
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Recipients
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Delivery Rate
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {campaigns.map((campaign) => (
                                            <tr key={campaign.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {campaign.name}
                                                    </div>
                                                    <div className="text-sm text-gray-500">
                                                        {campaign.message.substring(0, 50)}...
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.sender_id}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.recipient_count.toLocaleString()}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statuses[campaign.status]}`}>
                                                        {campaign.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {campaign.created_at}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="flex items-center">
                                                        <div className="flex-1">
                                                            <div className="h-2 bg-gray-200 rounded-full">
                                                                <div
                                                                    className="h-2 bg-green-500 rounded-full"
                                                                    style={{ width: `${campaign.delivery_rate}%` }}
                                                                />
                                                            </div>
                                                        </div>
                                                        <span className="ml-2 text-sm text-gray-600">
                                                            {campaign.delivery_rate}%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Link
                                                        href={route('sms.campaign-details', campaign.id)}
                                                        className="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        View Details
                                                    </Link>
                                                </td>
                                            </tr>
                                        ))}
                                        {campaigns.length === 0 && (
                                            <tr>
                                                <td colSpan="7" className="px-6 py-4 text-center text-gray-500">
                                                    No campaigns found
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}