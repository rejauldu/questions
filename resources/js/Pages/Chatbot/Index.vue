<script setup>
import { ref, nextTick, watch, computed, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
	threads: {
		type: Array,
		required: true,
	},
	activeChat: {
		type: Object,
		required: true,
		default: () => ({ id: null, title: 'New Chat', messages: [] })
	}
});

const isHistoryOpen = ref(true);
const newMessage = ref('');
const chatContainer = ref(null);
const isLoading = ref(false);
const messages = ref(props.activeChat.messages);
const currentChatId = computed(() => props.activeChat.id);
const windowWidth = ref(window.innerWidth);

watch(() => props.activeChat.messages, (newMessages) => {
	messages.value = newMessages;
}, { deep: true });

/* -------------------------------
   FIXED SIDEBAR WIDTH — UPDATED
   This now works always because
   Tailwind is forced to generate
   the width classes.
--------------------------------*/
const sidebarWidthClass = computed(() => {
    if (window.innerWidth < 768) { // mobile
        return isHistoryOpen.value ? 'fixed left-0 top-0 z-50 w-64 h-full shadow-lg' : 'hidden';
    } else { // desktop
        return isHistoryOpen.value ? 'w-1/4 max-w-[20rem]' : 'w-16';
    }
});

watch(messages, () => {
	nextTick(() => {
		if (chatContainer.value) {
			chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
		}
	});
}, { deep: true });

const toggleHistory = () => {
	isHistoryOpen.value = !isHistoryOpen.value;
};

const historyItems = computed(() => (props.threads || []).map(thread => ({
	...thread,
	active: thread.id === currentChatId.value
})));

const selectHistory = (id) => {
	if (id === currentChatId.value) return;
	router.get(route('chatbot', { id }));
};

const startNewChat = () => {
	if (currentChatId.value !== null) {
		router.get(route('chatbot'), {}, { preserveState: false });
	}
};

const sendMessage = async () => {
	if (!newMessage.value.trim() || isLoading.value) return;

	const userMessage = newMessage.value.trim();
	newMessage.value = '';
	isLoading.value = true;

	const tempId = Date.now();
	messages.value.push({
		id: tempId,
		text: userMessage,
		sender: 'user',
	});

	try {
		const response = await axios.post(route('api.chat.send'), {
			chat_id: currentChatId.value,
			message: userMessage,
		});

		const data = response.data;

		const tempIndex = messages.value.findIndex(msg => msg.id === tempId);
		if (tempIndex !== -1) messages.value.splice(tempIndex, 1);

		if (data.new_thread_data) {
			router.replace(route('chatbot', { id: data.new_thread_data.id }));
		}

		if (data.new_messages) {
			messages.value.push(...data.new_messages);
		}

	} catch (error) {
		console.error("Failed to send message:", error);

		const tempIndex = messages.value.findIndex(msg => msg.id === tempId);
		if (tempIndex !== -1) messages.value.splice(tempIndex, 1);

		messages.value.push({
			id: Date.now(),
			text: "Error: Could not reach the server.",
			sender: 'bot',
		});
	} finally {
		isLoading.value = false;
	}
};
const linkify = (text) => {
    const urlRegex = /(https?:\/\/[^\s]+)/g;

    return text.replace(urlRegex, (url) => {

        let cleanUrl = url;

        // Extract only the path without domain
        let path = '';
        try {
            const u = new URL(url);
            path = u.pathname; // "/folder/file name"
        } catch {
            path = url; // fallback
        }

        // Remove leading slash
        path = path.replace(/^\/+/, '');

        // Convert everything (except domain) to hyphen-separated
        let hyphenated = path
            .trim()
            .replace(/[\/\\]+/g, '-')  // replace slashes with hyphens
            .replace(/\s+/g, '-')      // replace spaces with hyphens
            .replace(/_+/g, '-')        // underscores -> hyphens
            .replace(/[^A-Za-z0-9.-]+/g, '-') // remove special chars
            .replace(/-+/g, '-')        // collapse multiple hyphens
            .toLowerCase();

        // Fallback if empty
        if (!hyphenated) hyphenated = url;

        return `<a href="${url}" target="_blank" class="text-blue-600 underline">${hyphenated}</a>`;
    });
};
const maxChars = 100;

const charCount = computed(() => {
    return newMessage.value.length;
});

onMounted(() => {
	if (chatContainer.value) {
		chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
	}
});

onMounted(() => {
    // Open by default on desktop, closed on mobile
    if (window.innerWidth < 768) { // md breakpoint
        isHistoryOpen.value = false;
    } else {
        isHistoryOpen.value = true;
    }
});

</script>

<template>
	<Head :title="activeChat.title" />

	<!-- 👇 Invisible div to force Tailwind to generate dynamic classes -->
	<div class="hidden w-full md:w-1/4 md:max-w-[20rem] w-16"></div>

    <div v-if="isHistoryOpen && windowWidth < 768" class="fixed inset-0 bg-black bg-opacity-30 z-40" @click="toggleHistory"></div>

	<div class="min-h-screen bg-gray-50 flex overflow-hidden font-sans">
		<div
			:class="[
				sidebarWidthClass,
				isHistoryOpen ? 'block' : 'hidden md:block',
				'bg-white border-r border-gray-200 transition-all duration-300 ease-in-out flex flex-col h-screen overflow-hidden'
			]"
		>
			<div class="p-3 border-b bg-gray-50 flex justify-between items-center">
				<h2 v-if="isHistoryOpen" class="text-lg font-bold text-gray-800">
					<button @click="startNewChat"
						class="px-3 py-1 bg-green-500 text-white text-sm rounded-lg hover:bg-green-600 transition disabled:bg-gray-400"
						:disabled="currentChatId === null">
						+ New Chat
					</button>
				</h2>

				<button @click="toggleHistory" class="p-2 rounded-full text-blue-600 hover:bg-blue-100 transition">
					<svg v-if="isHistoryOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
						fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M15 19l-7-7 7-7" />
					</svg>
					<svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
						fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
							d="M9 5l7 7-7 7" />
					</svg>
				</button>
			</div>

			<div v-if="isHistoryOpen" class="flex-1 overflow-y-auto">
				<div
					v-for="item in historyItems"
					:key="item.id"
					@click="selectHistory(item.id)"
					:class="[
						'p-4 cursor-pointer border-b hover:bg-gray-100 transition truncate',
						item.active ? 'bg-blue-50 border-l-4 border-blue-600 font-semibold' : 'text-gray-600'
					]"
				>
					{{ item.title }}
				</div>
			</div>

			<div v-if="!isHistoryOpen" class="flex-1 flex items-center justify-center p-2">
				<span class="text-xs font-medium text-gray-500 transform rotate-90 whitespace-nowrap">
					HISTORY
				</span>
			</div>
		</div>

		<div class="flex-1 flex flex-col h-screen">
			<div class="w-full bg-white flex flex-col h-full">
				<div class="p-4 border-b border-gray-200 bg-blue-600 text-white flex items-center justify-between">
					<div class="flex items-center space-x-3">
        <!-- Home Link/Button -->
        <!-- Return to Home Link -->
        <a :href="route('home')"
           class="bg-white text-blue-600 px-3 py-1 rounded hover:bg-gray-100 transition font-medium text-sm">
           Return to Home
        </a>

        <!-- Chat Title -->
        <h1 class="text-xl font-bold">{{ activeChat.title }}</h1>
    </div>
					<button @click="toggleHistory"
						class="p-2 rounded-full bg-blue-700 hover:bg-blue-800 transition shadow-md">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
							fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path v-if="isHistoryOpen" stroke-linecap="round" stroke-linejoin="round"
								stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
							<path v-else stroke-linecap="round" stroke-linejoin="round"
								stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>
				</div>

				<div ref="chatContainer" class="flex-1 p-6 overflow-y-auto space-y-4 bg-gray-50">
					<div v-for="message in messages" :key="message.id"
						:class="['flex', message.sender === 'user' ? 'justify-end' : 'justify-start']">
						<div
							:class="[
								'p-4 rounded-xl max-w-sm lg:max-w-md shadow-md',
								message.sender === 'user'
									? 'bg-blue-600 text-white rounded-br-none'
									: 'bg-white text-gray-800 rounded-tl-none border border-gray-100'
							]"
							v-html="linkify(
                                    message.text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                                )"
						></div>
					</div>

					<div v-if="isLoading" class="flex justify-start">
						<div class="bg-white text-gray-800 p-4 rounded-xl rounded-tl-none border border-gray-100 shadow-md">
							<div class="flex space-x-1">
								<span class="w-2 h-2 bg-gray-500 rounded-full animate-pulse"></span>
								<span class="w-2 h-2 bg-gray-500 rounded-full animate-pulse delay-150"></span>
								<span class="w-2 h-2 bg-gray-500 rounded-full animate-pulse delay-300"></span>
							</div>
						</div>
					</div>
				</div>

				<div class="p-4 border-t border-gray-200 bg-white flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-3">
                    <!-- Input + Counter Wrapper -->
                    <div class="flex-1 flex flex-col relative">
                        <input
                        type="text"
                        v-model="newMessage"
                        @keyup.enter="sendMessage"
                        :disabled="isLoading"
                        :maxlength="maxChars"
                        placeholder="Ask about an exam date..."
                        class="w-full p-3 pr-14 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100 transition"
                        />
                        <!-- Character Counter -->
                        <span class="absolute bottom-1 right-3 text-xs text-gray-400 select-none">
                        {{ charCount }}/{{ maxChars }}
                        </span>
                    </div>

                    <!-- Send Button -->
                    <button
                        @click="sendMessage"
                        :disabled="isLoading || !newMessage.trim()"
                        class="flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow hover:bg-blue-700 disabled:bg-gray-400 transition"
                    >
                        <span v-if="!isLoading">Send</span>
                        <svg
                        v-else
                        class="animate-spin h-5 w-5 text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        >
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2
                            5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824
                            3 7.938l3-2.647z"
                        />
                        </svg>
                    </button>
                </div>
			</div>
		</div>
	</div>
</template>
