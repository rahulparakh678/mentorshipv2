<style>
    #sidebar {
        position: fixed;
        width: 270px;
        height: 100%;
        background: var(--black1);
        transition: 0.5s;
        overflow: hidden;
    }
    .c-sidebar.active {
        width: 80px;
    }
    .c-sidebar ul {
        width: 100%;
    }
    .c-sidebar ul li {
        position: relative;
        width: 100%;
        list-style: none;
    }
    .c-sidebar ul li a {
        position: relative;
        display: flex;
        text-decoration: none;
        color: var(--white);
        transition: background-color 0.3s, color 0.3s;
    }
    .c-sidebar ul li a:hover {
        color: var(--black1);
        background: white;
    }
    .c-sidebar ul li a .icon {
        display: block;
        min-width: 80px;
        height: 60px;
        line-height: 60px;
        text-align: center;
    }
    .c-sidebar ul li a .title {
        display: block;
        padding: 0 5px;
        height: 60px;
        line-height: 60px;
        text-align: start;
        white-space: nowrap;
    }
</style>

<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            Mentor Dashboard
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('mentor.dashboard') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        {{--<li class="c-sidebar-nav-dropdown {{ request()->is('admin/sessions*') ? 'c-show' : '' }} {{ request()->is('admin/one-one-attendances*') ? 'c-show' : '' }} {{ request()->is('admin/assign-tasks*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                {{ trans('cruds.oneOneSession.title') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('sessions.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/one-one-attendances') || request()->is('admin/one-one-attendances/*') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-paperclip c-sidebar-nav-icon"></i>
                        Session Details
                    </a>
                </li>
                @can('assign_task_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.assign-tasks.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/assign-tasks') || request()->is('admin/assign-tasks/*') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-tasks c-sidebar-nav-icon"></i>
                        {{ trans('cruds.assignTask.title') }}
                    </a>
                </li>
                @endcan
            </ul>--}}
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('mentor.modules')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-sitemap"></i>
                Modules
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('quizdetails')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-question-circle-o"></i>
                Total Quizes
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('tasks.index')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-tasks"></i>
                Task Completed
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('sessions.index')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-users"></i>
                Sessions
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('resources.index')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-link"></i>
                Resources
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{route('opportunity.index')}}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-suitcase"></i>
                Opportunities
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fa fa-calendar"></i>
                Calender
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            {{--<a href="{{ route('generate.certificate') }}" class="c-sidebar-nav-link">--}}
            <a href="#" class="c-sidebar-nav-link">
                <i class="fa-solid fa-certificate c-sidebar-nav-icon" aria-hidden="true"></i>
                Generate Certificate
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            {{--<a href="{{ route('support.index') }}" class="c-sidebar-nav-link">--}}
            <a href="#" class="c-sidebar-nav-link">  
                <i class="fa fa-ticket c-sidebar-nav-icon" ></i>
                Support
            </a>
        </li>
        <!-- More list items here -->

        
        
            @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
               
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                            <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                            </i>
                            {{ trans('global.change_password') }}
                        </a>
                    </li>
                
            @endif
            <li class="c-sidebar-nav-item">
                <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                    </i>
                    {{ trans('global.logout') }}
                </a>
            </li>
    </ul>

</div>