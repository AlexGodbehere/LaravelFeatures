<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;
	use function Spatie\SslCertificate\length;

	class AddFeature extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:add {feature*} {--P|premium} {--D|dev}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Adds a feature to your application.';

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

			$arguments = $this->argument('feature');

			is_array($arguments) ? $name = $arguments[0] : $name = $arguments;

			if (Feature::where('name', '=', $name)->exists()) {
				$this->error('The feature already exists.');

				return;
			}

			// If no description was supplied as an argument
			if (is_array($arguments)) {
				if (sizeof($arguments) > 1) {
					$description = $arguments[1];
				}
				else {
					$description = $this->ask('Enter a short description of the feature');
				}
			}
			else {
				$description = $this->ask('Enter a short description of the feature');
			}

			// Add feature to database
			$feature = new Feature();
			$feature->name = $name;
			$feature->description = $description;
			$feature->premium = $this->option('premium');
			$feature->enabled = !$this->option('dev');

			$feature->save();

			if ($this->option('premium')) {
				$this->info('Premium feature added: ' . $feature->name);
			}
			else {
				$this->info('Free feature added: ' . $feature->name);
			}
		}
	}