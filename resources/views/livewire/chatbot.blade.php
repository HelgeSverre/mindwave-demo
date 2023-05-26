<div class="h-full -my-10 flex flex-col overflow-auto relative">
    <div>
        <h2 class="text-xl py-4 border-b-2 border-gray-200">
            You are now talking to <b>Mindwave</b> 🧠️
        </h2>
    </div>
    <div
        x-data="{
            init() { this.scroll(); $wire.on('message', () => { this.scroll() }) },
            scroll: () => { $el.scrollTo(0, $el.scrollHeight); },
        }"
        class="flex-1 flex flex-col overflow-y-scroll"
    >
        <div class="flex-1 h-full py-4">


            @foreach($messages as $message)

                @if($message["role"] == "system" && $this->debug)
                    <div class="flex justify-end">
                        <div class="mb-4 flex">
                            <div class="flex-1 px-2">
                                <div class="inline-block max-w-xl rounded-3xl p-4 bg-bob-yellow-light text-gray-900/80">
                                    <div class="font-bold text-xs mb-1">Debug</div>
                                    <span>{!! nl2br($message["content"]) !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                @elseif($message["role"] == "user")
                    <div class="mb-4 flex">
                        <div>
                            <div class="w-12 h-12 relative">
                                <img
                                    class="w-12 h-12 rounded-full mx-auto"
                                    src="{{ Gravatar::get("helge.sverre@gmail.com") }}"
                                    alt=""
                                />
                            </div>
                        </div>
                        <div class="flex-1 px-2">
                            <div class="inline-block max-w-4xl rounded-3xl p-4 bg-gray-200 text-gray-700">
                                <span>{!! nl2br($message["content"]) !!}</span>
                            </div>
                        </div>
                    </div>
                @elseif($message["role"] == "assistant")
                    <div class="flex justify-end">
                        <div class="mb-4 flex">
                            <div class="flex-1 px-2">
                                <div class="inline-block max-w-4xl rounded-3xl p-4 bg-purple-600 text-white">
                                    <span>{!! nl2br($message["content"]) !!}</span>
                                </div>
                            </div>
                            <div>
                                <div class="w-12 h-12 relative">
                                    <img

                                        class="w-12 h-12 rounded-full mx-auto"
                                        src="{{ asset("icon.png") }}"
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


    <div class="h-14 p-1 my-2 relative flex items-center justify-center w-full">
        <div class="h-12 bg-white border border-gray-200 shadow flex rounded-lg w-full bg-red-600 overflow-hidden">
            <div
                wire:loading.delay.class.remove="hidden"
                class="hidden m-1 absolute inset-0 bg-gray-400 z-50 flex items-center justify-center opacity-60 rounded-lg"
            >
                <x-heroicon-m-cog class="animate-spin h-6 w-6 text-gray-600"/>
            </div>
            <div class="flex-1">
                <!--suppress HtmlFormInputWithoutLabel -->
                <input
                    type="text"
                    wire:model.lazy="draft"
                    wire:keyup.enter="sendMessage"
                    wire:loading.attr="readonly"
                    class="w-full h-full block border-transparent outline-transparent focus:ring-0 outline-none focus:outline-transparent focus:border-transparent py-4 px-4 resize-none"
                    autofocus
                >
            </div>
            <div class="p-2 flex content-center items-center">
                <button
                    wire:click="sendMessage"
                    wire:loading.attr="disabled"
                    class="bg-blue-400 w-9 h-9 rounded-full flex justify-center items-center"
                >
                    <x-heroicon-m-paper-airplane class="w-5 h-5 text-white -rotate-45"/>
                </button>
            </div>
        </div>
    </div>
</div>
