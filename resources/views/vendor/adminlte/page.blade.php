@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')
@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation (fullscreen mode) --}}
        @if($preloaderHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script>
        @can('is-costing')
            document.addEventListener('DOMContentLoaded', function() {
                const updatePiSidebarCounts = function() {
                    fetch('{{ route('price-indications.counts') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        // Read the stream exactly once
                        return response.json();
                    })
                    .then(function(data) {
                        // Log the actual parsed data here if you need to debug
                        // console.log('Parsed data:', data);

                        if (!data || data.error) {
                            return;
                        }

                        // ✅ Fix: Use native CSS selectors to get the child span
                        const forCostingLabel = document.querySelector('#pi-for-costing-label span');
                        const costingInprogressLabel = document.querySelector('#pi-costing-inprogress-label span');
                        const costedLabel = document.querySelector('#pi-costed-label span');
                        const bomProcessingLabel = document.querySelector('#bom-for-processing-label span');
                        const bomProcessingInProgressLabel = document.querySelector('#bom-processing-inprogress-label span');
                        const bomCompletedLabel = document.querySelector('#bom-completed-label span');

                        if (forCostingLabel) {
                            const count = Number(data.for_costing || 0);
                            forCostingLabel.textContent = count > 0 ? count : '';
                            forCostingLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }

                        if (costingInprogressLabel) {
                            const count = Number(data.costing_inprogress || 0);
                            costingInprogressLabel.textContent = count > 0 ? count : '';
                            costingInprogressLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }

                        if (costedLabel) {
                            const count = Number(data.costed || 0);
                            costedLabel.textContent = count > 0 ? count : '';
                            costedLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }

                        if (bomProcessingLabel) {
                            const count = Number(data.bom_processing || 0);
                            bomProcessingLabel.textContent = count > 0 ? count : '';
                            bomProcessingLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }

                        if (bomProcessingInProgressLabel) {
                            const count = Number(data.bom_processing_inprogress || 0);
                            bomProcessingInProgressLabel.textContent = count > 0 ? count : '';
                            bomProcessingInProgressLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }

                        if (bomCompletedLabel) {
                            const count = Number(data.bom_completed || 0);
                            bomCompletedLabel.textContent = count > 0 ? count : '';
                            bomCompletedLabel.style.display = count > 0 ? 'inline-flex' : 'none';
                        }
                    })
                    .catch(function(error) {
                        // Silent failure; labels stay as they are.
                        // console.error('Sidebar count update failed:', error);
                    });
                };

                // Run immediately on load
                updatePiSidebarCounts();
                
                // Then run every 30 seconds
                setInterval(updatePiSidebarCounts, 30000);
            });
        @endcan
    </script>
@stop
