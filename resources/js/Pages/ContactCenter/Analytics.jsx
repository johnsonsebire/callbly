import React, { useState } from 'react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';

// Placeholder for real charts - Replace with your preferred charting library
const Chart = ({ type, data, options }) => (
    <div className="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
        <p className="text-gray-500">Chart Placeholder: {type}</p>
    </div>
);

export default function Analytics({ auth, metrics = {}, agents = [], teams = [] }) {
    const [timeframe, setTimeframe] = useState('today');
    const [activeTab, setActiveTab] = useState('overview');

    const tabs = [
        { id: 'overview', name: 'Overview' },
        { id: 'agents', name: 'Agent Performance' },
        { id: 'queues', name: 'Queue Analytics' },
        { id: 'quality', name: 'Quality Metrics' }
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Contact Center Analytics
                    </h2>
                    <select
                        value={timeframe}
                        onChange={(e) => setTimeframe(e.target.value)}
                        className="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 30 Days</option>
                        <option value="quarter">Last Quarter</option>
                    </select>
                </div>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Tabs */}
                    <div className="mb-6">
                        <div className="sm:hidden">
                            <select
                                value={activeTab}
                                onChange={(e) => setActiveTab(e.target.value)}
                                className="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                {tabs.map((tab) => (
                                    <option key={tab.id} value={tab.id}>{tab.name}</option>
                                ))}
                            </select>
                        </div>
                        <div className="hidden sm:block">
                            <nav className="flex space-x-4" aria-label="Tabs">
                                {tabs.map((tab) => (
                                    <button
                                        key={tab.id}
                                        onClick={() => setActiveTab(tab.id)}
                                        className={`${
                                            activeTab === tab.id
                                                ? 'bg-indigo-100 text-indigo-700'
                                                : 'text-gray-500 hover:text-gray-700'
                                        } rounded-md px-3 py-2 text-sm font-medium`}
                                    >
                                        {tab.name}
                                    </button>
                                ))}
                            </nav>
                        </div>
                    </div>

                    {/* Overview Tab */}
                    {activeTab === 'overview' && (
                        <div className="space-y-6">
                            {/* Key Metrics */}
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Total Calls</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.total_calls || 0}</dd>
                                        <div className="mt-2 flex items-center text-sm">
                                            <span className={`text-${metrics.calls_trend >= 0 ? 'green' : 'red'}-600`}>
                                                {metrics.calls_trend > 0 ? '+' : ''}{metrics.calls_trend}%
                                            </span>
                                            <span className="text-gray-500 ml-2">vs previous period</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Average Handle Time</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.avg_handle_time || '0:00'}</dd>
                                        <div className="mt-2 flex items-center text-sm">
                                            <span className={`text-${metrics.handle_time_trend <= 0 ? 'green' : 'red'}-600`}>
                                                {metrics.handle_time_trend > 0 ? '+' : ''}{metrics.handle_time_trend}%
                                            </span>
                                            <span className="text-gray-500 ml-2">vs previous period</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Customer Satisfaction</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.csat || 0}%</dd>
                                        <div className="mt-2 flex items-center text-sm">
                                            <span className={`text-${metrics.csat_trend >= 0 ? 'green' : 'red'}-600`}>
                                                {metrics.csat_trend > 0 ? '+' : ''}{metrics.csat_trend}%
                                            </span>
                                            <span className="text-gray-500 ml-2">vs previous period</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Abandoned Rate</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.abandoned_rate || 0}%</dd>
                                        <div className="mt-2 flex items-center text-sm">
                                            <span className={`text-${metrics.abandoned_trend <= 0 ? 'green' : 'red'}-600`}>
                                                {metrics.abandoned_trend > 0 ? '+' : ''}{metrics.abandoned_trend}%
                                            </span>
                                            <span className="text-gray-500 ml-2">vs previous period</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Charts */}
                            <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Call Volume Trends</h3>
                                        <Chart type="line" data={metrics.call_volume_data} />
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Average Wait Time</h3>
                                        <Chart type="line" data={metrics.wait_time_data} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Agent Performance Tab */}
                    {activeTab === 'agents' && (
                        <div className="space-y-6">
                            {/* Agent Performance Table */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full divide-y divide-gray-200">
                                            <thead className="bg-gray-50">
                                                <tr>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Agent
                                                    </th>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Calls Handled
                                                    </th>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Avg Handle Time
                                                    </th>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        CSAT Score
                                                    </th>
                                                    <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Resolution Rate
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody className="bg-white divide-y divide-gray-200">
                                                {agents.map((agent) => (
                                                    <tr key={agent.id}>
                                                        <td className="px-6 py-4 whitespace-nowrap">
                                                            <div className="flex items-center">
                                                                <div className="text-sm font-medium text-gray-900">
                                                                    {agent.name}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {agent.calls_handled}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {agent.avg_handle_time}
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {agent.csat_score}%
                                                        </td>
                                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            {agent.resolution_rate}%
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {/* Agent Performance Charts */}
                            <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Agent Productivity</h3>
                                        <Chart type="bar" data={metrics.agent_productivity} />
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">CSAT by Agent</h3>
                                        <Chart type="bar" data={metrics.agent_csat} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Queue Analytics Tab */}
                    {activeTab === 'queues' && (
                        <div className="space-y-6">
                            {/* Queue Performance Metrics */}
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Queue Wait Times</h3>
                                        <Chart type="line" data={metrics.queue_wait_times} />
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Queue Size Trends</h3>
                                        <Chart type="line" data={metrics.queue_size_trends} />
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Abandon Rate by Queue</h3>
                                        <Chart type="bar" data={metrics.queue_abandon_rates} />
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}

                    {/* Quality Metrics Tab */}
                    {activeTab === 'quality' && (
                        <div className="space-y-6">
                            {/* Quality Score Cards */}
                            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Average CSAT</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.avg_csat || 0}%</dd>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">First Call Resolution</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.fcr || 0}%</dd>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">Quality Score</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.quality_score || 0}%</dd>
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <dt className="text-sm font-medium text-gray-500 truncate">NPS</dt>
                                        <dd className="mt-1 text-3xl font-semibold text-gray-900">{metrics.nps || 0}</dd>
                                    </div>
                                </div>
                            </div>

                            {/* Quality Trend Charts */}
                            <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">CSAT Trends</h3>
                                        <Chart type="line" data={metrics.csat_trends} />
                                    </div>
                                </div>

                                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                    <div className="p-6">
                                        <h3 className="text-lg font-medium text-gray-900 mb-4">Quality Score Trends</h3>
                                        <Chart type="line" data={metrics.quality_trends} />
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