<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @auth
                        @if(auth()->user()->role == "Administrator")
                            {{ __("Welcome Back Admin!") }}
                        @else
                            {{ __("Welcome Back Petugas!") }}
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Container untuk dua chart -->
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Chart.js Bar Chart -->
                <div class="bg-white p-6 shadow-md rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Bar Chart</h3>
                    <canvas id="chartjs-bar"></canvas>
                </div>

                <!-- ApexCharts Pie Chart -->
                <div class="bg-white p-6 shadow-md rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Pie Chart</h3>
                    <div id="apexcharts-pie"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="{{ asset('js/chart-bar.js') }}"></script>
    <script src="{{ asset('js/chart-pie.js') }}"></script>

</x-app-layout>
