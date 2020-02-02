const { exec, spawn } = require('child_process');

const waPhpRunner = 'C:/OS/domains/ss8.local/wa.php';

const Build = () => {
	return new Promise(resolve => {
		console.log('Generating composer autoload...');

		exec('cd .. && composer dumpautoload -o', (error, stdout, stderr) => {
			if (error) {
				console.error(error);
				return resolve();
			} else if (stderr) {
				console.log(stderr);
			}

			console.log(stdout);

			console.log('Starting compress...');

			const child = spawn('php', `${waPhpRunner} compress wa-plugins/shipping/ecom -style true -skip test`.split(' '));

			child.stdout.on('data', function (data) {
				console.log(''+data);
			});

			child.stderr.on('data', function (data) {
				console.error(''+data);
			});

			child.on('close', function (code) {
				console.log('child process exited with code ' + code);

				resolve();
			});
		});
	});
};

module.exports = Build;
