<div>


    <div class="max-w-3xl mx-auto divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-base font-semibold leading-6 text-gray-900">
                {{ $document->filename }}
            </h3>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <div class="prose max-w-none">
                {{ $document->text }}
            </div>
        </div>
    </div>
</div>
