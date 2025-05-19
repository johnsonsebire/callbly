import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function Browse({ auth, countries = [], availableNumbers = [], filters = {} }) {
    const [selectedNumbers, setSelectedNumbers] = useState([]);
    const { data, setData, get, processing } = useForm({
        country: filters.country || '',
        area_code: filters.area_code || '',
        pattern: filters.pattern || '',
        number_type: filters.number_type || 'any'
    });

    const handleSearch = (e) => {
        e.preventDefault();
        get(route('virtual-numbers.search'), {
            preserveState: true,
            preserveScroll: true
        });
    };

    const toggleNumberSelection = (number) => {
        setSelectedNumbers(prev => 
            prev.includes(number.id)
                ? prev.filter(id => id !== number.id)
                : [...prev, number.id]
        );
    };

    const numberTypes = [
        { value: 'any', label: 'Any Type' },
        { value: 'mobile', label: 'Mobile' },
        { value: 'landline', label: 'Landline' },
        { value: 'tollfree', label: 'Toll Free' }
    ];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Browse Virtual Numbers
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Search Filters */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div className="p-6">
                            <form onSubmit={handleSearch} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div>
                                        <Label forInput="country" value="Country" />
                                        <select
                                            id="country"
                                            value={data.country}
                                            onChange={e => setData('country', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="">Select Country</option>
                                            {countries.map(country => (
                                                <option key={country.code} value={country.code}>
                                                    {country.name}
                                                </option>
                                            ))}
                                        </select>
                                    </div>

                                    <div>
                                        <Label forInput="area_code" value="Area Code" />
                                        <Input
                                            type="text"
                                            id="area_code"
                                            value={data.area_code}
                                            onChange={e => setData('area_code', e.target.value)}
                                            className="mt-1 block w-full"
                                            placeholder="e.g. 212"
                                        />
                                    </div>

                                    <div>
                                        <Label forInput="pattern" value="Number Pattern" />
                                        <Input
                                            type="text"
                                            id="pattern"
                                            value={data.pattern}
                                            onChange={e => setData('pattern', e.target.value)}
                                            className="mt-1 block w-full"
                                            placeholder="e.g. *1234*"
                                        />
                                    </div>

                                    <div>
                                        <Label forInput="number_type" value="Number Type" />
                                        <select
                                            id="number_type"
                                            value={data.number_type}
                                            onChange={e => setData('number_type', e.target.value)}
                                            className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            {numberTypes.map(type => (
                                                <option key={type.value} value={type.value}>
                                                    {type.label}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                </div>

                                <div className="flex justify-end">
                                    <Button type="submit" processing={processing}>
                                        Search Numbers
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Available Numbers */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-6">
                                <h3 className="text-lg font-medium text-gray-900">
                                    Available Numbers
                                </h3>
                                {selectedNumbers.length > 0 && (
                                    <Button
                                        onClick={() => {
                                            // Handle purchase of selected numbers
                                        }}
                                    >
                                        Purchase Selected ({selectedNumbers.length})
                                    </Button>
                                )}
                            </div>

                            <div className="overflow-x-auto">
                                <table className="min-w-full divide-y divide-gray-200">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th scope="col" className="relative px-6 py-3">
                                                <input
                                                    type="checkbox"
                                                    className="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                    checked={selectedNumbers.length === availableNumbers.length}
                                                    onChange={() => {
                                                        if (selectedNumbers.length === availableNumbers.length) {
                                                            setSelectedNumbers([]);
                                                        } else {
                                                            setSelectedNumbers(availableNumbers.map(n => n.id));
                                                        }
                                                    }}
                                                />
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Number
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Type
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Location
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Monthly Cost
                                            </th>
                                            <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Setup Fee
                                            </th>
                                            <th scope="col" className="relative px-6 py-3">
                                                <span className="sr-only">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="bg-white divide-y divide-gray-200">
                                        {availableNumbers.map((number) => (
                                            <tr key={number.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <input
                                                        type="checkbox"
                                                        className="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                        checked={selectedNumbers.includes(number.id)}
                                                        onChange={() => toggleNumberSelection(number)}
                                                    />
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">
                                                        {number.formatted_number}
                                                    </div>
                                                    {number.features?.length > 0 && (
                                                        <div className="text-sm text-gray-500">
                                                            {number.features.join(', ')}
                                                        </div>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                                                        number.type === 'tollfree' 
                                                            ? 'bg-green-100 text-green-800'
                                                            : number.type === 'mobile'
                                                            ? 'bg-blue-100 text-blue-800'
                                                            : 'bg-gray-100 text-gray-800'
                                                    }`}>
                                                        {number.type}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-900">{number.country}</div>
                                                    <div className="text-sm text-gray-500">{number.region}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${number.monthly_cost}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ${number.setup_fee}
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <Button
                                                        onClick={() => {
                                                            // Handle single number purchase
                                                        }}
                                                        className="text-indigo-600 hover:text-indigo-900"
                                                    >
                                                        Purchase
                                                    </Button>
                                                </td>
                                            </tr>
                                        ))}
                                        {availableNumbers.length === 0 && (
                                            <tr>
                                                <td colSpan="7" className="px-6 py-4 text-center text-gray-500">
                                                    No numbers found matching your criteria. Try adjusting your search filters.
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