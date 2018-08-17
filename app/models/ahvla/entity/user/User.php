<?php

namespace ahvla\entity\user;

use ahvla\entity\Practice;
use ahvla\entity\pvsPractice\PvsPractice;
use ahvla\entity\pvsUser\PvsUser;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;
use Cartalyst\Sentry\Sentry;

class User
{
    /**
     * @var SentryUser
     */
    private $sentryUser;

    /**
     * @var PvsUser
     */
    private $pvsUser;

    /**
     * @var PvsPractice
     */
    private $pvsPractice;

    /**
     * @var Sentry
     */
    private $sentry;

    /**
     * @param SentryUser $sentryUser
     * @param PvsUser $pvsUser
     */
    function __construct(SentryUser $sentryUser, PvsUser $pvsUser)
    {
        $this->sentryUser = $sentryUser;
        $this->pvsUser = $pvsUser;
        $this->pvsPractice = $pvsUser->getPractice();
        $this->sentry = \App::make(Sentry::class);;
    }

    public function getSentryUser()
    {
        return $this->sentryUser;
    }

    public function getPvsUser()
    {
        return $this->pvsUser;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->sentryUser->getId();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->sentryUser->getLogin();
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->sentryUser->getPassword();
    }

    /**
     * @return PvsPractice
     */
    public function getPractice()
    {
        return $this->pvsPractice;
    }

    /**
     * @return string
     */
    public function getPracticeLimsCode()
    {
        return $this->pvsPractice->lims_code;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->sentryUser->first_name . ' ' . $this->sentryUser->last_name;
    }

    /**
     * @return string
     */
    public function getLastLogin()
    {
        if (is_null($this->sentryUser->last_login)) {
            return 'Not logged in yet';
        }

        return $this->sentryUser->last_login->format('j-m-Y \@ g:ia');
    }

    /**
     * @return boolean
     */
    public function canCreateSubmissions()
    {
        return $this->sentryUser->hasPermission('createSubmissions');
    }

    /**
     * @return boolean
     */
    public function canManageUserAccounts()
    {
        return $this->sentryUser->hasPermission('manageUserAccounts');
    }

    public function canManagePractice($practiceId)
    {
        if ($this->canManagePracticeAccounts()) {
            return true;
        }

        return $this->canManageUserAccounts() && ($this->getPractice()->id == $practiceId);
    }

    /**
     * Checks if this user has permission to manage another user
     *
     * @param User $user The other user to test against
     * @return bool
     */
    public function canUpdateUser(User $user)
    {
        if ($this->canManagePracticeAccounts()) {
            return true;
        }
        $practice = $this->getPractice();
        $otherPractice = $user->getPractice();
        if ($practice && $otherPractice && $practice->getId() == $otherPractice->getId()) {
            return true;
        }

        return false;
    }

    // Can manage practice accounts
    public function canManagePracticeAccounts()
    {
        return $this->sentryUser->hasPermission('managePracticeAccounts');
    }

    // Can manage Victor administrator accounts
    public function canManageVictorAccounts()
    {
        return $this->sentryUser->hasPermission('manageVictorAccounts');
    }

    // Can manage lookup tables
    public function canManageLookupTables()
    {
        return $this->sentryUser->hasPermission('manageLookupTables');
    }

    // Can manage information messages displayed within the app
    public function canManageInformationMessages()
    {
        return $this->sentryUser->hasPermission('manageInformationMessages');
    }

    // Can assume identities of other users
    public function canAssumeIdentities()
    {
        return $this->sentryUser->hasPermission('assumeIdentities');
    }

    // Alias for: Can assume identities of other users
    public function canImpersonateUsers()
    {
        return $this->canAssumeIdentities();
    }

    // Can manage view system/api logs
    public function canViewLogs()
    {
        return $this->sentryUser->hasPermission('viewLogs');
    }

    // Can manage the VICTOR settings
    public function canManageVictorSettings()
    {
        return $this->sentryUser->hasPermission('manageVictorSettings');
    }

    /**
     * @return bool
     */
    public function isActivated()
    {
        return $this->sentryUser->isActivated();
    }

    public function getStatus()
    {
        $throttle = $this->sentry->findThrottlerByUserId($this->getId());

        if ($throttle->isBanned()) {
            return 'Locked: ';
        } elseif ($throttle->isSuspended()) {
            return 'Suspended: ';
        } elseif (! $this->isActivated()) {
            return 'Inactive';
        } else {
            return 'Active';
        }
    }

    public function getReason()
    {
        $throttle = $this->sentry->findThrottlerByUserId($this->getId());

        if ($throttle->isBanned()) {
            return $this->getBannedReason();
        } elseif ($throttle->isSuspended()) {
            return $this->getSuspendedReason();
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getBannedReason()
    {
        return $this->sentryUser->getBannedReason();
    }

    /**
     * @return string
     */
    public function getSuspendedReason()
    {
        return $this->sentryUser->getSuspendedReason();
    }

    /**
     * Update the reason for banning the user
     *
     * @param $reason
     * @return bool
     */
    public function setBannedReason($reason)
    {
        $this->sentryUser->setBannedReason($reason);

        return $this->sentryUser->save();
    }

    /**
     * Update the reason for suspending the user
     *
     * @param $reason
     * @return bool
     */
    public function setSuspendedReason($reason)
    {
        $this->sentryUser->setSuspendedReason($reason);

        return $this->sentryUser->save();
    }

    /**
     * @return array Group[]
     */
    public function getGroups()
    {
        return $this->sentryUser->getGroups();
    }

    /**
     * @return string
     */
    public function getGroupsAsString()
    {
        $groupsArr = [];
        $groups = $this->sentryUser->getGroups();
        foreach ($groups as $group) {
            $groupsArr[] = $group->getName();
        }

        return implode(',', $groupsArr);
    }

    /**
     * Check if user belongs to a specific group
     *
     * @param $groupName
     * @return bool
     */
    public function inGroupByName($groupName)
    {
        $groups = $this->sentryUser->getGroups();
        foreach ($groups as $group) {
            if ($group->name == $groupName) {
                return true;
            }
        }

        return false;
    }

    /**
     * Shortcut method to check whether user belongs to Admin group
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->inGroupByName('Admin');
    }
}