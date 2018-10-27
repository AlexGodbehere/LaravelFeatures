<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class EnableFeature extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:enable {feature}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Enables a feature in your application.';

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

			if ($feature->enabled == true) {
				$this->error('The feature is already enabled.');
				return;
			}

			$feature = Feature::where('name', '=', $name)->first();
			$feature->enabled = true;
			$feature->save();

			$this->info($feature->name . ' has been enabled.');

		}
	}