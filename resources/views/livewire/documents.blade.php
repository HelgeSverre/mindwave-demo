<div>

    <div>
        <form wire:submit.prevent="save">
            <input type="file" wire:model="photos" multiple>

            @error('photos.*') <span class="error">{{ $message }}</span> @enderror

            <button type="submit">Save Photo</button>
        </form>

    </div>


    <div class="bg-white">
        <div class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
                @foreach($documents as $document)
                    <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                        <div class="flex w-0 flex-1 items-center">
                            <x-heroicon-s-paper-clip class="h-5 w-5 flex-shrink-0 text-gray-400"/>
                            <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                <span class="truncate font-medium">{{ $document->filename }}</span>
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0">
                            <a href="{{ route("documents.show", $document) }}"
                               class="font-medium text-indigo-600 hover:text-indigo-500">
                                View
                            </a>
                        </div>
                    </li>
                @endforeach

            </ul>
        </div>
    </div>

</div>
