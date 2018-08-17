<div class="column-half">
    <div class="account-management">
        <p class="account-management__user"><span>@if (Session::has('ImpersonatingUser'))Logged in as @endif {{{$loggedUser->getFullname()}}}</span>{{$loggedUser->getPractice()->getName()}}</p>
    </div>
</div>
<div class="column-half">
    <div id="account-management" class="account-management">
        <div class="account-management__menu">
            <a href="#account-management" class="account-management__button"><span>Account navigation</span></a>
            <ul class="account-management__list">
                @if($loggedUser->canManageVictorAccounts())
                    <li class="account-management__item">{{link_to_route('dashboard', 'Dashboard')}}</li>
                @endif        
                <li class="account-management__item">{{link_to_route('my-profile-form', 'My profile')}}</li>
                @if($loggedUser->canManageUserAccounts() && ! $loggedUser->canManagePracticeAccounts())
                    <li class="account-management__item">{{link_to_route('users.view', 'Manage users', ['practiceId' => $loggedUser->getPractice()->id], array('id'=>'manage-users'))}}</li>
                @endif
                @if($loggedUser->canManagePracticeAccounts())
                    <li class="account-management__item">{{link_to_route('practices.view', 'Manage practices', null, array('id'=>'manage-practices-nav'))}}</li>
                @endif
                @if($loggedUser->canManageVictorAccounts())
                    <li class="account-management__item">{{link_to_route('users.view', 'Manage victor administrators', ['practiceId' => $loggedUser->getPractice()->id], array('id'=>'manage-admins'))}}</li>
                @endif
                @if($loggedUser->canManageVictorAccounts())
                    <li class="account-management__item">{{link_to_route('settings.edit', 'Manage victor settings', null, array('id'=>'settings'))}}</li>
                @endif
                @if($loggedUser->canManageLookupTables())
                    <li class="account-management__item">{{link_to_route('crud.crud', 'Manage lookups tables')}}</li>
                @endif
                @if($loggedUser->canManageInformationMessages())
                    <li class="account-management__item">{{link_to_route('information-messages.edit', 'Manage information messages', null, array('id'=>'manage-information-message'))}}</li>
                @endif
                @if($loggedUser->canManageLookupTables())
                    <li class="account-management__item">{{link_to_route('lookups.test-advice', 'Upload test advice', null, array('id'=>'test-advice-upload'))}}</li>
                @endif            
                @if($loggedUser->canViewLogs())
                    <li class="account-management__item">{{link_to_route('logs.view', 'Logs', null, array('id'=>'logs'))}}</li>
                @endif
                @if (Session::has('ImpersonatingUser'))
                    <li class="account-management__item">{{link_to_route('unimpersonate', 'Revert to admin', null)}}</li>
                @endif
                <li class="account-management__item">{{link_to_route('logout', 'Logout', null, array('id'=>'logout'))}}</li>
            </ul>
        </div>
    </div>
</div>