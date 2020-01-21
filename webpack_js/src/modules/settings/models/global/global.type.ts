import { BehaviorSubject } from 'rxjs';
import { LocationHandbook } from 'util/location-handbook';

export type TGlobalBehaviorSubjects = {
	isNavigationDisabled: boolean;
	pointsHandbookCount: IParams['points_handbook_count'];
};

export type TGlobalModel = {
	[K in keyof TGlobalBehaviorSubjects]: BehaviorSubject<TGlobalBehaviorSubjects[K]>
} & {
	locationHandbook: LocationHandbook;
};
