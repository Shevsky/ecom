const gulp = require('gulp');
const SavePhpSettings = require('./util/save-php-settings');

gulp.task('save-php-settings', cb => {
	SavePhpSettings().then(cb);
});
