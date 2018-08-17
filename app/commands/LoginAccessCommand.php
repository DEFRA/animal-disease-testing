<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use ahvla\entity\victorSettings\VictorSettingsRepository;
use ahvla\entity\victorSettings\VictorSetting;

class LoginAccessCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'command:login-access';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Enables or disables the login form.';

    /** @var VictorSettingsRepository */
    protected $settingsRepo;

    /**
     * Constructor
     * @param VictorSettingsRepository $settingsRepo
     */
    function __construct(VictorSettingsRepository $settingsRepo) {
        parent::__construct();
        $this->settingsRepo = $settingsRepo;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // get the current status
        // 0 is enabled, 1 is disabled
        $currentStatusId = $this->settingsRepo->getLoginFormStatus();

        if ('1' === $currentStatusId) {
            $currentStatus = 'disable';
            $statusToBecome = 'enable';
        } else {
            $currentStatus = 'enable';
            $statusToBecome = 'disable';
        }

        if ( $currentStatus === $this->option('status') ) {
            if ($this->option('disable-log') !== '1') {
                $this->info('The login form is already ' . $this->option('status') . 'd.');
            }
        } else {

            // deal with requested change
            if ($this->option('disable-confirm') === '1' || $this->confirm('Do you wish to ' . $statusToBecome . ' the login form? [yes|no]')) {

                if ('enable' === trim(strtolower($this->option('status')))) {

                    $this->settingsRepo->enableLogin();

                    if ($this->option('disable-log') !== '1') {
                        $this->info('The login form is now enabled.');
                    }

                } else if ('disable' === trim(strtolower($this->option('status')))) {

                    $this->settingsRepo->disableLogin();

                    if ($this->option('disable-log') !== '1') {
                        $this->info('The login form is now disabled.');
                    }

                } else {

                    if ($this->option('disable-log')) {
                        $this->info('Invalid Status. Use \'enabled\' or \'disabled\'');
                    }

                }
            }
        }
	}

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['status', null, InputOption::VALUE_REQUIRED, 'The status of the login form.', 'enabled'],
            ['disable-confirm', null, InputOption::VALUE_OPTIONAL, 'Disable the Are You Sure prompt.', '0'],
            ['disable-log', null, InputOption::VALUE_OPTIONAL, 'Output confirmation.', '0'],
        ];
    }

}