export interface IPointsHandbookFinderPoint {
	id: number;
	index: number;
	location: {
		latitude: number;
		longitude: number;
		address: string;
		full_address: string;
		way: string;
	};
}
