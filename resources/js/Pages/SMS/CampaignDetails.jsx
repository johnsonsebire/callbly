import React from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';

export default function CampaignDetails({ auth, campaign, deliveryStats = {}, messages = [] }) {
    const statuses = {
        delivered: 'bg-green-100 text-green-800',
        failed: 'bg-red-100 text-red-800',
        pending: 'bg-yellow-100 text-yellow-800',
        sent: 'bg-blue-100 text-blue-800'
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Campaign Details
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Campaign Overview */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6">
                            <div className="flex justify-between items-start">
                                <div>
                                    <h3 className="text-lg font-semibold text-gray-900">
                                        {campaign.name}
                                    </h3>
                                    <p className="mt-1 text-sm text-gray-500">
                                        Created on {campaign.created_at}
                                    </p>
                                </div>
                                <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${statuses[campaign.status]}`}>
                                    {campaign.status}
                                </span>
                            </div>
                            
                            <div className="mt-6 border-t border-gray-200 pt-6">
                                <dl className="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2 lg:grid-cols-4">
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Sender ID</dt>
                                        <dd className="mt-1 text-lg font-semibold text-gray-900">{campaign.sender_id}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Total Recipients</dt>
                                        <dd className="mt-1 text-lg font-semibold text-gray-900">{campaign.recipient_count.toLocaleString()}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Message Segments</dt>
                                        <dd className="mt-1 text-lg font-semibold text-gray-900">{campaign.message_segments}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Total Cost</dt>
                                        <dd className="mt-1 text-lg font-semibold text-gray-900">${campaign.total_cost.toFixed(2)}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {/* Delivery Statistics */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                    Delivery Statistics
                                </h3>
                                <div className="space-y-4">
                                    <div>
                                        <div className="flex justify-between mb-1">
                                            <span className="text-sm font-medium text-gray-700">Delivered</span>
                                            <span className="text-sm font-medium text-gray-700">{deliveryStats.delivered_percentage}%</span>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-2">
                                            <div 
                                                className="bg-green-500 h-2 rounded-full" 
                                                style={{ width: `${deliveryStats.delivered_percentage}%` }}
                                            />
                                        </div>
                                        <p className="mt-1 text-sm text-gray-500">{deliveryStats.delivered_count.toLocaleString()} messages</p>
                                    </div>

                                    <div>
                                        <div className="flex justify-between mb-1">
                                            <span className="text-sm font-medium text-gray-700">Failed</span>
                                            <span className="text-sm font-medium text-gray-700">{deliveryStats.failed_percentage}%</span>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-2">
                                            <div 
                                                className="bg-red-500 h-2 rounded-full" 
                                                style={{ width: `${deliveryStats.failed_percentage}%` }}
                                            />
                                        </div>
                                        <p className="mt-1 text-sm text-gray-500">{deliveryStats.failed_count.toLocaleString()} messages</p>
                                    </div>

                                    <div>
                                        <div className="flex justify-between mb-1">
                                            <span className="text-sm font-medium text-gray-700">Pending</span>
                                            <span className="text-sm font-medium text-gray-700">{deliveryStats.pending_percentage}%</span>
                                        </div>
                                        <div className="w-full bg-gray-200 rounded-full h-2">
                                            <div 
                                                className="bg-yellow-500 h-2 rounded-full" 
                                                style={{ width: `${deliveryStats.pending_percentage}%` }}
                                            />
                                        </div>
                                        <p className="mt-1 text-sm text-gray-500">{deliveryStats.pending_count.toLocaleString()} messages</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Message Content */}
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                    Message Content
                                </h3>
                                <div className="bg-gray-50 rounded-lg p-4">
                                    <p className="text-gray-900 whitespace-pre-wrap">{campaign.message}</p>
                                </div>
                                <dl className="mt-4 grid grid-cols-2 gap-4">
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Characters</dt>
                                        <dd className="mt-1 text-sm text-gray-900">{campaign.character_count}</dd>
                                    </div>
                                    <div>
                                        <dt className="text-sm font-medium text-gray-500">Encoding</dt>
                                        <dd className="mt-1 text-sm text-gray-900">{campaign.encoding}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {/* Message Logs */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-4">
                                Message Logs
                            </h3>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Recipient
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Sent At
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Delivered At
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Error Message
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {messages.map((message) => (
                                            <tr key={message.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {message.recipient}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statuses[message.status]}`}>
                                                        {message.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {message.sent_at}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {message.delivered_at || '-'}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-red-500">
                                                    {message.error_message || '-'}
                                                </td>
                                            </tr>
                                        ))}
                                        {messages.length === 0 && (
                                            <tr>
                                                <td colSpan="5" className="px-6 py-4 text-center text-gray-500">
                                                    No messages found
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