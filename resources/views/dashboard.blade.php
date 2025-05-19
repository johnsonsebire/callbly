@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-semibold mb-4">Welcome to your Callbly Dashboard</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                        <h2 class="text-lg font-medium text-blue-800 mb-2">SMS Credits</h2>
                        <p class="text-3xl font-bold">{{ auth()->user()->sms_credits }}</p>
                        <div class="mt-4">
                            <a href="{{ route('sms.credits') }}" class="text-sm text-blue-600 hover:text-blue-800">Buy SMS Credits</a>
                        </div>
                        <div class="mt-1">
                            <a href="{{ route('sms.billing-tier') }}" class="text-sm text-blue-600 hover:text-blue-800">View Billing Tier</a>
                        </div>
                    </div>
                    
                    <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                        <h2 class="text-lg font-medium text-green-800 mb-2">Call Credits</h2>
                        <p class="text-3xl font-bold">{{ auth()->user()->call_credits }}</p>
                        <div class="mt-4">
                            <a href="#" class="text-sm text-green-600 hover:text-green-800">Buy Call Credits</a>
                        </div>
                    </div>
                    
                    <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                        <h2 class="text-lg font-medium text-purple-800 mb-2">USSD Credits</h2>
                        <p class="text-3xl font-bold">{{ auth()->user()->ussd_credits }}</p>
                        <div class="mt-4">
                            <a href="#" class="text-sm text-purple-600 hover:text-purple-800">Buy USSD Credits</a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <a href="{{ route('sms.compose') }}" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Send SMS</h3>
                            <p class="text-sm text-gray-600">Send SMS to single or multiple recipients</p>
                        </a>
                        
                        <a href="#" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Make Calls</h3>
                            <p class="text-sm text-gray-600">Initiate calls through our contact center</p>
                        </a>
                        
                        <a href="#" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Create USSD Service</h3>
                            <p class="text-sm text-gray-600">Set up interactive USSD services</p>
                        </a>
                        
                        <a href="#" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Get Virtual Number</h3>
                            <p class="text-sm text-gray-600">Purchase virtual phone numbers</p>
                        </a>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <div class="p-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-medium">Recent SMS Campaigns</h3>
                            </div>
                            <div class="p-4">
                                @if(auth()->user()->smsCampaigns()->count() > 0)
                                    <ul class="divide-y divide-gray-200">
                                        @foreach(auth()->user()->smsCampaigns()->latest()->take(3)->get() as $campaign)
                                            <li class="py-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium truncate">{{ $campaign->name }}</span>
                                                    <span class="text-xs text-gray-500">{{ $campaign->created_at->format('M d, Y') }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500">{{ $campaign->recipients_count }} recipients</p>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('sms.campaigns') }}" class="text-sm text-blue-600 hover:text-blue-800">View All Campaigns</a>
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-2">
                                        <p>No recent SMS campaigns found.</p>
                                        <div class="mt-2">
                                            <a href="{{ route('sms.compose') }}" class="text-sm text-blue-600 hover:text-blue-800">Send your first SMS</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-white shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <div class="p-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="font-medium">Sender Names</h3>
                            </div>
                            <div class="p-4">
                                @if(auth()->user()->senderNames()->count() > 0)
                                    <ul class="divide-y divide-gray-200">
                                        @foreach(auth()->user()->senderNames()->latest()->take(3)->get() as $senderName)
                                            <li class="py-2">
                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium">{{ $senderName->name }}</span>
                                                    <span class="text-xs px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($senderName->status === 'approved') bg-green-100 text-green-800 
                                                    @elseif($senderName->status === 'pending') bg-yellow-100 text-yellow-800 
                                                    @else bg-red-100 text-red-800 @endif">
                                                        {{ ucfirst($senderName->status) }}
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="mt-3 text-center">
                                        <a href="{{ route('sms.sender-names') }}" class="text-sm text-blue-600 hover:text-blue-800">Manage Sender Names</a>
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 py-2">
                                        <p>No sender names found.</p>
                                        <div class="mt-2">
                                            <a href="{{ route('sms.sender-names') }}" class="text-sm text-blue-600 hover:text-blue-800">Register a Sender Name</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-center">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <a href="{{ route('settings.currency') }}" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Currency Settings</h3>
                            <p class="text-sm text-gray-600">Change your preferred currency</p>
                            <p class="text-xs mt-1 font-medium text-blue-600">Current: {{ auth()->user()->currency->code }}</p>
                        </a>
                        
                        <a href="{{ route('profile') }}" class="block p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100">
                            <h3 class="font-medium">Profile Settings</h3>
                            <p class="text-sm text-gray-600">Update your account information</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection