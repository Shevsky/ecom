const gulp = require('gulp');
const SavePhpSettings = require('./util/save-php-settings');
const DeliveryDates = require('./util/delivery-dates/delivery-dates');
const Build = require('./build');

gulp.task('save-php-settings', cb => {
	SavePhpSettings().then(cb);
});

gulp.task('parse-delivery-dates', cb => {
	DeliveryDates().then(cb);
});

gulp.task('build', cb => {
	console.log('Saving PHP settings...');
	SavePhpSettings().then(() => {
		console.log('PHP settings saved successfully');

		Build().then(cb)
	});
});
