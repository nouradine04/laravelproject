<x-filament::page>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Tableau de bord Gestionnaire</h1>

        <!-- Grille de cartes -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Carte Montant Total -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-wide opacity-80">Montant Total</p>
                        <p class="text-2xl font-bold">{{ number_format($totalRevenue, 2) }} €</p>
                    </div>
                </div>
            </div>

            <!-- Carte Nombre de Clients -->
            <div class="bg-gradient-to-r from-green-500 to-green-700 text-white rounded-lg shadow-lg p-6 transform hover:scale-105 transition-transform duration-300">
                <div class="flex items-center">
                    <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm uppercase tracking-wide opacity-80">Nombre de Clients</p>
                        <p class="text-2xl font-bold">{{ $clientCount }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>