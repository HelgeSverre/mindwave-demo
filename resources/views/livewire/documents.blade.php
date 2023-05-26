<div>
    <section x-data="Uploader({
        multiple: true,
        resetFilesOnAllFilesCompleted: true,
        resetFilesOnAllFilesCompletedTimeout: 2500,
        onAllFilesCompleted: (files) => {
            $wire.set('uploads', files);
            console.log(files);
            $wire.saveUploads();
        }
    })">

        <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
            <div class="relative">
                <input
                    type="file"
                    class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-gray-200 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-gray-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-gray-100 file:px-3 file:py-[0.32rem] file:text-gray-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-gray-200 focus:border-primary focus:text-gray-700 focus:outline-none"
                    multiple
                    x-on:change="onFileInputChanged"
                />
            </div>

            <div class="mt-4" x-show="uploadedFiles.length" x-cloak x-collapse.duration.300ms>
                <dd class="mt-1 text-sm text-gray-900">
                    <div class="w-full">
                        <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                            <template x-for="(file, index) in uploadedFiles" :key="file.id">
                                <li class="relative">
                                    <div
                                        class="absolute inset-0 opacity-20 transition-all z-10"
                                        x-bind:class="file.error ? 'bg-red-300' : 'bg-emerald-300'"
                                        x-bind:style="`width: ${file.progress}%`"
                                    ></div>

                                    <div class="relative pl-3 pr-4 py-3 flex items-center justify-between text-sm z-20">
                                        <div class="w-0 flex-1 flex items-center">
                                            <x-heroicon-s-document
                                                class="flex-shrink-0 h-5 w-5 text-gray-400"
                                                x-show="file.error === false"
                                            />

                                            <x-heroicon-s-document-minus
                                                class="flex-shrink-0 h-5 w-5 text-red-400"
                                                x-show="file.error === true"
                                            />
                                            <span
                                                class="ml-2 flex-1 w-0 truncate"
                                                x-bind:class="file.error ? 'text-red-400' : 'text-gray-900'"
                                                x-text="file.name"
                                            ></span>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </div>
                </dd>
            </div>
        </div>
    </section>


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
            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden" wire:poll.1s.visible>
                <div class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                    <ul role="list" class="divide-y divide-gray-100 rounded-md">
                        <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6 bg-gray-50 border-b">
                            <div class="flex w-0 flex-1 items-center">
                                <x-heroicon-o-document class="h-5 w-6 flex-shrink-0 text-gray-400" />

                                <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                    <span class="truncate font-medium">Filename</span>
                                </div>
                            </div>

                            <div class="ml-4 flex-shrink-0 space-x-4">
                                <button
                                    wire:click="deleteAll"
                                    wire:loading.attr="disabled"
                                    wire:target="deleteAll"
                                    class="font-medium text-red-500 hover:text-red-400"
                                >
                                    Delete all
                                </button>
                            </div>
                        </li>

                        @foreach($this->documents as $document)
                            <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6"
                                wire:key="{{ $document->id }}">
                                <div class="flex w-0 flex-1 items-center">
                                    <x-heroicon-o-document class="h-5 w-6 flex-shrink-0 text-gray-400" />

                                    <div class="ml-4 flex items-center min-w-0 flex-1 gap-2">
                                        <span class="truncate font-medium">{{ $document->filename }}</span>

                                        @if($document->state === \App\Enums\DocumentState::pending)
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-50 px-1.5 py-0.5 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                {{ $document->state->label() }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::empty || $document->state === \App\Enums\DocumentState::unsupported)
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-50 px-1.5 py-0.5 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                                                {{ $document->state->label() }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::failed)
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-50 px-1.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                                {{ $document->state->label() }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::consuming)
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                                {{ $document->state->label() }}
                                            </span>
                                        @elseif($document->state === \App\Enums\DocumentState::consumed)
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                {{ $document->state->label() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="ml-4 flex-shrink-0 space-x-4">
                                    <span class="truncate font-normal text-gray-500">{{ $document->mime }}</span>
                                </div>
                                <div class="ml-4 flex-shrink-0 space-x-4">
                                    <a href="{{ route("documents.show", $document) }}"
                                       class="font-medium text-purple-500 hover:text-purple-400">
                                        View
                                    </a>

                                    <button
                                        wire:click="delete({{ $document->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="delete"
                                        class="font-medium text-red-500 hover:text-red-400"
                                    >
                                        Delete
                                    </button>
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
