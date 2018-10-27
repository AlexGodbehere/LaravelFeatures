<?php
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateFeaturesTable extends Migration {

		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up () {

			Schema::create('features', function(Blueprint $table) {

				$table->increments('id');
				$table->timestamps();
				$table->string('name')->unique();
				$table->text('description')->nullable();
				$table->boolean('premium')->default(false);
				$table->boolean('enabled')->default(true);
				$table->bigInteger('number_of_times_accessed')->default(0);

				$table->softDeletes();
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down () {

			Schema::dropIfExists('users');
		}
	}
