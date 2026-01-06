<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FAQ - eVubaConnect</title>

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@500;700&display=swap"
        rel="stylesheet">

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
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

<body class="bg-gray-50 text-gray-800 antialiased font-sans">

    <div class="min-h-screen flex flex-col">
        @include('web.header')

        <main class="flex-grow pt-40 pb-16">
            <div class="container mx-auto px-4 max-w-4xl">

                <!-- Header Section -->
                <div class="text-center mb-16" x-data>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 font-outfit">
                        Got Questions? We've Got <span class="text-black">Answers!</span>
                    </h1>
                    <p class="text-xl text-gray-500 font-light mb-8">
                        We are here to answer all your questions.
                    </p>

                    <!-- Search Input -->
                    <div class="max-w-xl mx-auto relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-black transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-on:input="$dispatch('search-faq', $el.value)"
                            placeholder="Search for answers..."
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-gray-200 bg-white shadow-sm focus:ring-2 focus:ring-black/5 focus:border-black transition-all outline-none text-gray-700 placeholder-gray-400">
                    </div>
                </div>

                <!-- FAQ Accordion -->
                <div x-data="{ 
                    search: '',
                    active: null,
                    items: [
                        {
                            question: 'Will eVuba Connect completely replace manual sales process?',
                            answer: 'No, it works alongside manual sales process but simplifies the specific processes for real time and automated support management.', 
                        },
                        {
                            question: 'What are payroll-based taxes?',
                            answer: 'Payroll-based taxes refer to the deductions withheld by employers from employees\' salaries. These include PAYE (pay as you earn) and contributions such as Pension, Maternity Leave, CBHI Subsidy, and Medical/RAMA for medical insurance users.',
                        },
                        {
                            question: 'Who should I contact if I have questions about the eVuba Connect platform?',
                            answer: 'You can contact our support team via the contact page or call our help line at +250786325291.',
                        },
                        {
                            question: 'What is eVubaConnect?',
                            answer: 'eVubaConnect is an all-in-one business management platform designed to streamline operations, manage customers, and optimize team performance through AI-powered tools and real-time tracking.',
                        },
                        {
                            question: 'Is my data secure?',
                            answer: 'Absolutely. We use industry-standard encryption (AES-256) and security protocols to ensure that your business and customer data remains private and protected at all times.',
                        }
                    ],
                    get filteredItems() {
                        if (this.search === '') return this.items;
                        return this.items.filter(item => 
                            item.question.toLowerCase().includes(this.search.toLowerCase()) || 
                            item.answer.toLowerCase().includes(this.search.toLowerCase())
                        );
                    }
                }" @search-faq.window="search = $event.detail" class="space-y-6">

                    <template x-for="(item, index) in filteredItems" :key="index">
                        <div
                            class="bg-white rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200 border border-transparent hover:border-gray-100">
                            <button @click="active = active === index ? null : index"
                                class="w-full flex items-center justify-between text-left focus:outline-none group">
                                <span class="text-lg font-bold text-gray-800 group-hover:text-black transition-colors"
                                    x-text="item.question"></span>

                                <!-- Toggle Icon -->
                                <div class="shrink-0 ml-4">
                                    <template x-if="active !== index">
                                        <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </template>
                                    <template x-if="active === index">
                                        <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4"></path>
                                        </svg>
                                    </template>
                                </div>
                            </button>

                            <div x-show="active === index" x-collapse x-cloak
                                class="mt-4 text-gray-600 leading-relaxed">
                                <p x-text="item.answer"></p>
                            </div>
                        </div>
                    </template>

                </div>
            </div>
            <!-- CTA Section -->
            <div class="mt-24 mb-12">
                <div
                    class="bg-blue-600 rounded-2xl p-8 md:p-12 text-center text-white shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold mb-4">Still have questions?</h2>
                        <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto">
                            Can't find the answer you're looking for? Please chat to our friendly team.
                        </p>
                        <a href="{{ route('web.contact') }}"
                            class="inline-block px-8 py-3.5 bg-white text-blue-600 rounded-xl font-bold shadow-sm hover:shadow-lg hover:bg-gray-50 transition-all duration-300">
                            Contact Us
                        </a>
                    </div>

                    <!-- Decorative circles -->
                    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 bg-black/10 rounded-full blur-3xl">
                    </div>
                </div>
            </div>
    </div>
    </main>

    @include('web.footer')
    </div>

    <style>
        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</body>

</html>