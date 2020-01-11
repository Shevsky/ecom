const { TypescriptParser } = require('typescript-parser');
const parser = new TypescriptParser();
const fs = require('fs');
const defaultSettings = require('./default-settings');

const SavePhpSettings = () => {
	return new Promise(resolve => {
		parser.parseFile('../webpack_js/src/modules/settings/declares.d.ts').then((output) => {
			const ISettings = output.declarations.find((declaration) => declaration.name === 'ISettings');
			if (!ISettings) {
				return;
			}

			const phpFields = ISettings.properties.map(property => {
				let value = "''";
				if (property.name in defaultSettings) {
					value = defaultSettings[property.name];
				}

				let phpField = `\t'${property.name}' => [`;
				phpField += `
		'value' => ${value},`;
				if (property.type === 'boolean') {
					phpField += `
		'control_type' => 'checkbox',`;
				}
				phpField += `
	]`;

				return phpField;
			});

			fs.writeFileSync('../lib/config/settings.php', `<?php

return [
${phpFields.join(",\n")}
];`);
		});

		resolve();
	});
};

module.exports = SavePhpSettings;
