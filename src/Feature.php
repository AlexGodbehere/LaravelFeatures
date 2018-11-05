<?php

	namespace AlexGodbehere\LaravelFeatures;

	use Illuminate\Database\Eloquent\Model;

	class Feature extends Model {

		protected $table = 'features';

		/**
		 *    Returns whether or not the currently signed in user can access the given feature
		 */
		public static function can ($featureName, $isPremium = false) {

			// Get the feature
			$feature = Feature::where('name', '=', $featureName)->first();

			// If the feature doesn't exist, return false
			if ($feature === null) {
				throw new \Exception('The feature does not exist');

				return false;
			}

			\DB::table('features')->whereId($feature->id)->increment('number_of_times_accessed');

			// Only allow override in development environments. This is to allow for testing with no auth scaffolding.
			if (!env('APP_ENV') == 'production') {
				// Return true if the user is premium or the feature is free.
				return $isPremium || !$feature->premium;
			}

			// Return true if the user is premium or the feature is free.
			return auth()->user()->isPremium || !$feature->premium;
		}

		public static function cannot ($featureName, $isPremium = false) {

			return !self::can($featureName, $isPremium);
		}

		public static function description ($featureName) {

			return Feature::where('name', '=', $featureName)->first()->description;
		}
	}