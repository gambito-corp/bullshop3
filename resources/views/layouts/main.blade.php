@include('layouts.assets.head')
@include('layouts.assets.style')
@include('layouts.assets.bodyFirst')
<!-- Page Content -->
<main>
    <div class="py-12">
        <div class="max-w-7xl mt-12 mx-auto sm:px-6 lg:px-8">
            <div class=" bg-white overflow-hidden shadow-xl sm:rounded lg">
                @yield('content')
            </div>
        </div>
    </div>
</main>
@include('layouts.assets.footer')
@include('layouts.assets.script')
@include('layouts.assets.bodyLast')
