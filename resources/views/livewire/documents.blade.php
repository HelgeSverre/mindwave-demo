<div>
    <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
        <form wire:submit.prevent="save" class="m-0 p-0">
            <input
                class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-gray-200 bg-clip-padding px-3 py-[0.32rem] text-base font-normal text-gray-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem] file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-gray-100 file:px-3 file:py-[0.32rem] file:text-gray-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px] file:[margin-inline-end:0.75rem] hover:file:bg-gray-200 focus:border-primary focus:text-gray-700 focus:outline-none"
                type="file"
                wire:model="uploadedDocuments"
                id="fileUpload"
            />
        </form>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg mb-4 p-4">
        <input
            type="search"
            wire:model.debounce="search"
            class="block w-full rounded-md border-0 py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-purple-500 sm:text-sm sm:leading-6"
            placeholder="Search..."
        >
    </div>

    <div class="bg-white border border-gray-200 rounded-lg">
        <div class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
            <ul role="list" class="divide-y divide-gray-100 rounded-md">
                @foreach($documents as $document)
                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                        <div class="flex w-0 flex-1 items-center">
                            <x-heroicon-s-document-text class="h-5 w-5 flex-shrink-0 text-gray-400"/>
                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                <span class="truncate font-medium">{{ $document->filename }}</span>
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
        {!! $documents->links() !!}
    </div>
</div>
