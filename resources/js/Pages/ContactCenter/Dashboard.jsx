import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';

// Placeholder for real-time charts - Replace with your preferred charting library
const Chart = ({ data, type }) => (
    <div className="h-48 bg-gray-50 rounded-lg flex items-center justify-center">
        <p className="text-gray-500">Chart Placeholder: {type}</p>
    </div>
);

export default function Dashboard({ auth, stats = {}, activeAgents = [], queuedCalls = [], ongoingCalls = [] }) {
    const [agentStatus, setAgentStatus] = useState('offline');
    const [selectedCall, setSelectedCall] = useState(null);

    // Simulate real-time updates
    useEffect(() => {
        const interval = setInterval(() => {
            // In a real implementation, this would be replaced with WebSocket updates
        }, 5000);

        return () => clearInterval(interval);
    }, []);

    const handleStatusChange = (newStatus) => {
        setAgentStatus(newStatus);
        // In a real implementation, this would update the server
    };

    const handleCallAction = (action, call) => {
        // Handle call actions (accept, transfer, end, etc.)
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Contact Center Dashboard
                    </h2>
                    <div className="flex items-center space-x-4">
                        <span className="text-sm text-gray-500">Agent Status:</span>
                        <select
                            value={agentStatus}
                            onChange={(e) => handleStatusChange(e.target.value)}
                            className="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="online">Online</option>
                            <option value="break">On Break</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
                </div>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Real-time Statistics */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900">Active Calls</h3>
                                <p className="mt-2 text-3xl font-semibold text-indigo-600">
                                    {stats.active_calls || 0}
                                </p>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900">Queued Calls</h3>
                                <p className="mt-2 text-3xl font-semibold text-yellow-600">
                                    {stats.queued_calls || 0}
                                </p>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900">Active Agents</h3>
                                <p className="mt-2 text-3xl font-semibold text-green-600">
                                    {stats.active_agents || 0}
                                </p>
                            </div>
                        </div>

                        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-medium text-gray-900">Avg. Wait Time</h3>
                                <p className="mt-2 text-3xl font-semibold text-gray-900">
                                    {stats.avg_wait_time || '0:00'}
                                </p>
                            </div>
                        </div>
                    </div>

                    {/* Main Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Call Queue */}
                        <div className="lg:col-span-2">
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Call Queue</h3>
                                    <div className="space-y-4">
                                        {queuedCalls.map((call) => (
                                            <div
                                                key={call.id}
                                                className="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                            >
                                                <div>
                                                    <p className="font-medium text-gray-900">{call.caller_number}</p>
                                                    <p className="text-sm text-gray-500">
                                                        Waiting for {call.wait_time} | Queue Position: {call.position}
                                                    </p>
                                                </div>
                                                {agentStatus === 'online' && (
                                                    <Button
                                                        onClick={() => handleCallAction('accept', call)}
                                                        className="bg-green-600 hover:bg-green-700"
                                                    >
                                                        Accept Call
                                                    </Button>
                                                )}
                                            </div>
                                        ))}
                                        {queuedCalls.length === 0 && (
                                            <p className="text-center text-gray-500 py-4">
                                                No calls in queue
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Active Calls */}
                            <div className="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Active Calls</h3>
                                    <div className="space-y-4">
                                        {ongoingCalls.map((call) => (
                                            <div
                                                key={call.id}
                                                className="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                            >
                                                <div>
                                                    <p className="font-medium text-gray-900">{call.caller_number}</p>
                                                    <p className="text-sm text-gray-500">
                                                        Duration: {call.duration} | Agent: {call.agent_name}
                                                    </p>
                                                </div>
                                                <div className="flex space-x-2">
                                                    <Button
                                                        onClick={() => handleCallAction('transfer', call)}
                                                        className="bg-yellow-600 hover:bg-yellow-700"
                                                    >
                                                        Transfer
                                                    </Button>
                                                    <Button
                                                        onClick={() => handleCallAction('end', call)}
                                                        className="bg-red-600 hover:bg-red-700"
                                                    >
                                                        End Call
                                                    </Button>
                                                </div>
                                            </div>
                                        ))}
                                        {ongoingCalls.length === 0 && (
                                            <p className="text-center text-gray-500 py-4">
                                                No active calls
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Right Sidebar */}
                        <div className="space-y-6">
                            {/* Online Agents */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Online Agents</h3>
                                    <div className="space-y-3">
                                        {activeAgents.map((agent) => (
                                            <div
                                                key={agent.id}
                                                className="flex items-center justify-between py-2"
                                            >
                                                <div className="flex items-center">
                                                    <span className={`h-2.5 w-2.5 rounded-full ${
                                                        agent.status === 'online' ? 'bg-green-600' :
                                                        agent.status === 'break' ? 'bg-yellow-600' :
                                                        'bg-gray-600'
                                                    } mr-2`}></span>
                                                    <span className="text-sm font-medium text-gray-900">
                                                        {agent.name}
                                                    </span>
                                                </div>
                                                <span className="text-sm text-gray-500">
                                                    {agent.active_calls} calls
                                                </span>
                                            </div>
                                        ))}
                                        {activeAgents.length === 0 && (
                                            <p className="text-center text-gray-500 py-2">
                                                No agents online
                                            </p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Today's Stats */}
                            <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div className="p-6">
                                    <h3 className="text-lg font-medium text-gray-900 mb-4">Today's Stats</h3>
                                    <Chart type="line" data={stats.today_call_volume} />
                                    <div className="mt-4 space-y-2">
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-500">Total Calls:</span>
                                            <span className="font-medium text-gray-900">{stats.total_calls}</span>
                                        </div>
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-500">Avg. Handle Time:</span>
                                            <span className="font-medium text-gray-900">{stats.avg_handle_time}</span>
                                        </div>
                                        <div className="flex justify-between text-sm">
                                            <span className="text-gray-500">Abandoned Rate:</span>
                                            <span className="font-medium text-gray-900">{stats.abandoned_rate}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}