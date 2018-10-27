<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class ShowFeatureUsage extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:usage {feature}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Displays how many times a feature has been used in your application.';

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

			$this->info($feature->name . ' has been used ' . $feature->number_of_times_accessed . ' times.');
		}
	}