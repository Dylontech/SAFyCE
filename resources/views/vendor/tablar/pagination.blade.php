@if ($paginator->hasPages())
    <!-- Información de registros -->
    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 mb-4">
        <div class="pagination-info">
            <span class="text-muted">
                Mostrando <strong>{{ $paginator->firstItem() ?? 0 }}</strong> a <strong>{{ $paginator->lastItem() ?? 0 }}</strong> de <strong>{{ $paginator->total() }}</strong> resultados
            </span>
        </div>
        
        <!-- Paginación moderna y estética -->
        <nav aria-label="Navegación de páginas" class="pagination-modern">
            <div class="pagination-container">
                <!-- Botón Primera Página -->
                <div class="pagination-group">
                    @if (!$paginator->onFirstPage())
                        <a href="{{ $paginator->url(1) }}" class="pagination-btn pagination-edge" title="Primera página">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 17L6 12L11 7M18 17L13 12L18 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="pagination-btn pagination-edge pagination-disabled">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 17L6 12L11 7M18 17L13 12L18 7"/>
                            </svg>
                        </span>
                    @endif

                    <!-- Botón Anterior -->
                    @if (!$paginator->onFirstPage())
                        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn pagination-prev" title="Página anterior">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18L9 12L15 6"/>
                            </svg>
                            <span class="pagination-text">Anterior</span>
                        </a>
                    @else
                        <span class="pagination-btn pagination-prev pagination-disabled">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18L9 12L15 6"/>
                            </svg>
                            <span class="pagination-text">Anterior</span>
                        </span>
                    @endif
                </div>

                <!-- Páginas numeradas -->
                <div class="pagination-pages">
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="pagination-ellipsis">...</span>
                        @endif

                        @if (is_array($element))
                            @php
                                $current = $paginator->currentPage();
                                $total = $paginator->lastPage();
                                $pages = [];
                                
                                // Mostrar máximo 5 páginas
                                if ($total <= 7) {
                                    $pages = $element;
                                } else {
                                    if ($current <= 3) {
                                        $pages = array_slice($element, 0, 4, true);
                                        $pages = $pages + ['...' => ''] + array_slice($element, -1, 1, true);
                                    } elseif ($current >= $total - 2) {
                                        $pages = array_slice($element, 0, 1, true) + ['...' => ''] + array_slice($element, -4, 4, true);
                                    } else {
                                        $pages = array_slice($element, 0, 1, true) + ['...' => ''] + 
                                                array_slice($element, $current - 1, 3, true) + ['...' => ''] + 
                                                array_slice($element, -1, 1, true);
                                    }
                                }
                            @endphp
                            
                            @foreach ($pages as $page => $url)
                                @if ($page === '...')
                                    <span class="pagination-ellipsis">...</span>
                                @elseif ($page == $paginator->currentPage())
                                    <span class="pagination-page pagination-active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="pagination-page">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </div>

                <!-- Botones Siguiente y Última -->
                <div class="pagination-group">
                    <!-- Botón Siguiente -->
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn pagination-next" title="Página siguiente">
                            <span class="pagination-text">Siguiente</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18L15 12L9 6"/>
                            </svg>
                        </a>
                    @else
                        <span class="pagination-btn pagination-next pagination-disabled">
                            <span class="pagination-text">Siguiente</span>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18L15 12L9 6"/>
                            </svg>
                        </span>
                    @endif

                    <!-- Botón Última Página -->
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-btn pagination-edge" title="Última página">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 17L18 12L13 7M6 17L11 12L6 7"/>
                            </svg>
                        </a>
                    @else
                        <span class="pagination-btn pagination-edge pagination-disabled">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 17L18 12L13 7M6 17L11 12L6 7"/>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Indicador de página actual -->
            <div class="pagination-indicator">
                <span class="current-page">Página {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>
            </div>
        </nav>
    </div>

    <style>
    /* Paginación moderna y estética */
    .pagination-modern {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .pagination-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--tblr-bg-surface);
        padding: 0.75rem 1rem;
        border-radius: 12px;
        border: 1px solid var(--tblr-border-color);
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .pagination-group {
        display: flex;
        gap: 0.25rem;
    }

    .pagination-pages {
        display: flex;
        gap: 0.25rem;
        margin: 0 0.5rem;
    }

    /* Botones de paginación */
    .pagination-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        min-width: 3rem;
        height: 3rem;
        border: 1px solid var(--tblr-border-color);
        border-radius: 8px;
        background: var(--tblr-bg-surface);
        color: var(--tblr-body-color);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pagination-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(var(--tblr-primary-rgb), 0.1), transparent);
        transition: left 0.6s;
    }

    .pagination-btn:hover::before {
        left: 100%;
    }

    .pagination-btn:hover {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(var(--tblr-primary-rgb), 0.15);
    }

    .pagination-btn:active {
        transform: translateY(0);
    }

    .pagination-prev, .pagination-next {
        min-width: 6rem;
    }

    .pagination-edge {
        min-width: 2.5rem;
        padding: 0.75rem 0.5rem;
    }

    .pagination-disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background: var(--tblr-bg-surface-secondary);
    }

    .pagination-disabled:hover {
        transform: none;
        border-color: var(--tblr-border-color);
        background: var(--tblr-bg-surface-secondary);
        box-shadow: none;
    }

    /* Páginas numeradas */
    .pagination-page {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 3rem;
        height: 3rem;
        padding: 0.75rem;
        border: 1px solid var(--tblr-border-color);
        border-radius: 8px;
        background: var(--tblr-bg-surface);
        color: var(--tblr-body-color);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination-page:hover {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb), 0.05);
        transform: translateY(-1px);
    }

    .pagination-active {
        background: var(--tblr-primary) !important;
        color: white !important;
        border-color: var(--tblr-primary) !important;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(var(--tblr-primary-rgb), 0.3);
        transform: scale(1.05);
    }

    .pagination-ellipsis {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 3rem;
        color: var(--tblr-muted);
        font-weight: 500;
    }

    /* Indicador de página */
    .pagination-indicator {
        text-align: center;
    }

    .current-page {
        color: var(--tblr-muted);
        font-size: 0.875rem;
        font-weight: 500;
    }

    .pagination-text {
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pagination-container {
            flex-direction: column;
            gap: 0.75rem;
            padding: 1rem;
        }

        .pagination-pages {
            margin: 0;
            order: -1;
        }

        .pagination-group {
            width: 100%;
            justify-content: space-between;
        }

        .pagination-prev, .pagination-next {
            min-width: auto;
            flex: 1;
        }

        .pagination-edge {
            min-width: 2.5rem;
        }

        .pagination-page {
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0.5rem;
        }
    }

    @media (max-width: 480px) {
        .pagination-pages {
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-text {
            display: none;
        }

        .pagination-prev, .pagination-next {
            min-width: 2.5rem;
        }
    }

    /* Animaciones */
    @keyframes paginationSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .pagination-container {
        animation: paginationSlide 0.5s ease-out;
    }
    </style>
@endif