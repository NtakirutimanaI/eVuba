<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FAQ - eVubaConnect</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }
        .bg-gradient-brand {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        }
        /* Hide scrollbar for clean look in some elements if needed */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="text-gray-800 antialiased bg-gray-50 relative overflow-x-hidden selection:bg-emerald-500 selection:text-white">

    <!-- Background Pattern -->
    <div class="fixed inset-0 z-0 pointer-events-none opacity-20" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;"></div>
    
    <!-- Abstract Blobs -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        @include('web.header')

        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative pt-24 pb-16 lg:pt-32 lg:pb-24">
                <div class="container mx-auto px-4 text-center relative z-10">
                    <span class="inline-block py-1 px-3 rounded-full bg-blue-100/80 text-blue-600 text-sm font-semibold mb-6 border border-blue-200 backdrop-blur-sm animate-fade-in-up">
                        Help Center
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6 tracking-tight animate-fade-in-up animation-delay-200">
                        How can we <span class="bg-clip-text text-transparent bg-gradient-brand">help you?</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto mb-10 animate-fade-in-up animation-delay-300">
                        Find answers to common questions about eVubaConnect, manage your account, and learn how to optimize your business workflow.
                    </p>

                    <!-- Search Box -->
                    <div class="max-w-xl mx-auto relative group animate-fade-in-up animation-delay-400" x-data>
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                        </div>
                        <input 
                            type="text" 
                            x-on:input="$dispatch('search-faq', $el.value)"
                            placeholder="Search for answers..." 
                            class="w-full pl-11 pr-4 py-4 rounded-2xl border border-gray-200 bg-white/80 backdrop-blur-xl shadow-lg focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none text-gray-700 placeholder-gray-400 text-lg"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <kbd class="hidden md:inline-block px-2 py-1 bg-gray-100 border border-gray-200 rounded-lg text-xs text-gray-500 font-sans">
                                /
                            </kbd>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FAQ Section -->
            <section class="pb-24 px-4" x-data="{ 
                search: '', 
                active: null,
                items: [
                    {
                        question: 'What is eVubaConnect?',
                        answer: 'eVubaConnect is an all-in-one business management platform designed to streamline operations, manage customers, and optimize team performance through AI-powered tools and real-time tracking.',
                        icon: 'fa-rocket'
                    },
                    {
                        question: 'How do I get started?',
                        answer: 'You can get started by registering an account through our website. Once registered, you can choose the plan that best fits your business needs and start setting up your dashboard. The onboarding process is simple and guided.',
                        icon: 'fa-play-circle'
                    },
                    {
                        question: 'Is my data secure?',
                        answer: 'Absolutely. We use industry-standard encryption (AES-256) and security protocols to ensure that your business and customer data remains private and protected at all times. We perform regular security audits.',
                        icon: 'fa-shield-alt'
                    },
                    {
                        question: 'Do you offer 24/7 support?',
                        answer: 'Yes, our premium support team is available 24/7 to assist you with any questions or technical issues. You can reach us via our support center, live chat, or dedicated email support.',
                        icon: 'fa-headset'
                    },
                    {
                        question: 'Can I integrate other tools?',
                        answer: 'Yes, eVubaConnect supports a wide range of integrations with popular tools like Slack, QuickBooks, Stripe, and Google Workspace to help you maintain a seamless workflow across your entire tech stack.',
                        icon: 'fa-plug'
                    }
                ],
                get filteredItems() {
                    if (this.search === '') return this.items;
                    return this.items.filter(item => 
                        item.question.toLowerCase().includes(this.search.toLowerCase()) || 
                        item.answer.toLowerCase().includes(this.search.toLowerCase())
                    );
                }
            }" @search-faq.window="search = $event.detail">
                
                <div class="max-w-3xl mx-auto">
                    <!-- Categories (Visual embellishment) -->
                    <div class="flex flex-wrap justify-center gap-3 mb-12 animate-fade-in-up animation-delay-500">
                        <button class="px-5 py-2 rounded-full bg-white/70 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all text-sm font-medium shadow-sm backdrop-blur-sm">General</button>
                        <button class="px-5 py-2 rounded-full bg-white/70 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all text-sm font-medium shadow-sm backdrop-blur-sm">Billing</button>
                        <button class="px-5 py-2 rounded-full bg-white/70 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all text-sm font-medium shadow-sm backdrop-blur-sm">Account</button>
                        <button class="px-5 py-2 rounded-full bg-white/70 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all text-sm font-medium shadow-sm backdrop-blur-sm">Support</button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <div class="glass-card rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-lg"
                                 :class="active === index ? 'ring-2 ring-blue-500/20' : ''">
                                <button 
                                    @click="active = active === index ? null : index"
                                    class="w-full px-6 py-5 text-left flex items-center justify-between gap-4 focus:outline-none"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-emerald-50 text-blue-600 shrink-0 border border-white shadow-sm">
                                            <i :class="['fas', item.icon]"></i>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-800" x-text="item.question"></span>
                                    </div>
                                    <span class="shrink-0 w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center transition-transform duration-300"
                                          :class="active === index ? 'rotate-180 bg-blue-100 text-blue-600' : 'text-gray-400'">
                                        <i class="fas fa-chevron-down text-sm"></i>
                                    </span>
                                </button>
                                
                                <div x-show="active === index" 
                                     x-collapse
                                     x-cloak>
                                    <div class="px-6 pb-6 pl-[4.5rem] text-gray-600 leading-relaxed">
                                        <p x-text="item.answer"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Empty State -->
                        <div x-show="filteredItems.length === 0" class="text-center py-12 text-gray-500 glass-card rounded-2xl">
                            <div class="text-4xl mb-3">😕</div>
                            <p class="text-lg">No questions found matching your search.</p>
                            <button @click="search = ''; $dispatch('clear-search-input')" class="mt-4 text-blue-600 hover:text-blue-700 font-medium hover:underline">Clear search</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="py-16 px-4">
                <div class="max-w-4xl mx-auto bg-gradient-brand rounded-3xl p-8 md:p-12 text-center text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-black/10 rounded-full blur-3xl"></div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 relative z-10">Still have questions?</h2>
                    <p class="text-blue-50 text-lg mb-8 max-w-xl mx-auto relative z-10">Can't find the answer you're looking for? Please chat to our friendly team.</p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center relative z-10">
                        <a href="{{ route('web.contact') }}" class="px-8 py-3.5 bg-white text-blue-600 rounded-xl font-bold shadow-lg hover:shadow-xl hover:bg-gray-50 hover:-translate-y-1 transition-all duration-300">
                            Contact Us
                        </a>
                        <!-- Assuming there might be a chat route or similar in future -->
                    </div>
                </div>
            </section>
        </main>

        @include('web.footer')
    </div>

    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        .animation-delay-200 { animation-delay: 0.2s; }
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-400 { animation-delay: 0.4s; }
        .animation-delay-500 { animation-delay: 0.5s; }
        
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
