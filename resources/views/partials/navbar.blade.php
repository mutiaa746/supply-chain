<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="/">

            <i class="bi bi-globe2 me-2"></i>

            Supply Chain Risk Monitoring

        </a>

        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item me-3">

                    <span class="text-white">

                        <i class="bi bi-calendar-event me-1"></i>

                        {{ now()->format('d M Y') }}

                    </span>

                </li>

                <li class="nav-item">

                    <span class="badge bg-light text-primary p-2">

                        <i class="bi bi-person-circle me-1"></i>

                        Administrator

                    </span>

                </li>

            </ul>

        </div>

    </div>

</nav>