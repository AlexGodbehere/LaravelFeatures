<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Console\Command;

	class ListFeatures extends Command {

		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'feature:list {--free} {--premium}';
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Lists all features in your application.';

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

			if ($this->option('premium')) {
				$features = Feature::where('premium', '=', true)->get([
					'name',
					'description',
					'premium',
					'enabled',
					'number_of_times_accessed'])->toArray();
			}

			else if ($this->option('free')) {
				$features = Feature::where('premium', '=', false)->get([
					'name',
					'description',
					'premium',
					'enabled',
					'number_of_times_accessed'])->toArray();
			}
			else {
				$features = Feature::all([
					'name',
					'description',
					'premium',
					'enabled',
					'number_of_times_accessed'])->toArray();
			}

			$headers = [
				'Name',
				'Description',
				'Premium',
				'Enabled',
				'Times Accessed'];

			$this->table($headers, $features);
		}
	}