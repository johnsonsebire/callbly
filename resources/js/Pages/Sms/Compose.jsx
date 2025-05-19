import React, { useState, useEffect } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';
import ValidationErrors from '../../Components/ValidationErrors';
import TextArea from '../../Components/TextArea';
import Select from '../../Components/Select';

export default function SmsCompose({ auth, senderNames = [], type = 'single', creditsInfo = {} }) {
    const { errors, flash } = usePage().props;
    
    const [messageType, setMessageType] = useState(type);
    const [showFileUpload, setShowFileUpload] = useState(false);
    const [previewData, setPreviewData] = useState([]);
    const [characterCount, setCharacterCount] = useState(0);
    const [messageCount, setMessageCount] = useState(1);
    const [estimatedCost, setEstimatedCost] = useState(0);
    const [recipientCount, setRecipientCount] = useState(messageType === 'single' ? 1 : 0);
    
    // SMS pricing information
    const smsPricing = {
        credits: creditsInfo.credits || 0,
        perMessage: creditsInfo.perMessage || 1,
        costPerSms: creditsInfo.costPerSms || 0.02,
        currency: creditsInfo.currency || 'USD'
    };
    
    // Form state
    const { data, setData, post, processing, reset } = useForm({
        sender_id: senderNames.length > 0 ? senderNames[0].id : '',
        message: '',
        recipients: '',
        schedule_date: '',
        schedule_time: '',
        file: null,
    });
    
    // Handle message character count and segments
    useEffect(() => {
        const count = data.message.length;
        setCharacterCount(count);
        
        // SMS segments calculation (160 chars for regular, 70 for unicode)
        // This is a simplified version - actual SMS segment calculation can be more complex
        const hasUnicode = /[^\u0000-\u007F]/.test(data.message);
        const charsPerSegment = hasUnicode ? 70 : 160;
        const segmentCount = count > 0 ? Math.ceil(count / charsPerSegment) : 1;
        setMessageCount(segmentCount);
        
        // Calculate estimated cost
        calculateEstimatedCost(segmentCount, recipientCount);
    }, [data.message, recipientCount]);
    
    // Calculate cost when recipient count changes
    useEffect(() => {
        calculateEstimatedCost(messageCount, recipientCount);
    }, [recipientCount, messageCount]);
    
    // Handle recipient count change
    useEffect(() => {
        if (messageType === 'single') {
            setRecipientCount(1);
        } else {
            const recipients = data.recipients.split(',').filter(r => r.trim() !== '').length;
            setRecipientCount(recipients);
        }
    }, [data.recipients, messageType]);
    
    const calculateEstimatedCost = (segments, recipients) => {
        const totalMessages = segments * recipients;
        const totalCredits = totalMessages * smsPricing.perMessage;
        const cost = totalCredits * smsPricing.costPerSms;
        setEstimatedCost(cost);
    };
    
    const handleFileUpload = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('file', file);
            
            // Preview file contents (first few rows)
            const reader = new FileReader();
            reader.onload = (event) => {
                const content = event.target.result;
                const lines = content.split('\n').filter(line => line.trim() !== '');
                
                if (lines.length > 0) {
                    // Assume CSV format with header row
                    const preview = lines.slice(0, Math.min(5, lines.length)).map(line => {
                        const values = line.split(',');
                        return values[0]; // Just display the first column (phone numbers)
                    });
                    
                    setPreviewData(preview);
                    setRecipientCount(lines.length - 1); // Assuming first line is header
                }
            };
            reader.readAsText(file);
        }
    };
    
    const handleSubmit = (e) => {
        e.preventDefault();
        post(messageType === 'single' ? route('sms.send') : route('sms.send.campaign'));
    };
    
    const toggleMessageType = (type) => {
        setMessageType(type);
        if (type === 'single') {
            setShowFileUpload(false);
            setData('recipients', '');
            setRecipientCount(1);
        }
    };
    
    // Generate characters remaining text
    const getCharactersRemainingText = () => {
        const hasUnicode = /[^\u0000-\u007F]/.test(data.message);
        const charsPerSegment = hasUnicode ? 70 : 160;
        const currentSegment = messageCount;
        const charsUsedInCurrentSegment = characterCount % charsPerSegment || characterCount;
        const charsRemainingInSegment = (currentSegment * charsPerSegment) - characterCount;
        
        return `${characterCount} characters | ${messageCount} message(s) | ${charsRemainingInSegment} chars until next segment`;
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex flex-col md:flex-row md:items-center md:justify-between">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">Compose SMS</h2>
                </div>
            }
        >
            <div className="py-6">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {flash.success && (
                        <div className="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                            <div className="flex">
                                <div className="flex-shrink-0">
                                    <svg className="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                    </svg>
                                </div>
                                <div className="ml-3">
                                    <p className="text-sm text-green-700">{flash.success}</p>
                                </div>
                            </div>
                        </div>
                    )}
                    
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <ValidationErrors errors={errors} />
                            
                            {/* Message Type Tabs */}
                            <div className="mb-6">
                                <div className="sm:hidden">
                                    <Label forInput="message_type" value="Message Type" />
                                    <select
                                        id="message_type"
                                        className="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        value={messageType}
                                        onChange={(e) => toggleMessageType(e.target.value)}
                                    >
                                        <option value="single">Single SMS</option>
                                        <option value="campaign">Bulk Campaign</option>
                                    </select>
                                </div>
                                <div className="hidden sm:block">
                                    <div className="border-b border-gray-200">
                                        <nav className="-mb-px flex space-x-8" aria-label="Tabs">
                                            <button
                                                onClick={() => toggleMessageType('single')}
                                                className={`${
                                                    messageType === 'single'
                                                        ? 'border-indigo-500 text-indigo-600'
                                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                                } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm`}
                                            >
                                                Single SMS
                                            </button>
                                            <button
                                                onClick={() => toggleMessageType('campaign')}
                                                className={`${
                                                    messageType === 'campaign'
                                                        ? 'border-indigo-500 text-indigo-600'
                                                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                                } whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm`}
                                            >
                                                Bulk Campaign
                                            </button>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            
                            <form onSubmit={handleSubmit}>
                                <div className="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                    {/* Sender ID */}
                                    <div className="sm:col-span-3">
                                        <Label forInput="sender_id" value="Sender ID" />
                                        <div className="mt-1">
                                            {senderNames.length > 0 ? (
                                                <Select
                                                    name="sender_id"
                                                    value={data.sender_id}
                                                    className="block w-full"
                                                    onChange={e => setData('sender_id', e.target.value)}
                                                >
                                                    {senderNames.map(sender => (
                                                        <option key={sender.id} value={sender.id}>
                                                            {sender.name}
                                                        </option>
                                                    ))}
                                                </Select>
                                            ) : (
                                                <div className="flex items-center space-x-2">
                                                    <Input
                                                        type="text"
                                                        name="sender_id_placeholder"
                                                        value="No sender IDs available"
                                                        className="block w-full"
                                                        disabled
                                                    />
                                                    <Button
                                                        type="button"
                                                        className="flex items-center"
                                                        onClick={() => window.location.href = route('sms.sender-names')}
                                                    >
                                                        Register
                                                    </Button>
                                                </div>
                                            )}
                                            <p className="mt-2 text-sm text-gray-500">
                                                The sender ID that will appear on recipient's device
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {/* Message Scheduling (for campaigns) */}
                                    {messageType === 'campaign' && (
                                        <>
                                            <div className="sm:col-span-3">
                                                <Label forInput="schedule" value="Schedule (Optional)" />
                                                <div className="mt-1 flex space-x-2">
                                                    <Input
                                                        type="date"
                                                        name="schedule_date"
                                                        min={new Date().toISOString().split('T')[0]}
                                                        value={data.schedule_date}
                                                        className="block w-full"
                                                        onChange={e => setData('schedule_date', e.target.value)}
                                                    />
                                                    <Input
                                                        type="time"
                                                        name="schedule_time"
                                                        value={data.schedule_time}
                                                        className="block w-full"
                                                        onChange={e => setData('schedule_time', e.target.value)}
                                                    />
                                                </div>
                                                <p className="mt-2 text-sm text-gray-500">
                                                    Leave blank to send immediately
                                                </p>
                                            </div>
                                        </>
                                    )}

                                    {/* Recipients */}
                                    <div className="sm:col-span-6">
                                        <Label forInput="recipients" value="Recipients" />
                                        
                                        {messageType === 'single' ? (
                                            <div className="mt-1">
                                                <Input
                                                    type="text"
                                                    name="recipients"
                                                    value={data.recipients}
                                                    className="block w-full"
                                                    isFocused={true}
                                                    placeholder="Enter phone number with country code (e.g., 2341234567890)"
                                                    onChange={e => setData('recipients', e.target.value)}
                                                    required
                                                />
                                            </div>
                                        ) : (
                                            <div className="mt-1">
                                                {showFileUpload ? (
                                                    <div className="space-y-3">
                                                        <div className="flex items-center justify-center w-full">
                                                            <label
                                                                htmlFor="file-upload"
                                                                className="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100"
                                                            >
                                                                <div className="flex flex-col items-center justify-center pt-5 pb-6">
                                                                    <svg
                                                                        className="w-8 h-8 mb-4 text-gray-500"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                    >
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            strokeWidth={2}
                                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                                        />
                                                                    </svg>
                                                                    <p className="mb-2 text-sm text-gray-500">
                                                                        <span className="font-semibold">Click to upload</span> or drag and drop
                                                                    </p>
                                                                    <p className="text-xs text-gray-500">
                                                                        CSV or Excel file with phone numbers
                                                                    </p>
                                                                </div>
                                                                <input
                                                                    id="file-upload"
                                                                    name="file"
                                                                    type="file"
                                                                    className="hidden"
                                                                    accept=".csv,.xlsx"
                                                                    onChange={handleFileUpload}
                                                                />
                                                            </label>
                                                        </div>
                                                        
                                                        {previewData.length > 0 && (
                                                            <div className="bg-gray-50 p-3 rounded-md">
                                                                <h4 className="text-sm font-medium text-gray-700 mb-2">Preview (First 5 recipients)</h4>
                                                                <ul className="text-xs text-gray-600 space-y-1">
                                                                    {previewData.map((item, index) => (
                                                                        <li key={index} className="truncate">{item}</li>
                                                                    ))}
                                                                </ul>
                                                                <p className="text-sm text-gray-700 mt-2">
                                                                    Total recipients: <span className="font-semibold">{recipientCount}</span>
                                                                </p>
                                                            </div>
                                                        )}
                                                        
                                                        <div className="flex items-center">
                                                            <button
                                                                type="button"
                                                                className="text-sm text-indigo-600 hover:text-indigo-900"
                                                                onClick={() => {
                                                                    setShowFileUpload(false);
                                                                    setData('file', null);
                                                                    setPreviewData([]);
                                                                }}
                                                            >
                                                                Enter numbers manually instead
                                                            </button>
                                                        </div>
                                                    </div>
                                                ) : (
                                                    <div className="space-y-3">
                                                        <TextArea
                                                            name="recipients"
                                                            value={data.recipients}
                                                            rows={4}
                                                            className="block w-full"
                                                            placeholder="Enter phone numbers with country code, separated by commas (e.g., 2341234567890, 2349876543210)"
                                                            onChange={e => setData('recipients', e.target.value)}
                                                            required={!data.file}
                                                        />
                                                        
                                                        <div className="flex items-center">
                                                            <button
                                                                type="button"
                                                                className="text-sm text-indigo-600 hover:text-indigo-900"
                                                                onClick={() => setShowFileUpload(true)}
                                                            >
                                                                Upload from file instead
                                                            </button>
                                                        </div>
                                                    </div>
                                                )}
                                            </div>
                                        )}
                                    </div>

                                    {/* Message Content */}
                                    <div className="sm:col-span-6">
                                        <div className="flex justify-between items-center">
                                            <Label forInput="message" value="Message" />
                                            <span className="text-xs text-gray-500">
                                                {getCharactersRemainingText()}
                                            </span>
                                        </div>
                                        <div className="mt-1">
                                            <TextArea
                                                name="message"
                                                value={data.message}
                                                rows={5}
                                                className="block w-full"
                                                onChange={e => setData('message', e.target.value)}
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                
                                {/* Summary and Submit */}
                                <div className="mt-8 border-t border-gray-200 pt-8">
                                    <div className="flex justify-between items-start">
                                        <div>
                                            <h3 className="text-lg font-medium leading-6 text-gray-900">Summary</h3>
                                            <div className="mt-2 max-w-xl text-sm text-gray-500">
                                                <dl className="space-y-2">
                                                    <div className="flex">
                                                        <dt className="w-40">Recipients:</dt>
                                                        <dd className="font-medium">{recipientCount}</dd>
                                                    </div>
                                                    <div className="flex">
                                                        <dt className="w-40">Message count:</dt>
                                                        <dd className="font-medium">{messageCount} per recipient</dd>
                                                    </div>
                                                    <div className="flex">
                                                        <dt className="w-40">Total messages:</dt>
                                                        <dd className="font-medium">{messageCount * recipientCount}</dd>
                                                    </div>
                                                    <div className="flex">
                                                        <dt className="w-40">Credits required:</dt>
                                                        <dd className="font-medium">{messageCount * recipientCount * smsPricing.perMessage}</dd>
                                                    </div>
                                                    <div className="flex">
                                                        <dt className="w-40">Estimated cost:</dt>
                                                        <dd className="font-medium">
                                                            {new Intl.NumberFormat('en-US', {
                                                                style: 'currency',
                                                                currency: smsPricing.currency
                                                            }).format(estimatedCost)}
                                                        </dd>
                                                    </div>
                                                </dl>
                                            </div>
                                        </div>
                                        <div className="mt-4 text-right">
                                            <div className="text-sm text-gray-600 mb-2">
                                                Available credits: <span className="font-semibold">{smsPricing.credits}</span>
                                            </div>
                                            <Button
                                                type="submit"
                                                className={`px-8`}
                                                disabled={processing || !senderNames.length || recipientCount === 0}
                                                processing={processing}
                                            >
                                                Send Message{messageType === 'campaign' ? 's' : ''}
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}