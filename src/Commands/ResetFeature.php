<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class ResetFeature extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:reset {feature}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Resets a feature from your application';

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

			$name = $this->argument('feature');
			$feature = Feature::where('name', '=', $name)->first();

			// If the feature doesn't exist, return false
			if ($feature === null) {
				$this->error('The feature does not exist.');
				return false;
			}

			$confirmation = $this->ask('Are you sure you wish to reset the usage counter for this feature? Enter the feature name to confirm');

			if ($confirmation !== $feature->name)
			{
				$this->error('The feature usage counter has not been reset.');
				return;
			}

			$feature->number_of_times_accessed = 0;
			$feature->save();

			$this->info('The usage counter for ' . $name . ' has been reset.');
		}
	}