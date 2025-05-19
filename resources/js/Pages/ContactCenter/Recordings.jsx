import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function Recordings({ auth, recordings = [], filters = {} }) {
    const [selectedRecordings, setSelectedRecordings] = useState([]);
    const [showPlayer, setShowPlayer] = useState(false);
    const [currentRecording, setCurrentRecording] = useState(null);

    const handleSearch = (e) => {
        e.preventDefault();
        // Handle search implementation
    };

    const handleDownload = (recordings) => {
        // Handle download implementation
    };

    const handleDelete = (recordings) => {
        // Handle delete implementation
    };

    const playRecording = (recording) => {
        setCurrentRecording(recording);
        setShowPlayer(true);
    };

    const toggleRecordingSelection = (recording) => {
        setSelectedRecordings(prev => 
            prev.includes(recording.id)
                ? prev.filter(id => id !== recording.id)
                : [...prev, recording.id]
        );
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Call Recordings & Voicemails
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Search and Filter */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6">
                            <form onSubmit={handleSearch} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div>
                                        <Label forInput="type" value="Type" />
                                        <select
                                            id="type"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="all">All</option>
                                            <option value="call">Call Recordings</option>
                                            <option value="voicemail">Voicemails</option>
                                        </select>
                                    </div>

                                    <div>
                                        <Label forInput="date_range" value="Date Range" />
                                        <select
                                            id="date_range"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="today">Today</option>
                                            <option value="yesterday">Yesterday</option>
                                            <option value="last_7_days">Last 7 Days</option>
                                            <option value="last_30_days">Last 30 Days</option>
                                            <option value="custom">Custom Range</option>
                                        </select>
                                    </div>

                                    <div>
                                        <Label forInput="agent" value="Agent" />
                                        <select
                                            id="agent"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">All Agents</option>
                                        </select>
                                    </div>

                                    <div>
                                        <Label forInput="duration" value="Duration" />
                                        <select
                                            id="duration"
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">Any Duration</option>
                                            <option value="short">< 1 minute</option>
                                            <option value="medium">1-5 minutes</option>
                                            <option value="long">> 5 minutes</option>
                                        </select>
                                    </div>
                                </div>

                                <div className="flex justify-end">
                                    <Button type="submit">
                                        Search
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Recordings List */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {/* Bulk Actions */}
                            {selectedRecordings.length > 0 && (
                                <div className="mb-4 flex justify-between items-center">
                                    <span className="text-sm text-gray-700">
                                        {selectedRecordings.length} selected
                                    </span>
                                    <div className="space-x-2">
                                        <Button
                                            onClick={() => handleDownload(selectedRecordings)}
                                            className="bg-indigo-600 hover:bg-indigo-700"
                                        >
                                            Download Selected
                                        </Button>
                                        <Button
                                            onClick={() => handleDelete(selectedRecordings)}
                                            className="bg-red-600 hover:bg-red-700"
                                        >
                                            Delete Selected
                                        </Button>
                                    </div>
                                </div>
                            )}

                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="relative px-6 py-3">
                                                <input
                                                    type="checkbox"
                                                    className="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    checked={selectedRecordings.length === recordings.length}
                                                    onChange={() => {
                                                        if (selectedRecordings.length === recordings.length) {
                                                            setSelectedRecordings([]);
                                                        } else {
                                                            setSelectedRecordings(recordings.map(r => r.id));
                                                        }
                                                    }}
                                                />
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Type
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date/Time
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Number
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Agent
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Duration
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {recordings.map((recording) => (
                                            <tr key={recording.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <input
                                                        type="checkbox"
                                                        className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        checked={selectedRecordings.includes(recording.id)}
                                                        onChange={() => toggleRecordingSelection(recording)}
                                                    />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        recording.type === 'call'
                                                            ? 'bg-blue-100 text-blue-800'
                                                            : 'bg-purple-100 text-purple-800'
                                                    }`}>
                                                        {recording.type === 'call' ? 'Call Recording' : 'Voicemail'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">{recording.date}</div>
                                                    <div className="text-sm text-gray-500">{recording.time}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {recording.phone_number}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {recording.agent_name}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {recording.duration}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div className="flex justify-end space-x-2">
                                                        <button
                                                            onClick={() => playRecording(recording)}
                                                            className="text-indigo-600 hover:text-indigo-900"
                                                        >
                                                            Play
                                                        </button>
                                                        <button
                                                            onClick={() => handleDownload([recording.id])}
                                                            className="text-green-600 hover:text-green-900"
                                                        >
                                                            Download
                                                        </button>
                                                        <button
                                                            onClick={() => handleDelete([recording.id])}
                                                            className="text-red-600 hover:text-red-900"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {/* Audio Player Modal */}
                    {showPlayer && currentRecording && (
                        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                            <div className="fixed inset-0 z-10 overflow-y-auto">
                                <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                    <div className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                        <div>
                                            <div className="mt-3 text-center sm:mt-5">
                                                <h3 className="text-base font-semibold leading-6 text-gray-900">
                                                    Playing Recording
                                                </h3>
                                                <div className="mt-4">
                                                    <audio
                                                        controls
                                                        className="w-full"
                                                        src={currentRecording.url}
                                                    >
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>
                                            </div>
                                        </div>
                                        <div className="mt-5 sm:mt-6">
                                            <Button
                                                type="button"
                                                className="w-full"
                                                onClick={() => {
                                                    setShowPlayer(false);
                                                    setCurrentRecording(null);
                                                }}
                                            >
                                                Close
                                            </Button>
                                        </div>
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