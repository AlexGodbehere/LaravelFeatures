<?php

	namespace AlexGodbehere\LaravelFeatures\Tests;

	use AlexGodbehere\LaravelFeatures\Feature;
	use AlexGodbehere\LaravelFeatures\LaravelFeaturesServiceProvider;
	use Illuminate\Support\Facades\Artisan;

	class FeatureTest extends \Orchestra\Testbench\TestCase {

		/**
		 * Setup the test environment.
		 */
		protected function setUp ()
		: void {

			parent::setUp();

			$this->artisan('migrate', ['--database' => 'testbench'])->run();
		}

		/**
		 * Define environment setup.
		 *
		 * @param  \Illuminate\Foundation\Application $app
		 *
		 * @return void
		 */
		protected function getEnvironmentSetUp ($app) {

			// Setup default database to use sqlite :memory:
			$app['config']->set('database.default', 'testbench');
			$app['config']->set('database.connections.testbench', [
				'driver'   => 'sqlite',
				'database' => ':memory:',
				'prefix'   => '',]);
		}

		/**
		 * Get package providers.  At a minimum this is the package being tested, but also
		 * would include packages upon which our package depends, e.g. Cartalyst/Sentry
		 * In a normal app environment these would be added to the 'providers' array in
		 * the config/app.php file.
		 *
		 * @param  \Illuminate\Foundation\Application $app
		 *
		 * @return array
		 */
		protected function getPackageProviders ($app) {

			return [
				LaravelFeaturesServiceProvider::class,];
		}

		protected function signIn ($user = null) {

			$user = $user ?: create('App\Models\Auth\User\User');

			$this->actingAs($user, 'api');

			return $this;
		}

		/*
		>|-----------------------------------------
		>| Tests
		>|-----------------------------------------
		*/

		/** @test */
		public function it_can_create_a_premium_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(!!Feature::first()->premium);
		}

		/** @test */
		public function it_can_create_a_free_feature () {

			$this->artisan('feature:add', ['feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertFalse(!!Feature::first()->premium);
		}

		/** @test */
		public function it_can_not_create_a_feature_that_already_exists () {

			$this->artisan('feature:add', ['feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertFalse(!!Feature::first()->premium);
			$this->artisan('feature:add', ['feature' => 'NewFeature'])->expectsOutput('The feature already exists.');
		}

		/** @test */
		public function it_can_change_a_free_feature_to_a_premium_feature () {

			$this->artisan('feature:add', ['feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertFalse(!!Feature::first()->premium);
			$this->artisan('feature:premium', ['feature' => 'NewFeature']);
			$this->assertTrue(!!Feature::first()->premium);
		}

		/** @test */
		public function it_can_change_a_premium_feature_to_a_free_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(!!Feature::first()->premium);
			$this->artisan('feature:free', ['feature' => 'NewFeature']);
			$this->assertFalse(!!Feature::first()->premium);
		}

		/** @test */
		public function it_cannot_change_a_premium_feature_to_a_premium_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(!!Feature::first()->premium);
			$this->artisan('feature:premium', ['feature' => 'NewFeature'])->expectsOutput('The feature is already premium.');
			$this->assertTrue(!!Feature::first()->premium);
		}

		/** @test */
		public function it_cannot_change_a_free_feature_to_a_free_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertFalse(!!Feature::first()->premium);
			$this->artisan('feature:free', ['feature' => 'NewFeature'])->expectsOutput('The feature is already free.');
			$this->assertFalse(!!Feature::first()->premium);
		}

		/** @test */
		public function it_can_delete_a_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->artisan('feature:remove', ['feature' => 'NewFeature']);
			$this->assertCount(0, Feature::all());
		}

		/** @test */
		public function it_can_create_a_disabled_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature',
				'--dev'   => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->assertEquals(0, Feature::first()->enabled);
		}

		/** @test */
		public function it_can_enable_a_disabled_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature',
				'--dev'   => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->assertEquals(0, Feature::first()->enabled);
			$this->artisan('feature:enable', [
				'feature' => 'NewFeature']);
			$this->assertEquals(1, Feature::first()->enabled);
		}

		/** @test */
		public function it_can_disable_an_enabled_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->assertEquals(1, Feature::first()->enabled);
			$this->artisan('feature:disable', [
				'feature' => 'NewFeature']);
			$this->assertEquals(0, Feature::first()->enabled);
		}

		/** @test */
		public function it_cannot_enable_an_enabled_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->assertEquals(1, Feature::first()->enabled);
			$this->artisan('feature:enable', [
				'feature' => 'NewFeature'])->expectsOutput('The feature is already enabled.');
			$this->assertEquals(1, Feature::first()->enabled);
		}

		/** @test */
		public function it_cannot_disable_a_disabled_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature',
				'--dev'   => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertCount(1, Feature::all());
			$this->assertEquals(0, Feature::first()->enabled);
			$this->artisan('feature:disable', [
				'feature' => 'NewFeature'])->expectsOutput('The feature is already disabled.');
			$this->assertEquals(0, Feature::first()->enabled);
		}

		/** @test */
		public function a_free_user_can_use_a_free_feature () {

			$this->artisan('feature:add', ['feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(Feature::can('NewFeature'));
		}

		/** @test */
		public function a_free_user_can_not_use_a_premium_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertFalse(Feature::can('NewFeature'));
		}

		/** @test */
		public function a_premium_user_can_use_a_free_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(Feature::can('NewFeature', true));
		}

		/** @test */
		public function a_premium_user_can_use_a_premium_feature () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(Feature::can('NewFeature', true));
		}

		/** @test */
		public function it_knows_how_many_times_it_has_been_used () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertEquals(0, Feature::first()->number_of_times_accessed);
			Feature::can('NewFeature', true);
			$this->assertEquals(1, Feature::first()->number_of_times_accessed);
		}

		/** @test */
		public function it_can_reset_its_usage_counter () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			Feature::can('NewFeature', true);
			$this->assertEquals(1, Feature::first()->number_of_times_accessed);
			$this->artisan('feature:reset', [
				'feature' => 'NewFeature'])
				 ->expectsQuestion('Are you sure you wish to reset the usage counter for this feature? Enter the feature name to confirm', 'NewFeature');
			$this->assertEquals(0, Feature::first()->number_of_times_accessed);
		}

		/** @test */
		public function it_can_reset_all_usage_counters () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');

			$this->artisan('feature:add', [
				'feature' => 'NewFeature2'])->expectsQuestion('Enter a short description of the feature', 'testing');

			Feature::can('NewFeature', true);
			Feature::can('NewFeature2', true);

			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature')->first()->number_of_times_accessed);
			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature2')->first()->number_of_times_accessed);

			$this->artisan('feature:resetall')
				 ->expectsQuestion('Are you sure you wish to reset the usage counter for all feature? Enter \'resetall\' to confirm', 'resetall');

			$this->assertEquals(0, Feature::where('name', '=', 'NewFeature')->first()->number_of_times_accessed);
			$this->assertEquals(0, Feature::where('name', '=', 'NewFeature2')->first()->number_of_times_accessed);
		}

		/** @test */
		public function it_can_show_the_number_of_times_it_has_been_used () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');

			Feature::can('NewFeature', true);
			Feature::can('NewFeature', true);
			Feature::can('NewFeature', true);

			$this->artisan('feature:usage', [
				'feature' => 'NewFeature'])->expectsOutput('NewFeature has been used 3 times.');
		}

		/** @test */
		public function it_can_only_reset_all_usage_counters_if_the_prompt_is_passed () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');

			$this->artisan('feature:add', [
				'feature' => 'NewFeature2'])->expectsQuestion('Enter a short description of the feature', 'testing');

			Feature::can('NewFeature', true);
			Feature::can('NewFeature2', true);

			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature')->first()->number_of_times_accessed);
			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature2')->first()->number_of_times_accessed);

			$this->artisan('feature:resetall')
				 ->expectsQuestion('Are you sure you wish to reset the usage counter for all feature? Enter \'resetall\' to confirm', 'no')
				 ->expectsOutput('The feature usage counters have not been reset.');

			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature')->first()->number_of_times_accessed);
			$this->assertEquals(1, Feature::where('name', '=', 'NewFeature2')->first()->number_of_times_accessed);
		}

		/** @test */
		public function it_can_only_reset_its_usage_counter_if_the_prompt_is_passed () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			Feature::can('NewFeature', true);
			$this->assertEquals(1, Feature::first()->number_of_times_accessed);
			$this->artisan('feature:reset', [
				'feature' => 'NewFeature'])
				 ->expectsQuestion('Are you sure you wish to reset the usage counter for this feature? Enter the feature name to confirm', 'no')
				 ->expectsOutput('The feature usage counter has not been reset.');
			$this->assertEquals(1, Feature::first()->number_of_times_accessed);
		}

		/** @test */
		public function a_free_user_can_not_use_a_premium_feature_using_cannot_function () {

			$this->artisan('feature:add', [
				'feature'   => 'NewFeature',
				'--premium' => 'true'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertTrue(Feature::cannot('NewFeature'));
		}

		/** @test */
		public function a_user_can_get_the_description_of_a_feature () {

			$this->artisan('feature:add', [
				'feature' => 'NewFeature'])->expectsQuestion('Enter a short description of the feature', 'testing');
			$this->assertEquals('testing', Feature::description('NewFeature'));
		}
	}