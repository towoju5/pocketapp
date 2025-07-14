<div class="bg-[#222636] min-h-screen w-[25rem] mr-auto" id="content-area">
	<div class="bg-[#222636] min-h-screen w-full">
		<!-- Search Bar -->
		<div class="bg-[#1e2232] w-full p-2 pb-2">
			<div class="flex items-center gap-2 mb-4 w-[100%] bg-transparent">
				<button class="p-2 bg-[#293145] rounded-md">
					<svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
					</svg>
				</button>

				<div class="relative flex-1">
					<input type="search" placeholder="Search..." class="text-left w-full bg-[#1d2130] text-slate-300 rounded-lg border border-slate-400 py-2 px-4">
					<svg class="w-5 h-5 text-slate-400 absolute right-3 top-2.5" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
					</svg>
				</div>

				<button class="p-2 bg-[#293145] rounded-md">
					<svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
					</svg>
				</button>
			</div>

			<!-- Tabs -->
			<div class="flex gap-2 mb-2">
				<button class="flex-1 bg-[#314463] border border-[#0c84d3] py-2 text-white text-sm rounded-lg relative">
					Chats
					<span class="absolute -top-1 -right-1 bg-blue-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">1</span>
				</button>
				<button class="flex-1 bg-[#1d2130] text-slate-300  border border-[#454a56] rounded-lg relative">
					Notifications
					<span class="absolute -top-1 -right-1 bg-blue-500 text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
				</button>
			</div>
		</div>

		<!-- Chat List -->
		<div class="space-y-2 pt-4">
			<!-- Support Chat -->
			<div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-800/50 group cursor-pointer">
				<div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
					<img src="https://pocketoption.com/uploads/users/9d/e3/d9/2_user.png">
				</div>
				<div class="flex-1">
					<div class="flex items-center gap-2">
						<h3 class="text-slate-200 font-medium text-sm">Support Chat (Online)</h3>
						<svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="#ffffff" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
						</svg>
					</div>
					<p class="text-slate-400 text-xs">Welcome to the Support Chat! Here you can g...</p>
				</div>
			</div>
			<hr class="border-t border-slate-700">

			<!-- General Chat -->
			<div class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-800/50 group cursor-pointer">
				<div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center">
					<span class="text-slate-300 text-sm">EN</span>
				</div>
				<div class="flex-1">
					<div class="flex items-center gap-2">
						<h3 class="text-slate-200 font-medium text-sm">General chat (English)</h3>
						<span class="text-xs text-blue-400 bg-blue-500/20 px-2 py-0.5 rounded">39155</span>
					</div>
					<p class="text-slate-400 text-xs">You can find that in the social trading section</p>
				</div>
			</div>
		</div>
	</div>
</div>