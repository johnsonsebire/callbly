import React, { useState, useEffect } from 'react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';
import TextArea from '../../Components/TextArea';

export default function Compose({ auth, senderIds = [] }) {
    const [recipientType, setRecipientType] = useState('single');
    const [messagePreview, setMessagePreview] = useState('');
    const [characterCount, setCharacterCount] = useState(0);
    const [messageCount, setMessageCount] = useState(1);
    const [estimatedCost, setEstimatedCost] = useState(0);
    const [recipientCount, setRecipientCount] = useState(0);

    const { data, setData, post, processing, errors } = useForm({
        sender_id: '',
        message: '',
        recipients: '',
        schedule_time: '',
        file: null
    });

    const calculateMessageStats = (message) => {
        const length = message.length;
        setCharacterCount(length);
        // SMS length calculations based on GSM 7-bit encoding
        const singleSMSLength = 160;
        const concatenatedSMSLength = 153;
        
        if (length <= singleSMSLength) {
            setMessageCount(1);
        } else {
            setMessageCount(Math.ceil(length / concatenatedSMSLength));
        }
    };

    const calculateCost = () => {
        // This is a placeholder calculation. Actual cost should be based on your pricing model
        const costPerMessage = 0.01; // Example cost per message segment
        const totalRecipients = recipientType === 'single' 
            ? data.recipients.split(',').length 
            : recipientCount;
        
        setEstimatedCost(totalRecipients * messageCount * costPerMessage);
    };

    useEffect(() => {
        calculateMessageStats(data.message);
        calculateCost();
    }, [data.message, recipientCount, data.recipients]);

    const handleFileUpload = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('file', file);
            // For CSV files, you might want to read and count the rows
            const reader = new FileReader();
            reader.onload = (e) => {
                const text = e.target.result;
                const rows = text.split('\n').length - 1; // Subtract header row
                setRecipientCount(rows);
            };
            reader.readAsText(file);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('sms.send'));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Compose SMS Campaign
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <form onSubmit={handleSubmit}>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                {/* Left Column - Message Composition */}
                                <div className="md:col-span-2 space-y-6">
                                    <div>
                                        <Label forInput="sender_id" value="Sender ID" required />
                                        <select
                                            name="sender_id"
                                            value={data.sender_id}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            onChange={e => setData('sender_id', e.target.value)}
                                        >
                                            <option value="">Select a sender ID</option>
                                            {senderIds.map(id => (
                                                <option key={id} value={id}>{id}</option>
                                            ))}
                                        </select>
                                        {errors.sender_id && <p className="text-red-500 text-xs mt-1">{errors.sender_id}</p>}
                                    </div>

                                    <div>
                                        <Label forInput="message" value="Message Content" required />
                                        <TextArea
                                            name="message"
                                            value={data.message}
                                            className="mt-1 block w-full"
                                            onChange={e => {
                                                setData('message', e.target.value);
                                                setMessagePreview(e.target.value);
                                            }}
                                            rows={6}
                                        />
                                        {errors.message && <p className="text-red-500 text-xs mt-1">{errors.message}</p>}
                                        <div className="mt-2 text-sm text-gray-500">
                                            {characterCount} characters | {messageCount} message(s)
                                        </div>
                                    </div>

                                    <div>
                                        <Label forInput="schedule_time" value="Schedule (Optional)" />
                                        <Input
                                            type="datetime-local"
                                            name="schedule_time"
                                            value={data.schedule_time}
                                            className="mt-1 block w-full"
                                            onChange={e => setData('schedule_time', e.target.value)}
                                        />
                                    </div>
                                </div>

                                {/* Right Column - Recipients and Preview */}
                                <div className="space-y-6">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Recipient Type</label>
                                        <div className="mt-2 space-x-4">
                                            <label className="inline-flex items-center">
                                                <input
                                                    type="radio"
                                                    className="form-radio"
                                                    name="recipient_type"
                                                    value="single"
                                                    checked={recipientType === 'single'}
                                                    onChange={e => setRecipientType(e.target.value)}
                                                />
                                                <span className="ml-2">Single/Multiple</span>
                                            </label>
                                            <label className="inline-flex items-center">
                                                <input
                                                    type="radio"
                                                    className="form-radio"
                                                    name="recipient_type"
                                                    value="file"
                                                    checked={recipientType === 'file'}
                                                    onChange={e => setRecipientType(e.target.value)}
                                                />
                                                <span className="ml-2">Upload File</span>
                                            </label>
                                        </div>
                                    </div>

                                    {recipientType === 'single' ? (
                                        <div>
                                            <Label forInput="recipients" value="Recipients" required />
                                            <TextArea
                                                name="recipients"
                                                value={data.recipients}
                                                className="mt-1 block w-full"
                                                placeholder="Enter phone numbers separated by commas"
                                                onChange={e => setData('recipients', e.target.value)}
                                                rows={4}
                                            />
                                            {errors.recipients && <p className="text-red-500 text-xs mt-1">{errors.recipients}</p>}
                                        </div>
                                    ) : (
                                        <div>
                                            <Label forInput="file" value="Upload Recipients (CSV)" required />
                                            <Input
                                                type="file"
                                                accept=".csv"
                                                onChange={handleFileUpload}
                                                className="mt-1 block w-full"
                                            />
                                            {errors.file && <p className="text-red-500 text-xs mt-1">{errors.file}</p>}
                                            {recipientCount > 0 && (
                                                <p className="text-sm text-gray-600 mt-2">
                                                    {recipientCount} recipients found in file
                                                </p>
                                            )}
                                        </div>
                                    )}

                                    <div className="bg-gray-50 rounded-lg p-4">
                                        <h3 className="text-sm font-medium text-gray-900 mb-2">Message Preview</h3>
                                        <div className="bg-white rounded border border-gray-200 p-3 text-sm">
                                            {messagePreview || 'Your message preview will appear here'}
                                        </div>
                                    </div>

                                    <div className="bg-gray-50 rounded-lg p-4">
                                        <h3 className="text-sm font-medium text-gray-900 mb-2">Estimated Cost</h3>
                                        <p className="text-2xl font-bold text-gray-900">
                                            ${estimatedCost.toFixed(2)}
                                        </p>
                                        <p className="text-sm text-gray-500 mt-1">
                                            Based on current rates and message length
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-6 flex justify-end space-x-3">
                                <Button
                                    type="button"
                                    className="bg-white"
                                    onClick={() => window.history.back()}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" processing={processing}>
                                    {data.schedule_time ? 'Schedule Campaign' : 'Send Now'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}