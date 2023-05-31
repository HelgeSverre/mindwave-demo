<div>
    <ul>
        @foreach($emails as $email)
            <li class="border border-gray-300 overflow-hidden rounded-md mb-4" x-data="{open: false}">
                <div class="border-b border-gray-200 p-4 bg-gray-50">
                    <h2 class="text-lg font-semibold">{{ $email->subject }}</h2>
                    <p class="text-gray-500">From: {{ $email->from }}</p>
                    <p class="text-gray-500">To: {{ $email->to }}</p>
                    <p class="text-gray-500">Reply To: {{ $email->reply_to }}</p>
                </div>
                <div>
                    <div class="bg-gray-100">
                        <button x-on:click="open = !open" class="p-2 w-full flex justify-center items-center">
                            <x-heroicon-s-chevron-down class="h-5 w-5 text-gray-500"/>
                        </button>
                    </div>
                    <div class="bg-white" x-show="open" x-cloak>
                        <iframe
                            src="data:text/html;base64,{{ base64_encode($email->body_html) }}"
                            class="w-full"
                            height="600"
                        ></iframe>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
