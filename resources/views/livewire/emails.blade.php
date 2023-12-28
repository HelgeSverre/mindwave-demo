<div>
    <div class="mb-6">
        <input
            wire:model.blur="search"
            type="search"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            placeholder="Search emails..."
        />
    </div>

    <div>
        @if ($emails)
            <ul>
                @foreach ($emails as $email)
                    <div
                        class="mx-auto my-4 w-full overflow-hidden rounded-lg border border-gray-200 bg-white"
                        wire:key="email-{{ $email->id }}"
                    >
                        <div class="p-4">
                            <div class="flex items-center">
                                <div class="ml-2">
                                    <div
                                        class="text-sm font-semibold text-gray-900"
                                    >
                                        <span class="text-gray-600">
                                            {{ $email->vector_db_id }}
                                            ::
                                            {{ $email->subject }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        From:
                                        <span class="text-gray-600">
                                            {{ $email->from }}
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        To:
                                        <span class="text-gray-600">
                                            {{ $email->to }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 text-sm text-gray-600">
                                <div
                                    class="max-h-[300px] overflow-auto border border-gray-200 bg-gray-50 p-4 font-mono text-xs"
                                >
                                    {{ trim($email->body_text) }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </ul>
        @else
            <div class="text-center">
                <div class="text-gray-500">No emails found.</div>
            </div>
        @endif
    </div>
</div>
