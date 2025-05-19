import React, { useState } from 'react';
import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';

export default function Configure({ auth, service = null }) {
    const [menuItems, setMenuItems] = useState(service?.menu_items || []);
    const [editingItem, setEditingItem] = useState(null);

    const { data, setData, post, processing, errors } = useForm({
        name: service?.name || '',
        shortcode: service?.shortcode || '',
        welcome_message: service?.welcome_message || '',
        menu_items: service?.menu_items || []
    });

    const addMenuItem = () => {
        setMenuItems([
            ...menuItems,
            {
                id: Date.now(),
                option: String(menuItems.length + 1),
                text: '',
                action: 'submenu',
                submenu: [],
                api_endpoint: ''
            }
        ]);
    };

    const updateMenuItem = (id, updates) => {
        setMenuItems(menuItems.map(item => 
            item.id === id ? { ...item, ...updates } : item
        ));
    };

    const removeMenuItem = (id) => {
        setMenuItems(menuItems.filter(item => item.id !== id));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        setData('menu_items', menuItems);
        post(route(service ? 'ussd.update' : 'ussd.store'));
    };

    const MenuItemForm = ({ item }) => (
        <div className="bg-gray-50 p-4 rounded-lg mb-4">
            <div className="grid grid-cols-6 gap-4">
                <div className="col-span-1">
                    <Label forInput={`option-${item.id}`} value="Option" />
                    <Input
                        type="text"
                        id={`option-${item.id}`}
                        value={item.option}
                        onChange={(e) => updateMenuItem(item.id, { option: e.target.value })}
                        className="mt-1"
                    />
                </div>
                <div className="col-span-3">
                    <Label forInput={`text-${item.id}`} value="Menu Text" />
                    <Input
                        type="text"
                        id={`text-${item.id}`}
                        value={item.text}
                        onChange={(e) => updateMenuItem(item.id, { text: e.target.value })}
                        className="mt-1"
                    />
                </div>
                <div className="col-span-2">
                    <Label forInput={`action-${item.id}`} value="Action" />
                    <select
                        id={`action-${item.id}`}
                        value={item.action}
                        onChange={(e) => updateMenuItem(item.id, { action: e.target.value })}
                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="submenu">Sub Menu</option>
                        <option value="endpoint">API Endpoint</option>
                        <option value="response">Final Response</option>
                    </select>
                </div>
            </div>

            {item.action === 'endpoint' && (
                <div className="mt-4">
                    <Label forInput={`endpoint-${item.id}`} value="API Endpoint" />
                    <Input
                        type="text"
                        id={`endpoint-${item.id}`}
                        value={item.api_endpoint}
                        onChange={(e) => updateMenuItem(item.id, { api_endpoint: e.target.value })}
                        className="mt-1"
                        placeholder="https://api.example.com/endpoint"
                    />
                </div>
            )}

            {item.action === 'submenu' && (
                <div className="mt-4 pl-6 border-l-2 border-gray-200">
                    <div className="flex justify-between items-center mb-2">
                        <h4 className="text-sm font-medium text-gray-900">Sub Menu Items</h4>
                        <Button
                            type="button"
                            onClick={() => {
                                const submenu = [...(item.submenu || [])];
                                submenu.push({
                                    id: Date.now(),
                                    option: String(submenu.length + 1),
                                    text: '',
                                    action: 'response'
                                });
                                updateMenuItem(item.id, { submenu });
                            }}
                            className="text-sm"
                        >
                            Add Sub Item
                        </Button>
                    </div>
                    {item.submenu?.map((subItem) => (
                        <div key={subItem.id} className="mb-2">
                            <div className="grid grid-cols-6 gap-4">
                                <div className="col-span-1">
                                    <Input
                                        type="text"
                                        value={subItem.option}
                                        onChange={(e) => {
                                            const submenu = item.submenu.map(si => 
                                                si.id === subItem.id ? { ...si, option: e.target.value } : si
                                            );
                                            updateMenuItem(item.id, { submenu });
                                        }}
                                    />
                                </div>
                                <div className="col-span-5">
                                    <Input
                                        type="text"
                                        value={subItem.text}
                                        onChange={(e) => {
                                            const submenu = item.submenu.map(si => 
                                                si.id === subItem.id ? { ...si, text: e.target.value } : si
                                            );
                                            updateMenuItem(item.id, { submenu });
                                        }}
                                    />
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            )}

            <div className="mt-4 flex justify-end">
                <Button
                    type="button"
                    onClick={() => removeMenuItem(item.id)}
                    className="bg-red-600 hover:bg-red-700 focus:ring-red-500"
                >
                    Remove
                </Button>
            </div>
        </div>
    );

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    {service ? 'Edit USSD Service' : 'Create USSD Service'}
                </h2>
            }
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <form onSubmit={handleSubmit} className="p-6">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <Label forInput="name" value="Service Name" required />
                                    <Input
                                        type="text"
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        className="mt-1 block w-full"
                                        required
                                    />
                                    {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
                                </div>

                                <div>
                                    <Label forInput="shortcode" value="USSD Shortcode" required />
                                    <Input
                                        type="text"
                                        id="shortcode"
                                        value={data.shortcode}
                                        onChange={(e) => setData('shortcode', e.target.value)}
                                        className="mt-1 block w-full"
                                        placeholder="*123#"
                                        required
                                    />
                                    {errors.shortcode && <p className="text-red-500 text-xs mt-1">{errors.shortcode}</p>}
                                </div>
                            </div>

                            <div className="mb-6">
                                <Label forInput="welcome_message" value="Welcome Message" required />
                                <textarea
                                    id="welcome_message"
                                    value={data.welcome_message}
                                    onChange={(e) => setData('welcome_message', e.target.value)}
                                    className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows={3}
                                    required
                                />
                                {errors.welcome_message && <p className="text-red-500 text-xs mt-1">{errors.welcome_message}</p>}
                            </div>

                            <div className="mb-6">
                                <div className="flex justify-between items-center mb-4">
                                    <h3 className="text-lg font-medium text-gray-900">Menu Items</h3>
                                    <Button type="button" onClick={addMenuItem}>
                                        Add Menu Item
                                    </Button>
                                </div>

                                <div className="space-y-4">
                                    {menuItems.map((item) => (
                                        <MenuItemForm key={item.id} item={item} />
                                    ))}
                                    {menuItems.length === 0 && (
                                        <p className="text-gray-500 text-center py-4">
                                            No menu items yet. Click "Add Menu Item" to start building your USSD menu.
                                        </p>
                                    )}
                                </div>
                            </div>

                            <div className="flex justify-end space-x-3">
                                <Button
                                    type="button"
                                    className="bg-white"
                                    onClick={() => window.history.back()}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" processing={processing}>
                                    {service ? 'Update Service' : 'Create Service'}
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}