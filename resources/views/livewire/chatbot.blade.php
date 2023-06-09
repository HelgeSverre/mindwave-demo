<div class="relative -my-10 flex h-full flex-col overflow-auto">
    <div>
        <h2 class="border-b-2 border-gray-200 py-4 text-xl">
            You are now talking to
            <b>Mindwave</b>
            🧠️
        </h2>
    </div>
    <div
        x-data="{
            init() { this.scroll(); $wire.on('message', () => { this.scroll() }) },
            scroll: () => { $el.scrollTo(0, $el.scrollHeight); },
        }"
        class="flex flex-1 flex-col overflow-y-scroll"
    >
        <div class="h-full flex-1 py-4">
            @foreach ($messages as $message)
                @if ($message['role'] == 'system' && $this->debug)
                    <div class="flex justify-end">
                        <div class="mb-4 flex">
                            <div class="flex-1 px-2">
                                <div
                                    class="inline-block max-w-xl rounded-3xl bg-yellow-300 p-4 text-gray-900/80"
                                >
                                    <div class="mb-1 text-xs font-bold">
                                        Debug
                                    </div>
                                    <span>
                                        {!! nl2br($message['content']) !!}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif ($message['role'] == 'user')
                    <div class="mb-4 flex">
                        <div>
                            <div class="relative h-12 w-12">
                                <img
                                    class="mx-auto h-12 w-12 rounded-full"
                                    src="{{ Gravatar::get('helge.sverre@gmail.com') }}"
                                    alt=""
                                />
                            </div>
                        </div>
                        <div class="flex-1 px-2">
                            <div
                                class="inline-block max-w-4xl rounded-3xl bg-gray-200 p-4 text-gray-700"
                            >
                                <span>{!! nl2br($message['content']) !!}</span>
                            </div>
                        </div>
                    </div>
                @elseif ($message['role'] == 'assistant')
                    <div class="flex justify-end">
                        <div class="mb-4 flex">
                            <div class="flex-1 px-2">
                                <div
                                    class="inline-block max-w-4xl rounded-3xl bg-purple-600 p-4 text-white"
                                >
                                    <span>
                                        {!! nl2br($message['content']) !!}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="relative h-12 w-12">
                                    <img
                                        class="mx-auto h-12 w-12 rounded-full"
                                        src="{{ asset('icon.png') }}"
                                        alt="Mindwave"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <div class="py-2"></div>
        </div>
    </div>

    <div class="relative my-2 flex h-14 w-full items-center justify-center p-1">
        <div
            class="flex h-12 w-full overflow-hidden rounded-lg border border-gray-200 bg-red-600 bg-white shadow"
        >
            <div
                wire:loading.delay.class.remove="hidden"
                class="absolute inset-0 z-50 m-1 flex hidden items-center justify-center rounded-lg bg-gray-400 opacity-60"
            >
                <x-heroicon-m-cog class="h-6 w-6 animate-spin text-gray-600" />
            </div>
            <div class="flex-1">
                <!--suppress HtmlFormInputWithoutLabel -->
                <input
                    type="text"
                    wire:model.lazy="draft"
                    wire:keyup.enter="sendMessage"
                    wire:loading.attr="readonly"
                    class="block h-full w-full resize-none border-transparent px-4 py-4 outline-none outline-transparent focus:border-transparent focus:outline-transparent focus:ring-0"
                    autofocus
                />
            </div>
            <div class="flex content-center items-center p-2">
                <button
                    wire:click="sendMessage"
                    wire:loading.attr="disabled"
                    class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-400"
                >
                    <x-heroicon-m-paper-airplane
                        class="h-5 w-5 -rotate-45 text-white"
                    />
                </button>
            </div>
        </div>
    </div>
</div>
