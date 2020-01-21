export interface ICountry {
	iso3letter: string;
	name: string;
}

export type TRegions = {
	entities: Record<string, string>;
	order: string[];
};
