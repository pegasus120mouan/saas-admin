<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs - Business Suite ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {"50":"#f0fdf4","100":"#dcfce7","200":"#bbf7d0","300":"#86efac","400":"#4ade80","500":"#22c55e","600":"#16a34a","700":"#15803d","800":"#166534","900":"#14532d","950":"#052e16"}
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Business Suite</span>
                </div>
                <a href="#" class="px-4 py-2 text-primary-600 font-medium hover:bg-primary-50 rounded-lg">Connexion</a>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="py-16 text-center">
        <div class="max-w-4xl mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Choisissez le plan adapté à votre entreprise
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Démarrez gratuitement pendant 14 jours. Aucune carte bancaire requise.
            </p>

            <!-- Toggle Mensuel/Annuel -->
            <div class="inline-flex items-center gap-4 bg-white rounded-full p-1 shadow-sm border border-gray-200">
                <button type="button" id="btn-monthly" onclick="togglePricing('monthly')" class="px-6 py-2 rounded-full text-sm font-medium bg-primary-600 text-white">
                    Mensuel
                </button>
                <button type="button" id="btn-yearly" onclick="togglePricing('yearly')" class="px-6 py-2 rounded-full text-sm font-medium text-gray-600 hover:text-gray-900">
                    Annuel <span class="text-primary-600 text-xs">-20%</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Plans -->
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min(count($plans), 4) }} gap-8">
                @foreach($plans as $plan)
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $plan->is_popular ? 'border-primary-500 relative' : 'border-transparent' }} overflow-hidden">
                        @if($plan->is_popular)
                            <div class="absolute top-0 right-0 bg-primary-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                                POPULAIRE
                            </div>
                        @endif

                        <div class="p-8">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                            <p class="text-gray-500 text-sm mb-6">{{ $plan->description }}</p>

                            <div class="mb-6">
                                <div class="price-monthly {{ $plan->price_monthly > 0 ? '' : 'hidden' }}">
                                    <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 0) }}€</span>
                                    <span class="text-gray-500">/mois</span>
                                </div>
                                <div class="price-yearly hidden">
                                    <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->monthly_equivalent, 0) }}€</span>
                                    <span class="text-gray-500">/mois</span>
                                    <p class="text-sm text-primary-600 mt-1">Facturé {{ number_format($plan->price_yearly, 0) }}€/an</p>
                                </div>
                                @if($plan->price_monthly == 0)
                                    <span class="text-4xl font-bold text-gray-900">Gratuit</span>
                                @endif
                            </div>

                            <a href="#" class="block w-full py-3 px-4 text-center font-semibold rounded-lg {{ $plan->is_popular ? 'bg-primary-600 text-white hover:bg-primary-700' : 'bg-gray-100 text-gray-900 hover:bg-gray-200' }} transition-colors mb-8">
                                Commencer l'essai gratuit
                            </a>

                            <div class="space-y-4">
                                <p class="text-sm font-semibold text-gray-900">Inclus :</p>
                                
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $plan->max_users == -1 ? 'Utilisateurs illimités' : $plan->max_users . ' utilisateur(s)' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $plan->max_customers == -1 ? 'Clients illimités' : $plan->max_customers . ' clients' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span>{{ $plan->max_invoices_per_month == -1 ? 'Factures illimitées' : $plan->max_invoices_per_month . ' factures/mois' }}</span>
                                    </div>

                                    @if($plan->feature_quotes)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Devis</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_expenses)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Gestion des dépenses</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_reports)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Rapports avancés</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_stock_management)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Gestion de stock</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_multi_currency)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Multi-devises</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_api_access)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Accès API</span>
                                        </div>
                                    @endif

                                    @if($plan->feature_priority_support)
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>Support prioritaire</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Questions fréquentes</h2>
            
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Puis-je changer de plan à tout moment ?</h3>
                    <p class="text-gray-600">Oui, vous pouvez upgrader ou downgrader votre plan à tout moment. Les changements prennent effet immédiatement.</p>
                </div>
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Comment fonctionne l'essai gratuit ?</h3>
                    <p class="text-gray-600">Vous bénéficiez de 14 jours d'essai gratuit avec accès à toutes les fonctionnalités. Aucune carte bancaire n'est requise.</p>
                </div>
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Mes données sont-elles sécurisées ?</h3>
                    <p class="text-gray-600">Absolument. Vos données sont chiffrées et sauvegardées quotidiennement. Nous respectons le RGPD.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Puis-je annuler mon abonnement ?</h3>
                    <p class="text-gray-600">Oui, vous pouvez annuler à tout moment. Vous conservez l'accès jusqu'à la fin de votre période de facturation.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400">© {{ date('Y') }} Business Suite. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        function togglePricing(type) {
            const monthlyBtn = document.getElementById('btn-monthly');
            const yearlyBtn = document.getElementById('btn-yearly');
            const monthlyPrices = document.querySelectorAll('.price-monthly');
            const yearlyPrices = document.querySelectorAll('.price-yearly');

            if (type === 'monthly') {
                monthlyBtn.classList.add('bg-primary-600', 'text-white');
                monthlyBtn.classList.remove('text-gray-600');
                yearlyBtn.classList.remove('bg-primary-600', 'text-white');
                yearlyBtn.classList.add('text-gray-600');
                monthlyPrices.forEach(el => el.classList.remove('hidden'));
                yearlyPrices.forEach(el => el.classList.add('hidden'));
            } else {
                yearlyBtn.classList.add('bg-primary-600', 'text-white');
                yearlyBtn.classList.remove('text-gray-600');
                monthlyBtn.classList.remove('bg-primary-600', 'text-white');
                monthlyBtn.classList.add('text-gray-600');
                yearlyPrices.forEach(el => el.classList.remove('hidden'));
                monthlyPrices.forEach(el => el.classList.add('hidden'));
            }
        }
    </script>
</body>
</html>
