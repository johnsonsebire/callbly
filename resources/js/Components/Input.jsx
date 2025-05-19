import React from 'react';

export default function Input({ type = 'text', name, value, onChange, className = '', error, ...props }) {
    return (
        <div>
            <input
                type={type}
                name={name}
                value={value}
                onChange={onChange}
                className={`border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-indigo-500 ${error ? 'border-red-500' : 'border-gray-300'} ${className}`}
                {...props}
            />
            {error && <div className="text-red-500 text-xs mt-1">{error}</div>}
        </div>
    );
}
