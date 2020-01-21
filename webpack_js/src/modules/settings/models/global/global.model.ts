import { BehaviorSubject } from 'rxjs';
import { LocationHandbook } from 'util/location-handbook';
import { TGlobalBehaviorSubjects, TGlobalModel } from './global.type';

export class GlobalModel implements TGlobalModel {
	isNavigationDisabled: BehaviorSubject<TGlobalBehaviorSubjects['isNavigationDisabled']>;
	pointsHandbookCount: BehaviorSubject<TGlobalBehaviorSubjects['pointsHandbookCount']>;
	locationHandbook: LocationHandbook;

	constructor(params: Omit<IParams, 'settings'>) {
		this.isNavigationDisabled = new BehaviorSubject(false);
		this.pointsHandbookCount = new BehaviorSubject(params.points_handbook_count);
		this.locationHandbook = new LocationHandbook(params.countries, params.get_regions_url);
	}
}
