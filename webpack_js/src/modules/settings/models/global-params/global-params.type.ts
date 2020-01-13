import { BehaviorSubject } from 'rxjs';

export type TGlobalParams = {
	isNavigationDisabled: boolean;
	pointsHandbookCount: IParams['points_handbook_count'];
};

export type TGlobalParamsModel = { [K in keyof TGlobalParams]: BehaviorSubject<TGlobalParams[K]> };
