<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Callbly API Documentation</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Prism Syntax Highlighter -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: '#10B981', // Emerald 500
                        dark: '#111827',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #F9FAFB; color: #374151; }
        .sidebar { height: 100vh; position: sticky; top: 0; }
        .endpoint-method { 
            display: inline-block; 
            padding: 2px 8px; 
            border-radius: 4px; 
            font-size: 0.75rem; 
            font-weight: 600; 
            margin-right: 8px;
        }
        .method-post { background-color: #D1FAE5; color: #065F46; }
        .method-get { background-color: #DBEAFE; color: #1E40AF; }
        
        pre[class*="language-"] {
            border-radius: 0.5rem;
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }
        
        /* Tabs styles */
        .tab-btn {
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #9CA3AF;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }
        .tab-btn.active {
            color: #10B981;
            border-bottom-color: #10B981;
        }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
    </style>
</head>
<body class="antialiased">

<div class="flex flex-col md:flex-row">
    
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-white border-r border-gray-200 sidebar overflow-y-auto hidden md:block">
        <div class="p-6">
            <a href="/" class="text-2xl font-bold text-gray-900 tracking-tight flex items-center">
                <svg class="w-7 h-7 text-primary mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9v-2h2v2zm0-4H9V7h2v5zm4 4h-2v-2h2v2zm0-4h-2V7h2v5z"/></svg>
                Callbly
            </a>
            <p class="text-sm text-gray-500 mt-1">API Documentation</p>
        </div>
        
        <nav class="px-4 pb-6">
            <div class="mb-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Getting Started</h3>
                <div class="mt-2 space-y-1">
                    <a href="#introduction" class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">Introduction</a>
                    <a href="#authentication" class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">Authentication</a>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">SMS API</h3>
                <div class="mt-2 space-y-1">
                    <a href="#send-single" class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">Send Single SMS</a>
                    <a href="#send-bulk" class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">Send Bulk SMS</a>
                </div>
            </div>
            
            <div class="mb-6">
                <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Wallet API</h3>
                <div class="mt-2 space-y-1">
                    <a href="#check-balance" class="block px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-50 hover:text-primary">Check Balance</a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 max-w-4xl px-4 py-10 mx-auto md:px-8 lg:px-12">
        
        <!-- Mobile Header -->
        <div class="md:hidden flex items-center mb-8 border-b pb-4">
            <a href="/" class="text-xl font-bold text-gray-900">Callbly API Docs</a>
        </div>

        <div id="introduction" class="mb-16">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Callbly API Reference</h1>
            <p class="text-lg text-gray-600 leading-relaxed mb-6">
                Welcome to the Callbly API! You can use our REST API to programmatically send SMS messages, check your wallet balance, and manage your account.
            </p>
            <p class="text-md text-gray-600 leading-relaxed">
                Our API is organized around REST. Our API has predictable resource-oriented URLs, accepts form-encoded request bodies, returns JSON-encoded responses, and uses standard HTTP response codes, authentication, and verbs.
            </p>
        </div>

        <hr class="border-gray-200 mb-12">

        <div id="authentication" class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Authentication</h2>
            <p class="text-md text-gray-600 leading-relaxed mb-4">
                The Callbly API uses Bearer Tokens to authenticate requests. You can generate and view your API tokens in your Callbly Dashboard under Settings.
            </p>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Your API tokens carry many privileges, so be sure to keep them secure! Do not share your secret API tokens in publicly accessible areas such as GitHub or client-side code.
                        </p>
                    </div>
                </div>
            </div>
            <p class="text-md text-gray-600 leading-relaxed mb-4">
                All API requests must be made over HTTPS. Calls made over plain HTTP will fail. You must include your token in the <code class="bg-gray-100 text-red-500 px-1 py-0.5 rounded text-sm font-mono">Authorization</code> header.
            </p>
<pre><code class="language-http">Authorization: Bearer YOUR_API_TOKEN</code></pre>
        </div>

        <hr class="border-gray-200 mb-12">

        <!-- Endpoint: Send Bulk SMS -->
        <div id="send-bulk" class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Send Bulk SMS</h2>
            <div class="flex items-center mb-6">
                <span class="endpoint-method method-post">POST</span>
                <code class="text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200">/api/v1/sms/send-bulk</code>
            </div>
            
            <p class="text-md text-gray-600 mb-6">
                Send an identical SMS message to multiple recipients in a single API request. This is the recommended approach for broadcasting messages.
            </p>

            <h4 class="font-semibold text-gray-900 mb-2">Request Body (JSON)</h4>
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-md overflow-hidden mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">recipients</td>
                        <td class="px-4 py-3 text-gray-500">Array</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> An array of phone numbers (strings).</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">message</td>
                        <td class="px-4 py-3 text-gray-500">String</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> The body of the SMS message.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">sender_name</td>
                        <td class="px-4 py-3 text-gray-500">String</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> Approved sender ID (max 11 characters).</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-dark rounded-lg overflow-hidden shadow-lg mt-8">
                <div class="flex border-b border-gray-700 bg-gray-900 px-2 pt-2">
                    <button class="tab-btn active" onclick="switchTab(this, 'bulk-php')">PHP</button>
                    <button class="tab-btn" onclick="switchTab(this, 'bulk-node')">Node.js</button>
                    <button class="tab-btn" onclick="switchTab(this, 'bulk-python')">Python</button>
                    <button class="tab-btn" onclick="switchTab(this, 'bulk-curl')">cURL</button>
                </div>
                
                <div class="p-4 bg-dark text-sm">
                    <div id="bulk-php" class="tab-content active">
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->acceptJson()
    ->post('https://callbly.com/api/v1/sms/send-bulk', [
        'recipients' => ['+233201234567', '+233241234567'],
        'message' => 'Hello from Callbly! This is a bulk message.',
        'sender_name' => 'MyBrand',
    ]);

if ($response->successful()) {
    echo "Message sent successfully!";
}</code></pre>
                    </div>
                    <div id="bulk-node" class="tab-content">
<pre class="!m-0"><code class="language-javascript">const axios = require('axios');

axios.post('https://callbly.com/api/v1/sms/send-bulk', {
  recipients: ['+233201234567', '+233241234567'],
  message: 'Hello from Callbly! This is a bulk message.',
  sender_name: 'MyBrand'
}, {
  headers: {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})
.then(response => console.log(response.data))
.catch(error => console.error(error));</code></pre>
                    </div>
                    <div id="bulk-python" class="tab-content">
<pre class="!m-0"><code class="language-python">import requests

url = "https://callbly.com/api/v1/sms/send-bulk"
payload = {
    "recipients": ["+233201234567", "+233241234567"],
    "message": "Hello from Callbly! This is a bulk message.",
    "sender_name": "MyBrand"
}
headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Accept": "application/json"
}

response = requests.post(url, json=payload, headers=headers)
print(response.json())</code></pre>
                    </div>
                    <div id="bulk-curl" class="tab-content">
<pre class="!m-0"><code class="language-bash">curl -X POST "https://callbly.com/api/v1/sms/send-bulk" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -H "Accept: application/json" \
     -d '{
           "recipients": ["+233201234567", "+233241234567"],
           "message": "Hello from Callbly! This is a bulk message.",
           "sender_name": "MyBrand"
         }'</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 mb-12">

        <!-- Endpoint: Send Single SMS -->
        <div id="send-single" class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Send Single SMS</h2>
            <div class="flex items-center mb-6">
                <span class="endpoint-method method-post">POST</span>
                <code class="text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200">/api/v1/sms/send</code>
            </div>
            
            <p class="text-md text-gray-600 mb-6">
                Send a single SMS message to one specific recipient.
            </p>

            <h4 class="font-semibold text-gray-900 mb-2">Request Body (JSON)</h4>
            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-md overflow-hidden mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parameter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">recipient</td>
                        <td class="px-4 py-3 text-gray-500">String</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> A single phone number.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">message</td>
                        <td class="px-4 py-3 text-gray-500">String</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> The body of the SMS message.</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 font-mono text-gray-900">sender_name</td>
                        <td class="px-4 py-3 text-gray-500">String</td>
                        <td class="px-4 py-3 text-gray-600"><strong>Required.</strong> Approved sender ID (max 11 characters).</td>
                    </tr>
                </tbody>
            </table>

            <div class="bg-dark rounded-lg overflow-hidden shadow-lg mt-8">
                <div class="flex border-b border-gray-700 bg-gray-900 px-2 pt-2">
                    <button class="tab-btn active" onclick="switchTab(this, 'single-php')">PHP</button>
                    <button class="tab-btn" onclick="switchTab(this, 'single-curl')">cURL</button>
                </div>
                
                <div class="p-4 bg-dark text-sm">
                    <div id="single-php" class="tab-content active">
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->post('https://callbly.com/api/v1/sms/send', [
        'recipient' => '+233201234567',
        'message' => 'Hello from Callbly!',
        'sender_name' => 'MyBrand',
    ]);</code></pre>
                    </div>
                    <div id="single-curl" class="tab-content">
<pre class="!m-0"><code class="language-bash">curl -X POST "https://callbly.com/api/v1/sms/send" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "recipient": "+233201234567",
           "message": "Hello from Callbly!",
           "sender_name": "MyBrand"
         }'</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-gray-200 mb-12">

        <!-- Endpoint: Check Balance -->
        <div id="check-balance" class="mb-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Check Wallet Balance</h2>
            <div class="flex items-center mb-6">
                <span class="endpoint-method method-get">GET</span>
                <code class="text-gray-700 text-sm bg-gray-100 px-2 py-1 rounded border border-gray-200">/api/v1/wallet/balance</code>
            </div>
            
            <p class="text-md text-gray-600 mb-6">
                Retrieve your current wallet balance to ensure you have enough credits before sending messages.
            </p>

            <div class="bg-dark rounded-lg overflow-hidden shadow-lg mt-8">
                <div class="flex border-b border-gray-700 bg-gray-900 px-2 pt-2">
                    <button class="tab-btn active" onclick="switchTab(this, 'balance-php')">PHP</button>
                    <button class="tab-btn" onclick="switchTab(this, 'balance-node')">Node.js</button>
                    <button class="tab-btn" onclick="switchTab(this, 'balance-python')">Python</button>
                    <button class="tab-btn" onclick="switchTab(this, 'balance-curl')">cURL</button>
                </div>
                
                <div class="p-4 bg-dark text-sm">
                    <div id="balance-php" class="tab-content active">
<pre class="!m-0"><code class="language-php">&lt;?php
$response = Http::withToken('YOUR_API_TOKEN')
    ->acceptJson()
    ->get('https://callbly.com/api/v1/wallet/balance');

if ($response->successful()) {
    $balance = $response->json()['data']['balance'];
    echo "Current Balance: ₵" . $balance;
}</code></pre>
                    </div>
                    <div id="balance-node" class="tab-content">
<pre class="!m-0"><code class="language-javascript">const axios = require('axios');

axios.get('https://callbly.com/api/v1/wallet/balance', {
  headers: {
    'Authorization': 'Bearer YOUR_API_TOKEN',
    'Accept': 'application/json'
  }
})
.then(response => console.log(response.data.data.balance))
.catch(error => console.error(error));</code></pre>
                    </div>
                    <div id="balance-python" class="tab-content">
<pre class="!m-0"><code class="language-python">import requests

url = "https://callbly.com/api/v1/wallet/balance"
headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Accept": "application/json"
}

response = requests.get(url, headers=headers)
print(response.json()['data']['balance'])</code></pre>
                    </div>
                    <div id="balance-curl" class="tab-content">
<pre class="!m-0"><code class="language-bash">curl -X GET "https://callbly.com/api/v1/wallet/balance" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Accept: application/json"</code></pre>
                    </div>
                </div>
            </div>
            
            <h4 class="font-semibold text-gray-900 mt-8 mb-2">Example Response</h4>
            <div class="bg-gray-50 border border-gray-200 rounded-md p-4 overflow-x-auto">
<pre class="!m-0"><code class="language-json">{
  "success": true,
  "data": {
    "balance": 1450.50,
    "currency": "GHS",
    "formatted_balance": "₵1,450.50"
  }
}</code></pre>
            </div>
        </div>
        
    </main>
</div>

<!-- Prism.js for Syntax Highlighting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-markup-templating.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-php.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-python.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-json.min.js"></script>

<script>
    // Simple tab switching logic
    function switchTab(btn, targetId) {
        // Find parent container
        const container = btn.closest('.bg-dark');
        
        // Remove active class from all buttons and contents in this block
        container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        container.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked button and target content
        btn.classList.add('active');
        document.getElementById(targetId).classList.add('active');
    }
</script>

</body>
</html>
