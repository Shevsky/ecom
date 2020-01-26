import { ComponentType } from 'react';
import { BehaviorSubject, Subject } from 'rxjs';
import { LocationHandbook } from 'util/location-handbook';
import { TGlobalBehaviorSubjects, TGlobalModel } from './global.type';

export class GlobalModel implements TGlobalModel {
	isNavigationDisabled: BehaviorSubject<TGlobalBehaviorSubjects['isNavigationDisabled']>;
	pointsHandbookCount: BehaviorSubject<TGlobalBehaviorSubjects['pointsHandbookCount']>;
	tabController: Subject<ComponentType>;
	locationHandbook: LocationHandbook;

	constructor(params: Omit<IParams, 'settings'>) {
		this.isNavigationDisabled = new BehaviorSubject(false);
		this.pointsHandbookCount = new BehaviorSubject(params.points_handbook_count);
		this.tabController = new Subject();
		this.locationHandbook = new LocationHandbook(params.countries, params.get_regions_url);
	}
}
