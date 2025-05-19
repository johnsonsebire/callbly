import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';

// Mock chart component - Replace with your preferred charting library
const Chart = ({ data, type }) => (
    <div className="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
        <p className="text-gray-500">Chart Placeholder: {type}</p>
    </div>
);

export default function Analytics({ auth, service, analytics = {}, dateRange = {} }) {
    const [timeframe, setTimeframe] = useState('7d'); // 7d, 30d, 90d

    const stats = [
        {
            name: 'Total Sessions',
            value: analytics.total_sessions,
            change: analytics.sessions_change,
            changeType: analytics.sessions_change >= 0 ? 'increase' : 'decrease'
        },
        {
            name: 'Unique Users',
            value: analytics.unique_users,
            change: analytics.users_change,
            changeType: analytics.users_change >= 0 ? 'increase' : 'decrease'
        },
        {
            name: 'Average Session Time',
            value: analytics.avg_session_time + 's',
            change: analytics.session_time_change,
            changeType: analytics.session_time_change >= 0 ? 'increase' : 'decrease'
        },
        {
            name: 'Completion Rate',
            value: analytics.completion_rate + '%',
            change: analytics.completion_rate_change,
            changeType: analytics.completion_rate_change >= 0 ? 'increase' : 'decrease'
        }
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        {service.name} Analytics
                    </h2>
                    <Link
                        href={route('ussd.services')}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Back to Services
                    </Link>
                </div>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Timeframe Selection */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6">
                            <div className="flex items-center justify-between">
                                <div className="flex space-x-4">
                                    <button
                                        onClick={() => setTimeframe('7d')}
                                        className={`px-4 py-2 text-sm font-medium rounded-md ${
                                            timeframe === '7d'
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'text-gray-500 hover:text-gray-700'
                                        }`}
                                    >
                                        Last 7 days
                                    </button>
                                    <button
                                        onClick={() => setTimeframe('30d')}
                                        className={`px-4 py-2 text-sm font-medium rounded-md ${
                                            timeframe === '30d'
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'text-gray-500 hover:text-gray-700'
                                        }`}
                                    >
                                        Last 30 days
                                    </button>
                                    <button
                                        onClick={() => setTimeframe('90d')}
                                        className={`px-4 py-2 text-sm font-medium rounded-md ${
                                            timeframe === '90d'
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'text-gray-500 hover:text-gray-700'
                                        }`}
                                    >
                                        Last 90 days
                                    </button>
                                </div>
                                <Button>
                                    Export Report
                                </Button>
                            </div>
                        </div>
                    </div>

                    {/* Stats Overview */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        {stats.map((stat) => (
                            <div key={stat.name} className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <dt className="text-sm font-medium text-gray-500">
                                        {stat.name}
                                    </dt>
                                    <dd className="mt-1 flex justify-between items-baseline md:block lg:flex">
                                        <div className="flex items-baseline text-2xl font-semibold text-indigo-600">
                                            {stat.value}
                                        </div>
                                        <div className={`inline-flex items-baseline px-2.5 py-0.5 rounded-full text-sm font-medium ${
                                            stat.changeType === 'increase'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        }`}>
                                            {stat.changeType === 'increase' ? (
                                                <svg className="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                                </svg>
                                            ) : (
                                                <svg className="-ml-1 mr-0.5 h-5 w-5 flex-shrink-0 self-center text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                                </svg>
                                            )}
                                            <span className="sr-only">
                                                {stat.changeType === 'increase' ? 'Increased' : 'Decreased'} by
                                            </span>
                                            {Math.abs(stat.change)}%
                                        </div>
                                    </dd>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Charts Grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    Sessions Over Time
                                </h3>
                                <Chart type="line" data={analytics.sessions_chart} />
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    Menu Navigation
                                </h3>
                                <Chart type="tree" data={analytics.menu_navigation} />
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    Session Duration Distribution
                                </h3>
                                <Chart type="histogram" data={analytics.duration_distribution} />
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">
                                    Peak Usage Hours
                                </h3>
                                <Chart type="bar" data={analytics.peak_hours} />
                            </div>
                        </div>
                    </div>

                    {/* Popular Menu Options */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">
                                Most Popular Menu Options
                            </h3>
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Menu Option
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Usage Count
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Success Rate
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Avg. Time Spent
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {analytics.popular_options?.map((option) => (
                                            <tr key={option.id}>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    {option.text}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {option.usage_count.toLocaleString()}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="flex items-center">
                                                        <div className="flex-1 h-2 bg-gray-200 rounded-full">
                                                            <div
                                                                className="h-2 bg-green-500 rounded-full"
                                                                style={{ width: `${option.success_rate}%` }}
                                                            />
                                                        </div>
                                                        <span className="ml-2 text-sm text-gray-600">
                                                            {option.success_rate}%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {option.avg_time}s
                                                </td>
                                            </tr>
                                        ))}
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