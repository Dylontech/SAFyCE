<!-- @auth
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
           aria-label="Show notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path
                    d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
            </svg>
            {{-- <span class="badge bg-red">{{ Auth::user()->unreadNotifications->count() }}</span> --}}
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notificaciones</h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                    {{-- @foreach (Auth::user()->unreadNotifications as $notification)
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col text-truncate">
                                    <a href="#" class="text-body d-block">{{ $notification->data['message'] }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                </div>
            </div>
        </div>
    </div>
@endif -->
