import { ICountry, TRegions } from './location-handbook.type';

interface IGetRegionsResponse {
	data?: {
		options?: Record<string, string>;
		oOrder?: string[];
	};
}

export class LocationHandbook {
	countries: ICountry[];

	private getRegionsUrl: string;
	private regions: {
		[countryCode: string]: TRegions;
	} = {};

	constructor(countries: ICountry[], getRegionsUrl: string) {
		this.countries = countries;
		this.getRegionsUrl = getRegionsUrl;
	}

	getRegions(countryCode: string): Promise<TRegions> {
		if (!(countryCode in this.regions)) {
			return new Promise((resolve: (regions: TRegions) => void, reject: VoidFunction) => {
				if (!this.getRegionsUrl) {
					reject();
				}

				$.get(
					this.getRegionsUrl,
					{
						country: countryCode
					},
					(response: IGetRegionsResponse): void => {
						if (!response || !response.data || !('options' in response.data)) {
							reject();
						} else {
							const regions = {
								entities: response.data.options,
								order: 'oOrder' in response.data ? response.data.oOrder : []
							};

							this.regions[countryCode] = regions;
							resolve(regions);
						}
					},
					'json'
				).fail((): void => reject());
			});
		} else {
			return Promise.resolve(this.regions[countryCode]);
		}
	}
}
