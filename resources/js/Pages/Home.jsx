import React from 'react';
import AppLayout from '../Layouts/AppLayout';
import Button from '../Components/Button';
import { Link } from '@inertiajs/react';

export default function Home() {
    // Feature blocks for the homepage
    const features = [
        {
            title: 'SMS Campaigns',
            description: 'Create and manage bulk SMS campaigns with easy scheduling and detailed analytics.',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            )
        },
        {
            title: 'Contact Center',
            description: 'Manage your customer service with intelligent call routing and comprehensive analytics.',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                </svg>
            )
        },
        {
            title: 'USSD Services',
            description: 'Build interactive USSD menus for customer engagement and self-service options.',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                </svg>
            )
        },
        {
            title: 'Virtual Numbers',
            description: 'Get local and international virtual phone numbers for business communications.',
            icon: (
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                    <path strokeLinecap="round" strokeLinejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            )
        }
    ];

    // Testimonials for the homepage
    const testimonials = [
        {
            name: "Sarah Johnson",
            company: "TechFusion Inc.",
            content: "Callbly has transformed the way we communicate with our customers. The SMS campaigns feature has increased our engagement rates by 40%.",
            avatar: "/img/testimonial-1.jpg"
        },
        {
            name: "Michael Chen",
            company: "Global Solutions Ltd.",
            content: "The virtual number service has allowed us to establish a local presence in multiple countries without physical offices.",
            avatar: "/img/testimonial-2.jpg"
        },
        {
            name: "Aisha Patel",
            company: "Retail Connect",
            content: "Our customer service team loves the contact center interface. It's intuitive and provides all the tools they need to handle calls efficiently.",
            avatar: "/img/testimonial-3.jpg"
        }
    ];

    return (
        <AppLayout>
            {/* Hero Section */}
            <div className="relative isolate overflow-hidden bg-gradient-to-b from-indigo-100/20">
                <div className="mx-auto max-w-7xl px-6 pb-24 pt-10 sm:pb-32 lg:flex lg:px-8 lg:py-40">
                    <div className="mx-auto max-w-2xl flex-shrink-0 lg:mx-0 lg:max-w-xl lg:pt-8">
                        <div className="mt-4">
                            <span className="rounded-full bg-indigo-600/10 px-3 py-1 text-sm font-semibold leading-6 text-indigo-600 ring-1 ring-inset ring-indigo-600/10">
                                Cloud Telephony Platform
                            </span>
                        </div>
                        <h1 className="mt-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                            Transforming Business Communication
                        </h1>
                        <p className="mt-6 text-lg leading-8 text-gray-600">
                            Streamline your business communications with our comprehensive suite of SMS, USSD, and contact center solutions. Reach your customers effectively and efficiently.
                        </p>
                        <div className="mt-10 flex items-center gap-x-6">
                            <Link href="/register">
                                <Button className="px-8 py-3 text-base">Get Started</Button>
                            </Link>
                            <Link href="/login" className="text-base font-semibold leading-7 text-gray-900">
                                Sign In <span aria-hidden="true">→</span>
                            </Link>
                        </div>
                    </div>
                    <div className="mx-auto mt-16 flex max-w-2xl sm:mt-24 lg:ml-10 lg:mt-0 lg:mr-0 lg:max-w-none lg:flex-none xl:ml-32">
                        <div className="max-w-3xl flex-none sm:max-w-5xl lg:max-w-none">
                            <img
                                src="/img/hero-image.png"
                                alt="App screenshot"
                                width={2432}
                                height={1442}
                                className="w-[76rem] rounded-md bg-white/5 shadow-2xl ring-1 ring-white/10"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Feature Section */}
            <div className="bg-white py-24 sm:py-32">
                <div className="mx-auto max-w-7xl px-6 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            Everything you need to connect with your customers
                        </h2>
                        <p className="mt-6 text-lg leading-8 text-gray-600">
                            From SMS campaigns to virtual contact centers, we provide the tools you need to engage your audience effectively.
                        </p>
                    </div>

                    <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                        <dl className="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-4">
                            {features.map((feature, index) => (
                                <div key={index} className="flex flex-col">
                                    <dt className="flex items-center gap-x-3 text-xl font-semibold leading-7 text-gray-900">
                                        <div className="h-10 w-10 flex-none rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                                            {feature.icon}
                                        </div>
                                        {feature.title}
                                    </dt>
                                    <dd className="mt-4 flex flex-auto flex-col text-base leading-7 text-gray-600">
                                        <p className="flex-auto">{feature.description}</p>
                                        <p className="mt-6">
                                            <Link href="#" className="text-sm font-semibold leading-6 text-indigo-600">
                                                Learn more <span aria-hidden="true">→</span>
                                            </Link>
                                        </p>
                                    </dd>
                                </div>
                            ))}
                        </dl>
                    </div>
                </div>
            </div>

            {/* Statistics Section */}
            <div className="bg-indigo-900 py-24 sm:py-32">
                <div className="mx-auto max-w-7xl px-6 lg:px-8">
                    <div className="mx-auto max-w-2xl lg:max-w-none">
                        <div className="text-center">
                            <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                                Trusted by businesses worldwide
                            </h2>
                            <p className="mt-4 text-lg leading-8 text-indigo-200">
                                Our platform handles millions of communications daily
                            </p>
                        </div>
                        <dl className="mt-16 grid grid-cols-1 gap-0.5 overflow-hidden rounded-2xl text-center sm:grid-cols-2 lg:grid-cols-4">
                            <div className="flex flex-col bg-white/5 p-8">
                                <dt className="text-sm font-semibold leading-6 text-indigo-200">Messages sent daily</dt>
                                <dd className="order-first text-3xl font-semibold tracking-tight text-white">12M+</dd>
                            </div>
                            <div className="flex flex-col bg-white/5 p-8">
                                <dt className="text-sm font-semibold leading-6 text-indigo-200">Countries served</dt>
                                <dd className="order-first text-3xl font-semibold tracking-tight text-white">42</dd>
                            </div>
                            <div className="flex flex-col bg-white/5 p-8">
                                <dt className="text-sm font-semibold leading-6 text-indigo-200">Active users</dt>
                                <dd className="order-first text-3xl font-semibold tracking-tight text-white">8,000+</dd>
                            </div>
                            <div className="flex flex-col bg-white/5 p-8">
                                <dt className="text-sm font-semibold leading-6 text-indigo-200">Customer satisfaction</dt>
                                <dd className="order-first text-3xl font-semibold tracking-tight text-white">99.8%</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {/* Testimonials Section */}
            <div className="bg-white py-24 sm:py-32">
                <div className="mx-auto max-w-7xl px-6 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                            What our customers say
                        </h2>
                        <p className="mt-6 text-lg leading-8 text-gray-600">
                            Discover how businesses are transforming their communication with Callbly
                        </p>
                    </div>
                    <div className="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-8 lg:max-w-none lg:grid-cols-3">
                        {testimonials.map((testimonial, index) => (
                            <div key={index} className="flex flex-col rounded-2xl bg-gray-50 p-8">
                                <div className="flex items-center gap-x-4">
                                    <img 
                                        src={testimonial.avatar} 
                                        alt="" 
                                        className="h-12 w-12 rounded-full bg-gray-50" 
                                        onError={(e) => {
                                            e.target.onerror = null; 
                                            e.target.src = "https://placehold.co/100x100?text=User";
                                        }}
                                    />
                                    <div>
                                        <h3 className="text-lg font-semibold leading-8 tracking-tight text-gray-900">{testimonial.name}</h3>
                                        <p className="text-base leading-7 text-gray-600">{testimonial.company}</p>
                                    </div>
                                </div>
                                <p className="mt-4 text-base leading-7 text-gray-600">"{testimonial.content}"</p>
                            </div>
                        ))}
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            <div className="bg-indigo-600">
                <div className="py-24 px-6 sm:px-6 sm:py-32 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <h2 className="text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            Ready to transform your business communications?
                        </h2>
                        <p className="mx-auto mt-6 max-w-xl text-lg leading-8 text-indigo-100">
                            Start connecting with your customers more effectively today.
                        </p>
                        <div className="mt-10 flex items-center justify-center gap-x-6">
                            <Link href="/register">
                                <Button className="px-8 py-3 text-base bg-white text-indigo-600 hover:bg-indigo-50">
                                    Get started
                                </Button>
                            </Link>
                            <Link href="#" className="text-base font-semibold leading-7 text-white">
                                Learn more <span aria-hidden="true">→</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}