import { BehaviorSubject } from 'rxjs';
import { TGlobalParams, TGlobalParamsModel } from './global-params.type';

export class GlobalParamsModel implements TGlobalParamsModel {
	isNavigationDisabled: BehaviorSubject<TGlobalParams['isNavigationDisabled']>;
	pointsHandbookCount: BehaviorSubject<TGlobalParams['pointsHandbookCount']>;

	constructor(params: Omit<IParams, 'settings'>) {
		this.isNavigationDisabled = new BehaviorSubject(false);
		this.pointsHandbookCount = new BehaviorSubject(params.points_handbook_count);
	}
}
