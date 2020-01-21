const regionToRegionCodePool = {
	'Адыгея': '01',
	'Башкортостан': '02',
	'Бурятия': '03',
	'Алтай': '04',
	'Алтайский': '04',
	'Дагестан': '05',
	'Ингушетия': '06',
	'Кабардино-Балкарская': '07',
	'Калмыкия': '08',
	'Карачаево-Черкесская': '09',
	'Карелия': '10',
	'Коми': '11',
	'Марий Эл': '12',
	'Мордовия': '13',
	'Саха (Якутия)': '14',
	'Северная Осетия-Алания': '15',
	'Татарстан': '16',
	'Тыва': '17',
	'Удмуртская': '18',
	'Удмуртия': '18',
	'Хакасия': '19',
	'Чеченская': '20',
	'Чувашская': '21',
	'Алтайский': '22',
	'Краснодарский': '23',
	'Красноярский': '24',
	'Приморский': '25',
	'Ставропольский': '26',
	'Хабаровский': '27',
	'Амурская': '28',
	'Архангельская': '29',
	'Астраханская': '30',
	'Белгородская': '31',
	'Брянская': '32',
	'Владимирская': '33',
	'Волгоградская': '34',
	'Вологодская': '35',
	'Воронежская': '36',
	'Ивановская': '37',
	'Иркутская': '38',
	'Калининградская': '39',
	'Калужская': '40',
	'Камчатский': '41',
	'Кемеровская': '42',
	'Кировская': '43',
	'Костромская': '44',
	'Курганская': '45',
	'Курская': '46',
	'Ленинградская': '47',
	'Липецкая': '48',
	'Магаданская': '49',
	'Московская': '50',
	'Мурманская': '51',
	'Нижегородская': '52',
	'Новгородская': '53',
	'Новосибирская': '54',
	'Омская': '55',
	'Оренбургская': '56',
	'Орловская': '57',
	'Пензенская': '58',
	'Пермский': '59',
	'Псковская': '60',
	'Ростовская': '61',
	'Рязанская': '62',
	'Самарская': '63',
	'Саратовская': '64',
	'Сахалинская': '65',
	'Свердловская': '66',
	'Смоленская': '67',
	'Тамбовская': '68',
	'Тверская': '69',
	'Томская': '70',
	'Тульская': '71',
	'Тюменская': '72',
	'Ульяновская': '73',
	'Челябинская': '74',
	'Забайкальский': '75',
	'Ярославская': '76',
	'Москва': '77',
	'Санкт-Петербург': '78',
	'Еврейская автономная': '79',
	'Ненецкий автономный округ': '83',
	'Ханты-Мансийский-Югра автономный округ': '86',
	'Чукотский автономный округ': '87',
	'Ямало-Ненецкий автономный округ': '89',
	'Крым': '91',
	'Севастополь': '92'
};

module.exports = (rawRegion) => {
	let regionName = rawRegion.trim();
	regionName = regionName.replace(/(область|республика|край)/i, '');
	regionName = regionName.trim();

	if (regionName in regionToRegionCodePool) {
		return regionToRegionCodePool[regionName];
	}

	throw new Error('undefined region name: ' + rawRegion + ' (trying ' + regionName + ')');
};
