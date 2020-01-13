import { Observable, Subject, timer } from 'rxjs';

interface IRunnerResponse {
	process_id?: string;
	error?: string;
}

interface IProcessInfo {
	ready?: boolean;
	error?: string;
}

type TProcessResponse<T> = IProcessInfo & T;

const PROCESS_COMMON_DELAY = 1000;
const PROCESS_FAIL_DELAY = 2000;

export class LongActionRequest<TProcessData extends object, TFinishResponse extends object> {
	private url: string;
	private params: object;

	private storeProcessId$: Subject<string> = new Subject();
	private processId$: Observable<string> = this.storeProcessId$.asObservable();
	private processId?: string;
	private isCancelled: boolean = false;

	private storeProcess$: Subject<TProcessData> = new Subject();
	process$: Observable<TProcessData> = this.storeProcess$.asObservable();

	private storeFinish$: Subject<TFinishResponse> = new Subject();
	finish$: Observable<TFinishResponse> = this.storeFinish$.asObservable();

	private storeTerminateTap$: Subject<void> = new Subject();
	terminateTap$: Observable<void> = this.storeTerminateTap$.asObservable();

	private storeError$: Subject<string> = new Subject();
	error$: Observable<string> = this.storeError$.asObservable();

	constructor(url: string, params: object = {}) {
		this.url = url;
		this.params = params;

		this.processId$.subscribe(this.handleProcessId);
	}

	run(): void {
		this.isCancelled = false;

		$.post(this.url, this.params, this.handleRunResponse, 'json').fail(
			(): void => this.terminate()
		);
	}

	cancel(): void {
		this.isCancelled = true;
		this.terminate();
	}

	private terminate(error?: string): void {
		if (error) {
			this.storeError$.next(error);
		}

		this.storeTerminateTap$.next();

		this.processId = undefined;
	}

	private process(delay: number = PROCESS_COMMON_DELAY): void {
		if (!this.processId) {
			return;
		}

		const timer$ = timer(delay);
		timer$.subscribe(
			(): void => {
				$.post(
					this.url,
					{
						processId: this.processId
					},
					(response?: TProcessResponse<TProcessData>): void => {
						this.handleProcessResponse(response);
					},
					'json'
				).fail(
					(): void => {
						this.process(PROCESS_FAIL_DELAY);
					}
				);
			}
		);
	}

	private finish(): void {
		if (!this.processId) {
			return;
		}

		$.post(
			this.url,
			{
				process_id: this.processId,
				cleanup: true
			},
			this.handleFinishResponse,
			'json'
		).fail((): void => this.terminate());
	}

	private handleProcessId = (processId: string): void => {
		this.processId = processId;
		this.process();
	};

	private handleRunResponse = (response: IRunnerResponse): void => {
		if (this.isCancelled) {
			return;
		}

		if (response && response.process_id) {
			this.storeProcessId$.next(response.process_id);
		} else if (response && response.error) {
			this.terminate(response.error);
		} else {
			this.terminate();
		}
	};

	private handleProcessResponse = (response?: TProcessResponse<TProcessData>): void => {
		if (!this.processId) {
			return;
		}

		if (!response) {
			return this.process(PROCESS_FAIL_DELAY);
		}

		if (response.ready) {
			this.storeProcess$.next(response as TProcessData);
			this.finish();
		} else if (response.error) {
			this.terminate(response.error);
		} else {
			this.storeProcess$.next(response as TProcessData);
			this.process();
		}
	};

	private handleFinishResponse = (response: TFinishResponse): void => {
		if (!this.processId) {
			return;
		}

		this.storeFinish$.next(response);
		this.processId = undefined;
	};
}
