import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function MyNumbers({ auth, numbers = [], timezones = [] }) {
    const [editingNumber, setEditingNumber] = useState(null);
    
    const { data, setData, put, processing, errors } = useForm({
        forwarding_number: '',
        voicemail_enabled: false,
        recording_enabled: false,
        timezone: '',
        business_hours: {
            monday: { enabled: true, start: '09:00', end: '17:00' },
            tuesday: { enabled: true, start: '09:00', end: '17:00' },
            wednesday: { enabled: true, start: '09:00', end: '17:00' },
            thursday: { enabled: true, start: '09:00', end: '17:00' },
            friday: { enabled: true, start: '09:00', end: '17:00' },
            saturday: { enabled: false, start: '09:00', end: '17:00' },
            sunday: { enabled: false, start: '09:00', end: '17:00' }
        }
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('virtual-numbers.update', editingNumber.id), {
            onSuccess: () => setEditingNumber(null)
        });
    };

    const handleBusinessHourChange = (day, field, value) => {
        setData('business_hours', {
            ...data.business_hours,
            [day]: {
                ...data.business_hours[day],
                [field]: value
            }
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        My Virtual Numbers
                    </h2>
                    <Link
                        href={route('virtual-numbers.browse')}
                        className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Get New Number
                    </Link>
                </div>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Number
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Forwarding To
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Features
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Next Billing
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {numbers.map((number) => (
                                            <React.Fragment key={number.id}>
                                                <tr>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <div className="text-sm font-medium text-gray-900">
                                                            {number.formatted_number}
                                                        </div>
                                                        <div className="text-sm text-gray-500">
                                                            {number.country} - {number.region}
                                                        </div>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                            number.active 
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-red-100 text-red-800'
                                                        }`}>
                                                            {number.active ? 'Active' : 'Inactive'}
                                                        </span>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {number.forwarding_number || 'Not configured'}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap">
                                                        <div className="flex space-x-2">
                                                            {number.voicemail_enabled && (
                                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    Voicemail
                                                                </span>
                                                            )}
                                                            {number.recording_enabled && (
                                                                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                    Recording
                                                                </span>
                                                            )}
                                                        </div>
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {number.next_billing_date}
                                                    </td>
                                                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <button
                                                            onClick={() => {
                                                                setEditingNumber(number);
                                                                setData({
                                                                    forwarding_number: number.forwarding_number || '',
                                                                    voicemail_enabled: number.voicemail_enabled || false,
                                                                    recording_enabled: number.recording_enabled || false,
                                                                    timezone: number.timezone || '',
                                                                    business_hours: number.business_hours || data.business_hours
                                                                });
                                                            }}
                                                            className="text-indigo-600 hover:text-indigo-900"
                                                        >
                                                            Configure
                                                        </button>
                                                    </td>
                                                </tr>
                                                {editingNumber?.id === number.id && (
                                                    <tr>
                                                        <td colSpan="6" className="px-6 py-4">
                                                            <form onSubmit={handleSubmit} className="space-y-6">
                                                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                                    <div>
                                                                        <Label forInput="forwarding_number" value="Forward Calls To" />
                                                                        <Input
                                                                            type="tel"
                                                                            id="forwarding_number"
                                                                            value={data.forwarding_number}
                                                                            onChange={e => setData('forwarding_number', e.target.value)}
                                                                            className="mt-1 block w-full"
                                                                            placeholder="+1234567890"
                                                                        />
                                                                        {errors.forwarding_number && (
                                                                            <p className="mt-1 text-sm text-red-600">
                                                                                {errors.forwarding_number}
                                                                            </p>
                                                                        )}
                                                                    </div>

                                                                    <div>
                                                                        <Label forInput="timezone" value="Timezone" />
                                                                        <select
                                                                            id="timezone"
                                                                            value={data.timezone}
                                                                            onChange={e => setData('timezone', e.target.value)}
                                                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                        >
                                                                            <option value="">Select Timezone</option>
                                                                            {timezones.map(tz => (
                                                                                <option key={tz} value={tz}>{tz}</option>
                                                                            ))}
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div className="flex space-x-4">
                                                                    <label className="flex items-center">
                                                                        <input
                                                                            type="checkbox"
                                                                            checked={data.voicemail_enabled}
                                                                            onChange={e => setData('voicemail_enabled', e.target.checked)}
                                                                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                        />
                                                                        <span className="ml-2 text-sm text-gray-600">Enable Voicemail</span>
                                                                    </label>

                                                                    <label className="flex items-center">
                                                                        <input
                                                                            type="checkbox"
                                                                            checked={data.recording_enabled}
                                                                            onChange={e => setData('recording_enabled', e.target.checked)}
                                                                            className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                        />
                                                                        <span className="ml-2 text-sm text-gray-600">Enable Call Recording</span>
                                                                    </label>
                                                                </div>

                                                                <div>
                                                                    <h4 className="text-sm font-medium text-gray-900 mb-4">Business Hours</h4>
                                                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                        {Object.entries(data.business_hours).map(([day, hours]) => (
                                                                            <div key={day} className="flex items-center space-x-4">
                                                                                <label className="flex items-center min-w-[100px]">
                                                                                    <input
                                                                                        type="checkbox"
                                                                                        checked={hours.enabled}
                                                                                        onChange={e => handleBusinessHourChange(day, 'enabled', e.target.checked)}
                                                                                        className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                                    />
                                                                                    <span className="ml-2 text-sm text-gray-600 capitalize">{day}</span>
                                                                                </label>
                                                                                <div className="flex items-center space-x-2">
                                                                                    <Input
                                                                                        type="time"
                                                                                        value={hours.start}
                                                                                        onChange={e => handleBusinessHourChange(day, 'start', e.target.value)}
                                                                                        disabled={!hours.enabled}
                                                                                        className="w-32"
                                                                                    />
                                                                                    <span className="text-gray-500">to</span>
                                                                                    <Input
                                                                                        type="time"
                                                                                        value={hours.end}
                                                                                        onChange={e => handleBusinessHourChange(day, 'end', e.target.value)}
                                                                                        disabled={!hours.enabled}
                                                                                        className="w-32"
                                                                                    />
                                                                                </div>
                                                                            </div>
                                                                        ))}
                                                                    </div>
                                                                </div>

                                                                <div className="flex justify-end space-x-3">
                                                                    <Button
                                                                        type="button"
                                                                        onClick={() => setEditingNumber(null)}
                                                                        className="bg-white"
                                                                    >
                                                                        Cancel
                                                                    </Button>
                                                                    <Button type="submit" processing={processing}>
                                                                        Save Changes
                                                                    </Button>
                                                                </div>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                )}
                                            </React.Fragment>
                                        ))}
                                        {numbers.length === 0 && (
                                            <tr>
                                                <td colSpan="6" className="px-6 py-4 text-center text-gray-500">
                                                    You don't have any virtual numbers yet.{' '}
                                                    <Link href={route('virtual-numbers.browse')} className="text-indigo-600 hover:text-indigo-900">
                                                        Get your first number
                                                    </Link>
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