import React, { useState, useEffect } from 'react';
import { Link, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';
import Button from '../../Components/Button';
import Input from '../../Components/Input';
import Label from '../../Components/Label';
import { route } from 'ziggy-js';

export default function Register() {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [passwordStrength, setPasswordStrength] = useState(0);

    const { data, setData, post, processing, errors } = useForm({
        name: '',
        email: '',
        phone: '',
        company_name: '',
        password: '',
        password_confirmation: '',
    });

    const checkPasswordStrength = (password) => {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        setPasswordStrength(strength);
    };

    useEffect(() => {
        checkPasswordStrength(data.password);
    }, [data.password]);

    const submit = (e) => {
        e.preventDefault();
        post(route('register', undefined, Ziggy));
    };

    const getStrengthColor = () => {
        switch (passwordStrength) {
            case 0:
            case 1:
                return 'bg-red-500';
            case 2:
            case 3:
                return 'bg-yellow-500';
            case 4:
            case 5:
                return 'bg-green-500';
            default:
                return 'bg-gray-200';
        }
    };

    return (
        <AppLayout>
            <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
                    <div className="mb-8 text-center">
                        <h2 className="text-3xl font-bold text-gray-900">Create your account</h2>
                        <p className="mt-2 text-sm text-gray-600">
                            Join thousands of businesses using Callbly
                        </p>
                    </div>

                    <form onSubmit={submit}>
                        <div className="mb-6">
                            <Label forInput="name" value="Full Name" required />
                            <Input
                                type="text"
                                name="name"
                                value={data.name}
                                className="mt-1 block w-full"
                                autoComplete="name"
                                isFocused={true}
                                onChange={(e) => setData('name', e.target.value)}
                                error={errors.name}
                                required
                            />
                        </div>

                        <div className="mb-6">
                            <Label forInput="email" value="Email Address" required />
                            <Input
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                onChange={(e) => setData('email', e.target.value)}
                                error={errors.email}
                                required
                            />
                        </div>

                        <div className="mb-6">
                            <Label forInput="phone" value="Phone Number" required />
                            <Input
                                type="tel"
                                name="phone"
                                value={data.phone}
                                className="mt-1 block w-full"
                                autoComplete="tel"
                                onChange={(e) => setData('phone', e.target.value)}
                                error={errors.phone}
                                required
                            />
                        </div>

                        <div className="mb-6">
                            <Label forInput="company_name" value="Company Name" />
                            <Input
                                type="text"
                                name="company_name"
                                value={data.company_name}
                                className="mt-1 block w-full"
                                autoComplete="organization"
                                onChange={(e) => setData('company_name', e.target.value)}
                                error={errors.company_name}
                            />
                        </div>

                        <div className="mb-6">
                            <Label forInput="password" value="Password" required />
                            <div className="relative">
                                <Input
                                    type={showPassword ? 'text' : 'password'}
                                    name="password"
                                    value={data.password}
                                    className="mt-1 block w-full pr-10"
                                    autoComplete="new-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                    error={errors.password}
                                    required
                                />
                                <button
                                    type="button"
                                    className="absolute inset-y-0 right-0 mt-1 pr-3 flex items-center"
                                    onClick={() => setShowPassword(!showPassword)}
                                >
                                    {showPassword ? (
                                        <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    ) : (
                                        <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    )}
                                </button>
                            </div>
                            <div className="mt-2">
                                <div className="h-1 w-full bg-gray-200 rounded-full">
                                    <div
                                        className={`h-1 rounded-full transition-all ${getStrengthColor()}`}
                                        style={{ width: `${(passwordStrength / 5) * 100}%` }}
                                    ></div>
                                </div>
                                <p className="text-xs text-gray-600 mt-1">
                                    Password must contain at least 8 characters, including uppercase, lowercase, numbers, and symbols
                                </p>
                            </div>
                        </div>

                        <div className="mb-6">
                            <Label forInput="password_confirmation" value="Confirm Password" required />
                            <div className="relative">
                                <Input
                                    type={showConfirmPassword ? 'text' : 'password'}
                                    name="password_confirmation"
                                    value={data.password_confirmation}
                                    className="mt-1 block w-full pr-10"
                                    autoComplete="new-password"
                                    onChange={(e) => setData('password_confirmation', e.target.value)}
                                    error={errors.password_confirmation}
                                    required
                                />
                                <button
                                    type="button"
                                    className="absolute inset-y-0 right-0 mt-1 pr-3 flex items-center"
                                    onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                >
                                    {showConfirmPassword ? (
                                        <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    ) : (
                                        <svg className="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    )}
                                </button>
                            </div>
                        </div>

                        <Button className="w-full justify-center" processing={processing}>
                            Create Account
                        </Button>

                        <p className="mt-6 text-center text-sm text-gray-600">
                            Already have an account?{' '}
                            <Link href={route('login', undefined, Ziggy)} className="font-medium text-indigo-600 hover:text-indigo-500">
                                Sign in
                            </Link>
                        </p>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}