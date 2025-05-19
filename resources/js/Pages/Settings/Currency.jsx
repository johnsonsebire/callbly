import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function Currency({ auth, currencies = [], defaultCurrency = null }) {
    const [editingCurrency, setEditingCurrency] = useState(null);
    const [showAddModal, setShowAddModal] = useState(false);
    const [formData, setFormData] = useState({
        code: '',
        name: '',
        symbol: '',
        exchange_rate: '',
        is_default: false
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        // Handle form submission
    };

    const handleEdit = (currency) => {
        setEditingCurrency(currency);
        setFormData({
            code: currency.code,
            name: currency.name,
            symbol: currency.symbol,
            exchange_rate: currency.exchange_rate,
            is_default: currency.is_default
        });
    };

    const handleDelete = (currencyId) => {
        // Handle currency deletion
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                        Currency Settings
                    </h2>
                    <Button onClick={() => setShowAddModal(true)}>
                        Add Currency
                    </Button>
                </div>
            }
        >
            <Head title="Currency Settings" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Currency List */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Currency
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Code
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Symbol
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Exchange Rate
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {currencies.map((currency) => (
                                            <tr key={currency.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {currency.name}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">
                                                        {currency.code}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">
                                                        {currency.symbol}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">
                                                        {currency.exchange_rate}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        currency.is_default
                                                            ? 'bg-green-100 text-green-800'
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {currency.is_default ? 'Default' : 'Active'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <button
                                                        onClick={() => handleEdit(currency)}
                                                        className="text-indigo-600 hover:text-indigo-900 mr-4"
                                                    >
                                                        Edit
                                                    </button>
                                                    {!currency.is_default && (
                                                        <button
                                                            onClick={() => handleDelete(currency.id)}
                                                            className="text-red-600 hover:text-red-900"
                                                        >
                                                            Delete
                                                        </button>
                                                    )}
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

            {/* Add/Edit Currency Modal */}
            {(showAddModal || editingCurrency) && (
                <div className="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
                    <div className="fixed inset-0 z-10 overflow-y-auto">
                        <div className="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                            <div className="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                                <div className="absolute right-0 top-0 pr-4 pt-4">
                                    <button
                                        type="button"
                                        onClick={() => {
                                            setShowAddModal(false);
                                            setEditingCurrency(null);
                                            setFormData({
                                                code: '',
                                                name: '',
                                                symbol: '',
                                                exchange_rate: '',
                                                is_default: false
                                            });
                                        }}
                                        className="rounded-md bg-white text-gray-400 hover:text-gray-500"
                                    >
                                        <span className="sr-only">Close</span>
                                        <svg className="h-6 w-6" fill="none" viewBox="0 0 24 24" strokeWidth="1.5" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div className="sm:flex sm:items-start">
                                    <div className="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 className="text-base font-semibold leading-6 text-gray-900">
                                            {editingCurrency ? 'Edit Currency' : 'Add Currency'}
                                        </h3>

                                        <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                                            <div>
                                                <Label forInput="code" value="Currency Code" />
                                                <Input
                                                    id="code"
                                                    type="text"
                                                    value={formData.code}
                                                    className="mt-1 block w-full"
                                                    onChange={(e) => setFormData({ ...formData, code: e.target.value })}
                                                    required
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="name" value="Currency Name" />
                                                <Input
                                                    id="name"
                                                    type="text"
                                                    value={formData.name}
                                                    className="mt-1 block w-full"
                                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                                    required
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="symbol" value="Symbol" />
                                                <Input
                                                    id="symbol"
                                                    type="text"
                                                    value={formData.symbol}
                                                    className="mt-1 block w-full"
                                                    onChange={(e) => setFormData({ ...formData, symbol: e.target.value })}
                                                    required
                                                />
                                            </div>

                                            <div>
                                                <Label forInput="exchange_rate" value="Exchange Rate" />
                                                <Input
                                                    id="exchange_rate"
                                                    type="number"
                                                    step="0.0001"
                                                    value={formData.exchange_rate}
                                                    className="mt-1 block w-full"
                                                    onChange={(e) => setFormData({ ...formData, exchange_rate: e.target.value })}
                                                    required
                                                />
                                            </div>

                                            {!defaultCurrency && (
                                                <div className="flex items-center">
                                                    <input
                                                        id="is_default"
                                                        type="checkbox"
                                                        checked={formData.is_default}
                                                        onChange={(e) => setFormData({ ...formData, is_default: e.target.checked })}
                                                        className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                                                    />
                                                    <Label forInput="is_default" value="Set as default currency" className="ml-2" />
                                                </div>
                                            )}

                                            <div className="mt-6 flex justify-end gap-x-3">
                                                <Button
                                                    type="button"
                                                    className="bg-white text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                                                    onClick={() => {
                                                        setShowAddModal(false);
                                                        setEditingCurrency(null);
                                                    }}
                                                >
                                                    Cancel
                                                </Button>
                                                <Button type="submit">
                                                    {editingCurrency ? 'Update' : 'Add'} Currency
                                                </Button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AuthenticatedLayout>
    );
}