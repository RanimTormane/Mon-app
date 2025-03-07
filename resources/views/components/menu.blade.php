<div class="h-screen w-64 bg-[#1E1E2D] text-white fixed shadow-lg">
    <div class="p-5 text-xl font-bold border-b border-gray-700">
        Perfometrics 🚀
    </div>
    <ul class="mt-5 space-y-2">
        <li class="p-3 hover:bg-[#27293D] ">
            <a href="#" class="flex items-center space-x-3">
                <i class="fa fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Liste dynamique des APIs -->
        <li class="p-3 hover:bg-[#27293D] {{ request()->routeIs('APIs.index') ? 'bg-[#27293D]' : '' }}">
            <a href="{{ route('APIs.index') }}" class="flex items-center space-x-3">
                <i class="fa fa-link"></i>
                <span>APIs</span>
            </a>
           
        </li>

        

        <li class="p-3 hover:bg-[#27293D]">
            <a href="#">
                <i class="fa fa-user"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>
</div>
