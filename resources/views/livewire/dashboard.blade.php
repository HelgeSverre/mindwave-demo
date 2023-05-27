<div>
    <div class="border-b border-gray-200 pb-4 mb-8">
        <h3 class="text-base font-semibold leading-6 text-gray-900">Documents</h3>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
        <dl class="grid max-w-screen-xl grid-cols-2 gap-8 p-8 mx-auto text-gray-900 sm:grid-cols-3">
            @foreach(\App\Enums\DocumentState::cases() as $state)
                <div class="flex flex-col items-start justify-center">
                    <dt class="mb-1 text-lg font-bold tabular-nums">
                        {{ \App\Models\Document::inState($state)->count() }}
                    </dt>
                    <dd class="text-gray-500">{{ $state->label() }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
</div>
