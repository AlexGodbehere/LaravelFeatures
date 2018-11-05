<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class GetFeatureDescription extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:description {feature}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Gets the description of the current feature';

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
			$this->info(Feature::where('name', '=', $this->argument('feature'))->first()->description);
		}
	}