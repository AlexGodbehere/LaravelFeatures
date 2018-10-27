<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class ResetAllFeatures extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:resetall';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Resets all feature usage counters in your application';

		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct () {

			parent::__construct();
		}

		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle () {

			$confirmation = $this->ask('Are you sure you wish to reset the usage counter for all feature? Enter \'resetall\' to confirm');
			if ($confirmation !== 'resetall')
			{
				$this->error('The feature usage counters have not been reset.');
				return;
			}

			\DB::table('features')->update(array('number_of_times_accessed' => 0));

			$this->info('The usage counter for all features have been reset.');
		}
	}