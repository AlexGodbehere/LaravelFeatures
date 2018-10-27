<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class FreeFeature extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:free {feature}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Sets a feature in your application as free.';

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

			if ($feature->premium == false) {
				$this->error('The feature is already free.');
				return;
			}

			$feature = Feature::where('name', '=', $name)->first();
			$feature->premium = false;
			$feature->save();

			$this->info($feature->name . ' has been set as a free feature.');

		}
	}