import React from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';

export default function SmsDashboard({ auth, stats = {}, recentCampaigns = [], senderNames = [] }) {
    // Default stats if not provided
    const smsStats = {
        totalSent: stats.totalSent || 0,
        totalDelivered: stats.totalDelivered || 0,
        totalFailed: stats.totalFailed || 0,
        deliveryRate: stats.deliveryRate || '0%',
        ...stats
    };
    
    // Default campaigns if not provided
    const campaigns = recentCampaigns.length > 0 ? recentCampaigns : [
        { id: 1, name: 'No campaigns found', status: 'none', recipients: 0, sentDate: '-', deliveryRate: '0%' }
    ];

    // Quick actions for SMS dashboard
    const quickActions = [
        {
            id: 'send-single',
            name: 'Send Single SMS',
            description: 'Send an SMS to a single recipient',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            ),
            route: 'sms.compose',
            color: 'bg-blue-500'
        },
        {
            id: 'create-campaign',
            name: 'Create Campaign',
            description: 'Create a new bulk SMS campaign',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                </svg>
            ),
            route: 'sms.compose',
            params: { type: 'campaign' },
            color: 'bg-green-500'
        },
        {
            id: 'register-sender',
            name: 'Register Sender ID',
            description: 'Register a new sender name or ID',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                </svg>
            ),
            route: 'sms.sender-names',
            color: 'bg-purple-500'
        },
        {
            id: 'view-campaigns',
            name: 'View Campaigns',
            description: 'See all your SMS campaigns',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                </svg>
            ),
            route: 'sms.campaigns',
            color: 'bg-yellow-500'
        },
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

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">SMS Dashboard</h2>
                    <div className="mt-4 md:mt-0">
                        <Link href={route('sms.compose')}>
                            <Button>
                                Send SMS
                            </Button>
                        </Link>
                    </div>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* SMS Analytics */}
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div className="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                            <div className="p-5">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 text-blue-600">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                        </svg>
                                    </div>
                                    <div className="ml-5 w-0 flex-1">
                                        <dl>
                                            <dt className="text-sm font-medium text-gray-500 truncate">Total Sent</dt>
                                            <dd>
                                                <div className="text-lg font-semibold text-gray-900">{smsStats.totalSent}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
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
                                            <dd>
                                                <div className="text-lg font-semibold text-gray-900">{smsStats.totalDelivered}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
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
                                            <dd>
                                                <div className="text-lg font-semibold text-gray-900">{smsStats.totalFailed}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
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
                                            <dd>
                                                <div className="text-lg font-semibold text-gray-900">{smsStats.deliveryRate}</div>
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {/* Quick Actions */}
                    <h2 className="text-lg font-semibold text-gray-800 mt-8 mb-4">Quick Actions</h2>
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        {quickActions.map((action) => (
                            <Link 
                                key={action.id} 
                                href={route(action.route, action.params || {})} 
                                className="block"
                            >
                                <div className="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:border-indigo-400 transition-colors duration-200">
                                    <div className="p-5">
                                        <div className="flex items-center">
                                            <div className={`flex-shrink-0 ${action.color} bg-opacity-10 rounded-md p-3`}>
                                                {React.cloneElement(action.icon, { className: `w-6 h-6 ${action.color.replace('bg-', 'text-')}` })}
                                            </div>
                                            <div className="ml-5">
                                                <h3 className="text-base font-medium text-gray-900">{action.name}</h3>
                                                <p className="mt-1 text-sm text-gray-500">{action.description}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>

                    {/* Sender Names List */}
                    <h2 className="text-lg font-semibold text-gray-800 mt-8 mb-4">Sender IDs</h2>
                    <div className="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 mb-8">
                        <div className="overflow-x-auto">
                            {senderNames && senderNames.length > 0 ? (
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Created
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {senderNames.map((sender, index) => (
                                            <tr key={sender.id || index} className="hover:bg-gray-50">
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {sender.name}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(sender.status)}`}>
                                                        {sender.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {sender.createdAt || '-'}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            ) : (
                                <div className="text-center py-6 px-4">
                                    <p className="text-gray-500 mb-4">You don't have any registered sender IDs yet</p>
                                    <Link href={route('sms.sender-names')}>
                                        <Button size="sm">Register Sender ID</Button>
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Recent Campaigns */}
                    <h2 className="text-lg font-semibold text-gray-800 mt-8 mb-4">Recent Campaigns</h2>
                    <div className="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Campaign
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Recipients
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Delivery
                                        </th>
                                        <th scope="col" className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {campaigns.map((campaign) => (
                                        <tr key={campaign.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {campaign.name}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                {campaign.status !== 'none' && (
                                                    <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusColor(campaign.status)}`}>
                                                        {campaign.status}
                                                    </span>
                                                )}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {campaign.recipients}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {campaign.sentDate}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {campaign.deliveryRate}
                                            </td>
                                            <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {campaign.status !== 'none' && (
                                                    <Link href={route('sms.campaign-details', campaign.id)} className="text-indigo-600 hover:text-indigo-900">
                                                        Details
                                                    </Link>
                                                )}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                        <div className="bg-gray-50 px-5 py-3 text-right">
                            <Link href={route('sms.campaigns')} className="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View All Campaigns <span aria-hidden="true">→</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}