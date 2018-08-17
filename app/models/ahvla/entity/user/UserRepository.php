<?php
namespace ahvla\entity\pvsUser;

use ahvla\entity\user\User;
use Cartalyst\Sentry\Sentry;

class UserRepository
{
    /**
     * @var PvsUserRepository
     */
    private $pvsUserRepository;
    /**
     * @var Sentry
     */
    private $sentry;

    public function __construct(PvsUserRepository $pvsUserRepository, Sentry $sentry)
    {
        $this->pvsUserRepository = $pvsUserRepository;
        $this->sentry = $sentry;
    }

    /**
     * @param int $practiceId
     * @return User[]
     */
    public function allPracticeUsers($practiceId)
    {
        $practicePvsUsers = $this->pvsUserRepository->allForPractice($practiceId);

        if (!$practicePvsUsers) {
            return [];
        }

        $users = [];
        foreach ($practicePvsUsers as $pvsUser) {

            try {
                if ($sentryUser = $this->sentry->findUserById($pvsUser->user_id)) {
                    $users[] = new User($sentryUser, $pvsUser);
                }

            } catch (\OutOfBoundsException $e) {
            }
        }
        return $users;
    }

    public function allVictorAdmins()
    {
        $group = $this->sentry->findGroupByName('VICTOR Admin');
        $users = $this->sentry->findAllUsersInGroup($group);

        $admins = [];
        foreach ($users as $user) {
            // Get PvsUser for this Admin
            $pvsUser = $this->pvsUserRepository->getByUserId($user->id, true);

            if ($pvsUser) {
                $admins[] = new User($user, $pvsUser);
            }
        }

        return $admins;
    }
}