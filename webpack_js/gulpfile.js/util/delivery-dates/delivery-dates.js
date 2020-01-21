const fs = require('fs');

const merge = require('lodash/merge');

const regionFormatter = require('./region-formatter');
const path = (file) => `./gulpfile.js/util/delivery-dates/${file}`;

const isOneWordRegion = (region) => (['Москва', 'Санкт-Петербург'].includes(region));
const isFewWordsCity = (city) => (['Набережные Челны', 'Нижний Новгород'].includes(city));

const parsePage = (page, pageNumber) => {
	const countFrom = page.length - 1;
	const countTo = page[1].match(/(\d\s?)*$/)[0].split(' ').length;
	const toRegions = [];
	const toCities = [];

	const isRegionsNotFilled = () => toRegions.length < countTo;
	const isCitiesNotFilled = () => toCities.length < countTo;

	const tos = page[0].split(' ');

	let isSkip = false;

	tos.forEach((to, index) => {
		if (isSkip) {
			isSkip = false;
			return;
		}

		if (isRegionsNotFilled()) {
			if (isOneWordRegion(to)) {
				toRegions.push(to);
			} else {
				toRegions.push(`${to} ${tos[index + 1]}`);
				isSkip = true;
			}
		} else if (isCitiesNotFilled()) {
			const tryFewWordsCity = `${to} ${tos[index + 1]}`;

			if (isFewWordsCity(tryFewWordsCity)) {
				toCities.push(tryFewWordsCity);
				isSkip = true;
			} else {
				toCities.push(to);
			}
		}
	});

	const returnTest = [...toRegions, ...toCities].join(' ');
	if (returnTest !== page[0]) {
		console.warn(page[0]);
		console.warn(returnTest);
		console.warn('Regions');
		console.warn(...toRegions);
		console.warn('Cities');
		console.warn(...toCities);

		throw new Error();
	}

	if (toRegions.length !== countTo || toCities.length !== countTo) {
		throw new Error();
	}

	const table = {};

	for(let i = 1; i <= countFrom; i++) {
		const matches = page[i].match(/^(?:\d+\s)([а-я0-9\-\s]*?)([\d\s]*)$/i);
		if (!matches || !matches[1] || !matches[2]) {
			console.warn('NO MATCHES', page[i]);
			return;
		}

		const [, from, daysStr] = matches;

		const days = daysStr.split(' ').filter(raw => raw !== '');

		let isSkip = false;

		const fromArr = from.split(' ');
		let fromRegion = '';
		let fromCity = '';

		fromArr.forEach((fromEntity, index) => {
			if (isSkip) {
				isSkip = false;
				return;
			}

			if (!fromRegion) {
				if (isOneWordRegion(fromEntity)) {
					fromRegion = fromEntity;
				} else {
					fromRegion = `${fromEntity} ${fromArr[index + 1]}`;
					isSkip = true;
				}
			} else if (!fromCity) {
				const tryFewWordsCity = `${fromEntity} ${fromArr[index + 1]}`;

				if (isFewWordsCity(tryFewWordsCity)) {
					fromCity = tryFewWordsCity;
					isSkip = true;
				} else {
					fromCity = fromEntity;
				}
			}
		});

		if (!(fromRegion in table)) {
			table[fromRegion] = {};
		}

		if (!(fromCity in table[fromRegion])) {
			table[fromRegion][fromCity] = {};
		}

		// from_region->from_city->to_region->to_city->[min, max]

		days.forEach((day, index) => {
			const toRegion = toRegions[index];
			const toCity = toCities[index];

			if (!(toRegion in table[fromRegion][fromCity])) {
				table[fromRegion][fromCity][toRegion] = {};
			}

			if (!(toCity in table[fromRegion][fromCity][toRegion])) {
				table[fromRegion][fromCity][toRegion][toCity] = [];
			}

			if (+day && !isNaN(+day)) {
				table[fromRegion][fromCity][toRegion][toCity].push(+day);
				table[fromRegion][fromCity][toRegion][toCity] = table[fromRegion][fromCity][toRegion][toCity].sort();
			}
		});
	}


	if (!table) {
		throw new Error(pageNumber);
	}

	return table;
};

const parse = data => {
	const rows = data.split('\n');
	const pages = [];
	let tempPage = [];
	rows.forEach((row, index) => {
		if (row.startsWith('Мин Макс')) {
			tempPage.push(rows[index - 1]);
		} else if (tempPage.length !== 0) {
			if (/^([0-9]+)\s([а-я]+)/i.test(row)) {
				tempPage.push(row);
			} else {
				pages.push(tempPage);
				tempPage = [];
			}
		}
	});

	return pages.map(parsePage);
};

const DeliveryDates = () => new Promise(resolve => {
	fs.readFile(path(`pdf-content`), 'utf8', (_, data) => {
		const parsed = parse(data);
		const tableNamed = merge(...parsed);

		const table = {};
		Object.entries(tableNamed).forEach(([fromRegionName, data]) => {
			const fromRegionCode = regionFormatter(fromRegionName);

			if (!(fromRegionCode in table)) {
				table[fromRegionCode] = {};
			}

			Object.entries(data).forEach(([fromCityName, _data]) => {
				if (!(fromCityName in table[fromRegionCode])) {
					table[fromRegionCode][fromCityName] = {};
				}

				Object.entries(_data).forEach(([toRegionName, __data]) => {
					const toRegionCode = regionFormatter(toRegionName);

					table[fromRegionCode][fromCityName][toRegionCode] = __data;
				});
			});
		});

		fs.writeFileSync('../lib/config/data/delivery_interval_handbook.json', JSON.stringify(table));

		resolve();
	});
});

module.exports = DeliveryDates;
