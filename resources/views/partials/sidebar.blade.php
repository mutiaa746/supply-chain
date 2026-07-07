<div class="col-md-2 bg-light vh-100 border-end">

    <div class="pt-3">

        <h5 class="text-center mb-4">
            Menu
        </h5>

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/countries') }}">
                    Countries
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/weather') }}">
                    Weather
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/economic') }}">
                    Economic Indicator
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/exchange') }}">
                    Exchange Rate
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/news') }}">
                    News
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/ports') }}">
                    Ports
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('ports.map') }}">
                    🗺 Port Map
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/risk') }}">
                    Risk Score
                </a>
            </li>

        </ul>

    </div>

</div>