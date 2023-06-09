<div>
    <div
        class="mx-auto max-w-3xl divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow"
    >
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">
                {{ $document->filename }}
            </h3>
        </div>
        <div class="bg-gray-100 p-2 text-xs">
            <code>{{ $document->text }}</code>
        </div>
    </div>
</div>
