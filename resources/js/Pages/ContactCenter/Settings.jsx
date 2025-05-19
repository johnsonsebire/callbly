import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function Settings({ auth, settings = {}, agents = [], teams = [] }) {
    const [activeTab, setActiveTab] = useState('routing');
    const { data, setData, post, processing } = useForm({
        routing_strategy: settings.routing_strategy || 'round-robin',
        queue_limit: settings.queue_limit || 50,
        max_wait_time: settings.max_wait_time || 300,
        wrap_up_time: settings.wrap_up_time || 30,
        music_on_hold: settings.music_on_hold || 'default',
        overflow_destination: settings.overflow_destination || 'voicemail',
        ivr_menu: settings.ivr_menu || [],
        operating_hours: settings.operating_hours || {
            monday: { enabled: true, start: '09:00', end: '17:00' },
            tuesday: { enabled: true, start: '09:00', end: '17:00' },
            wednesday: { enabled: true, start: '09:00', end: '17:00' },
            thursday: { enabled: true, start: '09:00', end: '17:00' },
            friday: { enabled: true, start: '09:00', end: '17:00' },
            saturday: { enabled: false, start: '09:00', end: '17:00' },
            sunday: { enabled: false, start: '09:00', end: '17:00' }
        },
        teams: settings.teams || []
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('contact-center.settings.update'));
    };

    const addIVROption = () => {
        setData('ivr_menu', [
            ...data.ivr_menu,
            {
                id: Date.now(),
                digit: String(data.ivr_menu.length + 1),
                message: '',
                action: 'transfer',
                destination: ''
            }
        ]);
    };

    const updateIVROption = (id, updates) => {
        setData('ivr_menu', data.ivr_menu.map(option =>
            option.id === id ? { ...option, ...updates } : option
        ));
    };

    const removeIVROption = (id) => {
        setData('ivr_menu', data.ivr_menu.filter(option => option.id !== id));
    };

    const addTeam = () => {
        setData('teams', [
            ...data.teams,
            {
                id: Date.now(),
                name: '',
                agents: [],
                priority: data.teams.length + 1
            }
        ]);
    };

    const updateTeam = (id, updates) => {
        setData('teams', data.teams.map(team =>
            team.id === id ? { ...team, ...updates } : team
        ));
    };

    const removeTeam = (id) => {
        setData('teams', data.teams.filter(team => team.id !== id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Contact Center Settings
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {/* Settings Tabs */}
                            <div className="border-b border-gray-200 mb-6">
                                <nav className="-mb-px flex space-x-8">
                                    {['routing', 'ivr', 'teams', 'hours'].map((tab) => (
                                        <button
                                            key={tab}
                                            onClick={() => setActiveTab(tab)}
                                            className={`${
                                                activeTab === tab
                                                    ? 'border-indigo-500 text-indigo-600'
                                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                            } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm capitalize`}
                                        >
                                            {tab.replace('_', ' ')}
                                        </button>
                                    ))}
                                </nav>
                            </div>

                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Routing Settings */}
                                {activeTab === 'routing' && (
                                    <div className="space-y-6">
                                        <div>
                                            <Label forInput="routing_strategy" value="Call Routing Strategy" />
                                            <select
                                                id="routing_strategy"
                                                value={data.routing_strategy}
                                                onChange={e => setData('routing_strategy', e.target.value)}
                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            >
                                                <option value="round-robin">Round Robin</option>
                                                <option value="least-recent">Least Recent</option>
                                                <option value="fewest-calls">Fewest Calls</option>
                                                <option value="skill-based">Skill Based</option>
                                            </select>
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <Label forInput="queue_limit" value="Maximum Queue Size" />
                                                <Input
                                                    type="number"
                                                    id="queue_limit"
                                                    value={data.queue_limit}
                                                    onChange={e => setData('queue_limit', e.target.value)}
                                                    className="mt-1 block w-full"
                                                    min="1"
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="max_wait_time" value="Maximum Wait Time (seconds)" />
                                                <Input
                                                    type="number"
                                                    id="max_wait_time"
                                                    value={data.max_wait_time}
                                                    onChange={e => setData('max_wait_time', e.target.value)}
                                                    className="mt-1 block w-full"
                                                    min="0"
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="wrap_up_time" value="Agent Wrap-up Time (seconds)" />
                                                <Input
                                                    type="number"
                                                    id="wrap_up_time"
                                                    value={data.wrap_up_time}
                                                    onChange={e => setData('wrap_up_time', e.target.value)}
                                                    className="mt-1 block w-full"
                                                    min="0"
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="overflow_destination" value="Queue Overflow Action" />
                                                <select
                                                    id="overflow_destination"
                                                    value={data.overflow_destination}
                                                    onChange={e => setData('overflow_destination', e.target.value)}
                                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                    <option value="voicemail">Send to Voicemail</option>
                                                    <option value="ivr">Return to IVR</option>
                                                    <option value="hangup">Hang Up</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* IVR Menu Settings */}
                                {activeTab === 'ivr' && (
                                    <div className="space-y-6">
                                        <div className="flex justify-between items-center">
                                            <h3 className="text-lg font-medium text-gray-900">IVR Menu Options</h3>
                                            <Button type="button" onClick={addIVROption}>
                                                Add Menu Option
                                            </Button>
                                        </div>

                                        <div className="space-y-4">
                                            {data.ivr_menu.map((option) => (
                                                <div key={option.id} className="bg-gray-50 p-4 rounded-lg">
                                                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                        <div>
                                                            <Label forInput={`digit-${option.id}`} value="Digit" />
                                                            <Input
                                                                type="text"
                                                                id={`digit-${option.id}`}
                                                                value={option.digit}
                                                                onChange={e => updateIVROption(option.id, { digit: e.target.value })}
                                                                className="mt-1"
                                                                maxLength={1}
                                                            />
                                                        </div>

                                                        <div className="md:col-span-2">
                                                            <Label forInput={`message-${option.id}`} value="Message" />
                                                            <Input
                                                                type="text"
                                                                id={`message-${option.id}`}
                                                                value={option.message}
                                                                onChange={e => updateIVROption(option.id, { message: e.target.value })}
                                                                className="mt-1"
                                                                placeholder="Press {N} for..."
                                                            />
                                                        </div>

                                                        <div>
                                                            <Label forInput={`action-${option.id}`} value="Action" />
                                                            <select
                                                                id={`action-${option.id}`}
                                                                value={option.action}
                                                                onChange={e => updateIVROption(option.id, { action: e.target.value })}
                                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                            >
                                                                <option value="transfer">Transfer to Team</option>
                                                                <option value="voicemail">Voicemail</option>
                                                                <option value="submenu">Sub-menu</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {option.action === 'transfer' && (
                                                        <div className="mt-4">
                                                            <Label forInput={`destination-${option.id}`} value="Transfer to Team" />
                                                            <select
                                                                id={`destination-${option.id}`}
                                                                value={option.destination}
                                                                onChange={e => updateIVROption(option.id, { destination: e.target.value })}
                                                                className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                            >
                                                                <option value="">Select Team</option>
                                                                {data.teams.map(team => (
                                                                    <option key={team.id} value={team.id}>
                                                                        {team.name}
                                                                    </option>
                                                                ))}
                                                            </select>
                                                        </div>
                                                    )}

                                                    <div className="mt-4 flex justify-end">
                                                        <Button
                                                            type="button"
                                                            onClick={() => removeIVROption(option.id)}
                                                            className="bg-red-600 hover:bg-red-700"
                                                        >
                                                            Remove
                                                        </Button>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}

                                {/* Team Settings */}
                                {activeTab === 'teams' && (
                                    <div className="space-y-6">
                                        <div className="flex justify-between items-center">
                                            <h3 className="text-lg font-medium text-gray-900">Agent Teams</h3>
                                            <Button type="button" onClick={addTeam}>
                                                Add Team
                                            </Button>
                                        </div>

                                        <div className="space-y-4">
                                            {data.teams.map((team) => (
                                                <div key={team.id} className="bg-gray-50 p-4 rounded-lg">
                                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <Label forInput={`team-name-${team.id}`} value="Team Name" />
                                                            <Input
                                                                type="text"
                                                                id={`team-name-${team.id}`}
                                                                value={team.name}
                                                                onChange={e => updateTeam(team.id, { name: e.target.value })}
                                                                className="mt-1"
                                                            />
                                                        </div>

                                                        <div>
                                                            <Label forInput={`team-priority-${team.id}`} value="Priority Level" />
                                                            <Input
                                                                type="number"
                                                                id={`team-priority-${team.id}`}
                                                                value={team.priority}
                                                                onChange={e => updateTeam(team.id, { priority: e.target.value })}
                                                                className="mt-1"
                                                                min="1"
                                                            />
                                                        </div>
                                                    </div>

                                                    <div className="mt-4">
                                                        <Label value="Team Members" />
                                                        <div className="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                                            {agents.map((agent) => (
                                                                <label key={agent.id} className="flex items-center">
                                                                    <input
                                                                        type="checkbox"
                                                                        checked={team.agents.includes(agent.id)}
                                                                        onChange={(e) => {
                                                                            const newAgents = e.target.checked
                                                                                ? [...team.agents, agent.id]
                                                                                : team.agents.filter(id => id !== agent.id);
                                                                            updateTeam(team.id, { agents: newAgents });
                                                                        }}
                                                                        className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                    />
                                                                    <span className="ml-2 text-sm text-gray-600">
                                                                        {agent.name}
                                                                    </span>
                                                                </label>
                                                            ))}
                                                        </div>
                                                    </div>

                                                    <div className="mt-4 flex justify-end">
                                                        <Button
                                                            type="button"
                                                            onClick={() => removeTeam(team.id)}
                                                            className="bg-red-600 hover:bg-red-700"
                                                        >
                                                            Remove Team
                                                        </Button>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}

                                {/* Operating Hours */}
                                {activeTab === 'hours' && (
                                    <div className="space-y-6">
                                        <h3 className="text-lg font-medium text-gray-900">Operating Hours</h3>
                                        <div className="space-y-4">
                                            {Object.entries(data.operating_hours).map(([day, hours]) => (
                                                <div key={day} className="flex items-center space-x-4">
                                                    <label className="flex items-center min-w-[100px]">
                                                        <input
                                                            type="checkbox"
                                                            checked={hours.enabled}
                                                            onChange={e => setData('operating_hours', {
                                                                ...data.operating_hours,
                                                                [day]: { ...hours, enabled: e.target.checked }
                                                            })}
                                                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                        />
                                                        <span className="ml-2 text-sm text-gray-600 capitalize">{day}</span>
                                                    </label>
                                                    <div className="flex items-center space-x-2">
                                                        <Input
                                                            type="time"
                                                            value={hours.start}
                                                            onChange={e => setData('operating_hours', {
                                                                ...data.operating_hours,
                                                                [day]: { ...hours, start: e.target.value }
                                                            })}
                                                            disabled={!hours.enabled}
                                                            className="w-32"
                                                        />
                                                        <span className="text-gray-500">to</span>
                                                        <Input
                                                            type="time"
                                                            value={hours.end}
                                                            onChange={e => setData('operating_hours', {
                                                                ...data.operating_hours,
                                                                [day]: { ...hours, end: e.target.value }
                                                            })}
                                                            disabled={!hours.enabled}
                                                            className="w-32"
                                                        />
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}

                                <div className="flex justify-end pt-6">
                                    <Button type="submit" processing={processing}>
                                        Save Settings
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}