const gulp = require('gulp');
const SavePhpSettings = require('./util/save-php-settings');
const DeliveryDates = require('./util/delivery-dates/delivery-dates');

gulp.task('save-php-settings', cb => {
	SavePhpSettings().then(cb);
});

gulp.task('parse-delivery-dates', cb => {
	DeliveryDates().then(cb);
});
