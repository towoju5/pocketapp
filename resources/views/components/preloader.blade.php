<!-- preloader -->
<div class="loading-container js-loading-container fixed inset-0 flex items-center justify-center bg-gray-900 z-50">
    @include('components.preloader-main')
</div>


@push('scripts')
<script>
    const loader = document.querySelector(".loading-container.js-loading-container");

    // Hide on page load
    document.addEventListener("DOMContentLoaded", () => {
        if (loader) loader.classList.add("hidden");
    });

    // Show when any link with class .show-loader is clicked
    document.querySelectorAll("a.show-loader").forEach(link => {
        link.addEventListener("click", () => {
            if (loader) loader.classList.remove("hidden");
        });
    });
</script>
@endpush