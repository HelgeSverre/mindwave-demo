<div>
    <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
        <div class="relative">
            <div
                wire:loading.flex
                wire:target="uploadedDocument"
                class="z-20 absolute inset-0 bg-gray-200/70 flex items-center justify-center">
                <x-heroicon-s-cog-6-tooth class="h-5 w-5 flex-shrink-0 text-gray-500 animate-spin" />
                <span class="text-sm m-2 text-gray-600">Uploading...</span>
            </div>
            <input
                type="file"
                class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-gray-200 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-gray-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-gray-100 file:px-3 file:py-[0.32rem] file:text-gray-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-gray-200 focus:border-primary focus:text-gray-700 focus:outline-none"
                wire:model="uploadedDocument"
            />
        </div>
    </div>


    @if($this->documents->isNotEmpty())
        <section>
            <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
                <input
                    type="search"
                    wire:model.debounce="search"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 sm:text-sm sm:leading-6"
                    placeholder="Search..."
                >
            </div>
            <div class="bg-white border border-gray-200 rounded-lg" wire:poll.1s.visible>
                <div class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    <ul role="list" class="divide-y divide-gray-100 rounded-md">
                        @foreach($this->documents as $document)
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                                <div class="flex w-0 flex-1 items-center">
                                    <x-heroicon-s-document class="h-5 w-6 flex-shrink-0 text-gray-400" />

                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium">{{ $document->filename }}</span>

                                        @if($document->state === \App\Enums\DocumentState::pending)
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-50 px-1.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                {{ $document->state }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::empty)
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-50 px-1.5 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                                {{ $document->state }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::failed)
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-50 px-1.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                                {{ $document->state }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::consuming)
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                {{ $document->state }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::consumed)
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                {{ $document->state }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-4 flex-shrink-0">
                                    <a href="{{ route("documents.show", $document) }}"
                                       class="font-medium text-purple-500 hover:text-purple-400">
                                        View
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="mt-4">
                {!! $this->documents->links() !!}
            </div>
        </section>
    @else
        <div class="p-4 text-center text-gray-700 text-sm">No documents uploaded yet.</div>
    @endif
</div>
