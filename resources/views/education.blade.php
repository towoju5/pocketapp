<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forex Trading Guide</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Left sidebar - Table of Contents -->
        <div class="w-80 bg-gray-800 overflow-y-auto">
            <div class="p-4">
                <h2 class="text-xl font-bold mb-4">Table of Contents</h2>
                <ul class="list-none">
                    <li class="mb-2">
                        <a href="#foreword" class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>1. Foreword</span>
                            <span class="text-gray-400">→</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#forex-glossary" class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>2. Forex Glossary</span>
                            <span class="text-gray-400">→</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#about-forex" class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>3. About Forex</span>
                            <span class="text-gray-400">→</span>
                        </a>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>4. Introduction</span>
                            <button id="toggle-intro" class="text-gray-400">▼</button>
                        </div>
                        <ul id="intro-subsections" class="pl-5 list-none">
                            <li class="mb-1">
                                <a href="#margin-trading" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.1. Margin trading</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="#what-is-forex" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.2. What is Forex</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="#advantages" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.3. Advantages of Forex</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="#currency-pair" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.4. Currency pair</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="#lot-transaction" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.5. Lot and transaction</span>
                                </a>
                            </li>
                            <li class="mb-1">
                                <a href="#trade-order" class="flex items-center p-2 hover:bg-gray-700 rounded">
                                    <span>4.6. Trade. Order, transaction, position. Types of orders</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>5. Technical analysis</span>
                            <button class="text-gray-400">▼</button>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>6. Fundamental analysis</span>
                            <button class="text-gray-400">▼</button>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>7. Trading Psychology</span>
                            <button class="text-gray-400">▼</button>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>8. Risk and money management</span>
                            <button class="text-gray-400">▼</button>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="flex justify-between items-center p-2 hover:bg-gray-700 rounded">
                            <span>9. Creating trading strategy</span>
                            <button class="text-gray-400">▼</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex-1 overflow-y-auto p-10">
            <h1 class="text-4xl font-bold mb-8">Guide and specifics of Forex trading</h1>
            
            <section id="foreword" class="mb-10">
                <h2 class="text-2xl font-bold mb-4">1. Foreword</h2>
                <p class="mb-4">
                    In this section you will find a comprehensive guide as well as a Forex glossary that will help you to understand Forex trading and its specifics.
                </p>
                <p class="mb-4">
                    Use the left sidebar to navigate a topic that interests you, the guide will be displayed on the right side of the screen accordingly.
                </p>
            </section>

            <section id="forex-glossary" class="mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">2. Forex Glossary</h2>
                <p class="mb-4">
                    This section contains essential Forex terms and definitions to help you understand the terminology used in trading.
                </p>
            </section>

            <section id="about-forex" class="mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">3. About Forex</h2>
                <p class="mb-4">
                    An overview of the foreign exchange market, its history, and its importance in global finance.
                </p>
            </section>

            <section id="introduction" class="mb-10">
                <h2 class="text-2xl font-bold mb-4">4. Introduction</h2>
                
                <div id="margin-trading" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.1. Margin trading</h3>
                    <p class="mb-4">
                        Margin trading allows traders to control larger positions with a relatively small amount of capital. This section explains how margin works and its implications for your trading account.
                    </p>
                    <img src="https://via.placeholder.com/800x400/1e293b/ffffff?text=Margin+Trading+Example" alt="Margin Trading Example" class="w-full rounded mb-4">
                </div>

                <div id="what-is-forex" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.2. What is Forex</h3>
                    <p class="mb-4">
                        The Foreign Exchange Market (Forex) is the largest financial market in the world. This section introduces you to the basics of how it operates, who participates, and why it's so significant.
                    </p>
                </div>

                <div id="advantages" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.3. Advantages of Forex</h3>
                    <p class="mb-4">
                        The Forex market offers several advantages over other financial markets. We explore these benefits and how they can be leveraged in your trading strategy.
                    </p>
                </div>

                <div id="currency-pair" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.4. Currency pair</h3>
                    <p class="mb-4">
                        Currencies are traded in pairs. This section explains how currency pairs work, the different types of pairs, and how to read and interpret them.
                    </p>
                </div>

                <div id="lot-transaction" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.5. Lot and transaction</h3>
                    <p class="mb-4">
                        Lots are standardized units that define the trade size in Forex. We explain the different lot sizes and how they affect your trading volume and risk exposure.
                    </p>
                </div>

                <div id="trade-order" class="mb-6">
                    <h3 class="text-xl font-bold mb-3">4.6. Trade. Order, transaction, position. Types of orders</h3>
                    <p class="mb-4">
                        Orders are instructions to buy or sell currencies. This section covers the various types of orders available to traders and how to use them effectively.
                    </p>
                    <video controls class="w-full rounded mb-4">
                        <source src="#" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </section>

            <section id="technical-analysis" class="mb-10 hidden">
                <h2 class="text-2xl font-bold mb-4">5. Technical Analysis</h2>
                <p class="mb-4">
                    Technical analysis involves studying past market data to predict future price movements. This section covers key concepts and tools used in technical analysis.
                </p>
            </section>
        </div>
    </div>

    <script>
        // Toggle subsection visibility
        document.getElementById('toggle-intro').addEventListener('click', function() {
            const subsections = document.getElementById('intro-subsections');
            if (subsections.style.display === 'none') {
                subsections.style.display = 'block';
                this.textContent = '▼';
            } else {
                subsections.style.display = 'none';
                this.textContent = '▶';
            }
        });

        // Smooth scrolling for navigation
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                
                // Hide all sections except the target one
                const sections = document.querySelectorAll('section');
                sections.forEach(section => {
                    if (section.id === this.getAttribute('href').substring(1)) {
                        section.classList.remove('hidden');
                    } else {
                        section.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</body>
</html>